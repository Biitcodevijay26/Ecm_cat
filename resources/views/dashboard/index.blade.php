@extends('front.layout_admin.app')

@section('page_level_css')
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<style>
     .dataTables_paginate  {
        float: right;
    }
    .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
    .table td, .table th {
        padding: .75rem 0.75rem;
    }
    .chart-card-height { height: 410px;}
    .badge-custom{
        position: absolute;
        right: 8px;
        /* width: 65px; */
        width: auto;
    }
    .apexcharts-menu-icon{
        display: none;
    }
    .ch{
        min-height: 340px;
    }
    .mysaving-border {
        border: 2px solid #1F3D4E !important;
    }
    .number-size {
        font-size: 18px !important;
    }
    .bg-custom {
        border-radius: 3px;
        background-color: #F2F3F9;

    }
    .alert-pl{
        padding-left: 30px;
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
        background: #ff0000 !important;
        color: #fff !important;
    }
    .bg-label-2 {
        background: #00eaff !important;
        color: #fff !important;
    }
    .bg-label-3 {
        background: #aa00ff !important;
        color: #fff !important;
    }
    .bg-label-4 {
        background: #bfff00 !important;
        color: #fff !important;
    }
    #loaderDB {
        border: 12px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 60px;
        height: 60px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin-left: 50%;
        margin-top: 5%;
    }
    .mysaving-icon {
        width: 64px;
    }

    .mysaving-number-font {
        font-weight: 700 !important;
    }
    .label-number-size{
        font-size: 14px !important;
    }
    .label-number-font {
        font-weight: 600 !important;
    }
    .share-popup {
      position: absolute;
      top: -60px; /* Adjusted for better positioning */
      right: 12px;
      width: 35%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      display: none;
      z-index: 999; /* Ensure the popup is above other content */
    }
    .share-popup.show {
      display: block;
    }
    .share-popup ul {
      list-style-type: none;
      /* padding: 0; */
      padding: 0 5px 0px 15px;
      margin: 0;
    }
    .share-popup li {
      display: inline-block; /* Display icons horizontally */
      margin-right: 16px; /* Adjust margin between icons */
    }
    .share-popup a {
      display: block;
      padding: 5px;
      text-align: center;
    }
    .share-icon {
      cursor: pointer;
    }

    .share-popup a#closePopup {
        position: relative;
        float: left;
        color: #000;
        text-decoration: none;
        margin-top: -10px;
        margin-right: 35px;
    }

    .share-popup span.share-text {
        display: block;
        margin: 0 auto;
    }
</style>
@endsection
@section('content')
<?php
    $company_login_id = session()->get('company_login_id');
