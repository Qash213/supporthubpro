<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.admin.styles')

    @if (setting('GOOGLE_ANALYTICS_ENABLE') == 'yes')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('GOOGLE_ANALYTICS') }}"></script>
        <script>
            $(function() {
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '{{ setting('GOOGLE_ANALYTICS') }}');
            })
        </script>
    @endif

</head>

<body
    class="app sidebar-mini
	{{ getIsRtl() }}
	@if (setting('SPRUKOADMIN_P') == 'off') @if (setting('DARK_MODE') == 1) dark-mode @endif
@else
@if (Auth::check() && Auth::user()->darkmode == 1) dark-mode @endif

	@endif
	@if (setting('sidemenu_icon_style') == 'on') icon-overlay sidenav-toggled @endif
	">



    <div class="page">
        <div class="page-main">
            @include('includes.admin.verticalmenu')
            <div class="app-content main-content">
                <div class="side-app">
                    @include('includes.admin.menu')

                    @if (setting('MAINTENANCE_MODE') == 'on')

                        <div class="alert alert-danger sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-hourglass-half fa-spin me-2 fs-15" aria-hidden="true"></i>
                            {{ lang('This application is in maintenance mode. We are performing scheduled maintenance.') }}
                        </div>

                    @endif

                    @if (setting('mail_host') == 'smtp.mailtrap.io' && Auth::user()->getRoleNames()[0] == 'superadmin')
                        <div class="alert alert-warning sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                            {{ lang('It is necessary to set up your email settings first in order to send and receive emails.') }}
                            <div class="">
                                <a href="{{ route('email.setting.alert') }}" class="btn btn-dark btn-sm mt-2"
                                    target="_blank"> <i class="fa fa-cogs me-2 fs-15"
                                        aria-hidden="true"></i>{{ lang('Email Setup') }} </a>
                                <a href="https://youtu.be/2jwH9P9-R4E" class="btn btn-dark btn-sm mt-2" target="_blank">
                                    <i class="fa fa-link me-2 fs-15"
                                        aria-hidden="true"></i>{{ lang('Setup Reference ') }}</a>
                            </div>
                        </div>
                    @endif

                    @php
                        $cronset = \App\Models\Setting::where('key', 'cronjob_set')->first();
                        $cronworking = $cronset->updated_at->addDay(1) >= \Carbon\Carbon::now();
                    @endphp

                    @if ($cronworking != true || $cronset->value == 'installed')
                        @if (Auth::user()->getRoleNames()[0] == 'superadmin')
                            <div class="alert alert-info sprukoclosebtn mt-5 fs-15">
                                <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                                {{ lang('It is necessary to set up your cron job first in order for the auto functions to work.') }}
                                <div class="">
                                    <a href="https://youtu.be/uqkZsQdU_TE" class="btn btn-dark btn-sm mt-2"
                                        target="_blank"> <i class="fa fa-link me-2 fs-15"
                                            aria-hidden="true"></i>{{ lang('Setup Reference ') }}</a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="modal effect-scale fade" id="adminautologout" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-body px-6 py-5 text-center">
                                    <div class="mb-5 text-center">
                                        <div class="mb-5">
                                            <span class="avatar avatar-xl brround bg-warning-transparent"><i
                                                    class="ri-alert-line fw-normal"></i></span>
                                        </div>
                                        <div>
                                            <h4 class="mb-1">{{lang('Session Timeout')}}</h4>
                                            <p class="fw-semibold mb-1">
                                                {{ lang('You have been inactive since last') }}
                                                {{ setting('admin_users_inactive_auto_logout_time') }}{{ lang(' minutes. Do you wish to stay?') }}
                                            </p>


                                            <span
                                                class="d-block text-muted fw-normal">{{ lang('Your session will be timed out in') }}
                                                <h3 class="countdown mb-0"></h3><span>{{ lang('seconds') }}</span>


                                        </div>
                                    </div>
                                    <button
                                        class="btn btn-primary w-lg adminstayin">{{ lang('Stay Signed In') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('content')

                </div>
            </div><!-- end app-content-->
        </div>
        @include('includes.admin.footer')

    </div>

    @include('includes.admin.scripts')

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
    @elseif(Session::has('adminreplied'))
        <script>
            toastr.success("{!! Session::get('adminreplied') !!}");
        </script>
        @php
            Session::pull('adminreplied', 'The response to the ticket was successful.');
        @endphp
    @endif

    @yield('modal')

</body>

</html>
