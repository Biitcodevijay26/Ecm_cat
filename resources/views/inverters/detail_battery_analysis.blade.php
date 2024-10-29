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
        .bat_an_per {
            top: 35%;
            left: 40%;
        }

        @media (min-width: 576px) { 
            .bat_an_per {
                left: 20%;
            }
        }
        @media (min-width: 768px) {
            .bat_an_per {
                left: 23%;
            }
        }
        @media (min-width: 992px) {
            .bat_an_per {
                left: 32%;
            }
        }
        @media (min-width: 1200px) {
            .bat_an_per {
                left: 36%;
            }
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
                        </p>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12">
                                    <h5>{{ $inverter->user->name ?? '' }} 
                                        <small>&nbsp; | &nbsp; Control Card SN :
                                            {{ $inverter->control_card_no ?? '' }}</small>
                                        <small>&nbsp; | &nbsp; Inverter SN :
                                            {{ $inverter->serial_no ?? '' }}</small>
                                        <small>&nbsp; | &nbsp; Site Name :
                                            {{ $inverter->site_name ?? '' }}</small>
                                    </h5>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs-new2">
                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#Home-new2">Battery Information</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#HistoryRecord">History Record</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#BatteryAlarm">Battery Alarm</a></li> --}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="Home-new2">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row col-md-12">
                                         <div class="col-md-2">
                                            <h5>Battery Information</h5>
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
                                         <div class="col-md-4">
                                        </div>
                                    </div>
                                </div>
                                <div class="body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <canvas id="batteryStatus"></canvas>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card overflowhidden">
                                                <div class="body top_counter bg-black">
                                                   <div class="content text-light" style="    height: 20px;">
                                                      <h6>Battery Information</h6>
                                                   </div>
                                                </div>
                                                <div class="body">
                                                    <div class="container">
                                                        <div class="row position-relative">
                                                            <div class="col"> 
                                                                <lottie-player src="{{ asset('/assets/lotti/2/Battery-Animation.json') }}"
                                                                    class="mr-auto" background="#1c222c" speed="1" id="batteryAnimation"
                                                                    style="" loop autoplay>
                                                                </lottie-player>
                                                            </div>
                                                          <div class="col position-absolute bat_an_per"">  
                                                            <span style="color: black;font-size: 20px;">
                                                                {{ number_format((float)$battery_details['data']['bat_soc']['value'], 2, '.', '') ?? ''}} {{ $battery_details['data']['bat_soc']['unit'] ?? ''}}
                                                            </span>
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="body">
                                                    
                                                   <div class="list-group list-widget" style="position: initial;">
                                                      {{-- <a href="javascript:void(0);" class="list-group-item">
                                                      <span class="badge badge-success">-</span>
                                                      <i class="fa fa-list text-muted"></i>Type</a> --}}
        
                                                      <a href="javascript:void(0);" class="list-group-item">
                                                      <span class="badge badge-success">{{ number_format((float)$battery_details['data']['bat_Voltage']['value'], 2, '.', '') ?? ''}} {{ $battery_details['data']['bat_Voltage']['unit'] ?? ''}}</span>
                                                      <i class="fa fa-list text-muted"></i> Voltage</a>
        
                                                      <a href="javascript:void(0);" class="list-group-item">
                                                      <span class="badge badge-success">{{ number_format((float)$battery_details['data']['bat_current']['value'], 2, '.', '') ?? ''}} {{ $battery_details['data']['bat_current']['unit'] ?? ''}}</span>
                                                      <i class="fa fa-list text-muted"></i> Current</a>
        
                                                      <a href="javascript:void(0);" class="list-group-item">
                                                      <span class="badge badge-success">{{ number_format((float)$battery_details['data']['bat_power']['value'], 2, '.', '') ?? ''}} {{ $battery_details['data']['bat_power']['unit'] ?? ''}}</span>
                                                      <i class="fa fa-list text-muted"></i> Power</a>
        
                                                      {{-- <a href="javascript:void(0);" class="list-group-item">
                                                      <span class="badge badge-success"></span>
                                                      <i class="fa fa-list text-muted"></i> Temperature</a> --}}
        
                                                      <a href="javascript:void(0);" class="list-group-item">
                                                        <span class="badge badge-success">{{ number_format((float)$battery_details['data']['bat_soc']['value'], 2, '.', '') ?? ''}} {{ $battery_details['data']['bat_soc']['unit'] ?? ''}}</span>
                                                        <i class="fa fa-list text-muted"></i> Battery SoC</a>
        
                                                        <a href="javascript:void(0);" class="list-group-item">
                                                        <span class="badge badge-warning">{{($battery_details && $battery_details['created_at']) ? date("Y-m-d h:i A", strtotime($battery_details['created_at'])) : ''}}</span>
                                                        <i class="fa fa-list text-muted"></i> Update Time</a>
        
                                                   </div>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="HistoryRecord">
                            <h6>Profile</h6>
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel </p>
                        </div>
                        <div class="tab-pane" id="BatteryAlarm">
                            <h6>Contact</h6>
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica</p>
                        </div>
                    </div>
                    
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
    
        $(document).ready(function() {
            $('#startDate').datepicker({
                dateFormat: 'yy-mm-dd',
            });
            
            $('#startDate').datepicker({})
                .on('change.dp', function(e) {
                    updateBatteryStatusChartData();
                });


        });

        const ctx = document.getElementById('batteryStatus');
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
                            text: 'Voltage(V)',
                            font: {
                                size: 12,
                                weight: 'bold',
                            },
                        }
                    },
                    y1: {
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
                            text: 'Current(A)',
                            font: {
                                size: 12,
                                weight: 'bold',
                            },
                        }
                    },
                    y2: {
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
                            text: 'Power(W)',
                            font: {
                                size: 12,
                                weight: 'bold',
                            },
                        }
                    },
                    y3: {
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
                            text: 'Battery SoC(%)',
                            font: {
                                size: 12,
                                weight: 'bold',
                            },
                        }
                    }
                }
            }
        };
        const myBatteryStatus = new Chart(ctx, config);


    function updateBatteryStatusChartData() {
            var startDate = $('#startDate').val();
           // var endDate = $('#endDate').val();
            var endDate = startDate;
            $('.btnLoading').removeClass('hideMe');
            $.ajax({
                    url: '{{url("admin/get-battery-status-graph-data")}}', 
                    // url: '{{url("admin/testQry")}}', 
                    type: "POST",             
                    data:  {control_card_no:control_card_no, startDate:startDate, endDate:endDate },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },    
                success: function(data) {
                    $('.btnLoading').addClass('hideMe');
                    myBatteryStatus.data.datasets = data.datasets;
                    myBatteryStatus.options.scales.x.time.unit = data.time_type;
                    myBatteryStatus.update();

                }
            });

        
    }
    updateBatteryStatusChartData();

    function handleLegendClick(evt, item, legend) {
        //get the index of the clicked legend
        var index = item.datasetIndex;
        //toggle chosen dataset's visibility
        myBatteryStatus.data.datasets[index].hidden = !myBatteryStatus.data.datasets[index].hidden;
        //toggle the related labels' visibility
        if(index == 4){
            myBatteryStatus.options.scales.y1.display = !myBatteryStatus.options.scales.y1.display;
        }
        
        myBatteryStatus.update();
    }


    </script>
@endsection
