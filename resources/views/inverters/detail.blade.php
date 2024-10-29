@extends('front.layout_admin.app')
{{-- <link rel="stylesheet" href="{{ url('app-assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"> --}}
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@section('page_level_css')
    <style>
        .card {
            margin-bottom: 10px;
        }

        .appliances-grp .body {
            min-height: 90px;
        }

        .hideMe {
            display: none !important;
        }
        .endDaypicker .ui-datepicker-header
        {
            display:none;   
        }
    </style>
@endsection

@section('content')
    <div id="main-content">
        <div class="container-fluid">
            <div class="block-header">
                <div class="row">
                    @include('inverters.detail_menu', array('inverter' => $inverter))

                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <h2><a href="javascript:void(0);" class="btn btn-link btn-toggle-fullwidth"><i
                                    class="fa fa-arrow-left"></i></a>{{ $title }}</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                            <li class="breadcrumb-item">{{ $title }}</li>
                            <li class="breadcrumb-item active">{{ $title_sub }}</li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12 text-left">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 text-right">
                        <p class="demo-button">
                            <a href="{{url('admin/inverter-settings') . '/' . $inverter->_id }}" class=" btn btn-primary btn-sm mr5 mr-3 btnSettings hideMe" title="Settings"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
                            <button type="button" class="btn btn-success hideMe btnStausConnection cnsuccess"><i
                                    class="fa fa-check-circle"></i> <span>Connected</span></button>
                            <button type="button" class="btn btn-warning hideMe btnStausConnection cnwarning"><i
                                    class="fa fa-warning"></i> <span>Warning</span></button>
                            <button type="button" class="btn btn-danger hideMe btnStausConnection cndanger"><i
                                    class="fa fa-times"></i> <span>Danger</span></button>
                            <button type="button" class="btn btn-primary btnStausConnection cnprimary"
                                disabled="disabled"><i class="fa fa-spinner fa-spin"></i> <span>Loading...</span></button>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 alarmWarn">
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12">
                                    <h5>{{ $inverter->user->name ?? '' }} <small>&nbsp; | &nbsp; Control Card SN :
                                            {{ $inverter->control_card_no ?? '' }}</small></h5>
                                    <h6 class="mb-0"><b>Inverter SN :</b> {{ $inverter->serial_no ?? '' }}</h6>
                                    <p class="mb-0"><b>Site Name :</b> {{ $inverter->site_name ?? '' }}</p>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <p
                                                class="mb-0 {{ $inverter->status_text == 'Active' ? 'text-success' : 'text-danger' }} ">
                                                <b>Status :</b> {{ $inverter->status_text ?? '' }}
                                            </p>
                                            <p
                                                class="{{ $inverter->verified == 'yes' ? 'text-success' : 'text-danger' }} ">
                                                <b>Verified :</b> {{ $inverter->verified ?? '' }}
                                            </p>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <p>
                                                <b>Last Updated At : <span class="lastUpdatedAt text-warning"> </span> </b>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card top_counter">
                                        <div class="body">
                                            <div class="content">
                                                <h5 class="number inv_total_power">0 w</h5>
                                                <div class="text">AC Power</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card top_counter">
                                        <div class="body">
                                            <div class="content">
                                                <h5 class="number inv_energy_today">0 kwh</h5>
                                                <div class="text">Daily Yield</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card top_counter">
                                        <div class="body">
                                            <div class="content">
                                                <h5 class="number inv_energy_total">0 kwh</h5>
                                                <div class="text">Total Yield</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card appliances-grp ng-star-inserted">
                                <div class="body clearfix">
                                    <div class="icon">
                                        <img src="{{ asset('/app-assets/images/icon/Group101.svg') }}" >
                                    </div>
                                    <div class="content dv_battery_details" data-content="battery_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;" >
                                        <h5> <span class="bat_power">0 w</span> - <span class="bat_soc">0%</span> <span
                                                class="text-success"></span></h5>
                                        <p class="ng-star-inserted"> <span class="text-success hideMe clCharging">...</span>
                                        </p>
                                        <p class="ng-star-inserted">Battery Power <span class="text-warning"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card appliances-grp ng-star-inserted">
                                <div class="body clearfix">
                                    <div class="icon">
                                        <img src="{{ asset('/app-assets/images/icon/Group96.svg') }}" >
                                    </div>
                                    <div class="content dv_pv_details" data-content="pv_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;" >
                                        <h5> <span class="total_pv_power">0 w</span> </h5>
                                        <p class="ng-star-inserted">Total PV <span class="text-warning"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card appliances-grp ng-star-inserted">
                                <div class="body clearfix">
                                    <div class="icon">
                                        <img src="{{ asset('/app-assets/images/icon/Group102.svg') }}" >
                                    </div>
                                    <div class="content dv_inverter_details" data-content="inverter_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;" >
                                        <h5> <span class="inv_total_power">0 w</span> </h5>
                                        <p class="ng-star-inserted">Total Inverter <span class="text-warning"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card appliances-grp ng-star-inserted">
                                <div class="body clearfix">
                                    <div class="icon">
                                        <img src="{{ asset('/app-assets/images/icon/Group193.svg') }}" >
                                    </div>
                                    <div class="content dv_grid_details" data-content="grid_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;" >
                                        <h5> <span class="grid_total_power">0 w</span> </h5>
                                        <p class="ng-star-inserted">Total Grid <span class="text-warning"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card appliances-grp ng-star-inserted">
                                <div class="body clearfix">
                                    <div class="icon">
                                        <img src="{{ asset('/app-assets/images/icon/Group1452.svg') }}" >
                                    </div>
                                    <div class="content">
                                        <h5> <span class="total_load_consume">0 w</span> </h5>
                                        <p class="ng-star-inserted">Total Load <span class="text-warning"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-7 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-4" style="z-index: 9999;">
                                    <div class="card dv_pv_details" data-content="pv_details"
                                        data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;">
                                        <div class="body text-center pb-0 pt-0">
                                            {{-- <i class="wi wi-day-sunny h1"></i> --}}
                                            <img src="{{ asset('/app-assets/images/icon/Group180.svg') }}" >
                                            <h5 class="mt-4 total_pv_power">0 w</h5>
                                            <p>PV</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-4"></div>
                                <div class="col-lg-4 col-md-4 col-4" style="z-index: 9998;">
                                    <div class="card dv_battery_details" data-content="battery_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;">
                                        <div class="body text-center pb-0 pt-0">
                                            {{-- <input type="text" class="bat_socChart" value="0" data-width="50"
                                                data-height="50" data-thickness="0.2" data-fgColor="#55BBEA" readonly>
                                            <lottie-player src="{{ asset('/assets/lotti/2/Battery-Animation.json') }}"
                                                class="mr-auto hideMe" background="#1c222c" speed="1" id="batteryAnimation"
                                                style="width: 70%; height: 70%;" loop autoplay>
                                            </lottie-player> --}}

                                            <img src="{{ asset('/app-assets/images/icon/Group192.svg') }}" >
                                            <div class="bat_socChart bat_socChartPer" style="position: absolute;top: 7%;left: 47%; font-size: 11px;">0</div>

                                            <h5 class="mt-4 bat_power">0 w</h5>
                                            <p> BATTERY </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-4" style="margin-top: -10%; z-index: 999;">
                                    <lottie-player src="{{ asset('/assets/lotti/2/1_SolarToInverter.json') }}"
                                        class="ml-auto hideMe SolartoInverter" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(22deg);" loop autoplay>
                                    </lottie-player>
                                </div>
                                <div class="col-lg-2 col-md-2 col-4">
                                    <div class="card" data-content="inverter_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="padding-top: 30%;">
                                        <div class="body text-center p-0">
                                            {{-- <i class="wi wi-lightning h1"></i> --}}
                                            <img src="{{ asset('/app-assets/images/icon/Group198.svg') }}" >
                                            {{-- <h5 class="mt-4 inv_total_power">0 w</h5> --}}
                                            <p class="mt-4">INVERTER</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-4" style="margin-top: -10%; z-index: 999">
                                    <lottie-player src="{{ asset('/assets/lotti/2/2_InverterToBattery.json') }}"
                                        class="mr-auto hideMe InvertertoBattery" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(-22deg);" loop autoplay>
                                    </lottie-player>
                                    <lottie-player src="{{ asset('/assets/lotti/2/3_BatteryToInverter.json') }}"
                                        class="mr-auto hideMe BatterytoInverter" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(-22deg);" loop autoplay>
                                    </lottie-player>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-3 pl-0 pr-0" style="z-index: 999;">
                                    <div class="card dv_grid_details"  data-content="grid_details"
                                    data-control_card_no="{{ $inverter->control_card_no ?? '' }}" style="cursor: pointer;">
                                        <div class="body text-center pt-0">
                                            <img src="{{ asset('/app-assets/images/icon/Group183.svg') }}" >
                                            <h5 class="mt-4 grid_total_power">0 w</h5>
                                            <p>GRID</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-3" style="margin-top: -3%;">
                                    <lottie-player src="{{ asset('/assets/lotti/2/4_GridToInverter.json') }}"
                                        class="mr-auto hideMe GridtoInverter" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(30deg);" loop autoplay>
                                    </lottie-player>
                                    <lottie-player src="{{ asset('/assets/lotti/2/5_InverterToGrid.json') }}"
                                        class="mr-auto hideMe InvertertoGrid" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(30deg);" loop autoplay>
                                    </lottie-player>
                                </div>
                                <div class="col-lg-4 col-md-4 col-3" style="margin-top: -3%;">
                                    <lottie-player src="{{ asset('/assets/lotti/2/6_InverterToLoad.json') }}"
                                        class="ml-auto hideMe InvertertoLoad" background="#1c222c" speed="1"
                                        style="width: 70%; height: 70%; transform: rotate(-30deg);" loop autoplay>
                                    </lottie-player>
                                </div>
                                <div class="col-lg-2 col-md-2 col-3 pl-0 pr-0">
                                    <div class="card">
                                        <div class="body text-center pt-0">
                                            <img src="{{ asset('/app-assets/images/icon/Group185.svg') }}" >
                                            <h5 class="mt-4 total_load_consume">0 w</h5>
                                            <p>LOAD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-12 text-center"
                                    style="z-index: 999; margin-top: -10%;">
                                    <lottie-player src="{{ asset('/assets/lotti/2/8_GridToLoad.json') }}" class="mx-auto hideMe"
                                        background="#1c222c" speed="1" style="width: 50%; height: 50%;" loop
                                        autoplay></lottie-player>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row col-md-12">
                                 <div class="col-md-2">
                                    <h5>Power</h5>
                                 </div>
                                 <div class="col-md-4">
                                 </div>
                                 <div class="col-md-2 pt-4 text-right">
                                     <button type="button" class="btn btn-primary btnLoading cnprimary"
                                         disabled="disabled"><i class="fa fa-spinner fa-spin"></i> <span>Loading...</span></button>
                                 </div>
                                 <div class="col-md-4">
                                     <label>Date</label>
                                     <input class="datepicker form-control" name="start" id="startDate" value="{{date('Y-m-d')}}">

                                     {{-- <div class="input-daterange input-group" data-provide="datepicker">
                                         <input type="text" class="input-sm form-control" name="start" id="startDate" value="{{date('Y-m-d')}}">
                                         <span class="input-group-addon">to</span>
                                         <input type="text" class="input-sm form-control hideMe" name="end" id="endDate" value="{{date('Y-m-d')}}">
                                     </div> --}}
                                 </div>
                            </div>
                        </div>
                        <div class="body">
                            
                            <canvas id="powerChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row col-md-12">
                                 <div class="col-md-2">
                                    <h5>Energy Diagram</h5>
                                 </div>
                                 
                                 <div class="col-md-2 pt-4 text-right">
                                    <button type="button" class="btn btn-primary btnLoadingEnergy cnprimary"
                                        disabled="disabled"><i class="fa fa-spinner fa-spin"></i> <span>Loading...</span></button>
                                </div>

                                 <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="enchartFilter" value="day" type="radio">
                                        <span><i></i>Day</span>
                                    </label>
                                 </div>
                                 <div class="col-md-1 text-left pl-0">
                                    <input class="form-control" name="enDay" id="enDay" value="{{date('d')}}">
                                 </div>
                                 <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="enchartFilter" value="month" type="radio" checked><span><i></i>Month</span></label>
                                 </div>
                                 <div class="col-md-1 text-left pl-0">
                                    <input class="form-control" name="enMonth" id="enMonth" value="{{date('m')}}">
                                 </div>
                                 <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="enchartFilter" value="year" type="radio" ><span><i></i>Year</span></label>
                                 </div>
                                 <div class="col-md-1 text-left pl-0">
                                    <input class="form-control" name="enYear" id="enYear" value="{{date('Y')}}">
                                 </div>
                                 <div class="col-md-2">
                                    <label class="fancy-radio"><input name="enchartFilter" value="all" type="radio" ><span><i></i>All</span></label>

                                    <span class="resetEnChart text-primary" style="cursor: pointer;">Reset</span>
                                 </div>
                                 
                            </div>
                        </div>
                        <div class="body">
                            
                            <canvas id="energyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Phase --}}
    <div class="modal fade" id="largeModal1" tabindex="-1" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel1">Phase</h4>
                    <p>The complete power-generating unit, consisting of any number of PV modules and panels. </p>
                </div>
                <div class="modal-body" id="largeModalBody1">
                    <div class="card top_counter">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6">
                                    <div class="body text-center pb-0 pt-0">
                                        {{-- <i class="wi wi-day-sunny h1 text-warning"></i> --}}
                                        <img src="{{ asset('/app-assets/images/icon/Group125.svg') }}" >
                                        <h5 class="mt-0 total_pv_power text-warning"> 0 w</h5>
                                        <p>Total PV Power</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 align-self-center"">
                                    <div class="body text-center pb-0 pt-0">
                                        <div class="content">
                                            <h5 class="number pv_energy_today">0 kwh</h5>
                                            <div class="text">Today Energy</div>
                                        </div>
                                        <div class="content pt-2">
                                            <h5 class="number pv_energy_total">0 kwh</h5>
                                            <div class="text">Total Energy</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>PV Details</h2>
                                </div>
                                <div class="body table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Inverter</th>
                                                <th>Voltage</th>
                                                <th>Current</th>
                                                <th>Power</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">PV1</th>
                                                <td class="pv1_voltage">0 v</td>
                                                <td class="pv1_current">0 a</td>
                                                <td class="pv1_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">PV2</th>
                                                <td class="pv2_voltage">0 v</td>
                                                <td class="pv2_current">0 a</td>
                                                <td class="pv2_power">0 w</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Inverter --}}
    <div class="modal fade" id="largeModal2" tabindex="-1" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel2">Inverter</h4>
                    <p>Invertor is a power electronic device or circuitry
                        that changes DC to AC. </p>
                </div>
                <div class="modal-body" id="largeModalBody2">
                    <div class="card top_counter">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6">
                                    <div class="body text-center pb-0 pt-0">
                                        <img src="{{ asset('/app-assets/images/icon/Group181.svg') }}" >
                                        <h5 class="mt-0 inv_total_power text-success"> 0 w</h5>
                                        <p>Total Inverter Power</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 align-self-center"">
                                    <div class="body text-center pb-0 pt-0">
                                        <div class="content">
                                            <h5 class="number inv_energy_today">0 kwh</h5>
                                            <div class="text">Today Energy</div>
                                        </div>
                                        <div class="content pt-2">
                                            <h5 class="number inv_energy_total">0 kwh</h5>
                                            <div class="text">Total Energy</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Inverter Details</h2>
                                </div>
                                <div class="body table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Inverter</th>
                                                <th>Voltage</th>
                                                <th>Current</th>
                                                <th>Power</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">AC <span class="text-danger">R</span> </th>
                                                <td class="inv_phase1_voltage">0 v</td>
                                                <td class="inv_phase1_current">0 a</td>
                                                <td class="inv_phase1_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">AC <span class="text-warning">Y</span></th>
                                                <td class="inv_phase2_voltage">0 v</td>
                                                <td class="inv_phase2_current">0 a</td>
                                                <td class="inv_phase2_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">AC <span class="text-primary">B</span></th>
                                                <td class="inv_phase3_voltage">0 v</td>
                                                <td class="inv_phase3_current">0 a</td>
                                                <td class="inv_phase3_power">0 w</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Battery --}}
    <div class="modal fade" id="largeModal3" tabindex="-1" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel3">Battery</h4>
                    <p>In case of power cut,
                        the battery will function as a backup. </p>
                </div>
                <div class="modal-body" id="largeModalBody3">
                    <div class="card top_counter">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6">
                                    <div class="body text-center pb-0 pt-0">
                                        <img src="{{ asset('/app-assets/images/icon/Group145.svg') }}" >
                                        <h5 class="mt-0 bat_power text-info"> 0 w</h5>
                                        <p>Today Power</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 align-self-center"">
                                    <div class="body text-center pb-0 pt-0">
                                        <div class="content">
                                            <h5 class="number bat_energy_total">0 kwh</h5>
                                            <div class="text">Total Energy</div>
                                        </div>
                                        <div class="content pt-2">
                                            {{-- <input type="text" class="bat_socChart" value="0" data-width="50"
                                                                    data-height="50" data-thickness="0.2" data-fgColor="#55BBEA" readonly> --}}
                                            <img src="{{ asset('/app-assets/images/icon/Group192.svg') }}" >
                                            <div class="bat_socChart bat_socChartPer" style="position: absolute;top: 66%;left: 48%;font-size: 11px;">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Battery Details</h2>
                                </div>
                                <div class="body table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Voltage</th>
                                                <td class="bat_Voltage">0 v</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Current</th>
                                                <td class="bat_current">0 a</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Power</th>
                                                <td class="bat_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">SoC</th>
                                                <td class="bat_soc">0 %</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="modal fade" id="largeModal4" tabindex="-1" role="dialog" style="z-index: 9999;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="largeModalLabel4">Grid</h4>
                    <p>A value indicating how many kilowatts
                        were exported and imported is shown in the grid data. </p>
                </div>
                <div class="modal-body" id="largeModalBody4">
                    <div class="card top_counter">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6">
                                    <div class="body text-center pb-0 pt-0">
                                        <img src="{{ asset('/app-assets/images/icon/Group38.svg') }}" >
                                        <h5 class="mt-0 grid_total_power" style="color: #B364D3;"> 0 w</h5>
                                        <p>Total Grid Power</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 align-self-center"">
                                    <div class="body text-center pb-0 pt-0">
                                        <div class="content">
                                            <h5 class="number grid_run_time">0 hr</h5>
                                            <div class="text">Run Time</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="body text-center pb-0 pt-0">
                                    <p class="pt-3">Energy Sell</p>
                                    <hr style="border-top: 2px solid rgb(253 251 251 / 10%);">
                                    <h5 class="mt-0 grid_energy_sell_today"> 0 kwh</h5>
                                    <p>Today Sell</p>
                    
                                    <h5 class="mt-0 grid_energy_sell_total"> 0 kwh</h5>
                                    <p>Total Sell</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="body text-center pb-0 pt-0">
                                    <p class="pt-3">Energy Buy</p>
                                    <hr style="border-top: 2px solid rgb(253 251 251 / 10%);">
                                    <h5 class="mt-0 grid_energy_buy_today"> 0 kwh</h5>
                                    <p>Today Buy</p>
                    
                                    <h5 class="mt-0 grid_energy_byu_total"> 0 kwh</h5>
                                    <p>Total Buy</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Grid Details</h2>
                                </div>
                                <div class="body table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Phases</th>
                                                <th>Voltage</th>
                                                <th>Power</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">Phase 1</th>
                                                <td class="grid_phase1_voltage">0 v</td>
                                                <td class="grid_phase1_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phase 2</th>
                                                <td class="grid_phase2_voltage">0 v</td>
                                                <td class="grid_phase2_power">0 w</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Phase 3</th>
                                                <td class="grid_phase3_voltage">0 v</td>
                                                <td class="grid_phase3_power">0 w</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_level_js')
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="{{ url('assets/js/mqtt4.3.7.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-moment/1.0.1/chartjs-adapter-moment.min.js"></script>
    {{-- <script src="{{ url('app-assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script> --}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">
        const control_card_no = "{{ $inverter->control_card_no ?? '' }}";
        const ip = "{{ config('constants.MQTT_IP') }}";
        const port = {{ config('constants.MQTT_PORT') }};

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

            let client = mqtt.connect('ws://' + ip + ':' + port, options);
            client.on('connect', function() {

                var msgDataRetain = '';
                client.publish('read_data', msgDataRetain, {retain : true}, function(err) {
                    if (err) {
                        console.log('ERROR read_data retain =>', err);
                    }
                });

                client.subscribe('read_data', {qos : 2}, function(err) {
                    if (!err) {
                        //client.publish('read_data', 'Hello mqtt Self Test');
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
               // console.log('message => ', message.toString());
               console.log('Data From MQTT => ');
               console.log('topic => ', topic);

                if (topic == 'read_data') {
                   // $('.clCharging').text('Charging...');
                    var msgData = message.toString();
                    var json;
                    try {
                        json = JSON.parse(msgData);
                    } catch (e) {}
                   console.log('json ====>', json);
                    if (json && json.Control_card_sn == control_card_no) {
                        getCurrenTime();
                        let content = json.content;
                        storeDataLocally(json);
                        switch (content) {
                            case 'inverter_details':
                                set_inverter_details(json);
                                break;
                            case 'battery_details':
                                set_battery_details(json);
                                break;
                            case 'pv_details':
                                set_pv_details(json);
                                break;
                            case 'grid_details':
                                set_grid_details(json);
                                break;
                            case 'energy_details':
                                set_energy_details(json);
                                break;
                            case 'alarm_warning_details':
                                set_alarm_warning_details(json);
                                break;


                            default:
                                break;
                        }
                    }

                    //$('.clCharging').text('Changinged');
                }

            });
            client.on('error', function(error) {
                console.log('Error => ', error);
                $('.btnStausConnection').addClass('hideMe');
                $('.cndanger').removeClass('hideMe').find('span').text(error);
            });

            client.on('connect', function(data) {
                console.log('connect => ', data);
                // $('.btnStausConnection').addClass('hideMe');
                // $('.cnsuccess').removeClass('hideMe').find('span').text('Connected');
            });

            client.on('disconnect', function(data) {
                console.log('disconnect => ', data);
                $('.btnStausConnection').addClass('hideMe');
                $('.cnwarning').removeClass('hideMe').find('span').text('Disconnected');
            });

            client.on('end', function(data) {
                console.log('end => ', data);
                $('.btnStausConnection').addClass('hideMe');
                $('.cnwarning').removeClass('hideMe').find('span').text('End');
            });

            function getCurrenTime() {
                var mmt = moment();
                var mmtformat = mmt.format();
                var mmtunix = mmt.unix();
                var mmtdotmated = mmt.format("DD-MM-YYYY hh:mm:ss a");
                $('.lastUpdatedAt').attr({
                        'data-mmtformat': mmtformat,
                        'data-mmtunix': mmtunix
                    })
                    .text(mmtdotmated);
            }
            //showAlarmWarning('ZS3A20220001',[28,11,12]);
            function showAlarmWarning(control_card_no='',codes=[]) {
                $.ajax({
                        url: '{{url("admin/get-inverter-warning-message")}}', 
                        type: "POST",             
                        data:  {warning_codes: codes, control_card_no:control_card_no },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    success: function(data) {
                        if(data && data.data){
                            $('.alarmWarn').html('');
                            var warnData = data.data;
                            for(var k in warnData){
                                var tmpWn = `<div class="alert alert-warning alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <i class="fa fa-warning"></i> `+warnData[k].msg+`
                                            </div>`;
                                $('.alarmWarn').append(tmpWn);
                            }
                        }
                    }
                });
            }

            $(document).on('click', '.dv_pv_details', function() {
                var _ts = $(this);
                var content = _ts.attr('data-content');
                var control_card_no = _ts.attr('data-control_card_no');
                $('#largeModal1').modal('show');
                /*$.ajax({
                        url: '{{url("admin/tmp-inverter-content-data")}}', 
                        type: "POST",             
                        data:  {content: content, control_card_no:control_card_no },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    success: function(data) {
                        $('#largeModalBody1').html(data);
                        $('#largeModal1').modal('show');
                    }
                });*/
            });

            $(document).on('click', '.dv_inverter_details', function() {
                var _ts = $(this);
                var content = _ts.attr('data-content');
                var control_card_no = _ts.attr('data-control_card_no');
                $('#largeModal2').modal('show');
                /*$.ajax({
                        url: '{{url("admin/tmp-inverter-content-data")}}', 
                        type: "POST",             
                        data:  {content: content, control_card_no:control_card_no },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    success: function(data) {
                        $('#largeModalBody2').html(data);
                        $('#largeModal2').modal('show');
                    }
                });*/
            });

            $(document).on('click', '.dv_battery_details', function() {
                var _ts = $(this);
                var content = _ts.attr('data-content');
                var control_card_no = _ts.attr('data-control_card_no');
                $('#largeModal3').modal('show');
                // $('.bat_socChart').knob({
                //     'format' : function (value) {
                //         return value + '%';
                //     }
                // });
                /*$.ajax({
                        url: '{{url("admin/tmp-inverter-content-data")}}', 
                        type: "POST",             
                        data:  {content: content, control_card_no:control_card_no },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    success: function(data) {
                        $('#largeModalBody3').html(data);
                        $('#largeModal3').modal('show');
                        $('.bat_socChart').knob({
                            'format' : function (value) {
                                return value + '%';
                            }
                        });
                    }
                });*/
            });

            $(document).on('click', '.dv_grid_details', function() {
                var _ts = $(this);
                var content = _ts.attr('data-content');
                var control_card_no = _ts.attr('data-control_card_no');
                $('#largeModal4').modal('show');
                /*$.ajax({
                        url: '{{url("admin/tmp-inverter-content-data")}}', 
                        type: "POST",             
                        data:  {content: content, control_card_no:control_card_no },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                    success: function(data) {
                        $('#largeModalBody4').html(data);
                        $('#largeModal4').modal('show');
                    }
                });*/
            });

            function storeDataLocally(json) {
                if(json && json.content){
                    var mmt = moment();
                    var mmtformat = mmt.format();
                    var mmtunix = mmt.unix();

                    if(json.content != "grid_details"){
                        $('.btnStausConnection').addClass('hideMe');
                        $('.cnsuccess').removeClass('hideMe').find('span').text('Connected');
                        $('.btnSettings').removeClass('hideMe');
                        localStorage.setItem('mqttToreAtmmtformat', mmtformat);
                        localStorage.setItem('mqttToreAtmmtmmtunix', mmtunix);
                    }

                    localStorage.setItem(json.content, JSON.stringify(json));
                }
            }
            function loadDataLocally(json) {
                var now = moment(new Date());
                var end = moment(localStorage.getItem('mqttToreAtmmtformat'));
                var duration = moment.duration(now.diff(end));
                var min = duration.asMinutes(); 
                var sec = duration.asSeconds(); 
                console.log('sec',sec);

                if(sec < 10 && control_card_no){
                    //$('.lastUpdatedAt').addClass('text-danger');
                    var inverter_details = JSON.parse(localStorage.getItem('inverter_details'));
                    if(inverter_details && inverter_details.Control_card_sn == control_card_no){
                        set_inverter_details(inverter_details);
                    }

                    var battery_details = JSON.parse(localStorage.getItem('battery_details'));
                    if(battery_details && battery_details.Control_card_sn == control_card_no){
                        set_battery_details(battery_details);
                    }

                    var pv_details = JSON.parse(localStorage.getItem('pv_details'));
                    if(pv_details && pv_details.Control_card_sn == control_card_no){
                        set_pv_details(pv_details);
                    }

                    var grid_details = JSON.parse(localStorage.getItem('grid_details'));
                    if(grid_details && grid_details.Control_card_sn == control_card_no){
                        set_grid_details(grid_details);
                    }

                    var energy_details = JSON.parse(localStorage.getItem('energy_details'));
                    if(energy_details && energy_details.Control_card_sn == control_card_no){
                        set_energy_details(energy_details);
                    }

                    var alarm_warning_details = JSON.parse(localStorage.getItem('alarm_warning_details'));
                    if(alarm_warning_details && alarm_warning_details.Control_card_sn == control_card_no){
                        set_alarm_warning_details(alarm_warning_details);
                    }


                }
            }
            loadDataLocally();
            
            function set_inverter_details(json) {
                let inv_total_powerText = json.inv_total_power.value + ' ' + json.inv_total_power.unit;
                let inv_energy_todayText = tofixNo(json.inv_energy_today.value) + ' ' + json.inv_energy_today.unit;
                let inv_energy_totalText = tofixNo(json.inv_energy_total.value)  + ' ' + json.inv_energy_total.unit;
                $('.inv_total_power').text(inv_total_powerText);
                $('.inv_energy_today').text(inv_energy_todayText);
                $('.inv_energy_total').text(inv_energy_totalText);

                let inv_phase1_voltageText = tofixNo(json.inv_phase1_voltage.value) + ' ' + json.inv_phase1_voltage.unit;
                let inv_phase1_currentText = tofixNo(json.inv_phase1_current.value) + ' ' + json.inv_phase1_current.unit;
                let inv_phase1_powerText = json.inv_phase1_power.value + ' ' + json.inv_phase1_power.unit;
                $('.inv_phase1_voltage').text(inv_phase1_voltageText);
                $('.inv_phase1_current').text(inv_phase1_currentText);
                $('.inv_phase1_power').text(inv_phase1_powerText);

                let inv_phase2_voltageText = tofixNo(json.inv_phase2_voltage.value) + ' ' + json.inv_phase2_voltage.unit;
                let inv_phase2_currentText = tofixNo(json.inv_phase2_current.value) + ' ' + json.inv_phase2_current.unit;
                let inv_phase2_powerText = json.inv_phase2_power.value + ' ' + json.inv_phase2_power.unit;
                $('.inv_phase2_voltage').text(inv_phase2_voltageText);
                $('.inv_phase2_current').text(inv_phase2_currentText);
                $('.inv_phase2_power').text(inv_phase2_powerText);

                let inv_phase3_voltageText = tofixNo(json.inv_phase3_voltage.value) + ' ' + json.inv_phase3_voltage.unit;
                let inv_phase3_currentText = tofixNo(json.inv_phase3_current.value) + ' ' + json.inv_phase3_current.unit;
                let inv_phase3_powerText = json.inv_phase3_power.value + ' ' + json.inv_phase3_power.unit;
                $('.inv_phase3_voltage').text(inv_phase3_voltageText);
                $('.inv_phase3_current').text(inv_phase3_currentText);
                $('.inv_phase3_power').text(inv_phase3_powerText);

            }

            function set_battery_details(json) {
                //let bat_powerText = Math.abs(json.bat_power.value) + ' ' + json.bat_power.unit;
                let bat_powerText = json.bat_power.value + ' ' + json.bat_power.unit;
                let bat_socText = json.bat_soc.value + ' ' + json.bat_soc.unit;
                let bat_energy_totalText = json.bat_energy_total.value + ' ' + json.bat_energy_total.unit;
                let bat_VoltageText = tofixNo(json.bat_Voltage.value) + ' ' + json.bat_Voltage.unit;
                let bat_currentText = tofixNo(json.bat_current.value) + ' ' + json.bat_current.unit;
                $('.bat_power').text( bat_powerText);
                $('.bat_energy_total').text( bat_energy_totalText);
                $('.bat_soc').text(bat_socText);
                $('.bat_Voltage').text(bat_VoltageText);
                $('.bat_current').text(bat_currentText);
                $('.bat_socChart').text(json.bat_soc.value + json.bat_soc.unit);
                //$('.bat_socChart').val(json.bat_soc.value + json.bat_soc.unit).trigger('change');

                let bat_current = parseInt(json.bat_current.value);
                //if (bat_current > 0) {
                if ( json.bat_power.value  == 0) {
                    $('.InvertertoBattery, .clCharging').addClass('hideMe');
                    $('.BatterytoInverter').addClass('hideMe');
                } else if ( json.bat_power.value  > 0) {
                    $('.InvertertoBattery, .clCharging').removeClass('hideMe');
                    $('.clCharging').addClass('text-success').removeClass('text-danger').text('Charging...');
                    $('.BatterytoInverter').addClass('hideMe');
                } else {
                    $('.InvertertoBattery, .clCharging').addClass('hideMe');
                    $('.clCharging').removeClass('hideMe text-success').addClass('text-danger').text('Discharging...');
                    $('.BatterytoInverter').removeClass('hideMe');
                }
                console.log('bat_power ==>', json.bat_power.value);
            }

            function set_pv_details(json) {
                let total_pv_powerText = json.total_pv_power.value + ' ' + json.total_pv_power.unit;
                $('.total_pv_power').text(total_pv_powerText);

                let pv_energy_todayText = tofixNo(json.pv_energy_today.value) + ' ' + json.pv_energy_today.unit;
                $('.pv_energy_today').text(pv_energy_todayText);

                let pv_energy_totalText = tofixNo(json.pv_energy_total.value) + ' ' + json.pv_energy_total.unit;
                $('.pv_energy_total').text(pv_energy_totalText);

                let pv1_voltageText = tofixNo(json.pv1_voltage.value) + ' ' + json.pv1_voltage.unit;
                $('.pv1_voltage').text(pv1_voltageText);
                let pv1_currentText = tofixNo(json.pv1_current.value) + ' ' + json.pv1_current.unit;
                $('.pv1_current').text(pv1_currentText);
                let pv1_powerText = json.pv1_power.value + ' ' + json.pv1_power.unit;
                $('.pv1_power').text(pv1_powerText);

                let pv2_voltageText = tofixNo(json.pv2_voltage.value) + ' ' + json.pv2_voltage.unit;
                $('.pv2_voltage').text(pv2_voltageText);
                let pv2_currentText = tofixNo(json.pv2_current.value) + ' ' + json.pv2_current.unit;
                $('.pv2_current').text(pv2_currentText);
                let pv2_powerText = json.pv2_power.value + ' ' + json.pv2_power.unit;
                $('.pv2_power').text(pv2_powerText);

                let pv1_power = parseInt(json.pv1_power.value);
                let pv2_power = parseInt(json.pv2_power.value);
                if (pv1_power > 0 || pv2_power > 0) {
                    $('.SolartoInverter').removeClass('hideMe');
                } else {
                    $('.SolartoInverter').addClass('hideMe');
                }
            }

            function set_grid_details(json) {
                let grid_total_powerText = json.grid_total_power.value + ' ' + json.grid_total_power.unit;
                $('.grid_total_power').text(grid_total_powerText);

                let grid_run_timeText = tofixNo(json.grid_run_time.value) + ' ' + json.grid_run_time.unit;
                $('.grid_run_time').text(grid_run_timeText);

                let grid_energy_sell_todayText = tofixNo(json.grid_energy_sell_today.value) + ' ' + json.grid_energy_sell_today.unit;
                let grid_energy_sell_totalText = tofixNo(json.grid_energy_sell_total.value) + ' ' + json.grid_energy_sell_total.unit;
                $('.grid_energy_sell_today').text(grid_energy_sell_todayText);
                $('.grid_energy_sell_total').text(grid_energy_sell_totalText);

                let grid_energy_buy_todayText = tofixNo(json.grid_energy_buy_today.value) + ' ' + json.grid_energy_buy_today.unit;
                let grid_energy_byu_totalText = tofixNo(json.grid_energy_byu_total.value) + ' ' + json.grid_energy_byu_total.unit;
                $('.grid_energy_buy_today').text(grid_energy_buy_todayText);
                $('.grid_energy_byu_total').text(grid_energy_byu_totalText);

                let grid_phase1_voltageText = tofixNo(json.grid_phase1_voltage.value) + ' ' + json.grid_phase1_voltage.unit;
                let grid_phase1_powerText = tofixNo(json.grid_phase1_power.value) + ' ' + json.grid_phase1_power.unit;
                $('.grid_phase1_voltage').text(grid_phase1_voltageText);
                $('.grid_phase1_power').text(grid_phase1_powerText);

                let grid_phase2_voltageText = tofixNo(json.grid_phase2_voltage.value) + ' ' + json.grid_phase2_voltage.unit;
                let grid_phase2_powerText = tofixNo(json.grid_phase2_power.value) + ' ' + json.grid_phase2_power.unit;
                $('.grid_phase2_voltage').text(grid_phase2_voltageText);
                $('.grid_phase2_power').text(grid_phase2_powerText);

                let grid_phase3_voltageText = tofixNo(json.grid_phase3_voltage.value) + ' ' + json.grid_phase3_voltage.unit;
                let grid_phase3_powerText = tofixNo(json.grid_phase3_power.value) + ' ' + json.grid_phase3_power.unit;
                $('.grid_phase3_voltage').text(grid_phase3_voltageText);
                $('.grid_phase3_power').text(grid_phase3_powerText);
            }

            function set_energy_details(json) {
                let total_load_consumeText = json.total_load_consume.value + ' ' + json.total_load_consume.unit;
                $('.total_load_consume').text(total_load_consumeText);

                let total_feedin_power = parseInt(json.total_feedin_power.value);
                let total_load_consume = parseInt(json.total_load_consume.value);
                if (total_feedin_power == 0) {
                    $('.GridtoInverter').addClass('hideMe');
                    $('.InvertertoGrid').addClass('hideMe');
                } else if (total_feedin_power > 0) {
                    $('.GridtoInverter').addClass('hideMe');
                    $('.InvertertoGrid').removeClass('hideMe');
                } else {
                    $('.GridtoInverter').removeClass('hideMe');
                    $('.InvertertoGrid').addClass('hideMe');
                }
                if (total_load_consume > 0) {
                    $('.InvertertoLoad').removeClass('hideMe');
                } else {
                    $('.InvertertoLoad').addClass('hideMe');
                }
            }

            function set_alarm_warning_details(json) {
                if(json.alarm_warning > 0){
                    showAlarmWarning(json.Control_card_sn,json.alarm_warning_code);
                }
            }

            setInterval(function(){ 

                var now = moment(new Date());
                var end = moment(localStorage.getItem('mqttToreAtmmtformat'));
                var duration = moment.duration(now.diff(end));
                var sec = duration.asSeconds(); 
                console.log('sec => ', sec);
                console.log('end => ', end.format('DD-MM-YYYY hh:mm:ss a'));
                if(sec < 10){
                    $('.btnStausConnection').addClass('hideMe');
                    $('.cnsuccess').removeClass('hideMe').find('span').text('Connected');
                    $('.btnSettings').removeClass('hideMe');
                } else {
                    $('.btnStausConnection').addClass('hideMe');
                    $('.cndanger').removeClass('hideMe').find('span').text('Disconnected');
                    $('.btnSettings').addClass('hideMe');
                    resetInvData();
                }

                

            }, 15000);

            function resetInvData() {
                $('.InvertertoBattery, .clCharging, .BatterytoInverter, .SolartoInverter, .GridtoInverter, .InvertertoGrid, .InvertertoLoad').addClass('hideMe');
                $('.inv_total_power, .inv_energy_today, .inv_energy_total').text(0);

                $('.bat_power, .bat_soc, .bat_socChart').text(0);
                $('.bat_socChart').val(0).trigger('change');

                $('.total_pv_power').text(0);

                $('.grid_total_power').text(0);

                $('.total_load_consume').text(0);

            }

        });

       /* let animation = document.getElementById("batteryAnimation");
                animation.addEventListener("ready", () => {
                console.log("You've captured the ready event!");
            });

        var totalFrames = animation.getLottie().totalFrames;
        console.log('totalFrames',totalFrames);
        function randomIntFromInterval(min, max) { // min and max included 
            return Math.floor(Math.random() * (max - min + 1) + min)
        }

        setInterval(function(){
            const rndInt = randomIntFromInterval(100, 1500);
            console.log('rndInt',rndInt);
            animation.seek(rndInt);
        }, 10000);*/


        $(document).ready(function() {
            $('#startDate').datepicker({
                dateFormat: 'yy-mm-dd',
            });
            
            $('#startDate').datepicker({})
                .on('change.dp', function(e) {
                    updateChartData();
                });
            // $('#endDate').datepicker({})
            //     .on('change.dp', function(e) {
            //         updateChartData();
            //     });


            $('#enDay').datepicker({
                dateFormat: 'dd',
                beforeShow: function (input, inst) {
                    inst.dpDiv.addClass('endDaypicker');
                },
                onClose: function(dateText, inst){
                    inst.dpDiv.removeClass('endDaypicker');
                }
            });

            $('#enMonth').datepicker({
                dateFormat: 'mm',
                autoclose: true,
                changeMonth:true,
                constrainInput: false,
                onChangeMonthYear: function(year, month) {
                    if(month < 10){
                        month = '0'+ month;
                    }
                    $('#enMonth').val(month);
                    //$( "#enMonth" ).datepicker( "option", "defaultDate", month );
                    // var newDate = new Date(year, month, 0);
                    // $("#enMonth").datepicker("setDate", newDate);
                    $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next").hide();
                    $(this).datepicker('hide');
                    updateEnergyChartData();
                }
            });
            $("#enMonth").focus(function () {
                $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-year").hide();
                $("#ui-datepicker-div").position({
                    my: "center top",
                    at: "center bottom",
                    of: $(this)
                });
            });

            $('#enYear').datepicker({
                dateFormat: 'yy',
                autoclose: true,
                changeYear:true,
                yearRange: "-5:+0",
                constrainInput: false,
                onChangeMonthYear: function(year, month) {
                    $('#enYear').val(year);
                    var date= $('#enYear').val();
                    $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next").hide();
                    $(this).datepicker('hide');
                    updateEnergyChartData();
                }
            });
            $("#enYear").focus(function () {
                $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-month").hide();
                $("#ui-datepicker-div").position({
                    my: "center top",
                    at: "center bottom",
                    of: $(this)
                });
            });

        });

        const ctx = document.getElementById('powerChart');
        var config = {
            type: 'line',
            data: {
            //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'PV Power',
                data: [],
                borderWidth: 1
            },
            {
                label: 'AC Power',
                data: [],
            }]
            },
            options: {
                responsive:true,
                plugins: { 
                    legend: {
                        labels: {
                            color: "white",  
                            font: {
                                size: 18
                            }
                        },
                        onClick: handleLegendClick
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'minute'
                        },
                        ticks: {
                            color: '#f5f2f2',
                            stepSize: 15 // interval
                        },
                        title: {
                            color: '#49c5b6',
                            display: true,
                            text: 'Time',
                            font: {
                                size: 20,
                                weight: 'bold',
                            },
                        }
                    },
                    y: {
                        position: 'left',
                        ticks: {
                            font: {
                                size: 15,
                                lineHeight: 0.5
                            },
                            color: 'white',
                        },
                        title: {
                            color: '#49c5b6',
                            display: true,
                            text: 'W',
                            font: {
                                size: 20,
                                weight: 'bold',
                            },
                        }
                    },
                    y1: {
                        display: false,
                        position: 'right',
                        ticks: {
                            font: {
                                size: 15,
                                lineHeight: 0.5
                            },
                            color: 'white',
                        },
                        title: {
                            color: '#49c5b6',
                            display: true,
                            text: '%',
                            font: {
                                size: 20,
                                weight: 'bold',
                            },
                        }
                    }
                }
            }
        };
        const myPowerChart = new Chart(ctx, config);


    function updateChartData() {
            var startDate = $('#startDate').val();
           // var endDate = $('#endDate').val();
            var endDate = startDate;
            $('.btnLoading').removeClass('hideMe');
            $.ajax({
                    url: '{{url("admin/get-power-graph-data")}}', 
                    // url: '{{url("admin/testQry")}}', 
                    type: "POST",             
                    data:  {control_card_no:control_card_no, startDate:startDate, endDate:endDate },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },    
                success: function(data) {
                    $('.btnLoading').addClass('hideMe');
                    myPowerChart.data.datasets = data.datasets;
                    myPowerChart.options.scales.x.time.unit = data.time_type;
                    myPowerChart.update();

                }
            });

        
    }
    updateChartData();

    function handleLegendClick(evt, item, legend) {
        //get the index of the clicked legend
        var index = item.datasetIndex;
        //toggle chosen dataset's visibility
        myPowerChart.data.datasets[index].hidden = !myPowerChart.data.datasets[index].hidden;
        //toggle the related labels' visibility
        if(index == 4){
            myPowerChart.options.scales.y1.display = !myPowerChart.options.scales.y1.display;
        }
        
        myPowerChart.update();
    }

    // Energy chart
    const ctx2 = document.getElementById('energyChart');
        var config = {
            type: 'bar',
            data: {
            //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: []
            },
            options: {
                responsive:true,
                plugins: { 
                    legend: {
                        labels: {
                            color: "white",  
                            font: {
                                size: 18
                            }
                        },
                    },
                    tooltip: {
                        mode: 'index',
                        //intersect: false
                    }
                },
                interaction: {
                    intersect: true,
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            color: '#f5f2f2',
                        },
                        title: {
                            color: '#49c5b6',
                            display: true,
                            text: 'Time',
                            font: {
                                size: 20,
                                weight: 'bold',
                            },
                        }
                    },
                    y: {
                        stacked: true,
                        position: 'left',
                        ticks: {
                            font: {
                                size: 15,
                                lineHeight: 0.5
                            },
                            color: 'white',
                        },
                        title: {
                            color: '#49c5b6',
                            display: true,
                            text: 'kwh',
                            font: {
                                size: 20,
                                weight: 'bold',
                            },
                        }
                    }
                }
            }
        };
        const myEnergyChart = new Chart(ctx2, config);
    $("input[name='enchartFilter']").click(function() {
        updateEnergyChartData();
    });
    $("#enDay, #enMonth, #enYear").change(function() {
        updateEnergyChartData();
    });
    $(".resetEnChart ").click(function() {
        $('#enDay').val("{{date('d')}}");
        $('#enMonth').val("{{date('m')}}");
        $('#enYear').val("{{date('Y')}}");
       // $('[name="enchartFilter"]').val('month').change();
        updateEnergyChartData();
    });
    function updateEnergyChartData() {
        var enchartFilter = $("input[name='enchartFilter']:checked").val();
        var enDay = $('#enDay').val();
        var enMonth = $('#enMonth').val();
        var enYear = $('#enYear').val();
        var timeText = 'Time';
        if(enchartFilter == 'day'){
            timeText = 'Hour';
        } else if(enchartFilter == 'month'){
            timeText = 'Date';
        } else if(enchartFilter == 'year'){
            timeText = 'Month';
        } else if(enchartFilter == 'all'){
            timeText = 'Year';
        }

        $('.btnLoadingEnergy').removeClass('hideMe');
        $.ajax({
                url: '{{url("admin/get-energy-graph-data")}}', 
                type: "POST",             
                data:  {control_card_no:control_card_no, enchartFilter:enchartFilter, enDay:enDay, enMonth:enMonth, enYear:enYear },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },    
            success: function(data) {
                $('.btnLoadingEnergy').addClass('hideMe');
                myEnergyChart.data.datasets = data.datasets;
                myEnergyChart.data.labels = data.labels;
                myEnergyChart.options.scales.x.title.text = timeText;
                myEnergyChart.update();

            }
        });
    }
    updateEnergyChartData();

    </script>
@endsection
