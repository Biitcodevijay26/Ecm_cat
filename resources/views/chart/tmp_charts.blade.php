@extends('front.layout_admin.app')

@section('page_level_css')
    <!--- Custom Style CSS -->
    <link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
    .lds-ring div {
        border: 6px solid #4180FF;
        border-color: #4180FF transparent transparent transparent;
    }
    .selection-scroll {
        max-height: 650px !important;
    }
    .chart-height {
        min-height: 700px;
    }
    .chart-maximize{
        width: 100vw;
        position: fixed;
        height: 100vh;
        top: 0;
        left: 0;
        z-index: 100;
    }

    .checkbox-main-div {
        position: relative;
        transition: opacity 0.5s ease;
    }

    .processing-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.8);
        padding: 40px;
        display: none;
    }

    .legend-container {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 20px;
        cursor: pointer;
    }

    .legend-item input {
        margin-right: 5px;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 3px;
        border: 1px solid #eaedf1;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .colorinput {
        display: flex;
        align-items: center;
    }

    .colorinput-label {
        margin-left: 10px;
    }

    .bg-label-1 {
        background: #AAC760 !important;
        color: #fff !important;
    }
    .bg-label-2 {
        background: #90ABB5 !important;
        color: #fff !important;
    }
    .bg-label-3 {
        background: #1F3D4E !important;
        color: #fff !important;
    }
    .bg-label-4 {
        background: #0F203D !important;
        color: #fff !important;
    }
    .bg-label-5 {
        background: #4E1F4A !important;
        color: #fff !important;
    }
    .bg-label-6 {
        background: #2F3F11 !important;
        color: #fff !important;
    }
    .bg-label-7 {
        background: #506849 !important;
        color: #fff !important;
    }
    .bg-label-8 {
        background: #D84B2B !important;
        color: #fff !important;
    }
    .bg-label-9 {
        background: #FF7E27 !important;
        color: #fff !important;
    }
    .bg-label-10 {
        background: #FFCE00 !important;
        color: #fff !important;
    }
    .legend-container{
        display: flex;
        flex-wrap: wrap;
    }
    .chart-logo{
        width: 20px;
    }
    .chart-header .logo{
        display: flex;
        align-items: center;
        float: right;
    }
    .chart-header .details{
        float: right;
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

    $macid      = $device_data->macid ?? '';
    $company_id = $device_data->company_id ?? '';
    $id         = $device_data->id ?? '';
    $userID     = $device_data->id ?? '';
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $company_login_id = session()->get('company_login_id');
    $location = (isset($device_data->location) && $device_data->location ? $device_data->location['address'] : '');

    $is_verified = 'false';
    if(isset($device_data->verified) && $device_data->verified == "DEVICE_VARIFIED")
    {
        $is_verified = 'true';
    }

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
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/system-overview') }}">System Overview</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}">POWRBANK Details</a></li>
                            <?php else : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            @can('isAdmin')
                            <li class="breadcrumb-item"><a href="{{ url('/company') }}">Company</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/system-overview/'.$company_id) }}">System Overview</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/device_details/'.$id) }}">POWRBANK Details</a></li>
                            @endcan
                            @can('isUser')
                            <li class="breadcrumb-item"><a href="{{ url('/system-overview') }}">System Overview</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/device_details/'.$id) }}">POWRBANK Details</a></li>
                            @endcan
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
                 <!-- ROW-1 Start -->
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                        <p class="device-serial-number">{{ $device_data->macid ?? ''}}</p>
                                        <p class="device-serial-label">MACID</p>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                        <p class="device-serial-number">{{ $device_data->name ?? ''}}</p>
                                        <p class="device-serial-label">POWRBANK Name</p>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                        <p class="device-serial-number machine-updated-date">July 24, 2023</p>
                                        <p class="device-serial-label machine-updated-time">10:22 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ROW-1 Close -->
                 <!-- ROW-Buttons Start -->
            <div class="row mb-3">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row d-flex justify-content-start">


                                <?php if ($company_login_id) : ?>
                                    <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/company/'.$company_login_id.'/battery_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon.png') }}" alt="battery-icon"> </span> Battery
                                    </a>

                                    <a href="{{url('/company/'.$company_login_id.'/edit-device/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/edit-icon.png') }}" alt="edit-device-icon"> </span> Edit POWRBANK
                                    </a>

                                    <a href="{{ url('/company/'.$company_login_id.'/charts/'.$id) }}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/chart-icon-w.png') }}" alt="view-chart-icon"> </span> View Chart
                                    </a>

                                    <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 machine_verified_btn d-none mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/verified-icon.png') }}" alt="verify-machine-icon"> </span> Verify Machine
                                    </a>

                                    <?php if(auth()->guard('admin')->user()->role_id == $adminRoleId) : ?>

                                        <a href="{{url('/company/'.$company_login_id.'/remort-access/'.$id)}}" class="btn bg-color-default text-black  device-btn-width device-btn-text me-2 remort_access_btn mb-2 d-none" style="width:260px !important">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access Setting
                                        </a>

                                        <?php endif; ?>
                                        <a href="{{url('/company/'.$company_login_id.'/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                        </a>

                                <?php else : ?>
                                    <a href="{{ url('/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/battery_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon.png') }}" alt="battery-icon"> </span> Battery
                                    </a>
                                    @can('DeviceManagementEdit')
                                    <a href="{{url('/edit-device/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/edit-icon.png') }}" alt="edit-device-icon"> </span> Edit POWRBANK
                                    </a>
                                    @endcan

                                    @can('ChartView')
                                    <a href="{{url('/charts/'.$id)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/chart-icon-w.png') }}" alt="view-chart-icon"> </span> View Chart
                                    </a>
                                    @endcan

                                    <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 machine_verified_btn d-none mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/verified-icon.png') }}" alt="verify-machine-icon"> </span> Verify Machine
                                    </a>
                                    @can('RemoteAccessView')
                                    <a href="{{url('/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                    </a>
                                    @endcan
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-Buttons Close -->
                <!-- ROW-1 Start -->
                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-9 col-xl-9">
                        <div class="card card-options-chart-maximize chart-height">
                            <div class="card-header pb-2">
                                <h3 class="device-card-title">Data Analysis Chart</h3>
                                <div class="card-options">
                                    <select class="form-select form-control me-2" name="select-charts" id="select-charts">
                                        <option value="">Select Chart</option>
                                        <option value="line" selected>Line</option>
                                        <option value="bar">Bar</option>
                                    </select>
                                    <div class="btn-group mb-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
                                            <span class="fa fa-align-justify"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="javascript:void(0);" class="card-options-btn">View in Full Screen</a></li>
                                            <li><a href="javascript:void(0);" class="exportChart" data-id="print_chart">Print Chart</a></li>
                                            <li><a href="javascript:void(0);" class="exportChart" data-id="png">Download PNG image</a></li>
                                            <li><a href="javascript:void(0);" class="exportChart" data-id="jpg">Download JPG image</a></li>
                                            <li><a href="javascript:void(0);" class="exportChart" data-id="pdf">Download PDF Document</a></li>
                                            <li><a href="javascript:void(0);" class="exportChart" data-id="svg">Download SVG vector image</a></li>
                                        </ul>
                                    </div>
                                    {{-- <a href="javascript:void(0);" class="card-options-btn me-3 mt-1"><i class="fe fe-maximize fs-20"></i></a> --}}
                                </div>
                            </div>
                            {{-- <div class="card-body selection-scroll"> --}}
                            <div class="card-body" id="exportChartDiv">
                                <div class="chart-header row export-details d-none">
                                    <div class="col-md-7">
                                        <div class="logo mt-3">
                                            <!--<img src="{{ url('theme-asset/images/icon/fast-forword.png') }}" class="chart-logo" alt="Logo">
                                            <span class="company-name" style="font-size: 1.2em; font-weight: bold;padding-left: 3px;">ADVANTAGE</span>-->
                                            <img src="{{ url('theme-asset/images/brand/logo_dark.png') }}" class="header-brand-img desktop-logo" alt="logo">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="details">
                                            <span class="number-font">POWRBANK: </span> <span>{{ $device_data->name ?? ''}}</span><br>
                                            <span class="number-font">SN. </span> <span>{{ $device_data->serial_no ?? ''}}</span><br>
                                            <span class="number-font">Location: </span> <span>{{ $location ?? ''}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-container">
                                    <canvas id="chartLine" height="500"></canvas>
                                </div>

                                <div class="legend-container">

                                </div>
                                <div class="dimmer chart-loader d-none">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </div>
                                <div class="export-details chart-generated float-end d-none">
                                    <div class="chart-generated">
                                        <span class="number-font">Chart Generated: </span> <span>{{ \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="card chart-height">
                            <div class="card-header pb-2">
                                <h3 class="device-card-title">Selection</h3>
                            </div>
                            <div class="card-body selection-scroll checkbox-main-div">
                                <div class="row">
                                    <?php
                                        if($frequentlyArray):
                                        echo '<h3 class="card-title mt-3 mb-3"> Frequently Used</h3>';
                                        foreach ($frequentlyArray as $key => $value) :
                                        ?>
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input chkBox frequently is_frequently_{{$value['uniq_class']}}" data-uniq-class="{{$value['uniq_class'] ?? ''}}" name="{{$value['name']}}"
                                                    data-text="{{$value['data_value'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-display="{{$value['option_disp'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{ $key == "0" ? 'checked' : ''}}>
                                                <span class="custom-control-label">{{$value['option_disp'] ?? ''}}</span>
                                            </label>
                                        </div>
                                    <?php endforeach; endif; ?>

                                    <?php
                                    $pv = 0;
                                    $grid_genset  = 0;
                                    $battery   = 0;
                                    $alaram_state = 0;
                                    $ess = 0;
                                    $system_calculated = 0;

                                    if (isset($data) && $data) :
                                    foreach ($data as $key => $value) :
                                    $name = '';
                                    if($value["option_type"] == "W"){
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
                                    } else if($value["option_type"] == "S"){
                                        $name = "chk_s[]";
                                    } else if($value["option_type"] == "L"){
                                        $name = "chk_l[]";
                                    } else if($value["option_type"] == "Wh"){
                                        $name = "chk_wh[]";
                                    } else if($value["option_type"] == "%"){
                                        $name = "chk_percentage[]";
                                    } else if($value["option_type"] == "°C"){
                                        $name = "chk_c[]";
                                    } else if($value["option_type"] == "Value 1"){
                                        $name = "chk_value1[]";
                                    } else if($value["option_type"] == "Value 2"){
                                        $name = "chk_value2[]";
                                    }

                                    ?>

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

                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label class="custom-control custom-checkbox">
                                            <?php if(isset($value['is_frequently']) && $value['is_frequently'] == 'true') : ?>
                                                <input type="checkbox" class="custom-control-input chkBox frequently is_frequently_{{$value['uniq_class']}}" data-uniq-class="{{$value['uniq_class'] ?? ''}}" name="{{$name}}"
                                                    data-text="{{$value['data_value'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-display="{{$value['option_disp'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{ $key == "0" ? 'checked' : ''}}>
                                                <span class="custom-control-label">{{$value['option_disp'] ?? ''}}</span>
                                            <?php else : ?>
                                                <input type="checkbox" class="custom-control-input chkBox" name="{{$name}}"
                                                    data-text="{{$value['data_value'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-display="{{$value['option_disp'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{ $key == "0" ? 'checked' : ''}}>
                                                <span class="custom-control-label">{{$value['option_disp'] ?? ''}}</span>
                                            <?php endif; ?>

                                        </label>
                                    </div>

                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                            <div class="processing-overlay" id="processingOverlay">
                                <div><strong> Processing... </strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ROW-1 end -->
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <label for="start_date">From Date</label>
                                <div class="input-group">
                                    <input class="form-control start-datepicker" placeholder="YYYY/MM/DD"
                                        type="text" id="start_date" name="start_date" autocomplete="off"
                                        readonly>
                                    <div class="input-group-text bg-color-black text-white">
                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <label for="end_date">To Date</label>
                                <div class="input-group">
                                    <input class="form-control end-datepicker" placeholder="YYYY/MM/DD"
                                        type="text" id="end_date" name="end_date" autocomplete="off"
                                        readonly>
                                    <div class="input-group-text bg-color-black text-white">
                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 mt-1">
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary search_filter_date_btn mt-5"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1">
                                <button type="button" class="btn btn-icon  btn-primary chart-home-btn mrt-sm-1"><i
                                        class="fa fa-home"></i></button>
                            </div>

                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <div class="form-group form-group-register error-year-id mrt-sm-1">
                                    <select class="form-select filter_dropdown_year" name="filter-dropdown-year" id="filter-dropdown-year">
                                        <option value="">Select Year</option>
                                        @foreach ($years as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <div class="form-group form-group-register error-country_id mrt-sm-1">
                                    <select class="form-select filter_dropdown_month" name="filter_dropdown_month" id="filter_dropdown_month">
                                        <option value="">Select Month</option>
                                        @foreach ($months as $key => $month)
                                        <option value="{{$month}}">{{$key}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary search_btn"><i class="fa fa-search"></i></a>
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary filter-btn" data-value="global">Global</a>
                                <a href="javascript:void(0);" class="btn btn-icon  btn-primary chart-reload-btn"><i class="fa fa-refresh"></i></a>
                                <form action="{{ url('export-chart-csv') }}" id="exportCSV" method="post" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-icon  btn-primary chart-export-csv" title="Click to Export">.CSV</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CONTAINER END -->
        </div>
    </div>
@endsection
@section('page_level_js')
    <!-- CHARTJS JS -->
    <script src="{{ url('theme-asset/plugins/chart/Chart.bundle.js') }} "></script>
    {{-- <script src="{{ url('theme-asset/js/chart.js')}}"></script> --}}

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ url('theme-asset/js/html2canvas.min.js') }}"></script>
    <script src="{{ url('theme-asset/js/html2pdf.bundle.min.js') }}"></script>

    <script type="text/javascript">
        // Hide sidebar

        var  chart_type = 'line';
        const macid     = "{{ $macid }}";
        const ip        = "{{ config('constants.MQTT_IP') }}";
        const port      = {{ config('constants.MQTT_PORT') }};
        var userID      = "{{ $userID }}";
        var is_verified = "{{ $is_verified }}";
        var legendLabel = ['bg-label-1', 'bg-label-2', 'bg-label-3', 'bg-label-4', 'bg-label-5', 'bg-label-6', 'bg-label-7', 'bg-label-8', 'bg-label-9', 'bg-label-10'];
        if(is_verified == 'false')
        {
            $('.machine_verified_btn').removeClass('d-none');
        }

        var options = {
            port: port,
            host: 'ws://' + ip,
            username: "{{ config('constants.MQTT_USERNAME') }}",
            password: "{{ config('constants.MQTT_PASSWORD') }}",
            options: {
                will: {
                    qos: 2
                }
            }
        };
        $(document).ready(function() {
            $('.app-sidebar__toggle').trigger('click');

            var Days = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25,
                26, 27, 28, 29, 30, 31
            ];
            var Month = {
                "1": "January",
                "2": "February",
                "3": "March",
                "4": "April",
                "5": "May",
                "6": "June",
                "7": "July",
                "8": "August",
                "9": "September",
                "10": "October",
                "11": "November",
                "12": "December",
            };

            $(".fc-datepicker").datepicker({
                "setDate": new Date(),
                "autoclose": true,
                dateFormat: 'yy/mm/dd',
            });

            setTimeout(() => {
                $(".fc-datepicker").datepicker("setDate", new Date());
                addDynamicGraphData();
            }, 1000);

            getCurrenTime();

            function getCurrenTime() {
                var mmt = moment();
                var mmtformat = mmt.format();
                var mmtunix = mmt.unix();
                var mmtDate = mmt.format("MMM DD, YYYY");
                var mmtTime = mmt.format("hh:mm a");
                // var mmtdotmated = mmt.format("DD-MM-YYYY hh:mm:ss a");
                $('.machine-updated-date').html('');
                $('.machine-updated-date').html(mmtDate);
                $('.machine-updated-time').html('');
                $('.machine-updated-time').html(mmtTime);
            }

            function tofixNo(value) {
                return Number(value).toFixed(2);
            }

            // ============= Start Chart =================//
            /*LIne-Chart */
            var ctx = document.getElementById("chartLine").getContext('2d');
            var myChart = new Chart(ctx, {
                type: chart_type,
                data: {
                    labels: ["Sun", "Mon", "Tus", "Wed", "Thu", "Fri", "Sat"],
                    datasets: [],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    scales: {
                        x: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: true,
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)'
                            },
                            title: {
                                color: "#1F3D4E",
                                display: true,
                                text: ''
                            }
                        },
                        y: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: true,
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: false,
                                text: '(W)',
                                color: "#1F3D4E"
                            }
                        },
                        y1: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(Hz)',
                                color: '#1F3D4E'
                            },
                        },
                        y2: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(AH)',
                                color: "#1F3D4E"
                            }
                        },
                        y3: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(KWH)',
                                color: '#1F3D4E'
                            },
                        },
                        y4: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(A)',
                                color: "#1F3D4E"
                            }
                        },
                        y5: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(V)',
                                color: "#1F3D4E"
                            }
                        },
                        y6: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(Battery Alarms)',
                                color: "#1F3D4E"
                            }
                        },
                        y7: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(State)',
                                color: "#1F3D4E"
                            }
                        },
                        y8: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(Status Alarms)',
                                color: "#1F3D4E"
                            }
                        },
                        y9: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(Temperature)',
                                color: "#1F3D4E"
                            }
                        },
                        y10: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '(Status)',
                                color: "#1F3D4E"
                            }
                        },
                        y11: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: 'S',
                                color: "#1F3D4E"
                            }
                        },
                        y12: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: 'L',
                                color: "#1F3D4E"
                            }
                        },
                        y13: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: 'Wh',
                                color: "#1F3D4E"
                            }
                        },
                        y14: {
                            ticks: {
                                color: "#1F3D4E",
                                callback: function (value) {
                                    return value + "%";
                                },
                            },
                            display: false,
                            beginAtZero: true,
                            max: 100,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '%',
                                color: "#1F3D4E"
                            }
                        },
                        y15: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: '°C',
                                color: "#1F3D4E"
                            }
                        },
                        y16: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: 'Value 1',
                                color: "#1F3D4E"
                            }
                        },
                        y17: {
                            ticks: {
                                color: "#1F3D4E",
                            },
                            display: false,
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                color: 'rgba(119, 119, 142, 0.2)',
                                drawOnChartArea: true,
                            },
                            title: {
                                display: true,
                                text: 'Value 2',
                                color: "#1F3D4E"
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: "#77778e",
                            },
                            display: false,
                            position:'bottom',
                        },
                    },
                }
            });

            $(document).on('click','.exportChart',function(){
                var _this = $(this);
                var type  = _this.attr('data-id');
                var parentContainer = document.getElementById('exportChartDiv');
                var computedStyles = window.getComputedStyle(parentContainer);
                var oldWidth  = computedStyles.width;
                var oldHeight = computedStyles.height;
                console.log('Computed Width:', computedStyles.width);
                console.log('Computed Height:', computedStyles.height);
                $('.export-details').removeClass('d-none');

                html2canvas(parentContainer,{ useCORS: true }).then(function(canvas) {
                    if(type == 'png'){
                            if($('.card-options-chart-maximize').hasClass('chart-maximize')){
                                // If fullscreen then size set

                                setTimeout(() => {
                                    var url = canvas.toDataURL('image/png');
                                    var link = document.createElement('a');
                                    link.download = 'chart.png';
                                    link.href = url;
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }, 1000);
                            } else {
                                setTimeout(() => {
                                    var url = canvas.toDataURL('image/png');
                                    var link = document.createElement('a');
                                    link.download = 'chart.png';
                                    link.href = url;
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }, 100);
                            }

                    } else if(type == 'jpg'){
                        if($('.card-options-chart-maximize').hasClass('chart-maximize')){
                            // If fullscreen then size set

                            setTimeout(() => {
                                var url = canvas.toDataURL('image/jpeg');
                                var link = document.createElement('a');
                                link.download = 'chart.jpg';
                                link.href = url;
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }, 100);
                        } else {
                            setTimeout(() => {
                                var url = canvas.toDataURL('image/jpeg');
                                var link = document.createElement('a');
                                link.download = 'chart.jpg';
                                link.href = url;
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }, 100);

                        }

                    } else if(type == 'svg'){
                        var width = oldWidth;

                        var svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="'+width+'" height="'+oldHeight+'">' +
                             '<foreignObject width="100%" height="100%">' +
                             '<div xmlns="http://www.w3.org/1999/xhtml">' +
                             '<img src="' + canvas.toDataURL('image/png') + '" width="'+width+'" height="'+oldHeight+'" />' +
                             '</div>' +
                             '</foreignObject>' +
                             '</svg>';

                        // Create Blob from SVG content
                        var blob = new Blob([svgContent], { type: 'image/svg+xml;charset=utf-8' });

                        // Trigger download
                        var url = URL.createObjectURL(blob);
                        var link = document.createElement('a');
                        link.download = 'chart.svg';
                        link.href = url;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else if(type == 'pdf'){
                        if($('.card-options-chart-maximize').hasClass('chart-maximize')){
                            // If fullscreen then size set
                            // parentContainer.style.width  = '950px';
                            // $('.legend-container').css('width', '850px');
                            setTimeout(() => {
                                // html2pdf()
                                // .set({
                                //     margin: 0.5,
                                //     filename: 'chart.pdf',
                                //     image: { type: 'jpeg', quality: 1 },
                                //     html2canvas: { scale: 2, useCORS: true },
                                //     jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
                                // })
                                // .from(parentContainer)
                                // .save();
                                // parentContainer.style.width  = oldWidth;

                                var chartWidth = 1550;
                                html2pdf()
                            .set({
                                margin: 0.5,
                                filename: 'chart.pdf',
                                image: { type: 'jpeg', quality: 2 },
                                html2canvas: { scale: 2, useCORS: true },
                                jsPDF: {
                                    unit: 'px',
                                    format: [chartWidth, 792],
                                    orientation: 'landscape'
                                }
                            })
                            .from(parentContainer)
                            .save();
                            }, 100);
                        } else {
                            html2pdf()
                            .set({
                                margin: 0.5,
                                filename: 'chart.pdf',
                                image: { type: 'jpeg', quality: 2 },
                                html2canvas: { scale: 2, useCORS: true },
                                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
                            })
                            .from(parentContainer)
                            .save();
                        }

                    } else if(type == 'print_chart'){
                        var canvasImage = canvas.toDataURL('image/png');

                        var printWindow = window.open('', '_blank');
                        printWindow.document.open();

                        printWindow.document.write('<html><head><title>Chart Print</title></head><body>');
                        printWindow.document.write('<img src="' + canvasImage + '" style="width:100%;">');
                        printWindow.document.write('</body></html>');
                        printWindow.document.close();

                        setTimeout(() => {
                            printWindow.print();
                        }, 1000);
                    }
                    $('.export-details').addClass('d-none');
                });
            });

            $(".prev").click(function() {
                var date = $('.fc-datepicker').datepicker('getDate', '-1d');
                date.setDate(date.getDate() - 1);
                $('.fc-datepicker').datepicker('setDate', date);

                var html = "";
                html += "<option value=''>Select</option>";
                $('#select-charts').val('line');

                // $('#filter-dropdown').html('');
                // $('#filter-dropdown').html(html);

                $('.filter_dropdown_year').val('');
                $('.filter_dropdown_month').val('');

                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-success');
                $('.filter-btn').addClass('btn-primary');

                $('.filter-error').remove();

                addDynamicGraphData();
            })

            $(".next").click(function() {
                var date = $('.fc-datepicker').datepicker('getDate', '+1d');
                date.setDate(date.getDate() + 1);
                $('.fc-datepicker').datepicker('setDate', date);

                // var html = "";
                // html += "<option value=''>Select</option>";
                // $('#select-charts').val('line');

                // $('#filter-dropdown').html('');
                // $('#filter-dropdown').html(html);

                $('.filter_dropdown_year').val('');
                $('.filter_dropdown_month').val('');

                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-success');
                $('.filter-btn').addClass('btn-primary');
                $('.filter-error').remove();

                addDynamicGraphData();
            });

            var limit = 10;
            var units = [];
            units.push($(':checkbox:first').val());
            $(document).on('change', '.chkBox', function() {
                $('.checkbox-main-div').css('opacity', 0.5);
                $('#processingOverlay').fadeIn();
                $('.checkbox-main-div .chkBox').prop('disabled', true);

                var _this         = $(this);
                var w_count       = $("input[name='chk_w[]']:checked").length;
                var hz_count      = $("input[name='chk_hz[]']:checked").length;
                var ah_count      = $("input[name='chk_ah[]']:checked").length;
                var kwh_count     = $("input[name='chk_kwh[]']:checked").length;
                var A_count       = $("input[name='chk_a[]']:checked").length;
                var V_count       = $("input[name='chk_v[]']:checked").length;
                var Battery_count = $("input[name='chk_battery[]']:checked").length;
                var State_count   = $("input[name='chk_state[]']:checked").length;
                var State_Alarms_count       = $("input[name='chk_status_alarms[]']:checked").length;
                var State_Temperature_count  = $("input[name='chk_temperature[]']:checked").length;
                var State_Battery_count      = $("input[name='chk_status_battry[]']:checked").length;
                var ess_status_count         = $("input[name='chk_ess_status[]']:checked").length;

                var chk_s_count            = $("input[name='chk_s[]']:checked").length;
                var chk_l_count            = $("input[name='chk_l[]']:checked").length;
                var chk_wh_count           = $("input[name='chk_wh[]']:checked").length;
                var chk_percentage_count   = $("input[name='chk_percentage[]']:checked").length;
                var chk_c_count            = $("input[name='chk_c[]']:checked").length;

                var chk_value1_count       = $("input[name='chk_value1[]']:checked").length;
                var chk_value2_count       = $("input[name='chk_value2[]']:checked").length;

                var chkSelected   = _this.attr('data-text');
                var selected_key  = _this.attr('data-key');
                var selected_type = _this.val();
                var selected_ckh_count = $('.chkBox:checked').length;
                var is_valid      = true;
                var units = [];
                $('.chkBox:checked').each(function() {
                    var checkboxValue = $(this).val();
                    if(checkboxValue !== 'on' && units.length < 5){
                        if (!units.includes(checkboxValue)) {
                            // Add the unique value
                            units.push(checkboxValue);
                        }
                    }
                });

                if(units.length > 0){
                    if ($.inArray(selected_type, units) !== -1) {
                        is_valid = true;
                    } else {
                        is_valid = false;
                        _this.prop('checked', false);
                        if(units.length > 4){
                            $.growl.notice({
                                title: "Success",
                                message: 'You can select maximum of 5 units.',
                            });
                        }
                    }
                }

                if (selected_type == "W") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = hz_count + w_count + ah_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + ess_status_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ess_status_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        // addData('day', _this.attr('data-value'), chkSelected,selected_type,selected_key);
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Hz") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = hz_count + w_count + ah_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        // addData('day', _this.attr('data-value'), chkSelected,selected_type,selected_key);
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "AH") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "KWH") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = kwh_count + ah_count + hz_count + w_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "A") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = A_count + ah_count + hz_count + w_count + kwh_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "V") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = V_count + ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Battery") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = Battery_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "State") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_Alarms_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Status_Alarms") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Alarms_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Temperature_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Temperature") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Temperature_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Battery_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Status_Battry") {
                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = State_Battery_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "ess_status") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = ess_status_count + ah_count + hz_count + w_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "S") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_s_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "L") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_l_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_wh_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Wh") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_wh_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_l_count + chk_percentage_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "%") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_percentage_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_l_count + chk_wh_count + chk_c_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "°C") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_c_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_value1_count + chk_value2_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Value 1") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_value1_count + chk_value2_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value2_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

                if (selected_type == "Value 2") {

                    if (selected_ckh_count > limit) {
                        _this.prop('checked', false);
                        $.growl.notice({
                            title: "Success",
                            message: 'You can select maximum of ' + limit + ' checkbox.',
                        });
                    } else {
                        var counts = chk_value2_count + chk_value1_count + V_count+ ah_count + hz_count + w_count + kwh_count + A_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count +  chk_s_count + chk_l_count + chk_wh_count + chk_percentage_count + chk_c_count;
                        if (counts > 10 && w_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && hz_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && ah_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && kwh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && A_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && V_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Alarms_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Temperature_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && State_Battery_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_s_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_l_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_wh_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_percentage_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_c_count > 0) {
                            _this.prop('checked', false);
                        }
                        else if (counts > 10 && chk_value1_count > 0) {
                            _this.prop('checked', false);
                        }
                    }

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
                    }
                }

            });

            // setDaysInDropdown(Days);
            function setDaysInDropdown(data) {
                var html = "";
                html += "<option value=''>Select</option>";
                $.each(data, function(key, val) {
                    html += '<option value="' + val + '">' + val + '</option>';
                });
                $('#filter-dropdown').html('');
                $('#filter-dropdown').html(html);
            }

            function setMonthInDropdown(data) {
                var html = "";
                html += "<option value=''>Select</option>";
                $.each(data, function(key, val) {
                    html += '<option value="' + key + '">' + val + '</option>';
                });
                $('#filter-dropdown').html('');
                $('#filter-dropdown').html(html);
            }

            function setYearsInDropdown() {
                var html = "";
                html += "<option value=''>Select</option>";
                var now_year    = new Date().getFullYear();
                var last_year   = new Date().getFullYear()-1;
                var s_last_year = new Date().getFullYear()-2;
                html += '<option value="' + now_year + '">' + now_year + '</option>';
                html += '<option value="' + last_year + '">' + last_year + '</option>';
                html += '<option value="' + s_last_year + '">' + s_last_year + '</option>';
                $('#filter-dropdown').html('');
                $('#filter-dropdown').html(html);
            }

            $(document).on('click', '.filter-btn', function() {
                var _this = $(this);
                var value = _this.attr('data-value');
                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-success');
                $('.filter-btn').addClass('btn-primary');

                _this.removeClass('btn-primary');
                _this.addClass('btn-success');
                _this.addClass('active-btn');
                if (value == "days") {
                    setDaysInDropdown(Days);
                } else if (value == "month") {
                    setMonthInDropdown(Month);
                } else if (value == "year") {
                    setYearsInDropdown();
                } else if(value == "global"){
                    $('.filter_dropdown_year').val('');
                    $('.filter_dropdown_month').val('');

                    // clear Dates
                    $('#start_date').val('');
                    $('#end_date').val('');

                    $('.filter-error').remove();
                    addDynamicGraphData();
                }

                setTimeout(() => {
                    if($('#filter-dropdown').val() == '' && value !== "global")
                    {
                        $('.filter-error').remove();
                        $('.error-country_id').after("<p class='text-danger filter-error'>Please select option</p>");
                        // $.growl.notice({
                        //     title: "Info",
                        //     message: "Please Select dropdown option."
                        // });
                    }
                }, 1000);

            });

            $(document).on('change', '.filter_dropdown', function() {
                var _this = $(this);
                var value = _this.val();
                if(value)
                {
                    $('.filter-error').remove();
                    addDynamicGraphData();
                }
            });

            $(document).on('change', '.filter_dropdown_year', function() {
                var _this = $(this);
                var value = _this.val();
                if(value)
                {
                    $('.filter-error').remove();
                }
                // clear Dates
                $('#start_date').val('');
                $('#end_date').val('');
            });
            $(document).on('change', '.filter_dropdown_month', function() {
                var _this = $(this);
                var value = _this.val();
                // clear Dates
                $('#start_date').val('');
                $('#end_date').val('');
            });

            $(document).on('click','.search_btn', function(){
                var year  = $('.filter_dropdown_year').val();
                var month = $('.filter_dropdown_month').val();
                if(year){
                    addDynamicGraphData();
                } else {
                    $('.filter-error').remove();
                    $('.error-year-id').after("<p class='text-danger filter-error'>Please select option</p>");
                }
            });

            function showHideLabels() {
                var dtset = myChart.data.datasets;
                if (dtset.length > 0) {
                    var html = '';
                    $.each(dtset,function(key,val){
                        html += '<label class="colorinput me-3">';
                        html += '<input name="color" type="checkbox" value="" id="legend-dataset'+key+'" class="colorinput-input" checked>';
                        html += '<span class="colorinput-color '+legendLabel[key]+'"></span>';
                        html += '<span class="colorinput-label">'+val.label+'</span>';
                        html += '</label>';
                    });
                    $('.legend-container').html('');
                    $('.legend-container').html(html);
                } else {
                    $('.legend-container').html('');
                }
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

                var chk_s_count            = $("input[name='chk_s[]']:checked").length;
                var chk_l_count            = $("input[name='chk_l[]']:checked").length;
                var chk_wh_count           = $("input[name='chk_wh[]']:checked").length;
                var chk_percentage_count   = $("input[name='chk_percentage[]']:checked").length;
                var chk_c_count            = $("input[name='chk_c[]']:checked").length;

                var chk_value1_count       = $("input[name='chk_value1[]']:checked").length;
                var chk_value2_count       = $("input[name='chk_value2[]']:checked").length;

                if (w_count <= 0) {
                    myChart.options.scales.y.title.display = false;
                    myChart.options.scales.y.display = false;
                } else {
                    myChart.options.scales.y.title.display = true;
                    myChart.options.scales.y.display = true;
                }

                if (hz_count <= 0) {
                    myChart.config.options.scales.y1.display = false;
                } else {
                    myChart.config.options.scales.y1.display = true;
                }

                if (ah_count <= 0) {
                    myChart.config.options.scales.y2.display = false;
                } else {
                    myChart.config.options.scales.y2.display = true;
                }

                if (kwh_count <= 0) {
                    myChart.config.options.scales.y3.display = false;
                } else {
                    myChart.config.options.scales.y3.display = true;
                }

                if (A_count <= 0) {
                    myChart.config.options.scales.y4.display = false;
                } else {
                    myChart.config.options.scales.y4.display = true;
                }

                if (V_count <= 0) {
                    myChart.config.options.scales.y5.display = false;
                } else {
                    myChart.config.options.scales.y5.display = true;
                }

                if (Battery_count <= 0) {
                    myChart.config.options.scales.y6.display = false;
                } else {
                    myChart.config.options.scales.y6.display = true;
                }

                if (State_count <= 0) {
                    myChart.config.options.scales.y7.display = false;
                } else {
                    myChart.config.options.scales.y7.display = true;
                }

                if (State_Alarms_count <= 0) {
                    myChart.config.options.scales.y8.display = false;
                } else {
                    myChart.config.options.scales.y8.display = true;
                }

                if (State_Temperature_count <= 0) {
                    myChart.config.options.scales.y9.display = false;
                } else {
                    myChart.config.options.scales.y9.display = true;
                }

                if (State_Battery_count <= 0 && ess_status_count <= 0) {
                    myChart.config.options.scales.y10.display = false;
                } else {
                    myChart.config.options.scales.y10.display = true;
                }

                if (chk_s_count <= 0) {
                    myChart.config.options.scales.y11.display = false;
                } else {
                    myChart.config.options.scales.y11.display = true;
                }

                if (chk_l_count <= 0) {
                    myChart.config.options.scales.y12.display = false;
                } else {
                    myChart.config.options.scales.y12.display = true;
                }

                if (chk_wh_count <= 0) {
                    myChart.config.options.scales.y13.display = false;
                } else {
                    myChart.config.options.scales.y13.display = true;
                }

                if (chk_percentage_count <= 0) {
                    myChart.config.options.scales.y14.display = false;
                } else {
                    myChart.config.options.scales.y14.display = true;
                }

                if (chk_c_count <= 0) {
                    myChart.config.options.scales.y15.display = false;
                } else {
                    myChart.config.options.scales.y15.display = true;
                }

                if (chk_value1_count <= 0) {
                    myChart.config.options.scales.y16.display = false;
                } else {
                    myChart.config.options.scales.y16.display = true;
                }

                if (chk_value2_count <= 0) {
                    myChart.config.options.scales.y17.display = false;
                } else {
                    myChart.config.options.scales.y17.display = true;
                }

                myChart.update();
            }

            function removeData(selected) {
                var chartData = myChart.data.datasets;

                $.each(chartData, function(key, val) {
                    if (val && val.line_id == selected) {
                        myChart.data.datasets.splice(key, 1);
                        myChart.update();
                    }
                });

                $('.checkbox-main-div').css('opacity', 1);
                $('#processingOverlay').fadeOut();
                $('.checkbox-main-div .chkBox').prop('disabled', false);

                showHideLabels();
            }

            // Date picker On change
            $(document).on('change', '#chart-date', function() {
                var _this = $(this);

                // var html = "";
                // html += "<option value=''>Select</option>";
                // $('#select-charts').val('line');

                // $('#filter-dropdown').html('');
                // $('#filter-dropdown').html(html);

                $('.filter_dropdown_year').val('');
                $('.filter_dropdown_month').val('');

                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-success');
                $('.filter-btn').addClass('btn-primary');
                $('.filter-error').remove();

                addDynamicGraphData();
            });

            function addDynamicGraphData() {
                const barColors   = ['#AAC760', '#90ABB5', '#1F3D4E', '#0F203D', '#4E1F4A', '#2F3F11', '#506849', '#D84B2B', '#FF7E27', '#FFCE00'];

                // var dropworn_val    = $("#filter-dropdown").val();
                var year            = $(".filter_dropdown_year").val();
                var month           = $(".filter_dropdown_month").val();
                var filter_type     = $('.filter-btn.active-btn').attr('data-value');
                var current_date    = $("#chart-date").val();
                var selected_option = [];
                var chkBox          = $('.chkBox:checked');
                var chkCount        = $('.chkBox:checked').length;
                var start_date      = $("#start_date").val();
                var end_date        = $("#end_date").val();
                if(chkBox)
                {
                    chkBox.each(function (j) {
                        var data = {};
                        data['selected']       = $(this).attr('data-text');
                        data['selected_key']   = $(this).attr('data-key');
                        data['selected_type']  = $(this).val();
                        data['chart_type']     = $(this).attr('data-value');
                        data['display_option'] = $(this).attr('data-display');
                        data['current_date']   = current_date;
                        data['macid']          = macid;
                        if(year && month)
                        {
                            data['filter_type']   = 'year_month';
                        } else if(year){
                            data['filter_type']   = 'year';
                        } else {
                            data['filter_type']   = (filter_type && filter_type == 'global' ? 'global' : 'interval');
                        }
                        data['filter_value_year']   = year;
                        data['filter_value_month']  = month;
                        data['filter_start_date']   = start_date;
                        data['filter_end_date']     = end_date;
                        selected_option.push(data);
                    });
                }

                if(chkCount > 0)
                {
                    $('.chart-container').addClass('d-none');
                    $('.legend-container').addClass('d-none');
                    $('.chart-loader').removeClass('d-none');
                    myChart.data.datasets = [];
                    myChart.update();
                    $.ajax({
                        url: '{{ url('get-charts-data') }}',
                        type: "POST",
                        data: {'chart' : selected_option},
                        dataType: 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            var days_diff = data.days_diff;
                            if(days_diff > 0){
                                days_diff =  days_diff + 1;
                                var tickLimit = days_diff * 2;
                                myChart.options.scales.x.ticks.maxTicksLimit = tickLimit
                            } else {
                                delete myChart.options.scales.x.ticks.maxTicksLimit;
                            }

                            myChart.data.labels = data.labels;
                            myChart.options.scales.x.title.text = data.x_axis_label;
                            if(data.dataset)
                            {
                                var html = '';
                                $.each(data.dataset,function(key,val){
                                    val.borderColor = barColors[key];
                                    val.pointBackgroundColor = barColors[key];
                                    val.backgroundColor = barColors[key];
                                    val.lineTension = 0;
                                    myChart.data.datasets.push(val);

                                    html += '<label class="colorinput me-3">';
                                    html += '<input name="color" type="checkbox" value="" id="legend-dataset'+key+'" class="colorinput-input" checked>';
                                    html += '<span class="colorinput-color '+legendLabel[key]+'"></span>';
                                    html += '<span class="colorinput-label">'+val.label+'</span>';
                                    html += '</label>';
                                });
                                $('.legend-container').html('');
                                $('.legend-container').html(html);
                            }
                            myChart.update();
                            setTimeout(() => {
                                $('.chart-loader').addClass('d-none');
                                $('.chart-container').removeClass('d-none');
                                $('.legend-container').removeClass('d-none');
                                $('.checkbox-main-div').css('opacity', 1);
                                $('#processingOverlay').fadeOut();
                                $('.checkbox-main-div .chkBox').prop('disabled', false);
                            }, 1000);
                            showHideLabels();
                            if(days_diff > 0){
                                updateLableInterval(tickLimit);
                            }
                        }
                    });
                }
            }

            $(document).on('click','.chart-reload-btn',function(){
                $('.chart-container').addClass('d-none');
                $('.chart-loader').removeClass('d-none');
                $('.legend-container').addClass('d-none');
                // myChart.data.datasets = [];
                // myChart.data.labels = ["Sun", "Mon", "Tus", "Wed", "Thu", "Fri", "Sat"];
                // myChart.update();

                setTimeout(() => {
                    $('.chart-loader').addClass('d-none');
                    $('.chart-container').removeClass('d-none');
                    $('.legend-container').removeClass('d-none');

                    var html = "";
                    html += "<option value=''>Select</option>";
                    $('#select-charts').val('line');

                    $('.filter_dropdown_year').val('');
                    $('.filter_dropdown_month').val('');

                    $('#start_date').val('');
                    $('#end_date').val('');

                    $('.filter-btn').removeClass('active-btn');
                    $('.filter-btn').removeClass('btn-success');
                    $('.filter-btn').addClass('btn-primary');
                    $('.chkBox').prop('checked',false);
                    $('.chkBox:checkbox:first').prop('checked',true);
                    $(".fc-datepicker").datepicker("setDate", new Date());
                    $('.filter-error').remove();
                    addDynamicGraphData();

                }, 1000);
            });

            // Home BTN
            $(document).on('click','.chart-home-btn',function(){
                var html = "";
                html += "<option value=''>Select</option>";

                $('.filter_dropdown_year').val('');
                $('.filter_dropdown_month').val('');

                // clear Dates
                $('#start_date').val('');
                $('#end_date').val('');

                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-success');
                $('.filter-btn').addClass('btn-primary');

                $(".fc-datepicker").datepicker("setDate", new Date());
                $('.filter-error').remove();
                addDynamicGraphData();
            });

            // charts Types changes
            $(document).on('change','#select-charts',function(){
                var _this = $(this);
                var chart_type = _this.val();
                if(chart_type)
                {
                    myChart.config.type = chart_type;
                    addDynamicGraphData();
                }
            });

            // Machine verified
            $(document).on('click','.machine_verified_btn',function(){
                var _this = $(this);
                if(macid)
                {
                    _this.prop('disabled', true).html("<i class='fa fa-refresh fa-spin'> </i>  Processing...");

                    $.ajax({
                        url: '{{ url("verified-machine") }}',
                        type: 'POST',
                        data: {
                            'macid' : macid,
                        },
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(resp) {
                            if (resp) {
                                try {
                                    resp = JSON.parse(resp)
                                } catch (e) {}
                                if (userID) {
                                    notif({
                                        type: "warning",
                                        msg: "<b> Please wait... We are verifying this POWRBANK with ECM. It may take 15-20 seconds </b>",
                                        position: "center",
                                        width: 800,
                                        autohide: true,
                                        timeout: 15000,
                                        opacity: 1
                                    });
                                    setInterval(() => {
                                            var is_verified = checkIsDeviceVerified(userID);
                                    }, 3000);

                                    setTimeout(() => {

                                        $.growl.error({
                                            title: "Error",
                                            message: "Sorry !!!  We could not verify your POWRBANK right now. please try again later. Make sure your ECM is ON and have proper internet connectivity.",
                                            size: 'large',
                                            duration: 15000,
                                        });

                                        setTimeout(() => {
                                            _this.prop('disabled', false).html("<span> <img class='device-btn-icon' src='{{ url('theme-asset/images/icon/verified-icon.png') }}' alt='verify-machine-icon'> </span> Verify Machine");
                                            location.reload();
                                        }, 10000);
                                    }, 15000);

                                }

                            }
                        }
                    })
                }
            });

            function checkIsDeviceVerified(id) {
                if(id)
                {
                    $.ajax({
                        url: '{{url("check-device-verified")}}',
                        type: "POST",
                        data:  {'id' : id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            try {
                                data = JSON.parse(data);
                            } catch(e){}
                            if(data.status == 'true')
                            {
                                $.growl.notice({
                                    title: "Success",
                                    message: "POWRBANK verified successfully."
                                });

                                setTimeout(() => {
                                    // location = "{{url('/system-overview')}}";
                                    location.reload();
                                }, 5000);

                            } else {
                                return 'false';
                            }
                        }
                    });
                }
            }

            $(document).on('click','.card-options-btn',function(){
                var _this = $(this);
                if($('.card-options-chart-maximize').hasClass('chart-maximize'))
                {
                    $('#exportChartDiv').css('width', '');

                    $('.card-options-chart-maximize').removeClass('chart-maximize');
                    _this.find('i').removeClass('fe-minimize');
                    _this.find('i').addClass('fe-maximize');
                    $('#chartLine').css('height', '500');

                    _this.text('View in Full Screen');
                    setTimeout(() => {
                        if($('.sidebar-mini').hasClass('sidenav-toggled')){
                            //
                        } else {
                            $('.app-sidebar__toggle').trigger('click');
                        }
                    }, 1000);
                } else {
                    $('.card-options-chart-maximize').addClass('chart-maximize');
                    _this.find('i').removeClass('fe-maximize');
                    _this.find('i').addClass('fe-minimize');
                    _this.text('View in Normal Screen');
                    setTimeout(() => {
                        $('#chartLine').css('height', '430');
                    }, 100);
                }
            });

            $(document).on('change','.colorinput-input',function(){
                const dataset0Checkbox = $('#legend-dataset0');
                const dataset1Checkbox = $('#legend-dataset1');
                const dataset2Checkbox = $('#legend-dataset2');
                const dataset3Checkbox = $('#legend-dataset3');
                const dataset4Checkbox = $('#legend-dataset4');
                const dataset5Checkbox = $('#legend-dataset5');
                const dataset6Checkbox = $('#legend-dataset6');
                const dataset7Checkbox = $('#legend-dataset7');
                const dataset8Checkbox = $('#legend-dataset8');
                const dataset9Checkbox = $('#legend-dataset9');

                if(myChart.data.datasets[0]){
                    myChart.data.datasets[0].hidden = !dataset0Checkbox.prop('checked');
                }

                if(myChart.data.datasets[1]){
                    myChart.data.datasets[1].hidden = !dataset1Checkbox.prop('checked');
                }

                if(myChart.data.datasets[2]){
                    myChart.data.datasets[2].hidden = !dataset2Checkbox.prop('checked');
                }

                if(myChart.data.datasets[3]){
                    myChart.data.datasets[3].hidden = !dataset3Checkbox.prop('checked');
                }

                if(myChart.data.datasets[4]){
                    myChart.data.datasets[4].hidden = !dataset4Checkbox.prop('checked');
                }

                if(myChart.data.datasets[5]){
                    myChart.data.datasets[5].hidden = !dataset5Checkbox.prop('checked');
                }

                if(myChart.data.datasets[6]){
                    myChart.data.datasets[6].hidden = !dataset6Checkbox.prop('checked');
                }

                if(myChart.data.datasets[7]){
                    myChart.data.datasets[7].hidden = !dataset7Checkbox.prop('checked');
                }

                if(myChart.data.datasets[8]){
                    myChart.data.datasets[8].hidden = !dataset8Checkbox.prop('checked');
                }

                if(myChart.data.datasets[9]){
                    myChart.data.datasets[9].hidden = !dataset9Checkbox.prop('checked');
                }

                myChart.update();
            });

            // New Filters
             $('#start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // Initialize the end date picker
            $('#end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#start_date').on('changeDate', function (e) {
                // $('#end_date').datepicker('update', e.date);
                $('#end_date').datepicker('setStartDate', e.date);
                $('.filter-error-dates').remove();
            });

            $(document).on('click','.search_filter_date_btn',function(){


                var start_date  = $('#start_date').val();
                var end_date    = $('#end_date').val();
                if(start_date){
                    // First remove other filters
                    $('.filter_dropdown_year').val('');
                    $('.filter_dropdown_month').val('');

                    $('.filter-btn').removeClass('active-btn');
                    $('.filter-btn').removeClass('btn-success');
                    $('.filter-btn').addClass('btn-primary');
                    $('.filter-error').remove();

                    addDynamicGraphData();
                } else {
                    $('.filter-error-dates').remove();
                    $('.start-datepicker').parent('.input-group').after("<p class='text-danger filter-error-dates'>Please select date</p>");
                }
            });

            $(document).on('click','.frequently',function(){
                var data_text = $(this).attr('data-uniq-class');
                var $currentCheckbox = $(this);
                $('.is_frequently_'+data_text).not($currentCheckbox).prop('checked', false);
            });

            $('#exportCSV').submit(function(eventObj) {
                var year            = $(".filter_dropdown_year").val();
                var month           = $(".filter_dropdown_month").val();
                var filter_type     = $('.filter-btn.active-btn').attr('data-value');
                var current_date    = $("#chart-date").val();
                var chkBox          = $('.chkBox:checked');
                var start_date      = $("#start_date").val();
                var end_date        = $("#end_date").val();
                var selected_option = [];

                chkBox.each(function() {
                    var data = {};
                    data['selected']       = $(this).attr('data-text');
                    data['selected_key']   = $(this).attr('data-key');
                    data['selected_type']  = $(this).val();
                    data['chart_type']     = $(this).attr('data-value');
                    data['display_option'] = $(this).attr('data-display');
                    data['current_date']   = current_date;
                    data['macid']          = macid;

                    if (year && month) {
                        data['filter_type'] = 'year_month';
                    } else if (year) {
                        data['filter_type'] = 'year';
                    } else {
                        data['filter_type'] = (filter_type && filter_type == 'global') ? 'global' : 'today';
                    }

                    data['filter_value_year']  = year;
                    data['filter_value_month'] = month;
                    data['filter_start_date']  = start_date;
                    data['filter_end_date']    = end_date;

                    selected_option.push(data);
                });
                console.log(selected_option);
                var selected_option_json = JSON.stringify(selected_option);
                $(this).append('<input type="hidden" name="chart_data" value=\'' + selected_option_json + '\' />');
                return true;
            });

            function formatDate(dateString) {
                const date = new Date(dateString);
                const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
                const month = months[date.getMonth()];
                const day = date.getDate();
                const formattedDate = (day < 10 ? '0' : '') + day + '.' + month;
                return formattedDate;
            }

            function updateLableInterval(tickLimit){
                const meta = myChart.getDatasetMeta(0);
                const xScale = meta.xScale;
                const labels = xScale.getLabels();
                const maxTicksLimit = xScale.options.ticks.maxTicksLimit;

                const modifiedLabels = [];
                const visibleLabels = [];

                for (let i = 0; i < labels.length; i++) {
                    let modifiedLabel = labels[i];
                    modifiedLabels.push(modifiedLabel);

                    // Check if the label is within the visible range
                    if (i % Math.ceil(labels.length / maxTicksLimit) === 0) {
                        visibleLabels.push(modifiedLabel);
                    }
                }
                var ctn = 1;
                for (let i = 0; i < modifiedLabels.length; i++) {

                    if ($.inArray(modifiedLabels[i], visibleLabels) !== -1) {


                        if (ctn % 2 == 0) {
                            modifiedLabels[i] = '12:00';
                        } else {
                            modifiedLabels[i] = formatDate(modifiedLabels[i]);
                        }
                        ctn = ctn + 1;
                    }
                }
                myChart.data.labels = modifiedLabels;
                myChart.update();
            }

        }); // Ready
    </script>
@endsection
