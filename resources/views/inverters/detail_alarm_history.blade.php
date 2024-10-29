@extends('front.layout_admin.app')
{{-- <link rel="stylesheet" href="{{ url('app-assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"> --}}
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@section('page_level_css')
    <style>
        .dataTables_paginate  {
            float: right;
        }
        .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
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
                                </div>
                                 <div class="col-md-2 pt-4 text-right">
                                 </div>
                                 <div class="col-md-2">
                                     <label>From Date</label>
                                     <input class="datepicker form-control" name="start" id="startDate" value="{{date('Y-m-d')}}">
                                 </div>
                                 <div class="col-md-2">
                                    <label>End Date</label>
                                    <input class="datepicker form-control" name="end" id="endDate" value="{{date('Y-m-d')}}">
                                </div>
                                <div class="col-md-2 text-right">
                                    <label for="username">&nbsp;&nbsp;</label>
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
                                        <table class="table table-striped table-bordered base-style yajra-datatable" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Control Card SN</th>
                                                    <th>Warning</th>
                                                    <th>Warning Code</th>
                                                    <th>Warning Text</th>
                                                    <th>Created At</th>
                                                    <!-- <th width="28%">Action</th> -->
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
            $('#startDate').datepicker({
                dateFormat: 'yy-mm-dd',
            });

            $('#startDate, #endDate').datepicker({
               // showOn: "both",
                beforeShow: customRange,
                dateFormat: "yy-mm-dd",
            });
            function customRange(input) {
                if (input.id == 'endDate') {
                    var minDate = new Date($('#startDate').val());
                    minDate.setDate(minDate.getDate() + 1)
                    return {
                        minDate: minDate
                    };
                }
                return {}
            }

            var tableRx = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                "searching": false,
                ajax: {
                    url: "{{ url('admin/alarm') }}",
                    data: function (d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.control_card_no = control_card_no;
                    }
                },
                oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
                aaSorting: [[5, 'desc']],
                columns: [

                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'control_card_sn', name: 'control_card_sn'},
                    {data: 'data.alarm_warning', name: 'data.alarm_warning'},
                    {data: 'warning_code', name: 'warning_code'},
                    {data: 'warning_Text', name: 'warning_Text'},
                    {data: 'created_at', name: 'created_at'},
                    
                ],

            });

            $(document).on('click', '.btnSearch', function(e) {
                tableRx.draw();
                e.preventDefault();
            });
            


        });


    </script>
@endsection
