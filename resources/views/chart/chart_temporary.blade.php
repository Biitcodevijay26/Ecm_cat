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
    ?>
    <!--app-content open-->
    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER 2 -->
            <div class="main-container container-fluid mt-5">
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
                                <div class="dimmer chart-loader d-none">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
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
                                    <?php
                                    $current = 0;
                                    $energy  = 0;
                                    $power   = 0;
                                    $source_selection = 0;
                                    $voltage = 0;
                                    $frequency = 0;
                                    $battery_alarms = 0;
                                    $state = 0;
                                    $status_alarms = 0;
                                    $temperature = 0;
                                    $status = 0;

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
                                    }
                                    ?>
                                    <?php  if($value['data_key'] == "Current" && $current == 0) { $current++; ?>
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
                                    <?php } ?>
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input chkBox" name="{{$name}}"
                                                data-text="{{$value['data_value'] ?? ''}}" data-value="{{$value['contain'] ?? ''}}" value="{{$value['option_type'] ?? ''}}" data-key="{{ $value['data_key'] ?? ''}}" {{ $key == 0 ? 'checked' : ''}}>
                                            <span class="custom-control-label">{{$value['data_value'] ?? ''}}</span>
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
        const macid = "{{ $macid }}";
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
            let client = mqtt.connect('ws://' + ip + ':' + port, options);
            client.on('connect', function(data) {
                var msgDataRetain = '';
                // client.publish('read_data', msgDataRetain, {retain : true}, function(err) {
                //     if (err) {
                //         console.log('ERROR read_data retain =>', err);
                //     }
                // });

                client.subscribe('read_data/' + macid, {
                    qos: 2
                }, function(err) {
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
                if (topic == 'read_data/' + macid) {
                    var msgData = message.toString();
                    var json;
                    try {
                        json = JSON.parse(msgData);
                    } catch (e) {}
                    console.log('json ====>', json.data.Contain);
                    if (json.data && json.data.Contain) {
                        getCurrenTime();
                        let content = json.data.Contain;
                        switch (content) {

                            case 'Battery':
                                set_battery_details(json);
                                break;

                            case 'Grid/Genset':
                                set_grid_details(json);
                                break;

                            case 'PV':
                                set_pv_details(json);
                                break;

                            case 'Alarms/State':
                                set_alarms_state_details(json);
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
                if (json.data.Power) {
                    var grid_total = 0;
                    var generator_total = 0;
                    $.each(json.data.Power, function(key, val) {
                        // Grid Total
                        if (key == "Grid_P_L1(W)" || key == "Grid_P_L2(W)" || key == "Grid_P_L3(W)") {
                            grid_total += parseInt(val);
                        }

                        // Generator Total
                        if (key == "Gen_P_L1(W)" || key == "Gen_P_L1(W)" || key == "Gen_P_L1(W)") {
                            generator_total += parseInt(val);
                        }


                    });
                    if (grid_total > 0) {
                        $('.Grid_P_L2').html('');
                        $('.Grid_P_L2').html(grid_total + '.00 W');
                    }
                    if (generator_total > 0) {
                        $('.Gen_P_L2').html('');
                        $('.Gen_P_L2').html(generator_total + '.00 W');
                    }

                }

                if (json.data["Source Selection"]) {
                    var voltage_total = 0;
                    $.each(json.data["Source Selection"], function(key, val) {
                        // Voltage
                        if (key == "O_V_L1(V)" || key == "O_V_L2(V)" || key == "O_V_L3(V)") {
                            voltage_total += parseInt(val);
                        }
                    });
                    if (voltage_total > 0) {
                        voltage_total = (voltage_total) * 1.473;
                        voltage_total = tofixNo(voltage_total);
                        $('.total_voltage').html('');
                        $('.total_voltage').html(voltage_total + ' V');
                    }
                }
                if (json.data["Frequency"]) {
                    var frequency_total = 0;
                    $.each(json.data["Frequency"], function(key, val) {
                        // Frequency
                        if (key == "Out_F(Hz)") {
                            frequency_total += parseInt(val);
                        }
                    });
                    if (frequency_total > 0) {
                        frequency_total = tofixNo(frequency_total);
                        $('.total_frequency').html('');
                        $('.total_frequency').html(frequency_total + ' Hz');
                    }
                }

            }

            function set_battery_details(json) {
                if (json.data.Status) {
                    $.each(json.data.Status, function(key, val) {
                        if (key == "SOC(%)") {
                            $('.SOC').text(val + '.00(%) SOC');
                        } else if (key == "SOH(%)") {
                            $('.SOH').text(val + '.00(%) SOH');
                        }

                    });
                }
            }

            function set_pv_details(json) {
                if (json.data.Power) {
                    var ac_solar_total = 0;
                    var dc_solar_total = 0;
                    $.each(json.data.Power, function(key, val) {
                        if (key == "PV_AC_O/L1(W)" || key == "PV_AC_O/L2(W)" || key == "PV_AC_O/L3(W)") {
                            ac_solar_total += parseInt(val);
                        }
                        if (key == "PV_DC(W)") {
                            dc_solar_total += parseInt(val);;
                        }
                    });
                    if (ac_solar_total > 0) {
                        $('.PV_AC_O_L2').html('');
                        $('.PV_AC_O_L2').html(ac_solar_total + '.00 W');
                    }
                    if (dc_solar_total > 0) {
                        $('.PV_DC').html('');
                        $('.PV_DC').html(dc_solar_total + '.00 W');
                    }
                }
            }

            function set_alarms_state_details(json) {
                $('.alert-alarms').addClass('d-none');
                if (json.data["Battery Alarms"]) {
                    if (json.data["Battery Alarms"]["BMS_BAT_CH"] == 1) {
                        $('.alert-alarms').removeClass('d-none');
                    }
                }
            }

            function tofixNo(value) {
                return Number(value).toFixed(2);
            }

            // ============= Start Chart =================//
            /*LIne-Chart */
            var ctx = document.getElementById("chartLine").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
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
                            display: false,
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
                    addDynamicGraphData();
                }

            });

            $(document).on('change', '.filter_dropdown', function() {
                var _this = $(this);
                var value = _this.val();
                if(value)
                {
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
                        url: '{{ url('get-charts-data') }}',
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
                myChart.data.datasets = [];
                myChart.data.labels = ["Sun", "Mon", "Tus", "Wed", "Thu", "Fri", "Sat"];
                myChart.update();

                setTimeout(() => {
                    $('.chart-loader').addClass('d-none');
                    $('.chart-container').removeClass('d-none');

                    var html = "";
                    html += "<option value=''>Select</option>";
                    $('#filter-dropdown').html('');
                    $('#filter-dropdown').html(html);

                    $('.filter-btn').removeClass('active-btn');
                    $('.filter-btn').removeClass('btn-default');
                    $('.filter-btn').addClass('btn-primary');
                    $('.chkBox').prop('checked',false);
                    $(".fc-datepicker").datepicker("setDate", new Date());
                    showHideLabels();

                }, 1000);
            });
            // Home BTN
            $(document).on('click','.chart-home-btn',function(){
                $(".fc-datepicker").datepicker("setDate", new Date());
                addDynamicGraphData();
            });




        }); // Ready
    </script>
@endsection
