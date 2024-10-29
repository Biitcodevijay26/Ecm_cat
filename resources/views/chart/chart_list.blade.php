@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<style>
     .dataTables_paginate  {
        float: right;
    }
    .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
    .table td, .table th {
        padding: .75rem 0.75rem;
    }
</style>
@endsection
@section('content')
<?php
 $company_login_id = session()->get('company_login_id');
?>
<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $heading }}</h1>
                    <ol class="breadcrumb">
                        <?php if ($company_login_id) : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        @can('isAdmin')
                        <li class="breadcrumb-item"><a href="{{ url('/company') }}">Company</a></li>
                        {{-- <li class="breadcrumb-item"><a href="{{ url('/charts-list/'.$company_id) }}">Charts List</a></li> --}}
                        @endcan
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                @can('ChartAdd')
                <div class="ms-auto pageheader-btn">
                    <?php if ($company_login_id) : ?>
                    <a href="{{ url('/company/'.$company_login_id.'/add-chart') }}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Create Chart
                    </a>
                    <?php else : ?>
                    @can('isAdmin')
                    <a href="{{url('/add-chart/'.$company_id)}}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Create Chart
                    </a>
                    @endcan

                    @can('isUser')
                    <a href="{{url('add-chart')}}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Create Chart
                    </a>
                    @endcan
                    <?php endif; ?>

                </div>
                @endcan
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">{{ $heading }} Info</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">Title</th>
                                            <th class="wd-15p border-bottom-0">Chart Type</th>
                                            <th class="wd-15p border-bottom-0">Status </th>
                                            <th class="wd-15p border-bottom-0">Created At </th>
                                            <th class="wd-25p border-bottom-0">Action</th>
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
            <!-- ROW-1 CLOSED -->
        </div>
         <!-- CONTAINER END -->
    </div>
</div>

@endsection
@section('page_level_js')

<!-- Custom Jquery Validation -->
<script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>
<script>
     $(document).ready(function() {

        var tableRx = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-charts-list') }}",
                data: function (d) {
                    // d.seacrh_name = $('input[name=seacrh_name]').val();
                    // d.seacrh_company_id = $('select[name=company_id]').val();
                    d.company_id = '{{$company_id}}';

                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[4, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title',orderable: true, searchable: false },
                {data: 'chart_type', name: 'chart_type',orderable: false, searchable: false },
                {data: 'status', name: 'status',orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });




        $(document).on('click', '.activeInactiveByAdmin', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

                $.ajax({
                    url: '{{url("active-inactive-users")}}',
                    type: "POST",
                    data:  {id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        tableRx.ajax.reload( null, false );
                    }
                });
        });





    });
</script>

@endsection
