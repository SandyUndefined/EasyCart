<?php

namespace App\Http\Controllers;

use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function productDetail($id)
    {
        $cart = session()->get('cart', []);
        return view('products.detail', ['id' => $id, 'cart' => $cart]);
    }

    public function productList($type, $id)
    {
        return view('products.list', ['type' => $type, 'id' => $id]);
    }

    public function cart()
    {


        return view('checkout');
    }

    public function productListAll()
    {
        return view('products.list_arrivals');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart(Request $request)
    {

        $req = $request->all();
        $id = $req['id'];
        $restaurant_id = $req['restaurant_id'];
        $cart = Session::get('cart', []);
        if (@$cart['item'] && isset($cart['item'][$restaurant_id])) {

        } else {

            $cart['item'] = array();
            Session::put('cart', $cart);
            Session::save();
        }

        if (@$req['deliveryCharge']) {
            $cart['deliverychargemain'] = $req['deliveryCharge'];
        }
        $cart['restaurant_latitude'] = $req['restaurant_latitude'];
        $cart['restaurant_longitude'] = $req['restaurant_longitude'];
        $deliveryChargemain = @$_COOKIE['deliveryChargemain'];
        $address_lat = @$_COOKIE['address_lat'];
        $address_lng = @$_COOKIE['address_lng'];
        $restaurant_latitude = @$_COOKIE['restaurant_latitude'];
        $restaurant_longitude = @$_COOKIE['restaurant_longitude'];

        if (@$deliveryChargemain && @$address_lat && @$address_lng && @$restaurant_latitude && @$restaurant_longitude) {
            $deliveryChargemain = json_decode($deliveryChargemain);
            if (!empty($deliveryChargemain)) {
                $delivery_charges_per_km = $deliveryChargemain->delivery_charges_per_km;
                $minimum_delivery_charges = $deliveryChargemain->minimum_delivery_charges;
                $minimum_delivery_charges_within_km = $deliveryChargemain->minimum_delivery_charges_within_km;
                $kmradius = $this->distance($address_lat, $address_lng, $restaurant_latitude, $restaurant_longitude, 'K');
                if ($minimum_delivery_charges_within_km > $kmradius) {
                    $cart['deliverychargemain'] = $minimum_delivery_charges;
                } else {
                    $cart['deliverychargemain'] = round(($kmradius * $delivery_charges_per_km), 2);
                }
                $cart['deliverykm'] = $kmradius;

            }
        }


        if (Session::get('takeawayOption') == "true") {
            $req['delivery_option'] = "takeaway";
        } else {
            $req['delivery_option'] = "delivery";
        }
        if (@$req['delivery_option'] == "delivery") {
            $cart['deliverycharge'] = @$cart['deliverychargemain'];
        } else {
            $cart['deliverycharge'] = 0;
            $cart['tip_amount'] = 0;
        }
        $cart['delivery_option'] = $req['delivery_option'];

        $cart['tip_amount'] = 0;
        /*by GA*/
        if (isset($req['variant_info']) && !empty($req['variant_info']['variant_id'])) {
            $id = $id . 'PV' . $req['variant_info']['variant_id'];
        }
        $cart['item'][$restaurant_id][$id] = [
            "name" => $req['name'],
            "quantity" => $req['quantity'],
            "stock_quantity" => $req['stock_quantity'],
            "item_price" => $req['item_price'],
            "price" => $req['price'],
            "dis_price" => $req['dis_price'],
            "extra_price" => $req['extra_price'],
            "extra" => @$req['extra'],
            "size" => @$req['size'],
            "image" => @$req['image'],
            "veg" => @$req['veg'],
            "iteam_extra_price" => @$req['iteam_extra_price'],
            "variant_info" => @$req['variant_info'],
            "category_id" => @$req['category_id'],
        ];
        $cart['restaurant']['id'] = @$restaurant_id;
        $cart['restaurant']['name'] = @$req['restaurant_name'];
        $cart['restaurant']['location'] = @$req['restaurant_location'];
        $cart['restaurant']['image'] = @$req['restaurant_image'];

        $cart['taxValue'] = @$req['taxValue'];
        $tax = 0;
        $tax_label = '';
        $total_item_price = 0;
        foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {

            $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
            if (@$value_cart['extra_price']) {
                $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
            }
            $total_item_price = $total_item_price + $total_one_item_price;
        }

        $discount_amount = 0;
        /*Disctount*/
        if (@$cart['coupon'] && $cart['coupon']['discountType']) {
            $discountType = $cart['coupon']['discountType'];
            $coupon_code = $cart['coupon']['coupon_code'];
            $coupon_id = @$cart['coupon']['coupon_id'];
            $discount = $cart['coupon']['discount'];
            if ($discountType == "Fix Price") {
                $discount_amount = $cart['coupon']['discount'];
                if ($discount_amount > $total_item_price) {
                    $discount_amount = $total_item_price;
                }
            } else {
                $discount_amount = $cart['coupon']['discount'];
                $discount_amount = ($total_item_price * $discount_amount) / 100;
                if ($discount_amount > $total_item_price) {
                    $discount_amount = $total;
                }
            }
        }

        /*Special Offer Disctount*/
        $specialOfferDiscount = 0;
        $specialOfferType = '';
        $specialOfferDiscountVal = 0;
        if (@$req['specialOfferForHour']) {
            $specialOfferForHour = $req['specialOfferForHour'];
            if (count($specialOfferForHour) > 0) {


                foreach ($specialOfferForHour as $key => $value) {
                    $specialOfferType = $value['type'];
                    $specialOfferDiscountVal = $value['discount'];

                    if ($value['type'] == 'percentage') {

                        $specialOfferDiscount = ($total_item_price * $value['discount']) / 100;

                    } else {
                        $specialOfferDiscount = $value['discount'];

                    }
                }


            }
        }

        $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

        $cart['specialOfferDiscount'] = $specialOfferDiscount;
        $cart['specialOfferDiscountVal'] = $specialOfferDiscountVal;
        $cart['specialOfferType'] = $specialOfferType;
        $totalTaxAmount = 0;
        if (is_array($cart['taxValue'])) {

            foreach ($cart['taxValue'] as $val) {
                if ($val['type'] == 'percentage') {
                    $tax = ($val['tax'] * $total_item_price) / 100;
                } else {
                    $tax = $val['tax'];
                }
                $totalTaxAmount += floatval($tax);
            }
            $tax = $totalTaxAmount;
            $tax_label = '';
           
        }
        $cart['tax_label'] = $tax_label;
        $cart['tax'] = $tax;

        $cart['decimal_degits'] = $req['decimal_degits'];
        Session::put('cart', $cart);
        Session::save();
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        echo json_encode($res);
        exit;
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function reorderaddToCart(Request $request)
    {

        $req = $request->all();
        $vendor_id = $req['restaurant_id'];
        $cart = Session::get('cart', []);

        
        $cart['item'] = array();
        Session::put('cart', $cart);
        Session::save();
    
        if (@$req['deliveryCharge']) {
            $cart['deliverychargemain'] = $req['deliveryCharge'];
        } else {
            $cart['deliverychargemain'] = 0;
        }

        if (Session::get('takeawayOption') == "true") {
            $req['delivery_option'] = "takeaway";
        } else {
            $req['delivery_option'] = "delivery";
        }
        if (@$req['delivery_option'] == "delivery") {
            $cart['deliverycharge'] = @$cart['deliverychargemain'];
        } else {
            $cart['deliverycharge'] = 0;
            $cart['tip_amount'] = 0;
        }
        $cart['delivery_option'] = $req['delivery_option'];
        $cart['tip_amount'] = 0;
        foreach ($req['item'] as $key => $value) {

            $id = 0;
            $name = '';
            $quantity = 0;
            $item_price = 0;
            $price = 0;
            $extra_price = 0;
            $extra = '';
            $size = 0;
            $image = '';
            if ($value['id']) {
                $id = $value['id'];
            }
            if ($value['name']) {
                $name = $value['name'];
            }
            if ($value['quantity']) {
                $quantity = $value['quantity'];
            }
            
            if ($value['item_price']) {
                $item_price = $value['item_price'];
            }
            if ($value['price']) {
                $price = $value['price'];
            }
            if ($value['extra_price']) {
                $extra_price = $value['extra_price'];
            }
            if ($value['extra']) {
                $extra = explode(',', $value['extra']);
            }
            if ($value['size']) {
                $size = $value['size'];
            }
            if ($value['image']) {
                $image = $value['image'];
            }

            /*by thm*/
            if (isset($req['variant_info']) && !empty($req['variant_info']['variant_id'])) {
                $id = $id . 'PV' . $req['variant_info']['variant_id'];
            }

            $cart['item'][$vendor_id][$id] = [
                "name" => @$name,
                "quantity" => @$quantity,
                "stock_quantity" => @$req['stock_quantity'],

                "item_price" => @$item_price,
                "price" => ($quantity * $price),
                "dis_price" => @$req['dis_price'],
                "extra_price" => ($quantity * $extra_price),
                "extra" => @$extra,
                "size" => @$size,
                "image" => @$image,
                "variant_info" => @$req['variant_info'],
                "category_id" => @$value['category_id'],
            ];

        }

        $cart['restaurant']['id'] = @$vendor_id;
        $cart['restaurant']['name'] = @$req['restaurant_name'];
        $cart['restaurant']['location'] = @$req['restaurant_location'];
        $cart['restaurant']['image'] = @$req['restaurant_image'];


        $cart['taxValue'] = @$req['taxValue'];
        $tax = 0;
        $tax_label = '';
        $total_item_price = 0;
        foreach ($cart['item'][$vendor_id] as $key_cart => $value_cart) {

            $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
            if ($value_cart['extra_price']) {
                $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
            }
            $total_item_price = $total_item_price + $total_one_item_price;
        }
        $discount_amount = 0;
        /*Special Offer Disctount*/
        $specialOfferDiscount = 0;
        $specialOfferType = '';
        $specialOfferDiscountVal = 0;
        if (@$req['specialOfferForHour']) {
            $specialOfferForHour = $req['specialOfferForHour'];
            if (count($specialOfferForHour) > 0) {


                foreach ($specialOfferForHour as $key => $value) {
                    $specialOfferType = $value['type'];
                    $specialOfferDiscountVal = $value['discount'];

                    if ($value['type'] == 'percentage') {

                        $specialOfferDiscount = ($total_item_price * $value['discount']) / 100;

                    } else {
                        $specialOfferDiscount = $value['discount'];

                    }
                }


            }
        }

        $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

        $cart['specialOfferDiscount'] = $specialOfferDiscount;
        $cart['specialOfferDiscountVal'] = $specialOfferDiscountVal;
        $cart['specialOfferType'] = $specialOfferType;

        $totalTaxAmount = 0;
        if (is_array($cart['taxValue'])) {

            foreach ($cart['taxValue'] as $val) {
                if ($val['type'] == 'percentage') {
                    $tax = ($val['tax'] * $total_item_price) / 100;
                } else {
                    $tax = $val['tax'];
                }
                $totalTaxAmount += floatval($tax);
            }
            $tax = $totalTaxAmount;
            $tax_label = '';
          
        }
        $cart['tax_label'] = $tax_label;
        $cart['tax'] = $tax;
        $cart['decimal_degits'] = $req['decimal_degits'];

        Session::put('cart', $cart);
        Session::save();
        $res = array('status' => true);
        echo json_encode($res);
        exit;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function orderTipAdd(Request $request)
    {

        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['tip_amount'] = $req['tip'];
        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }

        echo json_encode($res);
        exit;

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function orderDeliveryOption(Request $request)
    {

        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['delivery_option'] = $req['delivery_option'];
        if ($req['delivery_option'] == "takeaway") {
            //deliveryCharge
            $cart['tip_amount'] = 0;
            $cart['deliverycharge'] = 0;
        } else {
            //delivery
            if (isset($cart['deliverychargemain'])) {
                $cart['deliverycharge'] = $cart['deliverychargemain'];
            } else if (isset($req['deliveryCharge'])) {
                $cart['deliverychargemain'] = $req['deliveryCharge'];
                $cart['deliverycharge'] = $cart['deliverychargemain'];
            }
        }


        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }

        echo json_encode($res);
        exit;

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function changeQuantityCart(Request $request)
    {

        $req = $request->all();
        $id = $req['id'];

        $restaurant_id = $req['restaurant_id'];

        $quantity = $req['quantity'];
        $cart = Session::get('cart');

        if (isset($cart['item'][$restaurant_id][$id])) {
            if ($req['quantity'] == 0) {

                if (isset($cart['item'][$restaurant_id][$id])) {
                    unset($cart['item'][$restaurant_id][$id]);
                    Session::put('cart', $cart);
                    Session::save();
                }

            } else {
                $cart['item'][$restaurant_id][$id]['quantity'] = $req['quantity'];
                $cart['item'][$restaurant_id][$id]['price'] = $cart['item'][$restaurant_id][$id]['item_price'] * $cart['item'][$restaurant_id][$id]['quantity'];
                

                $tax = 0;
                $tax_label = '';
                $total_item_price = 0;
                foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {

                    $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                    if (@$value_cart['extra_price']) {
                        $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                    }
                    $total_item_price = $total_item_price + $total_one_item_price;
                }

                $discount_amount = 0;
                /*Disctount*/
                if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                    $discountType = $cart['coupon']['discountType'];
                    $coupon_code = $cart['coupon']['coupon_code'];
                    $coupon_id = @$cart['coupon']['coupon_id'];
                    $discount = $cart['coupon']['discount'];
                    if ($discountType == "Fix Price") {
                        $discount_amount = $cart['coupon']['discount'];
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total_item_price;
                        }
                    } else {
                        $discount_amount = $cart['coupon']['discount'];
                        $discount_amount = ($total_item_price * $discount_amount) / 100;
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total;
                        }
                    }
                }

                /*Special Offer Disctount*/

                $specialOfferDiscount = 0;
                if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                    $specialOfferType = $cart['specialOfferType'];
                    $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];

                    if ($specialOfferType == "amount") {
                        $specialOfferDiscount = $specialOfferDiscountVal;

                    } else {

                        $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;


                    }
                }

                $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

                $totalTaxAmount = 0;
                if (is_array($cart['taxValue'])) {

                    foreach ($cart['taxValue'] as $val) {
                        if ($val['type'] == 'percentage') {
                            $tax = ($val['tax'] * $total_item_price) / 100;
                        } else {
                            $tax = $val['tax'];
                        }
                        $totalTaxAmount += floatval($tax);
                    }
                    $tax = $totalTaxAmount;
                    $tax_label = '';
                    
                }
                $cart['tax_label'] = $tax_label;
                $cart['tax'] = $tax;


                Session::put('cart', $cart);
                Session::save();
            }


        }
        $cart = Session::get('cart');

        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart, 'is_checkout' => 1])->render());
        echo json_encode($res);
        exit;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            $cart['item'][$request->id]["quantity"] = $request->quantity;
            $tax = 0;
            $tax_label = '';
            $total_item_price = 0;
            foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {

                $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                if (@$value_cart['extra_price']) {
                    $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                }
                $total_item_price = $total_item_price + $total_one_item_price;
            }

            $discount_amount = 0;
            /*Disctount*/
            if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                $discountType = $cart['coupon']['discountType'];
                $coupon_code = $cart['coupon']['coupon_code'];
                $coupon_id = @$cart['coupon']['coupon_id'];
                $discount = $cart['coupon']['discount'];
                if ($discountType == "Fix Price") {
                    $discount_amount = $cart['coupon']['discount'];
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total_item_price;
                    }
                } else {
                    $discount_amount = $cart['coupon']['discount'];
                    $discount_amount = ($total_item_price * $discount_amount) / 100;
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total;
                    }
                }
            }

            /*Special Offer Disctount*/

            $specialOfferDiscount = 0;
            if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                $specialOfferType = $cart['specialOfferType'];
                $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];

                if ($specialOfferType == "amount") {
                    $specialOfferDiscount = $cart['specialOfferDiscount'];

                } else {
                    $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;

                }
            }


            $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

            $totalTaxAmount = 0;
            if (is_array($cart['taxValue'])) {

                foreach ($cart['taxValue'] as $val) {
                    if ($val['type'] == 'percentage') {
                        $tax = ($val['tax'] * $total_item_price) / 100;
                    } else {
                        $tax = $val['tax'];
                    }
                    $totalTaxAmount += floatval($tax);
                }
                $tax = $totalTaxAmount;
                $tax_label = '';
               
            }
            $cart['tax_label'] = $tax_label;
            $cart['tax'] = $tax;

            Session::put('cart', $cart);
            Session::save();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
            echo json_encode($res);
            exit;
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public
    function applyCoupon(Request $request)
    {
        if ($request->coupon_code) {
            $cart = Session::get('cart');
            $cart['coupon']['coupon_code'] = $request->coupon_code;
            $cart['coupon']['coupon_id'] = $request->coupon_id;
            $cart['coupon']['discount'] = $request->discount;
            $cart['coupon']['discountType'] = $request->discountType;

            $tax = 0;
            $tax_label = '';
            $total_item_price = 0;
            $id = array_key_first($cart['item']);
            $restaurant_id = $id;
            if ($restaurant_id) {
                foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {

                    $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                    if (@$value_cart['extra_price']) {
                        $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                    }
                    $total_item_price = $total_item_price + $total_one_item_price;
                }

                $discount_amount = 0;
                /*Disctount*/
                if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                    $discountType = $cart['coupon']['discountType'];
                    $coupon_code = $cart['coupon']['coupon_code'];
                    $coupon_id = @$cart['coupon']['coupon_id'];
                    $discount = $cart['coupon']['discount'];
                    if ($discountType == "Fix Price") {
                        $discount_amount = $cart['coupon']['discount'];
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total_item_price;
                        }
                    } else {
                        $discount_amount = $cart['coupon']['discount'];
                        $discount_amount = ($total_item_price * $discount_amount) / 100;
                        if ($discount_amount > $total_item_price) {
                            $discount_amount = $total;
                        }
                    }
                }

                /*Special Offer Disctount*/

                $specialOfferDiscount = 0;
                if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                    $specialOfferType = $cart['specialOfferType'];
                    $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];

                    if ($specialOfferType == "amount") {
                        $specialOfferDiscount = $cart['specialOfferDiscount'];

                    } else {
                        $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;

                    }
                }


                $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

                $totalTaxAmount = 0;
                if (is_array($cart['taxValue'])) {

                    foreach ($cart['taxValue'] as $val) {
                        if ($val['type'] == 'percentage') {
                            $tax = ($val['tax'] * $total_item_price) / 100;
                        } else {
                            $tax = $val['tax'];
                        }
                        $totalTaxAmount += floatval($tax);
                    }
                    $tax = $totalTaxAmount;
                    $tax_label = '';

                }
            }
            $cart['tax_label'] = $tax_label;
            $cart['tax'] = $tax;
            Session::put('cart', $cart);
            Session::save();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
            echo json_encode($res);
            exit;
        }
    }

    public function orderComplete(Request $request)
    {

        $cart = array();
        Session::put('cart', $cart);
        Session::put('success', 'Your order has been successful!');

        if(Storage::disk('local')->has('firebase/credentials.json')){
            
            $client= new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];

            $fcm_token = $request->fcm;
            
            if(!empty($access_token) && !empty($fcm_token)){

                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';

                $data = [
                    'message' => [
                        'notification' => [
                            'title' => $request->subject,
                            'body' => $request->message,
                        ],
                        'data' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => '1',
                            'status' => 'done',
                        ],
                        'token' => $fcm_token,
                    ],
                ];

                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result=json_decode($result);

                $response = array();
                $response['success'] = true;
                $response['message'] = 'Notification successfully sent.';
                $response['result'] = $result;

            }else{
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing sender id or token to send notification.';
            }

        }else{
            $response = array();
            $response['success'] = false;
            $response['message'] = 'Firebase credentials file not found.';
        }

        Session::save();

        $order_response = array('status' => true, 'order_complete' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart, 'order_complete' => true, 'is_checkout' => 1])->render(), 'response' => $response);
       
        return response()->json($order_response);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if ($request->id && $request->restaurant_id) {
            $cart = Session::get('cart');

            if (isset($cart['item'][$request->restaurant_id][$request->id])) {

                unset($cart['item'][$request->restaurant_id][$request->id]);

                $total_item_price = 0;
                $id = array_key_first($cart['item']);
                $restaurant_id = $id;

                if ($restaurant_id) {
                    foreach ($cart['item'][$restaurant_id] as $key_cart => $value_cart) {

                        $total_one_item_price = $value_cart['item_price'] * $value_cart['quantity'];
                        if (@$value_cart['extra_price']) {
                            $total_one_item_price = $total_one_item_price + ($value_cart['extra_price'] * $value_cart['quantity']);
                        }
                        $total_item_price = $total_item_price + $total_one_item_price;
                    }

                    $discount_amount = 0;
                    /*Disctount*/

                    if (@$cart['coupon'] && $cart['coupon']['discountType']) {
                        $discountType = $cart['coupon']['discountType'];
                        $coupon_code = $cart['coupon']['coupon_code'];
                        $coupon_id = @$cart['coupon']['coupon_id'];
                        $discount = $cart['coupon']['discount'];
                        if ($discountType == "Fix Price") {
                            $discount_amount = $cart['coupon']['discount'];
                            if ($discount_amount > $total_item_price) {
                                $discount_amount = $total_item_price;
                            }

                        } else {
                            $discount_amount = $cart['coupon']['discount'];
                            $discount_amount = ($total_item_price * $discount_amount) / 100;
                            if ($discount_amount > $total_item_price) {
                                $discount_amount = $total;
                            }

                        }

                    }


                    /*Special Offer Disctount*/

                    $specialOfferDiscount = 0;
                    if (@$cart['specialOfferType'] && $cart['specialOfferType']) {
                        $specialOfferType = $cart['specialOfferType'];
                        $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];

                        if ($specialOfferType == "amount") {
                            $specialOfferDiscount = $cart['specialOfferDiscount'];

                        } else {
                            $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;

                        }
                    }


                    $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;

                    if (is_array($cart['taxValue'])) {
                        $totalTaxAmount = 0;
                        foreach ($cart['taxValue'] as $val) {
                            if ($val['type'] == 'percentage') {
                                $tax = ($val['tax'] * $total_item_price) / 100;
                            } else {
                                $tax = $val['tax'];
                            }
                            $totalTaxAmount += floatval($tax);
                        }
                        $tax = $totalTaxAmount;
                        $tax_label = '';
                       
                    }
                    if (is_array($cart['taxValue'])) {

                        foreach ($cart['taxValue'] as $val) {
                            if ($val['type'] == 'percentage') {
                                $tax = ($val['tax'] * $total_item_price) / 100;
                            } else {
                                $tax = $val['tax'];
                            }
                            $totalTaxAmount += floatval($tax);
                        }
                        $tax = $totalTaxAmount;
                        $tax_label = '';
                        

                    }
                }
            }

            Session::put('cart', $cart);
            Session::save();
        }
        $cart = Session::get('cart');
        session()->flash('success', 'Product removed successfully');
        $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        echo json_encode($res);
        exit;
    }


    public
    function orderScheduleTimeAdd(Request $request)
    {

        $req = $request->all();
        $cart = Session::get('cart', []);
        $cart['scheduleTime'] = $req['scheduleTime'];
        Session::put('cart', $cart);
        Session::save();
        if (@$req['is_checkout']) {
            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['is_checkout' => 1, 'id' => $user->uuid, 'cart' => $cart])->render());
        } else {
            $res = array('status' => true, 'html' => view('restaurant.cart_item', ['cart' => $cart])->render());
        }

        echo json_encode($res);
        exit;

    }

}
