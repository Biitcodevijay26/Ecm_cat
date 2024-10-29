@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>

<style>
    .custom-chkbox-permission {
        width: 1.2rem !important;
        height: 1.2rem !important;
    }
    .form-check-label.h5 {
        margin-bottom:0;
    }

    .colorinput-input:checked + .bg-indigo 
    {
        background: #AAC760 !important;
        border: none !important;
    }

    .colorinput-color:not(:checked) {
        border: 2px solid #AAC760 !important;
        background: transparent !important; 
    }
</style>
<?php
    $comp_role_id = \Config::get('constants.roles.Company_Admin');
    $is_admin = false;
    if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
    {
        $is_admin = true;
    }
    $company_login_id = session()->get('company_login_id');
    $disabled = '';
    if($company_login_id)
    {
        $disabled = "disabled";
    }
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
                        <?php if($company_login_id) : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/new-users-list') }}">{{$module}}</a></li>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/new-users-list') }}">{{$module}}</a></li>
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
                                <input type="hidden" name="user_id" id="user_id" value="{{$data->id ?? ''}}">
                                <!-- ROW-1 OPEN -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{$data->first_name ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name <span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="last_name"  name="last_name" placeholder="Last Name" value="{{$data->last_name ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger"> *</span></label>
                                            <?php $is_disabled = (isset($data->id) && $data->id ? "disabled" : ''); ?>
                                            <input <?= $is_disabled ?> type="email" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{$data->email ?? ''}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-register error-company_id">
                                            <label for="company_id">Company <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="company_id" id="company_id" @can('isUser') disabled @endcan <?= $disabled ?>>
                                                <option  value="">Select</option>
                                                @if ($companies)
                                                @foreach ($companies as $company)
                                                <?php
                                                    $selected = "";
                                                    if(isset($data->company_id) && $company->id == $data->company_id){
                                                        $selected = "selected";
                                                    }
                                                ?>
                                                <option <?= $selected ?> value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-register error-country_id">
                                            <label for="country_id">Country <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="country_id" id="country_id">
                                                <option value="">Select</option>
                                                @if ($countries)
                                                    @foreach ($countries as $country)
                                                    <?php
                                                        $selected = "";
                                                        if(isset($data->country['id']) && $country->id == $data->country['id']){
                                                            $selected = "selected";
                                                        }
                                                    ?>
                                                    <option <?= $selected ?> value="{{$country->id ?? ''}}">{{$country->name ?? ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="city_name">City <span class="text-danger"> *</span></label>
                                            <input class="form-control" type="text" name="city_name" id="city_name" placeholder="City" autocomplete="off" value="{{$data->city_name ?? ''}}">
                                        </div>
                                    </div>
                                    <?php if ($is_admin || $mode == 'add') : ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password">Password </label>
                                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="cpassword">Confirm Password </label>
                                            <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-register error-role_id">
                                            <label for="role_id">Role <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="role_id" id="role_id">
                                                <option value="">Select</option>
                                                @if ($Roles)
                                                    @foreach ($Roles as $role)
                                                    <?php
                                                        $selected = "";
                                                        if(isset($data->role_id) && $role->id == $data->role_id){
                                                            $selected = "selected";
                                                        }
                                                    ?>
                                                    <option <?= $selected ?> value="{{$role->id ?? ''}}">{{$role->name ?? ''}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label">Is Active</label>
                                            <?php
                                            $is_active = 0;
                                            if(isset($data->is_active) && $data->is_active){
                                                $is_active = 1;
                                            } else if( !isset($data->is_active) ){
                                                $is_active = 1;
                                            }
                                            ?>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" class="custom-switch-input chk-status" {{ $is_active == 1 ? 'checked' : ''}}>
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if(isset($data) && $data->id) : ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label">Email Verification</label>
                                            <?php
                                            $status = '<span class="badge bg-danger">Not Verified</span>';
                                            if(isset($data->status) && $data->status == 1){
                                                $status = '<span class="badge bg-success">Verified</span>';
                                            }
                                            ?>
                                            <label class="custom-switch">
                                                {!! $status !!}
                                            </label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <!-- ROW-1 CLOSED -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group error-weight_unit">
                                            <label for="weight_unit">Weight Unit <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="weight_unit" id="weight_unit">
                                                <option value="">Select</option>
                                                <option value="kg" <?php echo (isset($data->weight_unit) && $data->weight_unit == 'kg' ? 'selected' : '') ?> >KG</option>
                                                <option value="lbs" <?php echo (isset($data->weight_unit) && $data->weight_unit == 'lbs' ? 'selected' : '') ?> <?= (isset($mode) && $mode == 'add' ? 'selected' : '') ?>>LBS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group error-liquid_unit">
                                            <label for="liquid_unit">Liquid Unit <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="liquid_unit" id="liquid_unit">
                                                <option value="">Select</option>
                                                <option value="gallons" <?php echo (isset($data->liquid_unit) && $data->liquid_unit == 'gallons' ? 'selected' : '') ?>  <?= (isset($mode) && $mode == 'add' ? 'selected' : '') ?> >Gallons</option>
                                                <option value="liter" <?php echo (isset($data->liquid_unit) && $data->liquid_unit == 'liter' ? 'selected' : '') ?> >Liter</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group error-currency">
                                            <label for="currency">Currency <span class="text-danger"> *</span></label>
                                            <select class="form-select select2 form-control" name="currency" id="currency">
                                                <option value="">Select</option>
                                                @if ($currency_list)
                                                @foreach ($currency_list as $currency)
                                                <?php
                                                    $selected = "";
                                                    if(isset($data->currency) && $currency->code == $data->currency){
                                                        $selected = "selected";
                                                    }

                                                    if(isset($mode) && $mode == 'add' && $currency->code == 'USD'){
                                                        $selected = "selected";
                                                    }

                                                ?>
                                                <option <?= $selected ?> value="{{$currency->code ?? ''}}">{{$currency->code ?? ''}} - {{$currency->name ?? ''}}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               <!-- ROW-2 -->
                               <div class="row mt-2">
                                <?php $ctn = 0; ?>
                                @foreach ($Permissions as $key => $permission)

                                <div class="col-xl-6 col-md-12">
                                    <div class="card card-border">
                                        <div class="card-header {{ $card_colors[$ctn] ?? ''}}">
                                            <div>
                                                <h3 class="card-title text-white"><?php echo parseCamelCase($key) ?? '' ?></h3>
                                            </div>
                                        </div>
                                        <div class="card-body pb-0 pt-4">
                                            @if($permission)
                                            @foreach ($permission as $item)
                                            <?php
                                                $checked = '';
                                                if(in_array($item['permission_code'],$user_permissions)) {
                                                    $checked = 'checked';
                                                }
                                            ?>
                                            <div class="activity1">
                                                <div class="activity-blog">
                                                    <div class="activity-img brround bg-info-transparent text-info">
                                                        <i class="fa fa-arrow-right fs-20"></i>
                                                    </div>
                                                    <div class="activity-details d-flex">
                                                        <div>
                                                            <h4 class="card-title mt-1">{{$item['permission_name'] ?? '' }}</h4>
                                                            {{-- <b>
                                                                <span class="text-dark"> {{ $item['permission_name'] ?? ''}} </span>
                                                            </b> --}}
                                                        </div>
                                                        <div class="ms-auto fs-13 text-dark fw-semibold">
                                                            <div class="col-auto">
                                                                <label class="colorinput">
                                                                    <input name="permisionCode[]" type="checkbox" value="{{ $item['permission_code'] ?? '' }}" class="colorinput-input" {{$checked}}>
                                                                    <span class="colorinput-color bg-indigo"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    if(isset($card_colors[$ctn]) && $card_colors[$ctn])
                                    {
                                        $ctn++;
                                    } else {
                                        $ctn = 0;
                                    }
                                ?>
                                @endforeach
                            </div>
                            <!-- ROW-2 CLOSED-->
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

        $('.select2').select2({});
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        // Validation Users
        $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                // company_id: {
                //     required: true,
                // },
                country_id: {
                    required: true,
                },
                role_id: {
                    required: true,
                },
                weight_unit: {
                    required: true,
                },
                liquid_unit: {
                    required: true,
                },
                currency: {
                    required: true,
                },
                city_name: {
                    required: true,
                },
                password: {
                    minlength: 6,
                },
                cpassword: {
                    minlength: 6,
                    equalTo: "#password",
                }

            },
            messages: {

            },
            // errorElement: "div",
            errorPlacement: function(error, element) {
                if(element.attr('name') == "company_id")
                {
                    error.insertAfter('.error-company_id');
                }
                else if(element.attr('name') == "country_id"){
                    error.insertAfter('.error-country_id');
                }
                else if(element.attr('name') == "role_id"){
                    error.insertAfter('.error-role_id');
                }
                else if(element.attr('name') == "weight_unit"){
                    error.insertAfter('.error-weight_unit');
                }
                else if(element.attr('name') == "liquid_unit"){
                    error.insertAfter('.error-liquid_unit');
                }
                else if(element.attr('name') == "currency"){
                    error.insertAfter('.error-currency');
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                // return true;
            }

        });

        var isAdmin          = '{{ $is_admin }}';
        var company_login_id = '{{ $company_login_id }}';
        var comp_role_id     = '{{ $comp_role_id }}';
        if(isAdmin)
        {
            $("select[id=company_id]").rules("add", "required");
        } else {
            $("select[id=company_id]").rules("remove", "required");
        }
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });

        // Save Record
        $(document).on('click', '.btn_save', function() {
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("new-user-save")}}',
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
                                message: "Succesfully Saved."
                            });
                            if(data.page_redirect)
                            {
                                if (company_login_id) {
                                    var url = "/company/"+company_login_id+"/users-list";
                                    location = url;
                                } else {
                                    location = "{{ url('/users-list') }}";
                                }
                            } else {
                                if (company_login_id) {
                                    var url = "/company/"+company_login_id+"/new-users-list";
                                    location = url;
                                } else {
                                    location = "{{url('/new-users-list')}}";
                                }
                            }

                        } else if(data.status == "error") {

                            if(data.errors)
                            {
                                $.each(data.errors,function(key,val) {
                                    if(key == "company_id")
                                    {
                                        $(".error-company_id").after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                    } else if(key == "country_id") {
                                        $(".error-country_id").after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                    } else if(key == "role_id") {
                                        $(".error-role_id").after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                    } else {
                                        $("#"+key).after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                    }
                                })

                            }
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

        $(document).on('change','.chk-all', function(event) {
            var _t = $(this);
            var check = event.target.checked;
            var roleid = event.target.value;
            if(check){
                $('.masterRole').find("[type='checkbox']").prop("checked", true);
            } else {
                $('.masterRole').find("[type='checkbox']").prop("checked", false);
            }
        });

        $(document).on('change','.chk-group', function(event) {
            var _t = $(this);
            var check = event.target.checked;
            var roleid = _t.attr('data-id');
            if(check){
                $('.'+roleid).prop("checked", true);
            } else {
                $('.'+roleid).prop("checked", false);
            }
        });

        $(document).on('change','#role_id', function(){
            var _this = $(this);
            if(comp_role_id == _this.val()){
                $("[name='permisionCode[]']").prop("checked", true);
            } else {
                $("[name='permisionCode[]']").prop("checked", false);
            }
        });

    });
</script>
@endsection
