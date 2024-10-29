@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection
@section('content')
<?php
 $company_login_id = session()->get('company_login_id');
 $ids = $device_details->id ?? '';
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
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/system-overview') }}">{{ $module }}</a></li>
                            <?php if($ids): ?>
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/device_details/'.$ids) }}">POWRBANK Details</a></li>
                            <?php endif; ?>
                        <?php else : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/system-overview') }}">{{$module}}</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/device_details/'.$ids) }}">POWRBANK Details</a></li>
                        <?php endif; ?>
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
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" id="frm_add">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $device_details->id ?? ''}}">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="ip">IP <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="ip" id="ip" placeholder="Enter IP" value="{{ $device_details->ip ?? ''}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="port">Port <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="port" id="port" placeholder="Enter Port" value="{{ $device_details->port ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="client_vnc_port">Client Side VNC Port <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="client_vnc_port" id="client_vnc_port" placeholder="Enter Port" value="{{ $device_details->client_vnc_port ?? '5900'}}">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-end">
                            <a href="javascript:void(0);" class="btn btn-danger w-sm btn_cancle">Cancel</a>
                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save">Save</a>
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
    $(document).ready(function($) {
        var company_login_id = '{{ $company_login_id ?? ''}}';
         // Validation Users

         $.validator.addMethod('IP4Checker', function(value) {
            return value.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/);
        }, 'Invalid IP address');

         $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                ip: {
                    required: true,
                    IP4Checker: true
                },
                port: {
                    required: true,
                    digits: true
                },
                client_vnc_port: {
                    required: true,
                    digits: true
                },
            },
            messages: {

            },
            // errorElement: "div",
            submitHandler: function (form) {
                // return true;
            }

        });

         // Save Record
         $(document).on('click', '.btn_save', function() {
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-remort-access")}}',
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
                        if(data.status == "true")
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "Succesfully saved."
                            });

                        } else {
                            $.growl.error({
                                message: "Cannot saved."
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
        });


    });
</script>
@endsection

