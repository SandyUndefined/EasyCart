<button type="button" id="locationModal" data-toggle="modal" data-target="#locationModalAddress" hidden>submit</button>

<div class="modal fade" id="locationModalAddress" tabindex="-1" role="dialog"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered location_modal">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title locationModalTitle">{{trans('lang.delivery_address')}}</h5>

            </div>

            <div class="modal-body">

                <form class="">

                    <div class="form-row">

                        <div class="col-md-12 form-group">

                            <label class="form-label">{{trans('lang.street_1')}}</label>

                            <div class="input-group">

                                <input placeholder="Delivery Area" type="text" id="address_line1" class="form-control">

                                <div class="input-group-append">
                                    <button onclick="getCurrentLocationAddress1()" type="button"
                                            class="btn btn-outline-secondary"><i class="feather-map-pin"></i></button>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-12 form-group"><label
                                    class="form-label">{{trans('lang.landmark')}}</label><input
                                    placeholder="{{trans('lang.footer')}}" value=""
                                    id="address_line2" type="text" class="form-control"></div>

                        <div class="col-md-12 form-group"><label
                                    class="form-label">{{trans('lang.zip_code')}}</label><input placeholder="Zip Code"
                                                                                                id="address_zipcode"
                                                                                                type="text"
                                                                                                class="form-control">
                        </div>

                        <div class="col-md-12 form-group"><label class="form-label">{{trans('lang.city')}}</label><input
                                    placeholder="{{trans('lang.city')}}" id="address_city" type="text"
                                    class="form-control"></div>

                        <div class="col-md-12 form-group"><label
                                    class="form-label">{{trans('lang.country')}}</label><input placeholder="Country"
                                                                                               id="address_country"
                                                                                               type="text"
                                                                                               class="form-control">
                        </div>

                        <input type="hidden" name="address_lat" id="address_lat">
                        <input type="hidden" name="address_lng" id="address_lng">
                    </div>

                </form>

            </div>

            <div class="modal-footer p-0 border-0">

                <div class="col-12 m-0 p-0">
                    <button type="button" id="close_button" class="close" data-dismiss="modal" aria-label="Close"
                            hidden>
                        <button type="button" class="btn btn-primary btn-lg btn-block"
                                onclick="saveShippingAddress()">{{trans('lang.save_changes')}}
                        </button>

                </div>

            </div>

        </div>

    </div>

</div>

<span style="display: none;">
    <button type="button" class="btn btn-primary" id="notification_accepted_order_by_restaurant_id" data-toggle="modal"
            data-target="#notification_accepted_order_by_restaurant">{{trans('lang.large_modal')}}</button>
</span>
<div class="modal fade" id="notification_accepted_order_by_restaurant" tabindex="-1" role="dialog"
     aria-labelledby="notification_accepted_order_by_restaurant" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notification-main" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title order_accepted_subject" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><span id="restaurnat_name"></span></h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"><a href="{{url('my_order')}}"
                                                                 id="notification_accepted_order_by_restaurant_a">{{trans('lang.go')}}</a>
                </button>
            </div>
        </div>
    </div>
</div>


<span style="display: none;">

    <button type="button" class="btn btn-primary" id="notification_rejected_order_by_restaurant_id" data-toggle="modal"
            data-target="#notification_rejected_order_by_restaurant">{{trans('lang.large_modal')}}</button>

    </span>

<div class="modal fade" id="notification_rejected_order_by_restaurant" tabindex="-1" role="dialog"
     aria-labelledby="notification_accepted_order_by_restaurant" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered notification-main" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title restaurant_rejected_order" id="exampleModalLongTitle"></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <h6><span id="restaurnat_name_1"></span></h6>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary"><a href="{{url('my_order')}}"
                                                                 id="notification_rejected_order_by_restaurant_a">{{trans('lang.go')}}</a>
                </button>

            </div>

        </div>

    </div>

</div>


<span style="display: none;">

    <button type="button" class="btn btn-primary" id="notification_accepted_order_id" data-toggle="modal"
            data-target="#notification_accepted_order">{{trans('lang.large_modal')}}</button>

    </span>

<div class="modal fade" id="notification_accepted_order" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered notification-main" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title driver_accepted_subject" id="exampleModalLongTitle"></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <h6><span id="np_accept_name"></span></h6>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary"><a href="{{url('my_order')}}"
                                                                 id="notification_accepted_a">{{trans('lang.go')}}</a>
                </button>

            </div>

        </div>

    </div>

</div>

<span style="display: none;">

    <button type="button" class="btn btn-primary" id="notification_order_complete_id" data-toggle="modal"
            data-target="#notification_order_complete">{{trans('lang.large_modal')}}</button>

    </span>

<div class="modal fade" id="notification_order_complete" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered notification-main" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title order_completed_subject"
                    id="exampleModalLongTitle">{{trans('lang.order_completed')}}</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <h6 id="order_completed_msg"></h6>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary"><a href="{{url('my_order')}}"
                                                                 id="">{{trans('lang.go')}}</a></button>

            </div>

        </div>

    </div>

</div>


<span style="display: none;">

    <button type="button" class="btn btn-primary" id="notification_accepted_dining_by_restaurant_id" data-toggle="modal"
            data-target="#notification_accepted_dining_by_restaurant">{{trans('lang.large_modal')}}</button>

    </span>

<div class="modal fade" id="notification_accepted_dining_by_restaurant" tabindex="-1" role="dialog"
     aria-labelledby="notification_accepted_dining_by_restaurant" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered notification-main" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title dinein_accepted"
                    id="exampleModalLongTitle"></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <h6><span id="restaurnat_name_dining"></span></h6>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary"><a href="{{url('my_dinein')}}"
                                                                 id="notification_accepted_dining_by_restaurant_a">{{trans('lang.go')}}</a>
                </button>

            </div>

        </div>

    </div>

</div>


<span style="display: none;">

    <button type="button" class="btn btn-primary" id="notification_rejected_dining_by_restaurant_id" data-toggle="modal"
            data-target="#notification_rejected_dining_by_restaurant">{{trans('lang.large_modal')}}</button>

    </span>

