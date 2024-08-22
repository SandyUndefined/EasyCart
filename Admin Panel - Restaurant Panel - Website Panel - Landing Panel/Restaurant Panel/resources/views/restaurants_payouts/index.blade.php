@extends('layouts.app')

@section('content')
        <div class="page-wrapper">


            <div class="row page-titles">

                <div class="col-md-5 align-self-center">

                    <h3 class="text-themecolor">{{trans('lang.restaurants_payout_plural')}}</h3>

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
                       
                        <div class="card">
                            
                             <div class="card-header">
                                    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                                        <li class="nav-item active">
                                            <a class="nav-link active" href="{!! url()->current() !!}"><i
                                                        class="fa fa-list mr-2"></i>{{trans('lang.vendors_payout_table')}}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" href="{!! route('payments.create') !!}"><i
                                                        class="fa fa-plus mr-2"></i>{{trans('lang.vendors_payout_create')}}</a>

                                        </li>

                                    </ul>
                                </div>

                            <div class="card-body">
                                <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{trans('lang.processing')}}</div>

                            {{--<div id="users-table_filter" class="pull-right"><label>{{trans('lang.search_by')}}
                                <select name="selected_search" id="selected_search" class="form-control input-sm">
                                      <option value="note">{{ trans('lang.restaurants_payout_note')}}</option>
                                </select>
                                <div class="form-group">
                                <input type="search" id="search" class="search form-control" placeholder="Search" ></label>&nbsp;<button onclick="searchtext();" class="btn btn-warning btn-flat">{{trans('lang.search')}}</button>&nbsp;<button onclick="searchclear();" class="btn btn-warning btn-flat">{{trans('lang.clear')}}</button>
                            </div> 
                            </div>--}}
 


                                <div class="table-responsive m-t-10">


                                    <table id="example24" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">

                                        <thead>

                                            <tr>
                                                <th>{{trans('lang.paid_amount')}}</th>
                                                <th>{{trans('lang.date')}}</th>
                                                <th>{{trans('lang.restaurants_payout_note')}}</th>
                                                <th>{{trans('lang.status')}}</th>
                                                <th>{{trans('lang.withdraw_method')}}</th>
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
    var offest=1;
    var pagesize=10; 
    var end = null;
    var endarray=[];
    var start = null;
    var user_number = [];
    var vendorUserId = "<?php echo $id; ?>";
    var currentCurrency ='';
    var currencyAtRight = false;
    var decimal_degits = 0;

    var refCurrency = database.collection('currencies').where('isActive', '==' , true);
    refCurrency.get().then( async function(snapshots){
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var append_list = '';
    var ref = '';
    var refData = ''
    getVendorId(vendorUserId).then(data => {
        vendorId= data;

        refData = database.collection('payouts').where('vendorID','==',vendorId);
        ref = refData.orderBy('paidDate', 'desc');

        $(document).ready(function() {

            $(document.body).on('click', '.redirecttopage' ,function(){    
                var url=$(this).attr('data-url');
                window.location.href = url;
            });

            var inx= parseInt(offest) * parseInt(pagesize);
            jQuery("#data-table_processing").show();
          
            append_list = document.getElementById('append_list1');
            append_list.innerHTML='';
            ref.get().then( async function(snapshots){  
            html='';
            
            html=await buildHTML(snapshots);
            
            if(html!=''){
                append_list.innerHTML=html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);

             }
             jQuery("#data-table_processing").hide();

            if(snapshots.docs.length < pagesize){ 
   
                jQuery("#data-table_paginate").hide();
            }else{

                jQuery("#data-table_paginate").show();
            }
            $('#example24').DataTable({
                    order: [],
                    columnDefs: [
                        {
                            targets: 1,
                            type: 'date',
                            render: function (data) {

                                return data;
                            }
                        },
                        {orderable: false, targets: [3]},
                    ],
                    order: [['1', 'desc']],
                    "language": {
                        "zeroRecords": "{{trans("lang.no_record_found")}}",
                        "emptyTable": "{{trans("lang.no_record_found")}}"
                    },
                    responsive: true
                });

          }); 
         
        });
    })


  async function buildHTML(snapshots){
        var html='';
            await Promise.all(snapshots.docs.map(async (listval) => {
            var datas=listval.data();
            var getData = await getListData(datas);
             html += getData;

        }));
        return html;
    }
 async function getListData(val) {
                html='';
                html=html+'<tr >';
            if (currencyAtRight) {
                price_val = parseFloat(val.amount).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                price_val = currentCurrency + "" + parseFloat(val.amount).toFixed(decimal_degits);
            }

            html = html+'<td class="text-danger">('+price_val+')</td>';
            var date =  val.paidDate.toDate().toDateString();
            var time = val.paidDate.toDate().toLocaleTimeString('en-US');
            html = html+'<td>'+date+' '+time+'</td>';

            if(val.note){
            html = html+'<td>'+val.note+'</td>';

            }else{
             html = html+'<td></td>';

            }

            if(val.paymentStatus == "Reject" || val.paymentStatus == "Failed"){
                html = html + '<td><span class="badge badge-danger py-2 px-3">'+val.paymentStatus+'</sapn></td>';
            }else if(val.paymentStatus == "Pending" || val.paymentStatus == "In Process"){
                html = html + '<td><span class="badge badge-warning py-2 px-3">'+val.paymentStatus+'</sapn></td>';
            }else if(val.paymentStatus == "Success"){
                html = html + '<td><span class="badge badge-success py-2 px-3">'+val.paymentStatus+'</sapn></td>';
            } else {
                html = html + '<td></td>';
            }

            if (val.withdrawMethod) {
                var selectedwithdrawMethod =  val.withdrawMethod == "bank" ? "Bank Transfer" : val.withdrawMethod;
                html = html + '<td style="text-transform:capitalize">' + selectedwithdrawMethod + '</td>';
            } else {
                html = html + '<td></td>';
            }

            html=html+'</tr>';
            return html;      

 }                

function prev(){
    if(endarray.length==1){
        return false;
    }
    end=endarray[endarray.length-2];
  
  if(end!=undefined || end!=null){
            jQuery("#data-table_processing").show();
                 

        if(jQuery("#selected_search").val()=='note' && jQuery("#search").val().trim()!=''){
          listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').startAt(end).get();

        }else{
                    listener = ref.startAt(end).limit(pagesize).get();
                }
                
                listener.then((snapshots) => {
                html='';
                html=buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if(html!=''){
                    append_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length-1]),1);

                    if(snapshots.docs.length < pagesize){ 
   
                        jQuery("#users_table_previous_btn").hide();
                    }
                    
                }
            });
  }
}

