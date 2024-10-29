@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<style>
     .dataTables_paginate  {
        float: right;
    }
    .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
    .table td, .table th {
        padding: .75rem 0.75rem;
    }
</style>
<?php
    $company_login_id = session()->get('company_login_id');
?>
@endsection
@section('content')

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $heading }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add Company
                    </a> --}}
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Company</div>
                        </div>
                        <div class="card-body">
                            <form method="post" id="frm_add">
                            @csrf
                            <input type="hidden" name="company_id" id="company_id" value="">
                            <div class="form-group">
                                <label class="form-label">Company Name <span class="text-danger"> *</span></label>
                                <input type="text" name="company_name" id="company_name" class="form-control" value="" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="is_active" class="form-label">Is Active</label>
                                <label class="custom-switch">
                                    <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input chk-status" checked>
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </div>
                            </form>

                        </div>
                        <div class="card-footer text-end">
                            <input type="reset" class="btn btn-danger w-sm btn_cancle" value="Cancel">
                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save">Save</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                            <div class="card-options">
                                <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name" value="" placeholder="Search Name">
                                <button type="button" class="btnSearch btn btn-info pull-right" title="Click to Search"><i class="fe fe-search"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Company Owner</th>
                                            <th class="wd-15p border-bottom-0">Company Login</th>
                                            <!-- <th class="wd-15p border-bottom-0">System Overview</th> -->
                                            <!-- <th class="wd-15p border-bottom-0">Charts</th> -->
                                            <th class="wd-15p border-bottom-0">Status</th>
                                            <th class="wd-15p border-bottom-0">Created At</th>
                                            <th class="wd-25p border-bottom-0">Action</th>
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
            <!-- ROW-1 CLOSED -->
        </div>
         <!-- CONTAINER END -->
    </div>
</div>

@endsection
@section('page_level_js')


<!-- Custom Jquery Validation -->
<script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>
<script>
     $(document).ready(function() {
        var tableRx = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-company-list') }}",
                data: function (d) {
                    d.seacrh_name = $('input[name=seacrh_name]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'company_name', name: 'company_name',orderable: true, searchable: false },
                {data: 'company_owner', name: 'company_owner',orderable: false, searchable: false },
                {data: 'company_login', name: 'company_login',orderable: false, searchable: false ,className:'text-center'},
                // {data: 'system_overview', name: 'system_overview',orderable: false, searchable: false ,className:'text-center'},
                // {data: 'charts', name: 'charts',orderable: false, searchable: false ,className:'text-center'},
                {data: 'status', name: 'status',orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at',orderable: true},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                company_name: {
                    required: true,
                },
            },
            messages: {
            },
            submitHandler: function (form) {
                // return true;
            }
        });


        $(document).on('click', '.btn_save', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-company")}}',
                    type: "POST",
                    data:  $('#frm_add').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        _this.prop('disabled', false).html('Save');
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == 1)
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "Succesfully saved."
                            });
                            tableRx.ajax.reload( null, false );
                            $('#frm_add')[0].reset();
                            $('#company_id').val('');
                            $('.btn_cancle').click();

                        } else {
                            $.growl.error({
                                message: "Cannot save."
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click', '.btn_cancle', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            $('#frm_add')[0].reset();
            $('#company_id').val('');
        });

        $(document).on('click', '.editCompany', function() {
            var _this        = $(this);
            var id           = _this.attr('data-id');
            var company_name = _this.attr('data-company-name');
            var status       = _this.attr('data-status');
            $('#company_id').val(id);
            $('#company_name').val(company_name);
            if(status == 1){
                $(".chk-status").prop('checked', true);
            }else{
                $(".chk-status").prop('checked', false);
            }

        });


        $(document).on('click', '.activeInactiveCompany', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

                $.ajax({
                    url: '{{url("active-inactive-company")}}',
                    type: "POST",
                    data:  {id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        tableRx.ajax.reload( null, false );
                    }
                });
        });

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

    });
</script>

@endsection