<div class="modal fade" id="notification_rejected_dining_by_restaurant" tabindex="-1" role="dialog"
     aria-labelledby="notification_rejected_dining_by_restaurant" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered notification-main" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title dinein_rejected"
                    id="exampleModalLongTitle"></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <h6><span id="restaurnat_name_dining_rejected"></span>
                </h6>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary"><a href="{{url('my_dinein')}}"
                                                                 id="notification_rejected_dining_by_restaurant_a">{{trans('lang.go')}}</a>
                </button>

            </div>

        </div>

    </div>

</div>

<footer class="section-footer border-top bg-dark">
    <div class="footerTemplate"></div>
</footer>

<script type="text/javascript" src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/sidebar/hc-offcanvas-nav.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick-lightbox.js')}}"></script>
<script type="text/javascript" src="{{asset('js/siddhi.js')}}"></script>
<script src="{{ asset('js/jquery.resizeImg.js') }}"></script>

<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-database.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>

<script type="text/javascript">

    <?php $id = null; if (Auth::user()) {
        $id = Auth::user()->getvendorId();
    } ?>

    var cuser_id = '<?php echo $id; ?>';

    var dine_in_enable = false;

    var place = [];

    var address_name = getCookie('address_name');

    var address_name1 = getCookie('address_name1');

    var address_name2 = getCookie('address_name2');

    var address_zip = getCookie('address_zip');

    var address_lat = getCookie('address_lat');

    var address_lng = getCookie('address_lng');

    var address_city = getCookie('address_city');

    var address_state = getCookie('address_state');

    var address_country = getCookie('address_country');


    var database = firebase.firestore();

    var googleMapKey = '';

    async function loadGoogleMapsScript() {

        await database.collection('settings').doc("googleMapKey").get().then(function (googleMapKeySnapshotsHeader) {

            var placeholderImageHeaderData = googleMapKeySnapshotsHeader.data();
            googleMapKey = placeholderImageHeaderData.key;

            const script = document.createElement('script');
            script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapKey + "&libraries=places";
            script.onload = function () {
                initialize();

            };
            document.head.appendChild(script);

        });

    }

    loadGoogleMapsScript();

    function initialize() {

        if (address_name != '') {

            document.getElementById('user_locationnew').value = address_name;

        }

        var input = document.getElementById('user_locationnew');


        autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function () {

            var place = autocomplete.getPlace();

            address_name = place.name;

            address_lat = place.geometry.location.lat();

            address_lng = place.geometry.location.lng();


            $.each(place.address_components, function (i, address_component) {

                address_name1 = '';

                if (address_component.types[0] == "premise") {

                    if (address_name1 == '') {

                        address_name1 = address_component.long_name;

                    } else {

                        address_name2 = address_component.long_name;

                    }

                } else if (address_component.types[0] == "postal_code") {

                    address_zip = address_component.long_name;

                } else if (address_component.types[0] == "locality") {

                    address_city = address_component.long_name;

                } else if (address_component.types[0] == "administrative_area_level_1") {

                    var address_state = address_component.long_name;

                } else if (address_component.types[0] == "country") {

                    var address_country = address_component.long_name;

                }

            });

            <?php if(@Route::current()->getName() != 'checkout'){ ?>

            setCookie('address_name1', address_name1, 365);

            setCookie('address_name2', address_name2, 365);

            setCookie('address_name', address_name, 365);

            setCookie('address_lat', address_lat, 365);

            setCookie('address_lng', address_lng, 365);

            setCookie('address_zip', address_zip, 365);

            setCookie('address_city', address_city, 365);

            setCookie('address_state', address_state, 365);

            setCookie('address_country', address_country, 365);
            <?php } ?>
            window.location.reload();

        });

    }

    <?php if(@Route::current()->getName() == 'checkout'){ ?>

    setTimeout(() => {
        initializeCheckout();
    }, 4000);

    function initializeCheckout() {


        if (address_name != '') {

            document.getElementById('address_line1').value = address_name;

        }

        var input2 = document.getElementById('address_line1');

        autocomplete2 = new google.maps.places.Autocomplete(input2);

        google.maps.event.addListener(autocomplete2, 'place_changed', function () {


            var place = autocomplete2.getPlace();

            address_name = place.name;

            address_lat = place.geometry.location.lat();

            address_lng = place.geometry.location.lng();

            $.each(place.address_components, function (i, address_component) {

                address_name1 = '';
                if (address_component.types[0] == "premise") {

                    if (address_name1 == '') {

                        address_name1 = address_component.long_name;

                    } else {

                        address_name2 = address_component.long_name;

                    }
                    if (address_name2 == null) {
                        address_name2 = '';
                    }
                } else if (address_component.types[0] == "postal_code") {

                    address_zip = address_component.long_name;

                } else if (address_component.types[0] == "locality") {

                    address_city = address_component.long_name;

                } else if (address_component.types[0] == "administrative_area_level_1") {

                    address_state = address_component.long_name;

                } else if (address_component.types[0] == "country") {
                    
                    address_country = address_component.long_name;

                }

            });
            $("#address_line1").val(address_name1);

            $("#address_line2").val(address_name2);

            $("#address_lat").val(address_lat);

            $("#address_lng").val(address_lng);

            $("#address_city").val(address_city);

            $("#address_country").val(address_country);

            $("#address_zipcode").val(address_zip);

        });


    }

    <?php } ?>



    async function getCurrentLocation(type = '') {


        var geocoder = new google.maps.Geocoder();


        if (navigator.geolocation) {


            navigator.geolocation.getCurrentPosition(async function (position) {


                var pos = {

                    lat: position.coords.latitude,

                    lng: position.coords.longitude

                };


                var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                var circle = new google.maps.Circle({

                    center: geolocation,

                    radius: position.coords.accuracy

                });


                var location = new google.maps.LatLng(pos['lat'], pos['lng']);    // turn coordinates into an object


                geocoder.geocode({'latLng': location}, async function (results, status) {


                    if (status == google.maps.GeocoderStatus.OK) {


                        if (results.length > 0) {

                            document.getElementById('user_locationnew').value = results[0].formatted_address;

                            address_name1 = '';

                            $.each(results[0].address_components, async function (i, address_component) {


                                address_name1 = '';

                                if (address_component.types[0] == "premise") {

                                    if (address_name1 == '') {

                                        address_name1 = address_component.long_name;

                                    } else {

                                        address_name2 = address_component.long_name;

                                    }

                                } else if (address_component.types[0] == "postal_code") {

                                    address_zip = address_component.long_name;

                                } else if (address_component.types[0] == "locality") {

                                    address_city = address_component.long_name;

                                } else if (address_component.types[0] == "administrative_area_level_1") {

                                    var address_state = address_component.long_name;

                                } else if (address_component.types[0] == "country") {

                                    var address_country = address_component.long_name;

                                }

                            });


                            address_name = results[0].formatted_address;

                            address_lat = results[0].geometry.location.lat();

                            address_lng = results[0].geometry.location.lng();

                            setCookie('address_name1', address_name1, 365);

                            setCookie('address_name2', address_name2, 365);

                            setCookie('address_name', address_name, 365);

                            setCookie('address_lat', address_lat, 365);

                            setCookie('address_lng', address_lng, 365);

                            setCookie('address_zip', address_zip, 365);

                            setCookie('address_city', address_city, 365);

                            setCookie('address_state', address_state, 365);

                            setCookie('address_country', address_country, 365);


                            if (type == 'reload') {

                                window.location.reload(true);

                            }

                        }


                    }


                });

                try {

                    if (autocomplete) {

                        autocomplete.setBounds(circle.getBounds());

                    }

                } catch (err) {


                }


            }, function () {


            });


        } else {

        }

    }

    function setCookie(name, value, days) {

        var expires = "";

        if (days) {

            var date = new Date();

            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

            expires = "; expires=" + date.toUTCString();

        }

        document.cookie = name + "=" + (value || "") + expires + "; path=/";

    }

    function getCookie(name) {

        var nameEQ = name + "=";

        var ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i++) {

            var c = ca[i];

            while (c.charAt(0) == ' ') c = c.substring(1, c.length);

            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);

        }

        return null;

    }

    var footerRef = database.collection('settings').doc('footerTemplate');

    footerRef.get().then(async function (snapshots) {
        var footerData = snapshots.data();
        if (footerData != undefined) {
            if (footerData.footerTemplate && footerData.footerTemplate != "" && footerData.footerTemplate != undefined) {
                $('.footerTemplate').html(footerData.footerTemplate);
            }
        }
    });

    var langcount = 0;
    var languages_list = database.collection('settings').doc('languages');
    languages_list.get().then(async function (snapshotslang) {
        snapshotslang = snapshotslang.data();
        if (snapshotslang != undefined) {
            snapshotslang = snapshotslang.list;
            languages_list_main = snapshotslang;
            snapshotslang.forEach((data) => {
                if (data.isActive == true) {
                    langcount++;
                    $('#language_dropdown').append($("<option></option>").attr("value", data.slug).text(data.title));
                }
            });
            if (langcount > 1) {
                $("#language_dropdown_box").css('visibility', 'visible');
            }
            <?php if(session()->get('locale')){ ?>
            $("#language_dropdown").val("<?php echo session()->get('locale'); ?>");
            <?php } ?>

        }
    });

    var url = "{{ route('changeLang') }}";

    $(".changeLang").change(function () {
        var slug = $(this).val();
        languages_list_main.forEach((data) => {
            if (slug == data.slug) {
                if (data.is_rtl == undefined) {
                    setCookie('is_rtl', 'false', 365);
                } else {
                    setCookie('is_rtl', data.is_rtl.toString(), 365);
                }
                debugger;
                window.location.href = url + "?lang=" + slug;
            }
        });
    });

    database.collection('settings').doc("notification_setting").get().then(async function (snapshots) {
        var data = snapshots.data();
        if(data != undefined){
            serviceJson = data.serviceJson;
            if(serviceJson != '' && serviceJson != null){
                $.ajax({
                    type: 'POST',
                    data: {
                        serviceJson: btoa(serviceJson),
                    },
                    url: "{{ route('store-firebase-service') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                    }
                });
            }
        }
    });

    //start - Get user zone id from address
    getUserZoneId();
    var user_zone_id = null;
    async function getUserZoneId(){
        var zone_list = [];
        var snapshots = await database.collection('zone').where("publish","==",true).get();
        if(snapshots.docs.length > 0){
            snapshots.docs.forEach((snapshot) => {
                var zone_data = snapshot.data();
                zone_list.push(zone_data);
            });   
        }
        if(zone_list.length > 0){
            for (i = 0; i < zone_list.length; i++) {
                var zone = zone_list[i];
                var vertices_x = [];
                var vertices_y = [];
                for (j = 0; j < zone.area.length; j++) {
                    var geopoint = zone.area[j];
                    vertices_x.push(geopoint.longitude);
                    vertices_y.push(geopoint.latitude);
                }
                var points_polygon = (vertices_x.length)-1; 
                if(is_in_polygon(points_polygon, vertices_x, vertices_y, address_lng, address_lat)){
                    user_zone_id = zone.id;
                    break; 
                }
            }
        }
    }
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
		$i = $j = $c = $point = 0;
		for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
			$point = $i;
            if( $point == $points_polygon )
				$point = 0;
			if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
				$c = !$c;
		}
		return $c;
	}
    //end - Get user zone id from address

    
    //start - Get product price with admin commission globally
    database.collection('settings').doc("AdminCommission").get().then(async function (snapshots) {
        var adminCommissionSettings = snapshots.data();
		localStorage.setItem('adminCommissionSettings',JSON.stringify(adminCommissionSettings));
    });
    
    function getPrice(price){
        if(price != null && price != ""){
            var adminCommissionSettings = localStorage.getItem('adminCommissionSettings')
            if(adminCommissionSettings != "" && adminCommissionSettings != undefined){
                adminCommissionSettings = JSON.parse(localStorage.getItem('adminCommissionSettings'));
                if(adminCommissionSettings.isEnabled){
                    if(adminCommissionSettings.commissionType == "Percent"){
                        var final_price = price + ((parseFloat(adminCommissionSettings.fix_commission) * price) / 100); 
                    }else{
                        var final_price = price + parseFloat(adminCommissionSettings.fix_commission);
                    }
                }else{
                    var final_price = price;
                }
            }else{
                var final_price = price;
            }
            return final_price;
        }
    }

    function getFormattedPrice(price){
        if(price != null && price != ""){
            var adminCommissionSettings = localStorage.getItem('adminCommissionSettings')
            if(adminCommissionSettings != "" && adminCommissionSettings != undefined){
                adminCommissionSettings = JSON.parse(localStorage.getItem('adminCommissionSettings'));
                if(adminCommissionSettings.isEnabled){
                    if(adminCommissionSettings.commissionType == "Percent"){
                        var final_price = price + ((parseFloat(adminCommissionSettings.fix_commission) * price) / 100); 
                    }else{
                        var final_price = price + parseFloat(adminCommissionSettings.fix_commission);
                    }
                }else{
                    var final_price = price;
                }
            }else{
                var final_price = price;
            }
            if (currencyAtRight) {
                var formatted_price = final_price.toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                var formatted_price = currentCurrency + "" + final_price.toFixed(decimal_degits);
            }
            return formatted_price;
        }
    }
    //end - Get product price with admin commission globally