function next(){
  if(start!=undefined || start!=null){

        jQuery("#data-table_processing").hide();
            if(jQuery("#selected_search").val()=='note' && jQuery("#search").val().trim()!=''){

        listener = refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').startAfter(start).get();

        } else{
                listener = ref.startAfter(start).limit(pagesize).get();
            }
          listener.then((snapshots) => {
            
                html='';
                html=buildHTML(snapshots);
                
                jQuery("#data-table_processing").hide();
                if(html!=''){
                    append_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];


                    if(endarray.indexOf(snapshots.docs[0])!=-1){
                        endarray.splice(endarray.indexOf(snapshots.docs[0]),1);
                    }
                    endarray.push(snapshots.docs[0]);
                }
            });
    }
}

function searchclear(){
    jQuery("#search").val('');
    searchtext();
}


function searchtext(){

  jQuery("#data-table_processing").show();
  
  append_list.innerHTML='';  

  if(jQuery("#selected_search").val()=='note' && jQuery("#search").val().trim()!=''){

     wherequery=refData.orderBy('note').limit(pagesize).startAt(jQuery("#search").val()).endAt(jQuery("#search").val()+'\uf8ff').get();

   }else{

    wherequery=ref.limit(pagesize).get();
   }
  
  wherequery.then((snapshots) => {
    html='';
    html=buildHTML(snapshots);
    jQuery("#data-table_processing").hide();
    if(html!=''){
        append_list.innerHTML=html;
        start = snapshots.docs[snapshots.docs.length - 1];
        endarray.push(snapshots.docs[0]);
        if(snapshots.docs.length < pagesize){ 
   
            jQuery("#data-table_paginate").hide();
        }else{

            jQuery("#data-table_paginate").show();
        }
    }
}); 

}

async function getVendorId(vendorUser){
    var vendorId = '';
    var ref;
    await database.collection('vendors').where('author',"==",vendorUser).get().then(async function(vendorSnapshots){
        var vendorData = vendorSnapshots.docs[0].data();    
        vendorId = vendorData.id;
    })
    
            return vendorId;
}



</script>



@endsection
