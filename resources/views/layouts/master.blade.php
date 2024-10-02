<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
	<head>
    	@include('includes.styles')


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

    <body class="{{getIsRtl()}}
    @if(setting('SPRUKOADMIN_C') == 'off')
    @if(setting('DARK_MODE') == 1) dark-mode @endif
    @else
            @if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null)
    @if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1) dark-mode @endif
    @else
    @if(setting('DARK_MODE') == 1) dark-mode @endif
    @endif
    @endif">
    <div id="sticky-wrapper" class="sticky-wrapper">
        <div class="uhelp-announcement-alertgroup clearfix sticky">
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

            @if(setting('ANNOUNCEMENT_USER') == 'non_login_users' && Auth::guard('customer')->check() == false || setting('ANNOUNCEMENT_USER') == 'all_users')

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
            @else
                @if(setting('ANNOUNCEMENT_USER') == 'only_login_user' && Auth::guard('customer')->check() == true)
                    @foreach ($announcement as $anct)
                        @if ($anct->status == 1)
                            <div class="alert" role="alert" style="background: {{$anct->primary_color}}; color:{{$anct->secondary_color}};">
                                <div class="container">
                                    <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" style="color:{{$anct->secondary_color}};" data-id="{{$anct->id}}">×</button>
                                    <div class="d-flex align-items-top">
                                        <div class="uhelp-announcement lh-1 svg-icon flex-shrink-0 me-2" style="background: {{ str_replace(', 1)', ', 0.1)', $anct->secondary_color) }}; color:{{$anct->secondary_color}};">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        </div>
                                        <div class="text-default d-flex align-items-top">
                                            <div class="notice-heading d-flex align-items-top flex-fill">
                                                <div>
                                                    <div class="fs-18 font-weight-bold text-white flex-fill" style="color:{{$anct->secondary_color}};">{{$anct->title}}<span class="text-white opacity-50 mx-2"><i class="ti ti-minus"></i></span>
                                                    </div>

                                                    <div class="mb-0 text-white uhelp-alert-content alert-notice">{!!$anct->notice!!}
                                                        @if($anct->buttonon == 1)
                                                        <a class="btn btn-sm ms-2 text-white text-decoration-underline" href="{{$anct->buttonurl}}" target="_blank">{{$anct->buttonname}}</a>
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
                                <div class="alert alert-days" role="alert" style="background: linear-gradient(to right, {{$ancts->primary_color}}, {{$ancts->secondary_color}});">
                                    <div class="container">
                                            <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" data-id="{{$ancts->id}}">×</button>
                                            <div class="d-flex align-items-top">
                                                <div class="uhelp-announcement me-2">
                                                    <svg class="svg-info" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                                </div>
                                                <div class="text-default d-flex align-items-top">
                                                    <div class="notice-heading d-flex align-items-top flex-fill">
                                                        <div>
                                                            <div class="fs-18 font-weight-bold text-white flex-fill" style="color:{{$anct->secondary_color}};">{{$ancts->title}}<span class="text-white opacity-50 mx-2"><i class="ti ti-minus"></i></span>
                                                            </div>

                                                            <div class="mb-0 text-white uhelp-alert-content alert-notice">{!!$ancts->notice!!}
                                                                @if($ancts->buttonon == 1)
                                                                <a class="btn btn-sm ms-2 text-white text-decoration-underline" href="{{$ancts->buttonurl}}" target="_blank">{{$ancts->buttonname}}</a>
                                                                @endif
                                                            </div>
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
            @endif
        </div>
    </div>

				@include('includes.user.mobileheader')

				@include('includes.menu')

				<div class="page page-1">
					<div class="page-main">

							@yield('content')

					</div>
				</div>

				@include('includes.footer')

    	@include('includes.scripts')

		@guest
		@if (customcssjs('CUSTOMCHATENABLE') == 'enable')
		@if (customcssjs('CUSTOMCHATUSER') == 'public')
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
		@else
		@if (customcssjs('CUSTOMCHATENABLE') == 'enable')
		@if (customcssjs('CUSTOMCHATUSER') == 'user')
		@if (Auth::guard('customer')->check() && Auth::guard('customer')->user())
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
		@if (customcssjs('CUSTOMCHATUSER') == 'public')
		@php echo customcssjs('CUSTOMCHAT') @endphp
		@endif
		@endif
	@endguest
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

			@if(setting('REGISTER_POPUP') == 'yes')
			@if(!Auth::guard('customer')->check())

			@include('user.auth.modalspopup.register')

			@include('user.auth.modalspopup.login')

			@include('user.auth.modalspopup.forgotpassword')

			@endif
			@endif

			@if(setting('GUEST_TICKET') == 'yes')

				@include('guestticket.guestmodal')

			@endif

			@yield('modal')

	</body>
</html>
