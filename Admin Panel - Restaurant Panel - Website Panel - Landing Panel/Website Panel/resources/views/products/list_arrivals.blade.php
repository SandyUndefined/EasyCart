@include('layouts.app')

@include('layouts.header')

<div class="st-brands-page pt-5 category-listing-page ">
	<div class="container">
		<div class="row m-5">
			<div class="col-md-12">
				<div id="product-list"></div>
			</div>
		</div>
	</div>
</div>

<div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
    {{trans('lang.processing')}}
</div>

@include('layouts.footer')

<script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>



<script type="text/javascript">

	var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var VendorNearBy = '';
    var DriverNearByRef = database.collection('settings').doc('RestaurantNearBy');

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then( async function(placeholderImageSnapshots){
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
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
	var vendorIds = [];

	jQuery("#data-table_processing").show();

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
                jQuery("#data-table_processing").hide();
                return false; 
            }
			getProductList();
            myStopTimer();
        })
    }
 
	async function getProductList(){
		
		var product_list = document.getElementById('product-list');
		product_list.innerHTML = '';
		var html = '';

		if (VendorNearBy) {
		 	var vendorsSnapshots = geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                radius: VendorNearBy
            }).where('zoneId','==',user_zone_id);
        } else {
        	var vendorsSnapshots = geoFirestore.collection('vendors').where('zoneId','==',user_zone_id);
        }

        vendorsSnapshots.get().then(async function (vendorsSnaps) {

			if(vendorsSnaps.docs.length > 0){

				vendorsSnaps.docs.forEach((listval) => {
					vendorIds.push(listval.id);
				});	

				database.collection('vendor_products').where("publish","==",true).get().then( async function(snapshots){
					if (snapshots.docs.length > 0) {
						html = buildProductsHTML(snapshots);
						product_list.innerHTML = html;
					}
				});
				
			}else{
				html = html +"<h5 class='font-weight-bold text-center mt-3'>{{trans('lang.no_results')}}</h5>";
				product_list.innerHTML = html;
			}
		});
		jQuery("#data-table_processing").hide();
	}
	
    function buildProductsHTML(snapshots){
        var html='';
		var alldata=[];
		snapshots.docs.forEach((listval) => {
			var datas=listval.data();
			datas.id=listval.id;
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
		
        if (alldata.length) {
            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 50);
        }

        var count = 0;
        var popularFoodCount = 0;
		
        html = html+ '<div class="row">';

        if(alldata.length){

	        alldata.forEach((listval) => {
	            var val=listval;

	            var rating = 0;
	            var reviewsCount = 0;
	            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
	                rating = (val.reviewsSum / val.reviewsCount);
	                rating = Math.round(rating * 10) / 10;
	                reviewsCount = val.reviewsCount;
	            }

	            html = html+ '<div class="col-md-4 pb-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
							status='Non-Veg';
							statusclass='closed';
							if(val.veg==true){
								status='Veg';
								statusclass='open';
							}
	            if(val.photo){
	                photo=val.photo;
	            }else{
	                photo=placeholderImageSrc;
	            }

	            var view_product_details = "{{ route('productDetail',':id')}}";
	            view_product_details = view_product_details.replace(':id',val.id);

	            html = html +'<div class="member-plan position-absolute"><span class="badge badge-dark '+statusclass+'">'+status+'</span></div><a href="'+view_product_details+'"><img alt="#" src="'+photo+'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="'+view_product_details+'" class="text-black">'+val.name+'</a></h6>';

	            if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0' && val.item_attribute == null) {
	            	var or_price = getFormattedPrice(parseFloat(val.price));
					var dis_price = getFormattedPrice(parseFloat(val.disPrice));
	                html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s></span>';
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
	                html = html + '<span class="pro-price">' + or_price + '</span>'
	            }

	            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';

	            html = html +'</div>';
	            html = html +'</div></div></div>';

			});

		}else{
			html = html +"<h5 class='font-weight-bold text-center mt-3'>{{trans('lang.no_results')}}</h5>";
		}

        html = html + '</div>';
        
		return html;
    }

	sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return b[key] - a[key];
        });
    };

</script>

@include('layouts.nav')
