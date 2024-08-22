@include('layouts.app')

@include('layouts.header')

<div class="siddhi-home-page">

    <div class="bg-primary px-3 d-none mobile-filter pb-3 section-content">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block">
                        <i class="feather-search"></i>
                    </button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto"
                       href="{{url('search')}}">{{trans('lang.filter')}}</a>
                </div>
            </div>

        </div>
    </div>

    <div class="ecommerce-banner multivendor-banner section-content">

        <div class="ecommerce-inner">

            <div class="" id="top_banner"></div>

        </div>
    </div>

    <div class="ecommerce-content multi-vendore-content section-content">

        <section class="restaurant_stories">
            <div class="container">
                <div id="stories" class="storiesWrapper stories"></div>
            </div>
        </section>

        <section class="top-categories top-categories-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.top_categories')}}</h5>
                    <span class="see-all ml-auto">
		                <a href="{{ url('categories')}}">{{trans('lang.see_all')}}</a>
		            </span>
                </div>

                <div class="top_categories" id="top_categories"></div>
            </div>

        </section>

        <section class="most-popular-item-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.popular')}} {{trans('lang.item')}}</h5>
                    <span class="see-all ml-auto">
		    <a href="{{ route('productlist.all') }}">{{trans('lang.see_all')}}</a>
		  </span>
                </div>
                <div id="most_popular_item"></div>
            </div>

        </section>

        <section class="most-popular-store-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.popular')}} {{trans('lang.restaurants')}}</h5>
                    <span class="see-all ml-auto">
		    <a href="{{route('restaurants','popular=yes')}}">{{trans('lang.see_all')}}</a>
		  </span>
                </div>
                <div id="most_popular_store"></div>
            </div>

        </section>

        <section class="new-arrivals-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.new_arrivals')}}</h5>
                    <span class="see-all ml-auto">
		                <a href="{{route('productlist.all')}}">{{trans('lang.see_all')}}</a>
		            </span>
                </div>
                <div id="new_arrival"></div>
            </div>

        </section>

        <section class="offers-coupons-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.offers')}} {{trans('lang.for_you')}}</h5>
                    <span class="see-all ml-auto">
		                <a href="{{ route('offers')}}">{{trans('lang.see_all')}}</a>
		            </span>
                </div>

                <div style="display:none" class="coupon_code_copied_div mt-4 error_top text-center">
                    <p>{{trans('lang.coupon_code_copied')}}</p>
                </div>

                <div id="offers_coupons"></div>
            </div>

        </section>

        <section class="middle-banners-section">

            <div class="container">

                <div id="middle_banner"></div>

            </div>

        </section>

        <section class="home-categories-section">

            <div class="container" id="home_categories"></div>

        </section>

        <section class="all-stores-section">

            <div class="container">

                <div class="title d-flex align-items-center">
                    <h5>{{trans('lang.all_stores')}}</h5>
                    <span class="see-all ml-auto">
		                <a href="{{url('restaurants')}}">{{trans('lang.see_all')}}</a>
		            </span>
                </div>

                <div id="all_stores"></div>

                <div class="row fu-loadmore-btn">
                    <a class="page-link loadmore-btn" href="javascript:void(0);" onclick="moreload()" data-dt-idx="0" tabindex="0" id="loadmore">{{trans('lang.see')}} {{trans('lang.more')}}</a>
                    <p class="text-danger" style="display:none;" id="noMoreCoupons">{{trans('lang.no_results')}}</p>
                </div>
            </div>

        </section>

    </div>

    <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
        {{trans('lang.processing')}}
    </div>

    <div class="zone-error m-5 p-5" style="display: none;">
        <div class="zone-image text-center">
            <img src="{{asset('img/zone_logo.png')}}" width="100">
        </div>
        <div class="zone-content text-center text-center font-weight-bold text-danger">
            <h3 class="title">{{trans('lang.zone_error_title')}}</h3>
            <h6 class="text">{{trans('lang.zone_error_text')}}</h6>
        </div>
    </div>
    
</div>

@include('layouts.footer')

<!-- lib styles -->
<link rel="stylesheet" href="{{asset('css/dist/zuck.min.css')}}">
<link rel="stylesheet" href="{{asset('css/dist/skins/snapssenger.css')}}">
<script src="{{asset('js/dist/zuck.min.js')}}"></script>

<script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>

