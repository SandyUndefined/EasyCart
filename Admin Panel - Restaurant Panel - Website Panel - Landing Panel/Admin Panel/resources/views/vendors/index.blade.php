@extends('layouts.app')

@section('content')
<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">
             @if(request()->is('vendors/approved'))
                @php $type = 'approved'; @endphp
                {{trans('lang.approved_vendors')}}
                @elseif(request()->is('vendors/pending'))
                @php $type = 'pending'; @endphp
                {{trans('lang.approval_pending_vendors')}}
                @else
                @php $type = 'all'; @endphp
                {{trans('lang.all_vendors')}}
                @endif
            </h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.vendor_list')}}</li>
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
                                            class="fa fa-list mr-2"></i>{{trans('lang.vendor_list')}}</a>
                            </li>
                           
                        </ul>
                    </div>
                    <div class="card-body">
                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">{{trans('lang.processing')}}
                        </div>

                        <div class="table-responsive m-t-10">
                            <table id="userTable"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>

                                    <?php if(
                                        ($type == "approved" && in_array('approve.vendors.delete', json_decode(@session('user_permissions'),true))) ||
                                        ($type == "pending" && in_array('pending.vendors.delete', json_decode(@session('user_permissions'),true))) ||
                                        ($type == "all" && in_array('vendors.delete', json_decode(@session('user_permissions'),true)))
                                    ){ ?>
                                    <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                class="col-3 control-label" for="is_active"
                                        ><a id="deleteAll" class="do_not_delete"
                                            href="javascript:void(0)"><i
                                                        class="fa fa-trash"></i> {{trans('lang.all')}}</a></label></th>
                                    <?php } ?> 

                                    <th>{{trans('lang.extra_image')}}</th>
                                    <th>{{trans('lang.user_name')}}</th>
                                    <th>{{trans('lang.email')}}</th>
                                    <th>{{trans('lang.date')}}</th>
                                    <th>{{trans('lang.document_plural')}}</th> 
                                    <th>{{trans('lang.active')}}</th>
                                    <?php if(
                                        ($type == "approved" && in_array('approve.vendors.delete', json_decode(@session('user_permissions'),true))) ||
                                        ($type == "pending" && in_array('pending.vendors.delete', json_decode(@session('user_permissions'),true))) ||
                                        ($type == "all" && in_array('vendors.delete', json_decode(@session('user_permissions'),true)))
                                    ){ ?>
                                    <th>{{trans('lang.actions')}}</th> 
                                    <?php } ?>
                                    
                                </tr>
                                </thead>
                                <tbody id="append_list1">
                                </tbody>
                            </table>

                        </div>
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
    var type = "{{$type}}";

    var user_permissions = '<?php echo @session("user_permissions")?>';
    user_permissions = Object.values(JSON.parse(user_permissions));
    var checkDeletePermission = false;
    if (
        (type == 'pending' && $.inArray('pending.vendors.delete', user_permissions) >= 0) ||
        (type == 'approved' && $.inArray('approve.vendors.delete', user_permissions) >= 0) ||
        (type == 'all' && $.inArray('vendors.delete', user_permissions) >= 0)

    ) {
        checkDeletePermission = true;
    }

    var ref = database.collection('users').where("role", "==", "vendor").orderBy('createdAt', 'desc');
    if (type == 'pending') {
        ref = database.collection('users').where("role", "==", "vendor").where("isDocumentVerify", "==", false).orderBy('createdAt', 'desc');
    } else if (type == 'approved') {
        ref = database.collection('users').where("role", "==", "vendor").where("isDocumentVerify", "==", true).orderBy('createdAt', 'desc');
    }
    var placeholderImage = '';
    var append_list = '';

    $(document).ready(function () {

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        var inx = parseInt(offest) * parseInt(pagesize);
        jQuery("#data-table_processing").show();

        var placeholder = database.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })

        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {

            html = '';

            html = buildHTML(snapshots);
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

                $('#userTable').DataTable({
                    order: [],
                    columnDefs: [
                        {
                            targets: 4,
                            type: 'date',
                            render: function (data) {
                                return data;
                            }
                        },
                        {orderable: false, targets: [0, 1, 5, 6,7]},
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
                 $('#userTable').DataTable({
                    order: [],
                    columnDefs: [
                        {
                            targets: 3,
                            type: 'date',
                            render: function (data) {
                                return data;
                            }
                        },
                        {orderable: false, targets: [0,4,5]},
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


    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();


            alldata.push(datas);
        });

        var count = 0;
        alldata.forEach((listval) => {

            var val = listval;

            html = html + '<tr>';
            newdate = '';
            var id = val.id;

            var route1 = '';
            if (val.vendorID != null && val.vendorID != '') {
                var route1 = '{{route("restaurants.edit", ":id")}}';
                route1 = route1.replace(':id', val.vendorID);
            } else {
                route1 = 'javascript:void(0)';
            }

            var checkIsRestaurant = getUserRestaurantInfo(id);

            var trroute1 = '{{route("users.walletstransaction", ":id")}}';
            trroute1 = trroute1.replace(':id', id);
            if (checkDeletePermission) {
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '" data-vendorid="'+val.vendorID+'"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
            }
            if (val.profilePictureURL == '') {

                html = html + '<td><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image"></td>';
            } else {
                html = html + '<td><img class="rounded" style="width:50px" src="' + val.profilePictureURL + '" alt="image"></td>';
            }

            html = html + '<td id="userName_' + id + '" data-url="' + route1 + '" class="redirecttopage">' + val.firstName + ' ' + val.lastName + '</td>';

            html = html + '<td>' + val.email + '</td>';
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
            document_list_view="{{route('vendors.document', ':id')}}";
            document_list_view= document_list_view.replace(':id',val.id);
            html = html + '<td><a href="'+ document_list_view +'"><i class="fa fa-file"></i></a></td>';

            if (val.active) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>';
            }
            if (checkDeletePermission) {

            html = html + '<td class="action-btn"><a id="' + val.id + '" data-vendorid="'+val.vendorID+'" class="do_not_delete" name="vendor-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>';
            }

            html = html + '</tr>';
            count = count + 1;
        });
        return html;
    }  


    async function getUserRestaurantInfo(userId) {

        await database.collection('vendors').where('author', '==', userId).get().then(async function (restaurantSnapshots) {

            if (restaurantSnapshots.docs.length > 0) {

                var restaurantId = restaurantSnapshots.docs[0].data();
                restaurantId = restaurantId.id;

                var restaurantView = '{{route("restaurants.edit", ":id")}}';
                restaurantView = restaurantView.replace(':id', restaurantId);

                $('#userName_' + userId).attr('data-url', restaurantView);
            }
        });
    }

    $("#is_active").click(function () {
        $("#userTable .is_open").prop('checked', $(this).prop('checked'));

    });

    $("#deleteAll").click(function () {
        if ($('#userTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {

                jQuery("#data-table_processing").show();
                $('#userTable .is_open:checked').each(function () {
                    var dataId = $(this).attr('dataId');
                    var VendorId = $(this).attr('data-vendorid');

                    database.collection('users').doc(dataId).delete().then(function () {
                        const getStoreName = deleteUserData(dataId,VendorId);
                        setTimeout(function () {
                            window.location.reload();
                        }, 7000);
                    });

                });

            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    async function deleteStoreData(VendorId) {
        // console.log("VendorId"+VendorId);

        await database.collection('vendor_products').where('vendorID', '==', VendorId).get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    database.collection('vendor_products').doc(data.id).delete().then(function () {
                    });
                });
            }
        });
        // await database.collection('restaurant_orders').where('vendorID', '==', VendorId).get().then(async function (snapshots) {
        //     if (snapshots.docs.length > 0) {
        //         snapshots.docs.forEach((listval) => {
        //             var item_data = temData.data();
        //             database.collection('restaurant_orders').doc(item_data.id).delete().then(function () {
        //             });
        //         });
        //     }
        // });
        await database.collection('foods_review').where('VendorId', '==', VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('foods_review').doc(item_data.id).delete().then(function () {
                    });
                });
            }

        });
        await database.collection('coupons').where('resturant_id', '==', VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    console.log("coupons"+item_data);
                    database.collection('coupons').doc(item_data.id).delete().then(function () {
                    });
                });
            }

        });
        await database.collection('favorite_restaurant').where('restaurant_id','==',VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('favorite_restaurant').doc(item_data.id).delete().then(function () {
                    });
                });
            }
        })
        await database.collection('favorite_item').where('store_id','==',VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('favorite_item').doc(item_data.id).delete().then(function () {
                    });
                });
            }
        })
        await database.collection('payouts').where('vendorID', '==', VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('payouts').doc(item_data.id).delete().then(function () {
                    });
                });
            }

        });
        await database.collection('booked_table').where('vendorID', '==', VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('booked_table').doc(item_data.id).delete().then(function () {
                    });
                });
            }

        });
        await database.collection('story').where('vendorID', '==', VendorId).get().then(async function (snapshotsItem) {
            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();
                    database.collection('story').doc(item_data.id).delete().then(function () {
                    });
                });
            }

        });
    
    }
    async function deleteUserData(userId,VendorId) {

        // console.log("userId"+userId+"VendorId"+VendorId);

        await database.collection('wallet').where('user_id', '==', userId).get().then(async function (snapshotsItem) {

            if (snapshotsItem.docs.length > 0) {
                snapshotsItem.docs.forEach((temData) => {
                    var item_data = temData.data();

                    database.collection('wallet').doc(item_data.id).delete().then(function () {

                    });
                });
            }
        });


        //delete user from authentication
        var dataObject = {"data": {"uid": userId}};
        var projectId = '<?php echo env('FIREBASE_PROJECT_ID') ?>';
        jQuery.ajax({
            url: 'https://us-central1-' + projectId + '.cloudfunctions.net/deleteUser',
            method: 'POST',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(dataObject),
            success: function (data) {
                console.log("data"+data);
                console.log('Delete user success:', data.result);
                database.collection('users').doc(userId).delete().then(function () {
                });
            },
            error: function (xhr, status, error) {
                var responseText = JSON.parse(xhr.responseText);
                console.log('Delete user error:', responseText.error);
            }
        });

        database.collection('vendors').doc(VendorId).delete().then(function () {
            const getStoreName = deleteStoreData(VendorId);
            setTimeout(function () {
                window.location.reload();
            }, 7000);
        });
    }

    function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];

        if (end != undefined || end != null) {

            if (jQuery("#selected_search").val() == 'first_name' && jQuery("#search").val().trim() != '') {
                listener = ref.orderBy('firstName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();

            } else if (jQuery("#selected_search").val() == 'last_name' && jQuery("#search").val().trim() != '') {

                listener = ref.orderBy('lastName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();

            } else if (jQuery("#selected_search").val() == 'email' && jQuery("#search").val().trim() != '') {

                listener = ref.orderBy('email').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();

            } else {
                listener = ref.startAt(end).limit(pagesize).get();
            }

            listener.then((snapshots) => {
                html = '';
                html = buildHTML(snapshots);

                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                }
            });
        }
    }

    function next() {

        if (start != undefined || start != null) {


            if (jQuery("#selected_search").val() == 'first_name' && jQuery("#search").val().trim() != '') {

                listener = ref.orderBy('firstName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();

            } else if (jQuery("#selected_search").val() == 'last_name' && jQuery("#search").val().trim() != '') {

                listener = ref.orderBy('lastName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();

            } else if (jQuery("#selected_search").val() == 'email' && jQuery("#search").val().trim() != '') {

                listener = ref.orderBy('email').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();

            } else {
                listener = ref.startAfter(start).limit(pagesize).get();
            }

            listener.then((snapshots) => {

                html = '';
                html = buildHTML(snapshots);

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

    function searchclear() {
        jQuery("#search").val('');
        searchtext();
    }


    function searchtext() {

        append_list.innerHTML = '';

        if (jQuery("#selected_search").val() == 'first_name' && jQuery("#search").val().trim() != '') {

            wherequery = ref.orderBy('firstName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();

        } else if (jQuery("#selected_search").val() == 'last_name' && jQuery("#search").val().trim() != '') {

            wherequery = ref.orderBy('lastName').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();

        } else if (jQuery("#selected_search").val() == 'email' && jQuery("#search").val().trim() != '') {

            wherequery = ref.orderBy('email').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();

        } else {

            wherequery = ref.limit(pagesize).get();
        }

        wherequery.then((snapshots) => {

            html = '';
            html = buildHTML(snapshots);

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

    $(document).on("click", "a[name='vendor-delete']", function (e) {
        var id = this.id;
        var VendorId = $(this).attr('data-vendorid');
        jQuery("#data-table_processing").show();
        database.collection('users').doc(id).delete().then(function (result) {
            const getStoreName = deleteUserData(id,VendorId);
            setTimeout(function () {
                window.location.href = '{{ url()->current() }}';
            }, 7000);
        });

    });

    $(document).on("click", "input[name='isActive']", function (e) {
        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('users').doc(id).update({'active': true}).then(function (result) {
            });
        } else {
            database.collection('users').doc(id).update({'active': false}).then(function (result) {
            });
        }

    });

</script>

@endsection
