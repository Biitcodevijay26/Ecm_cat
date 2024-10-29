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
                        <li class="breadcrumb-item"><a href="{{ url('/channel') }}">{{$module}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
                <div class="ms-auto pageheader-btn">
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- ROW-1 OPEN -->
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 col-6">
                    <div class="card bg-primary img-card box-primary-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font"><?php echo (isset($data->company->company_name) && $data->company->company_name ? $data->company->company_name : ''); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 col-6">
                    <div class="card bg-primary img-card box-primary-shadow">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="text-white">
                                    <h2 class="mb-0 number-font">Channel ID : <?php echo (isset($data->channels_basic_added_ids) && $data->channels_basic_added_ids ? $data->channels_basic_added_ids : '0'); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                            <div class="page-options d-flex float-end">
                                <input type="text" name="seacrh_name" class="form-control me-2" id="seacrh_name" value="" placeholder="Search Device">
                                {{-- <select class="form-select me-2" aria-label="Default select example" name="seacrh_name" id="seacrh_name">
                                    <option selected value="">Select Company</option>
                                    @if ($getCompany)
                                    @foreach ($getCompany as $company)
                                    <option value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                    @endforeach
                                     @endif
                                </select> --}}
                                <button type="button" class="btnSearch btn btn-info pull-right me-2" title="Click to Search"><i class="fe fe-search"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>Assign</th>
                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">POWRBANK Name</th>
                                            <th class="wd-15p border-bottom-0">MACID</th>
                                            <th class="wd-15p border-bottom-0">Created At</th>
                                            {{-- <th class="wd-25p border-bottom-0">Action</th> --}}
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
                url: "{{ url('get-channel-assign-list') }}",
                data: function (d) {
                    // d.seacrh_name = $('select[name=seacrh_name]').val();
                    d.seacrh_name = $('input[name=seacrh_name]').val();
                    d.company_id  = "{{$company_id ?? ''}}";
                    d.channel_id  = "{{$channel_id ?? ''}}";
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[4, 'desc']],
            columns: [

                {data: 'assign_chkbox', name: 'assign_chkbox', orderable: false, searchable: false,class:'text-center'},
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name',orderable: true, searchable: false},
                {data: 'macid', name: 'macid',orderable: true, searchable: false },
                {data: 'created_at', name: 'created_at',orderable: true, searchable: false},
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
            ],

        });

        $(document).on('click', '.btnSearch', function(e) {
            tableRx.draw();
            e.preventDefault();
        });

        $(document).on('click','.channel_chk_box',function(){
            var _this      = $(this);
            var device_id  = _this.val();
            var channel_id = "{{$channel_id}}";
            if(device_id){
                $.ajax({
                    url: '{{url("update-device-channel")}}',
                    type: "POST",
                    data:  {device_id:device_id,channel_id:channel_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == "true")
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "Successfully update Channel."
                            });

                        } else {
                            $.growl.error({
                                message: "Cannot Update Channel..!"
                            });
                        }
                    }
                });
            } else {
                $.growl.error({
                    message: "something went wrong please try again..!"
                });
            }
        });

    });
</script>

@endsection
