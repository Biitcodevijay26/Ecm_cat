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
$agentsAllowed   = $dwsData['agentsAllowed'] ?? 0;
$agentsInstalled = $dwsData['agentsInstalled'] ?? 0;
$avaible_agent   = $agentsAllowed - $agentsInstalled;
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
                                <select class="form-select me-2" aria-label="Default select example" name="seacrh_name" id="seacrh_name">
                                    <option selected value="">Select Company</option>
                                    @if ($getCompany)
                                    @foreach ($getCompany as $company)
                                    <option value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                    @endforeach
                                        @endif
                                </select>
                                <button type="button" class="btnSearch btn btn-info pull-right me-2" title="Click to Search"><i class="fe fe-search"></i></button>
                                <?php if($avaible_agent > 0) : ?>
                                <a href="{{url('/agent-add')}}" class="btn bg-add-btn pull-right" title="Click to add"><i class="fe fe-plus"></i></a>
                                <?php else : ?>
                                <a href="javascript:void(0);" class="btn bg-add-btn pull-right add_button" title="Click to add"><i class="fe fe-plus"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="row w-100 d-flex justify-content-center">

                                @if($dwsData)
                                <div class="col-md-3">
                                    <strong>
                                        Allowed agent : <span class="fw-bold"> {{$dwsData['agentsAllowed'] ?? ''}} </span>
                                    </strong>
                                </div>

                                <div class="col-md-3">
                                    <strong>
                                        Installed agent : <span class="fw-bold"> {{$dwsData['agentsInstalled'] ?? ''}} </span>
                                    </strong>
                                </div>

                                <div class="col-md-3">
                                    <strong>
                                        Available agent : <span class="fw-bold available_agent"> {{$dwsData['agentsAllowed'] - $dwsData['agentsInstalled']  ?? ''}} </span>
                                    </strong>
                                </div>
                                @else
                                <div class="col-md-3">
                                    <span>
                                        <b>{{$dwsError}}</b>
                                    </span>
                                </div>
                                @endif

                                <div class="col-md-3">
                                    <button class="btn btn-primary" data-bs-target="#allowAgent" data-bs-toggle="modal">Allow Agent</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th class="wd-15p border-bottom-0">Company Name</th>
                                            <th class="wd-15p border-bottom-0">Agents</th>
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
<!-- Allow Agent Modal -->
<div class="modal fade" id="allowAgent" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add Allow Agent</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" method="POST" id="allow-agent-frm">
                    @csrf
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="agent">Agents</label>
                            <input type="number" class="form-control" name="allow_agent" placeholder="Enter number of agents" value="" min="1">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary save_allow_agent_btn">Save</button>
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Allow Agent Modal END -->
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
                url: "{{ url('get-agents') }}",
                data: function (d) {
                    d.seacrh_name = $('select[name=seacrh_name]').val();
                }
            },
            oLanguage: {sProcessing: "<div id='loaderDB'></div>"},
            aaSorting: [[1, 'desc']],
            columns: [

                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'company_name', name: 'company_name',orderable: true, searchable: false},
                {data: 'agent', name: 'agent',orderable: true, searchable: false },
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

        $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
                dial_code: {
                    required: true,
                },
            },
            messages: {
            },
            submitHandler: function (form) {
                // return true;
            }
        });


        $(document).on('click', '.btn_save', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-countries")}}',
                    type: "POST",
                    data:  $('#frm_add').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        _this.prop('disabled', false).html('Save');
                        try {
                            data = JSON.parse(data);
                        } catch(e){}
                        if(data.status == 1)
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "Succesfully saved."
                            });
                            tableRx.ajax.reload( null, false );
                            $('#frm_add')[0].reset();
                            $('#country_id').val('');
                            $('.btn_cancle').click();

                        } else {
                            $.growl.error({
                                message: "Cannot save."
                            });
                        }
                    }
                });
            }
        });

        $(document).on('click', '.btn_cancle', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            $('#frm_add')[0].reset();
            $('#country_id').val('');
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


        $(document).on('click', '.activeInactiveCompanyAgent', function() {
            var _this = $(this);
            var id = _this.attr('data-id');
            $('label.error').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();

                $.ajax({
                    url: '{{url("active-company-agent")}}',
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

        // Start Allow Agent
        $('#allow-agent-frm').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                allow_agent: {
                    required: true,
                },
            },
            messages: {
            },
            submitHandler: function (form) {
                // return true;
            }
        });

        $(document).on('click', '.save_allow_agent_btn', function() {
            $('label.errorFrm').remove();
            $('label.success_msg').remove();
            $('.alert-outline-success').remove();
            var _this = $(this);
            if($("#allow-agent-frm").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-allow-agents")}}',
                    type: "POST",
                    data:  $('#allow-agent-frm').serialize(),
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
                                message: "Succesfully saved."
                            });
                            $('#allowAgent').modal('hide');
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            _this.prop('disabled', false).html('Save');
                            $.growl.error({
                                message: data.response_msg
                            });
                        }
                    }
                });
            }
        });

        $('#allowAgent').on('hide.bs.modal', function () {
            $('#allow_agent-error').remove();
            $('#allow-agent-frm')[0].reset();
            console.log('Modal is about to be hidden');

        });

        $(document).on('click','.add_button',function(){
            $.growl.error({
                message: "No available agent."
            });
        });
    });
</script>

@endsection