?>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                                <i class="fe fe-plus"></i>
                            </span> Add Account
                    </a> --}}
                    {{-- <a href="javascript:void(0);" class="btn btn-success btn-icon text-white">
                        <span>
                                <i class="fe fe-log-in"></i>
                            </span> Export
                    </a> --}}
                    <select name="filter-dashboard" id="filterDashboard" class="select2" style="width: 300px;">
                        <option value="" selected>ALL</option>
                        <optgroup label="Groups">
                            <?php
                                if ($group_dropdown && count($group_dropdown) > 0) :
                                foreach ($group_dropdown as $key => $group) :
                                $selected = '';
                                if (isset($filter_id) && $filter_id == $group->_id) {
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="{{$group->_id ?? ''}}" {{$selected}} data-name="group" data-macid="">{{ $group->name ?? ''}}</option>
                            <?php endforeach; endif; ?>
                        </optgroup>
                        <optgroup label="POWRBANK">
                            <?php
                                if ($powerbank_dropdown && count($powerbank_dropdown) > 0) :
                                foreach ($powerbank_dropdown as $key => $powerbank) :
                                $selected1 = '';
                                if (isset($filter_id) && $filter_id == $powerbank->_id) {
                                    $selected1 = 'selected';
                                }
                            ?>
                            <option value="{{$powerbank->_id ?? ''}}" {{$selected1}} data-name="powerbank" data-macid="{{$powerbank->macid ?? ''}}">{{ $powerbank->name ?? ''}}</option>
                            <?php endforeach; endif; ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            @if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $admin_role)
            <!-- Warning Message Start -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-wrap mb-4">
                                <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                                    <strong>Notice</strong>
                                    <hr class="message-inner-separator">
                                    <p>Admin will approve your account then access all features of this portal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Warning Message End -->
            @endif

            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="card-body ch account_summery_body">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-dark number-font text-uppercase">Account Summary <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Account Summary" data-bs-original-title="Account Summary"></i></h4>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <h5 class="mb-3"><strong>Users: <span class="users_count">NA</span></strong></h5>
                                        <h5 class="mb-3"><strong>Groups: <span class="cluster_count">NA</span></strong></h5>
                                        <h5 class="mb-3"><strong>Total POWRBANK: <span class="device_count">NA</span></strong></h5>
                                    </div>
                                    <div class="row mt-5">
                                        <h5 class="mb-4"><span class="dot-label bg-verified me-2"></span><strong> Verified POWRBANK: <span class="verified_device_count">NA</span></strong></h5>
                                        <h5 class="mb-4"><span class="dot-label bg-unverified me-2"></span><strong> Unverified POWRBANK: <span class="inverified_device_count">NA</span></strong></h5>
                                        <h5 class="mb-4"><span class="dot-label bg-connected me-2"></span><strong> Connected POWRBANK: <span class="connected_device_count">NA</span> </strong></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="card-body ch">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-dark number-font text-uppercase">ALERTS <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Alerts" data-bs-original-title="Alerts"></i></h4>
                                        </div>
                                    </div>
                                    <div class="row mt-4" style="height: 240px; overflow:auto;">
                                        <div class="dashboard_alerts">

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4 pie-chart-div">
                            <div id="sharePopup" class="share-popup">
                                <a href="javascript:void(0);" id="closePopup"><i class="fa fa-close"></i></a>
                                <span class="share-text">Share</span>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="text-primary shareOnLinkedin"><i class="bi bi-linkedin fs-20"></i></a>
                                    </li>

                                    <li>
                                        <a href="javascript:void(0);" class="text-primary shareOnFacebook"><i class="bi bi-facebook fs-20"></i></a>
                                    </li>

                                    {{-- <li>
                                        <a href="javascript:void(0);" class="text-primary shareOnTwitter"><i class="bi bi-twitter fs-20"></i></a>
                                    </li> --}}
                                </ul>
                            </div>
                            <div class="card overflow-hidde mysaving-border" id="exportMySaving">
                                <div class="card-body ch">
                                    <div class="row mb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-10">
                                            <h4 class="text-dark number-font text-uppercase">My Savings <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="My Saving" data-bs-original-title="My Saving"></i></h4>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-2">
                                            <a href="javascript:void(0);"  id="shareButton"><i class="fa fa-share-alt fs-20"></i></a>
                                        </div>
                                    </div>
                                    <div class="my_saving">
                                        <div class="row mb-4  bg-custom">
                                            <div class="col-4 col-xl-4">
                                                <img src="{{ url('theme-asset/images/icon/CO2-png.png') }}" alt="total Fule saving" class="mysaving-icon">
                                            </div>
                                            <div class="col-8 col-xl-8 mt-4">
                                               <span class="total-co2 mt-2 number-size mysaving-number-font">0 </span> <span class="total-co2-text mt-2 label-number-size  label-number-font"> LBS OF CO2</span>
                                            </div>
                                        </div>
                                        <div class="row mb-4 bg-custom">
                                            <div class="col-4 col-xl-4">
                                                <img src="{{ url('theme-asset/images/icon/fuel-consumption-png.png') }}" alt="total Fule saving" class="mysaving-icon">
                                            </div>
                                            <div class="col-8 col-xl-8 mt-4">
                                                 <span class="total-fule-saving mt-2 number-size mysaving-number-font">0 </span> <span class="total-fule-saving-text mt-2 label-number-size label-number-font"> GAL OF FUEL</span>
                                            </div>
                                        </div>
                                        <div class="row bg-custom">
                                            <div class="col-4 col-xl-4">
                                                <img src="{{ url('theme-asset/images/icon/Money_Square_ElectricBlue-png.png') }}" alt="total Fule saving" class="mysaving-icon">
                                            </div>
                                            <div class="col-8 col-xl-8 mt-4">
                                                <span class="total-fuel-consumption-symbol mt-2 number-size mysaving-number-font">$</span><span class="total-fuel-consumption mt-2 number-size mysaving-number-font">0 </span> <span class="total-fuel-consumption-text mt-2 label-number-size label-number-font"> IN FUEL COST</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card chart-card-height">
                        <div class="card-header">
                            <div class="col-sm-9">
                                <h3 class="card-title text-dark number-font text-uppercase">Total Energy <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Total Energy" data-bs-original-title="Total Energy"></i></h3>
                            </div>
                            <div class="col-sm-3">
                                <select name="filter_type" id="filter_type" class="form-select">
                                    {{-- <option value="all">ALL</option>
                                    <option value="year">Year</option>
                                    <option value="month">Month</option>
                                    <option value="today" selected>Today</option> --}}
                                    <option value="today" selected>Today</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                </select>
                            </div>

                        </div>
                        <div class="card-body pb-0">
                            <div class="legend-container">
                                {{-- <label class="colorinput me-3">
                                    <input name="color" type="checkbox" value="" id="legend-dataset1" class="colorinput-input" checked>
                                    <span class="colorinput-color bg-chart-acsolar"></span>
                                    <span class="colorinput-label">AC Solar</span>
                                </label>
                                <label class="colorinput me-3">
                                    <input name="color" type="checkbox" value="" id="legend-dataset2" class="colorinput-input" checked>
                                    <span class="colorinput-color bg-chart-dcsolar"></span>
                                    <span class="colorinput-label">DC Solar</span>
                                </label> --}}
                                <label class="colorinput me-3">
                                    <input name="color" type="checkbox" value="" id="legend-dataset2" class="colorinput-input" checked>
                                    <span class="colorinput-color bg-chart-dcsolar"></span>
                                    <span class="colorinput-label">Solar</span>
                                </label>
                                <label class="colorinput me-3">
                                    <input name="color" type="checkbox" value="" id="legend-dataset3" class="colorinput-input" checked>
                                    <span class="colorinput-color bg-chart-generator"></span>
                                    <span class="colorinput-label">Generator</span>
                                </label>
                                <label class="colorinput me-3">
                                    <input name="color" type="checkbox" value="" id="legend-dataset4" class="colorinput-input" checked>
                                    <span class="colorinput-color bg-chart-powerbank"></span>
                                    <span class="colorinput-label">POWRBANK</span>
                                </label>
                            </div>
                            <div class="chart-container">
                                <canvas id="DashboardBarChart" class="h-275"></canvas>
                            </div>
                            <div class="dimmer chart-loader d-none">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <!-- ROW-1 END -->

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark number-font text-uppercase">Daily Activity <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Daily Activity" data-bs-original-title="Daily Activity"></i></h4>
                        </div>
                        <div class="card-body pb-0" style="height: 340px; overflow:auto;">
                            <ul class="task-list task-daily-activity">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark number-font text-uppercase">POWRBANK Usage <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="POWRBANK Usage" data-bs-original-title="POWRBANK Usage"></i></h4>
                        </div>
                        <div class="card-body pt-2 pb-2" style="height: 340px; overflow:auto;">
                            <div class="power_bank_usage_old d-none"></div>
                            <div class="power_bank_usage">
                                <div class="activity1 mt-3">
                                    <div class="activity-blog">
                                        <div class="activity-img brround  text-white">
                                            <img src="{{url('theme-asset/images/icon/unit.svg')}}" alt="powrbank">
                                        </div>
                                        <div class="activity-details d-flex">
                                            <div style="max-width:150px;">
                                                <b>
                                                    <span class="text-dark fs-16">Total POWRBANK Runtime:</span>
                                                </b>

                                            </div>
                                            <div class="ms-auto fs-16 mt-1"><span class="badge bg-primary text-white badge-custom powrbank_usag_runtime_count">0 hrs</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity1 mt-2">
                                    <div class="activity-blog">
                                        <div class="activity-img brround  text-white">
                                            <img src="{{url('theme-asset/images/icon/ac-solar.svg')}}" alt="powrbank">
                                        </div>
                                        <div class="activity-details d-flex">
                                            <div style="max-width:150px;">
                                                <b>
                                                    <span class="text-dark fs-16">Charged with Solar:</span>
                                                </b>

                                            </div>
                                            <div class="ms-auto fs-16 mt-1"><span class="badge bg-primary text-white badge-custom powrbank_usag_solar_count">0 KWh</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity1 mt-2">
                                    <div class="activity-blog">
                                        <div class="activity-img brround  text-white">
                                            <img src="{{url('theme-asset/images/icon/genset.svg')}}" alt="powrbank">
                                        </div>
                                        <div class="activity-details d-flex">
                                            <div style="max-width:150px;">
                                                <b>
                                                    <span class="text-dark fs-16">Charged with Genset:</span>
                                                </b>
                                            </div>
                                            <div class="ms-auto fs-16 mt-1"><span class="badge bg-primary text-white badge-custom powrbank_usag_genset_count">0 KWh</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="activity1 mt-2">
                                    <div class="activity-blog">
                                        <div class="activity-img brround  text-white">
                                            <img src="{{url('theme-asset/images/icon/fuel-consumption.svg')}}" alt="powrbank">
                                        </div>
                                        <div class="activity-details d-flex">
                                            <div style="max-width:150px;">
                                                <b>
                                                    <span class="text-dark fs-16">Fuel Used:</span>
                                                </b>
                                            </div>
                                            <div class="ms-auto fs-16 mt-1"><span class="badge bg-primary text-white badge-custom powrbank_usag_fuel_count">0 gal</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title text-dark number-font text-uppercase">POWRBANK Notification History <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="POWRBANK Notification History" data-bs-original-title="POWRBANK Notification History"></i></h3>
                            </div>
                        </div>
                        <div class="card-body pb-0 pt-4" style="height: 340px; overflow:auto;">
                            <div class="dashboard_notification">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0 text-dark number-font text-uppercase">POWRBANK List <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="POWRBANK List" data-bs-original-title="POWRBANK List"></i></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead class="border-top">
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">POWRBANK Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Company Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Group Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Address</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Current Status</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Verified</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Connected</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">SOC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($filter_id)
                                        <tr class="odd text-center"><td valign="top" colspan="10" class="dataTables_empty">Not Applicable</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <!-- ROW-5 END -->

            <!-- ROW-6 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0 text-dark number-font text-uppercase">Groups List <i class="ion-information-circled" data-bs-placement="top" data-bs-toggle="tooltip" title="Groups List" data-bs-original-title="Groups List"></i></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom groups-datatable w-100">
                                    <thead class="border-top">
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Group Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Company Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">No. of POWRBANK</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($filter_id)
                                        <tr class="odd text-center"><td valign="top" colspan="10" class="dataTables_empty">Not Applicable</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <!-- ROW-6 END -->

        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content end-->

