@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection

@section('content')
<!--app-content open-->
<?php
    $macid      = $battery_details->macid ?? '';
    $company_id = $battery_details->company_id ?? '';
    $userID     = $battery_details->id ?? '';
    $ids        = $battery_details->id ?? '';

    $company_login_id = session()->get('company_login_id');
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $is_verified = 'false';
    if(isset($battery_details->verified) && $battery_details->verified == "DEVICE_VARIFIED")
    {
        $is_verified = 'true';
    }
    $is_live_view = 'false';
    if (Gate::allows('LiveView')) {
        $is_live_view = 'true';
    }

    // Dynamics Icons sets
    $icons_setting = getIconSettings($company_id);

    // Active Icons
    $activeGeneratorIcon = [];
    $activeACSolarIcon   = [];
    $activeGridIcon      = [];
    $activeDCSolarIcon   = [];
    $activeUnitIcon      = [];
    $activeViewDetails   = [];
    $activeLoadIcon      = [];
    $activeSolarIcon     = [];
    $activeBatteryIcon   = [];
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeGeneratorIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Generator';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeACSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'AC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeGridIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Grid';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeDCSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'DC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeUnitIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Unit';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeViewDetails = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'PowerBank Details';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeLoadIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Load';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $activeBatteryIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'active' && $item['icon_label'] == 'Battery';
        }));
    }

    $activeGeneratorIcon = isset($activeGeneratorIcon[0]) ? $activeGeneratorIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/charging.svg');
    $activeACSolarIcon   = isset($activeACSolarIcon[0]) ? $activeACSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/acsolar.svg');
    $activeGridIcon      = isset($activeGridIcon[0]) ? $activeGridIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/tower.svg');
    $activeDCSolarIcon   = isset($activeDCSolarIcon[0]) ? $activeDCSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/dcsolar.svg');
    $activeUnitIcon      = isset($activeUnitIcon[0]) ? $activeUnitIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/unit.svg');
    $activeViewDetails   = isset($activeViewDetails[0]) ? $activeViewDetails[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/view_details.png');
    $activeLoadIcon      = isset($activeLoadIcon[0]) ? $activeLoadIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/load.svg');
    $activeSolarIcon     = isset($activeSolarIcon[0]) ? $activeSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/solar.svg');
    $activeBatteryIcon   = isset($activeBatteryIcon[0]) ? $activeBatteryIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/battery_full_icon.svg');

    // Inactive Icons
    $inactiveGeneratorIcon = [];
    $inactiveACSolarIcon   = [];
    $inactiveGridIcon      = [];
    $inactiveDCSolarIcon   = [];
    $inactiveUnitIcon      = [];
    $inactiveViewDetails   = [];
    $inactiveSolarIcon     = [];
    $inactiveBatteryIcon   = [];
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveGeneratorIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Generator';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveACSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'AC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveGridIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Grid';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveDCSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'DC Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveUnitIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Unit';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveViewDetails = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'PowerBank Details';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveLoadIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Load';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveSolarIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Solar';
        }));
    }
    if(isset($icons_setting) && count($icons_setting) > 0){
        $inactiveBatteryIcon = array_values(array_filter($icons_setting, function ($item) {
            return $item['status'] === 'inactive' && $item['icon_label'] == 'Battery';
        }));
    }

    $inactiveGeneratorIcon = isset($inactiveGeneratorIcon[0]) ? $inactiveGeneratorIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/charging-off.svg');
    $inactiveACSolarIcon   = isset($inactiveACSolarIcon[0]) ? $inactiveACSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/acsolar-xl-off-icon.svg');
    $inactiveGridIcon      = isset($inactiveGridIcon[0]) ? $inactiveGridIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/tower-off.svg');
    $inactiveDCSolarIcon   = isset($inactiveDCSolarIcon[0]) ? $inactiveDCSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/dcsolar-xl-off-icon.svg');
    $inactiveUnitIcon      = isset($inactiveUnitIcon[0]) ? $inactiveUnitIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/unit-off.svg');
    $inactiveViewDetails   = isset($inactiveViewDetails[0]) ? $inactiveViewDetails[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/view_details.png');
    $inactiveLoadIcon      = isset($inactiveLoadIcon[0]) ? $inactiveLoadIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/load-xl-off-icon.svg');
    $inactiveSolarIcon     = isset($inactiveSolarIcon[0]) ? $inactiveSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/solar-xl-off-icon.svg');
    $inactiveBatteryIcon   = isset($inactiveBatteryIcon[0]) ? $inactiveBatteryIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/battery_full_icon_off.svg');
?>
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER 1-->
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
                        @can('isAdmin')
                            <li class="breadcrumb-item"><a href="{{ url('/company') }}">Company</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/system-overview/'.$company_id) }}">{{ $module }}</a></li>
                        @endcan
                        @can('isUser')
                            <li class="breadcrumb-item"><a href="{{ url('/system-overview') }}">{{ $module }}</a></li>
                        @endcan
                        <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                {{-- <div class="ms-auto pageheader-btn">
                    <a href="{{ url('/add-cluster') }}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                                <i class="fe fe-plus"></i>
                        </span> Create Group
                    </a>
                    <a href="{{ url('/add-device') }}" class="btn btn-success btn-icon text-white">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Create a POWRBANK
                    </a>
                </div> --}}
            </div>
            <!-- ROW-1 Start -->
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                    <p class="device-serial-number">{{ $macid ?? ''}}</p>
                                    <p class="device-serial-label">MACID</p>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                    <p class="device-serial-number">{{ $battery_details->name ?? ''}}</p>
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
                                    <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/company/'.$company_login_id.'/battery_details/'.$id) }}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon-w.png') }}" alt="battery-icon"> </span> Battery
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

                                    <a href="{{url('/company/'.$company_login_id.'/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                    </a>
                                    <?php if(auth()->guard('admin')->user()->role_id == $adminRoleId) : ?>

                                        <a href="{{url('/company/'.$company_login_id.'/remort-access/'.$id)}}" class="btn bg-color-default text-black  device-btn-width device-btn-text me-2 remort_access_btn mb-2 d-none" style="width:260px !important">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access Setting
                                        </a>
                                        <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2 deleteDeviceBtn">
                                            <i class="fa fa-trash"></i> Delete Device
                                        </a>
                                    <?php endif; ?>


                                <?php else : ?>
                                    <a href="{{ url('/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon.png') }}" alt="dashbord-icon"> </span> Dashboard
                                    </a>

                                    <a href="{{ url('/battery_details/'.$id) }}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/battry-icon-w.png') }}" alt="battery-icon"> </span> Battery
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
                                    @can('DeviceManagementDelete')
                                    <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2 deleteDeviceBtn">
                                        <i class="fa fa-trash"></i> Delete Device
                                    </a>
                                    @endcan
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-Buttons Close -->

            <!-- ROW-2 Start -->
            <div class="row permission-msg-row justify-content-center d-none">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="card text-white bg-secondary">
                        <div class="card-body text-center">
                            <h1> Permission denied </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bty-row">
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="card battry-card-voltage">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <p class="battery-voltage-text V_DC">0.00 V</p>
                                    <p class="battery-voltage-text-child">Voltage</p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/battery_voltage.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="card battry-card-current">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <p class="battery-voltage-text I_DC">0.00 A</p>
                                    <p class="battery-voltage-text-child">Current</p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/battery_current.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="card battry-card-power">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <p class="battery-voltage-text P_DC">0.00 W</p>
                                    <p class="battery-voltage-text-child">Power</p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/battery_power.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="card battry-card-state-of-charge">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <p class="battery-voltage-text SOC">0.00 %</p>
                                    <p class="battery-voltage-text-child">State of Charge</p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/battery-charging.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ROW-2 Close -->

            <!-- ROW-3 Start -->
            <div class="row bty-row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="device-card-title">Battery Status</h3>
                            {{-- <img src="{{ url('theme-asset/images/icon/charging-label.png') }}" alt="img" class="img-fluid margin-left-10"> --}}
                            <?php if ($machine_status == "ON") : ?>
                            <img src="{{ url('theme-asset/images/icon/connecting.png') }}" alt="img" style="width: 120px" class="img-fluid margin-left-10 connecting-btn {{$machine_status == 'ON' ? '' : 'd-none' }}">
                            <?php elseif ($machine_status == "Inverter_disconnected") : ?>
                                <h3 class="device-card-title Inverter_disconnected {{$machine_status == 'Inverter_disconnected' ? '' : 'd-none' }}">: Inverter Disconnected</h3>
                            <?php endif; ?>
                            <img src="{{ url('theme-asset/images/icon/ideal-abel.png') }}" alt="img" class="img-fluid margin-left-10 ideal-abel-btn d-none">
                            <img src="{{ url('theme-asset/images/icon/charging-label.png') }}" alt="img" class="img-fluid margin-left-10 on-btn d-none">
                            <img src="{{ url('theme-asset/images/icon/discharge-label.svg') }}" alt="img" class="img-fluid margin-left-10 discharge-btn d-none">
                            <img src="{{ url('theme-asset/images/icon/off-label.svg') }}" alt="img" class="img-fluid margin-left-10 off-btn {{$machine_status == 'OFF' ? '' : 'd-none' }}">
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-primary card-border-radius device-card-height justify-content-center generator-card card-machine-off machine-off card-z-index">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeGeneratorIcon }}" alt="img" class="img-fluid generator-img-on d-none">
                                                    <img src="{{ $inactiveGeneratorIcon }}" alt="img" class="img-fluid generator-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-primary mb-0 text-machine-off machine-off-text-color">Generator</h5>
                                                    <h3 class="card-kw-number-1 mb-0 E1-KWH text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Genrator_to_battery_Power text-machine-off machine-off-text-color"> 0 W</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">

                                <div class="card card-border-danger card-border-radius device-card-height justify-content-center card-z-index acdc-card card-machine-off machine-off">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeSolarIcon }}" alt="img" class="img-fluid acdc-img-on d-none">
                                                    <img src="{{ $inactiveSolarIcon }}" alt="img" class="img-fluid acdc-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-danger mb-0 text-machine-off machine-off-text-color">Solar</h5>
                                                    <h3 class="card-kw-number-1 mb-0 Solar_to_Grid text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Solar_to_battery_Power text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
                                    <img src="{{ url('theme-asset/images/battery/grid-to-battery.gif') }}" alt="img" class="bty-grid-to-battery-line-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/battery/battery-to-grid.gif') }}" alt="img" class="bty-battery-to-grid-line-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/battery/gen-to-battery.gif') }}" alt="img" class="bty-get-to-battry-line-img animation-line d-none">


                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <div class="card card-border-unit card-border-radius battry-card-height card-z-index battery-card card-machine-off machine-off">
                                        <div class="row text-center">
                                            <div class="col-12">
                                                <div class="card-body p-4">
                                                    <h5 class="bty-card-text-header custom-color-unit mt-5 text-machine-off machine-off-text-color">Battery</h5>
                                                    <h5 class="bty-card-text-header mt-5 text-machine-off machine-off-text-color SOC">0%</h5>
                                                    <img src="{{ $activeBatteryIcon }}" alt="img" class="img-fluid mt-2 battery-img-on d-none" style="width: 105px">
                                                    <img src="{{ $inactiveBatteryIcon }}" alt="img" class="img-fluid mt-2 battery-img-off" style="width: 105px">
                                                    <h3 class="bty-card-text mt-5 text-machine-off machine-off-text-color bty-charge-text"> Charging </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
                                    <img src="{{ url('theme-asset/images/battery/solar-to-battery.gif') }}" alt="img" class="bty-solar-to-battery-line-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/battery/battery-to-load.gif') }}" alt="img" class="bty-battry-to-load-line-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/battery/load-to-battery.gif') }}" alt="img" class="bty-load-to-battry-line-img animation-line d-none">
                                </div>
                            </div>
                            <div class="row justify-content-between bty-last-row-card">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-black card-border-radius device-card-height justify-content-center card-z-index grid-card card-machine-off machine-off">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeGridIcon }}" alt="img" class="img-fluid grid-img-on d-none">
                                                    <img src="{{ $inactiveGridIcon }}" alt="img" class="img-fluid grid-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-black mb-0 text-machine-off machine-off-text-color">Grid</h5>
                                                    <h3 class="card-kw-number-1 mb-0 E2-KWH text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Grid_to_battery_Power text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-success  card-border-radius device-card-height justify-content-center card-z-index load-card card-machine-off machine-off">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeLoadIcon }}" alt="img" class="img-fluid load-img-on d-none">
                                                    <img src="{{ $inactiveLoadIcon }}" alt="img" class="img-fluid load-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-success mb-0 text-machine-off machine-off-text-color">Load</h5>
                                                    <h3 class="card-kw-number-1 mb-0 E_OUT text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Load_P_DC text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="row d-flex justify-content-center bty-health-row-card mt-2">
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <div class="row bty-helth-card-row">
                                        <img src="{{ url('theme-asset/images/state-of-health-icon.svg') }}" alt="img" class="img-fluid bty-icon-img">
                                        <h5 class="bty-health-icon-text text-machine-off machine-off-text-color"> State of Health </h5>
                                        <h5 class="bty-health-icon-text custom-color-unit text-machine-off machine-off-text-color SOH"> 0 % </h5>
                                        <img src="{{ url('theme-asset/images/icon/battery-state-good-lable.svg') }}" alt="img" class="img-fluid bty-health-good-icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-3 Close -->
        </div>
    </div>