</script>


<script type="text/javascript">

    <?php
    use App\Models\user;use App\Models\VendorUsers;
    $user_email = '';
    $user_uuid = '';
    $auth_id = Auth::id();
    if ($auth_id) {

        $user = user::select('email')->where('id', $auth_id)->first();
        $user_email = $user->email;
        $user_uuid = VendorUsers::select('uuid')->where('email', $user_email)->first();
        $user_uuid = $user_uuid->uuid;
    }
    ?>

    var database = firebase.firestore();
    var refDineInRestaurant = database.collection('settings').doc("DineinForRestaurant");

    refDineInRestaurant.get().then(async function (snapshotsDinein) {

        var enableddineinRestaurant = snapshotsDinein.data();
        dine_in_enable = enableddineinRestaurant.isEnabled;
        if (dine_in_enable == true) {
            $(".dine_in_menu").show();
            $(".dine_in_menu").attr('style', 'display: block !important');
        }
    });

    var placeholderImageHeader = '';
    var googleMapKey = '';
    var googleMapKeySettingHeader = database.collection('settings').doc("googleMapKey");
    googleMapKeySettingHeader.get().then(async function (googleMapKeySnapshotsHeader) {
        var placeholderImageHeaderData = googleMapKeySnapshotsHeader.data();
        placeholderImageHeader = placeholderImageHeaderData.placeHolderImage;
        googleMapKey = placeholderImageHeaderData.key;
    });


    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    });

    var user_email = "<?php echo $user_email;  ?>";
    var user_ref = '';
    var referral_ref = '';
    if (user_email != '') {
        var user_uuid = "<?php echo $user_uuid; ?>";
        user_ref = database.collection('users').where("id", "==", user_uuid);
        referral_ref = database.collection('referral').doc(user_uuid);
    }


    $(document).ready(function () {

        jQuery("#data-table_processing").show();

        if (user_ref != '') {
            user_ref.get().then(async function (profileSnapshots) {
                var profile_user = profileSnapshots.docs[0].data();
                var profile_name = profile_user.firstName + " " + profile_user.lastName;
                if (profile_user.profilePictureURL != '') {
                    $("#dropdownMenuButton").append('<img alt="#" src="' + profile_user.profilePictureURL + '" class="img-fluid rounded-circle header-user mr-2 header-user">Hi ' + profile_user.firstName);
                } else {
                    $("#dropdownMenuButton").append('<img alt="#" src="' + placeholderImage + '" class="img-fluid rounded-circle header-user mr-2 header-user">Hi ' + profile_user.firstName);
                }
                if(profile_user.hasOwnProperty('shippingAddress')){
                    $("#user_location").html(profile_user.shippingAddress.city);
                }
            });
        }
        if (referral_ref) {
            referral_ref.get().then(async function (refSnapshot) {
                var referral_data = refSnapshot.data();
                if (referral_data != undefined && referral_data.referralCode != null && referral_data.referralCode != undefined) {
                    $(".referral_code").html("<b>{{trans('lang.your_referral_code')}} : " + referral_data.referralCode + "</b>");
                }
            })
        }
    })

    $(".user-logout-btn").click(async function () {
        firebase.auth().signOut().then(function () {
            var logoutURL = "{{route('logout')}}";
            $.ajax({
                type: 'POST',
                url: logoutURL,
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data1) {
                    if (data1.logoutuser) {
                        window.location = "{{ route('login')}}";
                    }
                }
            })
        });
    });

    <?php if(@$_GET['update_location'] == 1){ ?>
        var vendorsRef = database.collection('vendors');
        vendorsRef.get().then(async function (vendorsSnapshots) {
                vendorsSnapshots.forEach((doc) => {
                    vendorRate = doc.data();
                    if (vendorRate.g != undefined) {
                        if (vendorRate.g.geopoint._longitude && vendorRate.g.geopoint._latitude) {
                            coordinates = new firebase.firestore.GeoPoint(vendorRate.g.geopoint._latitude, vendorRate.g.geopoint._longitude);
                        try {
                            geoFirestore.collection('vendors').doc(vendorRate.id).update({'coordinates': coordinates}).then(() => {
                                console.log('Provided document has been updated in Firestore');
                            }, (error) => {
                                console.log('Error: ' + error);
                            });
                        } catch (err) {
                        }
                    }
                }
            });
        });

    <?php } ?>

