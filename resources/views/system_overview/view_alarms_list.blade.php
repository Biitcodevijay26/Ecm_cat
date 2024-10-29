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
    #loaderDB {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin-left:50%;
        margin-top:5%;
    }
</style>
@endsection
@section('content')
<?php
    $device_id = $data->id ?? '';
    $id        = $data->id ?? '';
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $company_login_id = session()->get('company_login_id');
    $is_permission_warning      = '';
    $is_permission_notification = '';

    if (Gate::allows('DeviceWarningView')) {
        $is_permission_warning = 'active';
    }

    if (Gate::allows('DeviceNotificationView')) {
        $is_permission_notification = 'active';
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
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/system-overview') }}">System Overview</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/device_details/'.$device_id) }}">POWRBANK Details</a></li>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/system-overview') }}">{{ $module }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/device_details/'.$device_id) }}">POWRBANK Details</a></li>
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
              <!-- ROW-1 Start -->
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-2">
                                    <p class="device-serial-number">{{ $macid ?? ''}}</p>
                                    <p class="device-serial-label">MACID</p>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-2">
                                    <p class="device-serial-number">{{ $data->name ?? ''}}</p>
                                    <p class="device-serial-label">POWRBANK Name</p>
                                </div>
                                {{-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 pt-2">
                                    <p class="device-serial-number machine-updated-date">July 24, 2023</p>
                                    <p class="device-serial-label machine-updated-time">10:22 PM</p>
                                </div> --}}
                                {{-- <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1">
                                    <div class="d-flex justify-content-md-center">
                                        <a href="{{url('/edit-device/'.$id)}}">
                                            <div class="edit-icon-btn box-shadow-success">
                                                <i class="icon icon-pencil text-white mb-5"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div> --}}
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
                                    <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/company/'.$company_login_id.'/battery_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon.png') }}" alt="battery-icon"> </span> Battery
                                    </a>

                                    <a href="{{url('/company/'.$company_login_id.'/edit-device/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/edit-icon.png') }}" alt="edit-device-icon"> </span> Edit POWRBANK
                                    </a>

                                    <a href="{{ url('/company/'.$company_login_id.'/charts/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/chart-icon.png') }}" alt="view-chart-icon"> </span> View Chart
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

                                        <a href="{{url('/company/'.$company_login_id.'/device-alarms-list/'.$macid)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/noti-w.png') }}" alt="remort-access-icon"> </span> Notification
                                        </a>

                                <?php else : ?>
                                    <a href="{{ url('/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/battery_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon.png') }}" alt="battery-icon"> </span> Battery
                                    </a>

                                    @can('DeviceManagementEdit')
                                    <a href="{{url('/edit-device/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/edit-icon.png') }}" alt="edit-device-icon"> </span> Edit POWRBANK
                                    </a>
                                    @endcan

                                    @can('ChartView')
                                    <a href="{{url('/charts/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/chart-icon.png') }}" alt="view-chart-icon"> </span> View Chart
                                    </a>
                                    @endcan

                                    @can('LiveView')
                                    <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 machine_verified_btn d-none mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/verified-icon.png') }}" alt="verify-machine-icon"> </span> Verify Machine
                                    </a>
                                    @endcan

                                    @can('RemoteAccessView')
                                    <a href="{{url('/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                    </a>
                                    @endcan

                                    <a href="{{url('/device-alarms-list/'.$macid)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/noti-w.png') }}" alt="remort-access-icon"> </span> Notification
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-Buttons Close -->
            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">{{ $heading }} Info</h3>
                            </div>
                            <div class="card-options">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1 ">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            @can('DeviceWarningView')
                                            <li class="me-2"><a href="#warning-msg" class="active tab-btn-custom fs-16" data-bs-toggle="tab" class="warning-tab-btn">Warning</a></li>
                                            @endcan
                                            @can('DeviceNotificationView')
                                            <li><a href="#error-msg" data-bs-toggle="tab" class="{{ isset($is_permission_warning) && $is_permission_warning == '' ? 'active' : '' }} error-tab-btn tab-btn-custom fs-16" style="background-color: var(--primary01);">Notification</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        @can('DeviceWarningView')
                                        <div class="tab-pane active" id="warning-msg">
                                            <div class=" tab-menu-heading">
                                                <div class="tabs-menu1">
                                                    <!-- Tabs -->
                                                    <ul class="nav panel-tabs">
                                                        <li class="me-2">
                                                            <a href="#current-warning" data-bs-toggle="tab" class="active error-tab-btn cur-warning-btn tab-btn-custom fs-16" style="background-color: var(--primary01);">Current Warning</a>
                                                        </li>
                                                        <li>
                                                            <a href="#current-history" class="tab-btn-custom fs-16" data-bs-toggle="tab" class="warning-tab-btn cur-his-btn" style="background-color: var(--primary01);">Warning History</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel-body tabs-menu-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane " id="current-history">
                                                        <!-- ROW-1 OPEN -->
                                                        <div class="row">
                                                            <div class="col-xl-12 col-md-12 col-sm-12">
                                                                <div class="card card-border-black">
                                                                    <div class="card-header bg-color-black"  style="height: 60px;">
                                                                        <h3 class="card-title text-white">Warning History</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-nowrap border-bottom current-history-table w-100">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>SN</th>
                                                                                        <th class="wd-15p border-bottom-0">Code</th>
                                                                                        <th class="wd-15p border-bottom-0">Title</th>
                                                                                        <th class="wd-15p border-bottom-0">Message</th>
                                                                                        <th class="wd-15p border-bottom-0">Code Date</th>
                                                                                        <th class="wd-15p border-bottom-0">Created At</th>
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
                                                    <div class="tab-pane active" id="current-warning">
                                                        <!-- ROW-1 OPEN -->
                                                        <div class="row">
                                                            <div class="col-xl-12 col-md-12 col-sm-12">
                                                                <div class="card card-border-black">
                                                                    <div class="card-header bg-color-black"  style="height: 60px;">
                                                                        <h3 class="card-title text-white">Current Warning</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-nowrap border-bottom current-warning-table w-100">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>SN</th>
                                                                                        <th class="wd-15p border-bottom-0">Code</th>
                                                                                        <th class="wd-15p border-bottom-0">Title</th>
                                                                                        <th class="wd-15p border-bottom-0">Message</th>
                                                                                        <th class="wd-15p border-bottom-0">Code Date</th>
                                                                                        <th class="wd-15p border-bottom-0">Created At</th>
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
                                        @endcan
                                        @can('DeviceNotificationView')
                                        <div class="tab-pane {{ isset($is_permission_warning) && $is_permission_warning == '' ? 'active' : '' }}" id="error-msg">
                                            <div class=" tab-menu-heading">
                                                <div class="tabs-menu1">
                                                    <!-- Tabs -->
                                                    <ul class="nav panel-tabs">
                                                        <li class="me-2">
                                                            <a href="#current-notification" class="active tab-btn-custom fs-16 cur_noti_btn" data-bs-toggle="tab" class="warning-tab-btn">Current Notification</a>
                                                        </li>
                                                        <li>
                                                            <a href="#notification-history" data-bs-toggle="tab" class="error-tab-btn his_noti_btn tab-btn-custom fs-16" style="background-color: var(--primary01);">Notification History</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel-body tabs-menu-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="current-notification">
                                                        <!-- ROW-1 OPEN -->
                                                        <div class="row">
                                                            <div class="col-xl-12 col-md-12 col-sm-12">
                                                                <div class="card card-border-black">
                                                                    <div class="card-header bg-color-black"  style="height: 60px;">
                                                                        <h3 class="card-title text-white">Current Notification</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-nowrap border-bottom current-notification-table w-100">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>SN</th>
                                                                                        <th class="wd-15p border-bottom-0">Code</th>
                                                                                        <th class="wd-15p border-bottom-0">Title</th>
                                                                                        <th class="wd-15p border-bottom-0">Message</th>
                                                                                        <th class="wd-15p border-bottom-0">Code Date</th>
                                                                                        <th class="wd-15p border-bottom-0">Created At</th>
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
                                                    <div class="tab-pane" id="notification-history">
                                                        <!-- ROW-1 OPEN -->
                                                        <div class="row">
                                                            <div class="col-xl-12 col-md-12 col-sm-12">
                                                                <div class="card card-border-black">
                                                                    <div class="card-header bg-color-black"  style="height: 60px;">
                                                                        <h3 class="card-title text-white">Notification History</h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered text-nowrap border-bottom notification-history-table w-100">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>SN</th>
                                                                                        <th class="wd-15p border-bottom-0">Code</th>
                                                                                        <th class="wd-15p border-bottom-0">Title</th>
                                                                                        <th class="wd-15p border-bottom-0">Message</th>
                                                                                        <th class="wd-15p border-bottom-0">Code Date</th>
                                                                                        <th class="wd-15p border-bottom-0">Created At</th>
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
                                        @endcan
                                    </div>
                                </div>
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
<script src="{{ url('theme-asset/plugins/bootstrap-daterangepicker/moment.min.js')}}"></script>
<script>
     $(document).ready(function() {
        // $('.select2').select2({});
        // $(document).on('select2:open', () => {
        //     document.querySelector('.select2-search__field').focus();
        // });

        // var tableRx = $('.yajra-datatable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     stateSave: true,
        //     ajax: {
        //         url: "{{ url('get-alarms-list') }}",
        //         data: function (d) {
        //             d.macid = '{{$macid}}';
        //             // d.seacrh_company_id = $('select[name=company_id]').val();
        //         }
        //     },
        //     oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
        //     aaSorting: [[6, 'desc']],
        //     columns: [

        //         {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        //         {data: 'BMS_BAT_CH', name: 'BMS_BAT_CH',orderable: false, searchable: false },
        //         {data: 'BMS_BAT_DH', name: 'BMS_BAT_DH',orderable: false, searchable: false },
        //         {data: 'BMS_ERR', name: 'BMS_ERR',orderable: false, searchable: false },
        //         {data: 'BUS_SOC', name: 'BUS_SOC',orderable: false, searchable: false },
        //         {data: 'CELL_IMB', name: 'CELL_IMB',orderable: false, searchable: false },
        //         {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
        //     ],

        // });
        var CWTableRx = $('.current-warning-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-current-war-list-by-machine') }}",
                data: function (d) {
                    d.macid = '{{$macid}}';
                    // d.seacrh_company_id = $('select[name=company_id]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'error_code', name: 'error_code',orderable: false, searchable: false },
                {data: 'title', name: 'title',orderable: false, searchable: false },
                {data: 'message', name: 'message',orderable: false, searchable: false },
                {data: 'code_date', name: 'code_date',orderable: false, searchable: false },
                {data: 'created_at', name: 'created_at', 'visible': true, orderable: true, searchable: false,
                    // render:function(data,type,row){
                    //     return moment(row.created_at).format('DD-MM-YYYY hh:mm A');;
                    // }
                },
            ],

        });

        var tableRx = $('.current-history-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-warning-list-by-machine') }}",
                data: function (d) {
                    d.macid = '{{$macid}}';
                    // d.seacrh_company_id = $('select[name=company_id]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'error_code', name: 'error_code',orderable: false, searchable: false },
                {data: 'title', name: 'title',orderable: false, searchable: false },
                {data: 'message', name: 'message',orderable: false, searchable: false },
                {data: 'code_date', name: 'code_date',orderable: false, searchable: false },
                {data: 'created_at', name: 'created_at', 'visible': true, orderable: true, searchable: false,
                    // render:function(data,type,row){
                        // return moment(row.created_at).format('DD-MM-YYYY hh:mm A');;
                    // }
                },
            ],

        });



        var tableRxNoti = $('.current-notification-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-current-noti-list-by-machine') }}",
                data: function (d) {
                    d.macid = '{{$macid}}';
                    // d.seacrh_company_id = $('select[name=company_id]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'error_code', name: 'error_code',orderable: false, searchable: false },
                {data: 'title', name: 'title',orderable: false, searchable: false },
                {data: 'message', name: 'message',orderable: false, searchable: false },
                {data: 'code_date', name: 'code_date',orderable: false, searchable: false },
                {data: 'created_at', name: 'created_at', 'visible': true, orderable: true, searchable: false,
                    // render:function(data,type,row){
                    //     return moment(row.created_at).format('DD-MM-YYYY hh:mm A');;
                    // }
                },
            ],

        });

        var tableRxNotiHis = $('.notification-history-table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-noti-list-by-machine') }}",
                data: function (d) {
                    d.macid = '{{$macid}}';
                    // d.seacrh_company_id = $('select[name=company_id]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[5, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'error_code', name: 'error_code',orderable: false, searchable: false },
                {data: 'title', name: 'title',orderable: false, searchable: false },
                {data: 'message', name: 'message',orderable: false, searchable: false },
                {data: 'code_date', name: 'code_date',orderable: false, searchable: false },
                {data: 'created_at', name: 'created_at', 'visible': true, orderable: true, searchable: false,
                    // render:function(data,type,row){
                    //     return moment(row.created_at).format('DD-MM-YYYY hh:mm A');;
                    // }
                },
            ],

        });

        $(document).on('click', '.activeInactiveByAdmin', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

                $.ajax({
                    url: '{{url("active-inactive-users")}}',
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
