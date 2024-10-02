<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="{{$seopage->description ? $seopage->description :''}}" name="description">
		<meta content="{{$seopage->author ? $seopage->author :''}}" name="author">
		<meta name="keywords" content="{{$seopage->keywords ? $seopage->keywords :''}}"/>
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

		@if(getIsRtl() == 'rtl')

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

		<!---Icons css-->
		<link href="{{asset('build/assets/plugins/icons/icons.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="{{asset('build/assets/plugins/toastr/toastr.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Jquery js-->
		<script src="{{asset('build/assets/plugins/jquery/jquery.min.js')}}?v=<?php echo time(); ?>"></script>

		@yield('styles')

		<style>
			:root {
		--primary:@php echo setting('theme_color') @endphp;
		--secondary:@php echo setting('theme_color_dark') @endphp;
			}

		</style>

		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		@if(setting('GOOGLEFONT_DISABLE') == 'off')

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

		</style>

		@endif

		@if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes')
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={{setting('GOOGLE_ANALYTICS')}}"></script>
		<script>
            $(function() {
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{setting('GOOGLE_ANALYTICS')}}');
            })
		</script>
		@endif

	</head>

	<body class="@if(setting('DARK_MODE') == 1) dark-mode @endif {{getIsRtl()}}">
        <div class="uhelp-announcement-alertgroup">
            @if($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on')
                @foreach ($holidays as $anct)
                    @if ($anct->status == 1)
                        <div class="alert alert-holiday" role="alert" style="background: {{$anct->primaray_color}}; border-color: {{$anct->primaray_color}}; color:{{$anct->secondary_color}};">
                            <div class="container">
                                <button type="submit" class="btn-close ms-5 float-end notifyclose" style="color:{{$anct->secondary_color}};" data-id="{{$anct->id}}">×</button>
                                <div class="d-flex align-items-top">
                                    <div class="uhelp-announcement me-2 svg-icon" style="background: {{ str_replace(', 1)', ', 0.1)', $anct->secondary_color) }}; color:{{$anct->secondary_color}};">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"  fill="currentColor"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 10H4V19H20V10ZM15.0355 11.136L16.4497 12.5503L11.5 17.5L7.96447 13.9645L9.37868 12.5503L11.5 14.6716L15.0355 11.136ZM7 5H4V8H20V5H17V6H15V5H9V6H7V5Z"></path></svg>
                                </div>
                                <div class="d-flex align-items-top">
                                    <div class="notice-heading d-flex align-items-top flex-fill">
                                        <div>
                                            <div class="fs-18 font-weight-bold holiday-title flex-fill" style="color:{{$anct->secondary_color}};">{{$anct->occasion}}<span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                            <div class="mb-0  uhelp-alert-content alert-notice">{!!$anct->holidaydescription!!}
                                                @if($anct->buttonon == 1)
                                                <a class="btn btn-sm ms-2  text-decoration-underline" href="{{$anct->buttonurl}}" target="_blank">{{$anct->buttonname}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            @if(setting('ANNOUNCEMENT_USER') == 'non_login_users' || setting('ANNOUNCEMENT_USER') == 'all_users')
                @foreach ($announcement as $anct)
                    @if ($anct->status == 1)
                        <div class="alert alert-announcement" role="alert" style="background: {{$anct->primary_color}}; color:{{$anct->secondary_color}};">
                            <div class="container">
                                <button type="submit" class="btn-close ms-5 float-end  notifyclose" style="color:{{$anct->secondary_color}};" data-id="{{$anct->id}}">×</button>
                                <div class="d-flex align-items-top">
                                    <div class="uhelp-announcement lh-1 svg-icon flex-shrink-0 me-2" style="background: {{ str_replace(', 1)', ', 0.1)', $anct->secondary_color) }}; color:{{$anct->secondary_color}};">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                    </div>
                                    <div class="d-flex align-items-top">
                                        <div class="notice-heading d-flex align-items-top flex-fill">
                                            <div>
                                                <div class="fs-18 font-weight-bold  flex-fill" style="color:{{$anct->secondary_color}};">{{$anct->title}}<span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                                <div class="mb-0  uhelp-alert-content alert-notice">{!!$anct->notice!!}
                                                    @if($anct->buttonon == 1)
                                                        <a class="btn btn-sm ms-2  text-decoration-underline" href="{{$anct->buttonurl}}" target="_blank">{{$anct->buttonname}}</a>
                                                        @endif
                                                        @if ($anct->buttonlable != null && $anct->buttonurl != null)
                                                        <div class="ms-auto mt-auto">
                                                            <a href="{{ $anct->buttonurl }}" target="_blank" class="btn btn-info mt-2 btn-sm">{{ $anct->buttonlable }}<i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @foreach ($announcements as $ancts)
                    @php
                    $announceDay = explode(',', $ancts->announcementday);
                    $now = today()->format('D');

                    @endphp
                    @foreach ($announceDay as $announceDays)
                        @if ($ancts->status == 1 && $announceDays == $now)
                            <div class="alert alert-announcement" role="alert" style="background: {{$ancts->primary_color}}; color:{{$ancts->secondary_color}};">
                                <div class="container">
                                    <button type="submit" class="btn-close ms-5 float-end  notifyclose" style="color:{{$ancts->secondary_color}};" data-id="{{$ancts->id}}">×</button>
                                    <div class="d-flex align-items-top">
                                        <div class="uhelp-announcement lh-1 svg-icon flex-shrink-0 me-2" style="background: {{ str_replace(', 1)', ', 0.1)', $ancts->secondary_color) }}; color:{{$ancts->secondary_color}};">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        </div>
                                        <div class="d-flex align-items-top">
                                            <div class="notice-heading d-flex align-items-top flex-fill">
                                                <div>
                                                    <div class="fs-18 font-weight-bold  flex-fill" style="color:{{$ancts->secondary_color}};">{{$ancts->title}}<span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                                    <div class="mb-0  uhelp-alert-content alert-notice">{!!$ancts->notice!!}
                                                        @if($ancts->buttonon == 1)
                                                        <a class="btn btn-sm ms-2  text-decoration-underline" href="{{$ancts->buttonurl}}" target="_blank">{{$ancts->buttonname}}</a>
                                                        @endif
                                                        @if ($ancts->buttonlable != null && $ancts->buttonurl != null)
                                                            <div class="ms-auto mt-auto">
                                                                <a href="{{ $ancts->buttonurl }}" target="_blank" class="btn btn-info mt-2 btn-sm">{{ $ancts->buttonlable }}<i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            @endif
        </div>

		<div class="page login-bg1">
            <div class="page-single">
                <div class="container">
                    <div class="row justify-content-center py-4">
                        <div class="col-sm-12">
                            <div class="card authentication-card py-5 mx-auto">
                                <div class="pt-0 pb-1 text-center">

                                    <a class="header-brand ms-0" href="{{url('/')}}">
                                        @if ($title->image !== null)

                                        <img src="{{asset('uploads/logo/logo/'.$title->image)}}" class="header-brand-img custom-logo-dark"
                                            alt="{{$title->image}}">
                                        @else
                                        <img src="{{asset('uploads/logo/logo/logo-white.png')}}" class="header-brand-img custom-logo-dark"
                                            alt="logo">
                                        @endif
                                        @if ($title->image1 !== null)

                                            <img src="{{asset('uploads/logo/darklogo/'.$title->image1)}}" class="header-brand-img custom-logo"
                                            alt="{{$title->image1}}">
                                        @else

                                        <img src="{{asset('uploads/logo/darklogo/logo.png')}}" class="header-brand-img custom-logo"
                                            alt="logo">

                                        @endif

                                    </a>
                                </div>

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


		<!-- Bootstrap4 js-->
		<script src="{{asset('build/assets/plugins/bootstrap/popper.min.js')}}?v=<?php echo time(); ?>"></script>
		<script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}?v=<?php echo time(); ?>"></script>

		<script>

			@php echo customcssjs('CUSTOMJS') @endphp
		</script>



		<!--INTERNAL Toastr js -->
		<script src="{{asset('build/assets/plugins/toastr/toastr.min.js')}}?v=<?php echo time(); ?>"></script>

        <script type="text/javascript">
            $(function() {
                "use strict";
                (function($){
                    let notifyClose = document.querySelectorAll('.notifyclose');
                    notifyClose.forEach(ele => {
                    if(ele){
                    let id = ele.getAttribute('data-id');
                    if(getCookie(id)){
                    ele.closest('.alert').classList.add('d-none');
                    }else{
                    ele.addEventListener('click', setCookie);
                    }
                    }
                    })


                    function setCookie($event) {
                    const d = new Date();
                    let id = $event.currentTarget.getAttribute('data-id');
                    d.setTime(d.getTime() + (30 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = id + "=" + 'announcement_close' + ";" + expires + ";path=/";
                    $event.currentTarget.closest('.alert').classList.add('d-none');
                    }

                    function getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                    }
                    return '';
                    }
                })(jQuery);
            })
        </script>

			@if (Session::has('error'))
				<script>
					toastr.error("{!! Session::get('error') !!}");
				</script>
			@elseif(Session::has('success'))
				<script>
					toastr.success("{!! Session::get('success') !!}");
				</script>
			@elseif(Session::has('info'))
				<script>
					toastr.info("{!! Session::get('info') !!}");
				</script>
			@elseif(Session::has('warning'))
				<script>
					toastr.warning("{!! Session::get('warning') !!}");
				</script>
			@endif
			@yield('scripts')

			@yield('modal')
	</body>
</html>
