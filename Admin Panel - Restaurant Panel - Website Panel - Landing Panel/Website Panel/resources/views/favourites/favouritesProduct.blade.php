@include('layouts.app')


@include('layouts.header')

<div class="siddhi-favorites">
  <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}...</div>

    <div class="container  py-5">
        <h2 class="font-weight-bold mb-3">{{trans('lang.favorite_products')}}</h2>
        <div class="text-center py-5 not_found_div" style="display:none">

            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>

            <p class="font-weight-bold text-dark h5">{{trans('lang.nothing_found')}} </p>



        </div>
        <div class="product-list">

        <div id="append_list1" class="row"></div></div>

        <div class="row fu-loadmore-btn">
            <a class="page-link loadmore-btn" href="javascript:void(0);" id="loadmore" onclick="moreload()"
               data-dt-idx="0" tabindex="0">{{trans('lang.load_more')}} </a>
        </div>

    </div>
</div>


@include('layouts.footer')

@include('layouts.nav')


<script type="text/javascript">

    var newdate = new Date();
    var todaydate = new Date(newdate.setHours(23, 59, 59, 999));
    var currentCurrency = '';
    var currencyAtRight = false;
    var ref = database.collection('favorite_item').where('user_id', '==', user_uuid);

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then( async function(placeholderImageSnapshots){
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    var decimal_degits = 0;

    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var append_list = '';
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var place_image = '';
    var ref_place = database.collection('settings').doc("placeHolderImage");
    ref_place.get().then(async function (snapshots) {

        var placeHolderImage = snapshots.data();
        place_image = placeHolderImage.image;

    });


    $(document).ready(function () {


        jQuery("#loadmore").hide();
        $("#data-table_processing").show();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';

        ref.limit(pagesize).get().then(async function (snapshots) {

            if (snapshots != undefined) {
                var html = '';
                html = await buildHTML(snapshots);
                jQuery("#data-table_processing").hide();

                if (html != '') {
                  jQuery('.not_found_div').hide();
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    $("#data-table_processing").hide();
                    if (snapshots.docs.length < pagesize) {

                        jQuery("#loadmore").hide();
                    } else {
                        jQuery("#loadmore").show();
                    }
                }else{
                    jQuery('.not_found_div').show();
                }
            }

        });

    })


    async function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        var vendorIDS = [];

        for(listval of snapshots.docs){
            var datas = listval.data();
            datas.id=listval.id;
            var isInZone = await checkInZone(datas.store_id);
            if(isInZone){
                alldata.push(datas);
            }
        }

        alldata.forEach((listval) => {

            var val = listval;
            if (val.photo) {
                photo = product.photo;
            } else {
                photo = placeholderImageSrc;
            }
            if(val.product_id != undefined){
                const product_name = productName(val.product_id);
            }

            html = html + '<div class="col-md-4 mb-3 product-list"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm grid-card">';

            html = html + '<div class="list-card-image">';

            var fav = [val.user_id, val.product_id];
            html = html + '<div class="favourite-heart text-danger position-absolute"><a href="javascript:void(0);"  onclick="unFeveroute(`' + fav + '`)"><i class="fa fa-heart" style="color:red"></i></a></div>';

            
            html = html + '<a href="#" class="rurl_' + val.product_id + '"><img alt="#" src="'+place_image+'" class="img-fluid item-img w-100 rimage_' + val.product_id + '"></a>';
            html = html + '</div>';

            html = html + '<div class="py-2 position-relative">';

            html = html + '<div class="list-card-body"><h6 class="mb-1"><a href="#" class="text-black rtitle_' + val.product_id + '"></a></h6>';

            html = html + '<span class="text-gray mb-0 pro-price rprice_' + val.product_id + '"></span>';
            
            html = html + '<div class="star position-relative"><span class="badge badge-success rreview_' + val.product_id + '"><i class="feather-star"></i></span></div>';

            html = html + '</div></div>';

            html = html + '</div></div>';


        });

        return html;

    }

    async function moreload() {
        if (start != undefined || start != null) {
            jQuery("#data-table_processing").hide();

            listener = ref.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {

                html = '';
                html = await buildHTML(snapshots);

                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML += html;
                    start = snapshots.docs[snapshots.docs.length - 1];

                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);

                    if (snapshots.docs.length < pagesize) {

                        jQuery("#loadmore").hide();
                    } else {
                        jQuery("#loadmore").show();
                    }
                }
            });
        }
    }


    async function productName(productID) {

        var productName = '';
        var product_url = '';
        var product_photo = '';
        var product_price = '';
        var rating = 0;
        var reviewsCount = 0
        await database.collection('vendor_products').where("id", "==", productID).get().then(async function (snapshotss) {

            if (snapshotss.docs[0]) {

                var product = snapshotss.docs[0].data();
                productName = product.name;

                if(product.photo!=''){
                    product_photo = product.photo;
                }else{
                    product_photo = place_image;
                }
                product_url = "{{ route('productDetail',':id')}}";
                product_url = product_url.replace(':id',product.id);
                
                var or_price = getFormattedPrice(parseFloat(product.price));
                if (product.hasOwnProperty('disPrice') && product.disPrice != '' && product.disPrice != '0') {
                    var dis_price = getFormattedPrice(parseFloat(product.disPrice));
                    jQuery(".rprice_" + productID).html('<span>'+ dis_price + '  <s>' + or_price + '</s></span>');
                }else{
                    jQuery(".rprice_" + productID).html('<span>'+ or_price + '</span>');
                }

                if (product.hasOwnProperty('reviewsSum') && product.reviewsSum != 0 && product.hasOwnProperty('reviewsCount') && product.reviewsCount != 0) {
                    rating = (product.reviewsSum / product.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = product.reviewsCount;
                }

                jQuery(".rtitle_" + productID).html(productName);
                jQuery(".rtitle_" + productID).attr('href', product_url);
                jQuery(".rurl_" + productID).attr('href', product_url);
                jQuery(".rimage_" + productID).attr('src', product_photo);

                jQuery(".rreview_" + productID).append(rating + '(' + reviewsCount + '+)');

            } else {

                jQuery(".rtitle_" + productID).html('');
                jQuery(".rtitle_" + productID).attr('href', '#');
            }
        });
        return productName;
    }

    async function checkInZone(vendorID) {
        var vendor_zone_id = '';
        var snapshots = await database.collection('vendors').doc(vendorID).get();
        if(snapshots.exists){
            var vendor = snapshots.data();
            vendor_zone_id = vendor.zoneId;
        }
        return user_zone_id == vendor_zone_id ? true : false;
    }

    async function unFeveroute(id) {
        var data = id.split(",");
        var user_id = data[0];
        var product_id = data[1];

        const doc = await database.collection('favorite_item').where('user_id', '==', user_id).where('product_id', '==', product_id).get();
        doc.forEach(element => {
            element.ref.delete().then(function (result) {
                window.location.href = '{{ url()->current() }}';
            });

        });
    }


</script>
