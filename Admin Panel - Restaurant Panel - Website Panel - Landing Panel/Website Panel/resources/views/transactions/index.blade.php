@include('layouts.app')


@include('layouts.header')


<div class="siddhi-popular">


    <div class="container">


        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.Processing')}}
        </div>

        <div class="transactions-banner p-4 rounded">
            <div class="row align-items-center text-center">
                <div class="col-md-6">
                    <h3 class="font-weight-bold h4 text-light" id="total_payment"></h3>
                </div>
                <!-- <div class="col-md-6 traban-right">
                    <a href="#" class="btn btn-primary btn-md">Topup Wallet</a>
                </div> -->
            </div>
        </div>

        <div class="text-center py-5 not_found_div" style="display:none">
            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
            <p class="font-weight-bold text-dark h5">{{trans('lang.nothing_found')}}</p>
            <p>{{trans('lang.please_try_again')}}</p>
        </div>

        <!-- <div class="transactions-list-wrap mt-4">
            <div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm">
                <div class="gold-members d-flex align-items-center transactions-list">
                
                <div class="media transactions-list-left">
                    <div class="mr-3 font-weight-bold card-icon"><span><i class="fa fa-credit-card"></i></span></div>
                    <div class="media-body">
                        <h6 class="date">Mar 24, 2022</h6><p class="text-muted mb-0">Wallet Topup</p>
                    </div>
                </div>

                <div class="float-right ml-auto">
                    <span class="price font-weight-bold h4">$ 20.00</span>
                    <span class="go-arror text-dark btn-block text-right mt-2"> <i class="fa fa-angle-right"></i></span>
                </div>

             </div>
           </div>

        </div>  -->


        <div id="append_list1" class="res-search-list"></div>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container mt-4 mb-4 p-0">

                    <div class="data-table_paginate">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item ">
                                    <a class="page-link" href="javascript:void(0);" id="users_table_previous_btn"
                                       onclick="prev()" data-dt-idx="0" tabindex="0">{{trans('lang.previous')}}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0);" id="users_table_next_btn"
                                       onclick="next()" data-dt-idx="2" tabindex="0">{{trans('lang.next')}}</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@include('layouts.footer')


@include('layouts.nav')


<script type="text/javascript">


    var ref = database.collection('wallet').where('user_id', '==', user_uuid).orderBy('date', 'desc');

    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var append_list = '';
    var totalPayment = 0;

    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_digits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;

        if (currencyData.decimal_degits) {
            decimal_digits = currencyData.decimal_degits;

        }
    });


    $(document).ready(async function () {
        $("#data-table_processing").show();

        var totalPayment = 0;

        await database.collection('users').where("id", "==", user_uuid).get().then(
            (amountsnapshot) => {
                var paymentDatas = amountsnapshot.docs[0].data();


                if (paymentDatas.hasOwnProperty('wallet_amount') && paymentDatas.wallet_amount != null && !isNaN(paymentDatas.wallet_amount)) {

                    totalPayment = parseFloat(paymentDatas.wallet_amount);

                }

                if (currencyAtRight) {
                    totalPayment = totalPayment.toFixed(decimal_digits) + '' + currentCurrency;
                } else {
                    totalPayment = currentCurrency + '' + totalPayment.toFixed(decimal_digits);
                }

            });
        jQuery("#total_payment").html('{{trans("lang.total")}} {{trans("lang.payment")}} : ' + totalPayment);


        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';

        ref.limit(pagesize).get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();

                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    $("#data-table_processing").hide();
                }
            }

        });

    });


    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        var vendorIDS = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        alldata.forEach((listval) => {

            var val = listval;
            var date = val.date.toDate().toDateString();
            var time = val.date.toDate().toLocaleTimeString('en-US');
            var price_val = '';

            if (currencyAtRight) {
                price_val = parseFloat(val.amount).toFixed(decimal_digits) + '' + currentCurrency;
            } else {
                price_val = currentCurrency + '' + parseFloat(val.amount).toFixed(decimal_digits);
            }

            html = html + '<div class="transactions-list-wrap mt-4"><div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm"><div class="gold-members d-flex align-items-center transactions-list">';

            var desc = '';

            if ((val.hasOwnProperty('isTopUp') && val.isTopUp) || (val.payment_method == "Cancelled Order Payment")) {
                price_val = '<div class="float-right ml-auto"><span class="price font-weight-bold h4">+ ' + price_val + '</span>';
                desc = "Wallet Topup";
            } else if (val.hasOwnProperty('isTopUp') && !val.isTopUp) {

                price_val = '<div class="float-right ml-auto"><span class="font-weight-bold h4" style="color: red">- ' + price_val + '</span>';
                desc = "Wallet Amount Deducted";
            } else {
                price_val = '<div class="float-right ml-auto"><span class="font-weight-bold h4">' + price_val + '</span>';

            }

            html = html + '<div class="media transactions-list-left"><div class="mr-3 font-weight-bold card-icon"><span><i class="fa fa-credit-card"></i></span></div><div class="media-body"><h6 class="date">' + desc + '</h6><h6>' + date + ' ' + time + '</h6><p class="text-muted mb-0">' + val.payment_method + '</p><p class="mb-0 badge badge-success text-light">' + val.payment_status + '</p></div></div>';


            html = html + price_val;

            if (val.hasOwnProperty('order_id') && val.order_id != "") {
                var view_details = "{{ route('completed_order',':id')}}";
                view_details = view_details.replace(':id', 'id=' + val.order_id);

                html = html + '<a href="' + view_details + '"><span class="go-arror text-dark btn-block text-right mt-2"><i class="fa fa-angle-right"></i></span></a>';
            }


            html = html + '</div> </div></div></div>';

        });

        return html;

    }

    async function next() {
        if (start != undefined || start != null) {
            jQuery("#data-table_processing").hide();

            listener = ref.startAfter(start).limit(pagesize).get();
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
            listener = ref.startAt(end).limit(pagesize).get();

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


</script>