</script>


<script type="text/javascript" src="{{asset('js/rocket-loader.min.js')}}"></script>

<script type="text/javascript" src="{{asset('https://static.cloudflareinsights.com/beacon.min.js')}}"></script>

<?php if (Auth::user()) { ?>

<script type="text/javascript">
    
    var orderAcceptedSubject = '';
    var orderAcceptedMsg = '';
    var orderRejectedSubject = '';
    var orderRejectedMsg = '';
    var orderCompletedSubject = '';
    var orderCompletedMsg = '';
    var takeAwayOrderCompletedSubject = '';
    var takeAwayOrderCompletedMsg = '';
    var driverAcceptedSubject = '';
    var driverAcceptedMsg = '';
    var dineInAcceptedSubject = '';
    var dineInAcceptedMsg = '';
    var dineInRejectedSubject = '';
    var dineInRejectedMsg = '';

    var database = firebase.firestore();

    database.collection('dynamic_notification').get().then(async function (snapshot) {
        if (snapshot.docs.length > 0) {
            snapshot.docs.map(async (listval) => {
                val = listval.data();

                if (val.type == "driver_accepted") {

                    driverAcceptedSubject = val.subject;
                    driverAcceptedMsg = val.message;

                } else if (val.type == "restaurant_rejected") {

                    orderRejectedSubject = val.subject;
                    orderRejectedMsg = val.message;

                } else if (val.type == "takeaway_completed") {
                    takeAwayOrderCompletedSubject = val.subject;
                    takeAwayOrderCompletedMsg = val.message;

                } else if (val.type == "driver_completed") {
                    orderCompletedSubject = val.subject;
                    orderCompletedMsg = val.message;

                } else if (val.type == "restaurant_accepted") {

                    orderAcceptedSubject = val.subject;
                    orderAcceptedMsg = val.message;

                } else if (val.type == "dinein_accepted") {
                    dineInAcceptedSubject = val.subject;
                    dineInAcceptedMsg = val.message;

                } else if (val.type == "dinein_canceled") {
                    dineInRejectedSubject = val.subject;
                    dineInRejectedMsg = val.message;
                }
            });
        }
    });
    var route1 = '<?php echo route('my_order'); ?>';

    var pageloadded = 0;

    database.collection('restaurant_orders').where('author.id', "==", cuser_id).onSnapshot(function (doc) {

        if (pageloadded) {

            doc.docChanges().forEach(function (change) {

                var val = change.doc.data();

                if (change.type == "modified") {

                    if (val.status == "Order Completed" && val.takeAway == true || val.takeAway == 'true') {

                        $('.order_completed_subject').text(takeAwayOrderCompletedSubject);
                        $('#order_completed_msg').text(takeAwayOrderCompletedMsg);
                        $("#notification_order_complete_id").trigger("click");

                    } else if (val.status == "Order Completed" && val.takeAway == false || val.takeAway == 'false') {
                        $('.order_completed_subject').text(orderCompletedSubject);
                        $('#order_completed_msg').text(orderCompletedMsg);
                        $("#notification_order_complete_id").trigger("click");
                    } else if (val.status == "Order Accepted") {

                        title = '';

                        if (val.vendor.title) {
                            title = val.vendor.title;

                        }
                        $("#restaurnat_name").text(orderAcceptedMsg);
                        $('.order_accepted_subject').text(orderAcceptedSubject);

                        if (route1) {

                            $("#notification_accepted_order_by_restaurant_a").attr("href", route1);

                        }

                        $("#notification_accepted_order_by_restaurant_id").trigger("click");

                    } else if (val.status == "Driver Accepted") {
                        var driverName = '';
                        if (val.driver && val.driver.firstName) {
                            driverName = val.driver.firstName;

                        }
                        $("#np_accept_name").text(driverAcceptedMsg);
                        $('.driver_accepted_subject').text(driverAcceptedSubject);

                        if (route1) {

                            $("#notification_accepted_a").attr("href", route1.replace(':id', val.id));

                        }

                        $("#notification_accepted_order").modal('show');

                        $("#notification_accepted_order_id").trigger("click");

                    } else if (val.status == "Order Rejected") {

                        var title = '';
                        if (val.vendor.title) {
                            title = val.vendor.title;

                        }
                        $("#restaurnat_name_1").text(orderRejectedMsg);
                        $('.restaurant_rejected_order').text(orderRejectedSubject);

                        if (route1) {

                            $("#notification_rejected_order_by_restaurant_a").attr("href", route1);

                        }

                        $("#notification_rejected_order_by_restaurant_id").trigger("click");

                    }

                }


            });

        } else {

            pageloadded = 1;

        }

    });


    var route2 = '<?php echo route('my_dinein'); ?>';

    var pageloadded_dining = 0;

    database.collection('booked_table').where('author.id', "==", cuser_id).onSnapshot(function (doc) {

        if (pageloadded_dining) {

            doc.docChanges().forEach(function (change) {

                var val = change.doc.data();

                if (change.type == "modified") {


                    if (val.status == "Order Accepted") {
                        var title = '';
                        if (val.vendor.title) {
                            title = val.vendor.title;

                        }
                        $("#restaurnat_name_dining").text(dineInAcceptedMsg);
                        $('.dinein_accepted').text(dineInAcceptedSubject);

                        if (route1) {

                            $("#notification_accepted_dining_by_restaurant_a").attr("href", route2);

                        }

                        $("#notification_accepted_dining_by_restaurant_id").trigger("click");

                    } else if (val.status == "Order Rejected") {
                        var title = '';
                        if (val.vendor.title) {
                            title = val.vendor.title;

                        }
                        $("#restaurnat_name_dining_rejected").text(dineInRejectedMsg);
                        $('.dinein_rejected').text(dineInRejectedSubject);

                        if (route1) {

                            $("#notification_rejected_dining_by_restaurant_a").attr("href", route2);

                        }

                        $("#notification_rejected_dining_by_restaurant_id").trigger("click");

                    }

                }


            });

        } else {

            pageloadded_dining = 1;

        }

    });

    async function getCurrentLocationAddress1() {

        var geocoder = new google.maps.Geocoder();
        navigator.geolocation.getCurrentPosition(async function (position) {
            var address_city = "";
            var address_country = "";
            var address_state = "";
            var address_street = "";
            var address_street2 = "";
            var address_street3 = "";
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });


            var location = new google.maps.LatLng(pos['lat'], pos['lng']);     // turn coordinates into an object

            geocoder.geocode({'latLng': location}, async function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    if (results.length > 0) {
                        document.getElementById('user_locationnew').value = results[0].formatted_address;
                        address_name1 = '';
                        $.each(results[0].address_components, async function (i, address_component) {

                            address_name1 = '';
                            if (address_component.types[0] == "premise") {
                                if (address_name1 == '') {
                                    address_name1 = address_component.long_name;
                                } else {
                                    address_name2 = address_component.long_name;
                                }
                            } else if (address_component.types[0] == "postal_code") {
                                address_zip = address_component.long_name;
                            } else if (address_component.types[0] == "locality") {
                                address_city = address_component.long_name;
                            } else if (address_component.types[0] == "administrative_area_level_1") {
                                address_state = address_component.long_name;
                            } else if (address_component.types[0] == "country") {
                                address_country = address_component.long_name;
                            } else if (address_component.types[0] == "street_number") {
                                address_street = address_component.long_name;
                            } else if (address_component.types[0] == "route") {
                                address_street2 = address_component.long_name;
                            } else if (address_component.types[0] == "political") {
                                address_street3 = address_component.long_name;
                            }
                        });

                        address_lat = results[0].geometry.location.lat();
                        address_lng = results[0].geometry.location.lng();

                        $("#address_lat").val(address_lat);
                        $("#address_lng").val(address_lng);

                        if (results[0].formatted_address) {
                            $("#address_line1").val(results[0].formatted_address);
                        } else {
                            $("#address_line1").val(address_street + ", " + address_street2);
                        }
                        $("#address_line2").val(address_street3);
                        $("#address_city").val(address_city);
                        $("#address_country").val(address_country);
                        $("#address_zipcode").val(address_zip);
                    }

                }

            });
            try {

            } catch (err) {

            }

        }, function () {

        });

    }

    function saveShippingAddress() {

        var line1 = $("#address_line1").val();
        var line2 = $("#address_line2").val();
        var city = $("#address_city").val();
        var country = $("#address_country").val();
        var postalCode = $("#address_zipcode").val();
        var full_address = '';

        if (cuser_id != "") {

            userDetailsRef.get().then(async function (userSnapshots) {

                var userDetails = userSnapshots.docs[0].data();

                if (userDetails.hasOwnProperty('shippingAddress')) {
                    var shippingAddress = userDetails.shippingAddress;

                    shippingAddress.line1 = $("#address_line1").val();
                    shippingAddress.line2 = $("#address_line2").val();
                    shippingAddress.city = $("#address_city").val();
                    shippingAddress.country = $("#address_country").val();
                    shippingAddress.postalCode = $("#address_zipcode").val();
                } else {
                    var shippingAddress = [];
                    var shippingAddress = {
                        "line1": line1,
                        "line2": line2,
                        "city": city,
                        "country": country,
                        "postalCode": postalCode
                    };
                }


                setCookie('address_name1', line1, 365);
                setCookie('address_name2', line2, 365);
                setCookie('address_lat', jQuery("#address_lat").val(), 365);
                setCookie('address_lng', jQuery("#address_lng").val(), 365);
                setCookie('address_zip', postalCode, 365);
                setCookie('address_city', city, 365);
                setCookie('address_country', country, 365);
                if (line1 != "") {
                    full_address = line1;
                }
                if (line2 != "") {
                    full_address = full_address + ',' + line2;
                }
                if (postalCode != "") {
                    full_address = full_address + ',' + postalCode;
                }
                if (city != "") {
                    full_address = full_address + ',' + city;
                }
                if (country != "") {
                    full_address = full_address + ',' + country;
                }

                setCookie('address_name', full_address, 365);
                database.collection('users').doc(cuser_id).update({'shippingAddress': shippingAddress}).then(function (result) {

                    $('#close_button').trigger("click");
                    location.reload();
                });

            });

        } else {
            setCookie('address_name1', line1, 365);
            setCookie('address_name2', line2, 365);
            setCookie('address_lat', jQuery("#address_lat").val(), 365);
            setCookie('address_lng', jQuery("#address_lng").val(), 365);
            setCookie('address_zip', postalCode, 365);
            setCookie('address_city', city, 365);
            setCookie('address_country', country, 365);

            if (line1 != "") {
                full_address = line1;
            }
            if (line2 != "") {
                full_address = full_address + ',' + line2;
            }
            if (postalCode != "") {
                full_address = full_address + ',' + postalCode;
            }
            if (city != "") {
                full_address = full_address + ',' + city;
            }
            if (country != "") {
                full_address = full_address + ',' + country;
            }
            setCookie('address_name', full_address, 365);
            $('#close_button').trigger("click");
            location.reload();
        }
    }

    var email_templates = database.collection('email_templates').where('type', '==', 'new_order_placed');

    var emailTemplatesData = null;

    var currentCurrency = "";
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);

    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;

        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    async function sendMailData(userEmail, userName, orderId, address, paymentMethod, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tipAmount) {

        await email_templates.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();

        });

        var formattedDate = new Date();
        var month = formattedDate.getMonth() + 1;
        var day = formattedDate.getDate();
        var year = formattedDate.getFullYear();

        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;

        formattedDate = day + '-' + month + '-' + year;

        var shippingddress = '';

        if (address.hasOwnProperty('address')) {

            shippingddress = address.address;
        }
        if (address.hasOwnProperty('locality')) {

            if (address.locality != '') {
                shippingddress = shippingddress + ',' + address.locality;
            }

        }
        if (address.hasOwnProperty('landmark')) {
            if (address.landmark != '') {
                shippingddress = shippingddress + ' ' + address.landmark;
            }
        }

        var productDetailsHtml = '';
        var subTotal = 0;

        products.forEach((product) => {


            productDetailsHtml += '<tr>';

            var extra_html = '';
            var extras_price = 0;
            var price_item = parseFloat(product.price).toFixed(decimal_degits);
            var totalProductPrice = parseFloat(price_item) * parseInt(product.quantity);

            if (product.extras != undefined && product.extras != '' && product.extras.length > 0) {
                var extra_count = 1;
                try {

                    var extras_price_item = (parseFloat(product.extras_price) * parseInt(product.quantity)).toFixed(decimal_degits);
                    if (parseFloat(extras_price_item) != NaN && product.extras_price != undefined) {
                        extras_price = extras_price_item;
                    }
                    totalProductPrice = parseFloat(extras_price) + parseFloat(totalProductPrice);

                    product.extras.forEach((extra) => {

                        if (extra_count > 1) {
                            extra_html = extra_html + ',' + extra;
                        } else {
                            extra_html = extra_html + extra;
                        }
                        extra_count++;
                    })
                } catch (error) {

                }
            }

            totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);

            productDetailsHtml += '<td style="width: 20%; border-top: 1px solid rgb(0, 0, 0);">';

            productDetailsHtml += product.name;

            if (extra_count > 1) {
                productDetailsHtml += '<br> {{trans("lang.extra_item")}} : ' + extra_html;
            }

            subTotal += parseFloat(totalProductPrice);

            if (currencyAtRight) {
                price_item = parseFloat(price_item).toFixed(decimal_degits) + "" + currentCurrency;
                extras_price = parseFloat(extras_price).toFixed(decimal_degits) + "" + currentCurrency;
                totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                price_item = currentCurrency + "" + parseFloat(price_item).toFixed(decimal_degits);
                extras_price = currentCurrency + "" + parseFloat(extras_price).toFixed(decimal_degits);
                totalProductPrice = currentCurrency + "" + parseFloat(totalProductPrice).toFixed(decimal_degits);
            }


            productDetailsHtml += '</td>';
            productDetailsHtml += '<td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + product.quantity + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + price_item + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + extras_price + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">  ' + totalProductPrice + '</td>';

            productDetailsHtml += '</tr>';

        });

        var specialDiscountVal = '';
        var specialDiscountAmount = 0;
        var totalAmount = 0;

        if (specialDiscount.specialType != '') {

            specialDiscountAmount = parseFloat(specialDiscount.special_discount).toFixed(2);

            if (specialDiscount.specialType == "percentage") {
                specialDiscountVal = specialDiscount.special_discount_label + '%';
            } else {
                if (currencyAtRight) {
                    specialDiscountVal = parseFloat(specialDiscount.special_discount_label).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    specialDiscountVal = currentCurrency + "" + parseFloat(specialDiscount.special_discount_label).toFixed(decimal_degits);
                }
            }
        }

        var afterDiscountTotal = subTotal - (specialDiscountAmount + parseFloat(discount));

        var taxDetailsHtml = '';
        var total_tax_amount = 0;

        if (taxSetting.length > 0) {

            for (var i = 0; i < taxSetting.length; i++) {
                var data = taxSetting[i];

                var tax = 0;
                var taxvalue = 0;
                var taxlabel = "";
                var taxlabeltype = "";

                if (data.type && data.tax) {

                    tax = data.tax;
                    taxvalue = data.tax;
                    if (data.type == "percentage") {
                        tax = (data.tax * afterDiscountTotal) / 100;
                        taxlabeltype = "%";
                    }
                    taxlabel = data.title;
                }
                if (!isNaN(tax) && tax != 0) {
                    total_tax_amount += parseFloat(tax);

                    if (currencyAtRight) {
                        tax = parseFloat(tax).toFixed(decimal_degits) + '' + currentCurrency;
                        if (data.type == "fix") {

                            taxvalue = parseFloat(taxvalue).toFixed(decimal_degits) + '' + currentCurrency;

                        }
                    } else {
                        tax = currentCurrency + parseFloat(tax).toFixed(decimal_degits);
                        if (data.type == "fix") {
                            taxvalue = currentCurrency + parseFloat(taxvalue).toFixed(decimal_degits);

                        }
                    }
                    var html = '';

                    if (taxDetailsHtml != '') {
                        html = '<br>';
                    }

                    taxDetailsHtml += html + '<span style="font-size: 1rem;">' + taxlabel + " (" + taxvalue + taxlabeltype + '):' + tax + '</span>';
                }
            }
        }

        totalAmount = parseFloat(subTotal) - (parseFloat(specialDiscountAmount) + parseFloat(discount)) + parseFloat(total_tax_amount) + parseFloat(deliveryCharge) + parseFloat(tipAmount);


        if (currencyAtRight) {
            subTotal = parseFloat(subTotal).toFixed(decimal_degits) + "" + currentCurrency;
            discount = parseFloat(discount).toFixed(decimal_degits) + "" + currentCurrency;
            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits) + "" + currentCurrency;
            tipAmount = parseFloat(tipAmount).toFixed(decimal_degits) + "" + currentCurrency;
            specialDiscountAmount = parseFloat(specialDiscountAmount).toFixed(decimal_degits) + "" + currentCurrency;
            totalAmount = parseFloat(totalAmount).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            subTotal = currentCurrency + "" + parseFloat(subTotal).toFixed(decimal_degits);
            discount = currentCurrency + "" + parseFloat(discount).toFixed(decimal_degits);
            deliveryCharge = currentCurrency + "" + parseFloat(deliveryCharge).toFixed(decimal_degits);
            tipAmount = currentCurrency + "" + parseFloat(tipAmount).toFixed(decimal_degits);
            specialDiscountAmount = currentCurrency + "" + parseFloat(specialDiscountAmount).toFixed(decimal_degits);
            totalAmount = currentCurrency + "" + parseFloat(totalAmount).toFixed(decimal_degits);
        }

        var productHtml = '<table style="width: 100%; border-collapse: collapse; border: 1px solid rgb(0, 0, 0);">\n' +
            '    <thead>\n' +
            '        <tr>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{trans("lang.product_name")}}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{trans("lang.quantity_plural")}}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{trans("lang.price")}}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{trans("lang.extra_item")}} {{trans("lang.price")}}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{trans("lang.total")}}<br></th>\n' +
            '        </tr>\n' +
            '    </thead>\n' +
            '    <tbody id="productDetails">' + productDetailsHtml + '</tbody>\n' +
            '</table>';


        var subject = emailTemplatesData.subject;

        subject = subject.replace(/{orderid}/g, orderId);
        emailTemplatesData.subject = subject;

        var message = emailTemplatesData.message;
        message = message.replace(/{username}/g, userName);
        message = message.replace(/{date}/g, formattedDate);
        message = message.replace(/{orderid}/g, orderId);
        message = message.replace(/{address}/g, shippingddress);
        message = message.replace(/{paymentmethod}/g, paymentMethod);
        message = message.replace(/{productdetails}/g, productHtml);
        if (couponCode) {
            message = message.replace(/{coupon}/g, '(' + couponCode + ')');
        } else {
            message = message.replace(/{coupon}/g, "");
        }


        message = message.replace(/{discountamount}/g, discount);

        if (specialDiscountVal != '') {
            message = message.replace(/{specialcoupon}/g, '(' + specialDiscountVal + ')');
        } else {
            message = message.replace(/{specialcoupon}/g, "");
        }
        message = message.replace(/{specialdiscountamount}/g, specialDiscountAmount);

        if (taxDetailsHtml != '') {
            message = message.replace(/{taxdetails}/g, taxDetailsHtml);

        } else {
            message = message.replace(/{taxdetails}/g, "");

        }
        message = message.replace(/{shippingcharge}/g, deliveryCharge);
        message = message.replace(/{subtotal}/g, subTotal);
        message = message.replace(/{tipamount}/g, tipAmount);
        message = message.replace(/{totalAmount}/g, totalAmount);

        emailTemplatesData.message = message;

        var url = "{{url('send-email')}}";

        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [userEmail]);
    }

    async function sendEmail(url, subject, message, recipients) {

        var checkFlag = false;

        await $.ajax({

            type: 'POST',
            data: {
                subject: subject,
                message: message,
                recipients: recipients
            },
            url: url,
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                checkFlag = true;
            },
            error: function (xhr, status, error) {
                checkFlag = true;
            }
        });

        return checkFlag;

    }

