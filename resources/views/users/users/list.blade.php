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
                        <?php if($company_login_id) : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/company/'.$company_login_id.'/dashboard') }}">Dashboard</a></li>
                        <?php else : ?>
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                @can('UsereManagementAdd')
                <?php if($company_login_id) : ?>
                        <a href="{{url('/company/'.$company_login_id.'/user-add')}}" class="btn btn-primary btn-icon text-white me-2">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> Add User
                        </a>
                    <?php else : ?>
                <a href="{{url('user-add')}}" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add User
                    </a>
                <?php endif; ?>
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
                            <div class="card-options">
                                <div class="row">
                                    @can('isAdmin')
                                    <div class="col-xl-5 col-md-5 col-sm-12">
                                        <div class="form-group form-group-register error-company_id">

                                            <select class="form-select select2 form-control" name="company_id" id="company_id">
                                                <option  value="">Select Company</option>
                                                @if ($companies)
                                                @foreach ($companies as $company)
                                                <?php
                                                    $selected = "";
                                                    if(isset($data->company_id) && $company->id == $data->company_id){
                                                        $selected = "selected";
                                                    }
                                                ?>
                                                <option <?= $selected ?> value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @endcan
                                    @can('isAdmin')
                                    <div class="col-xl-5 col-md-5 col-sm-12">
                                    @else
                                    <div class="col-xl-10 col-md-10 col-sm-12">
                                    @endcan
                                        <div class="form-group">
                                            <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name" value="" placeholder="Search Name">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-sm-12">
                                        <button type="button" class="btnSearch btn btn-info pull-right" title="Click to Search"><i class="fe fe-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-15p border-bottom-0">Email</th>
                                            <th class="wd-15p border-bottom-0">Company</th>
                                            <th class="wd-15p border-bottom-0">Country</th>
                                            <th class="wd-15p border-bottom-0">City</th>
                                            <th class="wd-15p border-bottom-0">Email Verification</th>
                                            <th class="wd-15p border-bottom-0">Is Active </th>
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
        $('.select2').select2({});
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        var tableRx = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('get-users-list') }}",
                data: function (d) {
                    d.seacrh_name = $('input[name=seacrh_name]').val();
                    d.seacrh_company_id = $('select[name=company_id]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[8, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name',orderable: true, searchable: false },
                {data: 'email', name: 'email',orderable: false, searchable: false },
                {data: 'company', name: 'company',orderable: false, searchable: false },
                {data: 'country', name: 'country',orderable: false, searchable: false },
                {data: 'city_name', name: 'city_name',orderable: false, searchable: false },
                {data: 'status', name: 'status',orderable: false, searchable: false},
                {data: 'is_active', name: 'is_active',orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        $(document).on('click', '.editCountries', function() {
            var _this        = $(this);
            var id           = _this.attr('data-id');
            var name         = _this.attr('data-countries-name');
            var code         = _this.attr('data-countries-code');
            var dial_code    = _this.attr('data-countries-dialcode');
            var status       = _this.attr('data-status');
            $('#country_id').val(id);
            $('#name').val(name);
            $('#code').val(code);
            $('#dial_code').val(dial_code);
            if(status == 1){
                $(".chk-status").prop('checked', true);
            }else{
                $(".chk-status").prop('checked', false);
            }

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

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });



    });
</script>

@endsection
