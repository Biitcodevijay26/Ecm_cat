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
<?php
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
                                <input type="hidden" name="user_id" id="user_id" value="{{$data->id ?? ''}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Upload Logo  (Image Ex.200px * 55px) <span class="text-danger">*</span></label>
                                            <img src="{{ isset($data->value) && $data->value ? getLocalFileUrl('logo/'.$data->value) : getLocalFileUrl('/no_image.png')  }}" class="add_img form-control" id="img_disp" style="cursor: pointer; height: auto; width: 300px;" data-value="{{$data->id ?? ''}}">
                                            <input type="file" style="display: none;" name="logo" class="hideMe logo_image" id="image">
                                            <input type="hidden" name="image" value="{{ $data->value ?? ''}}" id="for_cat_image">
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
        $(document).on('click', '.add_img', function (event) {
            event.preventDefault();
            $('#image').trigger('click');
        });

        var _URL = window.URL || window.webkitURL;
        var file, img;
        $("#image").change(function (e) {

            $('#for_cat_image').siblings('.error').remove();

            if ((file = this.files[0])) {
                //console.log('file', file);
                var ext = file.name.split('.').pop();
                if(ext == 'jpg' || ext == 'png' || ext == 'jpeg' || ext == 'gif' || ext == 'bmp' || ext == 'JPG' || ext == 'PNG' || ext == 'JPEG' || ext == 'GIF' || ext == 'BMP' || ext == 'svg' || ext == 'SVG') {
                        img = new Image();
                        img.onload = function () {
                            //alert(this.width + " " + this.height);
                            checkImageDImension(this.width, this.height, file);
                        //    uploadFile(file,'#for_cat_image');
                           $('#img_disp').attr({src: _URL.createObjectURL(file)});
                        };
                        img.src = _URL.createObjectURL(file);
                } else {
                    $('#for_cat_image').after('<label class="error">Please select file in image formate (.jpg  .png  .jpeg  .gif  .bmp .svg)</label>');
                }
            }

        });

        function checkImageDImension(i_width, i_height) {
            if (i_width && i_height && i_width == "{{config('constants.LOGO_IMG_WIDTH')}}" && i_height == "{{config('constants.LOGO_IMG_HEGHT')}}") {
                uploadFile(file,'#for_cat_image');
                $('#img_disp').attr({src: _URL.createObjectURL(file)});
            } else {
                $('#for_cat_image').after('<label class="error">Please select image ratio {{config("constants.LOGO_IMG_WIDTH")}}px * {{config("constants.LOGO_IMG_HEGHT")}}px.</label>');
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function uploadFile(file,type) {
            var fd = new FormData();
            fd.append('file', file);
            $.ajax({
                url: '{{ url("update-logo") }}',
                type: 'POST',
                processData: false,
                contentType: false,
                data: fd,
                success: function (data, status, jqxhr) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){}
                    if (data.status == 'true') {
                        $(type).val(data.data);
                    } else {
                        alert('Cannot upload file.');
                    }
                },
                error: function (jqxhr, status, msg) {
                    //error code
                }
            });
        } //end upload image
    });
</script>
@endsection
