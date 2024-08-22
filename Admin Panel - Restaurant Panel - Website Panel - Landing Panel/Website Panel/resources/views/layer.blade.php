@include('layouts.app')

<div class="home" id="home"></div>

<div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
    {{trans('lang.processing')}}
</div>

@include('layouts.footer')

<script type="text/javascript">

		jQuery("#data-table_processing").show();

		var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
	    var placeholderImageSrc = '';
	    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
	        var placeHolderImageData = placeholderImageSnapshots.data();
	        placeholderImageSrc = placeHolderImageData.image;
	    })
		var globalSettingsRef = database.collection('settings').doc('globalSettings');

		var homepageTemplateRef = database.collection('settings').doc('homepageTemplate');


		homepageTemplateRef.get().then(async function (homepageTemplateSnapshots) {
	        var homepageTemplateData = homepageTemplateSnapshots.data();
			$('#home').html(homepageTemplateData.homepageTemplate);

			await globalSettingsRef.get().then(async function (globalSettingsSnapshots) {
				var globalSettingsData = globalSettingsSnapshots.data();
				var src_new = globalSettingsData.appLogo;
				$('#logo_web').html('<img alt="#" class="logo_web img-fluid" src="'+src_new+'">');

				$('.location-group .locate-me').attr("onclick","getCurrentLocation()");

                $("#logo_web").attr('src', globalSettingsData.appLogo);
                $('.hc-offcanvas-nav h2').html(globalSettingsData.applicationName);
                $("#footer_logo_web").attr('src', globalSettingsData.appLogo);
                setCookie('section_color', globalSettingsData.website_color, 365);
                setCookie('application_name', globalSettingsData.applicationName, 365);
                setCookie('meta_title', globalSettingsData.meta_title, 365);
                setCookie('favicon', globalSettingsData.favicon, 365);
				document.title = globalSettingsData.meta_title;
			});
            initialize();
	        jQuery("#data-table_processing").hide();
		});

		$(document).ready(function () {

			$(document).on("click", ".btn-continue", function (e) {
				var element = $('.cat-slider .cat-item.section-selected');
	            var section_id = element.attr('data-id');

	            if($('#user_locationnew').val() == ''){
	            	alert('Please select your address');
	            	return false;
	            }

	            window.location.href = "<?php echo url('/'); ?>";
	        });
	    });

	  function initialize() {
		
        if (address_name != '' && address_name != null) {
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

            setCookie('address_name1', address_name1, 365);
            setCookie('address_name2', address_name2, 365);
            setCookie('address_name', address_name, 365);
            setCookie('address_lat', address_lat, 365);
            setCookie('address_lng', address_lng, 365);
            setCookie('address_zip', address_zip, 365);
            setCookie('address_city', address_city, 365);
            setCookie('address_state', address_state, 365);
            setCookie('address_country', address_country, 365);
        });
    }
</script>
