@include('layouts.app')

@include('layouts.header')


<div class="d-none">

    <div class="bg-primary p-3 d-flex align-items-center">

        <a class="toggle togglew toggle-2" href="#"><span></span></a>

        <h4 class="font-weight-bold m-0 text-white">{{trans('lang.search')}}</h4>

    </div>

</div>

<div class="siddhi-popular">


    <div class="container">

        <div class="search py-5">

            <div class="input-group mb-4">


                <input type="text" class="form-control form-control-lg input_search border-right-0 food_search"
                       id="inlineFormInputGroup" value="" placeholder="{{trans('lang.search_product_items')}}">

                <div class="input-group-prepend">

                    <div class="btn input-group-text bg-white border_search border-left-0 text-primary search_food_btn">
                        <i class="feather-search"></i>

                    </div>

                </div>


            </div>


            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">

                <li class="nav-item" role="presentation">

                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab"
                       href="#home" role="tab" aria-controls="home" aria-selected="true"><i
                                class="feather-home mr-2"></i><span class="restaurant_counts"></span></a>

                </li>

            </ul>

            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.Processing')}}</div>


            <div class="text-center py-5 not_found_div" style="display:none">

                <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>

                <p class="font-weight-bold text-dark h5">{{trans('lang.nothing_found')}}</p>

                <p>{{trans('lang.please_try_again')}}</p>

            </div>


            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="container mt-4 mb-4 p-0">

                        <div id="append_list1" class="res-search-list-1"></div>

                    </div>

                </div>

            </div>


            <ul class="nav nav-tabs border-0 d-none" id="myTab2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab"
                       href="#home" role="tab" aria-controls="home" aria-selected="true"><i
                                class="feather-home mr-2"></i><span class="products_counts"></span></a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list2" class="res-search-list-1"></div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                <div class="row d-flex align-items-center justify-content-center py-5">

                    <div class="col-md-4 py-5">

                    </div>

                </div>


            </div>


        </div>


    </div>

</div>


@include('layouts.footer')


@include('layouts.nav')


