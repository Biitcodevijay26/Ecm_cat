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
                    {{-- <a href="javascript:void(0);" class="btn btn-primary btn-icon text-white me-2">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> Add Company
                    </a> --}}
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                            <div class="page-options d-flex float-end">
                                {{-- <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name" value="" placeholder="Search Name"> --}}
                                <select class="form-select me-2" aria-label="Default select example" name="seacrh_name" id="seacrh_name">
                                    <option selected value="">Select Company</option>
                                    @if ($getCompany)
                                    @foreach ($getCompany as $company)
                                    <option value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                    @endforeach
                                     @endif
                                </select>
                                <button type="button" class="btnSearch btn btn-info pull-right me-2" title="Click to Search"><i class="fe fe-search"></i></button>
                                <a href="{{url('/channel-add')}}" class="btn bg-add-btn pull-right" title="Click to add"><i class="fe fe-plus"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>

                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">Company Name</th>
                                            {{-- <th class="wd-15p border-bottom-0">Channel</th> --}}
                                            <th class="wd-15p border-bottom-0">Channel ID</th>
                                            <th class="wd-15p border-bottom-0">Assign Count</th>
                                            {{-- <th class="wd-15p border-bottom-0">Status</th> --}}
                                            <th class="wd-15p border-bottom-0">Created At</th>
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
                url: "{{ url('get-channel') }}",
                data: function (d) {
                    d.seacrh_name = $('select[name=seacrh_name]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[1, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'company_name', name: 'company_name',orderable: true, searchable: false},
                // {data: 'number_of_channel', name: 'number_of_channel',orderable: true, searchable: false },
                {data: 'channel_ids', name: 'channel_ids',orderable: false, searchable: false },
                {data: 'assign_counts', name: 'assign_counts',orderable: false, searchable: false },
                // {data: 'status', name: 'status',orderable: true, searchable: false},
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        // $(document).on('click', '.activeInactiveCompanyAgent', function() {
        //     var _this = $(this);
        //     var id = _this.attr('data-id');
        //     $('label.error').remove();
        //     $('label.success_msg').remove();
        //     $('.alert-outline-success').remove();

        //         $.ajax({
        //             url: '{{url("active-company-agent")}}',
        //             type: "POST",
        //             data:  {id:id},
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(data) {
        //                 tableRx.ajax.reload( null, false );
        //             }
        //         });
        // });

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

        $(document).on('click', '.deleteChannel', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            var result = confirm("Are you sure you want to delete this channel?");
            if (result) {
                if(id){
                    $.ajax({
                        url: '{{url("remove-channel")}}',
                        type: "POST",
                        data:  {id:id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            try {
                                data = JSON.parse(data);
                            } catch(e){}
                            if(data.status == 'true')
                            {
                                $.growl.notice({
                                    title: "Success",
                                    message: "Succesfully deleted."
                                });
                                tableRx.ajax.reload( null, false );

                            } else {
                                $.growl.error({
                                    message: data.response_msg
                                });
                            }
                        }
                    });
                }
            }

        });

    });
</script>

@endsection
