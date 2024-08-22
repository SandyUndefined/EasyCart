@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.food_plural')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! route('dashboard') !!}">{{trans('lang.dashboard')}}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{!! route('foods') !!}">{{trans('lang.food_plural')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.food_create')}}</li>
                </ol>
            </div>
        </div>

        <div>
            <div class="card-body">
                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                     style="display: none;">Processing...
                </div>
                <div class="error_top" style="display:none"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">

                        <fieldset> 
                            <legend style="display: none;">{{trans('lang.food_information')}}</legend>

                            <div class="form-group row width-100" id="admin_commision_info" style="display:none">
                                <div class="m-3">
                                    <div class="form-text font-weight-bold text-danger h6">{{trans('lang.price_instruction')}}</div>
                                    <div class="form-text font-weight-bold text-danger h6" id="admin_commision"></div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_name')}}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control food_name" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_name_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_price')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_price" required>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_price_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_discount')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_discount">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_discount_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.food_category_id')}}</label>
                                <div class="col-7">
                                    <select id='food_category' class="form-control" required>
                                        <option value="">{{trans('lang.select_category')}}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_category_id_help") }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.item_quantity')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control item_quantity" value="-1">
                                    <div class="form-text text-muted">
                                        {{trans('lang.item_quantity_help')}}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-100" id="attributes_div">
                                <label class="col-3 control-label">{{trans('lang.item_attribute_id')}}</label>
                                <div class="col-7">
                                    <select id='item_attribute' class="form-control chosen-select" required
                                            multiple="multiple" style="display: none;"
                                            onchange="selectAttribute();"></select>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <div class="item_attributes" id="item_attributes"></div>
                                <div class="item_variants" id="item_variants"></div>
                                <input type="hidden" id="attributes" value=""/>
                                <input type="hidden" id="variants" value=""/>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_image')}}</label>
                                <div class="col-7">
                                    <input type="file" id="product_image">
                                    <div class="placeholder_img_thumb product_image"></div>
                                    <div id="uploding_image"></div>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.food_image_help") }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{trans('lang.food_description')}}</label>
                                <div class="col-7">
                                    <textarea rows="8" class="form-control food_description"
                                              id="food_description"></textarea>
                                </div>
                            </div>
                            <div class="form-check width-100">
                                <input type="checkbox" class="food_publish" id="food_publish">
                                <label class="col-3 control-label"
                                       for="food_publish">{{trans('lang.food_publish')}}</label>
                            </div>

                            <div class="form-check width-100">
                                <input type="checkbox" class="food_nonveg" id="food_nonveg">
                                <label class="col-3 control-label" for="food_nonveg">{{ trans('lang.non_veg')}}</label>
                            </div>

                            <div class="form-check width-100">
                                <input type="checkbox" class="food_take_away_option" id="food_take_away_option">
                                <label class="col-3 control-label"
                                       for="food_take_away_option">{{trans('lang.food_take_away')}}</label>
                            </div>

                        </fieldset>

                        <fieldset>

                            <legend>{{trans('lang.ingredients')}}</legend>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.calories')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_calories">
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.grams')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_grams">
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.fats')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_fats">
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{trans('lang.proteins')}}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control food_proteins">
                                </div>
                            </div>

                        </fieldset>


                        <fieldset>
                            <legend>{{trans('lang.food_add_one')}}</legend>

                            <div class="form-group add_ons_list extra-row">
                            </div>

                            <div class="form-group row width-100">
                                <div class="col-7">
                                    <button type="button" onclick="addOneFunction()" class="btn btn-primary"
                                            id="add_one_btn">{{trans('lang.food_add_one')}}</button>
                                </div>
                            </div>

                            <div class="form-group row width-100" id="add_ones_div" style="display:none">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.food_title')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_ons_title">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.food_price')}}</label>
                                        <div class="col-7">
                                            <input type="number" class="form-control add_ons_price">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row save_add_one_btn width-100" style="display:none">
                                <div class="col-7">
                                    <button type="button" onclick="saveAddOneFunction()"
                                            class="btn btn-primary">{{trans('lang.save_add_ones')}}</button>
                                </div>
                            </div>

                        </fieldset>
                        <fieldset>

                            <legend>{{trans('lang.product_specification')}}</legend>

                            <div class="form-group product_specification extra-row">
                            </div>

                            <div class="form-group row width-100">
                                <div class="col-7">
                                    <button type="button" onclick="addProductSpecificationFunction()"
                                            class="btn btn-primary"
                                            id="add_one_btn"> {{trans('lang.add_product_specification')}}</button>
                                </div>
                            </div>
                            <div class="form-group row width-100" id="add_product_specification_div"
                                 style="display:none">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-2 control-label">{{trans('lang.lable')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_label">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-3 control-label">{{trans('lang.value')}}</label>
                                        <div class="col-7">
                                            <input type="text" class="form-control add_value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row save_product_specification_btn width-100" style="display:none">
                                <div class="col-7">
                                    <button type="button" onclick="saveProductSpecificationFunction()"
                                            class="btn btn-primary">{{trans('lang.save_product_specification')}}</button>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>

                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary  create_food_btn"><i
                                class="fa fa-save"></i> {{trans('lang.save')}}</button>
                    <a href="{!! route('foods') !!}" class="btn btn-default"><i
                                class="fa fa-undo"></i>{{trans('lang.cancel')}}</a>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script>

        var database = firebase.firestore();
        var addOnesTitle = [];
        var addOnesPrice = [];
        var sizeTitle = [];
        var sizePrice = [];
        var photo = "";
        var photos = [];
        var product_image_filename=[];
        var variant_photos=[];
        var variant_filename=[];
        var variant_vIds=[];
        var productImagesCount = 0;

        var vandorId;
        var vendorUserId = "<?php echo $id; ?>";
        var attributes_list = [];
        var product_specification = {};

        var refAdminCommission = database.collection('settings').doc("AdminCommission");
        refAdminCommission.get().then(async function (snapshots) {
            var adminCommissionSettings = snapshots.data();
            if(adminCommissionSettings){
                var commission_type = adminCommissionSettings.commissionType;
                var commission_value = adminCommissionSettings.fix_commission;
                var commission_enabled = adminCommissionSettings.isEnabled;
               
                if(commission_type == "Percent"){
                    var commission_text = commission_value+'%';
                }else{
                    if(currencyAtRight){
                        commission_text = parseFloat(commission_value).toFixed(decimal_degits) + "" + currentCurrency;
                    }else{
                        commission_text = currentCurrency + "" + parseFloat(commission_value).toFixed(decimal_degits);
                    }
                }
                if(adminCommissionSettings.isEnabled){
                    $("#admin_commision_info").show();
                    $("#admin_commision").html('Admin Commission: '+commission_text);
                }
            }
        });
        
        getVendorId(vendorUserId).then(data => {
            vandorId = data;
            $(document).ready(function () {
                jQuery(document).on("click", ".mdi-cloud-upload", function () {
                    var variant = jQuery(this).data('variant');
                    $('[id="file_' + variant + '"]').click();
                });

                jQuery(document).on("click", ".mdi-delete", function () {
                        var variant = jQuery(this).data('variant');
                        $('[id="variant_'+ variant+'_image"]').empty();
                        var photo_remove = $(this).attr('data-img');
                        index = variant_photos.indexOf(photo_remove);
                        if (index > -1) {
                            variant_photos.splice(index, 1); // 2nd parameter means remove one item only
                        }
                        var file_remove=$(this).attr('data-file');
                        fileindex = variant_filename.indexOf(file_remove);
                        if (fileindex > -1) {
                            variant_filename.splice(fileindex, 1); // 2nd parameter means remove one item only
                        }
                        variantindex = variant_vIds.indexOf(variant);
                        if (variantindex > -1) {
                            variant_vIds.splice(variantindex, 1); // 2nd parameter means remove one item only
                        }
                    
                });
                database.collection('vendor_categories').where('publish', '==', true).get().then(async function (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();

                        $('#food_category').append($("<option></option>")
                            .attr("value", data.id)
                            .text(data.title));
                    })
                });
                var attributes = database.collection('vendor_attributes');

                attributes.get().then(async function (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        attributes_list.push(data);
                        $('#item_attribute').append($("<option></option>")
                            .attr("value", data.id)
                            .text(data.title));
                    })
                    $("#item_attribute").show().chosen({"placeholder_text": "{{trans('lang.select_attribute')}}"});
                });


                $(".create_food_btn").click(async function () {
                    var id = database.collection('tmp').doc().id;
                    var name = $(".food_name").val();
                    var price = $(".food_price").val();
                    var discount = $(".food_discount").val();
                    var category = $("#food_category option:selected").val();
                    var size = $("#food_size option:selected").val();
                    var foodCalories = parseInt($(".food_calories").val());
                    var foodGrams = parseInt($(".food_grams").val());
                    var foodProteins = parseInt($(".food_proteins").val());
                    var foodFats = parseInt($(".food_fats").val());
                    var quantity = 0;
                    var description = $("#food_description").val();
                    var foodPublish = $(".food_publish").is(":checked");
                    var nonveg = $(".food_nonveg").is(":checked");
                    var foodTakeaway = $(".food_take_away_option").is(":checked");
                    var item_quantity = $(".item_quantity").val();
                    var veg = !nonveg;

                    if (discount == '') {
                        discount = "0";
                    }

                    if (!foodCalories) {
                        foodCalories = 0;
                    }
                    if (!foodGrams) {
                        foodGrams = 0;
                    }
                    if (!foodFats) {
                        foodFats = 0;
                    }
                    if (!foodProteins) {
                        foodProteins = 0;
                    }
                    if (name == '') {
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.enter_food_name_error')}}</p>");
                        window.scrollTo(0, 0);
                    } else if (price == '') {
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.enter_food_price_error')}}</p>");
                        window.scrollTo(0, 0);
                    } else if (category == '') {
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.select_food_category_error')}}</p>");
                        window.scrollTo(0, 0);
                    } else if (description == '') {
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.enter_food_description_error')}}</p>");
                        window.scrollTo(0, 0);
                    } else if (parseInt(price) < parseInt(discount)) {
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{trans('lang.price_should_not_less_then_discount_error')}}</p>");
                        window.scrollTo(0, 0);


                    } else {
                        $(".error_top").hide();
                        //start-item attribute
                        var attributes = [];
                        var variants = [];

                        if ($('#attributes').val().length > 0) {
                            var attributes = $.parseJSON($('#attributes').val());
                        }
                        if ($('#variants').val().length > 0) {
                            var variantsSet = $.parseJSON($('#variants').val());
                        await storeVariantImageData().then(async (vIMG) => {
                            $.each(variantsSet, function (key, variant) {
                                var variant_id = uniqid();
                                var variant_sku = variant;

                                var variant_price = $('[id="price_' + variant + '"]').val();
                                var variant_quantity = $('[id="qty_' + variant + '"]').val();
                                var variant_image = $('[id="variant_' + variant + '_url"]').val();

                                if (variant_image) {
                                    variants.push({
                                        'variant_id': variant_id,
                                        'variant_sku': variant_sku,
                                        'variant_price': variant_price,
                                        'variant_quantity': variant_quantity,
                                        'variant_image': variant_image
                                    });
                                } else {
                                    variants.push({
                                        'variant_id': variant_id,
                                        'variant_sku': variant_sku,
                                        'variant_price': variant_price,
                                        'variant_quantity': variant_quantity
                                    });
                                }
                            });
                         }).catch(err => {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>" + err + "</p>");
                        window.scrollTo(0, 0);
                    });
                        }

                        var item_attribute = null;
                        if (attributes.length > 0 && variants.length > 0) {
                            var item_attribute = {'attributes': attributes, 'variants': variants};
                        }
                        jQuery("#data-table_processing").show();
                        await storeProductImageData().then(async (IMG) => {
                        if(IMG.length>0){
                            photo=IMG[0];
                        }
                        database.collection('vendor_products').doc(id).set({
                            'name': name,
                            'price': price,
                            'quantity': parseInt(item_quantity),
                            'disPrice': discount,
                            'vendorID': vandorId,
                            'categoryID': category,
                            'photo': photo,
                            'calories': foodCalories,
                            "grams": foodGrams,
                            'proteins': foodProteins,
                            'fats': foodFats,
                            'description': description,
                            'publish': foodPublish,
                            'nonveg': nonveg,
                            'veg': veg,
                            'addOnsTitle': addOnesTitle,
                            'addOnsPrice': addOnesPrice,
                            'takeawayOption': foodTakeaway,
                            'id': id,
                            'item_attribute': item_attribute,
                            'product_specification': product_specification,
                            'photos': IMG
                        }).then(function (result) {
                            window.location.href = '{{ route("foods")}}';

                        });
                    }).catch(err => {
                    jQuery("#data-table_processing").hide();
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>" + err + "</p>");
                    window.scrollTo(0, 0);
                });
            }

                })

            })

        })


        var storageRef = firebase.storage().ref('images');

        function handleFileSelectProduct(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function (theFile) {
                return function (e) {

                    var filePayload = e.target.result;
                    var hash = CryptoJS.SHA256(Math.random() + CryptoJS.SHA256(filePayload));
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function (snapshot) {

                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        console.log('Upload is ' + progress + '% done');

                        $('.product_image').find(".uploding_image_photos").text("Image is uploading...");

                    }, function (error) {
                    }, function () {
                        uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
                            jQuery("#uploding_image").text("Upload is completed");
                            if (downloadURL) {

                                productImagesCount++;
                                photos_html = '<span class="image-item" id="photo_' + productImagesCount + '"><span class="remove-btn" data-id="' + productImagesCount + '" data-img="' + downloadURL + '"><i class="fa fa-remove"></i></span><img width="100px" id="" height="auto" src="' + downloadURL + '"></span>';
                                $(".product_image").append(photos_html);
                                photos.push(downloadURL);

                            }

                        });
                    });

                };
            })(f);
            reader.readAsDataURL(f);
        }

        $(document).on("click", ".remove-btn", function () {
            var id = $(this).attr('data-id');
            var photo_remove = $(this).attr('data-img');
            $("#photo_" + id).remove();
            index = photos.indexOf(photo_remove);
            if (index > -1) {
                photos.splice(index, 1); // 2nd parameter means remove one item only
            }

        });

        function handleFileSelect(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();

            new Compressor(f, {
                quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
                success(result) {
                    f = result;

                    reader.onload = (function (theFile) {
                        return function (e) {

                            var filePayload = e.target.result;
                            var val = f.name;
                            var ext = val.split('.')[1];
                            var docName = val.split('fakepath')[1];
                            var filename = (f.name).replace(/C:\\fakepath\\/i, '')

                            var timestamp = Number(new Date());
                            var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                            var uploadTask = storageRef.child(filename).put(theFile);
                            uploadTask.on('state_changed', function (snapshot) {

                                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                                console.log('Upload is ' + progress + '% done');
                                jQuery("#uploding_image").text("Image is uploading...");

                            }, function (error) {

                            }, function () {
                                uploadTask.snapshot.ref.getDownloadURL().then(function (downloadURL) {
                                    jQuery("#uploding_image").text("Upload is completed");
                                    photo = downloadURL;
                                    $("#uploaded_image").attr('src', photo);
                                    $(".uploaded_image").show();

                                });
                            });

                        };
                    })(f);
                    reader.readAsDataURL(f);

                },
                error(err) {
                    console.log(err.message);
                },
            });
        }

        async function getVendorId(vendorUser) {
            var vendorID = '';
            var ref;
            await database.collection('vendors').where('author', "==", vendorUser).get().then(async function (vendorSnapshots) {
                var vendorData = vendorSnapshots.docs[0].data();
                vendorID = vendorData.id;
            })
            return vendorID;
        }

        function addOneFunction() {
            $("#add_ones_div").show();
            $(".save_add_one_btn").show();
        }

        function deleteAddOnesSingle(index) {
            addOnesTitle.splice(index, 1);
            addOnesPrice.splice(index, 1);
            $("#add_ones_list_iteam_" + index).hide();
        }

        function addProductSpecificationFunction() {
            $("#add_product_specification_div").show();
            $(".save_product_specification_btn").show();
        }

        function saveProductSpecificationFunction() {
            var optionlabel = $(".add_label").val();
            var optionvalue = $(".add_value").val();
            $(".add_label").val('');
            $(".add_value").val('');
            if (optionlabel != '' && optionlabel != '') {

                product_specification[optionlabel] = optionvalue;

                $(".product_specification").append('<div class="row" style="margin-top:5px;" id="add_product_specification_iteam_' + optionlabel + '"><div class="col-5"><input class="form-control" type="text" value="' + optionlabel + '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' + optionvalue + '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick=deleteProductSpecificationSingle("' + optionlabel + '")><span class="fa fa-trash"></span></button></div></div>');
            } else {
                alert("Please enter Label and Value");
            }
        }

        function deleteProductSpecificationSingle(index) {

            delete product_specification[index];
            $("#add_product_specification_iteam_" + index).hide();
        }

        function saveAddOneFunction() {
            var optiontitle = $(".add_ons_title").val();
            var optionPrice = $(".add_ons_price").val();
            $(".add_ons_price").val('');
            $(".add_ons_title").val('');
            if (optiontitle != '' && optionPrice != '') {
                addOnesPrice.push(optionPrice);
                addOnesTitle.push(optiontitle);
                var index = addOnesTitle.length - 1;
                $(".add_ons_list").append('<div class="row" style="margin-top:5px;" id="add_ones_list_iteam_' + index + '"><div class="col-5"><input class="form-control" type="text" value="' + optiontitle + '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' + optionPrice + '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick="deleteAddOnesSingle(' + index + ')"><span class="fa fa-trash"></span></button></div></div>');
            } else {
                alert("Please enter Title and Price");
            }
        }

        $(document).on("click", ".remove-btn", function () {
            var id = $(this).attr('data-id');
            var photo_remove = $(this).attr('data-img');
            $("#photo_" + id).remove();
            index = photos.indexOf(photo_remove);
            if (index > -1) {
                photos.splice(index, 1); // 2nd parameter means remove one item only
            }

        });
    async function storeVariantImageData() {
        var newPhoto = [];
        if (variant_photos.length > 0) {
            await Promise.all(variant_photos.map(async (variantPhoto, index) => {
                variantPhoto = variantPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                var uploadTask = await storageRef.child(variant_filename[index]).putString(variantPhoto, 'base64', {contentType: 'image/jpg'});
                var downloadURL = await uploadTask.ref.getDownloadURL();
                $('[id="variant_'+ variant_vIds[index]+'_url"]').val(downloadURL);
                newPhoto.push(downloadURL);
            }));
        }
        return newPhoto;
    }
        function handleVariantFileSelect(evt, vid) {
            var f = evt.target.files[0];
            var reader = new FileReader();

            reader.onload = (function (theFile) {
                return function (e) {

                    var filePayload = e.target.result;
                    var hash = CryptoJS.SHA256(Math.random() + CryptoJS.SHA256(filePayload));
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var timestamp = Number(new Date());
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var filename = 'variant_' + vid + '_' + timestamp + '.' + ext;
                    variant_filename.push(filename);
                    variant_photos.push(filePayload);
                    variant_vIds.push(vid);
                    $('[id="variant_'+ vid+'_image"]').empty();
                    $('[id="variant_'+ vid+'_image"]').html('<img class="rounded" style="width:50px" src="' + filePayload + '" alt="image"><i class="mdi mdi-delete" data-variant="' + vid + '" data-img="' +filePayload + '" data-file="'+filename +'"></i>');
                  
                };
            })(f);
            reader.readAsDataURL(f);
        }


        function selectAttribute() {
            var html = '';
            $("#item_attribute").find('option:selected').each(function () {
                html += '<div class="row">';
                html += '<div class="col-md-3">';
                html += '<label>' + $(this).text() + '</label>';
                html += '</div>';
                html += '<div class="col-lg-9">';
                html += '<input type="text" class="form-control" id="attribute_options_' + $(this).val() + '" placeholder="Add attribute values" data-role="tagsinput" onchange="variants_update()">';
                html += '</div>';
                html += '</div>';
            });
            $("#item_attributes").html(html);
            $("#item_attributes input[data-role=tagsinput]").tagsinput();
            $("#attributes").val('');
            $("#variants").val('');
            $("#item_variants").html('');
        }

        function variants_update() {
        var html = '';
        variant_photos = [];
        variant_vIds = [];
        variant_filename = [];
        var item_attribute = $("#item_attribute").map(function (idx, ele) {
            return $(ele).val();
        }).get();

        if (item_attribute.length > 0) {

            var attributes = [];
            var attributeSet = [];
            $.each(item_attribute, function (index, attribute) {
                var attribute_options = $("#attribute_options_" + attribute).val();
                if (attribute_options) {
                    var attribute_options = attribute_options.split(',');
                    attribute_options = $.map(attribute_options, function (value) {
                        return value.replace(/[^a-zA-Z0-9]/g, '');
                    });
                    attributeSet.push(attribute_options);
                    attributes.push({ 'attribute_id': attribute, 'attribute_options': attribute_options });
                }
            });

            if (attributeSet.length > 0) {

                $('#attributes').val(JSON.stringify(attributes));

                var variants = getCombinations(attributeSet);
                $('#variants').val(JSON.stringify(variants));

                html += '<table class="table table-bordered">';
                html += '<thead class="thead-light">';
                html += '<tr>';
                html += '<th class="text-center"><span class="control-label">Variant</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Price</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Quantity</span></th>';
                html += '<th class="text-center"><span class="control-label">Variant Image</span></th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                $.each(variants, function (index, variant) {
                    html += '<tr>';
                    html += '<td><label for="" class="control-label">' + variant + '</label></td>';
                    html += '<td>';
                    var check_variant_price = $('#price_' + variant).val() ? $('#price_' + variant).val() : 1;
                    html += '<input type="number" id="price_' + variant + '" value="' + check_variant_price + '" min="0" class="form-control">';
                    html += '</td>';
                    html += '<td>';
                    var check_variant_qty = $('#price_' + variant).val() ? $('#price_' + variant).val() : -1;
                    html += '<input type="number" id="qty_' + variant + '" value="' + check_variant_qty + '" min="-1" class="form-control">';
                    html += '</td>';
                    html += '<td>';
                    html += '<div class="variant-image">';
                    html += '<div class="upload">';
                    html += '<div class="image" id="variant_' + variant + '_image"></div>';
                    html += '<div class="icon"><i class="mdi mdi-cloud-upload" data-variant="' + variant + '"></i></div>';
                    html += '</div>';
                    html += '<div id="variant_' + variant + '_process"></div>';
                    html += '<div class="input-file">';
                    html += '<input type="file" id="file_' + variant + '" onChange="handleVariantFileSelect(event,\'' + variant + '\')" class="form-control" style="display:none;">';
                    html += '<input type="hidden" id="variant_' + variant + '_url" value="">';
                    html += '</div>';
                    html += '</div>';
                    html += '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '</table>';
            }
        }
        $("#item_variants").html(html);
    }

        function getCombinations(arr) {
            if (arr.length) {
                if (arr.length == 1) {
                    return arr[0];
                } else {
                    var result = [];
                    var allCasesOfRest = getCombinations(arr.slice(1));
                    for (var i = 0; i < allCasesOfRest.length; i++) {
                        for (var j = 0; j < arr[0].length; j++) {
                            result.push(arr[0][j] + '-' + allCasesOfRest[i]);
                        }
                    }
                    return result;
                }
            }
        }
    async function storeProductImageData() {
        var newPhoto = [];
        if (photos.length > 0) {
            await Promise.all(photos.map(async (productPhoto, index) => {
                productPhoto = productPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                var uploadTask = await storageRef.child(product_image_filename[index]).putString(productPhoto, 'base64', {contentType: 'image/jpg'});
                var downloadURL = await uploadTask.ref.getDownloadURL();
                newPhoto.push(downloadURL);
            }));
        }
        return newPhoto;
    }
        $("#product_image").resizeImg({

            callback: function (base64str) {

                var val = $('#product_image').val().toLowerCase();
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = $('#product_image').val().replace(/C:\\fakepath\\/i, '')
                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                product_image_filename.push(filename);
                productImagesCount++;
                photos_html = '<span class="image-item" id="photo_' + productImagesCount + '"><span class="remove-btn" data-id="' + productImagesCount + '" data-img="' + base64str + '"><i class="fa fa-remove"></i></span><img class="rounded" width="50px" id="" height="auto" src="' + base64str + '"></span>'
                $(".product_image").append(photos_html);
                photos.push(base64str);
                $("#product_image").val('');
               
            }
        });


        function uniqid(prefix = "", random = false) {
            const sec = Date.now() * 1000 + Math.random() * 1000;
            const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
            return `${prefix}${id}${random ? `.${Math.trunc(Math.random() * 100000000)}` : ""}`;
        }

    </script>
@endsection
