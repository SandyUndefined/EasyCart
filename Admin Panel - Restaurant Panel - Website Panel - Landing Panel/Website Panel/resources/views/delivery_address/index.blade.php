@include('layouts.app')


@include('layouts.header')


<div class="siddhi-popular">


    <div class="container">


        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.Processing')}}
        </div>

        <div class="col-md-12 float-right ml-auto mt-3"><a href="javascript:void(0)" data-toggle="modal"
                                                           data-target="#addAddress"
                                                           class="add-address btn btn-lg btn-success"><i
                        class="fa fa-plus"></i> {{trans('lang.add_new_address')}}</a>
        </div>

        <div class="text-center py-5 not_found_div" style="display:none">
            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
            <p class="font-weight-bold text-dark h5">{{trans('lang.no_results')}}</p>
        </div>


        <div id="append_list1" class="res-search-list"></div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container mt-4 mb-4 p-0">

                </div>
            </div>
        </div>


    </div>
    <div class="modal fade" id="addAddress" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered location_modal">

            <div class="modal-content">

                <div class="modal-header">
                    <h5>{{trans('lang.delivery_address')}}</h5>
                </div>

                <div class="modal-body">
                    <div class="col-md-12"><strong id="address_err" style="color:red"></strong></div>
                    <div class="form-row p-2">
                        <form class="row">
                            <input type="text" id="addressId" hidden>


                            <div class="col-md-12 form-group">
                                <input placeholder="Flat/House.Floor/Building*" value="" id="address" type="text"
                                       class="form-control">
                            </div>

                            <div class="col-md-12 form-group">

                                <div class="input-group">

                                    <input placeholder="Area/Sector/Locality *" type="text" id="locality"
                                           class="form-control">

                                    <div class="input-group-append">
                                        <button onclick="getCurrentDeliveryLocation()" type="button"
                                                class="btn btn-outline-secondary"><i class="feather-map-pin"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-12 form-group">
                                <input placeholder="Nearby Landmark (optinal)" value="" id="landmark" type="text"
                                       class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="control-label">{{trans('lang.save_as')}}</label>
                                <div class="custom-control custom-radio border-bottom py-2">

                                    <input type="radio" name="save_as" id="home" value="Home"
                                           class="custom-control-input" checked>

                                    <label class="custom-control-label" for="home"><span
                                                class="currency-symbol-left"></span> <?php echo 'Home';?><span
                                                class="currency-symbol-right"></span></label>

                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">

                                    <input type="radio" name="save_as" id="work" value="Work"
                                           class="custom-control-input">

                                    <label class="custom-control-label" for="work"><span
                                                class="currency-symbol-left"></span> <?php echo 'Work';?><span
                                                class="currency-symbol-right"></span></label>

                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">

                                    <input type="radio" name="save_as" id="hotel" value="Hotel"
                                           class="custom-control-input">

                                    <label class="custom-control-label" for="hotel"><span
                                                class="currency-symbol-left"></span>
                                        <?php echo 'Hotel';?><span class="currency-symbol-right"></span>
                                    </label>

                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">

                                    <input type="radio" name="save_as" id="other" value="Other"
                                           class="custom-control-input">

                                    <label class="custom-control-label" for="other"><span
                                                class="currency-symbol-left"></span> <?php echo 'Other';?><span
                                                class="currency-symbol-right"></span></label>

                                </div>


                            </div>


                            <input type="hidden" name="address_lat" id="address_lat">
                            <input type="hidden" name="address_lng" id="address_lng">

                        </form>

                    </div>
                    <div class="modal-footer p-0 border-0">

                        <div class="col-6 m-0 p-0">

                            <button type="button" class="btn border-top btn-lg btn-block"
                                    data-dismiss="modal">{{trans('lang.close')}}
                            </button>

                        </div>

                        <div class="col-6 m-0 p-0">

                            <button type="button" class="btn btn-primary btn-lg btn-block"
                                    onclick="saveShippingAddress()">{{trans('lang.save')}}
                            </button>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@include('layouts.footer')


@include('layouts.nav')


