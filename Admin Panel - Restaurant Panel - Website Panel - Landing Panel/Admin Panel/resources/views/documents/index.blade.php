@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">

            <h3 class="text-themecolor restaurantTitle">{{trans('lang.document_plural')}}</h3>

        </div>

        <div class="col-md-7 align-self-center">

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>

                <li class="breadcrumb-item active">{{trans('lang.document_table')}}</li>

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
                                        class="fa fa-list mr-2"></i>{{trans('lang.document_table')}}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{!! route('documents.create') !!}"><i
                                        class="fa fa-plus mr-2"></i>{{trans('lang.document_create')}}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">

                        <div id="data-table_processing" class="dataTables_processing panel panel-default"
                            style="display: none;">Processing...
                        </div>

                        <div class="table-responsive m-t-10">

                            <table id="documentTable"
                                class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                cellspacing="0" width="100%">

                                <thead>

                                    <tr>
                                        <?php if (in_array('documents.delete', json_decode(@session('user_permissions'), true))) { ?>
                                            <th class="delete-all"><input type="checkbox" id="is_active"><label
                                                    class="col-3 control-label" for="is_active"><a id="deleteAll"
                                                        class="do_not_delete" href="javascript:void(0)"><i
                                                            class="fa fa-trash"></i> {{trans('lang.all')}}</a></label>
                                            <?php } ?>
                                        </th>
                                        <th>{{trans('lang.title')}}</th>

                                        <th>{{trans('lang.document_for')}}</th>

                                        <th>{{trans('lang.coupon_enabled')}}</th>

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

