@extends('front.layout_admin.app')

@section('page_level_css')
<link rel="stylesheet" href="{{ url('app-assets/vendor/summernote/dist/summernote.css')}}"/>
    <style>
        li {
            list-style: unset !important;
        }
    </style>
@endsection

@section('content')

<div id="main-content">
    <div class="container-fluid">
    	<div class="block-header">
           <div class="row">
               <div class="col-lg-5 col-md-8 col-sm-12">
                   <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i
                               class="fa fa-arrow-left"></i></a> {{ $heading }}</h2>
                   <ul class="breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                       <li class="breadcrumb-item active">{{ $heading }}</li>
                   </ul>
               </div>
               <div class="col-lg-7 col-md-4 col-sm-12 text-right">
               </div>
           </div>
       </div>
       <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="card">
                    <form class="form" method="post" id="frm_add">
                        @csrf
                        <input type="hidden" name="key" id="key" value="{{$data->key ?? ''}}">
                        <input type="hidden" name="id" id="id" value="{{$data->id ?? ''}}">
                        <div class="form-body">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="value">Content (Enter relevant content only) <span class="danger">*</span></label>
                                        <textarea class="form-control" name="value" style="display: none;">{{$data->value ?? ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                        <div class="form-actions right">                                                
                            <button type="button" class="btn btn-primary btn_save">
                                <i class="ft-check-square"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
       </div>
    </div>
</div>

@endsection

@section('page_level_js')
<script src="{{ url('app-assets/vendor/summernote/dist/summernote.js')}}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script>
    jQuery(document).ready(function($) {

        $('#frm_add').validate({
            ignore: "",
            //errorElement: 'div',
            //errorClass: "invalid-feedback",
            rules: {
                value: {
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
                        url: '{{url("admin/save-cms")}}', 
                        type: "POST",             
                        data:  $('#frm_add').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },    
                        success: function(data) {
                            _this.prop('disabled', false).text('Save');
                            try {
                                data = JSON.parse(data);
                            } catch(e){}
                            if(data.success) {
                                toastr.success("Succesfully saved.","Success");                                      
                            } else if(!data.success) {
                                var errMsg = data.message;
                                toastr.error("","Error");                            

                                $.each(errMsg,function(field_name,error){
                                    toastr.error(error,"Error");  
                                    //$(document).find('[name='+field_name+']').after('<label class="errorFrm">'+error+'</label>');
                                    
                                })
                            } else {
                                 toastr.success("Cannot save.","Error");                                 
                            }
                        }
                });
            }
        });
        

        // Editor
        $('[name="value"]').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ["link"]]
            ],
            height: 300,
            dialogsInBody: true,
            callbacks: {
                onImageUpload1: function (image) {
                    //alert(1);
                    uploadImage(image[0], function (url) {
                        var image = $('<img>').attr('src', url).css("width", "100%");
                        $('[name="value"]').summernote("insertNode", image[0]);
                    });
                },
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    //console.log(bufferText);
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

    });
    </script>
@endsection