</script>

<?php } ?>

<?php if (isset($_COOKIE['section_color'])) { ?>
<style type="text/css">


    a, .list-card a:hover, a:hover {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .hc-offcanvas-nav h2, .hc-offcanvas-nav:not(.touch-device) li:not(.custom-content) a:hover, .cat-item a.cat-link:hover {
        background-color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .homebanner-content .ban-btn a, .open-ticket-btn a, .select-sec-btn a {
        background-color: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .homebanner-content .ban-btn a:hover, .open-ticket-btn a:hover, .select-sec-btn a:hover {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .header-main .takeaway-div input[type="checkbox"]::before {
        background-color: <?php echo $_COOKIE['section_color']; ?>;
        opacity: 0.6;
    }

    .header-main .takeaway-div input[type="checkbox"]:checked::before {
        opacity: 1;
    }

    .list-card .member-plan .badge.open, .rest-basic-detail .feather_icon .fu-status a.rest-right-btn > span.open, .header-main .takeaway-div input[type="checkbox"]:checked::before {
        background-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .offer_coupon_code .offer_code p.badge, .offer_coupon_code .offer_price {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .cat-item a.cat-link:hover i.fa {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .rest-basic-detail .feather_icon a.rest-right-btn, .rest-basic-detail .feather_icon a.btn {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .rest-basic-detail .feather_icon a.rest-right-btn .feather-star, .rest-basic-detail .feather_icon a.btn, .rest-basic-detail .feather_icon a.rest-right-btn:hover, ul.rating {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .vendor-detail-left h4.h6::after, .sidebar-header h3.h6::after {
        background-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .gold-members .add-btn .menu-itembtn a.btn {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .btn-primary, .transactions-list .media-body .app-off-btn a {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .btn-primary:hover, .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show > .btn-primary.dropdown-toggle, .btn-primary.focus, .btn-primary:focus, .custom-control-input:checked ~ .custom-control-label::before, .row.fu-loadmore-btn .page-link {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .count-number-box .count-number .count-number-input, .count-number .count-number-input, .count-number-box .count-number button.count-number-input-cart:hover, .count-number button.btn-sm.btn:hover, .btn-link {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .transactions-banner {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .transactions-list .media-body .app-off-btn a:hover, .rating-stars .feather-star.star_active, .rating-stars .feather-star.text-warning {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .search .nav-tabs .nav-item.show .nav-link, .search .nav-tabs .nav-link.active {
        border-color: <?php echo $_COOKIE['section_color']; ?>  !important;
        background-color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .text-primary, .card-icon > span {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .checkout-left-box.siddhi-cart-item::after, .checkout-left-box.accordion::after, .dropdown-item.active, .dropdown-item:active, .restaurant-detail-left h4.h6::after, .sidebar-header h3.h6::after {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .page-link, .rest-basic-detail .feather_icon a.rest-right-btn {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .page-link:hover {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .btn-outline-primary {
        color: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .btn-outline-primary:hover {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .gendetail-row h3 {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .dyn-menulist button.view_all_menu_btn {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .daytab-cousines ul li > span {
        color: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .daytab-cousines ul li > span:hover {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .feather-star.text-warning, .list-card .offer_coupon_code .star .badge .feather-star.star_active, .list-card-body .offer-btm .star .badge .feather-star.star_active {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    a.restaurant_direction img {
        filter: grayscale(100%);
        -webkit-filter: grayscale(100%);
    }

    .modal-body .recepie-body .custom-control .custom-control-label > span.text-muted {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .payment-table tr th {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .slick-dots li.slick-active button::before {
        color: <?php echo $_COOKIE['section_color']; ?>  !important;
        background: <?php echo $_COOKIE['section_color']; ?>  !important;
    }

    .footer-top .title::after, .product-list .list-card .list-card-image .discount-price {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .ft-contact-box .ft-icon {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .head-search .dropdown {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .list-card .list-card-body .offer-code a {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .vandor-sidebar .vandorcat-list li a:hover, .vandor-sidebar .vandorcat-list li.active a {
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .list-card .list-card-body p.text-gray span.fa.fa-map-marker, .car-det-head .car-det-price span.price {
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .product-detail-page .addons-option .custom-control .custom-control-label.active::before {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .product-detail-page .addtocart .add-to-cart.btn.btn-primary.booknow {
        background: <?php echo $_COOKIE['section_color']; ?>;
    }

    .product-detail-page .addtocart .add-to-cart.btn.btn-primary {
        border: 1px solid<?php echo $_COOKIE['section_color']; ?>;
        color: <?php echo $_COOKIE['section_color']; ?>;
    }

    @media (max-width: 991px) {

        .bg-primary {
            background: <?php echo $_COOKIE['section_color']; ?>  !important;
        }

    }

</style>
<?php } ?>
