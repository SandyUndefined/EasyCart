@extends('layouts.app')

@section('content')

    <div class="page-wrapper">


        <div class="row page-titles">

            <div class="col-md-5 align-self-center">

                <h3 class="text-themecolor restaurantTitle">{{trans('lang.payout_request')}}</h3>

            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.payout_request')}}</li>
                </ol>
            </div>

            <div>

            </div>

        </div>


        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                <?php if ($id != '') { ?>
                    <div class="menu-tab">
                        <ul>
                            <li>
                                <a href="{{route('restaurants.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.foods',$id)}}">{{trans('lang.tab_foods')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.orders',$id)}}">{{trans('lang.tab_orders')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.coupons',$id)}}">{{trans('lang.tab_promos')}}</a>
                            <li>
                                <a href="{{route('restaurants.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                            </li>
                            <li class="active">
                                <a href="{{route('payoutRequests.restaurants.view',$id)}}">{{trans('lang.tab_payout_request')}}</a>
                            </li>
                            <li>
                                <a href="{{route('restaurants.booktable',$id)}}">{{trans('lang.dine_in_future')}}</a>
                            </li>
                            <li id="restaurant_wallet"></li>
                        </ul>
                    </div>
                <?php } ?>

                    <div class="card">

                        <div class="card-header">
                            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                <li class="nav-item restaurant_payout_tab">
                                    <a class="nav-link active" href="{!! url('payoutRequests/restaurants') !!}"><i
                                                class="fa fa-list mr-2"></i>{{trans('lang.restaurant_payout_request')}}
                                    </a>
                                </li>
                                @if($id == '')
                                <li class="nav-item driver_payout_tab">
                                    <a class="nav-link" href="{!! url('payoutRequests/drivers') !!}"><i
                                                class="fa fa-list mr-2"></i>{{trans('lang.drivers_payout_request')}}</a>
                                </li>
                                @endif
                            </ul>
                        </div>

                        <div class="card-body">

                            <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                 style="display: none;">{{trans('lang.processing')}}
                            </div>

                            <div class="error_top" style="display:none"></div>

                            <div class="success_top" style="display:none"></div>

                            <div class="table-responsive m-t-10">

                                <table id="payoutRequestTable"
                                       class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <?php if ($id == "") { ?>
                                            <th>{{ trans('lang.vendor')}}</th>
                                            <?php } ?>
                                            <th>{{trans('lang.paid_amount')}}</th>
                                            <th>{{trans('lang.restaurants_payout_note')}}</th>
                                            <th>{{trans('lang.date')}}</th>
                                            <th>{{trans('lang.status')}}</th>
                                            <th>{{trans('lang.withdraw_method')}}</th>
                                            <th>{{trans('lang.actions')}}</th>
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

    <div class="modal fade" id="bankdetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered location_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title locationModalTitle">{{trans('lang.bankdetails')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="">
                        <div class="form-row">
                            <input type="hidden" name="vendorId" id="vendorId">
                            <div class="form-group row">
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{
                                    trans('lang.bank_name')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="bank_name" class="form-control" id="bankName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{
                                    trans('lang.branch_name')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="branch_name" class="form-control" id="branchName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-4 control-label">{{
                                    trans('lang.holer_name')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="holer_name" class="form-control" id="holderName">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{
                                    trans('lang.account_number')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="account_number" class="form-control"
                                               id="accountNumber">
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{
                                    trans('lang.other_information')}}</label>
                                    <div class="col-12">
                                        <input type="text" name="other_information" class="form-control"
                                               id="otherDetails">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submit_accept">
                            {{trans('lang.accept')}}</a>
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                            {{trans('lang.close')}}</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelRequestModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title locationModalTitle">{{trans('lang.cancel_payout_request')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="data-table_processing_modal" class="dataTables_processing panel panel-default"
                            style="display: none;">{{trans('lang.processing')}}
                    </div>
                    <form class="">
                        <div class="form-row">
                            <div class="form-group row">
                                <div class="form-group row width-100">
                                    <label class="col-12 control-label">{{trans('lang.notes')}}</label>
                                    <div class="col-12">
                                        <textarea name="admin_note" class="form-control" id="admin_note" cols="5" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submit_cancel">
                            {{trans('lang.submit')}}</a>
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                            {{trans('lang.close')}}</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payoutResponseModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{trans('lang.payout_response')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="payout-response"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                        {{trans('lang.close')}}</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>

        var database = firebase.firestore();
        var offest = 1;
        var pagesize = 10;
        var end = null;
        var endarray = [];
        var start = null;
        var user_number = [];
        var vendorId = '{{$id}}';

        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        if (vendorId != "") {
            var refData = database.collection('payouts').where('vendorID', '==', vendorId);
            database.collection('vendors').where("id", "==", vendorId).get().then(async function(snapshots) {
                var vendorData = snapshots.docs[0].data();
                walletRoute = "{{route('users.walletstransaction',':id')}}";
                walletRoute = walletRoute.replace(":id", vendorData.author);
                $('#restaurant_wallet').append('<a href="' + walletRoute + '">{{trans("lang.wallet_transaction")}}</a>');
            });
            getStoreNameFunction(vendorId);
        } else {
            var refData = database.collection('payouts').where('paymentStatus', '==', 'Pending');
        }

        var email_templates = database.collection('email_templates').where('type', '==', 'payout_request_status');

        var emailTemplatesData = null;

        var ref = refData.orderBy('paidDate', 'desc');

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

        var append_list = '';

        $(document).ready(function () {

            email_templates.get().then(async function (snapshots) {
                emailTemplatesData = snapshots.docs[0].data();
            });

            $(document.body).on('click', '.redirecttopage', function () {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });

            var inx = parseInt(offest) * parseInt(pagesize);
            jQuery("#data-table_processing").show();

            append_list = document.getElementById('append_list1');
            append_list.innerHTML = '';

            ref.get().then(async function (snapshots) {
                var html = '';
                html = await buildHTML(snapshots);
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#data-table_paginate").hide();
                    }
                }

                if (vendorId == '') {
                    $('#payoutRequestTable').DataTable({
                        order: [],
                        columnDefs: [
                            {
                                targets: 3,
                                type: 'date',
                                render: function (data) {

                                    return data;
                                }
                            },
                            {orderable: false, targets: [5]},
                        ],
                        order: [['3', 'desc']],
                        "language": {
                            "zeroRecords": "{{trans("lang.no_record_found")}}",
                            "emptyTable": "{{trans("lang.no_record_found")}}"
                        },
                        responsive: true
                    });


                } else {
                    $('#payoutRequestTable').DataTable({
                        order: [],
                        columnDefs: [
                            {
                                targets: 2,
                                type: 'date',
                                render: function (data) {

                                    return data;
                                }
                            },
                            {orderable: false, targets: [4]},
                        ],
                        order: [['2', 'desc']],
                        "language": {
                            "zeroRecords": "{{trans("lang.no_record_found")}}",
                            "emptyTable": "{{trans("lang.no_record_found")}}"
                        },
                        responsive: true
                    });


                }

                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }

                jQuery("#data-table_processing").hide();
            });

        });

        async function getStoreNameFunction(vendorId) {
            await database.collection('vendors').where('id', '==', vendorId).get().then(async function (snapshots) {
                if (!snapshots.empty) {
                    var vendorData = snapshots.docs[0].data();
                    vendorName = vendorData.title;
                    $('.restaurantTitle').html('{{trans("lang.payout_request")}} - ' + vendorName);
                }
            });
        }

        async function buildHTML(snapshots) {
            var html = '';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var datas = listval.data();
                var getData = await getListData(datas);
                html += getData;

            }));
            return html;
        }

        async function getListData(val) {
            var html = '';
            var price_val = '';
            var price = val.amount;
            var payoutResponse = val.payoutResponse ? btoa(JSON.stringify(val.payoutResponse)) : '';

            if (intRegex.test(price) || floatRegex.test(price)) {
                price = parseFloat(price).toFixed(2);
            } else {
                price = 0;
            }

            if (currencyAtRight) {
                price_val = parseFloat(price).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                price_val = currentCurrency + "" + parseFloat(price).toFixed(decimal_degits);
            }

            html = html + '<tr>';

            <?php if($id == ''){ ?>
                const vendor = await payoutVendor(val.vendorID);
                var route = '{{route("restaurants.view",":id")}}';
                route = route.replace(':id', val.vendorID);
                html = html + '<td><a href="' + route + '" class="vendor_' + val.vendorID + ' redirecttopage">'+vendor+'</a></td>';
            <?php } ?> 

            html = html + '<td>' + price_val + '</td>';

            var date = val.paidDate.toDate().toDateString();
            var time = val.paidDate.toDate().toLocaleTimeString('en-US');
            if(val.note){
                html = html + '<td>' + val.note + '</td>';
            }else{
                html = html + '<td></td>';
            }

            html = html + '<td class="dt-time">' + date + ' ' + time + '</td>';

            if (val.paymentStatus == 'Pending' || val.paymentStatus == 'In Process') {
                html = html + '<td class="order_placed"><span>' + val.paymentStatus + '</span></td>';
            }else if (val.paymentStatus == 'Reject' || val.paymentStatus == 'Failed') {
                html = html + '<td class="order_rejected"><span>' + val.paymentStatus + '</span></td>';
            }else if (val.paymentStatus == 'Success') {
                html = html + '<td class="order_completed"><span>' + val.paymentStatus + '</span></td>';
            } else{
                html = html + '<td></td>';
            }

            if (val.withdrawMethod) {
                var selectedwithdrawMethod = (val.withdrawMethod == "bank") ? "Bank Transfer" : val.withdrawMethod;
                html = html + '<td style="text-transform:capitalize">' + selectedwithdrawMethod + '</td>';
            } else {
                html = html + '<td></td>';
            }

            html = html + '<td class="action-btn">';
                
                if (val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                    html = html + '<a id="' + val.id + '" name="vendor_view" data-auth="' + val.vendorID + '" data-amount = "' + price_val + '" href="javascript:void(0)" data-toggle="modal" data-target="#bankdetailsModal" class="btn btn-info mb-2">Manual Pay</a>';
                }

                if (val.withdrawMethod && val.withdrawMethod != "bank" && val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                    html = html + '<br>';
                    html = html + '<a id="' + val.id + '" name="vendor_pay"  data-auth="' + val.vendorID + '" data-amount="' + price + '" data-method="'+val.withdrawMethod+'" href="javascript:void(0)" class="btn btn-success mb-2">Pay Online</a>';
                }
                
                if (val.paymentStatus != "Reject" && val.paymentStatus != "Success") {
                    html = html + '<br>';
                    html = html + '<a id="' + val.id + '" name="vendor_reject_request" data-toggle="modal" data-target="#cancelRequestModal" data-auth="' + val.vendorID + '" data-amount = "' + price_val + '" data-price="' + price + '" href="javascript:void(0)" class="btn btn-primary mb-2">Cancel Request</a>';
                }
                
                if (val.paymentStatus == "In Process") {
                    html = html + '<br>';
                    html = html + '<a id="' + val.id + '" name="vendor_check_status" data-auth="' + val.vendorID + '" data-amount="' + price + '" data-method="'+val.withdrawMethod+'" href="javascript:void(0)" class="btn btn-dark mb-2">Check Payment Status</a>';
                }

            html = html + '</td>';
            html = html + '</tr>';

            return html;
        }


        async function getVendorBankDetails() {
            var vendorId = $('#vendorId').val();
            await database.collection('users').where("vendorID", "==", vendorId).get().then(async function (snapshotss) {
                if (snapshotss.docs[0]) {
                    var user_data = snapshotss.docs[0].data();
                    if (user_data.userBankDetails) {
                        $('#bankName').val(user_data.userBankDetails.bankName);
                        $('#branchName').val(user_data.userBankDetails.branchName);
                        $('#holderName').val(user_data.userBankDetails.holderName);
                        $('#accountNumber').val(user_data.userBankDetails.accountNumber);
                        $('#otherDetails').val(user_data.userBankDetails.otherDetails);
                    }
                }
            });
        }

        $(document).on("click", "a[name='vendor_view']", function (e) {
            $('#bankName').val("");
            $('#branchName').val("");
            $('#holderName').val("");
            $('#accountNumber').val("");
            $('#otherDetails').val("");
            var id = this.id;
            var auth = $(this).attr('data-auth');
            var amount = $(this).attr('data-amount');
            $('#vendorId').val(auth);
            getVendorBankDetails();
            $('#submit_accept').attr('data-id',id).attr('data-amount',amount).attr('data-auth',auth);
        });
        
        $(document).on("click", "a[name='vendor_pay']", async function (e) {
            $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
            var data = {};
            data['payoutId'] = this.id;
            data['method'] = $(this).data('method');
            data['amount'] = $(this).data('amount');
            data['user'] =  await getUserData($(this).data('auth'));
            data['settings'] = await getPaymentSettings();
            if(data['method'] != "undefined"){
                $.ajax({
                    type: 'POST',
                    data: {
                        data: btoa(JSON.stringify(data)),
                    },
                    url: "{{url('pay-to-user')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if(response.success == true){
                            $(".success_top").show().html("");
                            $(".success_top").append("<p>"+response.message+"</p>");    
                            window.scrollTo(0, 0);
                            database.collection('payouts').doc(data['payoutId']).update({'paymentStatus': response.status,'payoutResponse' : response.result}).then(async function (result) {
                                if (data['user'] && data['user'] != undefined){
                                    var emailData = await sendMailToRestaurant(data['user'], data['payoutId'], 'Approved', data['amount']);
                                    if(emailData){
                                        window.location.reload();        
                                    }
                                }
                            });
                        }else{
                            $(".error_top").show().html("");
                            $(".error_top").append("<p>"+response.message+"</p>");
                            window.scrollTo(0, 0);
                            setTimeout(function(){
                                window.location.reload();
                            },5000);
                        }
                    }
                });
            }
        });

        $(document).on("click", "a[name='vendor_check_status']", async function (e) {
            $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
            var data = {};
            data['payoutId'] = this.id;
            data['method'] = $(this).data('method');
            data['amount'] = $(this).data('amount');
            data['user'] =  await getUserData($(this).data('auth'));
            data['settings'] = await getPaymentSettings();
            data['payoutDetail'] = await getPayoutDetail(data['payoutId']);
            if(data['method'] != "undefined"){
                $.ajax({
                    type: 'POST',
                    data: {
                        data: btoa(JSON.stringify(data)),
                    },
                    url: "{{url('check-payout-status')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if(response.success == true){
                            $(".success_top").show().html("");
                            $(".success_top").append("<p>"+response.message+"</p>");    
                            window.scrollTo(0, 0);
                        }else{
                            $(".error_top").show().html("");
                            $(".error_top").append("<p>"+response.message+"</p>");    
                            window.scrollTo(0, 0);
                        }
                        $(this).prop('disabled',false).css({'cursor':'pointer','opacity':'1'});
                        
                        if(response.result && response.status){
                            database.collection('payouts').doc(data['payoutId']).update({'paymentStatus': response.status,'payoutResponse' : response.result});
                            $("#payoutResponseModal .payout-response").html(JSON.stringify(JSON.parse(JSON.stringify(response.result)),null,4));
                            $("#payoutResponseModal").modal('show');
                        }
                    }
                });
            }
        }); 

        async function sendMailToRestaurant(user, id, status, amount) {

            var formattedDate = new Date();
            var month = formattedDate.getMonth() + 1;
            var day = formattedDate.getDate();
            var year = formattedDate.getFullYear();

            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;

            formattedDate = day + '-' + month + '-' + year;

            var subject = emailTemplatesData.subject;

            subject = subject.replace(/{requestid}/g, id);
            emailTemplatesData.subject = subject;

            var message = emailTemplatesData.message;
            message = message.replace(/{username}/g, user.firstName + ' ' + user.lastName);
            message = message.replace(/{date}/g, formattedDate);
            message = message.replace(/{requestid}/g, id);
            message = message.replace(/{status}/g, status);
            message = message.replace(/{amount}/g, amount);
            message = message.replace(/{usercontactinfo}/g, user.phoneNumber);

            emailTemplatesData.message = message;

            var url = "{{url('send-email')}}";

            return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [user.email]);
        }

        async function getUserData(vendorId) {
            var data = '';
            await database.collection('users').where("vendorID", "==", vendorId).get().then(async function (snapshotss) {
                if (snapshotss.docs[0]) {
                    data = snapshotss.docs[0].data();
                }
            });
            if(data.id){
                await database.collection('withdraw_method').where("userId", "==", data.id).get().then(async function (snapshotss) {
                    if (snapshotss.docs.length) {
                        data['withdrawMethod'] = snapshotss.docs[0].data();
                    }
                });
            }
            return data;
        }

        async function getPaymentSettings() {
            var settings = {};
            await database.collection('settings').get().then(async function (snapshots) {
                snapshots.forEach((doc) => {
                    if(doc.id == "flutterWave"){
                        settings["flutterwave"] = doc.data();
                    }
                    if(doc.id == "paypalSettings"){
                        settings["paypal"] = doc.data();
                    }
                    if(doc.id == "razorpaySettings"){
                        settings["razorpay"] = doc.data();
                    }
                    if(doc.id == "stripeSettings"){
                        settings["stripe"] = doc.data();
                    }
                });
            });
            return settings;
        }

        async function getPayoutDetail(payoutId) {
            var snapshot = await database.collection('payouts').doc(payoutId).get();
            return snapshot.data();
        }

        $(document).on("click", "a[name='vendor_reject_request']", function (e) {
            $('#admin_note').val("");
            var id = this.id;
            var auth = $(this).attr('data-auth');
            var amount = $(this).attr('data-amount');
            var price = $(this).attr('data-price');
            $('#submit_cancel').attr('data-id',id).attr('data-amount',amount).attr('data-price',price).attr('data-auth',auth);
        });

        $(document).on("click", "#submit_cancel", async function (e) {
            $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
            var id = $(this).data('id');
            var auth = $(this).data('auth');
            var user = await getUserData(auth);
            var priceadd = $(this).data('price');
            var amount = $(this).data('amount');
            var admin_note = $("#admin_note").val();
            
            jQuery("#data-table_processing").show();
            database.collection('users').where("vendorID", "==", auth).get().then(function (resultvendor) {
                if (resultvendor.docs.length) {
                    var vendor = resultvendor.docs[0].data();
                    var wallet_amount = 0;
                    if (isNaN(vendor.wallet_amount) || vendor.wallet_amount == undefined) {
                        wallet_amount = 0;
                    } else {
                        wallet_amount = vendor.wallet_amount;
                    }
                    price = parseFloat(wallet_amount) + parseFloat(priceadd);
                    if (!isNaN(price)) {
                        database.collection('payouts').doc(id).update({'paymentStatus': 'Reject','adminNote':admin_note}).then(function (result) {
                            database.collection('users').doc(vendor.id).update({'wallet_amount': price}).then(async function (result) {
                                var wId = database.collection('temp').doc().id;
                                database.collection('wallet').doc(wId).set({
                                    'amount': parseFloat(priceadd),
                                    'date': firebase.firestore.FieldValue.serverTimestamp(),
                                    'id': wId,
                                    'isTopUp': false,
                                    'order_id': id,
                                    'payment_method': 'Wallet',
                                    'payment_status': 'Refund success',
                                    'transactionUser': 'vendor',
                                    'user_id': vendor.id,
                                    'note': 'Refund by admin'
                                });
                                if (user && user != undefined) {
                                    var emailData = await sendMailToRestaurant(user, id, 'Disapproved', amount);
                                    if (emailData) {
                                        window.location.reload();
                                    }
                                } else {
                                    window.location.reload();
                                }
                            });
                        });
                    }
                } else {
                    alert('Vendor not found.');
                }
            });
        });

        $(document).on("click", "#submit_accept", async function (e) {
            $(this).prop('disabled',true).css({'cursor':'default','opacity':'0.5'});
            var id = $(this).data('id');
            var auth = $(this).data('auth');
            var user = await getUserData(auth);
            var amount = $(this).data('amount');
            jQuery("#data-table_processing").show();
            database.collection('payouts').doc(id).update({'paymentStatus': 'Success'}).then(async function (result) {
                if (user && user != undefined) {
                    var emailData = await sendMailToRestaurant(user, id, 'Approved', amount);
                    if (emailData) {
                        window.location.reload();
                    }
                } else {
                    window.location.reload();
                }
            });
        });

        function prev() {
            if (endarray.length == 1) {
                return false;
            }
            end = endarray[endarray.length - 2];

            if (end != undefined || end != null) {
                jQuery("#data-table_processing").show();


                if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {
                    listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();

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
                if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {

                    listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();

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

        function searchclear() {
            jQuery("#search").val('');
            searchtext();
        }

        function searchtext() {

            jQuery("#data-table_processing").show();

            append_list.innerHTML = '';

            if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {

                wherequery = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').get();

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

        async function payoutVendor(vendor) {
            var payoutVendor = '';
            var route = '{{route("restaurants.view",":id")}}';
            route = route.replace(':id', vendor);
            await database.collection('vendors').where("id", "==", vendor).get().then(async function (snapshotss) {

                if (snapshotss.docs[0]) {
                    var vendor_data = snapshotss.docs[0].data();
                    payoutVendor = vendor_data.title;
                    
                } else {
                    
                }
                return payoutVendor;
            });
            return payoutVendor;
        }

    </script>

@endsection
