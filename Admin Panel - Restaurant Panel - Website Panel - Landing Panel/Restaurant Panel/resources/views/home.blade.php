@extends('layouts.app')

@section('content')

    <div id="main-wrapper" class="page-wrapper" style="min-height: 207px;">

        <div class="container-fluid">

            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                 style="display: none;margin-top:20px;">{{trans('lang.processing')}}
            </div>

            <div class="card mb-3 business-analytics">

                <div class="card-body">

                    <div class="row flex-between align-items-center g-2 mb-3 order_stats_header">
                        <div class="col-sm-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">{{trans('lang.dashboard_business_analytics')}}</h4>
                        </div>
                    </div>

                    <div class="row business-analytics_list">

                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card-box" onclick="location.href='{!! route('payments') !!}'">
                                <h5>{{trans('lang.dashboard_total_earnings')}}</h5>
                                <h2 id="earnings_count"></h2>
                                <i class="mdi mdi-cash-usd"></i>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card-box" onclick="location.href='{!! route('orders') !!}'">
                                <h5>{{trans('lang.dashboard_total_orders')}}</h5>
                                <h2 id="order_count"></h2>
                                <i class="mdi mdi-cart"></i>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-3">
                            <div class="card-box" onclick="location.href='{!! route('foods') !!}'">
                                <h5>{{trans('lang.dashboard_total_products')}}</h5>
                                <h2 id="product_count"></h2>
                                <i class="mdi mdi-buffer"></i>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status pending" href="{!! route('placedOrders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-lan-pending"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_placed')}}</h6>
                                </div>
                                <span class="count" id="placed_count"></span> </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status confirmed" href="{!! route('acceptedOrders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-check-circle"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_confirmed')}}</h6>
                                </div>
                                <span class="count" id="confirmed_count"></span> </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status packaging" href="{!! route('orders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-clipboard-outline"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_shipped')}}</h6>
                                </div>
                                <span class="count" id="shipped_count"></span> </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status delivered" href="{!! route('orders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-check-circle-outline"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_completed')}}</h6>
                                </div>
                                <span class="count" id="completed_count"></span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status canceled" href="{!! route('rejectedOrders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-window-close"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_canceled')}}</h6>
                                </div>
                                <span class="count" id="canceled_count"></span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status failed" href="{!! route('orders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_failed')}}</h6>
                                </div>
                                <span class="count" id="failed_count"></span>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a class="order-status failed" href="{!! route('orders') !!}">
                                <div class="data">
                                    <i class="mdi mdi-car-connected"></i>
                                    <h6 class="status">{{trans('lang.dashboard_order_pending')}}</h6>
                                </div>
                                <span class="count" id="pending_count"></span>
                            </a>
                        </div>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">{{trans('lang.total_sales')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative">
                                <canvas id="sales-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2"> <i class="fa fa-square" style="color:#2EC7D9"></i> {{trans('lang.dashboard_this_year')}} </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">{{trans('lang.service_overview')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flex-row">
                                <canvas id="visitors" height="222"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">{{trans('lang.sales_overview')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flex-row">
                                <canvas id="commissions" height="222"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row daes-sec-sec mb-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header no-border d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.recent_orders')}}</h3>
                            <div class="card-tools">
                                <a href="{{route('orders')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-valign-middle" id="orderTable">
                                <thead>
                                <tr>
                                    <th>{{trans('lang.order_id')}}</th>
                                    <th>{{trans('lang.order_user_id')}}</th>
                                    <th>{{trans('lang.order_type')}}</th>
                                    <th>{{trans('lang.total_amount')}}</th>
                                    <th>{{trans('lang.quantity')}}</th>
                                    <th>{{trans('lang.order_date')}}</th>
                                    <th>{{trans('lang.order_order_status_id')}}</th>
                                </tr>
                                </thead>
                                <tbody id="append_list_recent_order">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


@endsection

