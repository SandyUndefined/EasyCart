@extends('layouts.app')

@section('content')

    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor">{{trans('lang.order_plural')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.order_plural')}}</li>
                </ol>
            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">
                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{ trans('lang.processing')}}</div>
                            <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                            {{--<div id="users-table_filter" class="pull-right">
                                <label>{{trans('lang.search_by')}}
                                    <select name="selected_search" id="selected_search" class="form-control input-sm">
                                        <option value="status">{{trans('lang.order_order_status_id')}}</option>
                                        <option value="id">{{trans('lang.order_id')}}</option>

                                    </select>
                                </label>&nbsp;
                                <div class="form-group">
                                    <!-- <div id="selected_change"> -->

                                    <select id="order_status" class="form-control">
                                        <option value="All">{{ trans('lang.all')}}</option>
                                        <option value="Order Placed">{{ trans('lang.order_placed')}}</option>
                                        <option value="Order Accepted">{{ trans('lang.order_accepted')}}</option>
                                        <option value="Order Rejected">{{ trans('lang.order_rejected')}}</option>
                                        <option value="Driver Pending">{{ trans('lang.driver_pending')}}</option>
                                        <option value="Driver Rejected">{{ trans('lang.driver_rejected')}}</option>
                                        <option value="Order Shipped">{{ trans('lang.order_shipped')}}</option>
                                        <option value="In Transit">{{ trans('lang.in_transit')}}</option>
                                        <option value="Order Completed">{{ trans('lang.order_completed')}}</option>
                                    </select>
                                    <input type="search" id="search" class="search form-control" placeholder="Search"
                                           aria-controls="users-table" style="display:none">

                                    <button onclick="searchtext();"
                                            class="btn btn-warning btn-flat">{{trans('lang.search')}}</button>&nbsp;<button
                                            onclick="searchclear();"
                                            class="btn btn-warning btn-flat">{{trans('lang.clear')}}</button>
                                </div>
                            </div>--}}


                            <div class="table-responsive m-t-10">


                                <table id="orderTable"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">

                                    <thead>

                                    <tr>
                                        <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active">
                                                <a id="deleteAll" class="do_not_delete" href="javascript:void(0)">
                                                    <i class="fa fa-trash"></i> {{trans('lang.all')}}</a></label></th>
                                        <th>{{trans('lang.order_id')}}</th>

                                        <th>{{trans('lang.order_user_id')}}</th>
                                        <th class="driverClass">{{trans('lang.driver_plural')}}</th>
                                        <th>{{trans('lang.order_order_status_id')}}</th>
                                        <th>{{trans('lang.amount')}}</th>
                                        <th>{{trans('lang.order_type')}}</th>
                                        <th>{{trans('lang.date')}}</th>
                                        <th>{{trans('lang.actions')}}</th>

                                    </tr>

                                    </thead>

                                    <tbody id="append_list1">


                                    </tbody>

                                </table>
                                {{--<div id="data-table_paginate" style="display:none">
                                    <nav aria-label="Page navigation example" class="pagination_div">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item ">
                                                <a class="page-link" href="javascript:void(0);"
                                                   id="users_table_previous_btn" onclick="prev()" data-dt-idx="0"
                                                   tabindex="0">{{trans('lang.previous')}}</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);"
                                                   id="users_table_next_btn" onclick="next()" data-dt-idx="2"
                                                   tabindex="0">{{trans('lang.next')}}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>--}}
                            </div>

                        </div>

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
        var user_id = "<?php echo $id; ?>";
        var append_list = '';
        var user_number = [];
        var refData = database.collection('restaurant_orders').where('vendor.author', "==", user_id);
        var ref = database.collection('restaurant_orders').orderBy('createdAt', 'desc').where('vendor.author', "==", user_id);

        var currentCurrency = '';
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

        $(document).ready(function () {
            var order_status = jQuery('#order_status').val();
            var search = jQuery("#search").val();


            $(document.body).on('click', '.redirecttopage', function () {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });
            jQuery('#search').hide();

            $(document.body).on('change', '#selected_search', function () {

                if (jQuery(this).val() == 'status') {
                    jQuery('#order_status').show();
                    jQuery('#search').hide();
                } else {

                    jQuery('#order_status').hide();
                    jQuery('#search').show();

                }
            });


            jQuery("#data-table_processing").show();
            append_list = document.getElementById('append_list1');
            append_list.innerHTML = '';
            ref.get().then(async function (snapshots) {
                html = '';
                if (snapshots.docs.length > 0) {
                    html = await buildHTML(snapshots);
                }
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                }
                $('#orderTable').DataTable({
                    order: [],
                    columnDefs: [
                        {
                            targets: 7,
                            type: 'date',
                            render: function (data) {

                                return data;
                            }
                        },
                        {orderable: false, targets: [0, 8,4]},
                    ],
                    order: [['7', 'desc']],
                    "language": {
                        "zeroRecords": "{{trans("lang.no_record_found")}}",
                        "emptyTable": "{{trans("lang.no_record_found")}}"
                    },
                    responsive: true
                });

            });
             jQuery("#data-table_processing").hide();


        });

        async function buildHTML(snapshots) {
            var html='';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var val = listval.data();
                let result = user_number.filter(obj => {
                    return obj.id == val.author;
                })

                if (result.length > 0) {
                    val.phoneNumber = result[0].phoneNumber;
                    val.isActive = result[0].isActive;

                } else {
                    val.phoneNumber = '';
                    val.isActive = false;
                }

                var getData = await getListData(val);
                html += getData;
            
            }));
            
            return html;
        }

        async function getListData(val) {
            html='';
            html = html + '<tr>';
            newdate = '';
            var id = val.id;
            var route1 = '{{route("orders.edit",":id")}}';
            route1 = route1.replace(':id', id);

            var printRoute = '{{route("vendors.orderprint",":id")}}';
            printRoute = printRoute.replace(':id', id);
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
            html = html + '<td data-url="' + route1 + '" class="redirecttopage">' + val.id + '</td>';
            html = html + '<td>' + val.author.firstName + ' ' + val.author.lastName + '</td>';
            if (val.hasOwnProperty("driver") && val.driver!=null) {

                html = html + '<td >' + val.driver.firstName + ' ' + val.driver.lastName + '</td>';

            } else {
                html = html + '<td></td>';
            }
            if (val.status == 'Order Placed') {
                html = html + '<td class="order_placed"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Accepted') {
                html = html + '<td class="order_accepted"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Rejected') {
                html = html + '<td class="order_rejected"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Driver Pending') {
                html = html + '<td class="driver_pending"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Driver Rejected') {
                html = html + '<td class="driver_rejected"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Shipped') {
                html = html + '<td class="order_shipped"><span>' + val.status + '</span></td>';

            } else if (val.status == 'In Transit') {
                html = html + '<td class="in_transit"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Completed') {
                html = html + '<td class="order_completed"><span>' + val.status + '</span></td>';

            }

            var price = 0;
            var price =  buildHTMLProductstotal(val);

            html = html + '<td class="text-green">' + price + '</td>';
            if (val.takeAway) {
                html = html + '<td>{{trans("lang.order_takeaway")}}</td>';

            } else {
                html = html + '<td>{{trans("lang.order_delivery")}}</td>';
            }
            var createdAt_val = '';
            if (val.createdAt) {
                var date1 = val.createdAt.toDate().toDateString();
                createdAt_val = date1;
                var time = val.createdAt.toDate().toLocaleTimeString('en-US');
                createdAt_val = createdAt_val + ' ' + time;
            }

            html = html + '<td>' + createdAt_val + '</td>';


            html = html + '<td class="action-btn"><a href="' + printRoute + '"><i class="fa fa-print" style="font-size:20px;"></i></a><a href="' + route1 + '"><i class="fa fa-edit"></i></a><a id="' + val.id + '" class="do_not_delete" name="order-delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>';


            html = html + '</tr>';
            return html;

        }


        $("#is_active").click(function () {
            $("#orderTable .is_open").prop('checked', $(this).prop('checked'));

        });

        $("#deleteAll").click(function () {
            if ($('#orderTable .is_open:checked').length) {
                if (confirm('Are You Sure want to Delete Selected Data ?')) {
                    jQuery("#data-table_processing").show();
                    $('#orderTable .is_open:checked').each(function () {
                        var dataId = $(this).attr('dataId');

                        database.collection('restaurant_orders').doc(dataId).delete().then(function () {

                            window.location.reload();
                        });

                    });

                }
            } else {
                alert('Please Select Any One Record .');
            }
        });

        function prev() {
            if (endarray.length == 1) {
                return false;
            }
            end = endarray[endarray.length - 2];

            if (end != undefined || end != null) {
                jQuery("#data-table_processing").show();
                if (jQuery("#selected_search").val() == 'status' && jQuery("#order_status").val() != 'All' && jQuery("#order_status").val().trim() != '') {

                    listener = refData.orderBy('status').limit(pagesize).startAt(jQuery("#order_status").val()).endAt(jQuery("#order_status").val() + '\uf8ff').startAt(end).get();
                } else if (jQuery("#selected_search").val() == 'id' && jQuery("#search").val().trim() != '') {

                    listener = refData.orderBy('id').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();
                } else {
                    listener = ref.startAt(end).limit(pagesize).get();
                }

                listener.then((snapshots) => {
                    html = '';
                    html = buildHTML(snapshots);
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


        function next() {
            if (start != undefined || start != null) {

                jQuery("#data-table_processing").hide();

                if (jQuery("#selected_search").val() == 'status' && jQuery("#order_status").val() != 'All' && jQuery("#order_status").val().trim() != '') {

                    listener = refData.orderBy('status').limit(pagesize).startAt(jQuery("#order_status").val()).endAt(jQuery("#order_status").val() + '\uf8ff').startAfter(start).get();
                } else if (jQuery("#selected_search").val() == 'id' && jQuery("#search").val().trim() != '') {

                    listener = refData.orderBy('id').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();
                } else {
                    listener = ref.startAfter(start).limit(pagesize).get();
                }
                listener.then((snapshots) => {

                    html = '';
                    html = buildHTML(snapshots);
                    
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

        $(document).on("click", "a[name='order-delete']", function (e) {
            var id = this.id;
            database.collection('restaurant_orders').doc(id).delete().then(function (result) {
                window.location.href = '{{ url()->current() }}';
            });


        });

        function searchclear() {
            jQuery("#search").val('');
            jQuery("#order_status").val('All');
            
            location.reload();
        }

        function searchtext() {
            var offest = 1;
            jQuery("#data-table_processing").show();

            append_list.innerHTML = '';

            if (jQuery("#selected_search").val() == 'status' && jQuery("#order_status").val() != 'All' && jQuery("#order_status").val().trim() != '') {
                wherequery = refData.orderBy('status').limit(pagesize).startAt(jQuery("#order_status").val()).endAt(jQuery("#order_status").val() + '\uf8ff').get();

            } else if (jQuery("#selected_search").val() == 'id' && jQuery("#search").val().trim() != '') {

                wherequery = refData.orderBy('id').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();

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

        function buildHTMLProductstotal(snapshotsProducts) {

            var adminCommission = snapshotsProducts.adminCommission;
            var discount = snapshotsProducts.discount;
            var couponCode = snapshotsProducts.couponCode;
            var extras = snapshotsProducts.extras;
            var extras_price = snapshotsProducts.extras_price;
            var rejectedByDrivers = snapshotsProducts.rejectedByDrivers;
            var takeAway = snapshotsProducts.takeAway;
            var tip_amount = snapshotsProducts.tip_amount;
            var status = snapshotsProducts.status;
            var products = snapshotsProducts.products;
            var deliveryCharge = snapshotsProducts.deliveryCharge;
            var totalProductPrice = 0;
            var total_price = 0;
            var specialDiscount = snapshotsProducts.specialDiscount;

            var intRegex = /^\d+$/;
            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

            if (products) {

                products.forEach((product) => {

                    var val = product;
                    price_item = parseFloat(val.price).toFixed(2);
                    extras_price_item = (parseFloat(val.extras_price) * parseInt(val.quantity)).toFixed(2);

                    totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                    var extras_price = 0;
                    if (parseFloat(extras_price_item) != NaN && val.extras_price != undefined) {
                        extras_price = extras_price_item;
                    }
                    totalProductPrice = parseFloat(extras_price) + parseFloat(totalProductPrice);
                    totalProductPrice = parseFloat(totalProductPrice).toFixed(2);

                    total_price += parseFloat(totalProductPrice);

                });
            }

            if (intRegex.test(discount) || floatRegex.test(discount)) {

                discount = parseFloat(discount).toFixed(decimal_degits);
                total_price -= parseFloat(discount);

                if (currencyAtRight) {
                    discount_val = discount + "" + currentCurrency;
                } else {
                    discount_val = currentCurrency + "" + discount;
                }

            }
            var special_discount = 0;
            if (specialDiscount != undefined) {
                special_discount = parseFloat(specialDiscount.special_discount).toFixed(2);
                total_price = total_price - special_discount;
            }

            var total_item_price = total_price;
            var tax = 0;
            taxlabel = '';
            taxlabeltype = '';

            if (snapshotsProducts.hasOwnProperty('taxSetting')) {
                var total_tax_amount = 0;
                for (var i = 0; i < snapshotsProducts.taxSetting.length; i++) {
                    var data = snapshotsProducts.taxSetting[i];

                    if (data.type && data.tax) {
                        if (data.type == "percentage") {
                            tax = (data.tax * total_price) / 100;
                            taxlabeltype = "%";
                        } else {
                            tax = data.tax;
                            taxlabeltype = "fix";
                        }
                        taxlabel = data.title;
                    }
                    total_tax_amount += parseFloat(tax);
                }
                total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
            }


            if ((intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) && !isNaN(deliveryCharge)) {

                deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
                total_price += parseFloat(deliveryCharge);


                if (currencyAtRight) {
                    deliveryCharge_val = deliveryCharge + "" + currentCurrency;
                } else {
                    deliveryCharge_val = currentCurrency + "" + deliveryCharge;
                }
            }


            if (intRegex.test(tip_amount) || floatRegex.test(tip_amount) && !isNaN(tip_amount)) {

                tip_amount = parseFloat(tip_amount).toFixed(decimal_degits);
                total_price += parseFloat(tip_amount);
                total_price = parseFloat(total_price).toFixed(decimal_degits);

                if (currencyAtRight) {
                    tip_amount_val = tip_amount + "" + currentCurrency;
                } else {
                    tip_amount_val = currentCurrency + "" + tip_amount;
                }
            }

            if (currencyAtRight) {
                var total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                var total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
            }


            return total_price_val;
        }

    </script>


@endsection
