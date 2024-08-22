@include('layouts.app')


@include('layouts.header')

@php
    $cityToCountry= file_get_contents(asset('tz-cities-to-countries.json'));
    $cityToCountry=json_decode($cityToCountry,true);
    $countriesJs=array();
    foreach($cityToCountry as $key=>$value){
        $countriesJs[$key]=$value;
    }
@endphp
<div class="siddhi-checkout">


    <div class="container position-relative">

        <div class="py-5 row">

            <div class="col-md-12 mb-3">

                <div>


                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">

                        <div class="siddhi-cart-item-profile bg-white p-3">

                            <div class="card card-default">


                                <?php $authorName = @$cart['cart_order']['authorName']; ?>


                                @if($message = Session::get('success'))

                                    <div class="py-5 linus-coming-soon d-flex justify-content-center align-items-center">

                                        <div class="col-md-6">

                                            <div class="text-center pb-3">

                                                <h1 class="font-weight-bold"><?php if (@$authorName) {
                                                        echo @$authorName . ",";
                                                    } ?> {{trans('lang.your_order_has_been_successful')}}</h1>

                                                <p>Check your order status in <a href="{{route('my_order')}}"
                                                                                 class="font-weight-bold text-decoration-none text-primary">My
                                                        Orders</a> about next steps information.</p>

                                            </div>


                                            <div class="bg-white rounded text-center p-4 shadow-sm">

                                                <h1 class="display-1 mb-4">🎉</h1>

                                                <h6 class="font-weight-bold mb-2">{{trans('lang.preparing_your_order')}}</h6>

                                                <p class="small text-muted">{{trans('lang.your_order_will_be_prepared_and_will_come_soon')}}</p>

                                                <a href="{{route('my_order')}}"
                                                   class="btn rounded btn-primary btn-lg btn-block">{{trans('lang.view_order')}}</a>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>


    </div>

</div>

<div id="data-table_processing_order" class="dataTables_processing panel panel-default"
     style="display: none;">{{trans('lang.Processing')}}</div>


@include('layouts.footer')


@include('layouts.nav')



