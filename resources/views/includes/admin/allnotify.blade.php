@php $notifys = auth()->user()->unreadNotifications()->paginate(2); $badgecount = auth()->user()->unreadNotifications->count(); @endphp

@forelse( $notifys as $notification)
    @php
        if(isset($notification->data['link'])){
            $explodeurl = explode('ticket-view/', $notification->data['link']);
            $stringreplaceurl = str_replace($explodeurl[1], encrypt($explodeurl[1]) , $notification->data['link']);
        }else{
            $stringreplaceurl = '';
        }
    @endphp
    @if(array_key_exists('reasonofinfo', $notification->data) && $notification->data['reasonofinfo'] == 'adminreset2fa')
        <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
            <div class="d-flex align-items-center">
                <div class="">
                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                </div>
                <div class="d-flex">
                    <div class="ps-3">
                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                        <p class="fs-13 mb-1 text-wrap">{{lang('Your two factor authentication details are removed by admin', 'notification')}} </p>
                        <div class="small text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @elseif(array_key_exists( 'ticketassign', $notification->data) && $notification->data['ticketassign'] == 'yes')
        <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
            <div class="d-flex align-items-center">
                <div class="">
                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                </div>
                <div class="d-flex">
                    <div class="ps-3">
                        <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                        <p class="fs-13 mb-1 text-wrap">{{ $notification->data['ticket_id'] }} {{lang('Ticket is assigned', 'notification')}} </p>
                        <div class="small text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @elseif(array_key_exists( 'draftnotify', $notification->data) && $notification->data['draftnotify'] == 'draftcreated')
        <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
            <div class="d-flex align-items-center">
                <div class="">
                    <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                </div>
                <div class="d-flex">
                    <div class="ps-3">
                        <h6 class="mb-1">{{ lang('Draft') }} {{ lang($notification->data['title']) }}</h6>
                        <p class="fs-13 mb-1 text-wrap">{{ $notification->data['ticket_id'] }} {{lang('Ticket Draft is ', 'notification')}} {{ $notification->data['title'] }} {{ lang(' By ') }} {{ lang($notification->data['created_username']) }}</p>
                        <div class="small text-muted">
                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @else
        @if($notification->data['status'] == 'New')

            @if(isset($notification->data['replystatus']) && $notification->data['replystatus'] == 'Replied')
                <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                        </div>
                        <div class="d-flex">
                            <div class="ps-3">
                                <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                <p class="fs-13 mb-1 text-wrap">{{lang('You got a new reply on this ticket', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            @else

                @if($notification->data['overduestatus'] == 'Overdue')

                    <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                        <div class="d-flex align-items-center">
                        <div class="">
                            <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                        </div>
                            <div class="d-flex">
                                <div class="ps-3">
                                    <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                    <p class="fs-13 mb-1 text-wrap"> {{lang('This ticket status is overdue', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @else

                    <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                            </div>
                            <div class="d-flex">
                                <div class="ps-3">
                                    <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                    <p class="fs-13 mb-1 text-wrap"> {{lang('A new ticket has been created', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endif
        @endif
        @if($notification->data['status'] == 'Closed')

            <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                <div class="d-flex align-items-center">
                    <div class="">
                        <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                    </div>
                    <div class="d-flex">
                        <div class="ps-3">
                            <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                            <p class="fs-13 mb-1 text-wrap"> {{lang('This ticket has been closed', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
        @if($notification->data['status'] == 'On-Hold')

            <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                <div class="d-flex align-items-center">
                    <div class="">
                        <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                    </div>
                    <div class="d-flex">
                        <div class="ps-3">
                            <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                            <p class="fs-13 mb-1 text-wrap"> {{lang('This ticket status is On-Hold', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
        @if($notification->data['status'] == 'Re-Open')

            <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                <div class="d-flex align-items-center">
                    <div class="">
                        <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                    </div>
                    <div class="d-flex">
                        <div class="ps-3">
                            <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                            <p class="fs-13 mb-1 text-wrap"> {{lang('This ticket has been reopened', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
        @if($notification->data['status'] == 'Inprogress')
            @if($notification->data['overduestatus'] == 'Overdue')

                <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                    <div class="d-flex align-items-center">
                    <div class="">
                        <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                    </div>
                        <div class="d-flex">
                            <div class="ps-3">
                                <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                <p class="fs-13 mb-1 text-wrap"> {{lang('This ticket status is overdue', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @else
                <a class="dropdown-item border-bottom mark-as-read" href="{{$stringreplaceurl}}" data-id="{{ $notification->id }}">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-bell sidemenu_icon fs-20 text-success"></i></span>
                        </div>
                        <div class="d-flex">
                            <div class="ps-3">
                                <h6 class="mb-1">{{ Str::limit($notification->data['title'], '30') }}</h6>
                                <p class="fs-13 mb-1 text-wrap">{{lang('You got a new reply on this ticket', 'notification')}} {{ $notification->data['ticket_id'] }}</p>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endif
        @endif
        @if ($notification->data['status'] == 'mail')

            <a class="dropdown-item border-bottom mark-as-read" href="{{route('admin.notiication.view', $notification->id)}}" >
                <div class="d-flex ">
                    <div class="">
                        <span class="bg-success-transparent brround fs-12 notifications"><i class="feather  feather-mail sidemenu_icon fs-20 text-success"></i></span>
                    </div>
                    <div class="d-flex">
                        <div class="ps-3">
                            <h6 class="mb-1"> {{$notification->data['mailsubject']}}</h6>
                            <p class="fs-13 mb-1 text-wrap">
                                {{ Str::limit($notification->data['mailtext'], '30', '...') }}
                            </p>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
    @endif
@empty

<a class="dropdown-item border-bottom mark-as-read notification-dropdown" href="">
    <div class="d-flex justify-content-center">
        <div class="ps-3 text-center">
            <img src="{{asset('build/assets/images/nonotification.png')}}" alt="">
            <p class="fs-13 mb-1 text-muted">{{lang('There are no new notifications to display', 'notification')}}</p>
        </div>
    </div>
</a>

@endforelse


<script type="text/javascript">
    $(function() {
        //  Mark As Read
        function sendMarkRequest(id = null) {
            return $.ajax("{{ route('admin.markNotification') }}", {
                method: 'GET',
                data: {
                    // _token,
                    id
                }
            });
        }
        $('.mark-as-read').on('click', function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                $(this).parents('div.alert').remove();
            });
        });
    })
</script>