<script type="text/javascript">
    
    var currentCurrency = '';
    var currencyAtRight = false;
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })

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

    var productdata = [];
    var vendordata = [];
    var productsref = database.collection('vendor_products').where('publish', '==', true);
    var vendorsref = database.collection('vendors');

    var append_list = document.getElementById('append_list1');
    var append_list2 = document.getElementById('append_list2');

    async function getProductList(){
        var vendorIds = [];
		var vendorsSnapshots = await database.collection('vendors').where('zoneId','==',user_zone_id).get();
        if(vendorsSnapshots.docs.length > 0){
            vendorsSnapshots.docs.forEach((listval) => {
				vendorIds.push(listval.id);
			});	
        }
        var vendorIdsChunk = [];
        while (vendorIds.length > 0){
            vendorIdsChunk.push(vendorIds.splice(0, 10));
        }
        for(vendorIds of vendorIdsChunk){
            productsref.where("vendorID","in",vendorIds).get().then(async function (productsnapshot) {
                productsnapshot.docs.forEach((listval) => {
                    var val = listval.data();
                    productdata.push(val);
                });
            });
        }
    }

    async function getVendorList(){
        vendorsref.where('zoneId','==',user_zone_id).get().then(async function (vendorsnapshot) {
            vendorsnapshot.docs.forEach((listval) => {
                var val = listval.data();
                vendordata.push(val);
            });
        });
    }

    $(document).ready(function () {

        setTimeout( function(){ 
            getProductList();
            getVendorList();
            getResults();
        } ,1500);
        
        $(".food_search").keypress(function (e) {
            if (e.which == 13) {
                getResults();
            }
        })

        $(".search_food_btn").click(function () {
            getResults();
        });

    });

    async function getResults() {

        var vendors = [];

        $("#data-table_processing").show();
        var foodsearch = $(".food_search").val();

        var filter_product = [];
        var products = [];

        if (foodsearch != '') {

            productdata.forEach((listval) => {
                var data = listval;
                var Name = data.name.toLowerCase();
                var Ans = Name.indexOf(foodsearch.toLowerCase());
                if (Ans > -1) {
                    filter_product.push(data);
                    if (!products.includes(data.vendorID)) {
                        products.push(data.vendorID);
                    }
                }
            });

            if (products.length > 0) {
                for (i = 0; i < products.length; i++) {
                    var vendorId = products[i];
                    await database.collection('vendors').doc(vendorId).get().then(async function (snapshotss) {
                        var vendor_data = snapshotss.data();
                        if (vendor_data != undefined) {
                            vendors.push(vendor_data);
                        }
                    });
                }
            }

            vendordata.forEach((listval) => {
                var data = listval;
                var Name = data.title.toLowerCase();
                var Ans = Name.indexOf(foodsearch.toLowerCase());

                if (Ans > -1) {
                    if (!products.includes(data.id)) {
                        vendors.push(data);
                    }

                }
            });

        } else {

            await vendorsref.where('zoneId','==',user_zone_id).get().then(async function (snapshots) {
                if (snapshots != undefined) {
                    snapshots.docs.forEach((listval) => {
                        var datas = listval.data();
                        vendors.push(datas);
                    });
                }
            });
            $('#myTab2').hide();
        }

        var html_keypress = '';

        html_keypress = buildHTML(vendors);
        product_keypress = buildProductHTML(filter_product);

        if (html_keypress == '' && product_keypress == '') {
            $(".not_found_div").show();
            append_list.innerHTML = '';
            append_list2.innerHTML = '';
            $(".restaurant_counts").text('{{trans("lang.stores")}} (0)');
            $(".products_counts").text('{{trans("lang.products")}} (0)');
            $("#data-table_processing").hide();
        } else if (html_keypress != '' || product_keypress != '') {

            $(".not_found_div").hide();
            append_list.innerHTML = '';
            append_list.innerHTML = html_keypress;
            append_list2.innerHTML = '';
            append_list2.innerHTML = product_keypress;
            $("#data-table_processing").hide();
        } else {
        }
    }

    function buildHTML(alldata) {

        var html = '';

        var count = 0;

        $(".restaurant_counts").text('{{trans("lang.stores")}} (' + alldata.length + ')');

        alldata.forEach((listval) => {
            var val = listval;

            if (val.vendorID != '' && val.title != '') {
                count++;
                if (count == 1) {
                    html = html + '<div class="row">';
                }

                productStoreImage = val.photo;

                productStoreTitle = val.title;

                var view_vendor_details = "{{ route('restaurant',':id')}}";

                view_vendor_details = view_vendor_details.replace(':id', 'id=' + val.id);

                var rating = 0;
                var reviewsCount = 0;
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
                                        status = 'Open';
                                        statusclass = "open";
                                    }
                                }
                            }


                        }
                    }
                }

                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    reviewsCount = val.reviewsCount;
                    rating = Math.round(rating * 10) / 10;
                    rating = parseInt(rating);
                }

                if (productStoreImage == '') {
                    productStoreImage = placeholderImage
                }

                var ratinghtml = '<ul class="rating-stars list-unstyled"><li>';

                if (rating >= 1) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating >= 2) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }

                if (rating >= 3) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating >= 4) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating == 5) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                ratinghtml = ratinghtml + '</li></ul>';

                html = html + '<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';

                html = html + '<div class="star position-absolute"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + '+)</span></div>';

                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><div class=""><a href="' + view_vendor_details + '"><img alt="#" src="' + productStoreImage + '" class="img-fluid item-img w-100"></a></div>';

                html = html + '</div>';

                html = html + '<div class="p-3 position-relative">';

                html = html + '<div class="list-card-body" ><h6 class="mb-1"><a href="' + view_vendor_details + '" class="text-black">' + productStoreTitle + '</a></h6><p class="text-gray mb-3"><span class="fa fa-map-marker"></span> ' + val.location + '</p>' + ratinghtml + '</div>';

                html = html + '</div></div></div>';

                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }

            }

        });

        return html;

    }

    function buildProductHTML(allProductdata) {

        var html = '';
        var count = 0;
        
        $(".products_counts").text('{{trans("lang.products")}} (' + allProductdata.length + ')');

        if (allProductdata != undefined && allProductdata != '') {

            $('#myTab2').show();

            allProductdata.forEach((listval) => {
                count++;
                var val = listval;
                if (count == 1) {
                    html = html + '<div class="row">';
                }
                var product_id_single = val.id;
                var view_product_details = "{{ route('productDetail',':id')}}";
                view_product_details = view_product_details.replace(':id', product_id_single);
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }

                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';

                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImage;
                }
                html = html + '<a href="' + view_product_details + '"><img alt="#" src="' + photo + '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1"><a href="' + view_product_details + '" class="arv-title">' + val.name + '</a></h6>';

                if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0' && val.item_attribute == null) {
                    var or_price = getFormattedPrice(parseFloat(val.price));
					var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                    html = html + '<span class="text-gray mb-0 pro-price ">' + dis_price + '  <s>' + or_price + '</s></span>';
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
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                }

                html = html + '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';

                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }
            });

            html = html + '</div>';
        }

        return html;
    }

</script>
