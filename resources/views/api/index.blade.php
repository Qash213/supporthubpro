<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<!-- Title -->
		<title>{{lang('Purchase Code Verification')}}</title>

		<!--Favicon -->
		<link rel="icon" href="{{asset('build/assets/images/brand/favicon.ico')}}" type="image/x-icon"/>

		<!-- Bootstrap css -->
		<link href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Style css -->
        @vite(['resources/sass/app.scss'])
        @vite(['resources/assets/updatestyle/updatestyles.scss'])
        @vite(['resources/assets/custom-theme/dark.scss'])

		<!-- Animate css -->
        @vite(['resources/assets/custom-theme/custom/animated.css'])

		<!---Icons css-->
        <link href="{{asset('build/assets/plugins/icons/icons.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="{{asset('build/assets/plugins/select2/select2.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- P-scroll bar css-->
		<link href="{{asset('build/assets/plugins/p-scrollbar/p-scrollbar.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		@if(setting('GOOGLEFONT_DISABLE') == 'off')
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
		</style>
		@endif

	</head>

	<body>

		<div class="page error-bg">
			<div class="page-content m-0">
				<div class="container text-center">
					<div class="alert alert-danger">
						{{lang('License code is already installed on maximum allowed product instance.')}}
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}?v=<?php echo time(); ?>"></script>

		<!-- Bootstrap4 js-->
		<script src="{{asset('build/assets/plugins/bootstrap/popper.min.js')}}?v=<?php echo time(); ?>"></script>
		<script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}?v=<?php echo time(); ?>"></script>

		<!-- Select2 js -->
		<script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}?v=<?php echo time(); ?>"></script>

		<!-- P-scroll js-->
		<script src="{{asset('build/assets/plugins/p-scrollbar/p-scrollbar.js')}}?v=<?php echo time(); ?>"></script>

		<!-- Custom js-->
        @vite(['resources/assets/js/custom.js'])

	</body>
</html>