@if($message = Session::get('success'))

    <script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>

    <script type="text/javascript">

        cityToCountry = '<?php echo json_encode($countriesJs);?>';
        cityToCountry = JSON.parse(cityToCountry);
        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        userCity = userTimeZone.split('/')[1];
        userCountry = cityToCountry[userCity];


        var fcmToken = '';

        var id_order = database.collection('tmp').doc().id;

        var userId = "<?php echo $id; ?>";

        var userDetailsRef = database.collection('users').where('id', "==", userId);

        var vendorDetailsRef = database.collection('vendors');

        var uservendorDetailsRef = database.collection('users');

        var AdminCommission = database.collection('settings').doc('AdminCommission');

        var razorpaySettings = database.collection('settings').doc('razorpaySettings');

        taxSetting = [];

        var reftaxSetting = database.collection('tax').where('country', '==', userCountry).where('enable', '==', true);
        reftaxSetting.get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                snapshots.docs.forEach((val) => {
                    val = val.data();
                    var obj = '';
                    obj = {
                        'country': val.country,
                        'enable': val.enable,
                        'id': val.id,
                        'tax': val.tax,
                        'title': val.title,
                        'type': val.type,
                    }
                    taxSetting.push(obj);

                })
            }
        });


        <?php if(@$cart['payment_status'] == true && !empty(@$cart['cart_order']['order_json'])){ ?>

        var order_json = '<?php echo json_encode($cart['cart_order']['order_json']); ?>';

        var notes = '<?php echo @$cart['order-note'];?>';

        order_json = JSON.parse(order_json);

        main_restaurant_id = $("#main_restaurant_id").val();

        uservendorDetailsRef.where('vendorID', "==", order_json.vendorID).get().then(async function (uservendorSnapshots) {
            if (uservendorSnapshots.docs.length) {
                var userVendorDetails = uservendorSnapshots.docs[0].data();
                if (userVendorDetails && userVendorDetails.fcmToken) {
                    fcmToken = userVendorDetails.fcmToken;
                }
            }


        });

        finalCheckout();


        function finalCheckout() {

            userDetailsRef.get().then(async function (userSnapshots) {


                var userDetails = userSnapshots.docs[0].data();
                
                payment_method = '<?php echo $payment_method; ?>';

                var vendorID = order_json.vendorID;

                vendorDetailsRef.where('id', "==", vendorID).get().then(async function (vendorSnapshots) {

                    var vendorDetails = vendorSnapshots.docs[0].data();

                    var vendorUser = await getVendorUser(vendorDetails.author);

                    var createdAt = firebase.firestore.FieldValue.serverTimestamp();
                    if (order_json.take_away == 'true') {
                        order_json.take_away = true;
                    }
                    if (order_json.take_away == 'false') {
                        order_json.take_away = false;
                    }
                    for (var n = 0; n < order_json.products.length; n++) {
                        if (order_json.products[n].photo == null) {
                            order_json.products[n].photo = "";
                        }
                        if (order_json.products[n].size == null) {
                            order_json.products[n].size = "";
                        }
                        order_json.products[n].quantity = parseInt(order_json.products[n].quantity);
                    }
                    var discount = 0;
                    if (order_json.discount) {
                        discount = parseInt(order_json.discount);
                    }

                    var scheduleTime = null;

                    if (order_json.scheduleTime && order_json.scheduleTime != '' && order_json.scheduleTime != undefined) {
                        scheduleTime = new Date(order_json.scheduleTime);
                    }

                    var location = {
                        'latitude': parseFloat(getCookie('address_lat')),
                        'longitude': parseFloat(getCookie('address_lng'))

                    };

                    var address = {
                        'address': null,
                        'addressAs': null,
                        'id': null,
                        'isDefault': null,
                        'landmark': null,
                        'locality': getCookie('address_name'),
                        'location': location
                    };

                    if (order_json.address) {
                        var location = {
                            'latitude': parseFloat(order_json.address.location.latitude),
                            'longitude': parseFloat(order_json.address.location.longitude)

                        };

                        address = {
                            'address': order_json.address.address,
                            'addressAs': order_json.address.addressAs,
                            'id': order_json.address.id,
                            'isDefault': (order_json.address.isDefault == "true" || order_json.address.isDefault == true) ? true : false,
                            'landmark': order_json.address.landmark,
                            'locality': order_json.address.locality,
                            'location': location
                        };
                    }


                    database.collection('restaurant_orders').doc(id_order).set({
                        'address': address,
                        'author': userDetails,
                        'authorID': order_json.authorID,
                        'couponCode': (order_json.couponCode == null) ? "" : order_json.couponCode,
                        'couponId': (order_json.couponId == null) ? "" : order_json.couponId,
                        'discount': parseFloat(discount),
                        "createdAt": createdAt,
                        'id': id_order,
                        'products': order_json.products,
                        'status': order_json.status,
                        'vendor': vendorDetails,
                        'vendorID': vendorDetails.id,
                        'deliveryCharge': order_json.deliveryCharge,
                        'tip_amount': order_json.tip_amount,
                        'adminCommission': order_json.adminCommission,
                        'adminCommissionType': order_json.adminCommissionType,
                        'payment_method': payment_method,
                        'takeAway': order_json.take_away,
                        'taxSetting': taxSetting,
                        "tax_label": order_json.tax_label,
                        "tax": order_json.tax,
                        "notes": notes,
                        "specialDiscount": order_json.specialDiscount,
                        "scheduleTime": scheduleTime,
                    }).then(function (result) {

                        $.ajax({

                            type: 'POST',

                            url: "<?php echo route('order-complete'); ?>",

                            data: {
                                _token: '<?php echo csrf_token() ?>',
                                'fcm': fcmToken,
                                'authorName': userDetails.firstName,
                                'subject': order_json.subject,
                                'message': order_json.message
                            },

                            success: async function (data) {

                                var emailUserData = await sendMailData(userDetails.email, userDetails.firstName, id_order, userDetails.shippingAddress, payment_method, order_json.products, order_json.couponCode, discount, order_json.specialDiscount, taxSetting, order_json.deliveryCharge, order_json.tip_amount);
                                if (vendorUser && vendorUser != undefined) {
                                    var emailVendorData = await sendMailData(vendorUser.email, vendorUser.firstName + ' ' + vendorUser.lastName, id_order, userDetails.shippingAddress, payment_method, order_json.products, order_json.couponCode, discount, order_json.specialDiscount, taxSetting, order_json.deliveryCharge, order_json.tip_amount);

                                }
                                $("#data-table_processing_order").hide();
                            }

                        });

                    });

                });

            });

        }

        async function getVendorUser(vendorUserId) {

            var vendorUSerData = '';

            await database.collection('users').where('id', "==", vendorUserId).get().then(async function (uservendorSnapshots) {
                if (uservendorSnapshots.docs.length) {
                    vendorUSerData = uservendorSnapshots.docs[0].data();

                }
            });

            return vendorUSerData;

        }

        <?php } ?>
        <?php if(@$cart['payment_status'] == true && !empty(@$cart['gift_cart_order']['order_json'])){ ?>

        $("#data-table_processing_order").show();

        var order_json = '<?php echo json_encode($cart['gift_cart_order']['order_json']); ?>';

        order_json = JSON.parse(order_json);

        finalCheckout();


        function finalCheckout() {

            userDetailsRef.get().then(async function (userSnapshots) {


                var userDetails = userSnapshots.docs[0].data();

                payment_method = '<?php echo $payment_method; ?>';
                var createdDate = firebase.firestore.FieldValue.serverTimestamp();
                var expiry = new Date();

                expiry.setDate(expiry.getDate() + parseInt(order_json.expiryDay));
                var expireDate = new Date(expiry);
                database.collection('gift_purchases').doc(id_order).set({
                    'giftId': order_json.giftId,
                    'price': order_json.price,
                    'message': order_json.message,
                    'redeem': false,
                    'userid': order_json.userid,
                    'id': id_order,
                    'giftTitle': order_json.giftTitle,
                    'giftPin': order_json.giftPin,
                    'giftCode': order_json.giftCode,
                    'createdDate': createdDate,
                    'expireDate': expireDate,
                    'paymentType': payment_method
                }).then(function (result) {
                    var id_wallet = database.collection('tmp').doc().id;

                    database.collection('wallet').doc(id_wallet).set({
                        'id': id_wallet,
                        'amount': order_json.price,
                        'date': createdDate,
                        'isTopUp': true,
                        'order_id': '',
                        'payment_method': 'Gift Voucher',
                        'payment_status': 'success',
                        'transactionUser': 'user',
                        'user_id': order_json.userid
                    }).then(function (result) {
                        database.collection('users').where('id', '==', order_json.userid).get().then(function (snapshot) {
                            var userData = snapshot.docs[0].data();
                            var walletAmount = 0;
                            if (userData.hasOwnProperty('wallet_amount') && !isNaN(userData.wallet_amount) && userData.wallet_amount != null) {
                                walletAmount = userData.wallet_amount;

                            }

                            var newWalletAmount = parseFloat(walletAmount) + parseFloat(order_json.price);

                            database.collection('users').doc(order_json.userid).update({
                                'wallet_amount': newWalletAmount
                            }).then(function (result) {
                                window.location.href = '{{ route("customize.giftcard")}}';

                            })
                        })

                    })

                });


            });

        }


        <?php } ?>

    </script>

@endif