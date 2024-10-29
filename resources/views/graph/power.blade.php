@extends('front.layout_admin.app')
<link rel="stylesheet" href="{{ url('app-assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@section('page_level_css')
<style>

</style>
@endsection

@section('content')

<div id="main-content">
    <div class="container-fluid">
    	<div class="block-header">
           <div class="row">
               <div class="col-lg-5 col-md-8 col-sm-12">
                   <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i
                               class="fa fa-arrow-left"></i></a> {{ $title }}</h2>
                   <ul class="breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                       <li class="breadcrumb-item active">{{ $title_sub }}</li>
                   </ul>
               </div>
               <div class="col-lg-7 col-md-4 col-sm-12 text-right">
               </div>
           </div>
       </div>
       <div class="row">
       	
       <div class="col-xl-12 col-lg-12 col-md-12 col-12">
           <div class="card">
               <div class="card-header">
                   <div class="row col-md-12">
                        <div class="col-md-2">
                            Control Card SN : <span class="text-info"> {{$inverter->control_card_no ?? ''}} </span>
                        </div>
                        <div class="col-md-2">
                            Inverter SN : <span class="text-info"> {{$inverter->serial_no ?? ''}} </span>
                        </div>
                        <div class="col-md-2 pt-4 text-right">
                            <button type="button" class="btn btn-primary btnLoading cnprimary"
                                disabled="disabled"><i class="fa fa-spinner fa-spin"></i> <span>Loading...</span></button>
                        </div>
                        <div class="col-md-6">
                            <label>Date</label>
                            <div class="input-daterange input-group" data-provide="datepicker">
                                <input type="text" class="input-sm form-control" name="start" id="startDate" value="{{date('Y-m-d')}}">
                                {{-- <span class="input-group-addon">to</span> --}}
                                <input type="text" class="input-sm form-control hideMe" name="end" id="endDate" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                   </div>
               </div>
               <div class="card-content collapse show">
                   <div class="card-body card-dashboard" style="">
                        <canvas id="powerChart"></canvas>
                   </div>
               </div>
           </div>
       </div>
       </div>
    </div>
</div>

@endsection

@section('page_level_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-moment/1.0.1/chartjs-adapter-moment.min.js"></script>
<script src="{{ url('app-assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
const control_card_no = "{{ $inverter->control_card_no ?? '' }}";
    $(document).ready(function() {
        $('.input-daterange').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
        
        $('#startDate').datepicker({})
            .on('change.dp', function(e) {
                // `e` here contains the extra attributes
                console.log('startDate', $('#startDate').val());
                updateChartData();
            });
        $('#endDate').datepicker({})
            .on('change.dp', function(e) {
                // `e` here contains the extra attributes
                console.log('endDate', $('#endDate').val());
                updateChartData();
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
            }
        },
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'hour'
                },
                ticks: {
                    color: '#f5f2f2',
                    // stepSize: 15 // interval
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
        var endDate = $('#endDate').val();
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
                console.log('data', data);
                /*myPowerChart.data.datasets = [{
                    label: 'PV Power',
                    data: [12, 19, 3, 5, 2, 30],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: ['rgba(255,177,193,0.2)'],
                    fill: true,
                    tension: 0.5
                },
                {
                    label: 'AC Power',
                    data: [11, 5, 3, 8, 2, 32],
                    borderColor: 'rgb(255, 204, 0)',
                    backgroundColor: ['rgba(255, 245, 204,0.2)'],
                    fill: true,
                    tension: 0.5

                }];*/
                myPowerChart.data.datasets = data.datasets;
                myPowerChart.options.scales.x.time.unit = data.time_type;
                myPowerChart.update();

            }
        });

    
  }
    // setInterval(function(){
    //     updateChartData();
    // }, 5000);
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




</script>
@endsection
