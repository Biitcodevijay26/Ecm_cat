@extends('front.layout_admin.app')

@section('page_level_css')
    <!--- Custom Style CSS -->
    <link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet" />
    <style>
    .lds-ring div {
        border: 6px solid #4180FF;
        border-color: #4180FF transparent transparent transparent;
    }
    .selection-scroll {
        max-height: 550px !important;
    }
    .chart-height {
        min-height: 610px;
    }
    .chart-maximize{
        width: 100vw;
        position: fixed;
        height: 100vh;
        top: 0;
        left: 0;
        z-index: 100;
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

                                        <a href="{{url('/edit-device/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/edit-icon.png') }}" alt="edit-device-icon"> </span> Edit POWRBANK
                                        </a>

                                        <a href="{{url('/charts/'.$id)}}" class="btn bg-color-black text-white device-btn-width device-btn-text me-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/chart-icon-w.png') }}" alt="view-chart-icon"> </span> View Chart
                                        </a>

                                        <a href="javascript:void(0);" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 machine_verified_btn d-none mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/verified-icon.png') }}" alt="verify-machine-icon"> </span> Verify Machine
                                        </a>
                                        <a href="{{url('/remote-access-view/'.$id)}}" class="btn bg-color-default text-black device-btn-width device-btn-text me-2 remort_access_btn mb-2">
                                            <span> <img class="device-btn-icon" src="{{ url('theme-asset/images/icon/remort-icon.png') }}" alt="remort-access-icon"> </span> Remote Access
                                        </a>
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
                                    <a href="javascript:void(0);" class="card-options-btn me-3 mt-1"><i class="fe fe-maximize fs-20"></i></a>
                                </div>
                            </div>
                            <div class="card-body selection-scroll">
                                <div class="chart-container">
                                    {{-- <canvas id="chartLine" class="h-275"></canvas> --}}
                                    <canvas id="chartLine" height="500"></canvas>
                                </div>
                                <div class="dimmer chart-loader d-none">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="card chart-height">
                            <div class="card-header pb-2">
                                <h3 class="device-card-title">Selection</h3>
                            </div>
                            <div class="card-body selection-scroll">
                                <div class="row">
                                    <?php
                                    $pv = 0;
                                    $grid_genset  = 0;
                                    $battery   = 0;
                                    $alaram_state = 0;
                                    $ess = 0;

                                    if (isset($data) && $data) :
                                    foreach ($data as $key => $value) :
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
                                    <?php } ?>

                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input chkBox" name="{{$name}}"
                                                data-text="{{$value['data_value'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{ $key == 0 ? 'checked' : ''}}>
                                            <span class="custom-control-label">{{$value['option_disp'] ?? ''}}</span>
                                        </label>
                                    </div>

                                    <?php endforeach; endif; ?>
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
                                        <button type="button" class="btn btn-icon  btn-primary prev"><i
                                                class="fa fa-arrow-left"></i></button>
                                        {{-- <ul class="icons-list">
                                        <li class="icons-list-item icons-list-item-overview-page prev"><i class="fa fa-arrow-left"></i></li>
                                    </ul> --}}
                                    </div>
                                    <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                        <div class="input-group">
                                            <input class="form-control fc-datepicker" placeholder="YYYY/MM/DD"
                                                type="text" id="chart-date" name="chart-date" autocomplete="off"
                                                readonly>
                                            <div class="input-group-text bg-color-black text-white">
                                                <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 p-0">
                                        <button type="button" class="btn btn-icon  btn-primary next"><i
                                                class="fa fa-arrow-right"></i></button>
                                        {{-- <ul class="icons-list">
                                        <li class="icons-list-item icons-list-item-overview-page"><i class="fa fa-arrow-right"></i></li>
                                    </ul> --}}
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center">
                                <button type="button" class="btn btn-icon  btn-primary chart-home-btn"><i
                                        class="fa fa-home"></i></button>
                                {{-- <ul class="icons-list">
                                <li class="icons-list-item icons-list-item-overview-page"><i class="fa fa-home"></i></li>
                            </ul> --}}
                            </div>
                            <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                <div class="form-group form-group-register error-country_id">
                                    <select class="form-select filter_dropdown" name="filter-dropdown" id="filter-dropdown">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary filter-btn"
                                    data-value="days">Days</a>
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary filter-btn"
                                    data-value="month">Months</a>
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary filter-btn"
                                    data-value="year">Years</a>
                                <a href="javascript:void(0);" class="btn btn-radius btn-primary filter-btn"
                                    data-value="global">Global</a>
                            </div>
                            <div class="col-sm-12 col-md-1 col-lg-1 col-xl-1">
                                <button type="button" class="btn btn-icon  btn-primary chart-reload-btn"><i
                                        class="fa fa-refresh"></i></button>
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

    <script type="text/javascript">
        // Hide sidebar



        var  chart_type = 'line';
        const macid     = "{{ $macid }}";
        const ip        = "{{ config('constants.MQTT_IP') }}";
        const port      = "{{ config('constants.MQTT_PORT') }}";
        var userID      = "{{ $userID }}";
        var is_verified = "{{ $is_verified }}";
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
            // let client = mqtt.connect('ws://' + ip + ':' + port, options);
            // client.on('connect', function(data) {
            //     var msgDataRetain = '';
            //     // client.publish('read_data', msgDataRetain, {retain : true}, function(err) {
            //     //     if (err) {
            //     //         console.log('ERROR read_data retain =>', err);
            //     //     }
            //     // });

            //     client.subscribe('read_data/' + macid, {
            //         qos: 2
            //     }, function(err) {
            //         if (!err) {
            //             // client.publish('read_data', 'Hello mqtt Self Test');
            //         }

            //         if (err) {
            //             console.log('ERROR read_data =>', err);
            //         }
            //     });

            //     // var msgData = 'Hello, this is data to write';
            //     // client.publish('write_data', msgData, function(err) {
            //     //     if (err) {
            //     //         console.log('ERROR write_data =>', err);
            //     //     }
            //     // });
            // });

            // client.on('message', function(topic, message) {
            //     // message is Buffer
            //     //    console.log('message => ', message.toString());
            //     //    console.log('Data From MQTT => ');
            //     //    console.log('topic => ', topic);
            //     if (topic == 'read_data/' + macid) {
            //         var msgData = message.toString();
            //         var json;
            //         try {
            //             json = JSON.parse(msgData);
            //         } catch (e) {}
            //         console.log('json ====>', json.data.Contain);
            //         if (json.data && json.data.Contain) {
            //             getCurrenTime();
            //             let content = json.data.Contain;
            //             switch (content) {

            //                 case 'Battery':
            //                     set_battery_details(json);
            //                     break;

            //                 case 'Grid/Genset':
            //                     set_grid_details(json);
            //                     break;

            //                 case 'PV':
            //                     set_pv_details(json);
            //                     break;

            //                 case 'Alarms/State':
            //                     set_alarms_state_details(json);
            //                     break;

            //                 default:
            //                     break;
            //             }
            //         }
            //     }
            // });
            // client.on('error', function(error) {
            //     console.log('Error => ', error);
            // });

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

            // function set_grid_details(json) {
            //     if (json.data.Power) {
            //         var grid_total = 0;
            //         var generator_total = 0;
            //         $.each(json.data.Power, function(key, val) {
            //             // Grid Total
            //             if (key == "Grid_P_L1(W)" || key == "Grid_P_L2(W)" || key == "Grid_P_L3(W)") {
            //                 grid_total += parseInt(val);
            //             }

            //             // Generator Total
            //             if (key == "Gen_P_L1(W)" || key == "Gen_P_L1(W)" || key == "Gen_P_L1(W)") {
            //                 generator_total += parseInt(val);
            //             }


            //         });
            //         if (grid_total > 0) {
            //             $('.Grid_P_L2').html('');
            //             $('.Grid_P_L2').html(grid_total + '.00 W');
            //         }
            //         if (generator_total > 0) {
            //             $('.Gen_P_L2').html('');
            //             $('.Gen_P_L2').html(generator_total + '.00 W');
            //         }

            //     }

            //     if (json.data["Source Selection"]) {
            //         var voltage_total = 0;
            //         $.each(json.data["Source Selection"], function(key, val) {
            //             // Voltage
            //             if (key == "O_V_L1(V)" || key == "O_V_L2(V)" || key == "O_V_L3(V)") {
            //                 voltage_total += parseInt(val);
            //             }
            //         });
            //         if (voltage_total > 0) {
            //             voltage_total = (voltage_total) * 1.473;
            //             voltage_total = tofixNo(voltage_total);
            //             $('.total_voltage').html('');
            //             $('.total_voltage').html(voltage_total + ' V');
            //         }
            //     }
            //     if (json.data["Frequency"]) {
            //         var frequency_total = 0;
            //         $.each(json.data["Frequency"], function(key, val) {
            //             // Frequency
            //             if (key == "Out_F(Hz)") {
            //                 frequency_total += parseInt(val);
            //             }
            //         });
            //         if (frequency_total > 0) {
            //             frequency_total = tofixNo(frequency_total);
            //             $('.total_frequency').html('');
            //             $('.total_frequency').html(frequency_total + ' Hz');
            //         }
            //     }

            // }

            // function set_battery_details(json) {
            //     if (json.data.Status) {
            //         $.each(json.data.Status, function(key, val) {
            //             if (key == "SOC(%)") {
            //                 $('.SOC').text(val + '.00(%) SOC');
            //             } else if (key == "SOH(%)") {
            //                 $('.SOH').text(val + '.00(%) SOH');
            //             }

            //         });
            //     }
            // }

            // function set_pv_details(json) {
            //     if (json.data.Power) {
            //         var ac_solar_total = 0;
            //         var dc_solar_total = 0;
            //         $.each(json.data.Power, function(key, val) {
            //             if (key == "PV_AC_O/L1(W)" || key == "PV_AC_O/L2(W)" || key == "PV_AC_O/L3(W)") {
            //                 ac_solar_total += parseInt(val);
            //             }
            //             if (key == "PV_DC(W)") {
            //                 dc_solar_total += parseInt(val);;
            //             }
            //         });
            //         if (ac_solar_total > 0) {
            //             $('.PV_AC_O_L2').html('');
            //             $('.PV_AC_O_L2').html(ac_solar_total + '.00 W');
            //         }
            //         if (dc_solar_total > 0) {
            //             $('.PV_DC').html('');
            //             $('.PV_DC').html(dc_solar_total + '.00 W');
            //         }
            //     }
            // }

            // function set_alarms_state_details(json) {
            //     $('.alert-alarms').addClass('d-none');
            //     if (json.data["Battery Alarms"]) {
            //         if (json.data["Battery Alarms"]["BMS_BAT_CH"] == 1) {
            //             $('.alert-alarms').removeClass('d-none');
            //         }
            //     }
            // }

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
                    // datasets: [{
                    //     label: '(W)',
                    //     data: [100, 420, 210, 420, 210, 320, 350],
                    //     borderWidth: 2,
                    //     backgroundColor: 'transparent',
                    //     borderColor: '#6259ca',
                    //     borderWidth: 3,
                    //     pointBackgroundColor: '#ffffff',
                    //     pointRadius: 2,
                    //     lineTension: 0.3,
                    //     yAxisID: 'y',
                    //     hidden: true
                    // }, {
                    //     label: '(V)',
                    //     data: [450, 200, 350, 250, 480, 200, 400],
                    //     borderWidth: 2,
                    //     backgroundColor: 'transparent',
                    //     borderColor: '#eb6f33',
                    //     borderWidth: 3,
                    //     pointBackgroundColor: '#ffffff',
                    //     pointRadius: 2,
                    //     lineTension: 0.3,
                    //     yAxisID: 'y1',
                    //     hidden: true
                    // }]
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
                                color: "#4180FF",
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
                                color: "#4180FF"
                            }
                        },
                        y1: {
                            ticks: {
                                color: "#eb6f33",
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
                                color: '#eb6f33'
                            },
                        },
                        y2: {
                            ticks: {
                                color: "#FBAF16",
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
                                color: "#FBAF16"
                            }
                        },
                        y3: {
                            ticks: {
                                color: "#45BF55",
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
                                color: '#45BF55'
                            },
                        },
                        y4: {
                            ticks: {
                                color: "#FF2966",
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
                                color: "#FF2966"
                            }
                        },
                        y5: {
                            ticks: {
                                color: "#4180FF",
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
                                color: "#4180FF"
                            }
                        },
                        y6: {
                            ticks: {
                                color: "#eb6f33",
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
                                color: "#eb6f33"
                            }
                        },
                        y7: {
                            ticks: {
                                color: "#FBAF16",
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
                                color: "#FBAF16"
                            }
                        },
                        y8: {
                            ticks: {
                                color: "#45BF55",
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
                                color: "#45BF55"
                            }
                        },
                        y9: {
                            ticks: {
                                color: "#FF2966",
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
                                color: "#FF2966"
                            }
                        },
                        y10: {
                            ticks: {
                                color: "#4180FF",
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
                                color: "#4180FF"
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: "#77778e",
                            },
                            display: true,
                            position:'bottom',
                        },
                    },
                }
            });

            $(".prev").click(function() {
                var date = $('.fc-datepicker').datepicker('getDate', '-1d');
                date.setDate(date.getDate() - 1);
                $('.fc-datepicker').datepicker('setDate', date);
                addDynamicGraphData();
            })

            $(".next").click(function() {
                var date = $('.fc-datepicker').datepicker('getDate', '+1d');
                date.setDate(date.getDate() + 1);
                $('.fc-datepicker').datepicker('setDate', date);
                addDynamicGraphData();
            })

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
                        var counts = hz_count + w_count + ah_count + kwh_count + A_count + V_count + Battery_count + State_count + State_Alarms_count + State_Temperature_count + State_Battery_count + ess_status_count;
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
                        else if (counts > 5 && ess_status_count > 0) {
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

                    if (_this.prop('checked') == true) {
                        // addData('day', _this.attr('data-value'), chkSelected,selected_type,selected_key);
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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

                    if (_this.prop('checked') == true) {
                        addDynamicGraphData();
                    } else {
                        removeData(chkSelected);
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
                $('.filter-btn').removeClass('btn-default');
                $('.filter-btn').addClass('btn-primary');

                _this.removeClass('btn-primary');
                _this.addClass('btn-default');
                _this.addClass('active-btn');
                if (value == "days") {
                    setDaysInDropdown(Days);
                } else if (value == "month") {
                    setMonthInDropdown(Month);
                } else if (value == "year") {
                    setYearsInDropdown();
                } else if(value == "global"){
                    var html = "";
                    html += "<option value=''>Select</option>";
                    $('#filter-dropdown').html('');
                    $('#filter-dropdown').html(html);
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
            function showHideLabels() {
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
                    // myChart.options.scales.y1.title.display = true;
                }
                if (ah_count <= 0) {
                    myChart.config.options.scales.y2.display = false;
                } else {
                    myChart.config.options.scales.y2.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }
                if (kwh_count <= 0) {
                    myChart.config.options.scales.y3.display = false;
                } else {
                    myChart.config.options.scales.y3.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }
                if (A_count <= 0) {
                    myChart.config.options.scales.y4.display = false;
                } else {
                    myChart.config.options.scales.y4.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (V_count <= 0) {
                    myChart.config.options.scales.y5.display = false;
                } else {
                    myChart.config.options.scales.y5.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (Battery_count <= 0) {
                    myChart.config.options.scales.y6.display = false;
                } else {
                    myChart.config.options.scales.y6.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (State_count <= 0) {
                    myChart.config.options.scales.y7.display = false;
                } else {
                    myChart.config.options.scales.y7.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (State_Alarms_count <= 0) {
                    myChart.config.options.scales.y8.display = false;
                } else {
                    myChart.config.options.scales.y8.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (State_Temperature_count <= 0) {
                    myChart.config.options.scales.y9.display = false;
                } else {
                    myChart.config.options.scales.y9.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (State_Battery_count <= 0) {
                    myChart.config.options.scales.y10.display = false;
                } else {
                    myChart.config.options.scales.y10.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                if (ess_status_count <= 0) {
                    myChart.config.options.scales.y10.display = false;
                } else {
                    myChart.config.options.scales.y10.display = true;
                    // myChart.options.scales.y1.title.display = true;
                }

                // Hide show Y axis
                // if (w_count <= 0) {
                //     display
                // } else {

                // }
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
                showHideLabels();
            }

            // Date picker On change
            $(document).on('change', '#chart-date', function() {
                var _this = $(this);
                addDynamicGraphData();
            });

            function addDynamicGraphData() {
                var dropworn_val = $("#filter-dropdown").val();
                var filter_type  = $('.filter-btn.active-btn').attr('data-value');
                var current_date = $("#chart-date").val();
                var selected_option = [];
                var chkBox   = $('.chkBox:checked');
                var chkCount = $('.chkBox:checked').length;
                if(chkBox)
                {
                    chkBox.each(function (j) {
                        var data = {};
                        data['selected']      = $(this).attr('data-text');
                        data['selected_key']  = $(this).attr('data-key');
                        data['selected_type'] = $(this).val();
                        data['chart_type']    = $(this).attr('data-value');
                        data['current_date']  = current_date;
                        data['macid']         = macid;
                        if(dropworn_val)
                        {
                            data['filter_type']   = filter_type ?? 'today';
                        } else {
                            data['filter_type']   = (filter_type && filter_type == 'global' ? 'global' : 'today');
                        }
                        data['filter_value']  = dropworn_val;
                        selected_option.push(data);
                    });
                }

                if(chkCount > 0)
                {
                    $('.chart-container').addClass('d-none');
                    $('.chart-loader').removeClass('d-none');
                    myChart.data.datasets = [];
                    myChart.update();
                    $.ajax({
                        url: '{{ url('get-test-charts-data') }}',
                        type: "POST",
                        data: {'chart' : selected_option},
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
                            showHideLabels();
                        }
                    });
                }
            }

            $(document).on('click','.chart-reload-btn',function(){
                $('.chart-container').addClass('d-none');
                $('.chart-loader').removeClass('d-none');
                // myChart.data.datasets = [];
                // myChart.data.labels = ["Sun", "Mon", "Tus", "Wed", "Thu", "Fri", "Sat"];
                // myChart.update();

                setTimeout(() => {
                    $('.chart-loader').addClass('d-none');
                    $('.chart-container').removeClass('d-none');

                    var html = "";
                    html += "<option value=''>Select</option>";
                    $('#select-charts').val('line');

                    $('#filter-dropdown').html('');
                    $('#filter-dropdown').html(html);

                    $('.filter-btn').removeClass('active-btn');
                    $('.filter-btn').removeClass('btn-default');
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

                $('#filter-dropdown').html('');
                $('#filter-dropdown').html(html);

                $('.filter-btn').removeClass('active-btn');
                $('.filter-btn').removeClass('btn-default');
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
                $('.card-options-chart-maximize').removeClass('chart-maximize');
                _this.find('i').removeClass('fe-minimize');
                _this.find('i').addClass('fe-maximize');
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
            }
        });

        }); // Ready
    </script>
@endsection
