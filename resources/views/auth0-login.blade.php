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
								DASHBOARD
									</span>

                                    <label class="label-margin" for="email">Email <span class="text-danger"> *</span> </label>
									<div class="wrap-input100 validate-input">
										<input class="input100 @error('email') is-invalid @enderror" type="text" name="email" value={{$email}} readonly>
										<span class="focus-input100"></span>
										<span class="symbol-input100">
											<i class="zmdi zmdi-email" aria-hidden="true"></i>
										</span>
									</div>
                                    <div class="error-email"></div>
                                    @error('email')
                                        <div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4 " role="alert">{{ $message }}</div>
                                    @enderror

                                  

								
									<div class="container-login100-form-btn">
										{{-- <button href="{{url('admin/dashboard')}}" class="login100-form-btn btn-primary">
											Login
										</button> --}}
										<a href="javascript:void(0)" class="login100-form-btn btn-success customer-login-btn" Style="font-weight:bold;width:150px;border-radius:50px">
											Dashboard 
                                        </a>

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

//  code for sign out nd login 
$(document).on('click', '.customer-login-btn', function() {
    var _this = $(this);
    
    if ($("#login-form").valid()) { 
        _this.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: '{{url("auth-login")}}',  
            type: "POST",
            data: $('#login-form').serialize(),  // Only email will be submitted now
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                _this.prop('disabled', false).html('Signup other Account');  // Reset button state
                
                try {
                    data = JSON.parse(data);  // Parse the JSON response
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }

                if (data.status == "success") {
                    // If login is successful, redirect to the dashboard
                  //  location = data.redirect;
                  location = '{{url("dashboard")}}';
                } else if (data.status == "error") {
                    // If there's an error with form submission, display the error messages
                    $('label.error').remove();  // Remove existing errors
                    
                    if (data.errors) {
                        $.each(data.errors, function(key, val) {
                            $("#" + key).after('<label id="' + key + '-error" class="error" for="' + key + '">' + val + '</label>');
                        });
                    } else if (data.msg) {
                        $(".error-email").before('<label id="email-error" class="error" for="email">' + data.msg + '</label>');
                    }
                } else if (data.status == "logout") {
                    // If email not found, redirect to Auth0 logout page
                    location = data.logoutUrl;
                    
                } else {
                    // Fallback for any other unexpected statuses
                    $(".error-email").before('<label id="email-error" class="error" for="email">Unexpected error occurred. Please try again.</label>');
                }
            },
            error: function(xhr, status, error) {
                // Handle any unexpected AJAX errors
                _this.prop('disabled', false).html('Login');
                console.error('AJAX Error:', status, error);
                $(".error-email").before('<label id="email-error" class="error" for="email">Something went wrong. Please try again.</label>');
            }
        });
    }
});
 


                 

               

            });
        </script>
	</body>
</html>
