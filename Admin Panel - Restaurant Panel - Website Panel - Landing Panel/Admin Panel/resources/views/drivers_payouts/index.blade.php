@extends('layouts.app')

@section('content')
<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor driverName">{{trans('lang.drivers_payout_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.drivers_payout_plural')}}</li>
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
                        <li >
                            <a href="{{route('drivers.view',$id)}}">{{trans('lang.tab_basic')}}</a>
                        </li>
                        <li>
                            <a href="{{route('orders')}}?driverId={{$id}}">{{trans('lang.tab_orders')}}</a>
                        </li>
                        <li class="active">
                            <a href="{{route('driver.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                        </li>
                        <li>
                            <a href="{{route('users.walletstransaction',$id)}}">{{trans('lang.wallet_transaction')}}</a>
                        </li>

                    </ul>

                </div>
                <?php } ?>
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                            class="fa fa-list mr-2"></i>{{trans('lang.drivers_payout_table')}}</a>
                            </li>

                            <?php if ($id != '') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('driver.payout.create',$id) !!}/"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.drivers_payout_create')}}</a>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('driversPayouts.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.drivers_payout_create')}}</a>
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">{{trans('lang.processing')}}
                        </div>

                        <div class="table-responsive m-t-10">


                            <table id="driverPayoutTable"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                    <th>{{ trans('lang.driver')}}</th>
                                    <th>{{trans('lang.paid_amount')}}</th>
                                    <th>{{trans('lang.drivers_payout_paid_date')}}</th>
                                    <th>{{trans('lang.drivers_payout_note')}}</th>
                                    <th>Admin {{trans('lang.drivers_payout_note')}}</th>
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

