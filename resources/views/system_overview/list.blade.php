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
    .divice_list .device-card {
        min-height: 120px;
    }
    .divice_list_with_cluster .device-card {
        min-height: 120px;
    }

</style>
<?php
    $company_login_id = session()->get('company_login_id');
?>
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
                        <?php if ($company_login_id) : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <?php else : ?>
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            @can('isAdmin')
                            <li class="breadcrumb-item"><a href="{{ url('/company') }}">Company</a></li>
                            @endcan
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                    @can('isUser')
                    @can('ClusterManagementAdd')
                    <a href="{{ url('/add-cluster') }}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                                <i class="fe fe-plus"></i>
                        </span> Create Group
                    </a>
                    @endcan
                    @can('DeviceManagementAdd')
                    <a href="{{ url('/add-device') }}" class="btn btn-success btn-icon text-white">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add POWRBANK
                    </a>
                    @endcan
                    @endcan
                    <?php if ($company_login_id): ?>
                        <a href="{{ url('/company/'.$company_login_id.'/add-cluster') }}" class="btn btn-primary btn-icon text-white me-2">
                            <span>
                                    <i class="fe fe-plus"></i>
                            </span> Create Group
                        </a>
                        <a href="{{ url('/company/'.$company_login_id.'/add-device') }}" class="btn btn-success btn-icon text-white">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> Add POWRBANK
                        </a>
                    <?php endif; ?>

                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <div class="row mb-2 d-none">
                <div class="col-lg-12 col-xl-12">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search_device_name" id="search_device_name" placeholder="Search POWRBANK name">
                        <div class="input-group-text btn btn-primary btn_search">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 Start Device list without cluster -->
            <div class="card device-list-card d-none">
                <div class="card-header">
                    <h1 class="page-title">POWRBANK</h1>
                </div>
                <div class="card-body">
                    <div class="row divice_list">

                    </div>
                </div>
            </div>
            <!-- ROW-1 Close -->

            <!-- ROW-2 Start POWRBANK list with cluster -->
            <div class="card mt-4 cluster-list-card d-none">
                <div class="card-header">
                    <h1 class="page-title">Groups</h1>
                </div>
                <div class="card-body">
                    <div class="row divice_list_with_cluster">

                    </div>
                </div>
            </div>
            <!-- ROW-2 Close -->

            <!-- ROW-3 No POWRBANK and Cluster Found -->
            <div class="card empty_page d-none">
                <div class="example">
                    <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                        <strong>No POWRBANK Found..!</strong>
                        <hr class="message-inner-separator">
                        <p>There is no POWRBANK register with this company. you can add new POWRBANK.</p>
                    </div>
                </div>
            </div>
            {{-- <div class="row mt-4 empty_page d-none bg-color-black">
                <div class="container">
                    <div class="row text-center mx-auto mt-5">
                        <div class="col-lg-8 col-sm-12 center-block align-items-center construction  ">
                            <div class="text-white">
                                <div class="card-body mt-6 mt-sm-0">
                                    <h1 class="display-2 mb-0 fw-semibold">No POWRBANK Found..!</h1>
                                    <div id="launch_date"></div>
                                    <div class="mt-5">
                                        <a class="btn btn-warning" href="{{url('/company')}}">
                                            <span class="btn-inner--icon"><i class="fa fa-long-arrow-left"></i> Back to Company</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- ROW-3 Close -->


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
        $('.select2').select2({});
        var company_id = '{{ $company_id ?? ''}}';
        $(".collaps-btn").click(function () {
            $(this).find('i').toggleClass("fa-arrow-right fa-arrow-down");
        });
        $(".collaps-btn-1").click(function () {
            $(this).find('i').toggleClass("ion-arrow-right-b ion-arrow-down-b");
        });

        // Render Device in Dynamic
        var offset = 0;
        var processing;
        renderDeviceList();
        $(window).scroll(function(e) {
            if (processing)
                return false;

            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 2200) {
                renderDeviceList();
            }
        });

        // With Cluster infinite pagination
        var offset_with_cluster = 0;
        var processing_with_cluster;
        renderDeviceListWithCluster();
        $(window).scroll(function(e) {
            if (processing_with_cluster)
                return false;

            if ($(window).scrollTop() + $(window).height() >= ($(document).height() - 20)) {
                renderDeviceListWithCluster();
            }
        });

        $(document).on('click','.btn_search',function(){
            var search_name = $('#search_device_name').val();
            // if(search_name)
            // {
                offset = 0;
                var search_name = $('#search_device_name').val();
                $.ajax({
                    url: '{{ url("get-tmp-device-list") }}',
                    type: 'POST',
                    data: {
                        'offset' : offset,
                        'search_name' : search_name,
                        'company_id' : company_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        if (resp) {
                            try {
                                resp = JSON.parse(resp)
                            } catch (e) {}
                            offset = resp.offset;

                            $('.divice_list').html('');
                            $('.divice_list').html(resp.html);
                            if (resp.is_data) {
                                processing = false;
                            }
                            isClassfound()
                        }
                    }
                })
            // }
        });

        // Without Cluster
        function renderDeviceList() {
            processing = true;
            var search_name = $('#search_device_name').val();
            $.ajax({
                url: '{{ url("get-tmp-device-list") }}',
                type: 'POST',
                data: {
                    'offset' : offset,
                    'search_name' : search_name,
                    'company_id' : company_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(resp) {
                    if (resp) {
                        try {
                            resp = JSON.parse(resp)
                        } catch (e) {}
                        offset = resp.offset;
                        if(resp.html)
                        {
                            $('.device-list-card').removeClass('d-none');
                        }
                        $('.divice_list').append(resp.html);
                        if (resp.is_data) {
                            processing = false;
                        }
                        isClassfound()
                    }
                }
            })
        }

        // With Cluster
        function renderDeviceListWithCluster() {
            processing_with_cluster = true;
            var search_name = $('#search_device_name').val();
            $.ajax({
                url: '{{ url("get-tmp-device-list-cluster-wise") }}',
                type: 'POST',
                data: {
                    'offset' : offset_with_cluster,
                    'search_name' : search_name,
                    'company_id' : company_id
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(resp) {
                    if (resp) {
                        try {
                            resp = JSON.parse(resp)
                        } catch (e) {}
                        offset_with_cluster = resp.offset;
                        if(resp.html)
                        {
                            $('.cluster-list-card').removeClass('d-none');
                        }
                        $('.divice_list_with_cluster').append(resp.html);
                        if (resp.is_data) {
                            processing_with_cluster = false;
                        }
                        isClassfound()
                    }
                }
            })
        }


        function isClassfound(){
            setTimeout(() => {
                $('.empty_page').addClass('d-none');
                if ($(".cluster_found").length <= 0){
                    $('.cluster-error').remove();
                }
                if($(".device_found").length <= 0) {
                    $('.device-error').remove();
                }

                if($(".cluster_found").length <= 0 && $(".device_found").length <= 0)
                {
                    $('.empty_page').removeClass('d-none');
                }
            }, 1000);

        }

        $(document).on('change','.assign_to_group',function(){
            var _this = $(this);
            var cluster_id = _this.val();
            var device_id = _this.attr('data-device-id');
            if(cluster_id && device_id){
                $.ajax({
                    url: '{{ url("device-assign-to-group") }}',
                    type: 'POST',
                    data: {
                        'cluster_id' : cluster_id,
                        'device_id' : device_id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        if (resp) {
                            try {
                                resp = JSON.parse(resp)
                            } catch (e) {}
                            if (resp.status == 'true') {
                                $.growl.notice({
                                    title: "Success",
                                    message: "Succesfully Assign To Group."
                                });
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                $.growl.error({
                                    title: "Error",
                                    message: "Something went wrong.",
                                });
                            }
                        }
                    }
                })
            }
        });

    }); // End Ready
</script>

@endsection
