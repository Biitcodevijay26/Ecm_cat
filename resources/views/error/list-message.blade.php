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
    .tab-menu-heading{
        padding:10px !important;
    }
    .tab-btn-custom{
        border-radius: 5px;
        background-color: var(--primary01);
    }
</style>
@endsection
@section('content')
<?php
    $company_login_id = session()->get('company_login_id');
    $is_admin = false;
    if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
    {
        $is_admin = true;
    }
?>
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
                        <?php if ($company_login_id) : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">

                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1 ">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li class="me-2"><a href="#warning-msg" class="active tab-btn-custom fs-16" data-bs-toggle="tab" class="warning-tab-btn">Warning</a></li>
                                            <li><a href="#error-msg" data-bs-toggle="tab" class="error-tab-btn tab-btn-custom fs-16" style="background-color: var(--primary01);">Notification</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active " id="warning-msg">
                                            <!-- ROW-1 OPEN -->
                                            <div class="row">
                                                @can('NotificationMessageAdd')
                                                <div class="col-xl-4 col-md-12 col-sm-12">
                                                    <div class="card card-border-black">
                                                        <div class="card-header bg-color-black">
                                                            <div class="card-title text-white">Add Warning</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <form method="post" id="frm_add">
                                                            @csrf
                                                            <input type="hidden" name="id" id="id" value="">
                                                            <?php if ($company_login_id || auth()->guard('admin')->user()->role_id != \Config::get('constants.roles.Master_Admin')) : ?>
                                                            <div class="form-group form-group-register error-device_id">
                                                                <label class="form-label" for="device_id">POWRBANK <span class="text-danger"> *</span></label>
                                                                <select class="form-select select2 form-control" name="device_id" id="device_id">
                                                                    <option value="">Select</option>
                                                                    @if ($devices)
                                                                    @foreach ($devices as $device)
                                                                    <?php
                                                                        $selected = "";
                                                                        if(isset($data->device_id) && $device->id == $data->device_id){
                                                                            $selected = "selected";
                                                                        }
                                                                    ?>
                                                                    <option <?= $selected ?> value="{{$device->id ?? ''}}">{{$device->name ?? ''}}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="form-group">
                                                                <label class="form-label">Error Code <span class="text-danger"> *</span></label>
                                                                <input type="text" name="error_code" id="error_code" class="form-control" value="" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Title <span class="text-danger"> *</span></label>
                                                                <input type="text" name="title" id="title" class="form-control" value="" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Message <span class="text-danger"> * </span></label>
                                                                <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                                                            </div>
                                                            {{-- <div class="form-group">
                                                                <label for="is_active" class="form-label">Is Active</label>
                                                                <label class="custom-switch">
                                                                    <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input chk-status" checked>
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div> --}}
                                                            </form>

                                                        </div>
                                                        <div class="card-footer text-end">
                                                            <input type="reset" class="btn btn-danger w-sm btn_cancle" value="Cancel">
                                                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save">Save</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-8 col-md-12 col-sm-12">
                                                @else
                                                <div class="col-xl-12 col-md-12 col-sm-12">
                                                @endcan
                                                    <div class="card card-border-black">
                                                        <div class="card-header bg-color-black"  style="height: 60px;">
                                                            <h3 class="card-title text-white">Warning Info</h3>
                                                            <div class="card-options">
                                                                <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name" value="" placeholder="Search...">
                                                                <button type="button" class="btnSearch btn btn-info pull-right" title="Click to Search"><i class="fe fe-search"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable warning-yajra-datatable w-100">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>SN</th>
                                                                            <th class="wd-15p border-bottom-0">Title</th>
                                                                            <th class="wd-15p border-bottom-0">POWRBANK Name</th>
                                                                            <th class="wd-15p border-bottom-0">Error Code</th>
                                                                            <th class="wd-15p border-bottom-0">Message</th>
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

                                        <div class="tab-pane" id="error-msg">
                                            <!-- ROW-1 OPEN -->
                                            <div class="row">
                                                @can('NotificationMessageAdd')
                                                <div class="col-xl-4 col-md-12 col-sm-12">
                                                    <div class="card card-border-black">
                                                        <div class="card-header bg-color-black">
                                                            <div class="card-title text-white">Add Notification</div>
                                                        </div>
                                                        <div class="card-body">
                                                            <form method="post" id="frm_add_error">
                                                            @csrf
                                                            <input type="hidden" name="error_id" id="error_id" value="">
                                                            <?php if ($company_login_id || auth()->guard('admin')->user()->role_id != \Config::get('constants.roles.Master_Admin')) : ?>
                                                            <div class="form-group form-group-register error-device_id_error">
                                                                <label class="form-label" for="device_id">POWRBANK <span class="text-danger"> *</span></label>
                                                                <select class="form-select select2 form-control" name="error_device_id" id="error_device_id">
                                                                    <option value="">Select</option>
                                                                    @if ($devices)
                                                                    @foreach ($devices as $device)
                                                                    <?php
                                                                        $selected = "";
                                                                        if(isset($data->device_id) && $device->id == $data->device_id){
                                                                            $selected = "selected";
                                                                        }
                                                                    ?>
                                                                    <option <?= $selected ?> value="{{$device->id ?? ''}}">{{$device->name ?? ''}}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <?php endif; ?>
                                                            <div class="form-group">
                                                                <label class="form-label">Error Code <span class="text-danger"> *</span></label>
                                                                <input type="text" name="error_code" id="error_error_code" class="form-control" value="" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Title <span class="text-danger"> *</span></label>
                                                                <input type="text" name="title" id="error_title" class="form-control" value="" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Message <span class="text-danger"> * </span></label>
                                                                <textarea class="form-control" name="message" id="error_message" rows="3"></textarea>
                                                            </div>
                                                            {{-- <div class="form-group">
                                                                <label for="is_active" class="form-label">Is Active</label>
                                                                <label class="custom-switch">
                                                                    <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input chk-status" checked>
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div> --}}
                                                            </form>

                                                        </div>
                                                        <div class="card-footer text-end">
                                                            <input type="reset" class="btn btn-danger w-sm btn_cancle" value="Cancel">
                                                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save_error">Save</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-8 col-md-12 col-sm-12">
                                                @else
                                                <div class="col-xl-12 col-md-12 col-sm-12">
                                                @endcan
                                                    <div class="card card-border-black">
                                                        <div class="card-header bg-color-black" style="height: 60px;">
                                                            <h3 class="card-title text-white">Notification Info</h3>
                                                            <div class="card-options">
                                                                <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name_error" value="" placeholder="Search...">
                                                                <button type="button" class="btnSearchError btn btn-info pull-right" title="Click to Search"><i class="fe fe-search"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable error-yajra-datatable w-100">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>SN</th>
                                                                            <th class="wd-15p border-bottom-0">Title</th>
                                                                            <th class="wd-15p border-bottom-0">POWRBANK Name</th>
                                                                            <th class="wd-15p border-bottom-0">Error Code</th>
                                                                            <th class="wd-15p border-bottom-0">Message</th>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- COL-END -->
            </div>
            <!-- ROW-1 CLOSED -->
        </div>
    </div>
