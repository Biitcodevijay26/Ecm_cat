@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="{{ url('novnc/app/styles/base.css') }}">
<link rel="stylesheet" href="{{ url('novnc/app/styles/input.css') }}">
@endsection
@section('content')
<?php
$company_login_id = session()->get('company_login_id');
$adminRoleId = \Config::get('constants.roles.Master_Admin');
?>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header d-none">
                <div>
                    <h1 class="page-title">{{$title}}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
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

            <!-- ROW-Buttons Start -->
            <div class="row mb-1">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row d-flex justify-content-left">

                                <?php if ($company_login_id) : ?>
                                    <a href="{{ url('/company/'.$company_login_id.'/device_details/'.$id) }}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
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

                                        <?php endif; ?>
                                        <a href="{{url('/company/'.$company_login_id.'/remote-access-view/'.$id)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
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
                                    <a href="{{url('/remote-access-view/'.$id)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2 remort_access_btn mb-2">
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

            <!-- ROW-1 OPEN -->
             <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}} Info</h3>
                        </div>
                        <div class="card-body">

                            @if($sessionError == '')
                            <iframe name="iframe" id="iframe" title=Remote" width="100%" height="100%" style="height:600px;width:100%;"></iframe>
                            @else
                             <h3 class="error"> {{$sessionError}} </h3>
                            @endif

                        </div>
                        <div class="card-footer text-end">
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
<script src="https://www.apiremoteaccess.com/res/js/dwsapi.js" type="text/javascript">
</script>
<script>
$(document).ready(function () {
    var sessionError = "{{$sessionError}}";
    var url = "{{$url}}";
    if(sessionError == ''){
        dwsApiOpenIFrameSession('iframe', url, testIframe);

        function testIframe(e){
            console.log('e', e);
        }
    }


});
</script>
@endsection

