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
                            {{-- <img src="{{ url('uploads/logo/logo.png') }}" class="header-brand-img" alt="logo"> --}}
						{{-- <h3>{{ config('app.name') }} </h3> --}}
						</div>
					</div>
					<div class="container-login100 login-section">
						<div class="wrap-login100 p-0">
							<div class="card-body">
								<form class="login100-form validate-form" action="{{ route('login') }}" method="POST" id="login-form">
									@csrf
									<span class="login100-form-title" Style="font-weight:bold">
										CUSTOMER LOGIN
									</span>

                                    <label class="label-margin" for="email">Email <span class="text-danger"> *</span> </label>
									<div class="wrap-input100 validate-input">
										<input class="input100 @error('email') is-invalid @enderror" type="text" name="email" placeholder="Email">
										<span class="focus-input100"></span>
										<span class="symbol-input100">
											<i class="zmdi zmdi-email" aria-hidden="true"></i>
										</span>
									</div>
                                    <div class="error-email"></div>
                                    @error('email')
                                        <div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4 " role="alert">{{ $message }}</div>
                                    @enderror

                                    <label class="label-margin mt-2" for="password">Password <span class="text-danger"> *</span> </label>
									<div class="wrap-input100 validate-input">
										<input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
										<span class="focus-input100"></span>
										<span class="symbol-input100">
											<i class="zmdi zmdi-lock" aria-hidden="true"></i>
										</span>
									</div>
                                    <div class="error-password"></div>
									@error('password')
										<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">{{ $message }}</div>
									@enderror

									<div class="text-end pt-1">
										<p class="mb-0"><a href="{{ url('/forgot-password') }}" class="text-primary ms-1">Forgot Password?</a></p>
									</div>
									<div class="container-login100-form-btn">
										{{-- <button href="{{url('admin/dashboard')}}" class="login100-form-btn btn-primary">
											Login
										</button> --}}
										<a href="javascript:void(0)" class="login100-form-btn btn-success customer-login-btn" Style="font-weight:bold;width:150px;border-radius:50px">
											LOGIN
                                        </a>

									</div>
									<div class="text-center pt-3">
										<p class="text-dark mb-0">Not a member-?<a href="{{url('/register')}}" class="text-primary ms-1">Create an Account</a></p>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="text-center">
				        <img src="{{ url('theme-asset/images/brand/logo2.png') }}" class="header-brand-img" alt="">
					<!-- CONTAINER CLOSED -->
                     <!-- Start OTP Verificaion -->

                    <!-- CONTAINER OPEN -->
					<div class="container-login100 otp-verification-login-section d-none">
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

                                        <div class="pt-3">
                                            <div class="form-group mb-0">
                                                <label for="otp">OTP <span class="text-danger">*</span></label>
                                                <input class="form-control" name="otp" id="otp" placeholder="Enter OTP" type="text" maxlength="4">
                                            </div>
                                            <div class="text-end pt-1">
                                                <p class="mb-2"><a href="javascript:void(0);" class="text-primary ms-1 resend_code_btn">Resend OTP?</a></p>
                                            </div>
                                            <div class="submit">
                                                <a class="btn btn-primary d-grid verify_otp_btn" href="javascript:void(0);">Submit</a>
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
			<!-- End PAGE -->

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

        <script>
            $(document).ready(function($) {

                $('#login-form').validate({
                    ignore: "",
                    //errorElement: 'div',
                    //errorClass: "invalid-feedback",
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                        }

                    },
                    messages: {

                    },
                    // errorElement: "div",
                    errorPlacement: function(error, element) {
                        if(element.attr('name') == "email")
                        {
                            error.insertBefore('.error-email');
                        } else if(element.attr('name') == "password"){
                            error.insertBefore('.error-password');
                        }  else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function (form) {
                    form.submit();
                    }

                });

                $(document).on('click','.customer-login-btn',function() {
                    var _this = $(this);
	             	if($("#login-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{route("login")}}',
                            type: "POST",
                            data:  $('#login-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Login');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == "success")
                                {
                                    location = "{{url('/dashboard')}}";
                                } else if(data.status == "error") {

                                    if(data.errors)
                                    {
                                        $.each(data.errors,function(key,val) {
                                            $("#"+key).after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                        })
                                    }
                                } else if(data.status == "open_veri_screen"){
                                    $('#user_id').val(data.data.user_id);
                                    $(".login-section").addClass('d-none');
                                    $(".otp-verification-login-section").removeClass('d-none');
                                    if(data.data.otp)
                                    {
                                        $("#otp").val(data.data.otp);
                                    }
                                    $('label.text-success').remove();
                                    if(data.error_class == "success")
                                    {
                                        $("#otp").after('<label id="otp-error" class="text-success" for="otp" style>'+data.data.msg+'</label>');
                                    }
                                    else
                                    {
                                        $("#otp").after('<label id="otp-error" class="error" for="otp" style>'+data.data.msg+'</label>');
                                    }
                                } else if(data.status == "user_login"){
                                    $('label.error').remove();
                                    $(".error-password").before('<label id="password-error" class="error" for="password">'+data.msg+'</label>');
                                } else {
                                    $('label.error').remove();
                                    $(".error-password").before('<label id="password-error" class="error" for="password">'+data.msg+'</label>');
                                }
                            }
                        });
                    }
                })
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
                                    $('label.error').remove();
                                    $('label.text-success').remove();
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
                                    $('label.error').remove();
                                    $('label.text-success').remove();
                                    $("#otp").val('');
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
                    $(".errorMsg").html('');
                    $("label.text-success").remove('');
                });

            });
        </script>
	</body>
</html>