</div>
@endsection
@section('page_level_js')
<script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>
<script>
     $(document).ready(function() {
        $('.select2').select2({});

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        // Warning Script

        var tableRx = $('.warning-yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-warning-list') }}",
                data: function (d) {
                    d.seacrh_name = $('input[name=seacrh_name]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title',orderable: true, searchable: false },
                {data: 'device_name', name: 'device_name',orderable: false, searchable: false },
                {data: 'error_code', name: 'error_code',orderable: true, searchable: false },
                {data: 'message_text', name: 'message',orderable: false, searchable: false,
                    render:function(data,type,row){
                        var msg = '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="'+row.message+'"> '+data+'</a>';
                        return msg;
                    }
                },
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
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
                title: {
                    required: true,
                },
                error_code: {
                    required: true,
                    digits: true
                },
                message: {
                    required: true,
                },
            },
            messages: {
            },
            errorPlacement: function(error, element) {
                if(element.attr('name') == "device_id")
                {
                    error.insertAfter('.error-device_id');
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                // return true;
            }
        });

        var isAdmin    = '{{ $is_admin }}';
        var isComLogin = '{{ $company_login_id }}';
        if(isAdmin && !isComLogin)
        {
            $("select[id=device_id]").rules("remove", "required");
        } else {
            $("select[id=device_id]").rules("add", "required");
        }
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        $(document).on('click', '.btn_save', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-warning")}}',
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
                            $('#id').val('');
                            $('.btn_cancle').click();

                        } else if(data.status == 2){
                            $.growl.error({
                                message: "Warning Code already exists."
                            });
                        }
                        else {
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
            $('#id').val('');
            $('#device_id').val('').select2();

            $('#frm_add_error')[0].reset();
            $('#error_id').val('');
            $('#error_device_id').val('').select2();
        });

        $(document).on('click', '.editWarning', function() {
            var _this        = $(this);
            var id           = _this.attr('data-id');
            var name         = _this.attr('data-title');
            var code         = _this.attr('data-error-code');
            var dial_code    = _this.attr('data-message');
            var status       = _this.attr('data-status');
            var device_id    = _this.attr('data-device_id');
            $('#id').val(id);
            $('#title').val(name);
            $('#error_code').val(code);
            $('#message').val(dial_code);
            $('#device_id').val(device_id).select2();
            if(status == 1){
                $(".chk-status").prop('checked', true);
            }else{
                $(".chk-status").prop('checked', false);
            }

        });

        $(document).on('click', '.removeWarning', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

            var result = confirm("Are you sure you want to delete this record?");
            if (result) {
                $.ajax({
                    url: '{{url("delete-warning")}}',
                    type: "POST",
                    data:  {id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data == '1') {
                            $.growl.notice({
                                title: "Success",
                                message: "Succesfully deleted."
                            });
                            tableRx.ajax.reload( null, false );
                        } else {
                            $.growl.error({
                                message: "Cannot deleted."
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

        // Error Script Start

        var tableErrorRx = $('.error-yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-error-list') }}",
                data: function (d) {
                    d.seacrh_name = $('#seacrh_name_error').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title',orderable: true, searchable: false },
                {data: 'device_name', name: 'device_name',orderable: false, searchable: false },
                {data: 'error_code', name: 'error_code',orderable: true, searchable: false },
                {data: 'message_text', name: 'message',orderable: false, searchable: false,
                    render:function(data,type,row){
                        var msg = '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="'+row.message+'"> '+data+'</a>';
                        return msg;
                    }
                },
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        $(document).on('click', '.btnSearchError', function(e) {
            tableErrorRx.draw();
            e.preventDefault();
        });

        $('#frm_add_error').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                title: {
                    required: true,
                },
                error_code: {
                    required: true,
                    digits: true
                },
                message: {
                    required: true,
                },
            },
            messages: {
            },
            errorPlacement: function(error, element) {
                if(element.attr('name') == "error_device_id")
                {
                    error.insertAfter('.error-device_id_error');
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                // return true;
            }
        });
        console.log(isComLogin);
        if(isAdmin && !isComLogin)
        {
            $("select[id=error_device_id]").rules("remove", "required");
        } else {
            $("select[id=error_device_id]").rules("add", "required");
        }
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        $(document).on('click', '.btn_save_error', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            var _this = $(this);
            if($("#frm_add_error").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-error")}}',
                    type: "POST",
                    data:  $('#frm_add_error').serialize(),
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
                            tableErrorRx.ajax.reload( null, false );
                            $('#frm_add_error')[0].reset();
                            $('#error_id').val('');
                            $('.btn_cancle').click();

                        } else if(data.status == 2){
                            $.growl.error({
                                message: "Warning Code already exists."
                            });
                        }
                        else {
                            $.growl.error({
                                message: "Cannot save."
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click', '.editError', function() {
            var _this        = $(this);
            var id           = _this.attr('data-id');
            var name         = _this.attr('data-title');
            var code         = _this.attr('data-error-code');
            var dial_code    = _this.attr('data-message');
            var status       = _this.attr('data-status');
            var device_id    = _this.attr('data-device_id');
            $('#error_id').val(id);
            $('#error_title').val(name);
            $('#error_error_code').val(code);
            $('#error_message').val(dial_code);
            $('#error_device_id').val(device_id).select2();
            // if(status == 1){
            //     $(".chk-status").prop('checked', true);
            // }else{
            //     $(".chk-status").prop('checked', false);
            // }

        });

        $(document).on('click', '.removeError', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

            var result = confirm("Are you sure you want to delete this record?");
            if (result) {
                $.ajax({
                    url: '{{url("delete-error")}}',
                    type: "POST",
                    data:  {id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data == '1') {
                            $.growl.notice({
                                title: "Success",
                                message: "Succesfully deleted."
                            });
                            tableErrorRx.ajax.reload( null, false );
                        } else {
                            $.growl.error({
                                message: "Cannot deleted."
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click','.error-tab-btn',function(){
            $('#error_device_id').select2({});
        });

        $(document).on('click','.warning-tab-btn',function(){
            $('.select2').select2({});
        });
    });
</script>
@endsection
