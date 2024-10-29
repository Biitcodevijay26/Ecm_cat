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
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    <a href="{{ url('/add-cluster') }}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                                <i class="fe fe-plus"></i>
                        </span> Create Group
                    </a>
                    <a href="{{ url('/add-device') }}" class="btn btn-success btn-icon text-white">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Create a POWRBANK
                    </a>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <!-- ROW OPEN -->
            <div class="row row-cards">
                <div class="col-lg-12 col-xl-12">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="">
                        <div class="input-group-text btn btn-primary">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="card mt-5 users store">
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap">
                                <tr>
                                    <td>
                                        <div class="activity-img brround bg-info-transparent text-info collaps-btn" data-bs-toggle="collapse" data-bs-target="#r1"><i class="fa fa-arrow-right fs-20"></i></div>
                                    </td>

                                    <td>
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star ">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse accordion-collapse" id="r1">
                                    <td colspan="2">
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="activity-img brround bg-info-transparent text-info collaps-btn" data-bs-toggle="collapse" data-bs-target="#r2"><i class="fa fa-arrow-right fs-20"></i></div>
                                    </td>

                                    <td>
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star ">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse accordion-collapse" id="r2">
                                    <td colspan="2">
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="activity-img brround bg-info-transparent text-info collaps-btn" data-bs-toggle="collapse" data-bs-target="#r3"><i class="fa fa-arrow-right fs-20"></i></div>
                                    </td>

                                    <td>
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star ">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse accordion-collapse" id="r3">
                                    <td colspan="2">
                                        <p class="modal-number"> S1909-00004 </p>
                                        <p class="modal-number"> POWERBANK 30-60/208 </p>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                        <div class="rating-stars block my-rating search-star mb-2">
                                            <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="kw-number-1"> 000.00 KWh </p>
                                        <p class="kw-number-2">  000.00 KW </p>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                    <td class="text-end text-primary d-none d-md-table-cell text-nowrap">
                                        <div class="rating-stars block my-rating search-star">
                                            <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        <ul class="pagination mb-5 float-end">
                            <li class="page-item page-prev disabled">
                                <a class="page-link" href="javascript:void(0);" tabindex="-1">Prev</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                            <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
                            <li class="page-item page-next">
                                <a class="page-link" href="javascript:void(0);">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- ROW CLOSE -->

            <!-- ROW-1 OPEN -->
            <div class="row ">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap">
                                <div class="row">

                                    <tr>
                                        <td>
                                            <div class="activity-img bg-info-transparent text-info collaps-btn-1" data-bs-toggle="collapse" data-bs-target="#r11"><i class="ion-arrow-right-b fs-30 pb-0"></i></div>
                                        </td>

                                        <td class="custom-border-left">
                                            <p class="modal-number"> S1909-00004 </p>
                                            <p class="modal-number"> POWERBANK 30-60/208 </p>
                                        </td>

                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star ">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <p class="kw-number-1"> 000.00 KWh </p>
                                            <p class="kw-number-2">  000.00 KW </p>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                    </tr>
                                </div>
                                <div class="row">
                                    <tr class="collapse accordion-collapse" id="r11">
                                        <td colspan="2">
                                            <p class="modal-number"> S1909-00004 </p>
                                            <p class="modal-number"> POWERBANK 30-60/208 </p>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <p class="kw-number-1"> 000.00 KWh </p>
                                            <p class="kw-number-2">  000.00 KW </p>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                    </tr>
                                </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- COL-END -->

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap">
                                <div class="row">

                                    <tr>
                                        <td>
                                            <div class="activity-img bg-info-transparent text-info collaps-btn-1" data-bs-toggle="collapse" data-bs-target="#r12"><i class="ion-arrow-right-b fs-30 pb-0"></i></div>
                                        </td>

                                        <td class="custom-border-left">
                                            <p class="modal-number"> S1909-00004 </p>
                                            <p class="modal-number"> POWERBANK 30-60/208 </p>
                                        </td>

                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star ">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <p class="kw-number-1"> 000.00 KWh </p>
                                            <p class="kw-number-2">  000.00 KW </p>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                    </tr>
                                </div>
                                <div class="row">
                                    <tr class="collapse accordion-collapse" id="r12">
                                        <td colspan="2">
                                            <p class="modal-number"> S1909-00004 </p>
                                            <p class="modal-number"> POWERBANK 30-60/208 </p>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/load.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                            <div class="rating-stars block my-rating search-star mb-2">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="custom-border-left">
                                            <p class="kw-number-1"> 000.00 KWh </p>
                                            <p class="kw-number-2">  000.00 KW </p>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/tower.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/energy.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                        <td class="text-end text-primary d-none d-md-table-cell text-nowrap custom-border-left">
                                            <div class="rating-stars block my-rating search-star">
                                                <img src="{{ url('theme-asset/images/charging.png') }}" alt="solar-panel">
                                            </div>
                                        </td>
                                    </tr>
                                </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- COL-END -->


                <div>
                    <ul class="pagination mb-5 float-end">
                        <li class="page-item page-prev disabled">
                            <a class="page-link" href="javascript:void(0);" tabindex="-1">Prev</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                        <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
                        <li class="page-item page-next">
                            <a class="page-link" href="javascript:void(0);">Next</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- ROW-1 CLOSE -->

            <!-- ROW-2 Start -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-border-primary">
                        <div class="card-header bg-primary br-tr-4 br-tl-4 ">
                            <h4 class="card-title-overview-page text-white"> S1909-00004 </h4>
                            <div class="card-options">
                                <a href="javascript:void(0);" class="card-options-collapse text-white" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="card card-border-warning">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                    <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="img" class="card-img-absolute">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-warning mb-0">AC Solar</h5>
                                                    <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                    <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="card card-border-warning">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                    <img src="{{ url('theme-asset/images/tower.png') }}" alt="img" class="card-img-absolute">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-black mb-0">Grid</h5>
                                                    <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                    <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row mt-4">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="card card-border-warning">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                    <img src="{{ url('theme-asset/images/load.png') }}" alt="img" class="card-img-absolute">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-success mb-0">Load</h5>
                                                    <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                    <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="card card-border-warning">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                    <img src="{{ url('theme-asset/images/charging.png') }}" alt="img" class="card-img-absolute">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-4">
                                                    <h5 class="card-kw-number-1 custom-color-primary mb-0">Generator</h5>
                                                    <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                    <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-status bg-success br-tr-7 br-tl-7"></div>
                        <div class="card-header">
                            <h4 class="card-title-overview-page"> S1909-00004 </h4>
                            <div class="card-options">
                                <a href="javascript:void(0);" class="card-options-collapse" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                <img src="{{ url('theme-asset/images/solar-panel.png') }}" alt="img" class="card-img-absolute">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-4">
                                                <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                <img src="{{ url('theme-asset/images/tower.png') }}" alt="img" class="card-img-absolute">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-4">
                                                <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row mt-4">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                <img src="{{ url('theme-asset/images/load.png') }}" alt="img" class="card-img-absolute">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-4">
                                                <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="circle-icon text-center align-self-center box-primary-shadow">
                                                <img src="{{ url('theme-asset/images/charging.png') }}" alt="img" class="card-img-absolute">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-4">
                                                <h3 class="card-kw-number-1 mb-0"> 000.00 KWh </h3>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                                <h5 class="card-kw-number-2 mb-0">  000.00 KW </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- ROW-2 Close -->


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
        $(".collaps-btn").click(function () {
            $(this).find('i').toggleClass("fa-arrow-right fa-arrow-down");
        });
        $(".collaps-btn-1").click(function () {
            $(this).find('i').toggleClass("ion-arrow-right-b ion-arrow-down-b");
        });
    });
</script>

@endsection