@section('scripts')

    <script src="{{asset('js/chart.js')}}"></script>

    <script>

        jQuery("#data-table_processing").show();

        var db = firebase.firestore();
        var currency = db.collection('settings');

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


        var vendorUserId = "<?php echo $id; ?>";

        var vendorId = '';

        getVendorId(vendorUserId).then(data => {

            vendorId = data;

            jQuery("#data-table_processing").show();

            $(document).ready(function () {

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).get().then(
                    (snapshot) => {
                        jQuery("#order_count").empty();
                        jQuery("#order_count").text(snapshot.docs.length);
                    });

                db.collection('vendor_products').where('vendorID', "==", vendorId).get().then(
                    (snapshot) => {
                        jQuery("#product_count").empty();
                        jQuery("#product_count").text(snapshot.docs.length);
                        setVisitors();
                    });

                getTotalEarnings();

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Placed"]).get().then(
                    (snapshot) => {
                        jQuery("#placed_count").empty();
                        jQuery("#placed_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Accepted", "Driver Accepted"]).get().then(
                    (snapshot) => {
                        jQuery("#confirmed_count").empty();
                        jQuery("#confirmed_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Shipped", "In Transit"]).get().then(
                    (snapshot) => {
                        jQuery("#shipped_count").empty();
                        jQuery("#shipped_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Completed"]).get().then(
                    (snapshot) => {
                        jQuery("#completed_count").empty();
                        jQuery("#completed_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Rejected"]).get().then(
                    (snapshot) => {
                        jQuery("#canceled_count").empty();
                        jQuery("#canceled_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Driver Rejected"]).get().then(
                    (snapshot) => {
                        jQuery("#failed_count").empty();
                        jQuery("#failed_count").text(snapshot.docs.length);
                    });

                db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Driver Pending"]).get().then(
                    (snapshot) => {
                        jQuery("#pending_count").empty();
                        jQuery("#pending_count").text(snapshot.docs.length);
                    });

                var placeholder = db.collection('settings').doc('placeHolderImage');
                placeholder.get().then(async function (snapshotsimage) {
                    var placeholderImageData = snapshotsimage.data();
                    placeholderImage = placeholderImageData.image;

                })

                var offest = 1;
                var pagesize = 10;
                var start = null;
                var end = null;
                var endarray = [];
                var inx = parseInt(offest) * parseInt(pagesize);
                var append_listrecent_order = document.getElementById('append_list_recent_order');
                append_listrecent_order.innerHTML = '';

                ref = db.collection('restaurant_orders');
                ref.orderBy('createdAt', 'desc').where('status', 'in', ["Order Placed", "Order Accepted", "Driver Pending", "Driver Accepted", "Order Shipped", "In Transit"]).where('vendorID', "==", vendorId).limit(inx).get().then(async (snapshots) => {
                    var html = '';
                    html = await buildOrderHTML(snapshots);
                    if (html != '') {
                        append_listrecent_order.innerHTML = html;
                        start = snapshots.docs[snapshots.docs.length - 1];
                        endarray.push(snapshots.docs[0]);
                    }

                    $('#orderTable').DataTable({
                        order: [],
                        columnDefs: [
                            {
                                targets: 5,
                                type: 'date',
                                render: function (data) {

                                    return data;
                                }
                            },
                            {orderable: false, targets: [6]},
                        ],
                        order: [['5', 'desc']],
                        "language": {
                            "zeroRecords": "{{trans("lang.no_record_found")}}",
                            "emptyTable": "{{trans("lang.no_record_found")}}"
                        },
                        responsive: true
                    });


                });

            });
        });

        async function getVendorId(vendorUser) {
            var vendorId = '';
            var ref;

            if (vendorUser) {
                await db.collection('vendors').where('author', "==", vendorUser).get().then(async function (vendorSnapshots) {

                    if (vendorSnapshots.docs[0]) {
                        var vendorData = vendorSnapshots.docs[0].data();
                        vendorId = vendorData.id;
                    }

                });
            }

            return vendorId;
        }

        async function getTotalEarnings() {
            var intRegex = /^\d+$/;
            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
            var v01 = 0;
            var v02 = 0;
            var v03 = 0;
            var v04 = 0;
            var v05 = 0;
            var v06 = 0;
            var v07 = 0;
            var v08 = 0;
            var v09 = 0;
            var v10 = 0;
            var v11 = 0;
            var v12 = 0;
            var currentYear = new Date().getFullYear();
            await db.collection('restaurant_orders').where('vendorID', "==", vendorId).where('status', 'in', ["Order Completed"]).get().then(async function (orderSnapshots) {
                var paymentData = orderSnapshots.docs;
                var totalEarning = 0;
                var adminCommission = 0;
                paymentData.forEach((order) => {
                    var orderData = order.data();
                    var price = 0;
                    var minprice = 0;
                    orderData.products.forEach((product) => {

                        if (product.price && product.quantity != 0) {
                            var extras_price = 0;
                            if (product.extras_price != undefined && product.extras_price != null) {
                                extras_price = parseFloat(product.extras_price) * parseInt(product.quantity);
                            }
                            if (!isNaN(extras_price)) {
                                var productTotal = (parseFloat(product.price) * parseInt(product.quantity)) + extras_price;
                            } else {
                                var productTotal = (parseFloat(product.price) * parseInt(product.quantity));
                            }
                            if (!isNaN(productTotal)) {
                                price = price + productTotal;
                                minprice = minprice + productTotal;
                            }

                        }
                    })

                    discount = orderData.discount;
                    if ((intRegex.test(discount) || floatRegex.test(discount)) && !isNaN(discount)) {
                        discount = parseFloat(discount).toFixed(decimal_degits);
                        price = price - parseFloat(discount);
                        minprice = minprice - parseFloat(discount);
                    }

                    tax = 0;
                    if (orderData.hasOwnProperty('taxSetting')) {
                        if (orderData.taxSetting.type && orderData.taxSetting.tax) {
                            if (orderData.taxSetting.type == "percent") {
                                tax = (parseFloat(orderData.taxSetting.tax) * minprice) / 100;
                            } else {
                                tax = parseFloat(orderData.taxSetting.tax);
                            }
                        }
                    }

                    if (!isNaN(tax)) {
                        price = price + tax;
                    }

                    if (orderData.deliveryCharge != undefined && orderData.deliveryCharge != "" && orderData.deliveryCharge > 0) {
                        price = price + parseFloat(orderData.deliveryCharge);
                    }

                    if (orderData.adminCommission != undefined && orderData.adminCommissionType != undefined && orderData.adminCommission > 0 && price > 0) {
                        var commission = 0;
                        if (orderData.adminCommissionType == "Percent") {
                            commission = (price * parseFloat(orderData.adminCommission)) / 100;

                        } else {
                            commission = parseFloat(orderData.adminCommission);
                        }

                        adminCommission = commission + adminCommission;
                    } else if (orderData.adminCommission != undefined && orderData.adminCommission > 0 && price > 0) {
                        var commission = parseFloat(orderData.adminCommission);
                        adminCommission = commission + adminCommission;
                    }

                    totalEarning = parseFloat(totalEarning) + parseFloat(price);

                    try {

                        if (orderData.createdAt) {
                            var orderMonth = orderData.createdAt.toDate().getMonth() + 1;
                            var orderYear = orderData.createdAt.toDate().getFullYear();
                            if (currentYear == orderYear) {
                                switch (parseInt(orderMonth)) {
                                    case 1:
                                        v01 = parseInt(v01) + price;
                                        break;
                                    case 2:
                                        v02 = parseInt(v02) + price;
                                        break;
                                    case 3:
                                        v03 = parseInt(v03) + price;
                                        break;
                                    case 4:
                                        v04 = parseInt(v04) + price;
                                        break;
                                    case 5:
                                        v05 = parseInt(v05) + price;
                                        break;
                                    case 6:
                                        v06 = parseInt(v06) + price;
                                        break;
                                    case 7:
                                        v07 = parseInt(v07) + price;
                                        break;
                                    case 8:
                                        v08 = parseInt(v08) + price;
                                        break;
                                    case 9:
                                        v09 = parseInt(v09) + price;
                                        break;
                                    case 10:
                                        v10 = parseInt(v10) + price;
                                        break;
                                    case 11:
                                        v11 = parseInt(v11) + price;
                                        break;
                                    default :
                                        v12 = parseInt(v12) + price;
                                        break;
                                }
                            }
                        }

                    } catch (err) {


                        var datas = new Date(orderData.createdAt._seconds * 1000);

                        var dates = firebase.firestore.Timestamp.fromDate(datas);

                        db.collection('restaurant_orders').doc(orderData.id).update({'createdAt': dates}).then(() => {

                            console.log('Provided document has been updated in Firestore');

                        }, (error) => {

                            console.log('Error: ' + error);

                        });

                    }


                })

                if (currencyAtRight) {
                    totalEarning = parseFloat(totalEarning).toFixed(decimal_degits) + "" + currentCurrency;
                    adminCommission = parseFloat(adminCommission).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    totalEarning = currentCurrency + "" + parseFloat(totalEarning).toFixed(decimal_degits);
                    adminCommission = currentCurrency + "" + parseFloat(adminCommission).toFixed(decimal_degits);
                }

                $("#earnings_count").append(totalEarning);
                $("#earnings_count_graph").append(totalEarning);
                $("#admincommission_count_graph").append(adminCommission);
                $("#admincommission_count").append(adminCommission);
                $("#total_earnings_header").text(totalEarning);
                $(".earnings_over_time").append(totalEarning);
                var data = [v01, v02, v03, v04, v05, v06, v07, v08, v09, v10, v11, v12];
                var labels = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
                var $salesChart = $('#sales-chart');
                var salesChart = renderChart($salesChart, data, labels);
                setCommision();
            })
            jQuery("#data-table_processing").hide();

        }


        async function buildOrderHTML(snapshots) {
            var html = '';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var val = listval.data();

                var getData = await getListData(val);
                html += getData;
            }));
            return html;
        }

        async function getListData(val) {
            var html = '';

            var route = '<?php echo route("orders.edit", ":id"); ?>';
            route = route.replace(':id', val.id);

            html = html + '<tr>';

            html = html + '<td data-url="' + route + '" class="redirecttopage">' + val.id + '</td>';

            html = html + '<td data-url="' + route + '" class="redirecttopage">' + val.author.firstName + ' ' + val.author.lastName + '</td>';

            if (val.takeAway == true) {
                html = html + '<td data-url="' + route + '" class="redirecttopage">Take away</td>';
            } else {
                html = html + '<td data-url="' + route + '" class="redirecttopage">Order Delivery</td>';
            }

            var price = 0;
            if (val.deliveryCharge != undefined) {
                price = parseInt(val.deliveryCharge) + price;
            }
            if (val.tip_amount != undefined) {
                price = parseInt(val.tip_amount) + price;
            }


            var date = '';
            var time = '';

            if (val.hasOwnProperty('createdAt')) {
                try {
                    date = val.createdAt.toDate().toDateString();
                    time = val.createdAt.toDate().toLocaleTimeString();
                } catch (e) {

                }
            }


            var price = await buildHTMLProductstotal(val);

            html = html + '<td data-url="' + route + '" class="redirecttopage">' + price + '</td>';
            html = html + '<td data-url="' + route + '" class="redirecttopage"><i class="fa fa-shopping-cart"></i> ' + val.products.length + '</td>';
            html = html + '<td data-url="' + route + '" class="redirecttopage">' + date + ' ' + time + '</td>';

            if (val.status == 'Order Placed') {
                html = html + '<td class="order_placed redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Accepted') {
                html = html + '<td class="order_accepted redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Rejected') {
                html = html + '<td class="order_rejected redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Driver Pending') {
                html = html + '<td class="driver_pending redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Driver Rejected') {
                html = html + '<td class="driver_rejected redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Shipped') {
                html = html + '<td class="order_shipped redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'In Transit') {
                html = html + '<td class="in_transit redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            } else if (val.status == 'Order Completed') {
                html = html + '<td class="order_completed redirecttopage" data-url="' + route + '"><span>' + val.status + '</span></td>';

            }

            html = html + '</a></tr>';

            return html;
        }


        function renderChart(chartNode, data, labels) {
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            };

            var mode = 'index';
            var intersect = true;
            return new Chart(chartNode, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            backgroundColor: '#2EC7D9',
                            borderColor: '#2EC7D9',
                            data: data
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                callback: function (value, index, values) {
                                    return currentCurrency + value.toFixed(decimal_degits);
                                }


                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        }

        $(document).ready(function () {
            $(document.body).on('click', '.redirecttopage', function () {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });
        });


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

            var intRegex = /^\d+$/;
            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

            if (products) {

                products.forEach((product) => {

                    var val = product;

                    price_item = parseFloat(val.price).toFixed(decimal_degits);
                    extras_price_item = (parseFloat(val.extras_price) * parseInt(val.quantity)).toFixed(decimal_degits);

                    totalProductPrice = parseFloat(price_item) * parseInt(val.quantity);
                    var extras_price = 0;
                    if (parseFloat(extras_price_item) != NaN && val.extras_price != undefined) {
                        extras_price = extras_price_item;
                    }
                    totalProductPrice = parseFloat(extras_price) + parseFloat(totalProductPrice);
                    totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);

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

            tax = 0;
            if (snapshotsProducts.hasOwnProperty('taxSetting')) {
                if (snapshotsProducts.taxSetting.type && snapshotsProducts.taxSetting.tax) {
                    if (snapshotsProducts.taxSetting.type == "percent") {
                        tax = (snapshotsProducts.taxSetting.tax * total_price) / 100;
                    } else {
                        tax = snapshotsProducts.taxSetting.tax;
                    }
                }
            }

            if (!isNaN(tax)) {
                total_price = parseFloat(total_price) + parseFloat(tax);
            }

            if (intRegex.test(deliveryCharge) || floatRegex.test(deliveryCharge)) {

                deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits);
                total_price += parseFloat(deliveryCharge);

                if (currencyAtRight) {
                    deliveryCharge_val = deliveryCharge + "" + currentCurrency;
                } else {
                    deliveryCharge_val = currentCurrency + "" + deliveryCharge;
                }
            }


            if (intRegex.test(tip_amount) || floatRegex.test(tip_amount)) {

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
                var total_price_val = total_price + "" + currentCurrency;
            } else {
                var total_price_val = currentCurrency + "" + total_price;
            }


            return total_price_val;
        }

        function setVisitors() {

            const data = {
                labels: [
                    "{{trans('lang.dashboard_total_orders')}}",
                    "{{trans('lang.dashboard_total_products')}}",
                ],
                datasets: [{
                    data: [jQuery("#order_count").text(), jQuery("#product_count").text()],
                    backgroundColor: [
                        '#B1DB6F',
                        '#7360ed',
                    ],
                    hoverOffset: 4
                }]
            };

            return new Chart('visitors', {
                type: 'doughnut',
                data: data,
                options: {
                    maintainAspectRatio: false,
                }
            })
        }

        function setCommision() {

            const data = {
                labels: [
                    "{{trans('lang.dashboard_total_earnings')}}"
                ],
                datasets: [{
                    data: [jQuery("#earnings_count").text().replace(currentCurrency, "")],
                    backgroundColor: [
                        '#feb84d',
                        '#9b77f8',
                        '#fe95d3'
                    ],
                    hoverOffset: 4
                }]
            };
            return new Chart('commissions', {
                type: 'doughnut',
                data: data,
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return data.labels[tooltipItems.index] + ': ' + currentCurrency + data.datasets[0].data[tooltipItems.index];
                            }
                        }
                    }
                }
            })
        }

    </script>

@endsection

