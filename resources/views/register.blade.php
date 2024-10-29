<!doctype html>
<html lang="en" dir="ltr">
  <head>

		<!-- META DATA -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta name="author" content="{{ config('app.name') }}">
		<meta name="keywords" content="">

		<!-- FAVICON -->
		<link rel="shortcut icon" type="image/x-icon" href="{{ url('theme-asset/images/brand/favicon.ico') }}" />

		<!-- TITLE -->
		<title>{{config('app.name')}}</title>

		<!-- BOOTSTRAP CSS -->
		<link id="style" href="{{ url('theme-asset/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

		<!-- STYLE CSS -->
		<link href="{{ url('theme-asset/css/style.css') }}" rel="stylesheet"/>
		<link href="{{ url('theme-asset/css/plugins.css') }}" rel="stylesheet"/>

		<!--- FONT-ICONS CSS -->
		<link href="{{ url('theme-asset/css/icons.css') }}" rel="stylesheet"/>

	    <!--- Custom Style CSS -->
		<link href="{{ url('theme-asset/css/custom_style.css') }}" rel="stylesheet"/>

        @yield('page_level_css')
        <style>
            .login100-form {
                width: 100%;
            }
	    	.wrap-input100 {
	    		margin-bottom:0 !important;
            }
        </style>
	</head>

	<body class="login-img">

		<!-- BACKGROUND-IMAGE -->
		<div>

			<!-- GLOABAL LOADER -->
			<div id="global-loader">
				<img src="{{ url('theme-asset/images/loader.svg') }}" class="loader-img" alt="Loader">
			</div>
			<!-- /GLOABAL LOADER -->

			<!-- PAGE -->
			<div class="page login-page">
				<div>
				    <!-- CONTAINER OPEN -->
					<div class="col col-login mx-auto mt-7">
						<div class="text-center">
							<img src="{{ url('theme-asset/images/brand/logo.png') }}" class="header-brand-img" alt="">
						</div>
					</div>

                    <!-- Start Register Page -->
					<div class="container-login100 registration-section">
						<div class="wrap-login100 wrap-login100-register p-0">
							<div class="card-body">
								<form class="login100-form validate-form" action="{{ route('register') }}" method="POST" id="register-form">
                                    @csrf
									<span class="login100-form-title">
										Customer Registration
									</span>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">First Name <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="text" id="first_name" name="first_name" placeholder="First Name" autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="first_name">Last Name <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="text" id="last_name" name="last_name" placeholder="Last Name" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="text" id="email" name="email" placeholder="Email" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-group-register error-company_id">
                                                <label for="company_id">Company <span class="text-danger"> *</span></label>
                                                <select class="form-select select2 form-control" name="company_id" id="company_id">
                                                    <option  value="">Select</option>
                                                    @if ($companies)
                                                    @foreach ($companies as $company)
                                                    <option value="{{$company->id ?? ''}}">{{$company->company_name ?? ''}}</option>
                                                    @endforeach
                                                     @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="password" name="password" id="password" placeholder="Password" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="cpassword">Confirm Password <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-register error-country_id">
                                                <label for="country_id">Country <span class="text-danger"> *</span></label>
                                                <select class="form-select select2 form-control" name="country_id" id="country_id">
                                                    <option value="">Select</option>
                                                    @if ($countries)
                                                        @foreach ($countries as $country)
                                                        <option value="{{$country->id ?? ''}}">{{$country->name ?? ''}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="city_name">City <span class="text-danger"> *</span></label>
                                                <input class="form-control" type="text" name="city_name" id="city_name" placeholder="City" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

									<div class="container-login100-form-btn">
										<a href="javascript:void(0)" class="login100-form-btn btn-primary btn_save">
											Register
										</a>
									</div>
									<div class="text-center pt-3">
										<p class="text-dark mb-0">Already have account?<a href="{{url('/login')}}" class="text-primary ms-1">Sign In</a></p>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
                    <!-- End Register Page -->

                    <!-- Start OTP Verificaion -->

                    <!-- CONTAINER OPEN -->
					<div class="container-login100 otp-verification-section d-none">
						<div class="row">
							<div class="col col-login mx-auto">
								<form class="card shadow-none" method="post" id="verify-otp-form">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="">
									<div class="card-body">
										<div class="text-center">
											<span class="login100-form-title">
												OTP Verification
											</span>
											<p class="text-muted">Enter the 4 digit otp send on your registered account</p>
										</div>

                                        <div class="pt-3" id="forgot">
                                            <div class="form-group mb-0">
                                                <label for="otp">OTP <span class="text-danger">*</span></label>
                                                <input class="form-control" name="otp" id="otp" placeholder="Enter OTP" type="text" maxlength="4">
                                            </div>
                                            <div class="text-end pt-1">
                                                <p class="mb-2"><a href="javascript:void(0)" class="text-primary ms-1 resend_code_btn">Resend OTP?</a></p>
                                            </div>
                                            <div class="submit">
                                                <a class="btn btn-primary d-grid verify_otp_btn" href="javascript:void(0)">Submit</a>
                                                {{-- <a class="btn btn-success d-grid mt-2" href="{{ url('/login') }}">Back</a> --}}
                                            </div>
                                            {{-- <div class="text-center mt-4">
                                                <p class="text-dark mb-0">Forgot It?<a class="text-primary ms-1" href="javascript:void(0);">Send me Back</a></p>
                                            </div> --}}
                                        </div>

									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->
                    <!-- End OTP Verificaion -->

				</div>
			</div>
			<!-- END PAGE -->

		</div>
		<!-- BACKGROUND-IMAGE CLOSED -->

        <!-- JQUERY JS -->
		<script src="{{ url('theme-asset/js/jquery.min.js') }}"></script>

		<!-- BOOTSTRAP JS -->
		<script src="{{ url('theme-asset/plugins/bootstrap/js/popper.min.js') }}"></script>
		<script src="{{ url('theme-asset/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

		<!-- SPARKLINE JS -->
		<script src="{{ url('theme-asset/js/jquery.sparkline.min.js') }}"></script>

		<!-- CHART-CIRCLE JS -->
		<script src="{{ url('theme-asset/js/circle-progress.min.js') }}"></script>

		<!-- Perfect SCROLLBAR JS-->
		<script src="{{ url('theme-asset/plugins/p-scroll/perfect-scrollbar.js') }}"></script>

		<!-- INPUT MASK JS -->
		<script src="{{ url('theme-asset/plugins/input-mask/jquery.mask.min.js') }}"></script>

		<!-- SELECT2 JS -->
		<script src="{{ url('theme-asset/plugins/select2/select2.full.min.js') }}"></script>

        <!-- INTERNAL Notifications js -->
        <script src="{{ url('theme-asset/plugins/notify/js/rainbow.js') }}"></script>
        <script src="{{ url('theme-asset/plugins/notify/js/jquery.growl.js') }}"></script>
        <script src="{{ url('theme-asset/plugins/notify/js/notifIt.js') }}"></script>

        <!-- Color Theme js -->
        <script src="{{ url('theme-asset/js/themeColors.js') }}"></script>

        <!-- swither styles js -->
        <script src="{{ url('theme-asset/js/swither-styles.js') }}"></script>

        <!-- CUSTOM JS -->
        <script src=" {{ url('theme-asset/js/custom.js') }}"></script>

        <!-- Custom Jquery Validation -->
        <script src=" {{ url('theme-asset/jquery-validation/jquery.validate.min.js') }}"></script>
		 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" type="text/javascript"></script> -->
        <script>
            $('.select2').select2({});
            $(document).ready(function($) {
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });

	            $('#register-form').validate({
	                ignore: "",
	                //errorElement: 'div',
	                //errorClass: "invalid-feedback",
	                rules: {
	                    first_name: {
	                        required: true,
	                    },
	                    last_name: {
	                        required: true,
	                    },
	                    email: {
	                    	required: true,
	                        email: true
	                    },
	                    password: {
	                    	required: true,
	                        minlength: 6,
	                    },
	                    cpassword: {
	                    	required: true,
	                        minlength: 6,
	                        equalTo: "#password",
	                    },
	                    company_id: {
	                        required: true,
	                    },
	                    country_id: {
	                        required: true,
	                    },
	                    city_name: {
	                        required: true,
	                    }

	                },
	                messages: {

	                },
	                // errorElement: "div",
	                errorPlacement: function(error, element) {
	                	if(element.attr('name') == "company_id")
	                	{
                            error.insertAfter('.error-company_id');
	                	}
                        else if(element.attr('name') == "country_id"){
	                		error.insertAfter('.error-country_id');
	                	}
                        else {
	                		error.insertAfter(element);
	                	}
	                },
	                submitHandler: function (form) {
	                   // return true;
	                }

	            });


	            $("select").on("select2:close", function (e) {
                   $(this).valid();
               });

	            // Save Register Form
	             $(document).on('click', '.btn_save', function() {
	             	var _this = $(this);
	             	if($("#register-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{route("register")}}',
                            type: "POST",
                            data:  $('#register-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Register');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == "success")
                                {
                                    $.growl.notice({
                                        title: "Success",
                                        message: "Succesfully Register."
                                    });
                                    $('#user_id').val(data.data.user_id);
                                    $(".registration-section").addClass('d-none');
                                    $(".otp-verification-section").removeClass('d-none');
                                    $("#otp").val('');
                                    if(data.data.otp)
                                    {
                                        $("#otp").val(data.data.otp);
                                    }
                                    // location = "{{url('/login')}}";
                                } else if(data.status == "error") {

                                    if(data.errors)
                                    {
                                        $.each(data.errors,function(key,val) {
                                            if(key == "company_id")
                                            {
                                                $(".error-company_id").after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                            } else if(key == "country_id") {
                                                $(".error-country_id").after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                            } else {
                                                $("#"+key).after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                            }
                                        })

                                    }
                                } else if(data.status == "open_veri_screen"){
                                    $('#user_id').val(data.data.user_id);
                                    $(".registration-section").addClass('d-none');
                                    $(".otp-verification-section").removeClass('d-none');
                                    $("#otp").val('');
                                    if(data.data.otp)
                                    {
                                        $("#otp").val(data.data.otp);
                                    }
                                    if(data.error_class == "success")
                                    {
                                        $("#otp").after('<label id="otp-error" class="text-success" for="otp" style>'+data.data.msg+'</label>');
                                    }
                                    else
                                    {
                                        $("#otp").after('<label id="otp-error" class="error" for="otp" style>'+data.data.msg+'</label>');
                                    }
                                } else if(data.status == "login_msg"){
                                    $.growl.warning({
                                        message: "Your account already exist. Please login."
                                    });
                                } else {
                                    $.growl.error({
                                        message: "Cannot saved."
                                    });
                                }
                            }
                        });
	             	}
	             });

                //  OTP Validation
                $('#verify-otp-form').validate({
	                ignore: "",
	                //errorElement: 'div',
	                //errorClass: "invalid-feedback",
	                rules: {
	                    otp: {
	                        required: true,
                            minlength: 4,
	                    }
	                },
	                messages: {

	                },

	                submitHandler: function (form) {
	                   // return true;
	                }

	            });

                // Verify OTP Only Accept Digit
                $("input[name='otp']").on('input', function (e) {

                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
                });

                $(document).on('click', '.verify_otp_btn', function() {
	             	var _this = $(this);
	             	if($("#verify-otp-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{url("verify-otp")}}',
                            type: "POST",
                            data:  $('#verify-otp-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Submit');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == 1)
                                {
                                     location = "{{url('/dashboard')}}";
                                } else {
                                    $('#otp-error').remove();
                                    $("#otp").after('<label id="otp-error" class="error" for="otp">Invalid OTP.</label>');
                                }
                            }
                        });

                    }
                });
                $(document).on('click', '.resend_code_btn', function() {
	             	var _this = $(this);
                    var user_id = $("#user_id").val();
	             	if(user_id) {
                        $.ajax({
                            url: '{{url("resend-otp")}}',
                            type: "POST",
                            data:  $('#verify-otp-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Resend OTP?');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == 1)
                                {
                                    $('#otp-error').remove();
                                    if(data.otp)
                                    {
                                        $("#otp").val(data.otp);
                                    }
                                    if(data.error_class == "success")
                                    {
                                        $("#otp").after('<label id="otp-error" class="text-success" for="otp" style>'+data.msg+'</label>');
                                    }
                                    else
                                    {
                                        $("#otp").after('<label id="otp-error" class="error" for="otp" style>'+data.msg+'</label>');
                                    }
                                }
                            }
                        });

                    }
                });

                $(document).on('keyup','#otp',function(){
                    $("label.text-success").remove();
                });

            }); // End Ready
        </script>
    </body>
</html>
