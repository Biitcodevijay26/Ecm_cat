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
					<div class="container-login100">
						<div class="wrap-login100 p-0">
							<div class="card-body">
								<form class="login100-form validate-form" action="{{ url('admin-login') }}" method="POST" id="admin-login-form">
									@csrf
									<span class="login100-form-title" Style="font-weight:bold">
										SUPER ADMIN LOGIN
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
										<div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">{{ $message }}</div>
									@enderror

                                    <label class="label-margin mt-2" for="password">Password <span class="text-danger"> *</span> </label>
                                    <div class="wrap-input100 validate-input mt-2">
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

									{{-- <div class="text-end pt-1">
										<p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a></p>
									</div> --}}
									<div class="container-login100-form-btn">
										<button href="{{url('admin/dashboard')}}" class="login100-form-btn btn-success" Style="font-weight:bold;width:150px;border-radius:50px">
										<img src="{{ url('theme-asset/images/brand/logo1.png') }}" width="20" height="20" Style="margin-right: 10px">
										LOGIN
				                       </button>
									</div>
									{{-- <div class="text-center pt-3">
										<p class="text-dark mb-0">Not a member?<a href="register.html" class="text-primary ms-1">Create an Account</a></p>
									</div> --}}
								</form>
							</div>
							{{-- <div class="card-footer">
								<div class="d-flex justify-content-center my-3">
									<a href="" class="social-login  text-center me-4">
										<i class="fa fa-google"></i>
									</a>
									<a href="" class="social-login  text-center me-4">
										<i class="fa fa-facebook"></i>
									</a>
									<a href="" class="social-login  text-center">
										<i class="fa fa-twitter"></i>
									</a>
								</div>
							</div> --}}
						</div>
					</div>
					<div class="text-center">
				        <img src="{{ url('theme-asset/images/brand/logo2.png') }}" class="header-brand-img" alt="">
			        </div>
					<!-- CONTAINER CLOSED -->
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

                 $('#admin-login-form').validate({
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
             });
         </script>

	</body>
</html>
