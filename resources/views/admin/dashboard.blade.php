@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAL Data table css -->
    <link href="{{ asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/buttonbootstrap.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />

    <style>
        .table-responsive.delete-button {
            min-height: 150px;
        }

        .uhelp-reply-badge {
            inset-inline-end: 14px;
            bottom: 10px;
            z-index: 1;
        }

        .pulse-badge {
            animation: pulse 1s linear infinite;
        }

        .pulse-badge.disabled {
            color: #b5c0df;
            animation: none;
        }

        @-webkit-keyframes pulse {
            0% {
                color: rgba(13, 205, 148, 0);
            }

            50% {
                color: rgba(13, 205, 148, 1);
            }

            100% {
                color: rgba(13, 205, 148, 0);
            }
        }

        @keyframes pulse {
            0% {
                -moz-color: rgba(13, 205, 148, 0);
                color: rgba(13, 205, 148, 0);
            }

            50% {
                -moz-color: rgba(13, 205, 148, 1);
                color: rgba(13, 205, 148, 1);
            }

            100% {
                -moz-color: rgba(13, 205, 148, 0);
                color: rgba(13, 205, 148, 0);
            }
        }
    </style>
@endsection

@section('content')


    <!--- Custom notification -->
    @php
        $mailnotify = auth()->user()->unreadNotifications()->where('data->status', 'mail')->get();

    @endphp
    @if ($mailnotify->isNotEmpty())
        <div class="alert alert-warning-light br-13 mt-6 align-items-center border-0 d-flex" role="alert">
            <div class="d-flex">
                <svg class="alt-notify  me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="#eec466"
                        d="M19,20H5a3.00328,3.00328,0,0,1-3-3V7A3.00328,3.00328,0,0,1,5,4H19a3.00328,3.00328,0,0,1,3,3V17A3.00328,3.00328,0,0,1,19,20Z" />
                    <path fill="#e49e00"
                        d="M22,7a3.00328,3.00328,0,0,0-3-3H5A3.00328,3.00328,0,0,0,2,7V8.061l9.47852,5.79248a1.00149,1.00149,0,0,0,1.043,0L22,8.061Z" />
                </svg>
            </div>
            <ul class="notify vertical-scroll5 custom-ul ht-0 me-5">
                @if (auth()->user())
                    @forelse($mailnotify as $notification)
                        @if ($notification->data['status'] == 'mail')
                            <li class="item">
                                <p class="fs-13 mb-0">{{ $notification->data['mailsubject'] }}
                                    {{ Str::limit($notification->data['mailtext'], '400', '...') }} <a
                                        href="{{ route('admin.notiication.view', $notification->id) }}"
                                        class="ms-3 text-blue mark-as-read">{{ lang('Read more') }}</a></p>
                            </li>
                        @endif
                    @empty
                    @endforelse
                @endif
            </ul>
            <div class="d-flex ms-6 sprukocnotify">
                <button class="btn-close ms-2 mt-0 text-warning" data-bs-dismiss="alert" aria-hidden="true">×</button>
            </div>
        </div>
    @endif
    <!--- End Custom notification -->

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Dashboard', 'menu') }}</span>
            </h4>
        </div>
        <div class="page-rightheader ms-md-auto">
            <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                <div class="d-flex breadcrumb-res">
                    <div class="header-datepicker me-3">
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="feather feather-calendar"></i>
                            </div>
                            <span
                                class="form-control fc-datepicker pb-0 pt-1">{{ now(setting('default_timezone'))->format(setting('date_format')) }}</span>
                        </div>
                    </div>
                    <div class="header-datepicker picker2 me-3">
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="feather feather-clock"></i>
                            </div><!-- input-group-text -->
                            <span id="tpBasic" placeholder="" class="form-control input-small pb-0 pt-1">

                                {{ \Carbon\Carbon::now(setting('default_timezone'))->format(setting('time_format')) }}

                            </span>
                        </div>
                    </div><!-- wd-150 -->
                </div>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!--Dashboard List-->

    {{-- <div class="row"> --}}
    {{-- <div class="col-xl-4"> --}}
    <h6 class="mb-3 fw-semibold">{{ lang('General Tickets') }}</h6>
    <div class="row row-cols-xxl-5">
        <div class="col-xxl-2 col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.recenttickets') }}">
                        <div class="d-flex">
                            <span class="me-3 my-auto">
                                <svg class="tickets-recent primary" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 60 60" viewBox="0 0 60 60">
                                    <path
                                        d="M54,15H6c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1c1.6542969,0,3,1.3457031,3,3s-1.3457031,3-3,3
                                                                                c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1h48c0.5522461,0,1-0.4477539,1-1V34c0-0.5522461-0.4477539-1-1-1
                                                                                c-1.6542969,0-3-1.3457031-3-3s1.3457031-3,3-3c0.5522461,0,1-0.4477539,1-1V16C55,15.4477539,54.5522461,15,54,15z M53,25.1005859
                                                                                C50.7207031,25.5649414,49,27.5854492,49,30s1.7207031,4.4350586,4,4.8994141V43h-9.0371094h-2H7v-8.1005859
                                                                                C9.2792969,34.4350586,11,32.4145508,11,30s-1.7207031-4.4350586-4-4.8994141V17h34.9628906h2H53V25.1005859z">
                                    </path>
                                    <rect width="2" height="2" x="41.963" y="27"></rect>
                                    <rect width="2" height="2" x="41.963" y="31"></rect>
                                    <rect width="2" height="2" x="41.963" y="19"></rect>
                                    <rect width="2" height="2" x="41.963" y="35"></rect>
                                    <rect width="2" height="2" x="41.963" y="23"></rect>
                                    <rect width="2" height="2" x="41.963" y="39"></rect>
                                </svg>
                            </span>
                            <div class="">
                                <p class="fs-14 font-weight-semibold mb-0">{{ lang('Recents Tickets') }}
                                </p>
                                <h3 class="mb-0 text-primary">{{ $recentticketcount }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('/admin/activeticket') }}">
                        <div class="d-flex">
                            <span class="me-3 my-auto">
                                <svg class="tickets-recent secondary" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 60 60" viewBox="0 0 60 60">
                                    <path
                                        d="M54,15H6c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1c1.6542969,0,3,1.3457031,3,3s-1.3457031,3-3,3
                                                                                c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1h48c0.5522461,0,1-0.4477539,1-1V34c0-0.5522461-0.4477539-1-1-1
                                                                                c-1.6542969,0-3-1.3457031-3-3s1.3457031-3,3-3c0.5522461,0,1-0.4477539,1-1V16C55,15.4477539,54.5522461,15,54,15z M53,25.1005859
                                                                                C50.7207031,25.5649414,49,27.5854492,49,30s1.7207031,4.4350586,4,4.8994141V43h-9.0371094h-2H7v-8.1005859
                                                                                C9.2792969,34.4350586,11,32.4145508,11,30s-1.7207031-4.4350586-4-4.8994141V17h34.9628906h2H53V25.1005859z">
                                    </path>
                                    <rect width="2" height="2" x="41.963" y="27"></rect>
                                    <rect width="2" height="2" x="41.963" y="31"></rect>
                                    <rect width="2" height="2" x="41.963" y="19"></rect>
                                    <rect width="2" height="2" x="41.963" y="35"></rect>
                                    <rect width="2" height="2" x="41.963" y="23"></rect>
                                    <rect width="2" height="2" x="41.963" y="39"></rect>
                                </svg>
                            </span>
                            <div>
                                <p class="fs-14 font-weight-semibold mb-0">{{ lang('Unassigned Tickets') }}</p>
                                <h3 class="mb-0 text-secondary">{{ $totalactivetickets }}</h3>
                                @if ($totalactiverecent > 0)
                                    <span class="position-absolute uhelp-reply-badge pulse-badge" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>{{ $totalactiverecent }}</span>
                                @else
                                    <span class="position-absolute uhelp-reply-badge pulse-badge disabled"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>0</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        {{-- <h6 class="fw-esmibold mb-3">{{lang('Self Tickets')}}</h6> --}}
        <div class="col-xxl-2 col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.selfassignticketview') }}">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <svg class="ticket-new primary svg-primary" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 60 60" viewBox="0 0 60 60">
                                    <path
                                        d="M54,15H6c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1c1.6542969,0,3,1.3457031,3,3s-1.3457031,3-3,3
                          c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1h48c0.5522461,0,1-0.4477539,1-1V34c0-0.5522461-0.4477539-1-1-1
                          c-1.6542969,0-3-1.3457031-3-3s1.3457031-3,3-3c0.5522461,0,1-0.4477539,1-1V16C55,15.4477539,54.5522461,15,54,15z M53,25.1005859
                          C50.7207031,25.5649414,49,27.5854492,49,30s1.7207031,4.4350586,4,4.8994141V43h-9.0371094h-2H7v-8.1005859
                          C9.2792969,34.4350586,11,32.4145508,11,30s-1.7207031-4.4350586-4-4.8994141V17h34.9628906h2H53V25.1005859z">
                                    </path>
                                    <rect width="2" height="2" x="41.963" y="27"></rect>
                                    <rect width="2" height="2" x="41.963" y="31"></rect>
                                    <rect width="2" height="2" x="41.963" y="19"></rect>
                                    <rect width="2" height="2" x="41.963" y="35"></rect>
                                    <rect width="2" height="2" x="41.963" y="23"></rect>
                                    <rect width="2" height="2" x="41.963" y="39"></rect>
                                </svg>
                            </div>
                            <div>
                                <p class="fs-14 font-weight-semibold mb-1">
                                    {{ lang('Self assigned Tickets') }}</p>
                                <h5 class="mb-0">{{ $selfassigncount }}</h5>
                                @if ($selfassignrecentreply > 0)
                                    <span class="position-absolute uhelp-reply-badge pulse-badge" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>{{ $selfassignrecentreply }}</span>
                                @else
                                    <span class="position-absolute uhelp-reply-badge pulse-badge disabled"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>0</span>
                                @endif

                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('/admin/myassignedtickets') }}">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <svg class="ticket-new success svg-success" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 60 60" viewBox="0 0 60 60">
                                    <path
                                        d="M54,15H6c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1c1.6542969,0,3,1.3457031,3,3s-1.3457031,3-3,3
                          c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1h48c0.5522461,0,1-0.4477539,1-1V34c0-0.5522461-0.4477539-1-1-1
                          c-1.6542969,0-3-1.3457031-3-3s1.3457031-3,3-3c0.5522461,0,1-0.4477539,1-1V16C55,15.4477539,54.5522461,15,54,15z M53,25.1005859
                          C50.7207031,25.5649414,49,27.5854492,49,30s1.7207031,4.4350586,4,4.8994141V43h-9.0371094h-2H7v-8.1005859
                          C9.2792969,34.4350586,11,32.4145508,11,30s-1.7207031-4.4350586-4-4.8994141V17h34.9628906h2H53V25.1005859z">
                                    </path>
                                    <rect width="2" height="2" x="41.963" y="27"></rect>
                                    <rect width="2" height="2" x="41.963" y="31"></rect>
                                    <rect width="2" height="2" x="41.963" y="19"></rect>
                                    <rect width="2" height="2" x="41.963" y="35"></rect>
                                    <rect width="2" height="2" x="41.963" y="23"></rect>
                                    <rect width="2" height="2" x="41.963" y="39"></rect>
                                </svg>
                            </div>
                            <div>
                                <p class="fs-14 font-weight-semibold mb-1">
                                    {{ lang('My Assigned Tickets') }}</p>
                                <h5 class="mb-0">{{ $myassignedticketcount }}</h5>
                                @if ($myassignedticketrecentreply > 0)
                                    <span class="position-absolute uhelp-reply-badge pulse-badge" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>{{ $myassignedticketrecentreply }}</span>
                                @else
                                    <span class="position-absolute uhelp-reply-badge pulse-badge disabled"
                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Un-Answered"><i
                                            class="fa fa-commenting me-1"></i>0</span>
                                @endif

                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xxl-2 col-xl-6 col-lg-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.myclosedtickets') }}">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <svg class="ticket-new bg-danger-transparent svg-danger"
                                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 60 60"
                                    viewBox="0 0 60 60">
                                    <path
                                        d="M54,15H6c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1c1.6542969,0,3,1.3457031,3,3s-1.3457031,3-3,3
                          c-0.5522461,0-1,0.4477539-1,1v10c0,0.5522461,0.4477539,1,1,1h48c0.5522461,0,1-0.4477539,1-1V34c0-0.5522461-0.4477539-1-1-1
                          c-1.6542969,0-3-1.3457031-3-3s1.3457031-3,3-3c0.5522461,0,1-0.4477539,1-1V16C55,15.4477539,54.5522461,15,54,15z M53,25.1005859
                          C50.7207031,25.5649414,49,27.5854492,49,30s1.7207031,4.4350586,4,4.8994141V43h-9.0371094h-2H7v-8.1005859
                          C9.2792969,34.4350586,11,32.4145508,11,30s-1.7207031-4.4350586-4-4.8994141V17h34.9628906h2H53V25.1005859z">
                                    </path>
                                    <rect width="2" height="2" x="41.963" y="27"></rect>
                                    <rect width="2" height="2" x="41.963" y="31"></rect>
                                    <rect width="2" height="2" x="41.963" y="19"></rect>
                                    <rect width="2" height="2" x="41.963" y="35"></rect>
                                    <rect width="2" height="2" x="41.963" y="23"></rect>
                                    <rect width="2" height="2" x="41.963" y="39"></rect>
                                </svg>
                            </div>
                            <div>
                                <p class="fs-14 font-weight-semibold mb-1">
                                    {{ lang('Closed Tickets') }}</p>
                                <h5 class="mb-0">{{ $myclosedticketcount }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    {{-- </div> --}}

    <!--Dashboard List-->


    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Recent Tickets') }}</h4>
                </div>
                <div class="card-body overflow-scroll">
                    <div class="">
                        <div class="data-table-btn">
                            @can('Ticket Delete')
                                <button id="massdelete" class="btn btn-outline-light btn-sm mb-4 " style="display: none;"><i
                                        class="fe fe-trash"></i><span>{{ lang('Delete') }}</span></button>
                            @endcan

                            <button id="refreshdata" class="btn btn-outline-light btn-sm mb-4 "><i
                                    class="fe fe-refresh-cw"></i> </button>
                        </div>
                        <div class="sprukoloader-img"><i
                                class="fa fa-spinner fa-spin"></i><span>{{ lang('Loading...') }}</span></div>
                        <div class="fetchedtabledata">
                            @include('admin.superadmindashboard.tabledatainclude')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row-->


    <!--Dashboard List-->

@endsection
@section('scripts')
    <!-- INTERNAL Vertical-scroll js-->
    <script src="{{ asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js') }}"></script>

    <!-- INTERNAL Data tables -->
    <script src="{{ asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/datatablebutton.min.js') }}"></script>
    <script src="{{ asset('build/assets/plugins/datatable/buttonbootstrap.min.js') }}"></script>


    <!-- INTERNAL Index js-->
    @vite(['resources/assets/js/support/support-sidemenu.js'])
    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

    <!-- INTERNAL Apexchart js-->
    <script src="{{ asset('build/assets/plugins/apexchart/apexcharts.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            "use strict";

            (function($) {

                var SITEURL = '{{ url('') }}';
                var timeurl = '{{ route('timeupdate') }}';
                $('#tpBasic').load(timeurl);
                setInterval(() => {
                    $.ajax({
                        url: timeurl,
                        success: function(data) {
                            $('#tpBasic').load(timeurl);
                        },
                        error: function(xhr, status, error) {
                            $.ajax({
                                url: '{{ route('admin.authcheckdetails') }}',
                                success: function(data) {
                                    if(data != 1){
                                        location.reload();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log('error',error);
                                }
                            });
                        }
                    });
                }, 1000);

                // csrf field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#refreshdata').on('click', function(e) {
                    e.preventDefault();

                    $('.sprukoloader-img').fadeIn();

                    $.ajax({
                        url: '{{ route('admin.dashboardtabledata') }}',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('.sprukoloader-img').fadeOut();
                            $('.fetchedtabledata').html(data.rendereddata);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', status, error);
                        }
                    });
                });

                var tabledataautorefresh = @json(setting('DASHBOARD_TABLE_DATA_AUTO_REFRESH'));
                var tabledataautorefreshtime = @json(setting('TABLE_DATA_AUTO_REFRESH_TIME'));

                if (tabledataautorefresh == 'yes') {
                    setInterval(function() {
                        $('.sprukoloader-img').fadeIn();

                        $.ajax({
                            url: '{{ route('admin.dashboardtabledata') }}',
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('.sprukoloader-img').fadeOut();
                                $('.fetchedtabledata').html(data.rendereddata);
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX error:', status, error);
                            }
                        });
                    }, tabledataautorefreshtime * 1000);
                }

                // TICKET DELETE SCRIPT
                $('body').on('click', '#show-delete', function() {
                    var _id = $(this).data("id");
                    swal({
                        title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                        text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: SITEURL + "/admin/delete-ticket/" + _id,
                                success: function(data) {
                                    toastr.success(data.success);
                                    location.reload();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                },
                            });
                        }
                    });

                });
                // TICKET DELETE SCRIPT END

                // when user click its get modal popup to assigned the ticket
                $('body').on('click', '#assigned', function() {
                    var assigned_id = $(this).data('id');
                    $('.select2_modalassign').select2({
                        dropdownParent: ".sprukosearch",
                        minimumResultsForSearch: '',
                        placeholder: "Search",
                        width: '100%'
                    });
                    $.get('admin/assigned/' + assigned_id, function(data) {
                        $('#AssignError').html('');
                        $('#assigned_id').val(data.assign_data.id);
                        $(".modal-title").text('{{ lang('Assign To Agent') }}');
                        $('#username').html(data.table_data);
                        if(data.assign_user_exist == 'no'){
                            $('#username').val([]).trigger('change')
                        }
                        $('#addassigned').modal('show');
                    });
                });

                // Assigned Submit button
                $('body').on('submit', '#assigned_form', function(e) {
                    e.preventDefault();
                    var actionType = $('#btnsave').val();
                    var fewSeconds = 2;
                    $('#btnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                    $('#btnsave').prop('disabled', true);
                    setTimeout(function() {
                        $('#btnsave').prop('disabled', false);
                    }, fewSeconds * 1000);
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: SITEURL + "/admin/assigned/create",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {

                            $('#AssignError').html('');
                            $('#assigned_form').trigger("reset");
                            $('#addassigned').modal('hide');
                            $('#btnsave').html('{{ lang('Save Changes') }}');
                            location.reload();
                            toastr.success(data.success);
                        },
                        error: function(data) {
                            $('#AssignError').html('');
                            // $('#AssignError').html(data.responseJSON.errors.assigned_user_id);
                            $('#AssignError').html("The assigned agent field is required");
                            $('#btnsave').html('{{ lang('Save Changes') }}');
                        }
                    });
                });

                // Remove the assigned from the ticket
                $('body').on('click', '#btnremove', function() {
                    var asid = $(this).data("id");
                    swal({
                            title: `{{ lang('Are you sure you want to unassign this agent?', 'alerts') }}`,
                            text: "{{ lang('This agent may no longer exist for this ticket.', 'alerts') }}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                $.ajax({
                                    type: "get",
                                    url: SITEURL + "/admin/assigned/update/" + asid,
                                    success: function(data) {
                                        location.reload();
                                        toastr.success(data.success);

                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    }
                                });

                            }
                        });
                });

                //Mass Delete
                $('body').on('click', '#massdelete', function() {

                    var id = [];
                    $('.checkall:checked').each(function() {
                        id.push($(this).val());
                    });
                    if (id.length > 0) {
                        swal({
                                title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                                text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: "{{ url('admin/ticket/delete/tickets') }}",
                                        method: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function(data) {
                                            location.reload();
                                            toastr.success(data.success);

                                        },
                                        error: function(data) {

                                        }
                                    });
                                }
                            });
                    } else {
                        toastr.error('{{ lang('Please select at least one check box.', 'alerts') }}');
                    }

                });

                $('#supportticket-dashe').dataTable();

                $('.form-select').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });

                $(document).ready(function() {

                    $(document).on('click', '#customCheckAll', function() {
                        $('.checkall').prop('checked', this.checked);
                        updateMassDeleteVisibility();
                    });

                    // Handle individual checkboxes
                    $(document).on('click', '.checkall', function() {
                        updateCustomCheckAll();
                        updateMassDeleteVisibility();
                    });

                    // Handle pagination controls
                    $(document).on('click', '.pagination a', function() {
                        // Assuming '.pagination a' is the selector for your pagination controls
                        setTimeout(function() {
                            updateMassDeleteVisibility();
                        }, 100);
                    });

                    // Initialize the "Select All" checkbox to unchecked
                    $('#customCheckAll').prop('checked', false);

                    // Function to update the visibility of the mass delete button
                    function updateMassDeleteVisibility() {
                        if ($('.checkall:checked').length === 0) {
                            $('#massdelete').hide();
                        } else {
                            $('#massdelete').show();
                        }
                    }

                    // Function to update the state of the "Select All" checkbox
                    function updateCustomCheckAll() {
                        var totalCheckboxes = $('.checkall').length;
                        var checkedCheckboxes = $('.checkall:checked').length;

                        if (checkedCheckboxes === totalCheckboxes) {
                            $('#customCheckAll').prop('checked', true);
                        } else {
                            $('#customCheckAll').prop('checked', false);
                        }
                    }
                });



                $('body').on('click', '#selfassigid', function(e) {

                    e.preventDefault();

                    let id = $(this).data('id');

                    $.ajax({
                        method: 'POST',
                        url: '{{ route('admin.selfassign') }}',
                        data: {
                            id: id,
                        },
                        success: (data) => {
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {

                        }
                    });
                })

                $(".vertical-scroll5").bootstrapNews({
                    newsPerPage: 1,
                    autoplay: true,
                    pauseOnHover: true,
                    navigation: false,
                    direction: 'down',
                    newsTickerInterval: 2500,
                    onToDo: function() {

                    }
                });

            })(jQuery);
        })
    </script>
@endsection

@section('modal')
    @include('admin.modalpopup.assignmodal')
@endsection