@endsection
@section('page_level_js')
<script src="{{ url('theme-asset/plugins/chart/Chart.bundle.js') }} "></script>
<script src="{{ url('theme-asset/js/html2canvas.min.js') }}"></script>
<script src="{{ url('theme-asset/js/html2pdf.bundle.min.js') }}"></script>
 {{-- <script src="{{ url('theme-asset/js/chart.js')}}"></script> --}}
 <script type="text/javascript">
    $(document).ready(function() {
        $('#shareButton').click(function() {
            $('#sharePopup').toggleClass('show');
        });

        $('#closePopup').click(function() {
            $('#sharePopup').removeClass('show');
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#shareButton').length && !$(event.target).closest('#sharePopup').length) {
                $('#sharePopup').removeClass('show');
            }
        });

        var filter_id  = "{{ $filter_id ?? ''}}";
        var user_currency     = "{{ $user_currency ?? ''}}";
        var user_liquid_unit  = "{{ $user_liquid_unit ?? ''}}";
        var user_weight_unit  = "{{ $user_weight_unit ?? ''}}";
        if(!filter_id){
            getMySavings()
            getPowerBankUsage()
            accountSummery()
            getAlerts()
            getDailyActivity()
            getPowerBankNotification()
            var tableRx = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: "{{ url('get-dashboard-device-list') }}",
                    data: function (d) {
                        {{-- d.seacrh_name = $('input[name=seacrh_name]').val(); --}}
                    }
                },
                oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                aaSorting: [[1, 'asc']],
                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name',orderable: true, searchable: false },
                    {data: 'company_name', name: 'company_name',orderable: false, searchable: false },
                    {data: 'group_name', name: 'group_name',orderable: false, searchable: false },
                    {data: 'address', name: 'address',orderable: false, searchable: false },
                    {data: 'current_status', name: 'current_status',orderable: false, searchable: false },
                    {data: 'verifed', name: 'verifed',orderable: false, searchable: false },
                    {data: 'connected', name: 'connected',orderable: false, searchable: false },
                    {data: 'SOC', name: 'SOC',orderable: false, searchable: false },
                ],

            });

            var tableGroupRx = $('.groups-datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: "{{ url('get-dashboard-group-list') }}",
                    data: function (d) {
                        // {{-- d.seacrh_name = $('input[name=seacrh_name]').val(); --}}
                    }
                },
                oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                aaSorting: [[1, 'asc']],
                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name',orderable: true, searchable: false },
                    {data: 'company_name', name: 'company_name',orderable: false, searchable: false },
                    {data: 'device_count', name: 'device_count',orderable: false, searchable: false },
                    {data: 'address', name: 'address',orderable: false, searchable: false },
                ],

            });
        } else {
            getPowerBankUsage(filter_id)
            getMySavings(filter_id)
            getAlerts(filter_id)
            getDailyActivity(filter_id)
            getPowerBankNotification(filter_id)
        }

        var company_login_id  = "{{ $company_login_id ?? ''}}";
        $('.select2').select2({});
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

        var ctx = document.getElementById("DashboardBarChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            color: "#77778e",
                        },
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: true,
                            color: "#77778e",
                            callback: function (value, index, values) {
                                return unitConverter(value)
                            }
                        },
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                    }
                },
                plugins: {
                    legend: { display: false },
                    // legend: {
                    //     labels: {
                    //         color: "#77778e"
                    //     },
                    // },
                }
            }
        });

        $('.legend-container input').change(function () {
            // const dataset1Checkbox = $('#legend-dataset1');
            const dataset2Checkbox = $('#legend-dataset2');
            const dataset3Checkbox = $('#legend-dataset3');
            const dataset4Checkbox = $('#legend-dataset4');

            // Update dataset visibility based on checkbox states
            // myChart.data.datasets[0].hidden = !dataset1Checkbox.prop('checked');
            myChart.data.datasets[0].hidden = !dataset2Checkbox.prop('checked');
            myChart.data.datasets[1].hidden = !dataset3Checkbox.prop('checked');
            myChart.data.datasets[2].hidden = !dataset4Checkbox.prop('checked');
            myChart.update();
        });

        callEnergyChart();
        function callEnergyChart() {
            var selected_option = $('#filter_type').val();
            if(selected_option == '')
            {
                selected_option = 'year';
            }
            $('.chart-container').addClass('d-none');
            $('.chart-loader').removeClass('d-none');
            myChart.data.datasets = [];
            myChart.update();
            $.ajax({
                url: '{{ url('get-dashboard-energy-chart') }}',
                type: "POST",
                data: {'selected_option' : selected_option, 'filter_id' : filter_id},
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    myChart.data.labels = data.labels;
                    if(data.dataset)
                    {
                        $.each(data.dataset,function(key,val){
                            myChart.data.datasets.push(val);
                        });
                    }
                    myChart.update();
                    setTimeout(() => {
                        $('.chart-loader').addClass('d-none');
                        $('.chart-container').removeClass('d-none');
                    }, 1000);
                    // ganrateEnergyChart(labels,dataset)
                }
            });

        }

        $(document).on('change','#filter_type',function(){
            var _this = $(this);
            callEnergyChart();
        });

        $(document).on('change','#filterDashboard',function(){
            var _this = $(this);
            var id    = _this.val();
            $('#global-loader').css('display','block');
            if(id){
                if(company_login_id){
                    var url  = "/company/"+company_login_id+"/dashboard/"+id;
                    location = url;
                } else {
                    var url  = "/dashboard/"+id;
                    location = url;
                }
            } else {
                var url  = "/dashboard";
                location = url;
            }
        });

        function accountSummery(){
            $('.account_summery_body').addClass('d-none');
            $('.account_summery_body').before('<div class="spinner1 spinner-account-summery"><div class="double-bounce2"></div></div>');
            $.ajax({
                url: '{{url("get-account-summery")}}',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        $('.account_summery_body .users_count').html(data.user_count_admin)
                        $('.account_summery_body .cluster_count').html(data.cluster_count_admin)
                        $('.account_summery_body .device_count').html(data.device_count_admin)
                        $('.account_summery_body .verified_device_count').html(data.verified_device_count_admin)
                        $('.account_summery_body .inverified_device_count').html(data.unverified_device_count_admin)
                        $('.account_summery_body .connected_device_count').html(data.connected_device_count_admin)
                        $('.spinner-account-summery').remove();
                        $('.account_summery_body').removeClass('d-none');

                    }
                }
            });
        }

        function getPowerBankUsage(filter_ids = false){
            var filter_id = '';
            if(filter_ids){
                filter_id = filter_ids;
            }
            $('.power_bank_usage').addClass('d-none');
            $('.power_bank_usage').before('<div class="spinner1 spinner_power_bank_usage"><div class="double-bounce2"></div></div>');
            $.ajax({
                url: '{{url("get-powerbank-usage-new")}}',
                type: "POST",
                data:{'filter_id' : filter_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        $('.powrbank_usag_runtime_count').html('');
                        $('.powrbank_usag_runtime_count').html(data.powrbank_runtime+' hrs');

                        $('.powrbank_usag_solar_count').html('');
                        $('.powrbank_usag_solar_count').html(data.charged_with_solar+' KWh');

                        $('.powrbank_usag_genset_count').html('');
                        $('.powrbank_usag_genset_count').html(data.charged_with_genset+' KWh');

                        $('.powrbank_usag_fuel_count').html('');
                        $('.powrbank_usag_fuel_count').html(data.total_fuel_used+' gal');

                        $('.spinner_power_bank_usage').remove();
                        $('.power_bank_usage').removeClass('d-none');
                    } else {
                        $('.spinner_power_bank_usage').remove();
                        $('.power_bank_usage').removeClass('d-none');
                    }
                }
            });
        }

        function getAlerts(filter_ids = false){
            var filter_id = '';
            if(filter_ids){
                filter_id = filter_ids;
            }
            $('.dashboard_alerts').addClass('d-none');
            $('.dashboard_alerts').before('<div class="spinner1 spinner_dashboard_alerts"><div class="double-bounce2"></div></div>');
            $.ajax({
                url: '{{url("get-dashboard-alerts")}}',
                type: "POST",
                data:{'filter_id' : filter_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        if (data && data.length > 0) {
                            $.each(data, function (index, historyItem) {
                                var bgArray = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                var randomKey = Math.floor(Math.random() * bgArray.length);
                                var randomColor = bgArray[randomKey];

                                var historyHtml = `
                                    <h5 class="mb-2 mt-4 fontw-5">
                                        <span class="dot-label ${randomColor} me-2"></span>
                                        ${historyItem.device_name ? historyItem.device_name : ''}
                                        <span class="text-muted fs-11 mx-2">${historyItem.code_date_format}</span>
                                    </h5>
                                    <div class="ms-auto fs-13 alert-pl fontw-5">Code : <span class="text-muted fs-14">${historyItem.code || ''}</span></div>
                                    <div class="ms-auto fs-13 alert-pl fontw-5">Title : <span class="text-muted fs-14">${historyItem.warning_title || ''}</span></div>
                                    <div class="fs-13 m-0 alert-pl fontw-5">Msg : <span class="text-muted fs-14">${historyItem.warning_message || ''}</span></div>
                                `;

                                // Append the rendered history item to the container
                                $(".dashboard_alerts").append(historyHtml);
                            });
                        } else {
                            $(".dashboard_alerts").html('<p>No POWRBANK history and warning found.</p>');
                        }
                        $('.spinner_dashboard_alerts').remove();
                        $('.dashboard_alerts').removeClass('d-none');
                    } else {
                        $('.spinner_dashboard_alerts').remove();
                        $(".dashboard_alerts").html('<p>No POWRBANK history and warning found.</p>');
                    }
                }
            });

        }
        function animateNumber(element, start, end, duration) {
            $({ Counter: start }).animate({ Counter: end }, {
                duration: duration,
                easing: 'swing',
                step: function (now) {
                    element.text(Math.ceil(now).toLocaleString());
                }
            });
        }
        function getMySavings(filter_ids = false){
            var filter_id = '';
            if(filter_ids){
                filter_id = filter_ids;
            }
            $('.my_saving').addClass('d-none');
            $('.my_saving').before('<div class="spinner1 spinner_my_saving"><div class="double-bounce2"></div></div>');

            $.ajax({
                url: '{{url("get-my-saving")}}',
                type: "POST",
                data:{'filter_id' : filter_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        if(data[0]){
                            data[0] = data[0].toFixed(2);
                        }
                        if(data[1]){
                            data[1] = data[1].toFixed(2);
                        }
                        if(data[2]){
                            data[2] = data[2].toFixed(2);
                        }

                        var total_co2             = data[2];
                        var total_fuel_saving     = data[0];
                        var total_fuel_consuption = data[1];

                    if(user_weight_unit == 'kg'){
                        var kg = 0.45359237;
                        total_co2 = total_co2 * kg;
                        $('.total-co2').text(total_co2);
                        $('.total-co2-text').text(' KG OF CO2');
                    } else {
                        $('.total-co2').text(total_co2);
                        $('.total-co2-text').text(' LBS OF CO2');
                    }

                    if(user_liquid_unit != 'gallons'){
                        var liter = 0.45359237;
                        total_fuel_saving = total_fuel_saving * liter;
                        $('.total-fule-saving').text(total_fuel_saving);
                        $('.total-fule-saving-text').text(' liters OF FUEL');
                    } else {
                       // $('.total-fule-saving').text('38');
                       $('.total-fule-saving').text(total_fuel_saving);
                        $('.total-fule-saving-text').text(' GAL OF FUEL');
                    }

                    if(user_currency != 'USD'){

                        var user_currency_rate = data['user_currency_rate'];
                        var user_currency_sign = data['user_currency_sign'];
                        total_fuel_consuption = total_fuel_consuption * user_currency_rate;
                        $('.total-fuel-consumption-symbol').text(user_currency_sign+' ');
                        $('.total-fuel-consumption').text(total_fuel_consuption);
                        $('.total-fuel-consumption-text').text(' IN FUEL COST');
                    } else {
                        $('.total-fuel-consumption').text(total_fuel_consuption);
                        $('.total-fuel-consumption-text').text(' IN FUEL COST');
                    }

                    $('.spinner_my_saving').remove();
                    $('.my_saving').removeClass('d-none');

                        animateNumber($('.total-co2'), 0, total_co2, 2000);
                       // animateNumber($('.total-fule-saving'), 0, 38, 2000);
                       animateNumber($('.total-fule-saving'), 0, total_fuel_saving, 2000);
                        animateNumber($('.total-fuel-consumption'), 0, total_fuel_consuption, 2000);
                    }
                }
            });

        }

        function getDailyActivity(filter_ids = false){
            var filter_id = '';
            if(filter_ids){
                filter_id = filter_ids;
            }
            $('.task-daily-activity').addClass('d-none');
            $('.task-daily-activity').before('<div class="spinner1 spinner_daily_activity"><div class="double-bounce2"></div></div>');

            $.ajax({
                url: '{{url("get-daily-activity")}}',
                type: "POST",
                data:{'filter_id' : filter_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        if (data && data.length > 0) {
                            $.each(data, function (index, activity) {
                                var listItem = $('<li></li>');
                                if (activity.conv_status == 'Off') {
                                    bgColor = '#D84B2B';
                                } else if (activity.conv_status == 'Charging') {
                                    bgColor = '#1F3D4E';
                                } else if (activity.conv_status == 'Descharging' || activity.conv_status == 'discharging' || activity.conv_status == 'Discharging') {
                                    bgColor = '#AAC760';
                                }else if(activity.conv_status == 'New User Added'){
                                    bgColor = '#2F3F11';
                                }else if(activity.conv_status == 'New Power Bank Added'){
                                    bgColor = ' #4E1F4A';
                                }else if(activity.conv_status == 'Device Updated'){
                                    bgColor = '#D84B2B';
                                } else {
                                    bgColor = '#D84B2B'; // Default color
                                }
                                listItem.append('<i class="task-icon" style="background-color: ' + bgColor + '"></i>');
                                // listItem.append('<i class="task-icon ' + (activity.bg_color || 'bg-primary') + '"></i>');
                                listItem.append('<h6>' + (activity.conv_status || '') + '<span class="text-muted fs-11 mx-2">' + (activity.created || '') + '</span></h6>');
                                listItem.append('<p class="text-muted fs-12">' + (activity.message || '') + '</p>');
                                $('.task-daily-activity').append(listItem);
                            });
                        } else {
                            $('.task-daily-activity').append('<p>No activity found.</p>');
                        }
                        $('.spinner_daily_activity').remove();
                        $('.task-daily-activity').removeClass('d-none');
                    }
                }
            });

        }

        function getPowerBankNotification(filter_ids = false){
            var filter_id = '';
            if(filter_ids){
                filter_id = filter_ids;
            }
            $('.dashboard_notification').addClass('d-none');
            $('.dashboard_notification').before('<div class="spinner1 spinner_dashboard_notification"><div class="double-bounce2"></div></div>');
            $.ajax({
                url: '{{url("get-powerbank-notification")}}',
                type: "POST",
                data:{'filter_id' : filter_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if(data){
                        if (data && data.length > 0) {
                            $.each(data, function (index, notification) {
                                var notificationHtml = `
                                    <div class="activity1">
                                        <div class="activity-blog">
                                            <div class="activity-img brround  text-black mt-2" style="border:unset;">
                                                <img src="{{url('theme-asset/images/063@blue-01.png')}}">
                                            </div>
                                            <div class="activity-details d-flex">
                                                <div>
                                                    <b>
                                                        <span class="text-dark fs-20">${notification.device_name || ''}</span>
                                                    </b>
                                                    <div class="ms-auto fs-13">Code : <span class="text-muted fs-14">${notification.code || ''}</span></div>
                                                    <div class="ms-auto fs-13">Title : <span class="text-muted fs-14">${notification.notification_title || ''}</span></div>
                                                    <p class="fs-14 m-0">Message : <span class="text-muted fs-14">${notification.notification_message || ''}</span> </p>
                                                </div>
                                                <div class="ms-auto fs-13"><span class="text-muted fs-11 mx-2">${notification.code_date_format || ''}</span></div>
                                            </div>
                                        </div>
                                    </div>`;

                                // Append the rendered history item to the container
                                $(".dashboard_notification").append(notificationHtml);
                            });
                        } else {
                            $(".dashboard_notification").html('<p>No POWRBANK Notification History found.</p>');
                        }
                        $('.spinner_dashboard_notification').remove();
                        $('.dashboard_notification').removeClass('d-none');
                    } else {
                        $('.spinner_dashboard_notification').remove();
                        $(".dashboard_notification").html('<p>No POWRBANK Notification History found.</p>');
                    }
                }
            });
        }

        $(document).on('click','.shareOnFacebook',function(){
            saveMySavingImage('facebook');
        });

        $(document).on('click','.shareOnLinkedin',function(){
            saveMySavingImage('linkdin');
        });

        $(document).on('click','.shareOnTwitter',function(){
            saveMySavingImage('twitter');
        });

        function saveMySavingImage(mediaType) {
            var parentContainer = document.getElementById('exportMySaving');
            $('#shareButton').addClass('d-none');
            setTimeout(() => {
                html2canvas(parentContainer, { useCORS: true}).then(function(canvas) {
                    canvas.toBlob(function(blob) {
                    var formData = new FormData();
                    formData.append('image', blob, 'mysaving.png');
                        // Send data to server
                        $.ajax({
                            url: '{{url("upload-mysaving-image")}}',
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                try {
                                    data = JSON.parse(data);
                                } catch(e) {}
                                console.log(data.file_url);

                                if(mediaType == 'linkdin'){
                                    var imageurl = data.file_url;
                                    var linkedInUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(imageurl)}`;
                                    window.open(linkedInUrl, '_blank');
                                } else if(mediaType == 'facebook') {
                                    var imageurl = encodeURIComponent(data.file_url);
                                    var text = encodeURIComponent('Im reducing my carbon footprint and fuel costs. Check out my savings with the POWRBANK battery energy storage system. #POWR2');
                                    var shareUrl = `https://www.facebook.com/sharer.php?u=${imageurl}&quote=${text}`;
                                    window.open(shareUrl, '_blank');
                                } else if(mediaType == 'twitter') {
                                    var imageUrl = encodeURIComponent(data.file_url);
                                    var text = encodeURIComponent('Im reducing my carbon footprint and fuel costs. Check out my savings with the POWRBANK battery energy storage system. #POWR2');
                                    var shareUrl = `https://twitter.com/share?url=${imageUrl}&text=${text}`;
                                    // var shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(imageUrl)}`;
                                    window.open(shareUrl, '_blank');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('AJAX error:', textStatus, errorThrown);
                            }
                        });
                    }, 'image/png');
                }).catch(function(error) {
                    console.error('Error generating canvas:', error);
                });
                $('#shareButton').removeClass('d-none');
                $('#closePopup').trigger('click');
            }, 500);
        }
    });
</script>
@endsection
