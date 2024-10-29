@extends('front.layout_admin.app')

@section('page_level_css')
<!--- Custom Style CSS -->
<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>
<style>
    .custom-chkbox-permission {
        width: 1.2rem !important;
        height: 1.2rem !important;
    }
    .form-check-label.h5 {
        margin-bottom:0;
    }
    .activity-img i {
        padding-bottom: 0px !important;
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
                                <input type="file" style="display: none;" name="image" class="hideMe icon_image" id="image">
                                <input type="hidden" id="icon_id" name="icon_id">
                                <input type="hidden" id="icon_name" name="icon_name">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Select Company <span class="text-danger">*</span></label>
                                            <select class="form-select me-2" aria-label="Default select example" name="company_id" id="company_id">
                                                <option selected value="">Select</option>
                                                @if ($getCompany)
                                                @foreach ($getCompany as $company)
                                                <option value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row active_inactive_div d-none">
                                    <div class="col-xl-12 col-md-12">
                                        <div class="card card-border">
                                            <div class="card-header bg-danger br-tr-4 br-tl-4">
                                                <h3 class="card-title text-white"> Inactive Icons (Uploads SVG)</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row inactve_icon_row">
                                                    {{-- @if($data)
                                                    @foreach($data as $key => $value)
                                                    @if($value->status == 'inactive')
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-control-label">{{$value->icon_label ?? ''}}<span class="text-danger">*</span></label>
                                                            <img src="{{$value->icon_img_url ?? getLocalFileUrl('/no_image.png')}}" class="add_img form-control img-thumbnail img-name-{{$value->_id ?? ''}}" data-id="{{$value->_id ?? ''}}" data-name="{{$value->icon_name ?? ''}}" style="cursor: pointer; height: auto; width: 80px;">
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row active_inactive_div d-none">
                                    <div class="col-xl-12 col-md-12">
                                        <div class="card card-border">
                                            <div class="card-header bg-primary br-tr-4 br-tl-4">
                                                <div>
                                                    <h3 class="card-title text-white"> Active Icons (Uploads SVG)</h3>
                                                </div>
                                            </div>
                                            <div class="card-body pb-0 pt-4">
                                                <div class="row actve_icon_row">
                                                    {{-- @if($data)
                                                    @foreach($data as $key => $value)
                                                    @if($value->status == 'active')
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-control-label">{{$value->icon_label ?? ''}}<span class="text-danger">*</span></label>
                                                            <img src="{{$value->icon_img_url ?? getLocalFileUrl('/no_image.png')}}" class="add_img form-control img-thumbnail img-name-{{$value->_id ?? ''}}" data-id="{{$value->_id ?? ''}}" data-name="{{$value->icon_name ?? ''}}" style="cursor: pointer; height: auto; width: 80px;">
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('change', '#company_id', function (event) {
            var _this = $(this);
            if(_this.val()){
                $('.inactve_icon_row').html('');
                $('.actve_icon_row').html('');
                $('#image').val('');
                getIcons(_this.val());
            } else {
                $('.active_inactive_div').addClass('d-none');
                $('#icon_id').val('');
                $('#icon_name').val('');
                $('#image').val('');
                $('.inactve_icon_row').html('');
                $('.actve_icon_row').html('');
            }
        });

        function getIcons(company_id){
            $('.inactve_icon_row').before('<div class="spinner1 spinner-loader"><div class="double-bounce2"></div></div>');
            $('.actve_icon_row').before('<div class="spinner1 spinner-loader"><div class="double-bounce2"></div></div>');
            $.ajax({
                url: '{{ url("get-icons") }}',
                type: 'POST',
                data: {'company_id' : company_id},
                success: function (data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if (data.status == 'true') {
                        $.each(data.data, function(key, value) {
                            if (value.status == 'inactive') {
                                // Create the HTML for each item
                                var html = '<div class="col-md-2">' +
                                            '<div class="form-group">' +
                                                '<label class="form-control-label">' + (value.icon_label || '') + '<span class="text-danger">*</span></label>' +
                                                '<img src="' + (value.icon_img_url) + '" class="add_img form-control img-thumbnail img-name-' + (value._id || '') + '" data-id="' + (value._id || '') + '" data-name="' + (value.icon_label || '') + '" style="cursor: pointer; height: auto; width: 80px;">' +
                                            '</div>' +
                                        '</div>';

                                // Append the HTML to the container
                                $('.inactve_icon_row').append(html);
                            } else {
                                var html1 = '<div class="col-md-2">' +
                                            '<div class="form-group">' +
                                                '<label class="form-control-label">' + (value.icon_label || '') + '<span class="text-danger">*</span></label>' +
                                                '<img src="' + (value.icon_img_url) + '" class="add_img form-control img-thumbnail img-name-' + (value._id || '') + '" data-id="' + (value._id || '') + '" data-name="' + (value.icon_label || '') + '" style="cursor: pointer; height: auto; width: 80px;">' +
                                            '</div>' +
                                        '</div>';

                                // Append the HTML to the container
                                $('.actve_icon_row').append(html1);
                            }
                        });
                    }
                    $('.spinner-loader').remove();
                    $('.active_inactive_div').removeClass('d-none');

                }
            });
        }

        $(document).on('click', '.add_img', function (event) {
            event.preventDefault();
            var icon_id = $(this).attr('data-id');
            var icon_name = $(this).attr('data-name');
            $('#icon_id').val(icon_id);
            $('#icon_name').val(icon_name);
            $('#image').trigger('click');
        });

        var _URL = window.URL || window.webkitURL;
        var file, img;
        $(document).on('change', '#image', function (e) {
            console.log("CALL Changes");
            var icon_id = $('#icon_id').val();
            var name    = $('.img-name-'+icon_id);
            if ((file = this.files[0])) {
                //console.log('file', file);
                var ext = file.name.split('.').pop();
                if(ext == 'svg' || ext == 'SVG') {
                        img = new Image();
                        img.onload = function () {
                            //alert(this.width + " " + this.height);
                            // checkImageDImension(this.width, this.height, file);
                           uploadFile(file);
                           $(name).attr({src: _URL.createObjectURL(file)});
                        };
                        img.src = _URL.createObjectURL(file);
                } else {
                    $.growl.error({
                        message: "Please upload only SVG files."
                    });
                }
            }

        });


        function uploadFile(file) {
            var fd = new FormData();
            var icon_name   = $('#icon_name').val();
            var icon_id     = $('#icon_id').val();
            var company_id  = $('#company_id').val();
            fd.append('file', file);
            fd.append('icon_name', icon_name);
            fd.append('icon_id', icon_id);
            fd.append('company_id', company_id);
            $.ajax({
                url: '{{ url("update-icon-setting") }}',
                type: 'POST',
                processData: false,
                contentType: false,
                data: fd,
                success: function (data, status, jqxhr) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if (data.status == 'true') {
                        $.growl.notice({
                            title: "Success",
                            message: "Successfully saved."
                        });
                    } else {
                        $.growl.error({
                            message: "Cannot upload file."
                        });
                    }
                    $('#icon_name').val('');
                    $('#icon_id').val('');
                },
                error: function (jqxhr, status, msg) {
                    //error code
                }
            });
        } //end upload image
    });
</script>
@endsection
