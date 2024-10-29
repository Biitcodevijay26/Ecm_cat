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
        width: 65px;
    }
    .apexcharts-menu-icon{
        display: none;
    }
    .ch{
        min-height: 340px;
    }
    .alert-pl{
        padding-left: 30px;
    }
</style>
@endsection
@section('content')
<?php
    $pie_chart_data = json_encode($piechart_data);
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
                    <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                                <i class="fe fe-plus"></i>
                            </span> Add Account
                    </a>
                    <a href="javascript:void(0);" class="btn btn-success btn-icon text-white">
                        <span>
                                <i class="fe fe-log-in"></i>
                            </span> Export
                    </a>
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
                                <div class="card-body ch">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-dark number-font text-uppercase">Account Summary</h4>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <h5 class="mb-3"><strong>Users: {{$user_count ?? 0}}</strong></h5>
                                        <h5 class="mb-3"><strong>Groups: {{$cluster_count_admin ?? 0}}</strong></h5>
                                        <h5 class="mb-3"><strong>Total POWRBANK: {{$device_count_admin ?? 0}}</strong></h5>
                                    </div>
                                    <div class="row mt-5">
                                        <h5 class="mb-4"><span class="dot-label bg-warning me-2"></span><strong> Verified POWRBANK: {{$verified_device_count_admin ?? 0}} </strong></h5>
                                        <h5 class="mb-4"><span class="dot-label bg-danger me-2"></span><strong> Unverified POWRBANK: {{$unverified_device_count_admin ?? 0}} </strong></h5>
                                        <h5 class="mb-4"><span class="dot-label bg-info me-2"></span><strong> Connected POWRBANK: {{$connected_device_count_admin ?? 0}} </strong></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="card-body ch">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-dark number-font text-uppercase">ALERTS</h4>
                                        </div>
                                    </div>
                                    <div class="row mt-4" style="height: 240px; overflow:auto;">
                                        <?php
                                            $bgArray = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                        ?>
                                        @if ($device_history && count($device_history) > 0)
                                        @foreach ($device_history as $DH)
                                            <?php
                                            $randomKey = array_rand($bgArray);
                                            $randomColor = $bgArray[$randomKey];
                                            ?>
                                            <h5 class="mb-2 mt-4 fontw-5"><span class="dot-label {{$randomColor ?? 'bg-warning' }} me-2"></span>{{ isset($DH->macid) && !empty($DH->macid) ? getDeviceNameByMacId($DH->macid) : '' }}  <span class="text-muted fs-11 mx-2">{{ isset($DH->code_date) && !empty($DH->code_date) ? Carbon\Carbon::createFromTimestamp($DH->code_date->toDateTime()->getTimestamp())->format('d M \'y') : '' }}</span></h5>
                                            <div class="ms-auto fs-13 alert-pl fontw-5">Code : <span class="text-muted fs-14">{{$DH->code ?? ''}}</span></div>
                                            <div class="ms-auto fs-13 alert-pl fontw-5">Title : <span class="text-muted fs-14">{{$DH->warning->title ?? ''}}</span></div>
                                            <div class="fs-13 m-0 alert-pl fontw-5">Msg : <span class="text-muted fs-14">{{$DH->warning->message ?? ''}}</span> </div>
                                        @endforeach
                                        @else
                                        <p>No POWRBANK history and warning found.</p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xl-4">
                            <div class="card overflow-hidden" style="display: flex; align-items: center; justify-content: center;">
                                <div class="card-body ch">
                                    <div class="row">
                                        <div class="col">
                                        {{-- <h3 class="card-title">Fule Saving / CO2 Emission</h3> --}}
                                        <h4 class="text-dark number-font text-uppercase">My Savings</h4>
                                        </div>
                                    </div>
                                    <div class="row" style="height: 340px;">
                                        {{-- <div id="pieChart" class="apex-charts ht-150"></div> --}}
                                        <canvas id="chartDonut" height="500"></canvas>
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
                                <h3 class="card-title">Total Energy</h3>
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
                            {{-- <div id="chartArea" class="chart-donut"></div> --}}
                            {{-- <div id="chartAreaCustom" class="chart-donut"></div> --}}
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
                {{-- <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
                    <div class="card custom-card ">
                        <div class="card-header">
                            <h3 class="card-title">Recent Orders</h3>
                        </div>
                        <div class="card-body pt-0 ps-0 pe-0">
                            <div id="recentorders" class="apex-charts ht-150"></div>
                            <div class="row sales-product-infomation pb-0 mb-0 mx-auto wd-100p mt-6">
                                <div class="col-md-6 col justify-content-center text-center">
                                    <p class="mb-0 d-flex justify-content-center"><span class="legend bg-primary"></span>Delivered</p>
                                    <h3 class="mb-1 fw-bold">5238</h3>
                                    <div class="d-flex justify-content-center ">
                                        <p class="text-muted mb-0">Last 6 months</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col text-center float-end">
                                    <p class="mb-0 d-flex justify-content-center "><span class="legend bg-background2"></span>Cancelled</p>
                                    <h3 class="mb-1 fw-bold">3467</h3>
                                    <div class="d-flex justify-content-center ">
                                        <p class="text-muted mb-0">Last 6 months</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- COL END -->
            </div>
            <!-- ROW-1 END -->

            <!-- ROW-3 -->
            <div class="row d-none">
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Timeline</h3>
                            </div>
                        </div>
                        <div class="card-body pb-0 pt-4">
                            <div class="activity1">
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-primary-transparent text-primary">
                                        <i class="fa fa-user-plus fs-20"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div><b><span class="text-dark"> Mr John </span> </b> Started following you <span class="d-flex text-muted fs-11">01 June 2020</span></div>
                                        <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-primary text-white">1m</span></div>
                                    </div>
                                </div>
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-secondary-transparent text-secondary">
                                        <i class="fa fa-comment fs-20"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div><b><span class="text-dark"> Lily </span> </b> 1 Commented applied <span class="d-flex text-muted fs-11">01 July 2020</span> </div>
                                        <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-danger text-white">3m</span></div>
                                    </div>
                                </div>
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-success-transparent text-success">
                                        <i class="fa fa-thumbs-up fs-20"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div><b><span class="text-dark"> Kevin </span> </b> liked your site <span class="d-flex text-muted fs-11">05 July 2020</span></div>
                                        <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-warning text-white">5m</span></div>
                                    </div>
                                </div>
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-info-transparent text-info">
                                        <i class="fa fa-envelope fs-20"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div><b><span class="text-dark"> Andrena </span> </b> posted a new article <span class="d-flex text-muted fs-11">09 October 2020</span></div>
                                        <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-info text-white">5m</span></div>
                                    </div>
                                </div>
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-danger-transparent text-danger">
                                        <i class="fa fa-shopping-bag fs-20"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div><b><span class="text-dark"> Sonia </span> </b> Delivery in progress <span class="d-flex text-muted fs-11">12 October 2020</span></div>
                                        <div class="ms-auto fs-13 text-dark fw-semibold"><span class="badge bg-warning text-white">5m</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title fw-semibold ">Browser Usage</h4>
                        </div>
                        <div class="card-body pt-2 pb-2">
                            <div class="d-md-flex align-items-center browser-stats">
                                <div class="d-flex me-1">
                                    <i class="fa fa-chrome bg-secondary-gradient text-white me-2"></i>
                                    <p class="fs-16 my-auto mb-0">Chrome</p>
                                </div>
                                <div class="ms-auto my-auto">
                                    <div class="d-flex">
                                        <span class="my-auto fs-16">35,502</span>
                                        <span class="text-success fs-15"><i class="fe fe-arrow-up"></i>12.75%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-flex align-items-center browser-stats">
                                <div class="d-flex me-1">
                                    <i class="fa fa-opera text-white bg-danger-gradient me-2"></i>
                                    <p class="fs-16 my-auto mb-0">Opera</p>
                                </div>
                                <div class="ms-auto my-auto">
                                    <div class="d-flex">
                                        <span class="my-auto fs-16">12,563</span>
                                        <span class="text-danger fs-15"><i class="fe fe-arrow-down"></i>15.12%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-flex align-items-center browser-stats">
                                <div class="d-flex me-1">
                                    <i class="fa fa-firefox text-white bg-purple-gradient me-2"></i>
                                    <p class="fs-16 my-auto mb-0">IE</p>
                                </div>
                                <div class="ms-auto my-auto">
                                    <div class="d-flex">
                                        <span class="my-auto fs-16">25,364</span>
                                        <span class="text-success fs-15"><i class="fe fe-arrow-up"></i>24.37%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-flex align-items-center browser-stats">
                                <div class="d-flex me-1">
                                    <i class="fa fa-edge text-white bg-info-gradient me-2"></i>
                                    <p class="fs-16 my-auto mb-0">Firefox</p>
                                </div>
                                <div class="ms-auto my-auto">
                                    <div class="d-flex">
                                        <span class="my-auto fs-16">14,635</span>
                                        <span class="text-success fs-15"><i class="fe fe-arrow-up"></i>15,63%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-md-flex align-items-center browser-stats">
                                <div class="d-flex me-1">
                                    <i class="fa fa-android text-white bg-success-gradient me-2"></i>
                                    <p class="fs-16 my-auto mb-0">Android</p>
                                </div>
                                <div class="ms-auto my-auto">
                                    <div class="d-flex">
                                        <span class="my-auto fs-16">15,453</span>
                                        <span class="text-danger fs-15"><i class="fe fe-arrow-down"></i>23.70%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title fw-semibold ">Daily Activity</h4>
                        </div>
                        <div class="card-body pb-0">
                            <ul class="task-list">
                                <li>
                                    <i class="task-icon bg-primary"></i>
                                    <h6>Task Finished<span class="text-muted fs-11 mx-2">29 Oct 2020</span></h6>
                                    <p class="text-muted fs-12">Adam Berry finished task on<a href="javascript:void(0);" class="fw-semibold"> Project Management</a></p>
                                </li>
                                <li>
                                    <i class="task-icon bg-secondary"></i>
                                    <h6>New Comment<span class="text-muted fs-11 mx-2">25 Oct 2020</span></h6>
                                    <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0);" class="fw-semibold"> AngularJS Template</a></p>
                                </li>
                                <li>
                                    <i class="task-icon bg-primary"></i>
                                    <h6>New Comment<span class="text-muted fs-11 mx-2">25 Oct 2020</span></h6>
                                    <p class="text-muted fs-12">Victoria commented on Project <a href="javascript:void(0);" class="fw-semibold"> AngularJS Template</a></p>
                                </li>
                                <li>
                                    <i class="task-icon bg-secondary"></i>
                                    <h6>Task Overdue<span class="text-muted fs-11 mx-2">14 Oct 2020</span></h6>
                                    <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0);" class="fw-semibold"> Integrated management</a></p>
                                </li>
                                <li>
                                    <i class="task-icon bg-primary"></i>
                                    <h6>Task Overdue<span class="text-muted fs-11 mx-2">29 Oct 2020</span></h6>
                                    <p class="text-muted mb-0 fs-12">Petey Cruiser finished task <a href="javascript:void(0);" class="fw-semibold"> Integrated management</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!-- COL END -->
            <!-- ROW-3 END -->

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title fw-semibold ">Daily Activity</h4>
                        </div>
                        <div class="card-body pb-0" style="height: 340px; overflow:auto;">
                            <ul class="task-list task-daily-activity">
                                @if ($daily_activities)
                                @foreach ($daily_activities as $activity)
                                    <li>
                                        <i class="task-icon {{$activity['bg_color'] ?? bg-primary}}"></i>
                                        <h6>{{$activity['conv_status'] ?? ''}}<span class="text-muted fs-11 mx-2">{{$activity['created'] ?? ''}}</span></h6>
                                        <p class="text-muted fs-12">{{$activity['message'] ?? ''}}</p>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title fw-semibold ">POWRBANK Usage</h4>
                        </div>
                        <div class="card-body pt-2 pb-2" style="height: 340px; overflow:auto;">

                            @if ($device_usage_new)
                            @foreach ($device_usage_new as $key => $usage)
                            @if ($key == "high_run_time")
                            <div class="activity1 mt-4">
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-success-gradient text-white mt-2">
                                        <i class="fa fa-clock-o fs-20 text-center mt-1"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div>
                                            <b>
                                                <span class="text-dark fs-20"> {{$usage['device_name'] ?? ''}} </span>
                                            </b>
                                            <div class="ms-auto fs-16 mt-1">Highest Run Time : <span class="badge bg-primary text-white badge-custom">{{$usage['high_run_time'] ?? ''}}</span></div>
                                        </div>
                                        <div class="ms-auto fs-13"><span class="text-muted fs-11 mx-2">{{$usage['created'] ?? ''}}</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($key == "solar_generated_power")
                            <div class="activity1 mt-4">
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-success-gradient text-white mt-2">
                                        <i class="fa fa-bolt fs-20 text-center mt-1"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div>
                                            <b>
                                                <span class="text-dark fs-20"> {{$usage['device_name'] ?? ''}} </span>
                                            </b>
                                            <div class="ms-auto fs-16 mt-1">Solar Generated Power : <span class="badge bg-primary text-white badge-custom">{{$usage['solar_generated_power'] ?? ''}}</span></div>
                                        </div>
                                        <div class="ms-auto fs-13"><span class="text-muted fs-11 mx-2">{{$usage['created'] ?? ''}}</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($key == "fule_saved")
                            <div class="activity1 mt-4">
                                <div class="activity-blog">
                                    <div class="activity-img brround bg-success-gradient text-white mt-2">
                                        <i class="zmdi zmdi-gas-station fs-20 text-center mt-1"></i>
                                    </div>
                                    <div class="activity-details d-flex">
                                        <div>
                                            <b>
                                                <span class="text-dark fs-20"> {{$usage['device_name'] ?? ''}} </span>
                                            </b>
                                            <p class="ms-auto fs-16 mt-1">Fuel Saved : <span class="badge bg-primary text-white badge-custom">{{$usage['fule_saved'] ?? ''}}</span> </p>
                                        </div>
                                        <div class="ms-auto fs-13"><span class="text-muted fs-11 mx-2">{{$usage['created'] ?? ''}}</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">POWRBANK Notification History </h3>
                            </div>
                        </div>
                        <div class="card-body pb-0 pt-4" style="height: 340px; overflow:auto;">
                            @if ($device_notification && count($device_notification) > 0)
                                @foreach ($device_notification as $DN)

                                <div class="activity1">
                                    <div class="activity-blog">
                                        <div class="activity-img brround bg-success-transparent text-black mt-2">
                                            <i class="fa fa-bell fs-20 text-center mt-2"></i>
                                        </div>
                                        <div class="activity-details d-flex">
                                            <div>
                                                <b>
                                                    <span class="text-dark fs-20">{{ isset($DN->macid) && !empty($DN->macid) ? getDeviceNameByMacId($DN->macid) : '' }}</span>
                                                </b>
                                                <div class="ms-auto fs-13">Code : <span class="text-muted fs-14">{{$DN->code ?? ''}}</span></div>
                                                <div class="ms-auto fs-13">Title : <span class="text-muted fs-14">{{$DN->notification->title ?? ''}}</span></div>
                                                <p class="fs-14 m-0">Message : <span class="text-muted fs-14">{{$DN->notification->message ?? ''}}</span> </p>
                                            </div>
                                            <div class="ms-auto fs-13"><span class="text-muted fs-11 mx-2">{{ isset($DN->code_date) && !empty($DN->code_date) ? Carbon\Carbon::createFromTimestamp($DN->code_date->toDateTime()->getTimestamp())->format('d M Y') : '' }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <p>No POWRBANK Notification History found.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card ">
                        <div class="card-header">
                            <h3 class="card-title mb-0">POWRBANK List</h3>
                        </div>
                        <div class="card-body">
                            {{-- <div class="table-responsive">
                                <table id="data-table" class="table table-bordered text-nowrap mb-0">
                                    <thead class="border-top">
                                        <tr>
                                            <th class="bg-transparent border-bottom-0 w-5">S.no</th>
                                            <th class="bg-transparent border-bottom-0">POWRBANK Name</th>
                                            <th class="bg-transparent border-bottom-0">Verified</th>
                                            <th class="bg-transparent border-bottom-0">Connected</th>
                                            <th class="bg-transparent border-bottom-0">SOC%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td class="text-muted fs-15 fw-semibold text-center">01.</td>
                                            <td>
                                                <div class="d-flex">
                                                    <span class="avatar avatar-md brround mt-1" style="background-image: url(../assets/images/users/11.jpg)"></span>
                                                    <div class="ms-2 mt-0 mt-sm-2 d-block">
                                                        <h6 class="mb-0 fs-14 fw-semibold">Jake poole</h6>
                                                        <span class="fs-12 text-muted">jacke123@gmail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-muted fs-15 fw-semibold">20-11-2020 </td>
                                            <td class="text-muted fs-15 fw-semibold">$5.321.2</td>
                                            <td class="text-success fs-15 fw-semibold">Success</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> --}}
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead class="border-top">
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">POWRBANK Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Company Name</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Address</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Current Status</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Verified</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">Connected</th>
                                            <th class="wd-15p bg-transparent border-bottom-0">SOC%</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- COL END -->
            </div>
            <!-- ROW-5 END -->
        </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content end-->

@endsection
@section('page_level_js')
<script src="{{ url('theme-asset/plugins/chart/Chart.bundle.js') }} "></script>
 {{-- <script src="{{ url('theme-asset/js/chart.js')}}"></script> --}}
 <script type="text/javascript">
    $(document).ready(function() {

        var pie_chart_data = '<?php echo $pie_chart_data ?>';
        if(pie_chart_data)
        {
            resp = JSON.parse(pie_chart_data)
            pie_chart_data = resp;
        }
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
                {data: 'address', name: 'address',orderable: false, searchable: false },
                {data: 'current_status', name: 'current_status',orderable: false, searchable: false },
                {data: 'verifed', name: 'verifed',orderable: false, searchable: false },
                {data: 'connected', name: 'connected',orderable: false, searchable: false },
                {data: 'SOC', name: 'SOC',orderable: false, searchable: false },
            ],

        });


        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

         /*Pie-Chart */
        var data = {
            labels: ['Total Fuel Consumption', 'Total CO2', 'Total Fuel Saving'],
            datasets: [{
                data: pie_chart_data, // Replace with your data values
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
            }],
        };

        var ctx7 = document.getElementById('chartDonut');
        var myPieChart7 = new Chart(ctx7, {
            type: 'pie',
            responsive : true,
            maintainAspectRatio: false,
            data: data,
            // options: optionpie
        });
        myPieChart7.canvas.parentNode.style.height = '260px';
        myPieChart7.canvas.parentNode.style.width = '260px';

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
                legend: {
                    labels: {
                        color: "#77778e"
                    },
                },
            }
        }
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
            data: {'selected_option' : selected_option},
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

    // function ganrateEnergyChart(labels,dataset) {
    //     document.querySelector("#chartAreaCustom").innerHTML = "";
    //     var datas = [];
    //     if(dataset)
    //     {
    //         $.each(dataset,function(key,val){
    //             var datafill = {
    //                 name: val.label,
    //                 type: 'bar',
    //                 data: val.data,
    //                 fillColor : val.fillColor
    //             }
    //             datas.push(datafill);
    //         });
    //     }
    //     var options = {
    //         chart: {
    //             height: 320,
    //             type: "bar",
    //             stacked: false,
    //             toolbar: {
    //                 show: true,
    //                 tools: {
    //                     download: false,
    //                     selection: true,
    //                     zoom: false,
    //                     zoomin: true,
    //                     zoomout: true,
    //                     pan: true,
    //                     reset: true | '<img src="/static/icons/reset.png" width="20">'
    //                   },
    //             },
    //             dropShadow: {
    //                 enabled: true,
    //                 opacity: 0.1,
    //             },
    //         },
    //         plotOptions: {
    //             bar: {
    //             horizontal: false
    //             }
    //         },
    //         colors: ["#FF0400", '#FFF700','#0A5AFA','#0A0A0A'],
    //         dataLabels: {
    //             enabled: false
    //         },
    //         stroke: {
    //             curve: "smooth",
    //             width: [3, 3, 0],
    //             dashArray: [0, 4],
    //             lineCap: "round"
    //         },
    //         grid: {
    //             padding: {
    //                 left: 0,
    //                 right: 0
    //             },
    //             strokeDashArray: 3
    //         },
    //         markers: {
    //             size: 0,
    //             hover: {
    //                 size: 0
    //             }
    //         },
    //         series: datas,
    //         xaxis: {
    //             categories: labels,
    //             axisBorder: {
    //                 show: false,
    //                 color: 'rgba(119, 119, 142, 0.08)',
    //             },
    //             labels: {
    //                 style: {
    //                     color: '#8492a6',
    //                     fontSize: '12px',
    //                 },
    //             },
    //         },
    //         yaxis: {
    //             labels: {
    //                 formatter: function(val) {
    //                     if(val){
    //                         return Number(val).toFixed(0);
    //                     }
    //                 },
    //                 style: {
    //                     color: '#8492a6',
    //                     fontSize: '12px',
    //                 },
    //             },
    //             axisBorder: {
    //                 show: false,
    //                 color: 'rgba(119, 119, 142, 0.08)',
    //             },
    //         },
    //         fill: {
    //             gradient: {
    //               inverseColors: false,
    //               shade: 'light',
    //               type: "vertical",
    //               opacityFrom: 0.85,
    //               opacityTo: 0.55,
    //               stops: [0, 100, 100, 100]
    //             }
    //           },
    //         tooltip: {
    //             show:true
    //         },
    //         legend: {
    //             position: "top",
    //             show:true
    //         }
    //     }
    //     document.querySelector("#chartAreaCustom").innerHTML = "";
    //     var chart = new ApexCharts(document.querySelector("#chartAreaCustom"), options);
    //     chart.render();
    // }

    $(document).on('change','#filter_type',function(){
        var _this = $(this);
        callEnergyChart();
    });


    });
    </script>
@endsection
