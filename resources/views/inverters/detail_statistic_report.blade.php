@extends('front.layout_admin.app')
{{-- <link rel="stylesheet" href="{{ url('app-assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"> --}}
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@section('page_level_css')
    <style>
        .dataTables_paginate  {
            float: right;
        }
        .dataTables_filter, .dataTables_info, .dataTables_length { display: none; }
        .table td, .table th {
            padding: .75rem 0.75rem;
        }
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
    <link rel="stylesheet" type="text/css" href="{{ url('app-assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
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
                    <div class="card">
                        <div class="card-header">
                            <div class="row col-md-12">
                                 <div class="col-md-2">
                                    <h5>{{$title_sub}}</h5>
                                 </div>
                                 
                                 <div class="col-md-2">
                                    <span><i></i>Report Type</span>
                                </div>
                                <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="streportFilter" value="daily" type="radio" checked><span><i></i>Daily Report</span></label>
                                 </div>
                                 <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="streportFilter" value="monthly" type="radio"><span><i></i>Monthly Report</span></label>
                                 </div>
                                 <div class="col-md-1 text-right pr-0">
                                    <label class="fancy-radio"><input name="streportFilter" value="yearly" type="radio"><span><i></i>Yearly Report</span></label>
                                 </div>

                                 <div class="col-md-2 text-left pl-2">
                                    <label>Time</label>
                                    <input class="form-control" name="enDaily" id="enDaily" value="{{date('Y-m-d')}}">
                                    <input class="form-control hideMe" name="enMonthly" id="enMonthly" value="{{date('Y-m')}}">
                                    <input class="form-control hideMe" name="enYearly" id="enYearly" value="{{date('Y')}}">
                                 </div>
                                
                                <div class="col-md-2 text-right">
                                    <div class="input-group mb-0" style="display: table;margin-left: auto;margin-right: auto;">
                                        <button type="button" class="btnSearch btn btn-info mr-1 mb-1" title="Click to Search"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered base-style yajra-datatable tbl1" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>PV1 Current (A)</th>
                                                    <th>PV2 Current (A)</th>
                                                    <th>PV1 Voltage (V)</th>
                                                    <th>PV2 Voltage (V)</th>
                                                    <th>PV1 Power (W)</th>
                                                    <th>PV2 Power (W)</th>
                                                    <th>AC Current R (A)</th>
                                                    <th>AC Current S (A)</th>
                                                    <th>AC Current T (A)</th>
                                                    <th>AC Voltage R (V)</th>
                                                    <th>AC Voltage S (V)</th>
                                                    <th>AC Voltage T (V)</th>
                                                    <th>AC Power(On-grid) (W)</th>
                                                    <th>EPS Power(Off-grid) (W)</th>
                                                    <th>Grid Power (W)</th>
                                                    <th>On-grid Daily Yield (kWh)</th>
                                                    <th>On-grid Total Yield (kWh)</th>
                                                    <th>Off-grid(EPS) Daily Yield (kWh)</th>
                                                    <th>Off-grid(EPS) Total Yield (kWh)</th>
                                                    <th>Created At</th>
                                                    <!-- <th width="28%">Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                        <table class="table table-striped table-bordered base-style yajra-datatable tbl2 hideMe" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Date</th>
                                                    <th>Yield(kWh)</th>
                                                    <th>Feed-in Energy(kWh)</th>
                                                    <th>Consume Energy(kWh)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                        <table class="table table-striped table-bordered base-style yajra-datatable tbl3 hideMe" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Month</th>
                                                    <th>Yield(kWh)</th>
                                                    <th>Feed-in Energy(kWh)</th>
                                                    <th>Consume Energy(kWh)</th>
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
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page_level_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    {{-- <script src="{{ url('app-assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script> --}}
    <script src="{{ url('assets/bundles/datatablescripts.bundle.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">
    const control_card_no = "{{ $inverter->control_card_no ?? '' }}";
    
        $(document).ready(function() {
            $('#enDaily').datepicker({
                dateFormat: 'yy-mm-dd',
            });

            $('#enMonthly').datepicker({
                dateFormat: 'yy-mm',
                //autoclose: true,
                changeMonth:true,
                changeYear:true,
                constrainInput: false,
                onChangeMonthYear: function(year, month) {
                    if(month < 10){
                        month = '0'+ month;
                    }
                    $('#enMonthly').val(year + '-' + month);
                    $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next").hide();
                    //$(this).datepicker('hide');
                }
            });
            $("#enMonthly").focus(function () {
                $(".ui-datepicker-calendar").hide();
            });

            $('#enYearly').datepicker({
                dateFormat: 'yy',
                autoclose: true,
                changeYear:true,
                yearRange: "-5:+0",
                constrainInput: false,
                onChangeMonthYear: function(year, month) {
                    $('#enYearly').val(year);
                    $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next").hide();
                    $(this).datepicker('hide');
                }
            });
            $("#enYearly").focus(function () {
                $(".ui-datepicker-calendar, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-month").hide();
            });

            $("input[name='streportFilter']").click(function() {
                var streportFilter = $("input[name='streportFilter']:checked").val();
                if(streportFilter == 'monthly'){
                    $('#enDaily, #enYearly').addClass('hideMe');
                    $('#enMonthly').removeClass('hideMe');
                    $('.tbl2, #DataTables_Table_1_wrapper').removeClass('hideMe');
                    $('.tbl1, .tbl3, #DataTables_Table_0_wrapper, #DataTables_Table_2_wrapper').addClass('hideMe');
                    tableRx2Draw();
                    
                } else if(streportFilter == 'daily'){
                    $('#enMonthly, #enYearly').addClass('hideMe');
                    $('#enDaily').removeClass('hideMe');
                    $('.tbl1, #DataTables_Table_0_wrapper').removeClass('hideMe');
                    $('.tbl2, .tbl3, #DataTables_Table_1_wrapper, #DataTables_Table_2_wrapper').addClass('hideMe');
                } else if(streportFilter == 'yearly'){
                    $('#enDaily, #enMonthly').addClass('hideMe');
                    $('#enYearly').removeClass('hideMe');
                    $('.tbl3, #DataTables_Table_2_wrapper').removeClass('hideMe');
                    $('.tbl1, .tbl2, #DataTables_Table_0_wrapper, #DataTables_Table_1_wrapper').addClass('hideMe');
                    tableRx3Draw();
                }
            });

            var tableRx = $('.tbl1').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                "searching": false,
                bPaginate:   false,
                ajax: {
                    url: "{{ url('admin/static-report') }}",
                    data: function (d) {
                        d.enMonthly = $('#enMonthly').val();
                        d.enDaily = $('#enDaily').val();
                        d.enYearly = $('#enYearly').val();
                        d.control_card_no = control_card_no;
                        d.streportFilter = $("input[name='streportFilter']:checked").val();
                    }
                },
                oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                aaSorting: [[5, 'desc']],
                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'data.pv1_current.value', name: 'data.pv1_current', orderable: false, searchable: false},
                    {data: 'data.pv2_current.value', name: 'data.pv2_current', orderable: false, searchable: false},
                    {data: 'data.pv1_voltage.value', name: 'data.pv1_voltage', orderable: false, searchable: false},
                    {data: 'data.pv2_voltage.value', name: 'data.pv2_voltage', orderable: false, searchable: false},
                    {data: 'data.pv1_power.value', name: 'data.pv1_power', orderable: false, searchable: false},
                    {data: 'data.pv2_power.value', name: 'data.pv2_power', orderable: false, searchable: false},
                    {data: 'inv_phase1_current', name: 'data2.inv_phase1_current', orderable: false, searchable: false},
                    {data: 'inv_phase2_current', name: 'data2.inv_phase2_current', orderable: false, searchable: false},
                    {data: 'inv_phase3_current', name: 'data2.inv_phase3_current', orderable: false, searchable: false},
                    {data: 'inv_phase1_voltage', name: 'data2.inv_phase1_voltage', orderable: false, searchable: false},
                    {data: 'inv_phase2_voltage', name: 'data2.inv_phase2_voltage', orderable: false, searchable: false},
                    {data: 'inv_phase3_voltage', name: 'data2.inv_phase3_voltage', orderable: false, searchable: false},
                    {data: 'inv_phase1_power', name: 'data2.inv_phase1_power', orderable: false, searchable: false},
                    {data: 'inv_phase2_power', name: 'data2.inv_phase2_power', orderable: false, searchable: false},
                    {data: 'inv_phase3_power', name: 'data2.inv_phase3_power', orderable: false, searchable: false},
                    {data: 'inv_energy_today', name: 'data2.inv_energy_today', orderable: false, searchable: false},
                    {data: 'inv_energy_total', name: 'data2.inv_energy_total', orderable: false, searchable: false},
                    {data: 'data.pv_energy_today.value', name: 'data.pv_energy_today', orderable: false, searchable: false},
                    {data: 'pv_energy_total', name: 'data.pv_energy_total', orderable: false, searchable: false},
                    
                    {data: 'created_at', name: 'created_at'},
                    
                ],

            });

            var tableRx2;

            function tableRx2Draw() {
                tableRx2 = $('.tbl2').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    "searching": false,
                    bPaginate:   false,
                    ajax: {
                        url: "{{ url('admin/static-report') }}",
                        data: function (d) {
                            d.enMonthly = $('#enMonthly').val();
                            d.enDaily = $('#enDaily').val();
                            d.enYearly = $('#enYearly').val();
                            d.control_card_no = control_card_no;
                            d.streportFilter = $("input[name='streportFilter']:checked").val();
                        }
                    },
                    oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                    aaSorting: [[1, 'asc']],
                    columns: [

                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'avg_battery_energy', name: 'avg_battery_energy', orderable: false, searchable: false},
                        {data: 'avg_total_feedin_power', name: 'avg_total_feedin_power', orderable: false, searchable: false},
                        {data: 'avg_total_load_consume', name: 'avg_total_load_consume', orderable: false, searchable: false},
                        
                    ],

                });
            }

            var tableRx3;

            function tableRx3Draw() {
                tableRx2 = $('.tbl3').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    "searching": false,
                    bPaginate:   false,
                    ajax: {
                        url: "{{ url('admin/static-report') }}",
                        data: function (d) {
                            d.enMonthly = $('#enMonthly').val();
                            d.enDaily = $('#enDaily').val();
                            d.enYearly = $('#enYearly').val();
                            d.control_card_no = control_card_no;
                            d.streportFilter = $("input[name='streportFilter']:checked").val();
                        }
                    },
                    oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                    aaSorting: [[1, 'asc']],
                    columns: [

                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'avg_battery_energy', name: 'avg_battery_energy', orderable: false, searchable: false},
                        {data: 'avg_total_feedin_power', name: 'avg_total_feedin_power', orderable: false, searchable: false},
                        {data: 'avg_total_load_consume', name: 'avg_total_load_consume', orderable: false, searchable: false},
                        
                    ],

                });
            }

            $(document).on('change', '#enDaily', function(e) {
                tableRx.draw();
            });

            $(document).on('click', '.btnSearch', function(e) {
                var streportFilter = $("input[name='streportFilter']:checked").val();
                if(streportFilter == 'monthly'){
                    tableRx2Draw();
                } else if(streportFilter == 'daily'){
                    tableRx.draw();
                } else if(streportFilter == 'yearly'){
                    tableRx3Draw();
                }
                
                e.preventDefault();
            });
            


        });


    </script>
@endsection
