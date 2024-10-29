@extends('front.layout_admin.app')

@section('page_level_css')
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<style>
     .dataTables_paginate  {
        float: right;
    }
    .dataTables_filter, .dataTables_info22, .dataTables_length { display: none; }
    .table td, .table th {
        padding: .75rem 0.75rem;
    }
    .chart-card-height { height: 410px;}
    .badge-custom{
        position: absolute;
        right: 8px;
        width: 65px;
    }
    .apexcharts-menu-icon{
        display: none;
    }
    .ch{
        min-height: 340px;
    }
    .alert-pl{
        padding-left: 30px;
    }
</style>
@endsection
@section('content')

<!--app-content open-->
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Reporting</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reporting</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- Warning Message Start -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-wrap mb-4">
                                <!--<iframe title="ECM_Powr2_New_091223" width="1300" height="836" src="https://app.powerbi.com/view?r=eyJrIjoiMTJiMzU1MTYtM2ZlYS00MzIxLTg1NGQtZWJjZjY5YTdlMzE0IiwidCI6ImM1M2U4Mjk2LWI2MzMtNDExNy1hZWMyLWZjZTA2Mjk0YjlmYiIsImMiOjF9" frameborder="0" allowFullScreen="true"></iframe>-->
                                <iframe title="ECM_Powr2_New_091223" width="1300" height="836" src="https://app.powerbi.com/view?r=eyJrIjoiNWQyYzJhMGItZTFlYi00NmQ4LWFmMDAtZWUxYTRlMmNhMDQ0IiwidCI6ImM1M2U4Mjk2LWI2MzMtNDExNy1hZWMyLWZjZTA2Mjk0YjlmYiIsImMiOjF9" frameborder="0" allowFullScreen="true"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Warning Message End -->
        </div>
    </div>
        <!-- CONTAINER END -->
    </div>
</div>
<!--app-content end-->

@endsection

