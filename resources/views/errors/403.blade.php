<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<!-- Title -->
		<title>{{lang('Forbidden')}}</title>

		@php
			use App\Models\Apptitle;

			$title = Apptitle::first();
		@endphp

		@if ($title->image4 == null)

		<!--Favicon -->
		<link rel="icon" href="{{asset('uploads/logo/favicons/favicon.ico')}}" type="image/x-icon"/>
		@else

		<!--Favicon -->
		<link rel="icon" href="{{asset('uploads/logo/favicons/'.$title->image4)}}" type="image/x-icon"/>
		@endif

		@if(str_replace('_', '-', app()->getLocale()) == 'عربى')

		<!-- Bootstrap css -->
		<link href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.rtl.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
		@else

		<!-- Bootstrap css -->
		<link href="{{asset('build/assets/plugins/bootstrap/css/bootstrap.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
		@endif

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

	<body class="@if(str_replace('_', '-', app()->getLocale()) == 'عربى')
		rtl
	@endif
@if(setting('DARK_MODE') == 1) dark-mode @endif">

		<!--Row-->
		<div class="page error-bg">
			<div class="page-content m-0">
				<div class="container text-center">
					<div class="display-1 text-danger mb-5 font-weight-bold"><i class="fa fa-ban" aria-hidden="true"></i></div>
					<h1 class="h3  mb-3 font-weight-semibold">{{lang('Access Denied', 'errorpages')}}</h1>
					<p class="h5 font-weight-normal mb-7 leading-normal">{{lang('It Seems Like You Are Not Authorized To Access This Page', 'errorpages')}}</p>
				</div>
			</div>
		</div>
		<!--Row-->

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
