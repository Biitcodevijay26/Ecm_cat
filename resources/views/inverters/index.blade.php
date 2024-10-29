@extends('front.layout_admin.app')

@section('page_level_css')
<style>
    .dataTables_paginate  {
        float: right;
    }
    .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
    .table td, .table th {
        padding: .75rem 0.75rem;
    }
    .redRaw {
        background-color: #ffb3b3 !important;
    }
    .spn-deleted {
        color: red;
    }
    .spn-deleted-at  {
        color: black;
    }
    .delParentSpn {
        display: grid;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')

<div id="main-content">
    <div class="container-fluid">
    	<div class="block-header">
           <div class="row">
               <div class="col-lg-5 col-md-8 col-sm-12">
                   <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i
                               class="fa fa-arrow-left"></i></a> Users</h2>
                   <ul class="breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                       <li class="breadcrumb-item active">Users</li>
                   </ul>
               </div>
               <div class="col-lg-7 col-md-4 col-sm-12 text-right">
               </div>
           </div>
       </div>
       <div class="row">
       	
       <div class="col-xl-12 col-lg-12 col-md-12 col-12">
           <div class="card">
               <div class="card-header">
                   <div class="row col-md-12">
                           {{-- <a href="{{ route('contact.create') }}" class="btn btn-success"> ADD Ebook</a> --}}
                           
                           <div class="col-md-2">
                                <label for="user_name">User</label>
                                <div class="input-group mb-0">
                                    <input type="text" name="user_name" class="form-control valid" id="user_name" value="" aria-invalid="false" placeholder="Enter User Name">
                                </div>
                            </div>
                           <div class="col-md-2">
                                <label for="control_card_no">Control Card SN</label>
                                <div class="input-group mb-0">
                                    <input type="text" name="control_card_no" class="form-control valid" id="control_card_no" value="" aria-invalid="false" placeholder="Enter Control Card SN">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="serial_no">Inverter SN</label>
                                <div class="input-group mb-0">
                                    <input type="text" name="serial_no" class="form-control valid" id="serial_no" value="" aria-invalid="false" placeholder="Enter Inverter SN">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="seacrh_name">Site Name</label>
                                <div class="input-group mb-0">
                                    <input type="text" name="seacrh_name" class="form-control valid" id="seacrh_name" value="" aria-invalid="false" placeholder="Enter Site Name">
                                </div>
                            </div>
                           <div class="col-md-3 align-middle text-right">

                               <label for="username">&nbsp;&nbsp;</label>
                               <div class="input-group mb-0" style="display: table;margin-left: auto;margin-right: auto;">

                                       <button type="button" class="btnSearch btn btn-info mr-1 mb-1" title="Click to Search"><i class="fa fa-search"></i></button>
                                       <!-- <a href="" class="mr-1 mb-1 btn btn-outline-danger" title="Click to Add"><i class="fa fa-plus"></i></a> -->

                                       <!-- <form action="" id="exportCSV" method="post" style="display: inline;">
                                           @csrf
                                           <button type="submit" title="Click to Export" class="mr-0 mb-1 btn btn-outline-danger"><i class="fa fa-file-excel"></i></button>
                                       </form> -->

                               </div>

                           </div>
                   </div>
               </div>
               <div class="card-content collapse show">
                   <div class="card-body card-dashboard">
                       <div class="table-responsive">
                           <table class="table table-striped table-bordered base-style yajra-datatable" style="width: 100%;">
                               <thead>
                                   <tr>
                                       <th>SN</th>
                                       <th>Action</th>
                                       <th>User</th>
                                       <th>Control Card SN</th>
                                       <th>Inverter SN</th>
                                       <th>Site Name</th>
                                       <th>Status</th>
                                       <th>Verified</th>
                                       <th>Created At</th>
                                       <th>Updated At</th>
                                       <!-- <th width="28%">Action</th> -->
                                   </tr>
                               </thead>
                               <tbody>
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

@endsection

@section('page_level_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- <script src="{{ url('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script> -->
<!-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> -->
<script src="{{ url('assets/bundles/datatablescripts.bundle.js') }}" type="text/javascript"></script>
<!-- <script src="{{ url('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}" type="text/javascript"></script> -->

<script type="text/javascript">
    $(document).ready(function() {
        var tableRx = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            "searching": false,
            ajax: {
                url: "{{ url('admin/inverters') }}",
                data: function (d) {
                    d.seacrh_name = $('input[name=seacrh_name]').val();
                    d.control_card_no = $('input[name=control_card_no]').val();
                    d.serial_no = $('input[name=serial_no]').val();
                    d.user_name = $('input[name=user_name]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[6, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {data: 'user_name', name: 'user_name'},
                {data: 'control_card_no', name: 'control_card_no'},
                {data: 'serial_no', name: 'serial_no'},
                {data: 'site_name', name: 'site_name'},
                {data: 'status', name: 'status'},
                {data: 'verified', name: 'verified'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
                
            ],
            "createdRow": function (row, data, index) {
                if (data.deleted == '1') {
                    $(row).addClass('redRaw');
                }
            }

        });
        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });
    });
</script>
@endsection
