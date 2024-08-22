<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorUsers;
use App\Models\User;
use Razorpay\Api\Api;

use Session;

class GiftCardController extends Controller
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

        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();
        return view('gift_card.giftcard')->with('id',$user->uuid);
    }
    public function giftCardProcessing(Request $request){
        $gift_cart_order = $request->all();
        $cart = array();
        Session::put('gift_cart', $cart);
        $cart = Session::get('gift_cart', []);
        $cart['gift_cart_order'] = $gift_cart_order;
        Session::put('gift_cart', $cart);
        Session::save();
        $res = array('status' => true);
        echo json_encode($res);
        exit;

    }
    public function proccesstopay(Request $request)
    {

        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('gift_cart', []);
        if (@$cart['gift_cart_order']) {
            if ($cart['gift_cart_order']['payment_method'] == 'razorpay') {
                $razorpaySecret = $cart['gift_cart_order']['razorpaySecret'];
                $razorpayKey = $cart['gift_cart_order']['razorpayKey'];
                $authorName = '';
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $amount = 0;
                return view('gift_card.razorpay', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email,'authorName'=>$authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'gift_cart_order' => $cart['gift_cart_order']]);
            } 
            else if ($cart['gift_cart_order']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $cart['gift_cart_order']['payfast_merchant_key'];
                $payfast_merchant_id = $cart['gift_cart_order']['payfast_merchant_id'];
                $payfast_isSandbox = $cart['gift_cart_order']['payfast_isSandbox'];

                $payfast_return_url = route('giftcard.success');
                $payfast_notify_url = route('notify');
                $payfast_cancel_url = route('giftcard.pay');


                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $amount = 0;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;

                return view('gift_card.payfast', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'payfast_merchant_key' => $payfast_merchant_key, 'payfast_merchant_id' => $payfast_merchant_id, 'payfast_isSandbox' => $payfast_isSandbox, 'payfast_return_url' => $payfast_return_url, 'payfast_notify_url' => $payfast_notify_url, 'payfast_cancel_url' => $payfast_cancel_url, 'gift_cart_order' => $cart['gift_cart_order']]);
            } else if ($cart['gift_cart_order']['payment_method'] == 'paystack') {

                $paystack_public_key = $cart['gift_cart_order']['paystack_public_key'];
                $paystack_secret_key = $cart['gift_cart_order']['paystack_secret_key'];
                $paystack_isSandbox = $cart['gift_cart_order']['paystack_isSandbox'];

                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $amount = 0;

                require_once(base_path() . '/paystack-php-master/vendor/autoload.php');
                define("PaystackPublicKey", $paystack_public_key);
                define("PaystackSecretKey", $paystack_secret_key);
                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $email,
                    'amount' => (int) ($total_pay * 100),
                    'callback_url'=>route('giftcard.success')
                ]);
                Session::put('paystack_authorization_url', $payment->authorization_url);
                Session::put('paystack_access_code', $payment->access_code);
                Session::put('paystack_reference', $payment->reference);
                Session::save();
                if ($payment->authorization_url) {
                    $script = "<script>window.location = '" . $payment->authorization_url . "';</script>";
                    echo $script;
                    exit;
                } else {
                    $script = "<script>window.location = '" . url('') . "';</script>";
                    echo $script;
                    exit;
                }


            } else if ($cart['gift_cart_order']['payment_method'] == 'flutterwave') {

                $currency = "USD";
                if (@$cart['gift_cart_order']['currencyData']['code']) {
                    $currency = $cart['gift_cart_order']['currencyData']['code'];
                }
                $flutterWave_secret_key = $cart['gift_cart_order']['flutterWave_secret_key'];
                $flutterWave_public_key = $cart['gift_cart_order']['flutterWave_public_key'];
                $flutterWave_isSandbox = $cart['gift_cart_order']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $cart['gift_cart_order']['flutterWave_encryption_key'];

                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];

                Session::put('flutterwave_pay', 1);
                Session::save();

                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);

                Session::save();

                return view('gift_card.flutterwave', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'gift_cart_order' => $cart['gift_cart_order'], 'currency' => $currency]);


            } else if ($cart['gift_cart_order']['payment_method'] == 'mercadopago') {

                $currency = "USD";
                if (@$cart['gift_cart_order']['currencyData']['code']) {
                    $currency = $cart['gift_cart_order']['currencyData']['code'];
                }
                $mercadopago_public_key = $cart['gift_cart_order']['mercadopago_public_key'];
                $mercadopago_access_token = $cart['gift_cart_order']['mercadopago_access_token'];
                $mercadopago_isSandbox = $cart['gift_cart_order']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $cart['gift_cart_order']['mercadopago_isEnabled'];
                $id = $cart['gift_cart_order']['id'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);

                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('giftcard.pay');
                $item['back_urls']['pending'] = route('notify');
                $item['back_urls']['success'] = route('giftcard.success');
                $item['auto_return'] = 'all';
                Session::put('mercadopago_pay', 1);
                Session::save();
                $url = "https://api.mercadopago.com/checkout/preferences";
                $data = array('Accept: application/json', 'Authorization:Bearer ' . $mercadopago_access_token);
                $post_data = json_encode($item);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $mercadopago_access_token));
                $response = curl_exec($ch);
                $mercadopago = json_decode($response);
                Session::put('mercadopago_preference_id', $mercadopago->id);
                Session::save();
                if ($mercadopago === null) {
                    die(curl_error($ch));
                }
                $authorName = '';
                $total_pay = $cart['gift_cart_order']['total_pay'];
                if ($mercadopago_isSandbox == "true") {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>location.href = '" . $payment_url . "';</script>";
                exit;

            } else if ($cart['gift_cart_order']['payment_method'] == 'stripe') {


                $stripeKey = $cart['gift_cart_order']['stripeKey'];
                $stripeSecret = $cart['gift_cart_order']['stripeSecret'];
                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $address_line1 = $cart['gift_cart_order']['address_line1'];
                $address_line2 = $cart['gift_cart_order']['address_line2'];
                $address_zipcode = $cart['gift_cart_order']['address_zipcode'];
                $address_city = $cart['gift_cart_order']['address_city'];
                $address_country = $cart['gift_cart_order']['address_country'];



                $stripeSecret = $cart['gift_cart_order']['stripeSecret'];
                $stripeKey = $cart['gift_cart_order']['stripeKey'];
                $isStripeSandboxEnabled = $cart['gift_cart_order']['isStripeSandboxEnabled'];
                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $amount = 0;
                return view('gift_card.stripe', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'gift_cart_order' => $cart['gift_cart_order']]);

            } else if ($cart['gift_cart_order']['payment_method'] == 'paypal') {

                $paypalKey = $cart['gift_cart_order']['paypalKey'];
                $paypalSecret = $cart['gift_cart_order']['paypalSecret'];
                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $address_line1 = $cart['gift_cart_order']['address_line1'];
                $address_line2 = $cart['gift_cart_order']['address_line2'];
                $address_zipcode = $cart['gift_cart_order']['address_zipcode'];
                $address_city = $cart['gift_cart_order']['address_city'];
                $address_country = $cart['gift_cart_order']['address_country'];
                $paypalSecret = $cart['gift_cart_order']['paypalSecret'];
                $paypalKey = $cart['gift_cart_order']['paypalKey'];
                $ispaypalSandboxEnabled = $cart['gift_cart_order']['ispaypalSandboxEnabled'];
                $authorName = $cart['gift_cart_order']['authorName'];
                $total_pay = $cart['gift_cart_order']['total_pay'];
                $amount = 0;
                return view('gift_card.paypal', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'gift_cart_order' => $cart['gift_cart_order']]);
            }
        } else {
            return redirect()->route('customize.giftcard');
        }

    }
    public function razorpaypayment(Request $request)
    {
        $input = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $cart = Session::get('gift_cart', []);
        $api_secret = $cart['gift_cart_order']['razorpaySecret'];
        $api_key = $cart['gift_cart_order']['razorpayKey'];
        $api = new Api($api_key, $api_secret);

        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::save();

            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }

        Session::put('success', 'Payment successful');
        return redirect()->route('giftcard.success');

    }
    public function processStripePayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $cart = Session::get('gift_cart', []);
        if (@$cart['gift_cart_order'] && $input['token_id']) {
            if ($cart['gift_cart_order']['stripeKey'] && $cart['gift_cart_order']['stripeSecret']) {
                $currency = "usd";
                if (@$cart['gift_cart_order']['currency']) {
                    $currency = $cart['gift_cart_order']['currency'];
                }
                $stripeSecret = $cart['gift_cart_order']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);

                $name = $input['name'];
                $address_line1 = $input['address_line1'];
                $address_line2 = $input['address_line2'];
                $address_city = $input['address_city'];
                $address_state = $input['address_state'];
                $address_country = $input['address_country'];
                $address_zipcode = $input['address_zipcode'];
                $description = env('APP_NAME', 'Foodie') . ' Order';

                try {

                    $charge = $stripe->paymentIntents->create([
                        'amount' => ($cart['gift_cart_order']['total_pay'] * 1000),
                        'currency' => $currency,
                        'description' => $description,
                    ]);

                    $cart['payment_status'] = true;
                    Session::put('gift_cart', $cart);
                    Session::put('success', 'Payment successful');
                    Session::save();
                    $res = array('status' => true, 'data' => $charge, 'message' => 'success');
                    echo json_encode($res);
                    exit;

                } catch (Exception $e) {
                    $cart['payment_status'] = false;
                    Session::put('gift_cart', $cart);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = array('status' => false, 'message' => $e->getMessage());
                    echo json_encode($res);
                    exit;
                }

            }
        }


    }
    public function processPaypalPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();

        $cart = Session::get('gift_cart', []);
        if (@$cart['gift_cart_order']) {
            if ($cart['gift_cart_order']) {

                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
                $res = array('status' => true, 'data' => array(), 'message' => 'success');
                echo json_encode($res);
                exit;

            }
        }


        $cart['payment_status'] = false;
        Session::put('gift_cart', $cart);
        Session::put('error', 'Faild Payment');
        Session::save();
        $res = array('status' => false, 'message' => 'Faild Payment');
        echo json_encode($res);
        exit;

    }

    public function success()
    {
        $cart = Session::get('gift_cart', []);
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();

        if (isset($_GET['token'])) {
            $payfast_payment = Session::get('payfast_payment_token');
            if ($payfast_payment == $_GET['token']) {
                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }

        if (isset($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            $paystack_access_code = Session::get('paystack_access_code');
            if ($paystack_reference == $_GET['reference']) {
                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }

        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('buy-gift-card');
            }
        }

        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $cart['payment_status'] = true;
                Session::put('gift_cart', $cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('buy-gift-card');
            }
        }
        $payment_method = (@$cart['gift_cart_order']['payment_method']) ? $cart['gift_cart_order']['payment_method'] : 'cod';

        return view('gift_card.success', ['cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);
    }


    public function giftcards()
    {
        return view('gift_card.my_giftcards');
    }
}
