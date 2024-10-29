@extends('front.layout_admin.app')

@section('page_level_css')
    <!--- Custom Style CSS -->
    <link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet" />
    <style>
    .lds-ring div {
        border: 6px solid #4180FF;
        border-color: #4180FF transparent transparent transparent;
    }
    </style>
@endsection

@section('content')
    <?php
    $P_V = '';
    $grid_genset = '';
    if (isset($grid_data['data']['data']['Power']) && $grid_data['data']['data']['Power']) {
        $grid_genset = $grid_data['data']['data']['Power'];
    }

    if (isset($PV['data']['data']['Power']) && $PV['data']['data']['Power']) {
        $P_V = $PV['data']['data']['Power'];
    }

    $selected_option = [];
    if (isset($data->option_data) && $data) {
        $company_id = $data->company_id;
        foreach ($data->option_data as $key => $value) {
            $selected_option[] = $value['data_value'];
        }
    }
    $company_login_id = session()->get('company_login_id');
    ?>
    <!--app-content open-->
    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER 2 -->
            <div class="main-container container-fluid mt-5">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">{{ $title }}</h1>
                        <ol class="breadcrumb">
                            <?php if ($company_login_id) : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/charts-list') }}">{{ $module }}</a></li>
                            <?php else : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            @can('isUser')
                            <li class="breadcrumb-item"><a href="{{ url('/charts-list') }}">{{ $module }}</a></li>
                            @endcan
                            @can('isAdmin')
                            <li class="breadcrumb-item"><a href="{{ url('/company') }}">Company</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/charts-list/'.$company_id) }}">Charts List</a></li>
                            @endcan
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
                <!-- PAGE-HEADER END -->
                <!-- ROW-1 Start -->
                <form action="javascript:void(0);" method="POST" id="create-chart-form">
                @csrf
                <input type="hidden" name="chart_id" id="chart_id" value="{{$data->id ?? ''}}">
                <input type="hidden" name="company_id" id="company_id" value="{{$company_id ?? ''}}">
                <div class="row mb-4">
                    <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <div class="card h-100">
                            <div class="card-header pb-2">
                                <h3 class="device-card-title">{{$title}}</h3>
                            </div>
                            <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label for="title">Chart Title <span class="text-danger"> *</span> </label>
                                                <input type="text" class="form-control" id="title"  name="title" placeholder="Enter title" value="{{$data->title ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label for="chart-type">Chart Type <span class="text-danger"> *</span> </label>
                                                <select class="form-select form-control" name="chart-type" id="chart-type">
                                                    <option value="">Select</option>
                                                    <option value="line" <?= (isset($data->chart_type) && $data->chart_type == "line" ? 'selected' : '')  ?>>Line Chart</option>
                                                    <option value="bar" <?= (isset($data->chart_type) && $data->chart_type == "bar" ? 'selected' : '')  ?>>Bar Chart</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                            <div class="card-footer">
                                <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white pull-right me-2 save_btn">
                                    <span>
                                        <i class="fe fe-save"></i>
                                    </span> Submit
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="card h-100 mrt-sm-2">
                            <div class="card-header pb-2">
                                <h3 class="device-card-title">Selection Option</h3>
                            </div>
                            <div class="card-body selection-scroll">
                                <div class="row">
                                    <input type="hidden" class="chk-error">
                                    <?php
                                    // $current = 0;
                                    // $energy  = 0;
                                    // $power   = 0;
                                    // $source_selection = 0;
                                    // $voltage = 0;
                                    // $frequency = 0;
                                    // $battery_alarms = 0;
                                    // $state = 0;
                                    // $status_alarms = 0;
                                    // $temperature = 0;
                                    // $status = 0;

                                    $pv = 0;
                                    $grid_genset  = 0;
                                    $battery   = 0;
                                    $alaram_state = 0;
                                    $ess = 0;
                                    $system_calculated = 0;

                                    if (isset($chk_data) && $chk_data) :
                                    foreach ($chk_data as $key => $value) :
                                    $name = '';
                                    if($value["option_type"] == "W")
                                    {
                                        $name = "chk_w[]";
                                    } else if($value["option_type"] == "Hz"){
                                        $name = "chk_hz[]";
                                    } else if($value["option_type"] == "AH"){
                                        $name = "chk_ah[]";
                                    } else if($value["option_type"] == "KWH"){
                                        $name = "chk_kwh[]";
                                    } else if($value["option_type"] == "A"){
                                        $name = "chk_a[]";
                                    } else if($value["option_type"] == "V"){
                                        $name = "chk_v[]";
                                    } else if($value["option_type"] == "Battery"){
                                        $name = "chk_battery[]";
                                    } else if($value["option_type"] == "State"){
                                        $name = "chk_state[]";
                                    } else if($value["option_type"] == "Status_Alarms"){
                                        $name = "chk_status_alarms[]";
                                    } else if($value["option_type"] == "Temperature"){
                                        $name = "chk_temperature[]";
                                    } else if($value["option_type"] == "Status_Battry"){
                                        $name = "chk_status_battry[]";
                                    } else if($value["option_type"] == "ess_status"){
                                        $name = "chk_ess_status[]";
                                    }
                                    ?>
                                    <?php /**  if($value['data_key'] == "Current" && $current == 0) { $current++; ?>
                                        <h3 class="card-title mb-3"> Current </h3>
                                    <?php } else if($value['data_key'] == "Energy" && $energy == 0) {  $energy++; ?>
                                        <h3 class="card-title mt-3 mb-3"> Energy </h3>
                                    <?php } else if($value['data_key'] == "Power" && $power == 0) {  $power++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Power </h3>
                                    <?php } else if($value['data_key'] == "Source Selection" && $source_selection == 0) {  $source_selection++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Source Selection </h3>
                                    <?php } else if($value['data_key'] == "Voltage" && $voltage == 0) {  $voltage++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Voltage </h3>
                                    <?php } else if($value['data_key'] == "Frequency" && $frequency == 0) {  $frequency++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Frequency </h3>
                                    <?php } else if($value['data_key'] == "Battery Alarms" && $battery_alarms == 0) {  $battery_alarms++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Battery Alarms </h3>
                                    <?php } else if($value['data_key'] == "State" && $state == 0) {  $state++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> State </h3>
                                    <?php } else if($value['data_key'] == "Status Alarms" && $status_alarms == 0) {  $status_alarms++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Status Alarms </h3>
                                    <?php } else if($value['data_key'] == "Temperature" && $temperature == 0) {  $temperature++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Temperature </h3>
                                    <?php } else if($value['data_key'] == "Status" && $status == 0) {  $status++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> Status </h3>
                                    <?php } */ ?>


                                    <?php if($value['contain'] == "PV" && $pv == 0) { $pv++; ?>
                                        <h3 class="card-title mb-3"> <?= $value['contain'] ?> </h3>
                                    <?php } else if($value['contain'] == "Grid/Genset" && $grid_genset == 0) {  $grid_genset++; ?>
                                        <h3 class="card-title mt-3 mb-3"> <?= $value['contain'] ?> </h3>
                                    <?php } else if($value['contain'] == "Battery" && $battery == 0) {  $battery++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> <?= $value['contain'] ?> </h3>
                                    <?php } else if($value['contain'] == "Alarms/State" && $alaram_state == 0) {  $alaram_state++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> <?= $value['contain'] ?> </h3>
                                    <?php } else if($value['contain'] == "ESS" && $ess == 0) {  $ess++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> <?= $value['contain'] ?> </h3>
                                    <?php } else if($value['contain'] == "System_calculated" && $system_calculated == 0) {  $system_calculated++;  ?>
                                        <h3 class="card-title mt-3 mb-3"> System Calculated </h3>
                                    <?php } ?>

                                    <?php
                                        $selected = '';
                                        if (in_array($value['data_value'], $selected_option))
                                        {
                                            $selected = 'checked';
                                        }
                                    ?>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input chkBox" name="{{$name}}"
                                                data-text="{{$value['data_value'] ?? ''}}" data-disp="{{$value['option_disp'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{$selected}}>
                                            <span class="custom-control-label">{{$value['option_disp'] ?? ''}}</span>
                                        </label>
                                    </div>

                                    <?php endforeach; endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>

            </div>
            <!-- CONTAINER END -->
        </div>
    </div>
