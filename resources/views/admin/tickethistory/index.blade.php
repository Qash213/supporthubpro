@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAL Data table css -->
    <link href="{{ asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}?v=<?php echo time(); ?>"
        rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.css') }}?v=<?php echo time(); ?>"
        rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection

@section('content')
    {{-- page header start --}}
    <div class="page-header d-xl-flex d-block mb-0">
        <div class="page-leftheader">
            <div class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Ticket History') }}</span></div>
        </div>
    </div>
    {{-- page header start --}}

    <!--Page header-->
    <div class="page-header d-lg-flex d-block mt-2">
        <div class="page-leftheader">
            <div class="page-title d-flex align-items-center">{{ $ticket->ticket_id }} {{ $ticket->subject }}

                <span class="badge fs-11 badge-pill bg-info-transparent text-info mx-2">{{ lang($ticket->status) }}</span>

            </div>
            <div class="ticket-title">
                <ul class="fs-13 font-weight-normal custom-ul d-flex">
                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ lang('Date') }}"><i
                            class="fe fe-calendar me-1 fs-14"></i>{{ \Carbon\Carbon::parse($ticket->created_at)->timezone(setting('default_timezone'))->format(setting('date_format')) }}
                    </li>
                    @if ($ticket->priority != null)
                        @if ($ticket->priority == 'Low')
                            <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ lang('Priority') }}">{{ lang($ticket->priority) }}</li>
                        @elseif($ticket->priority == 'High')
                            <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ lang('Priority') }}">{{ lang($ticket->priority) }}</li>
                        @elseif($ticket->priority == 'Critical')
                            <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ lang('Priority') }}">{{ lang($ticket->priority) }}</li>
                        @else
                            <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ lang('Priority') }}">{{ lang($ticket->priority) }}</li>
                        @endif
                    @else
                        ~
                    @endif

                    @if ($ticket->category_id != null)
                        @if ($ticket->category != null)
                            <li class="px-2 text-muted"><i class="fe fe-grid me-1 fs-14" data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ lang('Category') }}"></i>{{ $ticket->category->name }}</li>
                        @else
                            ~
                        @endif
                    @else
                        ~
                    @endif

                    @if ($ticket->last_reply == null)
                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ lang('Last Replied') }}"><i
                                class="fe fe-clock me-1 fs-14"></i>{{ \Carbon\Carbon::parse($ticket->created_at)->diffForHumans() }}
                        </li>
                    @else
                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ lang('Last Replied') }}"><i
                                class="fe fe-clock me-1 fs-14"></i>{{ \Carbon\Carbon::parse($ticket->last_reply)->diffForHumans() }}
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
    <!--End Page header-->

    <!-- row -->
    <div class="container">
        <ul class="notification">
            @foreach ($ticket->ticket_history as $tickethistory)
                <li>
                    <div class="notification-time">
                        <span
                            class="date">{{ \Carbon\Carbon::parse($tickethistory->created_at)->timezone(setting('default_timezone'))->format(setting('date_format')) }}</span>
                        <span
                            class="time">{{ \Carbon\Carbon::parse($tickethistory->created_at)->timezone(setting('default_timezone'))->format(setting('time_format')) }}</span>
                    </div>
                    <div class="notification-icon">
                        <a href="javascript:void(0);"></a>
                    </div>
                    <div class="notification-body">
                        <div class="media mt-0">
                            <div class="media-body">
                                @if ($tickethistory->ticketactions != null)
                                    {!! $tickethistory->ticketactions !!}
                                @else
                                    <div class="d-flex align-items-center">
                                        <div class="mt-0">
                                            <p class="mb-0 fs-12 mb-1">{{ lang('Status') }}
                                                <span class="font-weight-semibold mx-1
                                                    @if ($tickethistory->status == 'New' || $tickethistory->status == 'On-Hold' || $tickethistory->status == 'Re-Open') text-orange
                                                    @elseif ($tickethistory->status == 'Closed' || $tickethistory->status == 'Solved')
                                                        text-danger
                                                    @else
                                                        {{-- $tickethistory->status == 'Inprogress' --}}
                                                        text-info
                                                    @endif
                                                    ">{{ $tickethistory->status }}
                                                </span>
                                                @if ($tickethistory->replystatus)
                                                    <span
                                                        class="text-orange font-weight-semibold mx-1">{{ $tickethistory->replystatus }}</span>
                                                @endif
                                                @if ($tickethistory->ticketnote)
                                                    <span class="badge badge-warning py-0 px-2">{{ lang('Note') }}</span>
                                                @endif
                                                @if ($tickethistory->overduestatus)
                                                    <span
                                                        class="text-danger font-weight-semibold mx-1">{{ $tickethistory->overduestatus }}</span>
                                                @endif
                                            </p>
                                            <p class="mb-0 fs-17 font-weight-semibold text-dark">
                                                {{ $tickethistory->username }}
                                                @if ($tickethistory->currentAction)
                                                    <span
                                                        class="fs-11 mx-1 text-muted">({{ $tickethistory->currentAction }})</span>
                                                @endif
                                            </p>
                                            @php
                                                $explode_id = json_decode($tickethistory->assignUser, true);
                                                $dataArray = $explode_id;
                                            @endphp
                                            @if ($dataArray)
                                                @foreach ($dataArray as $user)
                                                    <div class="fs-11 font-weight-semibold ps-3">
                                                        <div>
                                                            <span class="fs-12">{{ $user['name'] }}</span>
                                                            <span class="text-muted">(Assignee)</span>
                                                        </div>
                                                        <small class="text-muted useroutput">{{ $user['roles'][0]['name'] }}</small>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        @if ($tickethistory->type)
                                            <div class="ms-auto">
                                                <span class="float-end badge
                                                    @if ($tickethistory->type == 'Customer' || $tickethistory->type == 'guest')
                                                        badge-danger-light
                                                    @else
                                                        badge-primary-light
                                                    @endif
                                                    ">
                                                    <span class="fs-11 font-weight-semibold">{{ $tickethistory->type }}
                                                </span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
    <!-- row closed -->
@endsection


@section('scripts')
    <script type="text/javascript">
        $(function() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('.custom-popover'));
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl, {
                    trigger: 'hover', // Set trigger to 'hover'
                });
            });

            "use strict";

            (function($) {

                // Variables
                var SITEURL = '{{ url('') }}';

                // Csrf Field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                // Initialize Bootstrap popovers




            })(jQuery);
        })
    </script>
@endsection
@section('modal')
@endsection