<script type="text/javascript">
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var vendorId;
    var ref;
    var append_list = '';
    var top_categories = '';
    var most_popular = '';
    var most_sale = '';
    var new_product = '';
    var offers_coupons = '';
    var appName = '';
    var popularStoresList = [];
    var currentCurrency = '';
    var currencyAtRight = false;
    var storyEnabled = false;
    var VendorNearBy = '';
    var pagesize = 12;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var nearByVendorsForStory = [];
    var vendorIds = [];

    var DriverNearByRef = database.collection('settings').doc('RestaurantNearBy');
    var itemCategoriesref = database.collection('vendor_categories').where('publish', '==', true).limit(7);
    var vendorsref = geoFirestore.collection('vendors');
    var productref = database.collection('vendor_products').where('publish', '==', true);
    var bannerref = database.collection('menu_items').where("is_publish", "==", true).orderBy('set_order', 'asc');
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    
    var decimal_degits = 0;
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })

    database.collection('settings').doc("story").get().then(async function (snapshots) {
        var story_data = snapshots.data();
        if (story_data.isEnabled) {
            storyEnabled = true;
        } else {
            $(".restaurant_stories").remove();
        }
    });

    var refs = database.collection('vendors').where('title', '!=', '').orderBy('title').limit(pagesize);
    var couponsRef = database.collection('coupons').where('isEnabled', '==', true).orderBy("expiresAt").startAt(new Date()).limit(4);

    function getBanners(){

        var available_stores = [];
        geoFirestore.collection('vendors').where('zoneId','==',user_zone_id).get().then(async function (snapshots) {
            snapshots.docs.forEach((doc) => {
                available_stores.push(doc.id);
            });
        });
        
        var position1_banners = [];
        var position2_banners = [];

        bannerref.get().then(async function (banners) {

            banners.docs.forEach((banner) => {
                var bannerData = banner.data();
                var redirect_type = '';
                var redirect_id = '';
                if (bannerData.position == 'top') {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }

                    var object = {
                        'photo': bannerData.photo,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }

                    position1_banners.push(object);
                }

                if (bannerData.position == 'middle') {

                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.photo,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }
                    position2_banners.push(object);
                }
            });

            if (position1_banners.length > 0) {
                var html = '';
                for (banner of position1_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';

                    var redirect_id = '#';

                    if (banner.redirect_type != '') {
                        if (banner.redirect_type == "store") {

                            redirect_id = "{{ route('restaurant',':id')}}";
                            redirect_id = redirect_id.replace(':id', 'id=' + banner.redirect_id);

                        } else if (banner.redirect_type == "product") {

                            redirect_id = "{{ route('productDetail',':id')}}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);


                        } else if (banner.redirect_type == "external_link") {
                            redirect_id = banner.redirect_id;
                        }
                    }

                    if(!jQuery.inArray(banner.redirect_id,available_stores) != -1) {
                        redirect_id = '#';
                    }

                    html += '<a href="' + redirect_id + '"><img src="' + banner.photo + '"></a>';
                    html += '</div>';
                    html += '</div>';
                }

                $("#top_banner").html(html);
            } else {
                $('.ecommerce-banner').remove();
            }

            if (position2_banners.length > 0) {
                var html = '';
                for (banner of position2_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';

                    var redirect_id = 'javascript::void()';
                    if (banner.redirect_type != '') {
                        if (banner.redirect_type == "store") {

                            redirect_id = "{{ route('restaurant',':id')}}";
                            redirect_id = redirect_id.replace(':id', 'id=' + banner.redirect_id);

                        } else if (banner.redirect_type == "product") {

                            redirect_id = "{{ route('productDetail',':id')}}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);


                        } else if (banner.redirect_type == "external_link") {
                            redirect_id = banner.redirect_id;
                        }
                    }
                    
                    if(!jQuery.inArray(banner.redirect_id,available_stores) != -1) {
                        redirect_id = '#';
                    }

                    html += '<a href="' + redirect_id + '"><img src="' + banner.photo + '"></a>';
                    html += '</div>';
                    html += '</div>';
                }
                $("#middle_banner").html(html);

            } else {
                $('.middle-banners').remove();
            }

            setTimeout(function(){
                slickcatCarousel();
            },200)
        });

    }
    
    var myInterval = setInterval(callStore, 1000);

    function myStopTimer() {
        clearInterval(myInterval);
    }

    async function callStore() {
        if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
            return false;
        }
        DriverNearByRef.get().then(async function (DriverNearByRefSnapshots) {
            var DriverNearByRefData = DriverNearByRefSnapshots.data();
            VendorNearBy = parseInt(DriverNearByRefData.radios);
            address_lat = parseFloat(address_lat);
            address_lng = parseFloat(address_lng);
            if(user_zone_id == null){
                jQuery(".section-content").remove();
                jQuery(".zone-error").show();
                jQuery("#data-table_processing").hide();
                return false;
            }
            myStopTimer();
            getBanners();
            getItemCategories();
            getHomepageCategory();
            getMostPopularStores();
            getAllStore();
            jQuery("#data-table_processing").hide();
        })
    }

    function slickcatCarousel() {

        if ($("#top_banner").html() != "") {
            $('#top_banner').slick({
                slidesToShow: 1,
                dots: true,
                arrows: true
            });
        }

        if ($("#middle_banner").html() != "") {

            $('#middle_banner').slick({
                slidesToShow: 3,
                dots: true,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    { 
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
    }

    async function getAllStore() {

        if (VendorNearBy) {
            var nearestRestauantRefnew = geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                radius: VendorNearBy
            }).where('zoneId','==',user_zone_id);
        } else {
            var nearestRestauantRefnew = geoFirestore.collection('vendors').where('zoneId','==',user_zone_id);
        }
        
        nearestRestauantRefnew.get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                var html = buildAllStoresHTML(snapshots);
                var all_stores = document.getElementById('all_stores');
                all_stores.innerHTML = html;

                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);

                if (snapshots.docs.length < pagesize) {
                    $('#loadmore').hide();
                }
            }else{
                $(".all-stores-section").remove();                
                $(".new-arrivals-section").remove();
                $(".section-content").remove();
                jQuery(".zone-error").show();
                jQuery(".zone-error").find('.title').text('{{trans("lang.restaurant_error_title")}}');
                jQuery(".zone-error").find('.text').text('{{trans("lang.restaurant_error_text")}}');
            }
        });
    }

    function buildAllStoresHTML(snapshots) {
        var html = '';
        var alldata = [];

        if (snapshots.docs.length > 0) {

            snapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
                vendorIds.push(listval.id);
            });

            //New arrivals products
            var newProductRef = database.collection('vendor_products').where('publish', '==', true).limit(4);
            newProductRef.get().then(async function (newProductSnapshot) {
                if(newProductSnapshot.docs.length > 0){
                    new_arrival = document.getElementById('new_arrival');
                    new_arrival.innerHTML = '';
                    var newproducthtml = buildHTMLNewProducts(newProductSnapshot);
                    new_arrival.innerHTML = newproducthtml;
                }else{
                    $(".new-arrivals-section").remove();   
                }
            });

            var count = 0;

            html = html + '<div class="row">';

            alldata.forEach((listval) => {

                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }

                var status = 'Closed';
                var statusclass = "closed";
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var currentdate = new Date();
                var currentDay = days[currentdate.getDay()];
                hour = currentdate.getHours();
                minute = currentdate.getMinutes();
                if (hour < 10) {
                    hour = '0' + hour
                }
                if (minute < 10) {
                    minute = '0' + minute
                }
                var currentHours = hour + ':' + minute;
                if (val.hasOwnProperty('workingHours')) {
                    for (i = 0; i < val.workingHours.length; i++) {
                        var day = val.workingHours[i]['day'];
                        if (val.workingHours[i]['day'] == currentDay) {
                            if (val.workingHours[i]['timeslot'].length != 0) {
                                for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                    var timeslot = val.workingHours[i]['timeslot'][j];
                                    var from = timeslot[`from`];
                                    var to = timeslot[`to`];
                                    if (currentHours >= from && currentHours <= to) {
                                        status = '{{trans("lang.open")}}';
                                        statusclass = "open";
                                    }
                                }
                            }


                        }
                    }
                }

                var vendor_id_single = val.id;
                var view_vendor_details = "{{ route('restaurant',':id') }}";
                view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);
                count++;

                getMinDiscount(val.id);

                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';

                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }

                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' + val.location + '</p>';

                html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';

                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

                html = html + '</div>';
                html = html + '</div></div></div>';

            });

            html = html + '</div>';

        } else {

            $('#noMoreCoupons').show();
            $('#loadmore').hide();
            setTimeout(function () {
                $("#noMoreCoupons").hide();
            }, 4000);
        }

        return html;
    }

    async function moreload() {

        all_stores = document.getElementById('all_stores');

        if (start != undefined || start != null) {

            jQuery("#data-table_processing").hide();

            listener = refs.where('zoneId','==',user_zone_id).startAfter(start).limit(pagesize).get();
            
            listener.then(async (snapshots) => {

                html = '';
                html = buildAllStoresHTML(snapshots);

                jQuery("#data-table_processing").hide();
                if (html != '') {
                    all_stores.innerHTML += html;
                    start = snapshots.docs[snapshots.docs.length - 1];

                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);

                    if (snapshots.docs.length < pagesize) {
                        $('#loadmore').hide();
                    }
                }

            });
        }
    }

    async function getItemCategories() {
        itemCategoriesref.get().then(async function (foodCategories) {
            top_categories = document.getElementById('top_categories');
            top_categories.innerHTML = '';
            foodCategorieshtml = buildHTMLItemCategory(foodCategories);
            top_categories.innerHTML = foodCategorieshtml;
        })
    }

    async function getHomepageCategory() {

        var home_cat_ref = database.collection('vendor_categories').where("publish", "==", true).where('show_in_homepage', '==', true).limit(5);

        home_cat_ref.get().then(async function (homeCategories) {
            home_categories = document.getElementById('home_categories');
            home_categories.innerHTML = '';

            var homeCategorieshtml = '';
            var alldata = [];
            homeCategories.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });

            for (listval of alldata) {

                var val = listval;
                var category_id = val.id;

                var category_route = "{{ route('RestaurantsbyCategory',[':id']) }}";
                category_route = category_route.replace(':id', category_id);

                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }

                var haveStores = await catHaveStores(category_id);
                if (haveStores == true) {
                    homeCategorieshtml += '<div class="category-content mb-5">';
                        homeCategorieshtml += '<div class="title d-flex align-items-center">';
                            homeCategorieshtml += '<h5>' + val.title + '</h5>';
                            homeCategorieshtml += '<span class="see-all ml-auto"><a href="' + category_route + '">{!! trans("lang.see_all") !!}</a></span>';
                        homeCategorieshtml += '</div>';
                            var productHtml = await buildHTMLHomeCategoryStores(category_id);
                            homeCategorieshtml += productHtml;
                    homeCategorieshtml += '</div>';
                }
            }
            if (homeCategorieshtml != '') {
                home_categories.innerHTML = homeCategorieshtml;
            } else {
                $('.home-categories-section').remove();
            }
        })
    }

    async function catHaveStores(categoryId) {
        var snapshots = await database.collection('vendors').where("categoryID", "==", categoryId).where('zoneId','==',user_zone_id).get();
        if (snapshots.docs.length > 0) {
            return true;
        } else {
            return false;
        }
    }

    async function buildHTMLHomeCategoryStores(category_id) {
        
        var html = '';
        var snapshots = await database.collection('vendors').where('categoryID', "==", category_id).where('zoneId','==',user_zone_id).limit(4).get();

        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        var count = 0;

        html = html + '<div class="row">';

        alldata.forEach((listval) => {

            var val = listval;
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);

                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }

            var status = 'Closed';
            var statusclass = "closed";
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var currentdate = new Date();
            var currentDay = days[currentdate.getDay()];
            hour = currentdate.getHours();
            minute = currentdate.getMinutes();
            if (hour < 10) {
                hour = '0' + hour
            }
            if (minute < 10) {
                minute = '0' + minute
            }
            var currentHours = hour + ':' + minute;
            if (val.hasOwnProperty('workingHours')) {
                for (i = 0; i < val.workingHours.length; i++) {
                    var day = val.workingHours[i]['day'];
                    if (val.workingHours[i]['day'] == currentDay) {
                        if (val.workingHours[i]['timeslot'].length != 0) {
                            for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                var timeslot = val.workingHours[i]['timeslot'][j];
                                var from = timeslot[`from`];
                                var to = timeslot[`to`];
                                if (currentHours >= from && currentHours <= to) {
                                    status = '{{trans("lang.open")}}';
                                    statusclass = "open";
                                }
                            }
                        }


                    }
                }
            }

            var vendor_id_single = val.id;
            var view_vendor_details = "{{ route('restaurant',':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);
            count++;

            getMinDiscount(val.id);

            html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';

            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }

            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' + val.location + '</p>';

            html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';

            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

            html = html + '</div>';
            html = html + '</div></div></div>';

        });

        html = html + '</div>';

        return html;
    }

    function buildHTMLItemCategory(foodCategories) {
        var html = '';
        var alldata = [];
        foodCategories.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        html += '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;

            var category_id = val.id;
            var trending_route = "{{ route('RestaurantsbyCategory',[':id']) }}";
            trending_route = trending_route.replace(':id', category_id);

            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<div class="col-md-2 top-cat-list"><a class="d-block text-center cat-link" href="' + trending_route + '"><span class="cat-img"><img alt="#" src="' + photo + '" class="img-fluid mb-2"></span><h4 class="m-0">' + val.title + '</h4></a></div>';

        });
        html += '</div>';
        return html;
    }

    async function getPopularItem() {
        
        if (popularStoresList.length > 0) {

            var popularStoresListnw = [];

            most_popular_item = document.getElementById('most_popular_item');
            most_popular_item.innerHTML = '';

            var from = 0;
            var total = 0;
            for (let i = 0; i < (popularStoresList.length / 10); i++) {
                from = i * 10;
                popularStoresListnw = [];
                total = 0;
                for (let j = 0; j < popularStoresList.length; j++) {
                    if (j > from && total < 10) {
                        total++;
                        popularStoresListnw.push(popularStoresList[j]);
                    }
                }
                if (popularStoresListnw.length) {
                    var refpopularItem = database.collection('vendor_products').where("vendorID", "in", popularStoresListnw);
                    refpopularItem.get().then(async function (snapshotsPopularItem) {
                        var trendingStorehtml = buildHTMLPopularItem(snapshotsPopularItem);
                        most_popular_item.innerHTML = trendingStorehtml;
                    });
                }else{
                    $(".most-popular-item-section").remove();
                }
            }
        }
    }

    async function getMostPopularStores() {
          
        var popularRestauantRefnew = geoFirestore.collection('vendors').near({
            center: new firebase.firestore.GeoPoint(address_lat, address_lng),
            radius: VendorNearBy
        }).limit(200).where('zoneId','==',user_zone_id);

        await popularRestauantRefnew.get().then(async function (popularRestauantSnapshot) {
            if(popularRestauantSnapshot.docs.length > 0){
                var most_popular_store = document.getElementById('most_popular_store');
                most_popular_store.innerHTML = '';
                var popularStorehtml = await buildHTMLPopularStore(popularRestauantSnapshot);
                most_popular_store.innerHTML = popularStorehtml;
            }else{
                $(".most-popular-store-section").remove();
                $(".most-popular-item-section").remove();
                $('.offers-coupons-section').remove();
            }
        });

        if (storyEnabled) {
            await getStories();
        }
    }

    function buildHTMLMostSaleStore(mostSaleSnapshot) {
        var html = '';
        var alldata = [];
        mostSaleSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;

            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;

            alldata.push(datas);
        });

        if (alldata.length) {
            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 4);
        }

        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var vendor_id_single = val.id;

            var view_vendor_details = "";
            if (vendor_id_single) {
                view_vendor_details = "{{ route('restaurant',':id') }}";
                view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);
            }

            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }

            var status = 'Closed';
            var statusclass = "closed";
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var currentdate = new Date();
            var currentDay = days[currentdate.getDay()];
            hour = currentdate.getHours();
            minute = currentdate.getMinutes();
            if (hour < 10) {
                hour = '0' + hour
            }
            if (minute < 10) {
                minute = '0' + minute
            }
            var currentHours = hour + ':' + minute;
            if (val.hasOwnProperty('workingHours')) {
                for (i = 0; i < val.workingHours.length; i++) {
                    var day = val.workingHours[i]['day'];
                    if (val.workingHours[i]['day'] == currentDay) {
                        if (val.workingHours[i]['timeslot'].length != 0) {
                            for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                var timeslot = val.workingHours[i]['timeslot'][j];
                                var from = timeslot[`from`];
                                var to = timeslot[`to`];
                                if (currentHours >= from && currentHours <= to) {
                                    status = '{{trans("lang.open")}}';
                                    statusclass = "open";
                                }
                            }
                        }


                    }
                }
            }

            getMinDiscount(val.id);

            html = html + '<div class="col-md-3 pro-list">' +
                '<div class="list-card position-relative">' +
                '<div class="py-2 position-relative">' +
                '<div class="list-card-body">' +
                '<div class="list-card-top">' +
                '<h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><h6>' + val.location + '</h6>';
        
            html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';

            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
        
            html = html + '</div><div class="list-card-image">';

            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });

        html = html + '</div>';
        return html;
    }

    function buildHTMLNewProducts(newProductSnapshot) {
        var html = '';
        var alldata = [];
        newProductSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;
            if($.inArray(datas.vendorID,vendorIds) !== -1){
                alldata.push(datas);
            }
        });

        html = html + '<div class="row">';
        
        alldata.forEach((listval) => {
            var val = listval;
            var vendor_id_single = val.id;

            var view_vendor_details = "{{ route('productDetail',':id')}}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);

            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            var status = '{{trans("lang.non_veg")}}';
            var statusclass = "closed";
            if (val.veg == true) {
                var status = '{{trans("lang.veg")}}';
                var statusclass = "open";
            }
            getMinDiscount(val.vendorID);

            html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';


            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }

            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.name + '</a></h6>';
            html = html + '<h6 class="text-gray mb-1 cat-title" id="popular_food_category_' + val.categoryID + '_' + val.id + '"></h6>';

            if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0' && val.item_attribute == null) {
                var or_price = getFormattedPrice(parseFloat(val.price));
				var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                html = html + '<h6 class="text-gray mb-1 pro-price">' + dis_price + '  <s>' + or_price + '</s></h6>';
            } else {
                if (val.item_attribute != null && val.item_attribute != "" && val.item_attribute.attributes.length > 0 && val.item_attribute.variants.length > 0) {
                    var variants_prices = [];
                    var variants = val.item_attribute.variants;
                    for(variant of variants){
                        variants_prices.push(variant.variant_price);
                    }
                    var min_price = Math.min.apply(Math,variants_prices);
                    var max_price = Math.max.apply(Math,variants_prices);
                    if(min_price != max_price){
                        var or_price = getFormattedPrice(parseFloat(min_price)) + " - "+getFormattedPrice(parseFloat(max_price));
                    }else{
                        var or_price = getFormattedPrice(parseFloat(max_price));    
                    }
                }else{
                    var or_price = getFormattedPrice(parseFloat(val.price));
                }
                html = html + '<h6 class="text-gray mb-1 pro-price">' + or_price + '</h6>';
            }

            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

            html = html + '</div>';
            html = html + '</div></div></div>';
        });

        html = html + '</div>';

        return html;
    }

    sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return b[key] - a[key];
        });
    };

    function buildHTMLPopularStore(popularRestauantSnapshot) {

        var html = '';
        var alldata = [];
        popularRestauantSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;
            if (datas.title != '') {
                alldata.push(datas);
                if (nearByVendorsForStory.includes(datas.id)) {
                } else {
                    nearByVendorsForStory.push(datas.id);
                }
            }
        });

        
        if (alldata.length) {

            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 4);

            var count = 0;
            var popularItemCount = 0;

            html = html + '<div class="row">';
            
            alldata.forEach((listval) => {

                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }

                if (popularItemCount < 10) {
                    popularItemCount++;
                    popularStoresList.push(val.id);
                }

                var status = 'Closed';
                var statusclass = "closed";
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var currentdate = new Date();
                var currentDay = days[currentdate.getDay()];
                hour = currentdate.getHours();
                minute = currentdate.getMinutes();
                if (hour < 10) {
                    hour = '0' + hour
                }
                if (minute < 10) {
                    minute = '0' + minute
                }
                var currentHours = hour + ':' + minute;
                if (val.hasOwnProperty('workingHours')) {
                    for (i = 0; i < val.workingHours.length; i++) {
                        var day = val.workingHours[i]['day'];
                        if (val.workingHours[i]['day'] == currentDay) {
                            if (val.workingHours[i]['timeslot'].length != 0) {
                                for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                    var timeslot = val.workingHours[i]['timeslot'][j];
                                    var from = timeslot[`from`];
                                    var to = timeslot[`to`];
                                    if (currentHours >= from && currentHours <= to) {
                                        status = '{{trans("lang.open")}}';
                                        statusclass = "open";
                                    }
                                }
                            }


                        }
                    }
                }

                var vendor_id_single = val.id;
                var view_vendor_details = "{{ route('restaurant',':id') }}";
                view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);
                count++;
                getMinDiscount(val.id);

                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image"><span class="discount-price vendor_dis_' + val.id + ' " ></span>';

                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }

                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' + val.location + '</p>';

                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

                html = html + '</div>';
                html = html + '</div></div></div>';

            });

            html = html + '</div>';
        }else{
            html = '<p class="text-danger text-center">{{trans("lang.no_results")}}</p>';
        }
        getPopularItem();
        getCouponsList();
        return html;
    }

    function buildHTMLPopularItem(popularItemsnapshot) {
        var html = '';
        var alldata = [];
        popularItemsnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;           
            alldata.push(datas);
        });

        var count = 1;

        html = html + '<div class="row">';
        alldata.forEach((listval) => {

            if (count < 5) {
                var val = listval;
                var vendor_id_single = val.id;

                var view_vendor_details = "{{ route('productDetail',':id')}}";
                view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);

                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                var status = '{{trans("lang.non_veg")}}';
                var statusclass = "closed";
                if (val.veg == true) {
                    var status = '{{trans("lang.veg")}}';
                    var statusclass = "open";
                }
                getMinDiscount(val.vendorID);

                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';


                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }

                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.name + '</a></h6>';
                var popularItemCategorytitle = popularItemCategory(val.categoryID, val.id);
                html = html + '<h6 class="text-gray mb-1 cat-title" id="popular_food_category_' + val.categoryID + '_' + val.id + '"></h6>';

                if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0' && val.item_attribute == null) {
                    var or_price = getFormattedPrice(parseFloat(val.price));
				    var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                    html = html + '<h6 class="text-gray mb-1 pro-price">' + dis_price + '  <s>' + or_price + '</s></h6>';
                } else {
                    if (val.item_attribute != null && val.item_attribute != "" && val.item_attribute.attributes.length > 0 && val.item_attribute.variants.length > 0) {
                        var variants_prices = [];
                        var variants = val.item_attribute.variants;
                        for(variant of variants){
                            variants_prices.push(variant.variant_price);
                        }
                        var min_price = Math.min.apply(Math,variants_prices);
                        var max_price = Math.max.apply(Math,variants_prices);
                        if(min_price != max_price){
                            var or_price = getFormattedPrice(parseFloat(min_price)) + " - "+getFormattedPrice(parseFloat(max_price));
                        }else{
                            var or_price = getFormattedPrice(parseFloat(max_price));    
                        }
                    }else{
                        var or_price = getFormattedPrice(parseFloat(val.price));
                    }
                    html = html + '<h6 class="text-gray mb-1 pro-price">' + or_price + '</h6>';
                }

                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

                html = html + '</div>';
                html = html + '</div></div></div>';

            }

            count = count + 1;

        });

        html = html + '</div>';

        return html;
    }

    async function popularItemCategory(categoryId, foodId) {
        var popularItemCategory = '';

        await database.collection('vendor_categories').where("id", "==", categoryId).get().then(async function (categorySnapshots) {

            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                popularItemCategory = categoryData.title;
                jQuery("#popular_food_category_" + categoryId + "_" + foodId).text(popularItemCategory);
            }
        });
        return popularItemCategory;
    }

    async function getMinDiscount(vendorId) {
        var min_discount = '';
        var disdata = [];

        var couponSnapshots = await couponsRef.where('resturant_id', '==', vendorId).get();
        if(couponSnapshots.docs.length > 0){
            couponSnapshots.docs.forEach((coupon) => {
                var cdata = coupon.data();
                disdata.push(parseInt(cdata.discount));
            });
            if (disdata.length > 0) {
                discount = Math.min.apply(Math, disdata);
                min_discount = "Min " + discount + "% off";
            }
        }

        if (min_discount) {
            $('.vendor_dis_' + vendorId).text(min_discount);
        } else {
            $('.vendor_dis_' + vendorId).hide();
        }
    }

    async function getCouponsList() {

        if (popularStoresList.length > 0) {
            var popularStoresList2 = popularStoresList.slice(0, 4);
            var couponsRef2 = database.collection('coupons').where('resturant_id', 'in', popularStoresList2).where('isEnabled', '==', true).where('isPublic', '==', true).where('expiresAt', '>=', new Date());
            couponsRef2.get().then(async function (couponListSnapshot) {
                if(couponListSnapshot.docs.length > 0){
                    offers_coupons = document.getElementById('offers_coupons');
                    offers_coupons.innerHTML = '';
                    var couponlistHTML = buildHTMLCouponList(couponListSnapshot);
                    offers_coupons.innerHTML = couponlistHTML;
                }else{
                    $('.offers-coupons-section').remove();
                }
            })
        }
    }

    function buildHTMLCouponList(couponListSnapshot) {
        var html = '';
        var alldata = [];
        couponListSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;

            alldata.push(datas);
        });

        if (alldata.length > 0) {

            html = html + '<div class="row">';

            alldata.forEach((listval) => {

                var val = listval;


                var status = '{{trans("lang.closed")}}';
                var statusclass = "closed";
                if (val.hasOwnProperty('reststatus') && val.reststatus) {
                    status = '{{trans("lang.open")}}';
                    statusclass = "open";
                }

                var vendor_id_single = val.resturant_id;
                var view_vendor_details = "";
                if (vendor_id_single) {
                    view_vendor_details = "{{ route('restaurant',':id') }}";
                    view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);

                }

                html = html + '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';

                if (val.image) {
                    photo = val.image;
                } else {
                    photo = placeholderImageSrc;
                }

                getVendorName(vendor_id_single);

                html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black vendor_title_' + vendor_id_single + '"></a></h6>';

                html = html + '<div class="text-gray mb-1 small offer-code"><a href="javascript:void(0)" onclick="copyToClipboard(`' + val.code + '`)"><i class="fa fa-file-text-o"></i> ' + val.code + '</a></div>';

                html = html + '</div>';
                html = html + '</div></div></div>';

            });

            html = html + '</div>';
        }

        return html;
    }

    async function getVendorName(vendorId) {
        await database.collection('vendors').where("id", "==", vendorId).get().then(async function (categorySnapshots) {
            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                vendorName = categoryData.title;
                jQuery(".vendor_title_" + vendorId).text(vendorName);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const stories = document.querySelectorAll('.story');
    });

    async function getStories() {

        var alldata = [];
        var storyDatas = [];
        var queryPromises = [];

        var stories = document.querySelectorAll('.story');

        for (var i = 0; i < nearByVendorsForStory.length; i++) {
            const query = await database.collection('story').where('vendorID', '==', nearByVendorsForStory[i]).get();
            queryPromises.push(query);
        }

        await Promise.all(queryPromises).then((querySnapshots) => {
            for (const querySnapshot of querySnapshots) {
                querySnapshot.forEach((doc) => {
                    alldata.push(doc.data());
                });
            }
        });

        for (data of alldata) {

            var vendorDataRes = await database.collection('vendors').doc(data.vendorID).get();
            var vendorData = vendorDataRes.data();

            if (vendorData != undefined) {

                var vendorRating = '';
                if (vendorData.hasOwnProperty('reviewsSum') && vendorData.reviewsSum != 0 && vendorData.hasOwnProperty('reviewsCount') && vendorData.reviewsCount != 0) {
                    rating = (vendorData.reviewsSum / vendorData.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = vendorData.reviewsCount;
                    vendorRating = vendorRating + '<div class="star position-relative ml-1 mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                }

                var vendorLink = "{{ route('restaurant',':id')}}";
                vendorLink = vendorLink.replace(':id', 'id=' + vendorData.id);

                var itemsObject = [];
                data.videoUrl.forEach((video) => {
                    var itemObject = {
                        id: vendorData.id,
                        type: "video",
                        length: 5,
                        src: video,
                        link: vendorLink,
                        linkText: vendorData.title,
                        time: new Date(data.createdAt.toDate()).getTime() / 1000,
                        seen: false
                    };
                    itemsObject.push(itemObject);
                });

                var storyObject = {
                    id: vendorData.id,
                    photo: data.videoThumbnail,
                    name: vendorData.title,
                    link: vendorLink,
                    seen: false,
                    items: itemsObject
                }
                storyDatas.push(storyObject);
            }
        }

        if (storyDatas.length) {

            stories = new Zuck('stories', {
                backNative: true,
                previousTap: true,
                skin: 'snapssenger',
                autoFullScreen: true,
                avatars: true,
                list: false,
                cubeEffect: true,
                localStorage: true,
                stories: storyDatas,
                language: {
                    unmute: '<i class="fa fa-volume-up"></i>',
                }
            });

            $('#stories').slick({
                slidesToShow: 5,
                dots: false,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 2,
                        }
                    }
                ]
            });

        }

        return true;
    }
</script>

@include('layouts.nav')
