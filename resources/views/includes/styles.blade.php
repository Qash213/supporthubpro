		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="{{$seopage->description ? $seopage->description :''}}" name="description">
		<meta content="{{$seopage->author ? $seopage->author :''}}" name="author">
		<meta name="keywords" content="{{$seopage->keywords ? $seopage->keywords :''}}" />
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- Title -->
		<title>{{$title->title}}</title>

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
        @vite(['resources/assets/custom-theme/skin-modes.scss'])

		<!-- Animate css -->
        @vite(['resources/assets/custom-theme/custom/animated.css'])

		<!-- P-scroll bar css-->
		<link href="{{asset('build/assets/plugins/p-scrollbar/p-scrollbar.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{asset('build/assets/plugins/icons/icons.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="{{asset('build/assets/plugins/select2/select2.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="{{asset('build/assets/plugins/toastr/toastr.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		@yield('styles')

		<!-- Color Setting -->
		<style>
			:root {
				--primary: @php echo setting('theme_color') @endphp;
				--secondary: @php echo setting('theme_color_dark') @endphp;
			}
		</style>

		<!-- Custom css -->
		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		@if(setting('GOOGLEFONT_DISABLE') == 'off')

		<!-- Google Fonts -->
		<style>

			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

		</style>

		@endif

		<!-- Jquery js-->
		<script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}?v=<?php echo time(); ?>"></script>
