@extends('layouts.app')


@section('content')
<div class="page-wrapper">


    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor restaurantTitle">{{trans('lang.restaurants_payout_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.restaurants_payout_plural')}}</li>
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
                            <li class="active">
                                <a href="{{route('restaurants.payout',$id)}}">{{trans('lang.tab_payouts')}}</a>
                            </li>
                            <li>
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
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i
                                            class="fa fa-list mr-2"></i>{{trans('lang.restaurants_payout_table')}}</a>
                            </li>

                            <?php if ($id != '') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('restaurantsPayouts.create') !!}/{{$id}}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.restaurants_payout_create')}}</a>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{!! route('restaurantsPayouts.create') !!}"><i
                                                class="fa fa-plus mr-2"></i>{{trans('lang.restaurants_payout_create')}}</a>
                                </li>
                            <?php } ?>


                        </ul>
                    </div>
                    <div class="card-body">
                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                             style="display: none;">{{trans('lang.processing')}}
                        </div>

                        <div class="table-responsive m-t-10">


                            <table id="restaurantPayoutTable"
                                   class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                   cellspacing="0" width="100%">

                                <thead>

                                <tr>
                                    <?php if ($id == '') { ?>
                                        <th>{{ trans('lang.restaurant')}}</th>
                                    <?php } ?>
                                    <th>{{trans('lang.paid_amount')}}</th>
                                    <th>{{trans('lang.date')}}</th>
                                    <th>{{trans('lang.restaurants_payout_note')}}</th>
                                    <th>Admin {{trans('lang.restaurants_payout_note')}}</th>
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

    var intRegex = /^\d+$/;
    var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

    var getId = '{{$id}}';
    <?php if($id != ''){ ?>
    database.collection('vendors').where("id", "==", '<?php echo $id; ?>').get().then(async function(snapshots) {
        var vendorData = snapshots.docs[0].data();
        walletRoute = "{{route('users.walletstransaction',':id')}}";
        walletRoute = walletRoute.replace(":id", vendorData.author);
        $('#restaurant_wallet').append('<a href="' + walletRoute + '">{{trans("lang.wallet_transaction")}}</a>');
    });
    var refData = database.collection('payouts').where('vendorID', '==', '<?php echo $id; ?>').where('paymentStatus', '==', 'Success');
    var ref = refData.orderBy('paidDate', 'desc');

    const getStoreName = getStoreNameFunction('<?php echo $id; ?>');

    <?php }else{ ?>
    var refData = database.collection('payouts').where('paymentStatus', '==', 'Success');
    var ref = refData.orderBy('paidDate', 'desc');
    <?php } ?>

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

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        var inx = parseInt(offest) * parseInt(pagesize);
        jQuery("#data-table_processing").show();

        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {
            html = '';

            html = await buildHTML(snapshots);
            if (html != '') {
                append_list.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }

            }

            if (getId != '') {
                $('#restaurantPayoutTable').DataTable({
                    columnDefs: [
                        {
                            targets: 0,
                            type: 'num-fmt',
                            render: function (data, type, row, meta) {
                                if (type === 'display') {
                                    return data;
                                }
                                return parseFloat(data.replace(/[^0-9.-]+/g, ""));
                            }
                        },
                        {
                            targets: 1,
                            type: 'date',
                            render: function (data) {

                                return data;
                            }
                        },

                    ],
                    order: [['1', 'desc']],
                    "language": {
                        "zeroRecords": "{{trans("lang.no_record_found")}}",
                        "emptyTable": "{{trans("lang.no_record_found")}}"
                    },
                    responsive: true
                });
            } else {
                $('#restaurantPayoutTable').DataTable({
                    columnDefs: [
                        {
                            targets: 1,
                            type: 'num-fmt',
                            render: function (data, type, row, meta) {
                                if (type === 'display') {
                                    return data;
                                }
                                return parseFloat(data.replace(/[^0-9.-]+/g, ""));
                            }
                        },
                        {
                            targets: 2,
                            type: 'date',
                            render: function (data) {
                                return data;
                            }
                        },
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
    async function payoutRestaurant(restaurant) {
        var payoutRestaurant = '';  
        await database.collection('vendors').where("id", "==", restaurant).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var restaurant_data = snapshotss.docs[0].data();
                payoutRestaurant = restaurant_data.title;
            } 
        });
        return payoutRestaurant;
    }

    async function getStoreNameFunction(vendorId) {
        var vendorName = '';
        await database.collection('vendors').where('id', '==', vendorId).get().then(async function (snapshots) {
            if(!snapshots.empty){
            var vendorData = snapshots.docs[0].data();

            vendorName = vendorData.title;
            $('.restaurantTitle').html('{{trans("lang.restaurants_payout_plural")}} - ' + vendorName);

            if (vendorData.dine_in_active == true) {
                $(".dine_in_future").show();
            }
        }
        });

        return vendorName;

    }

      async function buildHTML(snapshots) {
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
     
            var price_val = '';
            var price = val.amount;

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
             var route = '{{route("restaurants.view",":id")}}';
            route = route.replace(':id', val.vendorID);   
            const restaurant = await payoutRestaurant(val.vendorID);
            html = html + '<td><a href="'+route+'" class="redirecttopage" >'+restaurant+'</a></td>';
            <?php } ?>
            html = html + '<td class="text-red">(' + price_val + ')</td>';
            var date = val.paidDate.toDate().toDateString();
            var time = val.paidDate.toDate().toLocaleTimeString('en-US');
            html = html + '<td class="dt-time">' + date + ' ' + time + '</td>';

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

            html = html + '</tr>';
      
        return html;
    }

 

</script>

@endsection