<script type="text/javascript">


    var ref = database.collection('users').where('id', '==', user_uuid);

    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var append_list = '';
    var totalPayment = 0;

    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_digits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;

        if (currencyData.decimal_degits) {
            decimal_digits = currencyData.decimal_degits;
        }
    });


    $(document).ready(async function () {
        $("#data-table_processing").show();

        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';

        ref.get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();

                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    $("#data-table_processing").hide();
                } else {
                    $('.not_found_div').show();
                    $('#addAddress').modal('show');
                }
            }

        });

    });


    function buildHTML(snapshots) {
        var html = '';
        var shippingAddress = [];
        var val = snapshots.docs[0].data();
        if (val.hasOwnProperty('shippingAddress') && Array.isArray(val.shippingAddress)) {
            shippingAddress = val.shippingAddress;
            shippingAddress.forEach((listval, index) => {
                if (listval.isDefault == true) {
                    var BtnName = 'Default';
                    var className = 'btn-success';
                } else {
                    var BtnName = 'Mark As Default';
                    var className = 'btn-info';
                }

                var data_lat = (listval.hasOwnProperty('location') && listval.location.hasOwnProperty('latitude')) ? listval.location.latitude : null;
                var data_lng = (listval.hasOwnProperty('location') && listval.location.hasOwnProperty('longitude')) ? listval.location.longitude : null;

                defaultBtnHtml = '<a class="btn ' + className + ' ml-2" href="javascript:void(0)" id="default_' + index + '" name="mark-as-default" data-default="' + listval.isDefault + '">' + BtnName + '</a>';

                html = html + '<div class="transactions-list-wrap mt-4 col-md-6"><div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm"><div class="gold-members d-flex align-items-start transactions-list">';

                html = html + '<div class="media transactions-list-left"><div class="mr-3 font-weight-bold card-icon"><span><a href="javascript:void(0)" name="change-address" data-id="' + listval.id + '"><i class="feather-map-pin h2"></i></a></span></div>';
                html = html + '<a href="javascript:void(0)" name="change-address" data-locality="'+listval.locality+'" data-id="' + listval.id + '" data-lat="' + data_lat + '" data-lng="' + data_lng + '"><div class="media-body"><h6 class="date">' + listval.address + "," + listval.locality + " " + listval.landmark + '</h6></a>';

                html = html + '<button type="button" class="btn btn-info">' + listval.addressAs + '</button>' + defaultBtnHtml + '</div></div>';
                html = html + '<a class="btn btn-primary btn-sm ml-2" href="javascript:void(0)" id="' + listval.id + '" name="edit-address"><i class="fa fa-edit"></i></a><a href="javascript:void(0)" name="delete-address" data-id="' + listval.id + '" class="btn btn-primary btn-sm ml-2" href="javascript:void(0)"><i class="fa fa-trash"></i></a>'
                html = html + '</div> </div></div></div>';
            })

        }

        return html;

    }

    $(document).on("click", "#locality", function () {
        var input = document.getElementById('locality');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            address = place.formatted_address;
            $('#locality').val(address).attr('lat', place.geometry.location.lat()).attr('lng', place.geometry.location.lng());

        });

    });
    

    function saveShippingAddress() {
        var shippingAddress = [];
        var address = $('#address').val();
        var locality = $('#locality').val();
        var landmark = $('#landmark').val();
        var Lat = $('#locality').attr('lat');
        var Lng = $('#locality').attr('lng');
        var save_as = $('input[name="save_as"]:checked').val();
        var adrsId = $('#addressId').val();
        var id = (adrsId == '') ? database.collection('tmp').doc().id : adrsId;
        if (address == '') {
            $('#address_err').text('{{trans("lang.please_add_flat_no")}}')
            return false;
        }
        else if (locality == '') {
            $('#address_err').text('{{trans("lang.please_add_locality")}}')
            return false;
        } else if ((Lat == undefined && Lng == undefined) || (Lat == '' && Lng == '') || (Lat == null && Lng == null)) {
            $('#address_err').text('{{trans("lang.select_location_from_map_result")}}')
            return false;
        } else {
            var location = {'latitude': parseFloat(Lat), 'longitude': parseFloat(Lng)}
            var addAddress = {
                'address': address,
                'addressAs': save_as,
                'id': id,
                'isDefault': false,
                'landmark': landmark,
                'locality': locality,
                'location': location,
            }
            adrsId == '' ? (database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                        if (snapshot.docs.length > 0) {
                            var userData = snapshot.docs[0].data();
                            if (userData.hasOwnProperty('shippingAddress') && Array.isArray(userData.shippingAddress)) {
                                shippingAddress = userData.shippingAddress;
                            }
                            if (shippingAddress.length == 0) {
                                addAddress.isDefault = true;
                            }
                            shippingAddress.push(addAddress);
                            database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {
                                window.location.reload();
                            })
                        }
                    })
                ) :
                (
                    database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                        if (snapshot.docs.length > 0) {
                            var userData = snapshot.docs[0].data();
                            shippingAddress = userData.shippingAddress;
                            shippingAddress.forEach((listval, index) => {
                                if (listval.id == id) {
                                    shippingAddress[index] = addAddress;
                                }
                            })
                            database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {
                                window.location.reload();
                            })


                        }
                    })

                )
        }
    }

    $(document).on("click", "a[name='edit-address']", function () {
        var id = this.id;
        ref.get().then(async function (snapshots) {
            userData = snapshots.docs[0].data();
            var shippingAddress = userData.shippingAddress;
            shippingAddress.forEach((listval) => {
                if (listval.id == id) {
                    $('#addressId').val(listval.id);
                    $('#address').val(listval.address);
                    $('#locality').val(listval.locality);
                    $('#landmark').val(listval.landmark);
                    $('#locality').attr('lat', listval.location.latitude);
                    $('#locality').attr('lng', listval.location.longitude);
                    $('input[name="save_as"][value="' + listval.addressAs + '"]').prop('checked', true);
                }
            })
            $('#addAddress').modal('show');
        })
    })
    $(document).on("click", "a[name='mark-as-default']", function (e) {
        var index = (this.id).split("_")[1];
        checkDefault = $(this).attr('data-default');
        if (checkDefault == "false") {
            database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                if (snapshot.docs.length > 0) {
                    var userData = snapshot.docs[0].data();
                    var shippingAddress = userData.shippingAddress;
                    shippingAddress.forEach((listval, i) => {
                        if (listval.isDefault == true) {
                            shippingAddress[i].isDefault = false;
                            $('#default_' + i).removeClass('btn-success').addClass('btn-info').text('Mark As Default');
                            $('#default_' + i).attr('src', false);
                        }
                    })
                    $('#default_' + index).removeClass('btn-info').addClass('btn-success').text('Default');
                    $('#default_' + index).attr('src', true);

                    shippingAddress[index].isDefault = true;
                    database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {

                    })

                }
            })
        } else {

            e.preventDefault();
        }

    })
    $(document).on("click", "a[name='delete-address']", function () {
        var id = $(this).attr('data-id');
        var newShiipingAddress = [];
        ref.get().then(async function (snapshots) {
            userData = snapshots.docs[0].data();
            var shippingAddress = userData.shippingAddress;
            shippingAddress.forEach((listval) => {
                if (listval.id != id) {
                    newShiipingAddress.push(listval);
                }
            })
            database.collection('users').doc(user_uuid).update({'shippingAddress': newShiipingAddress}).then(function (result) {
                window.location.reload();

            })

        })
    })
    $(document).on("click", "a[name='change-address']", function () {
        var id = $(this).attr('data-id');
        var data_lat = $(this).attr('data-lat');
        var data_lng = $(this).attr('data-lng');
        var locality = $(this).attr('data-locality');

        setCookie('address_lat', data_lat, 365);
        setCookie('address_lng', data_lng, 365);
        setCookie('address_name', locality, 365);
        sessionStorage.setItem('addressId', id);

        window.location.href = "{{route('checkout')}}";

    });

    async function getCurrentDeliveryLocation() {

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
                        document.getElementById('locality').value = results[0].formatted_address;
                        address_lat = results[0].geometry.location.lat();
                        address_lng = results[0].geometry.location.lng();
                        $('#locality').attr('lat', address_lat).attr('lng', address_lng);
                    }

                }

            });
            try {

            } catch (err) {

            }

        }, function () {

        });

    }

</script>