<script>
    var driver_id = "<?php echo $id; ?>";
    var database = firebase.firestore();
    var offset = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var user_number = [];
    if (driver_id) {
        $('.menu-tab').show();
        getDriverName(driver_id);
        var refData = database.collection('driver_payouts').where('driverID', '==', driver_id).where('paymentStatus', '==', 'Success');
    } else {
        var refData = database.collection('driver_payouts').where('paymentStatus', '==', 'Success');
    }
    var ref = refData.orderBy('paidDate', 'desc');
    var append_list = '';

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

    $(document).ready(function () {
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        jQuery("#data-table_processing").show();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {
            var html = await buildHTML(snapshots);
            if (html != '') {
                append_list.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }
            }

            await populateDriverNames(snapshots);

            $('#driverPayoutTable').DataTable({
                order: [],
                columnDefs: [
                    { targets: 0, orderable: true }, 
                    {
                        targets: 2,
                        type: 'date',
                        render: function(data) {
                            return data;
                        }
                    }
                ],
                order: [0, 'asc'],
                language: {
                    zeroRecords: "{{trans('lang.no_record_found')}}",
                    emptyTable: "{{trans('lang.no_record_found')}}"
                },
                responsive: true,
            });

            jQuery("#data-table_processing").hide();
        });
    });

    async function getDriverName(driver_id) {
        var usersnapshots = await database.collection('users').doc(driver_id).get();
        var driverData = usersnapshots.data();
        if (driverData) {
            var driverName = driverData.firstName + ' ' + driverData.lastName;
            $('.driverName').html('{{trans('lang.drivers_payout_plural')}} - ' + driverName);
        }
    }

    async function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        alldata.forEach((listval) => {
            var val = listval;
            var route1 = '{{route("drivers.view", ":id")}}';
            route1 = route1.replace(':id', val.driverID);
            html += '<tr class="payout-id-'+val.id+'">';

            html += '<td><a class="driver_' + val.driverID + ' redirecttopage" href="' + route1 + '"></a></td>';

            if (currencyAtRight) {
                html += '<td class="text-red">' + parseFloat(val.amount).toFixed(decimal_digits) + ' ' + currentCurrency + '</td>';
            } else {
                html += '<td class="text-red">' + currentCurrency + ' ' + parseFloat(val.amount).toFixed(decimal_digits) + '</td>';
            }

            var date = val.paidDate.toDate().toDateString();
            var time = val.paidDate.toDate().toLocaleTimeString('en-US');
            html += '<td>' + date + ' ' + time + '</td>';
            
            if (val.note != undefined && val.note != '') {
                html = html + '<td>' + val.note + '</td>';
            } else {
                html = html + '<td></td>';
            }

            if (val.adminNote != undefined && val.adminNote != '') {
                html = html + '<td>' + val.adminNote + '</td>';
            } else {
                html = html + '<td></td>';
            }

            html += '</tr>';
        });
        return html;
    }

    function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];

        if (end != undefined || end != null) {

            jQuery("#data-table_processing").show();


            if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {
                listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAt(end).get();

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
            } else if (jQuery("#selected_search").val() == 'driver' && jQuery("#search").val().trim() != '') {
                title = jQuery("#search").val();

                database.collection('users').where('firstName', '==', title).get().then(async function (snapshots) {

                    if (snapshots.docs.length > 0) {
                        var driverdata = snapshots.docs[0].data();

                        listener = refData.orderBy('driverID').limit(pagesize).startAt(driverdata.id).endAt(driverdata.id + '\uf8ff').startAt(end).get();

                        listener.then((snapshotsInner) => {
                            html = '';
                            html = buildHTML(snapshotsInner);
                            jQuery("#data-table_processing").hide();
                            if (html != '') {
                                append_list.innerHTML = html;
                                start = snapshotsInner.docs[snapshotsInner.docs.length - 1];

                                endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                                if (snapshotsInner.docs.length < pagesize) {

                                    jQuery("#users_table_previous_btn").hide();
                                }

                            }
                        });
                    }
                });
            } else {
                listener = ref.startAt(end).limit(pagesize).get();

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
    }

    function next() {
        if (start != undefined || start != null) {

            jQuery("#data-table_processing").hide();
            if (jQuery("#selected_search").val() == 'note' && jQuery("#search").val().trim() != '') {

                listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val() + '\uf8ff').startAfter(start).get();

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
            } else if (jQuery("#selected_search").val() == 'driver' && jQuery("#search").val().trim() != '') {
                title = jQuery("#search").val();

                database.collection('users').where('firstName', '==', title).get().then(async function (snapshots) {

                    if (snapshots.docs.length > 0) {
                        var driverdata = snapshots.docs[0].data();

                        listener = refData.orderBy('driverID').limit(pagesize).startAt(driverdata.id).endAt(driverdata.id + '\uf8ff').startAt(end).get();

                        listener.then((snapshotsInner) => {
                            html = '';
                            html = buildHTML(snapshotsInner);
                            jQuery("#data-table_processing").hide();
                            if (html != '') {
                                append_list.innerHTML = html;
                                start = snapshotsInner.docs[snapshotsInner.docs.length - 1];

                                endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);

                                if (snapshotsInner.docs.length < pagesize) {

                                    jQuery("#users_table_previous_btn").hide();
                                }

                            }
                        });
                    }
                });
            } else {
                listener = ref.startAfter(start).limit(pagesize).get();

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
    }

    function searchclear() {
        jQuery("#search").val('');
        searchtext();
    }


  
    async function populateDriverNames(snapshots) {
        var driverIDs = [];
        snapshots.docs.forEach((doc) => {
            var data = doc.data();
            driverIDs.push(data.driverID);
        });

        await Promise.all(driverIDs.map(async (driverID) => {
            await payoutDriverfunction(driverID);
        }));
    }

    async function payoutDriverfunction(driverID) {
        var payoutDriver = '';
        var routedriver = '{{route("drivers.view", ":id")}}';
        routedriver = routedriver.replace(':id', driverID);
        await database.collection('users').where("id", "==", driverID).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var driver_data = snapshotss.docs[0].data();
                payoutDriver = driver_data.firstName + " " + driver_data.lastName;
                jQuery(".driver_" + driverID).attr("data-url", routedriver).html(payoutDriver);
            } else {
                jQuery(".driver_" + driverID).attr("data-url", routedriver).html('');
            }
        });
        return payoutDriver;
    }
</script>



@endsection
