@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection

@section('content')
<!--app-content open-->
<?php
    $macid      = $device_details->macid ?? '';
    $company_id = $device_details->company_id ?? '';
    $userID     = $device_details->id ?? '';
    $company_login_id = session()->get('company_login_id');
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $is_verified = 'false';
    if(isset($device_details->verified) && $device_details->verified == "DEVICE_VARIFIED")
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

    $activeGeneratorIcon = isset($activeGeneratorIcon[0]) ? $activeGeneratorIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/charging.svg');
    $activeACSolarIcon   = isset($activeACSolarIcon[0]) ? $activeACSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/acsolar.svg');
    $activeGridIcon      = isset($activeGridIcon[0]) ? $activeGridIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/tower.svg');
    $activeDCSolarIcon   = isset($activeDCSolarIcon[0]) ? $activeDCSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/dcsolar.svg');
    $activeUnitIcon      = isset($activeUnitIcon[0]) ? $activeUnitIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/unit.svg');
    $activeViewDetails   = isset($activeViewDetails[0]) ? $activeViewDetails[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/view_details.png');
    $activeLoadIcon      = isset($activeLoadIcon[0]) ? $activeLoadIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/load.svg');

    // Inactive Icons
    $inactiveGeneratorIcon = [];
    $inactiveACSolarIcon   = [];
    $inactiveGridIcon      = [];
    $inactiveDCSolarIcon   = [];
    $inactiveUnitIcon      = [];
    $inactiveViewDetails   = [];
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

    $inactiveGeneratorIcon = isset($inactiveGeneratorIcon[0]) ? $inactiveGeneratorIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/charging-off.svg');
    $inactiveACSolarIcon   = isset($inactiveACSolarIcon[0]) ? $inactiveACSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/acsolar-xl-off-icon.svg');
    $inactiveGridIcon      = isset($inactiveGridIcon[0]) ? $inactiveGridIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/tower-off.svg');
    $inactiveDCSolarIcon   = isset($inactiveDCSolarIcon[0]) ? $inactiveDCSolarIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/dcsolar-xl-off-icon.svg');
    $inactiveUnitIcon      = isset($inactiveUnitIcon[0]) ? $inactiveUnitIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/unit-off.svg');
    $inactiveViewDetails   = isset($inactiveViewDetails[0]) ? $inactiveViewDetails[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/view_details.png');
    $inactiveLoadIcon      = isset($inactiveLoadIcon[0]) ? $inactiveLoadIcon[0]['icon_img_url'] : url('/theme-asset/images/overview-icons/load-xl-off-icon.svg');

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
                                    <p class="device-serial-number">{{ $device_details->serial_no ?? ''}}</p>
                                    <p class="device-serial-label">Serial No</p>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-2">
                                    <p class="device-serial-number">{{ $device_details->name ?? ''}}</p>
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
                            <div class="row d-flex justify-content-left">


                                <?php if ($company_login_id) : ?>
                                    <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon-w.png') }}" alt="dashbord-icon"> </span> Dashboard
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
                                        <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2 deleteDeviceBtn">
                                            <i class="fa fa-trash"></i> Delete Device
                                        </a>
                                        <?php endif; ?>
                                        <a href="{{url('/company/'.$company_login_id.'/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                        </a>

                                <?php else : ?>
                                    <a href="{{ url('/device_details/'.$id) }}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/dashboard-icon-w.png') }}" alt="dashbord-icon"> </span> Dashboard
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

                                   {{-- @can('LiveView') --}}
                                    <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 machine_verified_btn d-none mb-2">
                                        <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/verified-icon.png') }}" alt="verify-machine-icon"> </span> Verify Machine
                                    </a>
                                   {{--@endcan--}} 

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
            <!-- <div class="row permission-msg-row justify-content-center d-none">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="card text-white bg-secondary">
                        <div class="card-body text-center">
                            <h1> Permission denied </h1>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row db-row">
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="card device-inverter-temp-card">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div>
                                        <p class="device-celcius Temp_1">0.00<sup>o</sup> C</p>
                                        <p class="device-celcius-text">Inverter Temperature</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/mdi_amplifier.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="card device-battery-temp-card">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div>
                                        <p class="device-celcius Temp_2">0.00<sup>o</sup> C</p>
                                        <p class="device-celcius-text">Battery Temperature</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/Battery.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="card device-ambient-temp-card">
                        <div class="card-body pb-2">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div>
                                        <p class="device-celcius Temp_3">0.00<sup>o</sup> C</p>
                                        <p class="device-celcius-text">Ambient Temperature</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="d-flex justify-content-sm-start justify-content-md-center">
                                    <img src="{{ url('theme-asset/images/icon/ambient.svg') }}" alt="img" class="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-2 Close -->

            <!-- ROW-3 Start -->
            <div class="row db-row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="device-card-title">Current Status</h3>
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
                                                    <h3 class="card-kw-number-1 mb-0 Gen_Tot_Energy text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Gen_Tot_Pow text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-warning card-border-radius device-card-height justify-content-center acsolar-card card-machine-off machine-off card-z-index">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeACSolarIcon }}" alt="img" class="img-fluid acsolar-img-on d-none">
                                                    <img src="{{ $inactiveACSolarIcon }}" alt="img" class="img-fluid acsolar-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-warning mb-0 text-machine-off machine-off-text-color">AC Solar</h5>
                                                    <h3 class="card-kw-number-1 mb-0 AC_Solar_Tot_Energy text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 AC_Solar_Tot_Pow text-machine-off machine-off-text-color">  0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <img src="{{ url('theme-asset/images/dashboard/unit-to-grid.gif') }}" alt="img" class="db-img-grid-line unit-to-grid-animation-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/dashboard/grid-to-unit.gif') }}" alt="img" class="db-img-grid-line grid-to-unit-animation-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/dashboard/gen-to-unit.gif') }}" alt="img" class="db-img-generator-line gen-to-unit-animation-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/dashboard/acsolar-to-unit.gif') }}" alt="img" class="db-img-acsolar-line acsolar-to-unit-animation-img animation-line d-none">
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <div class="card card-border-unit card-border-radius device-card-height justify-content-center unit-card card-machine-off machine-off battry-card-height card-z-index">
                                        <div class="row text-center">
                                            <div class="col-12">
                                                <div class="card-body p-4">
                                                    <img src="{{ $activeUnitIcon }}" alt="img" class="img-fluid mt-2 unit-img-on d-none">
                                                    <img src="{{ $inactiveUnitIcon }}" alt="img" class="img-fluid mt-2 unit-img-off">
                                                    <h5 class="card-kw-number-1 custom-color-unit mb-0 text-machine-off machine-off-text-color mt-3">Unit</h5>
                                                    <h3 class="card-kw-number-1 mb-0 SOC text-machine-off machine-off-text-color mt-5">0.00 % </h3>
                                                    <h5 class="card-kw-number-2 mb-0 SOH text-machine-off machine-off-text-color mt-5">0.00 V</h5>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <img src="{{ url('theme-asset/images/dashboard/dcsolar-to-unit.gif') }}" alt="img" class="db-img-dcsolar-line dcsolar-to-unit-animation-img animation-line d-none">
                                </div>
                            </div>
                            <div class="row justify-content-between db-grid-and-load-row-card">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-black card-border-radius device-card-height justify-content-center grid-card card-machine-off machine-off card-z-index">
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
                                                    <h3 class="card-kw-number-1 mb-0 Grid_Tot_Energy text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Grid_Tot_Pow text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <img src="{{ url('theme-asset/images/dashboard/unit-to-load.gif') }}" alt="img" class="db-img-unit-line unit-to-load-animation-img animation-line d-none">
                                    <img src="{{ url('theme-asset/images/dashboard/load-to-unit.gif') }}" alt="img" class="db-img-unit-line load-to-unit-animation-img animation-line d-none">
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-success  card-border-radius device-card-height justify-content-center load-card card-machine-off machine-off card-z-index">
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
                                                    <h3 class="card-kw-number-1 mb-0 Out_Tot_Energy text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 Out_Tot_Pow text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center db-dcsolar-row-card">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <div class="card card-border-danger card-border-radius device-card-height justify-content-center dcsolar-card card-machine-off machine-off card-z-index">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow mt-5">
                                                    <img src="{{ $activeDCSolarIcon }}" alt="img" class="img-fluid dcsolar-img-on d-none">
                                                    <img src="{{ $inactiveDCSolarIcon }}" alt="img" class="img-fluid dcsolar-img-off">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-danger mb-0 text-machine-off machine-off-text-color">DC Solar</h5>
                                                    <h3 class="card-kw-number-1 mb-0 DC_Solar_Energy text-machine-off machine-off-text-color"> 0 Wh </h3>
                                                    <h5 class="card-kw-number-2 mb-0 DC_Solar_Power text-machine-off machine-off-text-color"> 0 W </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-2 mb-2">
                                    <div class="row frequency-row">
                                        <div class="d-flex flex-row">
                                            <div>
                                                <img src="{{ url('theme-asset/images/icon/frequency-icon.svg') }}" alt="img" class="frequency-img-on d-none">
                                                <img src="{{ url('theme-asset/images/icon/frequency-icon-off.svg') }}" alt="img" class="frequency-img-off">
                                            </div>
                                            <div class="margin-left-10">
                                                <p class="frequency-main-text mb-0 text-machine-off machine-off-text-color"> Frequency </p>
                                                <p class="frequency-child-text mb-0 total_frequency text-machine-off machine-off-text-color"> 0.00 Hz</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-6 mb-2">
                                    <div class="row frequency-voltage-row">
                                        <div class="d-flex flex-row">
                                            <div>
                                                <img src="{{ url('theme-asset/images/icon/voltage-icon.svg') }}" alt="img" class="voltage-img-on d-none">
                                                <img src="{{ url('theme-asset/images/icon/voltage-icon-off.svg') }}" alt="img" class="voltage-img-off">
                                            </div>
                                            <div class="margin-left-10">
                                                <p class="frequency-main-text mb-0 text-machine-off machine-off-text-color"> Voltage </p>
                                                <p class="frequency-child-text mb-0 total_voltage text-machine-off machine-off-text-color"> 0.00 V</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <div class="row">
                                        <div class="d-flex flex-md-row-reverse">
                                            <?php if($company_login_id) :  ?>
                                            <a href="{{ url('/company/'.$company_login_id.'/device-alarms-list/'.$macid) }}">
                                                <div>
                                                    <img src="{{ url('theme-asset/images/icon/Notification.png') }}" alt="img">
                                                </div>
                                            </a>
                                            <?php else :  ?>
                                            @if(Gate::check('DeviceNotificationView') || Gate::check('DeviceWarningView'))
                                            <a href="{{url('device-alarms-list/'.$macid)}}">
                                                <div>
                                                    <img src="{{ url('theme-asset/images/icon/Notification.png') }}" alt="img">
                                                </div>
                                            </a>
                                            @endcan
                                            <?php endif;  ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 alert-alarms d-none">
                                <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                    <i class="fa fa-bell-o me-2" aria-hidden="true"></i>Alarms/State
                                    <hr class="message-inner-separator">
                                    <p>BMS_BAT_CH = 1</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-3 Close -->

        </div>
         <!-- CONTAINER END -->

        <!-- CONTAINER 2 -->
        <div class="main-container container-fluid d-none">
            <!-- ROW-1 Start -->
            <div class="row">
                <div class="col-sm-9 col-md-9 col-lg-9 col-xl-9">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="device-card-title">Data Analysis Chart</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="chartLine" class="h-275"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h3 class="device-card-title">Selection</h3>
                        </div>
                        <div class="card-body selection-scroll">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Gen_P_L1(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Gen_P_L2(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Gen_P_L3(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Grid_P_L1(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Grid_P_L2(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">Grid_P_L3(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">O_P_L1(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">O_P_L2(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">O_P_L3(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">OV_L1(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">OV_L2(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">OV_L3(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_w[]" data-value="W" value="option1">
                                        <span class="custom-control-label">PV_AC_G/L3(W)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Gen_V_L1(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Gen_V_L2(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Gen_V_L3(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Grid_V_L1(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Grid_V_L2(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">Grid_V_L3(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">O_V_L1(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">O_V_L2(V)</span>
                                    </label>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input chkBox" name="chk_v[]" data-value="V" value="option1">
                                        <span class="custom-control-label">O_V_L3(V)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 end -->
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                            <div class="row">
                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 pr-0">
                                    <button type="button" class="btn btn-icon  btn-primary prev"><i class="fa fa-arrow-left"></i></button>
                                    {{-- <ul class="icons-list">
                                        <li class="icons-list-item icons-list-item-overview-page prev"><i class="fa fa-arrow-left"></i></li>
                                    </ul> --}}
                                </div>
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="input-group">
                                        <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text" id="chart-date" name="chart-date">
                                        <div class="input-group-text bg-color-black text-white">
                                            <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 p-0">
                                    <button type="button" class="btn btn-icon  btn-primary next"><i class="fa fa-arrow-right"></i></button>
                                    {{-- <ul class="icons-list">
                                        <li class="icons-list-item icons-list-item-overview-page"><i class="fa fa-arrow-right"></i></li>
                                    </ul> --}}
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center">
                            <button type="button" class="btn btn-icon  btn-primary"><i class="fa fa-home"></i></button>
                            {{-- <ul class="icons-list">
                                <li class="icons-list-item icons-list-item-overview-page"><i class="fa fa-home"></i></li>
                            </ul> --}}
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                            <div class="form-group form-group-register error-country_id">
                                <select class="form-select" name="days" id="days">
                                    <option value="">Select Days</option>
                                    <option value="1">1 Days</option>
                                    <option value="2">2 Days</option>
                                    <option value="3">3 Days</option>
                                    <option value="4">4 Days</option>
                                    <option value="5">5 Days</option>
                                    <option value="6">6 Days</option>
                                    <option value="7">7 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <a href="javascript:void(0);" class="btn btn-radius btn-primary">Daily</a>
                            <a href="javascript:void(0);" class="btn btn-radius btn-primary">Months</a>
                            <a href="javascript:void(0);" class="btn btn-radius btn-primary">Years</a>
                            <a href="javascript:void(0);" class="btn btn-radius btn-primary">Global</a>
                        </div>
                        <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1">
                            <button type="button" class="btn btn-icon  btn-primary"><i class="fa fa-refresh"></i></button>
                            {{-- <ul class="icons-list">
                                <li class="icons-list-item icons-list-item-overview-page"><i class="fa fa-refresh"></i></li>
                            </ul> --}}
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
{{-- <script src="{{ url('theme-asset/plugins/sweet-alert/sweetalert.min.js') }}"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    const machine_status    = "{{ $machine_status }}";
    const machine_timeout   = 40;

    const macid = "{{ $macid }}";
    const ip    = "{{ config('constants.MQTT_IP') }}";
    const port  = {{ config('constants.MQTT_PORT') }};
    var is_verified = "{{ $is_verified }}";
    var userID     = "{{ $userID }}";
    var is_live_view    = "{{ $is_live_view }}";
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
            if(machine_status == 'ON'  || machine_status == 'Inverter_disconnected')
            {
                var i = 0;
                setInterval(function() {
                    i++;
                    // console.log(i);
                    if(machine_timeout < i){
                        turn_off_machine();
                    }
                }, 1000);
            }
        } else {
            turn_off_machine();
            $(".db-row").css("filter", "blur(6px)");
            // $(".permission-msg-row").removeClass('d-none');

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

                    $('.unit-card').removeClass('machine-off');
                    $('.unit-img-on').removeClass('d-none');
                    $('.unit-img-off').addClass('d-none');
                    $('.unit-card').find('.text-machine-off').removeClass('machine-off-text-color');

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
            if(json.data.Status)
            {
                var SOH      = json.data['Voltage']['V_DC(V)'];
                if( typeof SOH !== 'undefined')
                {
                    SOH = unitConverter(SOH);
                    $('.SOH').html('');
                    if(SOH == 0){
                        $('.SOH').html(SOH+' V');
                    } else {
                        $('.SOH').html(SOH+'V');
                    }
                }

                $.each(json.data.Status,function(key,val){
                    if(key == "SOC(%)")
                    {
                        $('.SOC').text(val+'(%) SOC');
                    }
                    // else if(key == "SOH(%)")
                    // {
                    //     // $('.SOH').text(val+'(%) SOH');
                    // }

                });
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
                var Gen_Tot_Energy          = parseFloat(json.data['Gen_Tot_Energy(Wh)']);
                var Gen_Tot_Pow             = parseFloat(json.data['Gen_Tot_Pow(W)']);
                var Grid_Tot_Energy         = parseFloat(json.data['Grid_Tot_Energy(Wh)']);
                var AC_Solar_Tot_Energy     = parseFloat(json.data['AC_Solar_Tot_Energy(Wh)']);
                var AC_Solar_Tot_Pow        = parseFloat(json.data['AC_Solar_Tot_Pow(W)']);
                var DC_Solar_Energy         = parseFloat(json.data['DC_Solar_Energy(Wh)']);
                var DC_Solar_Power          = parseFloat(json.data['DC_Solar_Power(W)']);
                var Grid_Tot_Pow            = parseFloat(json.data['Grid_Tot_Pow(W)']);
                var Out_Tot_Energy          = parseFloat(json.data['Out_Tot_Energy(Wh)']);
                var Out_Tot_Pow             = parseFloat(json.data['Out_Tot_Pow(W)']);
                if(typeof Gen_Tot_Energy !== 'undefined' && !isNaN(Gen_Tot_Energy))
                {
                    Gen_Tot_Energy = unitConverter(Gen_Tot_Energy);
                    $('.Gen_Tot_Energy').html('');
                    $('.Gen_Tot_Energy').html(Gen_Tot_Energy+'Wh');
                }

                if(typeof Gen_Tot_Pow !== 'undefined' && !isNaN(Gen_Tot_Pow))
                {
                    Gen_Tot_Pow = unitConverter(Gen_Tot_Pow);
                    $('.Gen_Tot_Pow').html('');
                    $('.Gen_Tot_Pow').html(Gen_Tot_Pow+'W');
                }

                if(typeof Grid_Tot_Energy !== 'undefined' && !isNaN(Grid_Tot_Energy))
                {
                    Grid_Tot_Energy = unitConverter(Grid_Tot_Energy);
                    $('.Grid_Tot_Energy').html('');
                    $('.Grid_Tot_Energy').html(Grid_Tot_Energy+'Wh');
                }

                if(typeof Out_Tot_Energy !== 'undefined' && !isNaN(Out_Tot_Energy))
                {
                    Out_Tot_Energy = unitConverter(Out_Tot_Energy);
                    $('.Out_Tot_Energy').html('');
                    $('.Out_Tot_Energy').html(Out_Tot_Energy+'Wh');
                }

                if( typeof AC_Solar_Tot_Energy !== 'undefined' && !isNaN(AC_Solar_Tot_Energy))
                {
                    AC_Solar_Tot_Energy = unitConverter(AC_Solar_Tot_Energy);
                    $('.AC_Solar_Tot_Energy').html('');
                    $('.AC_Solar_Tot_Energy').html(AC_Solar_Tot_Energy+'Wh');
                }

                if( typeof AC_Solar_Tot_Pow !== 'undefined' && !isNaN(AC_Solar_Tot_Pow))
                {
                    AC_Solar_Tot_Pow = unitConverter(AC_Solar_Tot_Pow);
                    $('.AC_Solar_Tot_Pow').html('');
                    $('.AC_Solar_Tot_Pow').html(AC_Solar_Tot_Pow+'W');
                }

                if( typeof DC_Solar_Energy !== 'undefined' && !isNaN(DC_Solar_Energy))
                {
                    DC_Solar_Energy = unitConverter(DC_Solar_Energy);
                    $('.DC_Solar_Energy').html('');
                    $('.DC_Solar_Energy').html(DC_Solar_Energy+'Wh');
                }

                if( typeof DC_Solar_Power !== 'undefined' && !isNaN(DC_Solar_Power))
                {
                    DC_Solar_Power = unitConverter(DC_Solar_Power);
                    $('.DC_Solar_Power').html('');
                    $('.DC_Solar_Power').html(DC_Solar_Power+'W');
                }

                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    Grid_Tot_Pow = unitConverter(Grid_Tot_Pow);
                    $('.Grid_Tot_Pow').html('');
                    $('.Grid_Tot_Pow').html(Grid_Tot_Pow+'W');
                }

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow))
                {
                    Out_Tot_Pow = unitConverter(Out_Tot_Pow);
                    $('.Out_Tot_Pow').html('');
                    $('.Out_Tot_Pow').html(Out_Tot_Pow+'W');
                }

            }
        }

        function set_sub_system_calculated(json){
            if(json.data)
            {
                var Gen_Tot_Energy   = parseFloat(json.data['Gen_Tot_Energy(Wh)']);
                var Grid_Tot_Pow     = parseFloat(json.data['Grid_Tot_Pow(W)']);
                var Out_Tot_Energy   = parseFloat(json.data['Out_Tot_Energy(Wh)']);
                var Out_Tot_Pow      = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_Tot_Energy  = parseFloat(json.data['Grid_Tot_Energy(Wh)']);
                console.log("Gen_Tot_Energy : ",Gen_Tot_Energy);

                if(typeof Gen_Tot_Energy !== 'undefined' && !isNaN(Gen_Tot_Energy))
                {
                    Gen_Tot_Energy = unitConverter(Gen_Tot_Energy);
                    $('.Gen_Tot_Energy').html('');
                    $('.Gen_Tot_Energy').html(Gen_Tot_Energy+'Wh');
                }
                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    Grid_Tot_Pow = unitConverter(Grid_Tot_Pow);
                    $('.Grid_Tot_Pow').html('');
                    $('.Grid_Tot_Pow').html(Grid_Tot_Pow+'W');
                }

                if( typeof Out_Tot_Energy !== 'undefined' && !isNaN(Out_Tot_Energy))
                {
                    Out_Tot_Energy = unitConverter(Out_Tot_Energy);
                    $('.Out_Tot_Energy').html('');
                    $('.Out_Tot_Energy').html(Out_Tot_Energy+'Wh');
                }

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow))
                {
                    Out_Tot_Pow = unitConverter(Out_Tot_Pow);
                    $('.Out_Tot_Pow').html('');
                    $('.Out_Tot_Pow').html(Out_Tot_Pow+'W');
                }

                if( typeof Grid_Tot_Energy !== 'undefined' && !isNaN(Grid_Tot_Energy))
                {
                    Grid_Tot_Energy = unitConverter(Grid_Tot_Energy);
                    $('.Grid_Tot_Energy').html('');
                    $('.Grid_Tot_Energy').html(Grid_Tot_Energy+'Wh');
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
                    Temp_1 = Temp_1 / 100;
                    $('.Temp_1').html('');
                    $('.Temp_1').html(Temp_1+'<sup>o</sup> C');
                }

                if( typeof Temp_1 !== 'undefined')
                {
                    Temp_2 = Temp_2 / 100;

                    $('.Temp_2').html('');
                    $('.Temp_2').html(Temp_2+'<sup>o</sup> C');
                }

                if( typeof Temp_1 !== 'undefined')
                {
                    Temp_3 = Temp_3 / 100;

                    $('.Temp_3').html('');
                    $('.Temp_3').html(Temp_3+'<sup>o</sup> C');
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

            if(contain == "System_calculated")
            {
                var Gen_Tot_Pow       = parseFloat(json.data['Gen_Tot_Pow(W)']);
                var DC_Solar_Power    = parseFloat(json.data['DC_Solar_Power(W)']);
                var AC_Solar_Tot_Pow  = parseFloat(json.data['AC_Solar_Tot_Pow(W)']);
                var Out_Tot_Pow       = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_Tot_Pow      = parseFloat(json.data['Grid_Tot_Pow(W)']);

                if(typeof Gen_Tot_Pow !== 'undefined' && !isNaN(Gen_Tot_Pow) && Gen_Tot_Pow > 5)
                {
                    $('.gen-to-unit-animation-img').removeClass('d-none');
                }

                if( typeof DC_Solar_Power !== 'undefined' && !isNaN(DC_Solar_Power) && DC_Solar_Power > 500)
                {
                    $('.dcsolar-to-unit-animation-img').removeClass('d-none');
                }

                if( typeof AC_Solar_Tot_Pow !== 'undefined' && !isNaN(AC_Solar_Tot_Pow) && AC_Solar_Tot_Pow > 1.5)
                {
                    $('.acsolar-to-unit-animation-img').removeClass('d-none');
                }

                if(Gen_Tot_Pow > 0 || DC_Solar_Power > 0 || AC_Solar_Tot_Pow > 0){
                    $('.unit-card').removeClass('machine-off');
                    $('.unit-img-on').removeClass('d-none');
                    $('.unit-img-off').addClass('d-none');
                    $('.unit-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow) && Out_Tot_Pow > 5)
                {
                    $('.unit-to-load-animation-img').removeClass('d-none');
                }

                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    if(Grid_Tot_Pow > 5)
                    {
                        $('.unit-to-grid-animation-img').addClass('d-none');
                        $('.grid-to-unit-animation-img').removeClass('d-none');
                    } else if(Grid_Tot_Pow  < -5){
                        $('.grid-to-unit-animation-img').addClass('d-none');
                        $('.unit-to-grid-animation-img').removeClass('d-none');
                    } else {
                        $('.unit-to-grid-animation-img').addClass('d-none');
                        $('.grid-to-unit-animation-img').addClass('d-none');
                    }
                }

            }

            if(contain == "Sub_System_calculated")
            {
                var Out_Tot_Pow      = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_Tot_Pow     = parseFloat(json.data['Grid_Tot_Pow(W)']);

                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    if(Grid_Tot_Pow > 5)
                    {
                        $('.unit-to-grid-animation-img').addClass('d-none');
                        $('.grid-to-unit-animation-img').removeClass('d-none');
                    } else if(Grid_Tot_Pow  < -5){
                        $('.grid-to-unit-animation-img').addClass('d-none');
                        $('.unit-to-grid-animation-img').removeClass('d-none');
                    } else {
                        $('.unit-to-grid-animation-img').addClass('d-none');
                        $('.grid-to-unit-animation-img').addClass('d-none');
                    }
                }

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow) && Out_Tot_Pow > 5)
                {
                    // $('.load-to-unit-animation-img').removeClass('d-none');
                    $('.unit-to-load-animation-img').removeClass('d-none');
                }
            }
        }

        function turn_on_icon(json){
            var contain = json.data.Contain

            $('.frequency-img-on').removeClass('d-none');
            $('.frequency-img-off').addClass('d-none');
            $('.frequency-row').find('.text-machine-off').removeClass('machine-off-text-color');

            $('.voltage-img-on').removeClass('d-none');
            $('.voltage-img-off').addClass('d-none');
            $('.frequency-voltage-row').find('.text-machine-off').removeClass('machine-off-text-color');

            if(contain == "System_calculated")
            {
                var Gen_Tot_Pow       = parseFloat(json.data['Gen_Tot_Pow(W)']);
                var DC_Solar_Power    = parseFloat(json.data['DC_Solar_Power(W)']);
                var AC_Solar_Tot_Pow  = parseFloat(json.data['AC_Solar_Tot_Pow(W)']);
                var Out_Tot_Pow       = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_Tot_Pow      = parseFloat(json.data['Grid_Tot_Pow(W)']);

                if( typeof Gen_Tot_Pow !== 'undefined' && !isNaN(Gen_Tot_Pow) &&  Gen_Tot_Pow > 1)
                {
                    $('.generator-card').removeClass('machine-off');
                    $('.generator-img-on').removeClass('d-none');
                    $('.generator-img-off').addClass('d-none');
                    $('.generator-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof DC_Solar_Power !== 'undefined' && !isNaN(DC_Solar_Power) && DC_Solar_Power > 100)
                {
                    $('.dcsolar-card').removeClass('machine-off');
                    $('.dcsolar-img-on').removeClass('d-none');
                    $('.dcsolar-img-off').addClass('d-none');
                    $('.dcsolar-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof AC_Solar_Tot_Pow !== 'undefined' && !isNaN(AC_Solar_Tot_Pow) && AC_Solar_Tot_Pow > 500)
                {
                    $('.acsolar-card').removeClass('machine-off');
                    $('.acsolar-img-on').removeClass('d-none');
                    $('.acsolar-img-off').addClass('d-none');
                    $('.acsolar-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if(Gen_Tot_Pow > 0 || DC_Solar_Power > 0 || AC_Solar_Tot_Pow > 0){
                    $('.unit-card').removeClass('machine-off');
                    $('.unit-img-on').removeClass('d-none');
                    $('.unit-img-off').addClass('d-none');
                    $('.unit-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow) && Out_Tot_Pow  > 1)
                {
                    $('.load-card').removeClass('machine-off');
                    $('.load-img-on').removeClass('d-none');
                    $('.load-img-off').addClass('d-none');
                    $('.load-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    if(Grid_Tot_Pow > 1)
                    {
                        $('.grid-card').removeClass('machine-off');
                        $('.grid-img-on').removeClass('d-none');
                        $('.grid-img-off').addClass('d-none');
                        $('.grid-card').find('.text-machine-off').removeClass('machine-off-text-color');
                    } else if(Grid_Tot_Pow < -1){
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

            }

            if(contain == "Sub_System_calculated")
            {
                var Out_Tot_Pow      = parseFloat(json.data['Out_Tot_Pow(W)']);
                var Grid_Tot_Pow     = parseFloat(json.data['Grid_Tot_Pow(W)']);

                if( typeof Out_Tot_Pow !== 'undefined' && !isNaN(Out_Tot_Pow) && Out_Tot_Pow > 1)
                {
                    $('.load-card').removeClass('machine-off');
                    $('.load-img-on').removeClass('d-none');
                    $('.load-img-off').addClass('d-none');
                    $('.load-card').find('.text-machine-off').removeClass('machine-off-text-color');
                }

                if( typeof Grid_Tot_Pow !== 'undefined' && !isNaN(Grid_Tot_Pow))
                {
                    if(Grid_Tot_Pow > 1)
                    {
                        $('.grid-card').removeClass('machine-off');
                        $('.grid-img-on').removeClass('d-none');
                        $('.grid-img-off').addClass('d-none');
                        $('.grid-card').find('.text-machine-off').removeClass('machine-off-text-color');
                    } else if(Grid_Tot_Pow < -1){
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
                $('.ideal-abel-btn').addClass('d-none');
                $('.Inverter_disconnected').removeClass('d-none');
            } else {

                $('.on-btn').addClass('d-none');
                $('.connecting-btn').addClass('d-none');
                $('.discharge-btn').addClass('d-none');
                $('.ideal-abel-btn').addClass('d-none');
                $('.Inverter_disconnected').addClass('d-none');
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

            $('.frequency-img-on').addClass('d-none');
            $('.frequency-img-off').removeClass('d-none');

            $('.voltage-img-on').addClass('d-none');
            $('.voltage-img-off').removeClass('d-none');

            $('.unit-img-on').addClass('d-none');
            $('.unit-img-off').removeClass('d-none');
        }

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
                    //     $('.Inverter_disconnected').addClass('d-none');
                    //     $('.ideal-abel-btn').addClass('d-none');
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
                }

            }
        }

    // ============= Start Chart =================//
        /*LIne-Chart */
        var ctx = document.getElementById("chartLine").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Sun", "Mon", "Tus", "Wed", "Thu", "Fri", "Sat"],
                datasets: [{
                    label: 'Profits',
                    data: [100, 420, 210, 420, 210, 320, 350],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: '#6259ca',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 2,
                    lineTension: 0.3
                }, {
                    label: 'Expenses',
                    data: [450, 200, 350, 250, 480, 200, 400],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: '#eb6f33',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 2,
                    lineTension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    x: {
                        ticks: {
                            color: "#77778e",
                        },
                        display: true,
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        }
                    },
                    y: {
                        ticks: {
                            color: "#77778e",
                        },
                        display: true,
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Thousands',
                            color: 'rgba(119, 119, 142, 0.2)'
                        }
                    },
                    y1: {
                        ticks: {
                            color: "#77778e",
                        },
                        display: true,
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Thousands',
                            color: 'rgba(119, 119, 142, 0.2)'
                        }
                    },
                    y2: {
                        ticks: {
                            color: "#77778e",
                        },
                        display: true,
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Thousands',
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        type: 'linear',
                        position: 'right',
                    },
                    y3: {
                        ticks: {
                            color: "#77778e",
                        },
                        display: true,
                        grid: {
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Thousands',
                            color: 'rgba(119, 119, 142, 0.2)'
                        },
                        type: 'linear',
                        position: 'right',
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

        $(".prev").click(function(){
            var date = $('.fc-datepicker').datepicker('getDate', '-1d');
            date.setDate(date.getDate()-1);
            $('.fc-datepicker').datepicker('setDate', date);
        })

        $(".next").click(function(){
        var date = $('.fc-datepicker').datepicker('getDate', '+1d');
            date.setDate(date.getDate()+1);
            $('.fc-datepicker').datepicker('setDate', date);
        })

        var limit_W = 10;
        var limit_v = 5;
        $(document).on('change','.chkBox',function(){
            var _this = $(this);
            var w_count = $("input[name='chk_w[]']:checked").length;
            var v_count = $("input[name='chk_v[]']:checked").length;

            if(w_count > limit_W && _this.attr('data-value') == "W")
            {
                _this.prop('checked', false);
                $.growl.notice({
                    title: "Success",
                    message: 'You can select (W) maximum of ' + limit_W + ' checkbox.',
                });
            }

            if(v_count > limit_v && _this.attr('data-value') == "V")
            {
                _this.prop('checked', false);
                $.growl.notice({
                    title: "Success",
                    message: 'You can select (V) maximum of ' + limit_v + ' checkbox.',
                });
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
                            console.log("get last 5 mini data "); 
                            if(resp.status == 'true'){

                                if(is_latest_data == true){
                                    $.each(resp.data,function(key,json){
                                        $('.unit-card').removeClass('machine-off');
                                        $('.unit-img-on').removeClass('d-none');
                                        $('.unit-img-off').addClass('d-none');
                                        $('.unit-card').find('.text-machine-off').removeClass('machine-off-text-color');
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
