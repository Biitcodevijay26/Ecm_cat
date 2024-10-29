@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
@endsection
@section('content')
<?php
 $company_login_id = session()->get('company_login_id');
 $ids = $data->id ?? '';
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
                        <li class="breadcrumb-item"><a href="{{ url('/agent') }}">{{$module}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

             <!-- ROW-1 OPEN -->
             <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $heading }} Info</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" id="frm_add">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ $data->_id ?? ''}}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name <span class="text-danger"> *</span></label>
                                            <select name="company_name" id="company_name" class="form-control select2">
                                                <option value="">Select</option>
                                                @if ($getCompany)
                                                @foreach ($getCompany as $company)
                                                <?php
                                                    $selected = "";
                                                    if(isset($data->company_id) && $company->_id == $data->company_id){
                                                        $selected = "selected";
                                                    }
                                                ?>
                                                <option  value="{{$company->_id ?? ''}}" {{ $selected }}>{{$company->company_name ?? ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="agent">Agents </label>
                                            <input type="number" class="form-control" name="agent" id="agent" placeholder="Enter number of agents" value="{{ $data->agent ?? ''}}" min="0" max="{{$available_agent}}">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{url('/agent')}}" class="btn btn-danger w-sm">Cancel</a>
                            <a href="javascript:void(0);" class="btn btn-primary w-sm btn_save">Save</a>
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
    $(document).ready(function($) {
        var company_login_id = '{{ $company_login_id ?? ''}}';
        $('.select2').select2({});
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        // Validation Device
        $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                company_name: {
                    required: true,
                },
                agent: {
                    required: true,
                }
            },
            messages: {

            },
            // errorElement: "div",
            submitHandler: function (form) {
                // return true;
            }

        });

        // Save Record
        $(document).on('click', '.btn_save', function() {
            var _this = $(this);
            if($("#frm_add").valid()) {
                _this.prop('disabled', true).text('Processing...');
                $.ajax({
                    url: '{{url("save-agents")}}',
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
                        if(data.status == "true")
                        {
                            $.growl.notice({
                                title: "Success",
                                message: "Successfully saved."
                            });
                            location = "{{url('/agent')}}";

                        } else {
                            $.growl.error({
                                message: data.response_msg
                            });
                        }
                    }
                });
            }
        });

    }); // End Ready
</script>
@endsection

