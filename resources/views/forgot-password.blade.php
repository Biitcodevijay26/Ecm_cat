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
	</head>

	<body class="login-img">

        <!-- BACKGROUND-IMAGE -->
		<div>

			<!-- GLOABAL LOADER -->
			<div id="global-loader">
				<img src="{{ url('theme-asset/images/loader.svg') }}" class="loader-img" alt="Loader">
			</div>
			<!-- End GLOABAL LOADER -->

			<!-- PAGE -->
			<div class="page login-page">

                <div>

				    <!-- CONTAINER OPEN -->
					<div class="col col-login mx-auto mt-7">
						<div class="text-center">
							<img src="{{ url('theme-asset/images/brand/logo.png') }}" class="header-brand-img" alt="">
						</div>
					</div>
					<div class="container-login100 forgot-pass-section">
						<div class="row">
							<div class="col col-login mx-auto">
								<form class="card shadow-none" method="post" id="forgot-password-form">
                                    @csrf
									<div class="card-body">
										<div class="text-center">
											<span class="login100-form-title">
												Forgot Password?
											</span>
											<p class="text-muted">Enter the email address registered on your account</p>
										</div>

                                        <div class="pt-3">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input class="form-control"  name="email" id="email" placeholder="Enter Your Email" type="email">
                                                <span class="errorMsg error"></span>
                                            </div>
                                            <div class="submit">
                                                <a class="btn btn-primary d-grid forgot_pass_btn" href="javascript:void(0);">Submit</a>
                                                <a class="btn btn-success d-grid mt-2" href="{{ url('/login') }}">Back</a>
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

                    <!-- Start OTP Verificaion -->

                    <!-- CONTAINER OPEN -->
					<div class="container-login100 otp-verification-forgot-section d-none">
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

                    <!-- Start Reset Password -->

                    <!-- CONTAINER OPEN -->
					<div class="container-login100 reset-password-section d-none">
						<div class="row">
							<div class="col col-login mx-auto">
								<form class="card shadow-none" method="post" id="reset-password-form">
                                    @csrf
                                    <input type="hidden" id="reset_email" name="reset_email" value="">
                                    <input type="hidden" id="reset_token" name="reset_token" value="">
									<div class="card-body">
										<div class="text-center">
											<span class="login100-form-title">
												Reset Password
											</span>
											<p class="text-muted">Enter the password and confirm password.</p>
										</div>

                                        <div class="pt-3">
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-danger">*</span></label>
                                                <input class="form-control reset_password" name="password" id="password" placeholder="Enter password" type="password" autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <label for="cpassword">Confirm Password <span class="text-danger">*</span></label>
                                                <input class="form-control reset_password" name="cpassword" id="cpassword" placeholder="Enter confirm password" type="password" autocomplete="off">
                                                <span class="resetMsg error"></span>
                                            </div>

                                            <div class="submit">
                                                <a class="btn btn-primary d-grid reset_submit_btn" href="javascript:void(0);">Submit</a>
                                                <a class="btn btn-success d-grid mt-2" href="{{ url('/forgot-password') }}">Back</a>
                                            </div>
                                        </div>

									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- CONTAINER CLOSED -->

                    <!-- End Reset Password -->

				</div>
			</div>
			<!--END PAGE -->

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
                $('#forgot-password-form').validate({
                    ignore: "",
                    //errorElement: 'div',
                    //errorClass: "invalid-feedback",
                    rules: {
                        email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {

                    },
                    submitHandler: function (form) {
                        form.submit();
	                }

                });

                $(document).on('keyup','#email,#otp',function(){
                    $(".errorMsg").html('');
                    $("label.text-success").remove('');
                });

                $(document).on('click','.forgot_pass_btn',function(){
                    var _this = $(this);
	             	if($("#forgot-password-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{url("forgot-password")}}',
                            type: "POST",
                            data:  $('#forgot-password-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Submit');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == "success")
                                {
                                    $('#user_id').val(data.data.user_id);
                                    $(".forgot-pass-section").addClass('d-none');
                                    $(".otp-verification-forgot-section").removeClass('d-none');
                                    if(data.data.otp)
                                    {
                                        $("#otp").val(data.data.otp);
                                    }
                                    $("#otp").after('<label id="otp-error" class="text-success" for="otp">'+data.data.msg+'</label>');
                                } else if(data.status == "error") {

                                    if(data.errors)
                                    {
                                        $.each(data.errors,function(key,val) {
                                            $("#"+key).after('<label id="'+key+'-error" class="error" for="'+key+'">'+val+'</label>');
                                        })
                                    }
                                } else {
                                    $(".errorMsg").html('');
                                    if(data.error_class == "success")
                                    {
                                        $(".errorMsg").removeClass('error');
                                        $(".errorMsg").addClass('text-success');

                                    }
                                    else
                                    {
                                        $(".errorMsg").addClass('error');
                                        $(".errorMsg").removeClass('text-success');
                                    }
                                    $(".errorMsg").html(data.msg);

                                    // $("#email").after('<label id="email-error" class="error" for="email" style>'+data.msg+'</label>');
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
                    $("label.text-success").remove('');
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
                });

                // Resend OTP
                $(document).on('click', '.resend_code_btn', function() {
	             	var _this = $(this);
                    var user_id = $("#user_id").val();
                    $("#otp").val('');

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

                $(document).on('click', '.verify_otp_btn', function() {
	             	var _this = $(this);
	             	if($("#verify-otp-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{url("verify-forgot-otp")}}',
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
                                    $('#reset_email').val(data.email);
                                    $('#reset_token').val(data.token);
                                    $(".forgot-pass-section").addClass('d-none');
                                    $(".otp-verification-forgot-section").addClass('d-none');
                                    $(".reset-password-section").removeClass('d-none');

                                } else {
                                    $('label.error').remove();
                                    $("#otp").after('<label id="otp-error" class="error" for="otp">Invalid OTP.</label>');
                                }
                            }
                        });

                    }
                });


                // Reset Password Validation
                $('#reset-password-form').validate({
                    ignore: "",
                    //errorElement: 'div',
                    //errorClass: "invalid-feedback",
                    rules: {
                        password: {
	                    	required: true,
	                        minlength: 6,
	                    },
	                    cpassword: {
	                    	required: true,
	                        minlength: 6,
	                        equalTo: "#password",
	                    },
                    },
                    messages: {

                    },
                    submitHandler: function (form) {
                        form.submit();
	                }

                });

                $(document).on('click', '.reset_submit_btn', function() {
	             	var _this = $(this);
	             	if($("#reset-password-form").valid()) {
                        _this.prop('disabled', true).text('Processing...');
                        $.ajax({
                            url: '{{url("reset-password")}}',
                            type: "POST",
                            data:  $('#reset-password-form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                _this.prop('disabled', false).html('Submit');
                                try {
                                    data = JSON.parse(data);
                                } catch(e){}
                                if(data.status == 'true')
                                {
                                    $.growl.notice({
                                        title: "Success",
                                        message: "Your password reset successfully."
                                    });
                                    location = "{{url('/login')}}";
                                } else {
                                    $('.resetMsg').html('');
                                    $('.resetMsg').html(data.msg);
                                }
                            }
                        });
                    }
                });

                $(document).on('keyup','.reset_password',function(){
                    $(".resetMsg").html('');
                });

            }); // Ready
        </script>
    </body>
</html>