</div>
@endsection
@section('page_level_js')
<!-- CHARTJS JS -->
<script src="{{ url('theme-asset/plugins/chart/Chart.bundle.js')}} "></script>
{{-- <script src="{{ url('theme-asset/js/chart.js')}}"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="{{ url('assets/js/mqtt4.3.7.min.js') }}" type="text/javascript"></script>

<!-- BOOTSTRAP-DATERANGEPICKER JS -->
<script src="{{ url('theme-asset/plugins/bootstrap-daterangepicker/moment.min.js')}}"></script>
<script src="{{ url('theme-asset/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

<!-- INTERNAL Bootstrap-Datepicker js-->
<script src="{{ url('theme-asset/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

<!-- DATEPICKER JS -->
<script src="{{ url('theme-asset/plugins/date-picker/date-picker.js') }}"></script>
<script src="{{ url('theme-asset/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ url('theme-asset/plugins/input-mask/jquery.maskedinput.js') }}"></script>
<script src="{{ url('theme-asset/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    const machine_status    = "{{ $machine_status }}";
    const machine_timeout   = 40;

    const macid = "{{ $macid }}";
    const ip    = "{{ config('constants.MQTT_IP') }}";
    const port  = {{ config('constants.MQTT_PORT') }};
    var is_verified = "{{ $is_verified }}";
    var userID      = "{{ $userID }}";
    var is_live_view = "{{ $is_live_view }}";
    var is_latest_data  = "{{ $is_latest_data }}";
    var company_id = "{{ $company_id ?? ''}}";
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
        getLast5MinitData(macid);
        // Check machine status ON / OFF
        if(is_live_view == 'true'){
            $(".permission-msg-row").addClass('d-none');
            if(machine_status == 'ON' || machine_status == 'Inverter_disconnected')
            {
                var i = 0;
                setInterval(function() {
                    i++;
                    // console.log(i);
                    if(machine_timeout < i){
                        turn_off_machine();
                    }
                }, 1000);
            } else {
                $('.bty-charge-text').html('');
                $('.bty-charge-text').html('Off');
            }
        } else {
            $(".bty-row").css("filter", "blur(6px)");
            $(".permission-msg-row").removeClass('d-none');
            // swal({
            //     title: "Permission denied",
            //     type: "warning",
            //     showCancelButton: false,
            //     confirmButtonClass: "btn-primary",
            //     confirmButtonText: " Back ",
            //     closeOnConfirm: false,
            //     closeOnCancel: false
            // },
            // function(isConfirm) {
            //     if (isConfirm) {
            //         location = "{{url('/system-overview')}}";
            //     }
            // });
        }
        $( ".fc-datepicker" ).datepicker({
            "setDate": new Date(),
            "autoclose": true
        });

        getCurrenTime();
        let client = mqtt.connect('ws://' + ip + ':' + port, options);
        client.on('connect', function(data) {
            var msgDataRetain = '';
            // client.publish('read_data', msgDataRetain, {retain : true}, function(err) {
            //     if (err) {
            //         console.log('ERROR read_data retain =>', err);
            //     }
            // });

            client.subscribe('read_data/'+macid, {qos : 2}, function(err) {
                if (!err) {
                    // client.publish('read_data', 'Hello mqtt Self Test');
                }

                if (err) {
                    console.log('ERROR read_data =>', err);
                }
            });

            // var msgData = 'Hello, this is data to write';
            // client.publish('write_data', msgData, function(err) {
            //     if (err) {
            //         console.log('ERROR write_data =>', err);
            //     }
            // });
        });

        client.on('message', function(topic, message) {
                // message is Buffer
            //    console.log('message => ', message.toString());
            //    console.log('Data From MQTT => ');
            //    console.log('topic => ', topic);
               if (topic == 'read_data/'+macid) {
                    var msgData = message.toString();
                    var json;
                    try {
                        json = JSON.parse(msgData);
                    } catch (e) {}
                   console.log('json ====>', json.data);
                    i = 0; // Reset Machine TimeOut

                    $('.battery-card').removeClass('machine-off');
                    $('.battery-img-on').removeClass('d-none');
                    $('.battery-img-off').addClass('d-none');
                    $('.battery-card').find('.text-machine-off').removeClass('machine-off-text-color');

                   if(json.data && json.data.Contain)
                   {
                        getCurrenTime();
                        btn_charge_discharge(json);
                        turn_on_animation(json);
                        turn_on_icon(json);
                        let content = json.data.Contain;
                        switch (content) {

                            case 'Battery':
                                set_battery_details(json);
                                break;

                            case 'Grid/Genset':
                                set_grid_details(json);
                                break;

                            // case 'PV':
                            //     set_pv_details(json);
                            //     break;

                            case 'Alarms/State':
                                set_alarms_state_details(json);
                                break;

                            case 'System_calculated':
                                set_system_calculated(json)
                                break;

                            case 'Sub_System_calculated':
                                set_sub_system_calculated(json)
                                break;

                            case 'Auxiliary':
                                set_auxiliary(json)
                                break;

                            case 'Inverter_disconnected':
                                set_inverter_disconnected(json)
                                break;

                            default:
                                break;
                        }
                   }
               }
        });
        client.on('error', function(error) {
            console.log('Error => ', error);
        });

        function btn_charge_discharge(json){
            if(json.data)
            {
                var contain = json.data.Contain;
                if(contain == "Battery")
                {
                    var P_DC_BTN   = parseFloat(json.data['Power']['P_DC(W)']);
                    // if(P_DC_BTN >= 1000)
                    // {
                    //     $('.on-btn').addClass('d-none');
                    //     $('.connecting-btn').addClass('d-none');
                    //     $('.off-btn').addClass('d-none');
                    //     $('.ideal-abel-btn').addClass('d-none');
                    //     $('.Inverter_disconnected').addClass('d-none');
                    //     $('.discharge-btn').removeClass('d-none');

                    // } else if(P_DC_BTN < 0){
                    //     if(Math.abs(P_DC_BTN) <= 1000) // convert nagative to positive value original(-1000)
                    //     {
                    //         $('.connecting-btn').addClass('d-none');
                    //         $('.off-btn').addClass('d-none');
                    //         $('.discharge-btn').addClass('d-none');
                    //         $('.Inverter_disconnected').addClass('d-none');
                    //         $('.ideal-abel-btn').addClass('d-none');
                    //         $('.on-btn').removeClass('d-none');
                    //     } else {
                    //         $('.on-btn').addClass('d-none');
                    //         $('.connecting-btn').addClass('d-none');
                    //         $('.discharge-btn').addClass('d-none');
                    //         $('.ideal-abel-btn').addClass('d-none');
                    //         $('.Inverter_disconnected').addClass('d-none');
                    //         $('.off-btn').removeClass('d-none');
                    //     }

                    // }  else if(P_DC_BTN < 1){
                    //     $('.on-btn').addClass('d-none');
                    //     $('.connecting-btn').addClass('d-none');
                    //     $('.discharge-btn').addClass('d-none');
                    //     $('.ideal-abel-btn').addClass('d-none');
                    //     $('.Inverter_disconnected').addClass('d-none');
                    //     $('.off-btn').removeClass('d-none');
                    // } else {
                    //     $('.on-btn').addClass('d-none');
                    //     $('.connecting-btn').addClass('d-none');
                    //     $('.discharge-btn').addClass('d-none');
                    //     $('.off-btn').addClass('d-none');
                    //     $('.Inverter_disconnected').addClass('d-none');
                    //     $('.ideal-abel-btn').removeClass('d-none');
                    // }
                    if(P_DC_BTN <= -1000){
                        $('.on-btn').addClass('d-none');
                        $('.connecting-btn').addClass('d-none');
                        $('.off-btn').addClass('d-none');
                        $('.Inverter_disconnected').addClass('d-none');
                        $('.ideal-abel-btn').addClass('d-none');
                        $('.discharge-btn').removeClass('d-none');
                        console.log('System_discharging');

                    } else  if(P_DC_BTN >= 1000){
                        $('.connecting-btn').addClass('d-none');
                        $('.off-btn').addClass('d-none');
                        $('.discharge-btn').addClass('d-none');
                        $('.Inverter_disconnected').addClass('d-none');
                        $('.ideal-abel-btn').addClass('d-none');
                        $('.on-btn').removeClass('d-none');
                        console.log('System_charging');
                    } else if(P_DC_BTN < 1 ){
                        $('.on-btn').addClass('d-none');
                        $('.connecting-btn').addClass('d-none');
                        $('.discharge-btn').addClass('d-none');
                        $('.ideal-abel-btn').addClass('d-none');
                        $('.Inverter_disconnected').addClass('d-none');
                        $('.off-btn').removeClass('d-none');
                        console.log('System_off');
                    } else {

                        $('.on-btn').addClass('d-none');
                        $('.connecting-btn').addClass('d-none');
                        $('.discharge-btn').addClass('d-none');
                        $('.off-btn').addClass('d-none');
                        $('.Inverter_disconnected').addClass('d-none');
                        $('.ideal-abel-btn').removeClass('d-none');
                        console.log('System_idel');
                    }
                     // Check Battry Status
                    checkStatus(P_DC_BTN);
                }

            }
        }

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

        function set_grid_details(json) {

            if(json.data["Source Selection"])
            {
                var voltage_total = 0;
                $.each(json.data["Source Selection"],function(key,val){
                    // Voltage
                    if(key == "O_V_L1(V)" || key == "O_V_L2(V)" || key == "O_V_L3(V)")
                    {
                        voltage_total += parseInt(val);
                    }
                });
                if(voltage_total > 0)
                {
                    // voltage_total = (voltage_total)*1.473;
                    voltage_total = (voltage_total) / 1.732;
                    voltage_total = tofixNo(voltage_total);
                    $('.total_voltage').html('');
                    $('.total_voltage').html(voltage_total+' V');
                }
            }
            if(json.data["Frequency"])
            {
                var frequency_total = 0;
                $.each(json.data["Frequency"],function(key,val){
                    // Frequency
                    if(key == "Out_F(Hz)")
                    {
                        frequency_total += parseInt(val);
                    }
                });
                if(frequency_total > 0)
                {
                    frequency_total = tofixNo(frequency_total);
                    $('.total_frequency').html('');
                    $('.total_frequency').html(frequency_total+' Hz');
                }
            }

        }

        function set_battery_details(json) {
            if(json.data)
            {
                var E1_KWH   = json.data['Energy']['E1(KWH)'];
                var E2_KWH   = json.data['Energy']['E2(KWH)'];
                var E_OUT    = json.data['Energy']['E_OUT(KWH)'];
                var V_DC     = json.data['Voltage']['V_DC(V)'];
                var I_DC     = json.data['Current']['I_DC(A)'];
                var P_DC     = json.data['Power']['P_DC(W)'];
                var SOC      = json.data['Status']['SOC(%)'];
                var SOH      = json.data['Status']['SOH(%)'];

                if( typeof E1_KWH !== 'undefined')
                {
                    $('.E1-KWH').html('');
                    if(E1_KWH == 0)
                    {
                        $('.E1-KWH').html(E1_KWH+' Wh');
                    } else {
                        $('.E1-KWH').html(E1_KWH+'Wh');
                    }
                }
                if( typeof E2_KWH !== 'undefined')
                {
                    $('.E2-KWH').html('');
                    if(E2_KWH == 0)
                    {
                        $('.E2-KWH').html(E2_KWH+' Wh');
                    } else {
                        $('.E2-KWH').html(E2_KWH+'Wh');
                    }
                }
                if( typeof E_OUT !== 'undefined')
                {
                    $('.E_OUT').html('');
                    if(E2_KWH == 0)
                    {
                        $('.E_OUT').html(E_OUT+' Wh');
                    } else {
                        $('.E_OUT').html(E_OUT+'Wh');
                    }
                }
                if( typeof V_DC !== 'undefined')
                {
                    $('.V_DC').html('');
                    V_DC = unitConverter(V_DC);
                    if(V_DC == 0)
                    {
                        $('.V_DC').html(V_DC+' V');
                    } else {
                        $('.V_DC').html(V_DC+'V');
                    }
                }
                if( typeof I_DC !== 'undefined')
                {
                    $('.I_DC').html('');
                    if(I_DC == 0)
                    {
                        $('.I_DC').html(I_DC+' A');
                    } else {
                        $('.I_DC').html(I_DC+'A');
                    }
                }
                if( typeof P_DC !== 'undefined')
                {
                    $('.P_DC').html('');
                    if(P_DC == 0)
                    {
                        $('.P_DC').html(P_DC+' W');
                    } else {
                        $('.P_DC').html(P_DC+'W');
                    }

                }
                if( typeof SOC !== 'undefined')
                {
                    $('.SOC').html('');
                    $('.SOC').html(SOC+'%');
                }
                if( typeof SOH !== 'undefined')
                {
                    $('.SOH').html('');
                    $('.SOH').html(SOH+'%');
                }
            }
        }

        function set_pv_details(json) {
            if(json.data.Power)
            {
                var ac_solar_total = 0;
                var dc_solar_total = 0;
                $.each(json.data.Power,function(key,val){
                    if(key == "PV_AC_O/L1(W)" || key == "PV_AC_O/L2(W)" || key == "PV_AC_O/L3(W)")
                    {
                        ac_solar_total += parseInt(val);
                    }
                    if(key == "PV_DC(W)")
                    {
                        dc_solar_total += parseInt(val);;
                    }
                });
                if(ac_solar_total > 0)
                {
                    $('.PV_AC_O_L2').html('');
                    $('.PV_AC_O_L2').html(ac_solar_total+'.00 W');
                }
                if(dc_solar_total > 0)
                {
                    $('.PV_DC').html('');
                    $('.PV_DC').html(dc_solar_total+'.00 W');
                }
            }
        }

        function set_alarms_state_details(json) {
            $('.alert-alarms').addClass('d-none');
            if(json.data["Battery Alarms"])
            {
                if (json.data["Battery Alarms"]["BMS_BAT_CH"] == 1) {
                    $('.alert-alarms').removeClass('d-none');
                }
            }
        }
        function tofixNo(value){
            return Number(value).toFixed(2);
        }

        function set_system_calculated(json){
            if(json.data)
            {
                var Genrator_to_battery_Power = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var Grid_to_battery_Power     = parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    = parseFloat(json.data['Solar_to_battery_Power(W)']);
                var Solar_to_Grid             = parseFloat(json.data['Solar_to_Grid(KWh)']);

                if( typeof Genrator_to_battery_Power !== 'undefined' && !isNaN(Genrator_to_battery_Power))
                {
                    Genrator_to_battery_Power = unitConverter(Genrator_to_battery_Power);
                    $('.Genrator_to_battery_Power').html('');
                    $('.Genrator_to_battery_Power').html(Genrator_to_battery_Power+' W');
                }

                if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    Grid_to_battery_Power = unitConverter(Grid_to_battery_Power);
                    $('.Grid_to_battery_Power').html('');
                    $('.Grid_to_battery_Power').html(Grid_to_battery_Power+'W');
                }

                if( typeof Solar_to_battery_Power !== 'undefined' && !isNaN(Solar_to_battery_Power))
                {
                    Solar_to_battery_Power = unitConverter(Solar_to_battery_Power);
                    $('.Solar_to_battery_Power').html('');
                    $('.Solar_to_battery_Power').html(Solar_to_battery_Power+'W');
                }

                if( typeof Solar_to_Grid !== 'undefined' && !isNaN(Solar_to_Grid))
                {
                    Solar_to_Grid = unitConverter(Solar_to_Grid);
                    $('.Solar_to_Grid').html('');
                    $('.Solar_to_Grid').html(Solar_to_Grid+'Wh');
                }
            }
        }

        function set_sub_system_calculated(json){
            if(json.data)
            {
                var Genrator_to_battery_Power = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var Grid_to_battery_Power     = parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    = parseFloat(json.data['Solar_to_battery_Power(W)']);
                var Solar_to_Grid             = parseFloat(json.data['Solar_to_Grid(KWh)']);

                if( typeof Genrator_to_battery_Power !== 'undefined' && !isNaN(Genrator_to_battery_Power))
                {
                    Genrator_to_battery_Power = unitConverter(Genrator_to_battery_Power);
                    $('.Genrator_to_battery_Power').html('');
                    $('.Genrator_to_battery_Power').html(Genrator_to_battery_Power+'W');
                }

                if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    Grid_to_battery_Power = unitConverter(Grid_to_battery_Power);
                    $('.Grid_to_battery_Power').html('');
                    $('.Grid_to_battery_Power').html(Grid_to_battery_Power+'W');
                }

                if( typeof Solar_to_battery_Power !== 'undefined'  && !isNaN(Solar_to_battery_Power))
                {
                    Solar_to_battery_Power = unitConverter(Solar_to_battery_Power);
                    $('.Solar_to_battery_Power').html('');
                    $('.Solar_to_battery_Power').html(Solar_to_battery_Power+'W');
                }

                if( typeof Solar_to_Grid !== 'undefined' && !isNaN(Solar_to_Grid))
                {
                    Solar_to_Grid = unitConverter(Solar_to_Grid);
                    $('.Solar_to_Grid').html('');
                    $('.Solar_to_Grid').html(Solar_to_Grid+'Wh');
                }

            }
        }

        function set_auxiliary(json){
            if(json.data)
            {
                var Temp_1     = json.data['Temp_1'];
                var Temp_2     = json.data['Temp_2'];
                var Temp_3     = json.data['Temp_3'];

                if( typeof Temp_1 !== 'undefined')
                {
                    $('.Temp_1').html('');
                    $('.Temp_1').html(Temp_1+'<sup>o</sup>C');
                }

                if( typeof Temp_1 !== 'undefined')
                {
                    $('.Temp_2').html('');
                    $('.Temp_2').html(Temp_2+'<sup>o</sup>C');
                }

                if( typeof Temp_1 !== 'undefined')
                {
                    $('.Temp_3').html('');
                    $('.Temp_3').html(Temp_3+'<sup>o</sup>C');
                }



            }
        }
        function set_inverter_disconnected(json) {
             $('.on-btn').addClass('d-none');
             $('.connecting-btn').addClass('d-none');
             $('.discharge-btn').addClass('d-none');
             $('.off-btn').addClass('d-none');
             $('.Inverter_disconnected').removeClass('d-none');
             turn_off_machine();
        }

        function turn_on_animation(json){
            var contain = json.data.Contain
            let P_DC = '';
            if(contain == "Battery")
            {
                P_DC    = json.data['Power']['P_DC(W)'];
            }

            if(contain == "System_calculated")
            {
                var Gen_Tot_Pow       = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var DC_Solar_Power    = parseFloat(json.data['DC_Solar_Power(W)']);
                var AC_Solar_Tot_Pow  = parseFloat(json.data['AC_Solar_Tot_Pow(W)']);
                var Grid_to_battery_Power     =  parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    =  parseFloat(json.data['Solar_to_battery_Power(W)']);

                if(Gen_Tot_Pow > 5000)
                {
                    $('.bty-get-to-battry-line-img').removeClass('d-none');
                }

                // if(DC_Solar_Power > 500)
                // {
                //     $('.dcsolar-to-unit-animation-img').removeClass('d-none');
                // }

                // if(AC_Solar_Tot_Pow > 1.5)
                // {
                //     $('.acsolar-to-unit-animation-img').removeClass('d-none');
                // }


                // Grid to battry Animation
                if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    if(Grid_to_battery_Power > 5000)
                    {
                        $('.bty-grid-to-battery-line-img').removeClass('d-none');
                    } else {
                        $('.bty-grid-to-battery-line-img').addClass('d-none');
                    }
                }

                // AC-DC Solar
                if( typeof Solar_to_battery_Power !== 'undefined' && !isNaN(Solar_to_battery_Power))
                {
                    if(Solar_to_battery_Power > 250)
                    {
                        $('.bty-solar-to-battery-line-img').removeClass('d-none');
                    } else {
                        $('.bty-solar-to-battery-line-img').addClass('d-none');
                    }
                }

            }

            if(contain == "Sub_System_calculated")
            {
                var Gen_Tot_Pow      = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var Out_Tot_Pow      = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_to_battery_Power     = parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    = parseFloat(json.data['Solar_to_battery_Power(W)']);

                if(Gen_Tot_Pow > 5000)
                {
                    $('.bty-get-to-battry-line-img').removeClass('d-none');
                }

                if( typeof P_DC !== 'undefined' )
                {
                    if (P_DC < -1000) { // convert nagative value to integer
                        if(typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow))
                        {
                            $('.Load_P_DC').html('');
                            $('.Load_P_DC').html(Out_Tot_Pow+' W');
                        }
                    } else {
                        $('.Load_P_DC').html('');
                        $('.Load_P_DC').html('0 W');
                    }
                }

                if(Out_Tot_Pow > 5)
                {
                    // $('.bty-load-to-battry-line-img').removeClass('d-none');
                    $('.bty-battry-to-load-line-img').removeClass('d-none');
                }

                // Grid to battry Animation
                if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    if(Grid_to_battery_Power > 5000)
                    {
                        $('.bty-grid-to-battery-line-img').removeClass('d-none');
                    } else {
                        $('.bty-grid-to-battery-line-img').addClass('d-none');
                    }
                }

                // AC-DC Solar
                if( typeof Solar_to_battery_Power !== 'undefined' && !isNaN(Solar_to_battery_Power))
                {
                    if(Solar_to_battery_Power > 250)
                    {
                        $('.bty-solar-to-battery-line-img').removeClass('d-none');
                    } else {
                        $('.bty-solar-to-battery-line-img').addClass('d-none');
                    }
                }
            }


        }

        function turn_on_icon(json){
            var contain = json.data.Contain
            if(contain == "System_calculated")
            {
                var Gen_Tot_Pow       = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var DC_Solar_Power    = parseFloat(json.data['DC_Solar_Power(W)']);
                var AC_Solar_Tot_Pow  = parseFloat(json.data['AC_Solar_Tot_Pow(W)']);
                var Grid_to_battery_Power     = parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    = parseFloat(json.data['Solar_to_battery_Power(W)']);
                if(Gen_Tot_Pow > 1000)
                {
                    $('.generator-card').removeClass('machine-off');
                    $('.generator-img-on').removeClass('d-none');
                    $('.generator-img-off').addClass('d-none');
                    $('.generator-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                // if(DC_Solar_Power > 100)
                // {
                //     $('.dcsolar-card').removeClass('machine-off');
                //     $('.dcsolar-img-on').removeClass('d-none');
                //     $('.dcsolar-img-off').addClass('d-none');
                //     $('.dcsolar-card').find('.text-machine-off').removeClass('machine-off-text-color');
                // }

                // if(AC_Solar_Tot_Pow > 500)
                // {
                //     $('.acsolar-card').removeClass('machine-off');
                //     $('.acsolar-img-on').removeClass('d-none');
                //     $('.acsolar-img-off').addClass('d-none');
                //     $('.acsolar-card').find('.text-machine-off').removeClass('machine-off-text-color');
                // }

                if (Gen_Tot_Pow > 0 || DC_Solar_Power > 0 || AC_Solar_Tot_Pow > 0) {
                    $('.battery-card').removeClass('machine-off');
                    $('.battery-img-on').removeClass('d-none');
                    $('.battery-img-off').addClass('d-none');
                    $('.battery-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }
                // Grid to battry
                if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    if(Grid_to_battery_Power > 1000)
                    {
                        $('.grid-card').removeClass('machine-off');
                        $('.grid-img-on').removeClass('d-none');
                        $('.grid-img-off').addClass('d-none');
                        $('.grid-card').find('.text-machine-off').removeClass('machine-off-text-color');
                    } else {
                        $('.grid-card').addClass('machine-off');
                        $('.grid-img-on').addClass('d-none');
                        $('.grid-img-off').removeClass('d-none');
                        $('.grid-card').find('.text-machine-off').addClass('machine-off-text-color');
                    }
                }

                 // AC-DC Solar
                 if( typeof Solar_to_battery_Power !== 'undefined' && !isNaN(Solar_to_battery_Power))
                {
                    if(Solar_to_battery_Power > 100)
                    {
                        $('.acdc-card').removeClass('machine-off');
                        $('.acdc-img-on').removeClass('d-none');
                        $('.acdc-img-off').addClass('d-none');
                        $('.acdc-card').find('.text-machine-off').removeClass('machine-off-text-color');

                    } else {
                        $('.acdc-card').addClass('machine-off');
                        $('.acdc-img-on').addClass('d-none');
                        $('.acdc-img-off').removeClass('d-none');
                        $('.acdc-card').find('.text-machine-off').addClass('machine-off-text-color');
                    }
                }
            }

            if(contain == "Sub_System_calculated")
            {
                var Gen_Tot_Pow       = parseFloat(json.data['Genrator_to_battery_Power(W)']);
                var Out_Tot_Pow       = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_to_battery_Power     = parseFloat(json.data['Grid_to_battery_Power(W)']);
                var Solar_to_battery_Power    = parseFloat(json.data['Solar_to_battery_Power(W)']);

                if(Gen_Tot_Pow > 1000)
                {
                    $('.generator-card').removeClass('machine-off');
                    $('.generator-img-on').removeClass('d-none');
                    $('.generator-img-off').addClass('d-none');
                    $('.generator-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if(Out_Tot_Pow > 1)
                {
                    $('.load-card').removeClass('machine-off');
                    $('.load-img-on').removeClass('d-none');
                    $('.load-img-off').addClass('d-none');
                    $('.load-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                 // Grid to battry
                 if( typeof Grid_to_battery_Power !== 'undefined' && !isNaN(Grid_to_battery_Power))
                {
                    if(Grid_to_battery_Power > 1000)
                    {
                        $('.grid-card').removeClass('machine-off');
                        $('.grid-img-on').removeClass('d-none');
                        $('.grid-img-off').addClass('d-none');
                        $('.grid-card').find('.text-machine-off').removeClass('machine-off-text-color');
                    } else {
                        $('.grid-card').addClass('machine-off');
                        $('.grid-img-on').addClass('d-none');
                        $('.grid-img-off').removeClass('d-none');
                        $('.grid-card').find('.text-machine-off').addClass('machine-off-text-color');
                    }
                }

                 // AC-DC Solar
                 if( typeof Solar_to_battery_Power !== 'undefined' && !isNaN(Solar_to_battery_Power))
                {
                    if(Solar_to_battery_Power > 100)
                    {
                        $('.acdc-card').removeClass('machine-off');
                        $('.acdc-img-on').removeClass('d-none');
                        $('.acdc-img-off').addClass('d-none');
                        $('.acdc-card').find('.text-machine-off').removeClass('machine-off-text-color');

                    } else {
                        $('.acdc-card').addClass('machine-off');
                        $('.acdc-img-on').addClass('d-none');
                        $('.acdc-img-off').removeClass('d-none');
                        $('.acdc-card').find('.text-machine-off').addClass('machine-off-text-color');
                    }
                }
            }
        }

        function turn_off_machine(){
            $('.card-machine-off').addClass('machine-off');
            $('.text-machine-off').addClass('machine-off-text-color');
            $('.animation-line').addClass('d-none');
            if(machine_status == 'Inverter_disconnected')
            {
                $('.on-btn').addClass('d-none');
                $('.connecting-btn').addClass('d-none');
                $('.discharge-btn').addClass('d-none');
                $('.off-btn').addClass('d-none');
            } else {

                $('.on-btn').addClass('d-none');
                $('.connecting-btn').addClass('d-none');
                $('.discharge-btn').addClass('d-none');
                $('.off-btn').removeClass('d-none');
            }

            $('.generator-img-on').addClass('d-none');
            $('.generator-img-off').removeClass('d-none');

            $('.grid-img-on').addClass('d-none');
            $('.grid-img-off').removeClass('d-none');

            $('.acsolar-img-on').addClass('d-none');
            $('.acsolar-img-off').removeClass('d-none');

            $('.dcsolar-img-on').addClass('d-none');
            $('.dcsolar-img-off').removeClass('d-none');

            $('.load-img-on').addClass('d-none');
            $('.load-img-off').removeClass('d-none');

            $('.battery-img-on').addClass('d-none');
            $('.battery-img-off').removeClass('d-none');
        }


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

        function checkStatus(P_DC_BTN){
            if(P_DC_BTN <= -1000){
                $('.bty-charge-text').html('');
                $('.bty-charge-text').html('Discharge');
            } else  if(P_DC_BTN >= 1000){
                $('.bty-charge-text').html('');
                $('.bty-charge-text').html('Charging');
            } else if(P_DC_BTN < 1 ){
                $('.bty-charge-text').html('');
                $('.bty-charge-text').html('Off');
            } else {
                $('.bty-charge-text').html('');
                $('.bty-charge-text').html('Ideal');
            }
        }

        function getLast5MinitData(macid){
            if(macid){
                $.ajax({
                    url: '{{ url("get-last-5-minit-data") }}',
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
                            if(resp.status == 'true'){
                                if(is_latest_data == true){
                                    $.each(resp.data,function(key,json){
                                        $('.battery-card').removeClass('machine-off');
                                        $('.battery-img-on').removeClass('d-none');
                                        $('.battery-img-off').addClass('d-none');
                                        $('.battery-card').find('.text-machine-off').removeClass('machine-off-text-color');

                                        if(json.data.data && json.data.data.Contain)
                                        {
                                            json = json.data;
                                            getCurrenTime();
                                            btn_charge_discharge(json);
                                            turn_on_animation(json);
                                            turn_on_icon(json);
                                            let content = json.data.Contain;
                                            switch (content) {

                                                case 'Battery':
                                                    set_battery_details(json);
                                                    break;

                                                case 'Grid/Genset':
                                                    set_grid_details(json);
                                                    break;

                                                // case 'PV':
                                                //     set_pv_details(json);
                                                //     break;

                                                case 'Alarms/State':
                                                    set_alarms_state_details(json);
                                                    break;

                                                case 'System_calculated':
                                                    set_system_calculated(json)
                                                    break;

                                                case 'Sub_System_calculated':
                                                    set_sub_system_calculated(json)
                                                    break;

                                                case 'Auxiliary':
                                                    set_auxiliary(json)
                                                    break;

                                                case 'Inverter_disconnected':
                                                    set_inverter_disconnected(json)
                                                    break;

                                                default:
                                                    break;
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    }
                })
            }
        }

        // Delete Device
        $(document).on('click','.deleteDeviceBtn',function(){
            var _this = $(this);
            if(userID)
            {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to delete this device.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#global-loader').css('display','block');
                        notif({
                            type: "warning",
                            msg: "<b> Please wait... we are proceing device delete operation do not refresh page or close </b>",
                            position: "center",
                            width: 800,
                            autohide: false,
                            opacity: 1
                        });

                        $.ajax({
                            url: '{{ url("delete-device") }}',
                            type: 'POST',
                            data: {
                                'device_id' : userID,
                            },
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                try {
                                    data = JSON.parse(data)
                                } catch (e) {}
                                if(data.status == 'true'){
                                    $.growl.notice({
                                        title: "Success",
                                        message: "deleted successfully."
                                    });
                                    if(company_id){
                                        setTimeout(() => {
                                            location = "{{url('/company')}}" + '/'+ company_id+'/system-overview';
                                        }, 1000);
                                    } else {
                                        setTimeout(() => {
                                            location = "{{url('/system-overview')}}";
                                        }, 1000);
                                    }
                                } else {
                                    $.growl.error({
                                        title: "Error",
                                        message: "Sorry !!!  Somthing want wrong.",
                                    });
                                    if(company_id){
                                        setTimeout(() => {
                                            location = "{{url('/company')}}" + '/'+ company_id+'/system-overview';
                                        }, 1000);
                                    } else {
                                        setTimeout(() => {
                                            location = "{{url('/system-overview')}}";
                                        }, 1000);
                                    }
                                }
                            }
                        })
                    }
                });
            }
        });
});
</script>
@endsection
