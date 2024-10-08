<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
		@if(Auth::guard('customer')->check())
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null)
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1) dark-mode @endif
			@endif
		@else
			@if(setting('DARK_MODE') == 1) dark-mode @endif
		@endif
	@endif
		">

				@include('includes.user.mobileheader')

				@include('includes.user.menu')

				<div class="page">
					<div class="page-main">

						<div class="modal effect-scale fade"  id="customerautologout" data-bs-backdrop="static">
							<div class="modal-dialog modal-dialog-centered text-center" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-body px-6 py-5 text-center">
										<div class="mb-5 text-center">
											<div class="mb-5">
												<span class="avatar avatar-xl brround bg-warning-transparent"><i class="ri-alert-line fw-normal"></i></span>
											</div>
											<div>
                                                <h4 class="mb-1">{{lang('Session Timeout')}}</h4>
												<p class="fw-semibold mb-1">{{lang('You have been inactive since last')}} {{setting('customer_inactive_auto_logout_time')}}{{lang(' minutes. Do you wish to stay?')}} </p>


												<span class="d-block text-muted fw-normal">{{lang('Your session will be timed out in')}} <h3 class="countdown mb-0"></h3><span>{{lang('seconds')}}</span>


											</div>
										</div>
										<button class="btn btn-primary w-lg clientstayin">{{lang('Stay Signed In')}}</button>
									</div>
								</div>
							</div>
						</div>

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