@endsection
@section('page_level_js')
    <!-- CHARTJS JS -->
    <script src="{{ url('theme-asset/plugins/chart/Chart.bundle.js') }} "></script>
    {{-- <script src="{{ url('theme-asset/js/chart.js')}}"></script> --}}

    <!-- Custom Jquery Validation -->
    <script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="{{ url('assets/js/mqtt4.3.7.min.js') }}" type="text/javascript"></script>

    <!-- BOOTSTRAP-DATERANGEPICKER JS -->
    <script src="{{ url('theme-asset/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('theme-asset/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- INTERNAL Bootstrap-Datepicker js-->
    <script src="{{ url('theme-asset/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

    <!-- DATEPICKER JS -->
    <script src="{{ url('theme-asset/plugins/date-picker/date-picker.js') }}"></script>
    <script src="{{ url('theme-asset/plugins/date-picker/jquery-ui.js') }}"></script>
    <script src="{{ url('theme-asset/plugins/input-mask/jquery.maskedinput.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            var company_id = "{{ $company_id ?? ''}}";
            var company_login_id = "{{ $company_login_id ?? ''}}";
            var isAdmin = "{{ auth()->guard('admin')->user()->can('isAdmin') ? 'true' : 'false' }}";
            // Validation Users
        $('#create-chart-form').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                "title": {
                    required: true,
                },
                "chart-type": {
                    required: true,
                },
                // ".chkBox" : {
                //     required:true,
                //     minlength:1
                // }

            },
            messages: {

            },
            // errorElement: "div",
            errorPlacement: function(error, element) {
                if(element.attr('type') == "checkbox")
                {
                    error.insertAfter('.chk-error');
                }
                else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                // return true;
            }
        });

        var limit = 10;
        var limit_v = 10;
        $(document).on('change', '.chkBox', function() {
            var _this = $(this);

            var w_count   = $("input[name='chk_w[]']:checked").length;
            var hz_count  = $("input[name='chk_hz[]']:checked").length;
            var ah_count  = $("input[name='chk_ah[]']:checked").length;
            var kwh_count = $("input[name='chk_kwh[]']:checked").length;
            var A_count   = $("input[name='chk_a[]']:checked").length;
            var V_count       = $("input[name='chk_v[]']:checked").length;
            var Battery_count = $("input[name='chk_battery[]']:checked").length;
            var State_count   = $("input[name='chk_state[]']:checked").length;
            var State_Alarms_count       = $("input[name='chk_status_alarms[]']:checked").length;
            var State_Temperature_count  = $("input[name='chk_temperature[]']:checked").length;
            var State_Battery_count      = $("input[name='chk_status_battry[]']:checked").length;
            var ess_status_count         = $("input[name='chk_ess_status[]']:checked").length;

            var chkSelected   = _this.attr('data-text');
            var selected_key  = _this.attr('data-key');
            var selected_type = _this.val();

                if (selected_type == "W") {

                    if (w_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = hz_count + w_count + ah_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "Hz") {

                    if (hz_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = hz_count + w_count + ah_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "AH") {

                    if (ah_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "KWH") {

                    if (kwh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = kwh_count + ah_count + hz_count + w_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "A") {

                    if (A_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = A_count + ah_count + hz_count + w_count + kwh_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                }

                if (selected_type == "V") {

                    if (V_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = V_count + ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "Battery") {

                    if (Battery_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = Battery_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "State") {

                    if (State_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_Alarms_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                }

                if (selected_type == "Status_Alarms") {

                    if (State_Alarms_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Alarms_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Temperature_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "Temperature") {

                    if (State_Temperature_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Temperature_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Battery_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                }
                if (selected_type == "Status_Battry") {

                    if (State_Battery_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Battery_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count;
                        if (counts > 5 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 5 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                    }
                }

                if (selected_type == "ess_status") {

                if (ess_status_count > limit) {
                    _this.prop('checked', false);
                    $.growl.notice({
                        title: "Success",
                        message: 'You can select maximum of ' + limit + ' checkbox.',
                    });
                } else {
                    var counts = ess_status_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count;
                    if (counts > 5 && w_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && hz_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && ah_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && kwh_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && A_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && V_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && Battery_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && State_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && State_Alarms_count > 0) {
                        _this.prop('checked', false);
                    }
                    else if (counts > 5 && State_Temperature_count > 0) {
                        _this.prop('checked', false);
                    }
                }

                if (_this.prop('checked') == true) {
                    addDynamicGraphData();
                } else {
                    removeData(chkSelected);
                }
                }
                checkBoxValidation();
        });

        $(document).on('click','.save_btn',function(){
            var _this = $(this);
            checkBoxValidation();
            if($("#create-chart-form").valid()) {
                var datas  = [];
                var form_data = new FormData($('#create-chart-form')[0]);
                $(".chkBox:checked").each(function (j) {
                    if($(this).val())
                    {
                        let data = {
                            'contain'     :  $(this).attr('data-value'),
                            'data_key'    :  $(this).attr('data-key'),
                            'data_value'  :  $(this).attr('data-text'),
                            'option_type' :  $(this).val(),
                            'option_disp' :  $(this).attr('data-disp'),
                        }
                        datas.push(data);
                    }
                });
                form_data.append('checkboxData',  JSON.stringify(datas));
                $.ajax({
                    url: '{{ url('save-charts') }}',
                    type: "POST",
                    contentType: false,
                    processData: false,
                    data: form_data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == 'true') {
                            if(company_login_id)
                            {
                                var url  = "/company/"+company_login_id+"/charts-list";
                                location = url;
                            } else {

                                if(company_id && isAdmin == "true")
                                {
                                    var url = "{{ url('/charts-list') }}" +'/'+company_id;
                                    location = url;
                                } else {
                                    location = "{{ url('/charts-list') }}";
                                }
                            }
                        } else
                        {
                            $.growl.error({
                                message: "Cannot saved."
                            });
                        }
                    }
                });
            }
        });

        function checkBoxValidation() {
            var chk_count = $(".chkBox:checked").length;
            if(chk_count <= 0)
            {
                $(".chkBox").rules("add", {
                    required:true,
                    minlength:1,
                    messages: {
                        required: "Please select at least one checkbox."
                    },
                });
            } else {
                $(".chkBox").rules("remove", "required min");
            }
        }


        }); // Ready
    </script>
@endsection