</div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;

    var ref = database.collection('documents');

    var append_list = '';
    var alldriver = database.collection('users').where('role', '==', 'driver');
    var allvendor = database.collection('users').where('role', '==', 'vendor');

    var user_permissions = '<?php echo @session("user_permissions") ?>';
    user_permissions = Object.values(JSON.parse(user_permissions));
    var checkDeletePermission = false;
    if ($.inArray('documents.delete', user_permissions) >= 0) {
        checkDeletePermission = true;
    }

    $(document).ready(function () {

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });

        jQuery("#data-table_processing").show();

        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {
            var html = '';

            html = await buildHTML(snapshots);
            if (html != '') {
                append_list.innerHTML = html;
                if (snapshots.docs.length < pagesize) {
                    jQuery("#data-table_paginate").hide();
                }

            }

            if (checkDeletePermission) {
                $('#documentTable').DataTable({
                    columnDefs: [
                        { targets: [0, 3, 4], orderable: false }
                    ],
                    order: [],
                    language: {
                        zeroRecords: "{{trans("lang.no_record_found")}}",
                        emptyTable: "{{trans("lang.no_record_found")}}"
                    },
                    responsive: true
                });
            }
            else {
                $('#documentTable').DataTable({
                    columnDefs: [
                        { targets: [2, 3], orderable: false }
                    ],
                    order: [],
                    language: {
                        zeroRecords: "{{trans("lang.no_record_found")}}",
                        emptyTable: "{{trans("lang.no_record_found")}}"
                    },
                    responsive: true
                });
            }

            jQuery("#data-table_processing").hide();

        });

    });

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
        html = html + '<tr>';
        newdate = '';
        var id = val.id;

        var route1 = '{{route("documents.edit", ":id")}}';
        route1 = route1.replace(':id', id);

        if (checkDeletePermission) {
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '" dataUser="' + val.type + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';
        }

        html = html + '<td><a href="' + route1 + '"  class="redirecttopage">' + val.title + '</a></td>';
        html = html + '<td>' + val.type + '</td>';

        if (val.enable) {
            html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isEnabled" dataUser="' + val.type + '"><span class="slider round"></span></label></td>';
        } else {
            html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isEnabled" dataUser="' + val.type + '"><span class="slider round"></span></label></td>';
        }

        html = html + '<td class="action-btn"><a href="' + route1 + '"><i class="fa fa-edit"></i></a>';
        if (checkDeletePermission) {
            html = html + '<a id="' + val.id + '" name="document_delete" dataUser="' + val.type + '" class="do_not_delete" href="javascript:void(0)"><i class="fa fa-trash"></i></a></td>';
        }
        html = html + '</tr>';
        return html;

    }


    $(document).on("click", "input[name='isEnabled']", function (e) {
        var ischeck = $(this).is(':checked');
        var id = this.id;
        var dataUser = $(this).attr('dataUser');
        var checkedVal = ischeck ? true : false;

        database.collection('documents').where('type', '==', dataUser).where('enable', '==', true).get().then(async function (snapshot) {
            if (snapshot.docs.length == 1 && checkedVal == false) {
                jQuery("#data-table_processing").hide();
                alert('{{trans("lang.atleast_one_document_should_enable")}}');
                window.location.reload();

            } else {
                database.collection('documents').doc(id).update({ 'enable': ischeck ? true : false }).then(async function (result) {
                    jQuery("#data-table_processing").show();

                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        await alldriver.get().then(async function (snapshotsdriver) {

                            if (snapshotsdriver.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsdriver, "driver");
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                }
                            }
                        })
                    } else {
                        var enableDocIds = await getDocId('restaurant');
                        await allvendor.get().then(async function (snapshotsvendor) {

                            if (snapshotsvendor.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsvendor, "restaurant");
                                if (verification) {
                                    jQuery("#data-table_processing").hide();
                                }
                            }

                        })
                    }

                });


            }
        })

    });

    $("#is_active").click(function () {

        $("#documentTable .is_open").prop('checked', $(this).prop('checked'));
    });


    $("#deleteAll").click(async function () {
        if ($('#documentTable .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();

                // Get all selected documents to be deleted
                const selectedDocs = $('#documentTable .is_open:checked').map(function () {
                    return {
                        dataId: $(this).attr('dataId'),
                        dataUser: $(this).attr('dataUser')
                    };
                }).get();

                for (let doc of selectedDocs) {
                    var dataId = doc.dataId;
                    var dataUser = doc.dataUser;

                    let snapshots = await database.collection('documents').where('type', '==', dataUser).get();
                    if (snapshots.docs.length == 1) {
                        console.log('here');
                        jQuery("#data-table_processing").hide();
                        alert('{{trans("lang.atleast_one_document_should_be_there_for")}} ' + dataUser);
                        return false;  // Stop further processing
                    }

                    await database.collection('documents').doc(dataId).delete();

                    let verifySnapshots = await database.collection('documents_verify').get();
                    for (let listval of verifySnapshots.docs) {
                        var data = listval.data();
                        var newDocArr = data.documents.filter(item => item.documentId !== dataId);
                        await database.collection('documents_verify').doc(data.id).update({ 'documents': newDocArr });
                    }

                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        let driverSnapshots = await database.collection('users').where('role', '==', 'driver').where('isDocumentVerify', '==', false).get();
                        if (driverSnapshots.docs.length > 0) {
                            var verification = await userDocVerification(enableDocIds, driverSnapshots, "driver");
                            if (verification) {
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    } else {
                        var enableDocIds = await getDocId('restaurant');
                        let vendorSnapshots = await database.collection('users').where('role', '==', 'vendor').where('isDocumentVerify', '==', false).get();
                        if (vendorSnapshots.docs.length > 0) {
                            var verification = await userDocVerification(enableDocIds, vendorSnapshots, "restaurant");
                            if (verification) {
                                window.location.reload();
                            }
                        } else {
                            window.location.reload();
                        }
                    }
                }

                jQuery("#data-table_processing").hide();
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }

    });

    $(document).on("click", "a[name='document_delete']", async function (e) {

        var id = this.id;
        var dataUser = $(this).attr('dataUser');
        await database.collection('documents').where('type', '==', dataUser).get().then(async function (snapshots) {
            if (snapshots.docs.length == 1) {
                jQuery("#data-table_processing").hide();
                alert('{{trans("lang.atleast_one_document_should_be_there_for")}} ' + dataUser);
                return false;
            } else {
                database.collection('documents').doc(id).delete().then(async function () {
                    jQuery("#data-table_processing").show();

                    await database.collection('documents_verify').get().then(async function (snapshots) {
                        snapshots.docs.forEach(async listval => {
                            var data = listval.data();
                            var newDocArr = data.documents.filter(item => item.documentId !== id);
                            await database.collection('documents_verify').doc(data.id).update({ 'documents': newDocArr });
                        })
                    })

                    if (dataUser == 'driver') {
                        var enableDocIds = await getDocId('driver');
                        await database.collection('users').where('role', '==', 'driver').where('isDocumentVerify', '==', false).get().then(async function (snapshotsdriver) {

                            if (snapshotsdriver.docs.length > 0) {
                                var verification = await userDocVerification(enableDocIds, snapshotsdriver, "driver");
                                if (verification) {
                                    window.location.reload();
                                }
                            } else {

                                window.location.reload();
                            }
                        })
                    }
                    else {
                        var enableDocIds = await getDocId('restaurant');
                        await database.collection('users').where('role', '==', 'vendor').where('isDocumentVerify', '==', false).get().then(async function (snapshotsvendor) {

                            if (snapshotsvendor.docs.length > 0) {

                                var verification = await userDocVerification(enableDocIds, snapshotsvendor, "restaurant");
                                if (verification) {
                                    window.location = "{{!url()->current() }}";

                                }
                            } else {
                                window.location = "{{!url()->current() }}";

                            }
                        })
                    }

                });
            }
        });

    });
    async function getDocId(type) {
        var enableDocIds = [];
        await database.collection('documents').where('type', '==', type).where('enable', "==", true).get().then(async function (snapshots) {
            await snapshots.forEach((doc) => {
                enableDocIds.push(doc.data().id);
            });
        });
        return enableDocIds;
    }

    async function userDocVerification(enableDocIds, snapshots, documentFor) {
        var isCompleted = false;
        await Promise.all(snapshots.docs.map(async (driver) => {
            await database.collection('documents_verify').doc(driver.id).get().then(async function (docrefSnapshot) {
                if (docrefSnapshot.data() && docrefSnapshot.data().documents.length > 0) {
                    var driverDocId = await docrefSnapshot.data().documents.filter((doc) => doc.status == 'approved').map((docData) => docData.documentId);
                    if (driverDocId.length >= enableDocIds.length) {
                        if (documentFor == 'driver') {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true, isActive: true });
                        } else {
                            await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': true });
                        }
                    } else {
                        await enableDocIds.forEach(async (docId) => {
                            if (!driverDocId.includes(docId)) {
                                if (documentFor == 'driver') {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false, isActive: false });

                                } else {
                                    await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false });

                                }
                            }
                        });
                    }
                } else {
                    if (documentFor == 'driver') {
                        await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false, isActive: false });

                    } else {
                        await database.collection('users').doc(driver.id).update({ 'isDocumentVerify': false });

                    }
                }
            });
            isCompleted = true;
        }));
        return isCompleted;
    }

</script>

@endsection