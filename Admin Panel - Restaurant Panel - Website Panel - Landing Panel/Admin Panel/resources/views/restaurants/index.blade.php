@extends('layouts.app')

@section('content')
    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.restaurant_plural')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                    <li class="breadcrumb-item">{{trans('lang.restaurant_plural')}}</li>

                    <li class="breadcrumb-item active">{{trans('lang.restaurant_table')}}</li>

                </ol>

            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{!! url()->current() !!}"><i
                                                class="fa fa-list mr-2"></i>{{trans('lang.restaurants_table')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('restaurants.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.create_restaurant')}}</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">

                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{trans('lang.processing')}}
                            </div>

                            <div class="table-responsive m-t-10">


                                <table id="storeTable"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">

                                    <thead>

                                    <tr>
                                        <?php if (in_array('restaurant.delete', json_decode(@session('user_permissions'),true))) { ?>
                                        <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                                                                   class="do_not_delete"
                                                                                                   href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{trans('lang.all')}}</a></label>
                                        </th>
                                        <?php } ?>

                                        <th>{{trans('lang.restaurant_image')}}</th>

                                        <th>{{trans('lang.restaurant_name')}}</th>


                                        <th>{{trans('lang.restaurant_phone')}}</th>
                                        <th>{{trans('lang.date')}}</th>

                                        <!-- <th>{{trans('lang.order_transactions')}}</th> -->

                                        <th>{{trans('lang.wallet_history')}}</th>

                                        <th>{{trans('lang.food_plural')}}</th>
                                        <th>{{trans('lang.order_plural')}}</th>

                                        <th>{{trans('lang.actions')}}</th>

                                    </tr>

                                    </thead>

                                    <tbody id="append_restaurants">


                                    </tbody>

                                </table>

                            </div>

                            <!-- Popup -->

                            <div class="modal fade" id="create_vendor" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered notification-main" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="exampleModalLongTitle">{{trans('lang.copy_vendor')}}
                                                <span id="vendor_title_lable"></span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="data-table_processing"
                                                 class="dataTables_processing panel panel-default"
                                                 style="display: none;">{{trans('lang.processing')}}
                                            </div>
                                            <div class="error_top"></div>
                                            <!-- Form -->
                                            <div class="form-row">
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.first_name')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Name" type="text" id="user_name"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.last_name')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Name" type="text" id="user_last_name"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">{{trans('lang.vendor_title')}}</label>
                                                    <div class="input-group">
                                                        <input placeholder="Vendor Title" type="text" id="vendor_title"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group"><label
                                                            class="form-label">{{trans('lang.email')}}</label><input
                                                            placeholder="Email" value="" id="user_email" type="text"
                                                            class="form-control"></div>
                                                <div class="col-md-12 form-group"><label
                                                            class="form-label">{{trans('lang.password')}}</label><input
                                                            placeholder="Password" id="user_password" type="password"
                                                            class="form-control">
                                                </div>

                                            </div>
                                            <!-- Form -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                    id="create_vendor_submit">{{trans('lang.create')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Popup -->


                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('scripts')

    <script type="text/javascript">

        var database = firebase.firestore();
        var offest = 1;
        var pagesize = 10;
        var end = null;
        var endarray = [];
        var start = null;
        var user_number = [];
        var refData = database.collection('vendors');
        var ref = refData.orderBy('createdAt', 'desc');
        var append_list = '';

        var placeholderImage = ''; 

        var user_permissions = '<?php echo @session("user_permissions")?>';
        user_permissions = Object.values(JSON.parse(user_permissions));
        var checkDeletePermission = false;
        if ($.inArray('restaurant.delete', user_permissions) >= 0) {
            checkDeletePermission = true;
        }
        
        var userData = [];
        var vendorData = [];
        var vendorProducts = [];

        var placeholder = database.collection('settings').doc('placeHolderImage');

        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })
        $(document).ready(function () {

            var inx = parseInt(offest) * parseInt(pagesize);
            jQuery("#data-table_processing").show();

            append_list = document.getElementById('append_restaurants');
            append_list.innerHTML = ''; 
            ref.get().then(async function (snapshots) {
                html = '';
                html = await buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#data-table_paginate").hide();
                    }
                }

                if (checkDeletePermission) {

                    $('#storeTable').DataTable({ 
                        order: [],
                        columnDefs: [
                            {
                                targets: 4,
                                type: 'date',
                                render: function (data) {
                                    return data;
                                }
                            },
                            {orderable: false, targets: [0, 1, 5, 8]},
                        ],
                        "language": {
                            "zeroRecords": "{{trans("lang.no_record_found")}}",
                            "emptyTable": "{{trans("lang.no_record_found")}}"
                        },
                        responsive: true
                    });
                }
                else
                {
                    $('#storeTable').DataTable({ 
                        order: [],
                        columnDefs: [
                            {
                                targets: 3,
                                type: 'date',
                                render: function (data) {
                                    return data;
                                }
                            },
                            {orderable: false, targets: [0, 4, 7]},
                        ],
                        "language": {
                            "zeroRecords": "{{trans("lang.no_record_found")}}",
                            "emptyTable": "{{trans("lang.no_record_found")}}"
                        },
                        responsive: true
                    });
                }
            });

        });


        async function buildHTML(snapshots) {
            var html = '';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var val = listval.data();

                if (val.title != '') {
                    var getData = await getListData(val);
                    html += getData;
                }

            }));
            return html;
        }

        async function getListData(val) {

            var html = '';

            html = html + '<tr>';
            newdate = '';
            var id = val.id;
            var vendorUserId = val.author;
           
            var route1 = '{{route("restaurants.edit",":id")}}';
            route1 = route1.replace(':id', id);

            var route_view = '{{route("restaurants.view",":id")}}';
            route_view = route_view.replace(':id', id);
            if (checkDeletePermission) {
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '" author="' + val.author + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
            }

            if (val.photo != '') {
                html = html + '<td><img alt="" width="100%" style="width:70px;height:70px;" src="' + val.photo + '" alt="image"></td>';

            } else {

                html = html + '<td><img alt="" width="100%" style="width:70px;height:70px;" src="' + placeholderImage + '" alt="image"></td>';
            }


            html = html + '<td data-url="' + route_view + '" class="redirecttopage">' + val.title + '</td>';

            const phone = userPhone(val.author);
            if (val.hasOwnProperty('phonenumber')) {
                html = html + '<td>' + val.phonenumber + '</td>';
            } else {
                html = html + '<td></td>';
            }

            var date = '';
            var time = '';
            if (val.hasOwnProperty("createdAt")) {
                try {
                    date = val.createdAt.toDate().toDateString();
                    time = val.createdAt.toDate().toLocaleTimeString('en-US');
                } catch (err) {
  
                }
                html = html + '<td class="dt-time">' + date + ' ' + time + '</td>';
            } else {
                html = html + '<td></td>';
            }
         
            var payoutRequests = '{{route("users.walletstransaction",":id")}}';
            payoutRequests = payoutRequests.replace(':id', val.author);

            html = html + '<td><a href="'+payoutRequests+'">{{trans("lang.wallet_history")}}</a></td>';

            var active = val.isActive;
            var totalProduct = await getTotalProduct(val.id);
            var vendorId = val.id;
            var url = '{{route("restaurants.foods",":id")}}';
            url = url.replace(":id", vendorId);
            html = html + '<td><a href = "' + url + '">' + totalProduct + '</a></td>';
            var totalOrders = await getTotalOrders(val.id);

            var url2 = '{{route("restaurants.orders",":id")}}';
            url2 = url2.replace(":id", vendorId);
            html = html + '<td><a href="' + url2 + '">' + totalOrders + '</a></td>';

            html = html + '<td class="vendor-action-btn"><a href="javascript:void(0)" vendor_id="' + val.id + '" author="' + val.author + '" name="vendor-clone"><i class="fa fa-clone"></i></a><a href="' + route_view + '"><i class="fa fa-eye"></i></a><a href="' + route1 + '"><i class="fa fa-edit"></i></a>';
            if (checkDeletePermission) {
            html = html + '<a id="' + val.id + '" author="' + val.author + '" name="vendor-delete" class="do_not_delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>';
            }
            html = html + '</tr>';

            return html;  
        }

        async function getTotalProduct(id) {
            var Product_total = 0;

            await database.collection('vendor_products').where('vendorID', '==', id).get().then(async function (productSnapshots) {
                Product_total = productSnapshots.docs.length;
              
            });

            return Product_total;
        }

        async function getTotalOrders(id) {
            var order_total = 0;

            await database.collection('restaurant_orders').where('vendorID', '==', id).get().then(async function (productSnapshots) {
                order_total = productSnapshots.docs.length;
                
            });

            return order_total;
        }

        $("#is_active").click(function () {
            $("#storeTable .is_open").prop('checked', $(this).prop('checked'));

        });

        $("#deleteAll").click(function () {
            if ($('#storeTable .is_open:checked').length) {
                if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                    jQuery("#data-table_processing").show();
                    $('#storeTable .is_open:checked').each(function () {
                            var dataId = $(this).attr('dataId');
                            var author = $(this).attr('author');

                            database.collection('users').doc(author).update({'vendorID': ""}).then(function (result) {
                                database.collection('vendors').doc(dataId).delete().then(function () {

                                    const getStoreName = deleteStoreData(dataId);

                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 7000);
                                });
                            });


                        }
                    );

                }
            } else {
                alert("{{trans('lang.select_delete_alert')}}"); 
            }
        });


        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });


        async function userPhone(author) {
            var userPhones = '';
            await database.collection('users').where("id", "==", author).get().then(async function (snapshotss) {

                if (snapshotss.docs[0]) {
                    var user = snapshotss.docs[0].data();
                    userPhones = user.phoneNumber;
                    if (user.isActive) {

                        jQuery(".active_restaurant_" + author + " span").addClass('badge-danger');
                        jQuery(".active_restaurant_" + author + " span").text('No');

                    } else {
                        jQuery(".active_restaurant_" + author + " span").addClass('badge-success');
                        jQuery(".active_restaurant_" + author + " span").text('Yes');
                    }

                } else {
                    jQuery(".phone_" + author).html('');
                    jQuery(".active_restaurant_" + author + " span").addClass('badge-success');
                    jQuery(".active_restaurant_" + author + " span").text('Yes');
                }
            });
            return userPhones;
        }

        async function next() {
            if (start != undefined || start != null) {
                jQuery("#data-table_processing").hide();

                if (jQuery("#selected_search").val() == 'title' && jQuery("#search").val().trim() != '') {
                    listener = refData.orderBy('title').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();
                } else {
                    listener = ref.startAfter(start).limit(pagesize).get();
                }
                listener.then(async (snapshots) => {

                    html = '';
                    html = await buildHTML(snapshots);
                    jQuery("#data-table_processing").hide();
                    if (html != '') {
                        append_list.innerHTML = html;
                        start = snapshots.docs[snapshots.docs.length - 1];

                        if (endarray.indexOf(snapshots.docs[0]) != -1) {
                            endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                        }
                        endarray.push(snapshots.docs[0]);
                    }
                });
            }
        }

        async function prev() {
            if (endarray.length == 1) {
                return false;
            }
            end = endarray[endarray.length - 2];

            if (end != undefined || end != null) {
                jQuery("#data-table_processing").show();
                if (jQuery("#selected_search").val() == 'title' && jQuery("#search").val().trim() != '') {

                    listener = refData.orderBy('title').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();
                } else {
                    listener = ref.startAt(end).limit(pagesize).get();
                }

                listener.then(async (snapshots) => {
                    html = '';
                    html = await buildHTML(snapshots);
                    jQuery("#data-table_processing").hide();
                    if (html != '') {
                        append_list.innerHTML = html;
                        start = snapshots.docs[snapshots.docs.length - 1];
                        endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                        if (snapshots.docs.length < pagesize) {

                            jQuery("#users_table_previous_btn").hide();
                        }

                    }
                });
            }
        }


        function searchtext() {

            jQuery("#data-table_processing").show();

            append_list.innerHTML = '';

            if (jQuery("#selected_search").val() == 'title' && jQuery("#search").val().trim() != '') {
                wherequery = refData.orderBy('title').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();
            } else {
                wherequery = ref.limit(pagesize).get();
            }

            wherequery.then((snapshots) => {
                html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {

                        jQuery("#data-table_paginate").hide();
                    } else {

                        jQuery("#data-table_paginate").show();
                    }
                }
            });

        }

        function searchclear() {
            jQuery("#search").val('');
            searchtext();
        }


        $(document).on("click", "a[name='vendor-delete']", function (e) {
            var id = this.id;
            jQuery("#data-table_processing").show();
            var author = $(this).attr('author');
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                database.collection('vendors').doc(id).delete().then(function () {
                    deleteStoreData(id).then(function () {
                        setTimeout(function () {
                            window.location.reload();
                        }, 9000);
                    });
                });
            }
        });
        async function deleteStoreData(storeId) {
            await database.collection('users').where('vendorID', '==', storeId).get().then(async function (userssanpshots) {

                if (userssanpshots.docs.length > 0) {
                    var item_data = userssanpshots.docs[0].data();
                    await database.collection('wallet').where('user_id', '==', item_data.id).get().then(async function (snapshotsItem) {
                        if (snapshotsItem.docs.length > 0) {
                            snapshotsItem.docs.forEach((temData) => {
                                var item_data = temData.data();
                                database.collection('wallet').doc(item_data.id).delete().then(function () {});
                            });
                        }
                    });

                    var projectId = '<?php echo env('FIREBASE_PROJECT_ID') ?>';
                    var dataObject = {"data": {"uid": item_data.id}};

                    jQuery.ajax({
                        url: 'https://us-central1-' + projectId + '.cloudfunctions.net/deleteUser',
                        method: 'POST',
                        contentType: "application/json; charset=utf-8",
                        data: JSON.stringify(dataObject),
                        success: function (data) {
                            console.log('Delete user success:', data.result);
                            database.collection('users').doc(item_data.id).delete().then(function () {
                            });
                        },
                        error: function (xhr, status, error) {
                            var responseText = JSON.parse(xhr.responseText);
                            console.log('Delete user error:', responseText.error);
                        }
                    });
                }
            });
            await database.collection('vendor_products').where('vendorID', '==', storeId).get().then(async function (snapshots) {
                if (snapshots.docs.length > 0) {
                    snapshots.docs.forEach((listval) => {
                        var data = listval.data();
                        database.collection('vendor_products').doc(data.id).delete().then(function () {
                        });
                    });
                }
            });
            await database.collection('foods_review').where('VendorId', '==', storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('foods_review').doc(item_data.Id).delete().then(function () {
                        });
                    });
                }

            });
            await database.collection('coupons').where('resturant_id', '==', storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('coupons').doc(item_data.id).delete().then(function () {
                        });
                    });
                }

            });
            await database.collection('favorite_restaurant').where('restaurant_id','==',storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('favorite_restaurant').doc(item_data.id).delete().then(function () {
                        });
                    });
                }
            })
            await database.collection('favorite_item').where('store_id','==',storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('favorite_item').doc(item_data.id).delete().then(function () {
                        });
                    });
                }
            })
            await database.collection('payouts').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('payouts').doc(item_data.id).delete().then(function () {
                        });
                    });
                }

            });
            await database.collection('booked_table').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('booked_table').doc(item_data.id).delete().then(function () {
                        });
                    });
                }

            });
            await database.collection('story').where('vendorID', '==', storeId).get().then(async function (snapshotsItem) {
                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();
                        database.collection('story').doc(item_data.id).delete().then(function () {
                        });
                    });
                }

            });
        }

        $(document).on("click", "a[name='vendor-clone']", async function (e) {

            var id = $(this).attr('vendor_id');
            var author = $(this).attr('author');

            await database.collection('users').doc(author).get().then(async function (snapshotsusers) {
                userData = snapshotsusers.data();
            });
            await database.collection('vendors').doc(id).get().then(async function (snapshotsvendors) {
                vendorData = snapshotsvendors.data();
            });
            await database.collection('vendor_products').where('vendorID', '==', id).get().then(async function (snapshotsproducts) {
                vendorProducts = [];
                snapshotsproducts.docs.forEach(async (product) => {
                    vendorProducts.push(product.data());
                });

            });


            if (userData && vendorData) {
                jQuery("#create_vendor").modal('show');
                jQuery("#vendor_title_lable").text(vendorData.title);
            }
        });


        $(document).on("click", "#create_vendor_submit", async function (e) {
            var vendor_id = database.collection("tmp").doc().id;

            if (userData && vendorData) {

                var vendor_title = jQuery("#vendor_title").val();
                var userFirstName = jQuery("#user_name").val();
                var userLastName = jQuery("#user_last_name").val();
                var email = jQuery("#user_email").val();
                var password = jQuery("#user_password").val();

                if (userFirstName == '') {

                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.user_name_required')}}</p>");
                    window.scrollTo(0, 0);

                } else if (userLastName == '') {

                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.user_last_name_required')}}</p>");
                    window.scrollTo(0, 0);

                } else if (vendor_title == '') {

                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.vendor_title_required')}}</p>");
                    window.scrollTo(0, 0);

                } else if (email == '') {

                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.user_email_required')}}</p>");
                    window.scrollTo(0, 0);
                } else if (password == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.enter_owners_password_error')}}</p>");
                    window.scrollTo(0, 0);
                } else {

                    jQuery("#data-table_processing2").show();

                    firebase.auth().createUserWithEmailAndPassword(email, password).then(async function (firebaseUser) {

                        var user_id = firebaseUser.user.uid;

                        userData.email = email;
                        userData.firstName = userFirstName;
                        userData.lastName = userLastName;
                        userData.id = user_id;
                        userData.vendorID = vendor_id;
                        userData.createdAt = createdAt;
                        userData.wallet_amount = 0;

                        vendorData.author = user_id;
                        vendorData.authorName = userFirstName + ' ' + userLastName;
                        vendorData.title = vendor_title;
                        vendorData.id = vendor_id;
                        coordinates = new firebase.firestore.GeoPoint(vendorData.latitude, vendorData.longitude);
                        vendorData.coordinates = coordinates;
                        vendorData.createdAt = createdAt;

                        await database.collection('users').doc(user_id).set(userData).then(async function (result) {

                            await geoFirestore.collection('vendors').doc(vendor_id).set(vendorData).then(async function (result) {
                                var count = 0;
                                await vendorProducts.forEach(async (product) => {
                                    var product_id = await database.collection("tmp").doc().id;
                                    product.id = product_id;
                                    product.vendorID = vendor_id;
                                    await database.collection('vendor_products').doc(product_id).set(product).then(function (result) {
                                        count++;
                                        if (count == vendorProducts.length) {
                                            jQuery("#data-table_processing2").hide();
                                            alert('Successfully created.');
                                            jQuery("#create_vendor").modal('hide');
                                            location.reload();
                                        }
                                    });

                                });


                            });
                        })

                    }).catch(function (error) {
                        $(".error_top").show();
                        jQuery("#data-table_processing2").hide();
                        $(".error_top").html("");
                        $(".error_top").append("<p>" + error + "</p>");
                    });

                }
            }
        });


    </script>



@endsection