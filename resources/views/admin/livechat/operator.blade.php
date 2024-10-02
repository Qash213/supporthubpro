@extends('layouts.adminmaster')
@section('styles')
    <style>
        .file-img {
            position: relative;
        }

        /* .list-unstyled{
            overflow:  hidden !important;
        } */

        .file-img button {
            position: absolute;
            inset-block-start: -4px;
            inset-inline-end: -4px;
            height: 20px;
            width: 20px;
            padding: 0px;
            border: 0;
        }

        .liveChatImageViewer {
            display: block;
            position: fixed;
            z-index: 9999999999;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .liveChatImageClose {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            border: 1px solid;
            padding: 0px 20px;
            border-radius: 50%;
            cursor: pointer;
        }

        .liveChatImageTag {
            margin: auto;
            display: block;
            /* width: 80vh; */
            height: 80vh;
            /* max-width: 403px; */
            border-radius: 10px;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
        }


        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted,
        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
            /* Remove !important */
        }

        /* Adjust animation properties */
        @keyframes shine {
            to {
                background-position-x: 200%;
                /* Adjust direction */
            }
        }

        /* Apply animation to the elements */
        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted,
        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            animation: shine 1.5s linear infinite;
        }


        .is-loading .agent-detail .avatar,
        .is-loading .agent-detail .name-email-container {
            background-image: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%) !important;
            border-radius: 5px
        }

        .is-loading .agent-detail .onlineOfflineIndicator {
            display: none
        }

        .is-loading .agent-detail .font-weight-semibold,
        .is-loading .agent-detail .text-muted {
            opacity: 0;
        }

        /* .is-loading .agent-detail .name-email-container {
            width: 250px
        } */

        .is-loading#main-chat-content .avatar,
        .is-loading#main-chat-content .operator-conversation-Info .flex-fill,
        .is-loading#main-chat-content .avatar,
        .is-loading#main-chat-content .chatnameperson,
        .is-loading#main-chat-content .chatting-user-info,
        .is-loading#main-chat-content .chat-day-label span,
        .is-loading#main-chat-content .chat-list-inner .main-chat-msg div {
            background-image: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%) !important;
            border-radius: 5px;
            animation: shine 1.5s linear infinite;
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
        }

        .is-loading#main-chat-content .chat-day-label span {
            padding: 0.188rem 3.5rem !important
        }

        .is-loading#main-chat-content .chatnameperson,
        .is-loading#main-chat-content .chat-item-end .chatting-user-info {
            padding: 0.188rem 3.5rem;
            font-size: 0.7rem;
            background-color: rgba(51, 102, 255, 0.1);
            border-radius: 0.3rem;
            color: var(--primary);
        }

        .main-chat-msg p {
            word-wrap: break-word !important;
            word-break: break-word !important;
        }

        .dark-mode .main-chart-wrapper .chat-info {
            background-color: #191d43 !important
        }

        .dark-mode .chat-reply-area {
            background-color: #191d43 !important
        }

        .dark-mode .main-chart-wrapper .chat-info ul li.checkforactive {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .checkforactive.active {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .dark-mode .main-chart-wrapper .main-chat-area .chat-content .main-chat-msg div p {
            color: #f1f4fb;
        }

        .dark-mode .main-chart-wrapper .main-chat-area .chat-content .chat-item-start .main-chat-msg div,
        .dark-mode .main-chart-wrapper .main-chat-area .chat-content .chat-item-end .main-chat-msg div {
            background-color: rgb(37 38 74);
        }
    </style>

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Operators Chat','menu')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="main-chart-wrapper">
        <div class="row">
            <div class="col-xl-3">
                <div class="chat-info border card">
                    <div class="card-header border-0 pb-5">
                        <h4 class="card-title">{{ lang('All Operators') }}</h4>
                    </div>
                    {{-- <div class="d-flex align-items-center justify-content-between w-100 p-3 border-bottom">
                        <div>
                            <h5 class="font-weight-semibold mb-0">{{ lang('All Operators') }}</h5>
                        </div>
                    </div> --}}
                    <div>
                        <ul class="list-unstyled mb-0 mt-2 px-2 chat-users-tab overflow-auto" id="chat-msg-scroll">
                            @php
                                $emptyConversation = true;
                            @endphp
                            @foreach ($allconver as $conversation)
                                @php
                                    $conver = collect($conversation)->sortByDesc('created_at')->first();
                                    $markasUnread = false;

                                    foreach ($conversation as $markAsUnreadarray) {
                                        if (
                                            is_array(json_decode($markAsUnreadarray['mark_as_unread'])) &&
                                            in_array(auth()->id(), json_decode($markAsUnreadarray['mark_as_unread']))
                                        ) {
                                            $markasUnread = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @if (
                                    (json_decode($conver['delete_status']) && !in_array(Auth::id(), json_decode($conver['delete_status']))) ||
                                        json_decode($conver['delete_status']) == null)
                                    {{ $emptyConversation = false }}
                                    @if ($conver != null)
                                        @if (isset($conver['created_user_id']))
                                            <li class="checkforactive" data-group-uniq="{{ $conver['unique_id'] }}">
                                            @else
                                            <li class="checkforactive"
                                                data-id="@if ($conver['sender_user_id'] != Auth::id()){{ $conver['sender_user_id'] }}@else{{ $conver['receiver_user_id'] }}@endif">
                                        @endif
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 lh-1">
                                                @if ($conver['sender_user_id'] != Auth::id() && !isset($conver['created_user_id']))
                                                    @if(\App\Models\User::find($conver['sender_user_id']) != null)
                                                        @if (\App\Models\User::find($conver['sender_user_id'])->image == null)
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/user-profile.png)">
                                                                <span class="avatar-status onlineOfflineIndicator"></span>
                                                            </span>
                                                        @else
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/{{ \App\Models\User::find($conver['sender_user_id'])->image }})">
                                                                <span class="avatar-status onlineOfflineIndicator"></span>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (!isset($conver['created_user_id']))
                                                        @if(\App\Models\User::find($conver['receiver_user_id']))
                                                            @if (\App\Models\User::find($conver['receiver_user_id'])->image == null)
                                                                <span class="avatar brround"
                                                                    style="background-image: url(../uploads/profile/user-profile.png)">
                                                                    <span class="avatar-status onlineOfflineIndicator"></span>
                                                                </span>
                                                            @else
                                                                <span class="avatar brround"
                                                                    style="background-image: url(../uploads/profile/{{ \App\Models\User::find($conver['receiver_user_id'])->image }})">
                                                                    <span class="avatar-status onlineOfflineIndicator"></span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif

                                                {{-- For the Group Icon --}}
                                                @if (isset($conver['created_user_id']))
                                                    @if(\App\Models\User::find($conver['created_user_id']))
                                                        @if (\App\Models\User::find($conver['created_user_id'])->image == null)
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/user-profile.png)"></span>
                                                        @else
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/{{ \App\Models\User::find($conver['created_user_id'])->image }})"></span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="flex-fill">
                                                <div class="mb-0 d-flex align-items-center justify-content-between">
                                                    <div class="font-weight-semibold">
                                                        {{-- For the group Name --}}
                                                        @if (isset($conver['created_user_id']))
                                                            <a
                                                                href="javascript:void(0);">{{ \App\Models\User::find($conver['created_user_id']) != null ? \App\Models\User::find($conver['created_user_id'])->name : lang('New Group') }}</a>
                                                        @else
                                                            <a href="javascript:void(0);">
                                                                @if ($conver['sender_user_id'] != Auth::id())
                                                                    {{ isset($conver['sender_username']) ? $conver['sender_username'] : '' }}@else{{ isset($conver['reciever_username']) ? $conver['reciever_username'] : '' }}
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="float-end text-muted fw-normal fs-12 chat-time" data-initial-24time='{{ \Carbon\Carbon::parse($conver['created_at'])->timezone(setting('default_timezone')) }}'>
                                                        {{ \Carbon\Carbon::parse($conver['created_at'])->diffForHumans() ?? '' }}
                                                    </div>
                                                    <div class="dropdown chat-actions lh-1">
                                                        <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fe fe-more-vertical fs-18"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            @if (
                                                                $markasUnread &&
                                                                    $conver['sender_user_id'] != Auth::id() &&
                                                                    !isset($conver['created_user_id']) &&
                                                                    collect($conversation)->where('message_status', 'delivered')->count() < 1)
                                                                <li><a class="dropdown-item markAsUnreadBtn"
                                                                        href="{{ route('admin.markasread', $conver['unique_id']) }}"
                                                                        href="javascript:void(0);"><i
                                                                            class="ri-chat-check-line align-middle me-2 fs-18"></i>{{ lang('Mark
                                                                        As Read') }}</a></li>
                                                            @else
                                                                @if (
                                                                    $conver['sender_user_id'] != Auth::id() &&
                                                                        collect($conversation)->where('message_status', 'delivered')->count() < 1 &&
                                                                        !isset($conver['created_user_id']))
                                                                    <li><a class="dropdown-item markAsUnreadBtn"
                                                                            href="{{ route('admin.markasunread', $conver['unique_id']) }}"
                                                                            href="javascript:void(0);"><i
                                                                                class="ri-chat-check-line align-middle me-2 fs-18"></i>{{ lang('Mark
                                                                            As Unread') }}</a></li>
                                                                @endif
                                                            @endif
                                                            @if (isset($conver['created_user_id']))
                                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                                        onclick="confirmFunction('{{ route('admin.groupconversiondelete', $conver['unique_id']) }}')"><i
                                                                            class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>{{ lang('Delete') }}</a>
                                                                </li>
                                                            @else
                                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                                        onclick="confirmFunction('{{ route('admin.conversationdelete', $conver['unique_id']) }}')"><i
                                                                            class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>{{ lang('Delete') }}</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    @php
                                                        $groupIncludeUsers = json_decode($conver['receiver_user_id']);
                                                    @endphp

                                                    @if ($conver['sender_user_id'] == Auth::id())
                                                        <span
                                                            class="chat-msg text-truncate fs-13 text-default">{{ $conver['message'] ?? '' }}</span>
                                                        @if ($conver['message_status'] == 'delivered')
                                                            <span class="chat-read-icon d-inline-flex align-middle ms-auto me-2">
                                                                <i class="ri-check-double-fill"></i>
                                                            </span>
                                                        @endif
                                                        @if ($conver['message_status'] == 'seen')
                                                            <span class="chat-read-mark d-inline-flex align-middle ms-auto me-2">
                                                                <i class="ri-check-double-fill"></i>
                                                            </span>
                                                        @endif
                                                        @if ($conver['message_status'] == 'sent')
                                                            <span class="chat-read-icon d-inline-flex align-middle ms-auto me-2">
                                                                <i class="ri-check-fill"></i>
                                                            </span>
                                                        @endif
                                                    @else
                                                        {{-- Group Last message status --}}
                                                        @if (isset($conver['created_user_id']))
                                                            @if (json_decode($conver['mark_as_unread']) &&
                                                                    in_array(Auth::id(), json_decode($conver['mark_as_unread'])) &&
                                                                    $conver['sender_user_id'] != Auth::id())
                                                                <span
                                                                    class="chat-msg text-truncate fs-13 text-default">{{ $conver['message'] ?? '' }}</span>
                                                            @else
                                                                <span
                                                                    class="chat-msg text-truncate fs-13 text-default font-weight-bold">{{ $conver['message'] ?? '' }}</span>
                                                                @php
                                                                    $messageCount = collect($conversation)
                                                                        ->filter(function ($message) {
                                                                            $markAsUnreadArray = json_decode(
                                                                                $message['mark_as_unread'],
                                                                            );
                                                                            return !in_array(Auth::id(), $markAsUnreadArray);
                                                                        })
                                                                        ->count();
                                                                @endphp
                                                                @if (!str_contains($conver['message'], 'Group created by'))
                                                                    <span
                                                                        class="badge bg-success-transparent rounded-circle float-end unReadIndexNumber me-1 ms-auto">{{ $messageCount ?? '' }}</span>
                                                                @endif
                                                            @endif
                                                            {{-- Agent Last message status --}}
                                                        @else
                                                            @if ($conver['message_status'] != 'seen')
                                                                <span
                                                                    class="chat-msg text-truncate fs-13 text-default font-weight-bold">{{ $conver['message'] ?? '' }}</span>
                                                                <span
                                                                    class="badge bg-success-transparent rounded-circle float-end unReadIndexNumber">{{ collect($conversation)->where('message_status', 'delivered')->count() }}</span>
                                                            @else
                                                                @if ($markasUnread)
                                                                    <span
                                                                        class="chat-msg text-truncate fs-13 text-default font-weight-bold">{{ $conver['message'] ?? '' }}</span>
                                                                    <span
                                                                        class="badge bg-success-transparent rounded-circle float-end unReadIndexNumber"></span>
                                                                @else
                                                                    <span
                                                                        class="chat-msg text-truncate fs-13 text-default">{{ $conver['message'] ?? '' }}</span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif

                                                    {{-- For the Group include People Icons --}}
                                                    @php
                                                        $conversationIncludeUsers = '';
                                                    @endphp
                                                    @if (isset($conver['created_user_id']))
                                                        <div class="avatar-list avatar-list-stacked me-3">
                                                            @foreach ($groupIncludeUsers as $index => $IncludeuserId)
                                                                @if(\App\Models\User::find($IncludeuserId))
                                                                    {{ $conversationIncludeUsers = $conversationIncludeUsers . \App\Models\User::find($IncludeuserId)->name . ', ' }}
                                                                    @if ($index < 2)
                                                                        @if (\App\Models\User::find($IncludeuserId)->image == null)
                                                                            <span class="avatar avatar-sm brround"
                                                                                style="background-image: url(../uploads/profile/user-profile.png)"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                data-bs-title={{ \App\Models\User::find($IncludeuserId)->name }}
                                                                                data-bs-original-title="" title=""></span>
                                                                        @else
                                                                            <span class="avatar avatar-sm brround"
                                                                                style="background-image: url(../uploads/profile/{{ \App\Models\User::find($IncludeuserId)->image }})"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                                data-bs-title={{ \App\Models\User::find($IncludeuserId)->name }}
                                                                                data-bs-original-title="" title=""></span>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach

                                                            @if (count($groupIncludeUsers) > 2)
                                                                <span class="avatar avatar-sm brround bg-light text-dark"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    data-bs-title='{{ $conversationIncludeUsers }}'>+{{ count($groupIncludeUsers) - 2 }}</span>
                                                            @endif
                                                        </div>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                        </li>
                                    @endif
                                @endif
                            @endforeach

                            @if ($emptyConversation)
                                <div class="text-center mt-5 p-1 bg-warning-transparent text-default noDiscussionsProgress" style="margin-top: 20rem;">
                                    <span>{{ lang('As of now, there are no chat discussions in progress') }}</span>
                                </div>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="main-chat-area main-chat-area-new bg-white">
                    <div id="main-chat-content">
                        <div class="card no-articles d-none shadow-none" style="height: calc(100vh - 8rem);background-color: transparent;">
                            <div class="card-body p-8">
                                <div class="main-content text-center">
                                    <div class="notification-icon-container p-4">
                                        <img src="{{ asset('build/assets/images/noarticle.png') }}" alt="">
                                    </div>
                                    <h4 class="mb-1">{{ lang('Currently, no active chat discussions at the moment') }}</h4>
                                    <p class="text-muted">{{ lang('There are currently no ongoing chat discussions at this time.') }}</p>
                                </div>
                            </div>
                        </div>


                        <div class="align-items-center py-2 px-3 rounded border-bottom bg-white mb-3 mainchat-skeleton-loader d-none"
                            id="operator-conversation-Info">
                            <div class="me-2 lh-1">
                                <span class="avatar avatar-md brround">
                                </span>
                            </div>
                            <div class="flex-fill">
                                <p class="mb-0 fw-semibold fs-14">
                                    <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">
                                    </a>
                                </p>
                                <p class="text-muted mb-0 chatpersonstatus">
                                </p>
                            </div>
                        </div>
                        <ul class="list-unstyled chat-content overflow-auto mainchat-skeleton-loader d-none"
                            id="operator-conversation" operator-id="1">
                            <li class="chat-day-label">
                                <span></span>
                            </li>
                            <li class="chat-item-start ">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/user-profile.png)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div
                                                style="
                                    padding-right: 250px;
                                ">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start mt-2">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/1699439294.jpg)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="msg-sent-time">
                                            </span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="padding-left: 250px;">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start mt-2">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/1699439294.jpg)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="msg-sent-time">
                                            </span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="padding-left: 250px;">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start ">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/user-profile.png)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="padding-right: 250px;">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start mt-2">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/1699439294.jpg)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="msg-sent-time">
                                            </span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="padding-left: 250px;">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start ">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/user-profile.png)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div
                                                style="
                                    padding-right: 250px;
                                ">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start mt-2">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/user-profile.png)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div
                                                style="
                                    padding-right: 250px;
                                ">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="chat-item-start mt-2">
                                <div class="chat-list-inner">
                                    <div class="chat-user-profile">
                                        <span class="avatar avatar-md brround"
                                            style="background-image: url(../uploads/profile/1699439294.jpg)">
                                        </span>
                                    </div>
                                    <div class="ms-3">
                                        <span class="chatting-user-info">
                                            <span class="msg-sent-time">
                                            </span>
                                        </span>
                                        <div class="main-chat-msg">
                                            <div style="padding-left: 250px;">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>




                    </div>
                    <div class="chat-footer bg-transparent shadow-none d-none border-top">
                        <div class="chat-reply-area chat-replyoptions flex-fill">
                            <textarea class="form-control textarea-chatoptions my-2 border-0" id="auto-expand" rows="1"
                                placeholder="Type your message here..."></textarea>
                            <div class="image-uploaded d-flex gap-2 flex-wrap">

                            </div>
                            <div class="d-flex align-items-center justify-content-between px-2">
                                <div data-bs-toggle="tooltip" onclick="handleClick(event);" data-bs-placement="top"
                                    data-bs-title="Canned responses" data-bs-original-title="" title="">
                                    <a aria-label="anchor" onclick="handleClick(event);" class="btn-emoji" href=""
                                        data-bs-target="#canned-responses" data-bs-toggle="modal">
                                        <i class="ri-message-line" onclick="handleClick(event);"></i>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a aria-label="anchor" class="btn-emoji allEmojisBtn dropdown" data-bs-toggle="dropdown"
                                        href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-title="Emojis" data-bs-original-title="" title="">
                                        <i class="ri-emotion-line"></i>
                                    </a>
                                    <ul class="dropdown-menu" id="emojiGrid" style="height: 200px; overflow-y: scroll;"></ul>

                                    @if (setting('liveChatAgentFileUpload') == '1')
                                        <a aria-label="anchor" class="" type="file"
                                            onclick="{document.querySelector('#chat-file-upload').click()}"
                                            href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="Upload">
                                            <i class="ri-attachment-line"></i>
                                        </a>
                                    @endif
                                    <input type="file" id="chat-file-upload" class="d-none" name="chat-file-upload" />
                                    <button aria-label="anchor" disabled="true" id="agentSendMessage"
                                        class="btn-reply border rounded-3 btn-outline-primary disabled" href="javascript:void(0)">
                                        <i class="ri-send-plane-2-fill fs-20"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="chat-user-details bg-transparent p-0" id="chat-user-details">
                    <div class="card overflow-hidden">
                        <div class="card-header border-0 pb-0 flex-wrap gap-2">
                            <h4 class="card-title">{{ lang('Available Operators') }}<span class="text-default ms-1 fs-14 fw-medium AvailableOperatorsIndex">(0)</span></h4>
                            <button class="btn btn-primary btn-sm btn-icon mb-1 chatRoomBtn d-flex align-items-center ms-auto"
                                onclick="$('#chatRoom').modal('show');" data-toggle="modal" data-target="#chatRoom"><i
                                    class="feather feather-users"></i></button>
                        </div>

                        <div class="card-body">
                            <div class="mb-5"><input class="form-control" id="availableAgentsSearchInput"
                                    placeholder="Search For Operator" type="text"></div>
                            <div class="noUser" id="noUserMessage" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex gap-3">
                                        <p> {{ lang('No user found') }} </p>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled agents-list is-loading overflow-y-scroll" id="agents-list">

                                @foreach ($user as $users)
                                    <li class="agent-detail" data-id="{{ $users->id }}">
                                        <a href="javascript:void(0);" class="streched-link">
                                            {{-- <div class="d-flex align-items-center justify-content-between"> --}}
                                                <div class="d-flex gap-3 align-items-center text-break">
                                                    <div>
                                                        @if ($users->image == null)
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/user-profile.png)">
                                                                <span class="avatar-status onlineOfflineIndicator"></span>
                                                            </span>
                                                        @else
                                                            <span class="avatar brround"
                                                                style="background-image: url(../uploads/profile/{{ $users->image }})">
                                                                <span class="avatar-status onlineOfflineIndicator"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="name-email-container">
                                                        <span class="font-weight-semibold">{{ $users->name }}</span>
                                                        <span class="d-block text-muted fs-12">{{ $users->email }}</span>
                                                    </div>
                                                </div>
                                            {{-- </div> --}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card">

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chat Room Model --}}
    <div class="modal fade" id="chatRoom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ lang('Select Group Operators') }}</h5>
                    <button type="button" onclick="$('#chatRoom').modal('hide')" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="onlineUsersChatRoom">
                    <ul class="list-group" id="onlineUlChatroom">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3">
                                    <input type="checkbox" class="" name="example-checkbox1" allchecked="true"
                                        value="option1">
                                    <div>
                                        <span class="font-weight-semibold">{{ lang('All Operators') }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @foreach ($user as $users)
                            <li class="list-group-item" data-id="{{ $users->id }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex gap-3">
                                        <input type="checkbox" class="" name="chatRoom-checkbox" value="option1">
                                        <div>
                                            @if ($users->image == null)
                                                <span class="avatar brround"
                                                    style="background-image: url(../uploads/profile/user-profile.png)">
                                                    <span class="avatar-status onlineOfflineIndicator"></span>
                                                </span>
                                            @else
                                                <span class="avatar brround"
                                                    style="background-image: url(../uploads/profile/{{ $users->image }})">
                                                    <span class="avatar-status onlineOfflineIndicator"></span>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="font-weight-semibold">{{ $users->name }}</span>
                                            <span class="d-block text-muted fs-12">{{ $users->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="$('#chatRoom').modal('hide')">{{ lang('Close') }}</button>
                    <button type="button" class="btn btn-primary savebutton" data-dismiss="modal" disabled>{{ lang('Create
                        Room') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Canned Responses --}}
    <div class="modal fade" id="canned-responses" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="form-label">{{ lang('Canned Responses') }}<a href=""
                                data-bs-target="#canned-add-responses" data-bs-toggle="modal"
                                class="fw-semibold font-weight-semibold text-primary text-decoration-underline float-end"></a></label>
                        <select name="livechatPosition" id="cannedResponses"
                            class="form-control select2 select2-show-search" required>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- image Viewer --}}
    <div class="liveChatImageViewer d-none">
        <span class="liveChatImageClose">×</span>
        <img class="liveChatImageTag" src="">
    </div>

@endsection

@section('scripts')
    <!-- INTERNAL Web Socket -->
    <script domainName='{{ url('') }}' wsPort="{{ setting('liveChatPort') }}"
        src="{{ asset('build/assets/plugins/livechat/web-socket.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}?v=<?php echo time(); ?>"></script>

    @vite(['resources/assets/js/select2.js'])

    <script>
        // Live Time Update
        document.addEventListener('DOMContentLoaded', () => {
            function updateTime() {
                const timeElements = document.querySelectorAll('.chat-time');

                timeElements.forEach(element => {
                    const initialTime = element.getAttribute('data-initial-24time');
                    const initialDate = new Date(initialTime);
                    const now = new Date();

                    const diffInMinutes = Math.floor((now - initialDate) / 60000);
                    let timeText;

                    if (diffInMinutes < 1) {
                        timeText = 'Just now';
                    } else if (diffInMinutes < 60) {
                        timeText = `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
                    } else {
                        const diffInHours = Math.floor(diffInMinutes / 60);
                        if (diffInHours < 24) {
                            timeText = `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                        } else {
                            const diffInDays = Math.floor(diffInHours / 24);
                            timeText = `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                        }
                    }

                    element.textContent = timeText;
                });
            }

            // Update time every minute
            updateTime();
            setInterval(updateTime, 60000);
        });
        document.addEventListener('DOMContentLoaded', () => {
            function updateTime() {
                const timeElements = document.querySelectorAll('.chat-time');

                timeElements.forEach(element => {
                    const initialTime = element.getAttribute('data-initial-24time');
                    const initialDate = new Date(initialTime);
                    const now = new Date();

                    const diffInMinutes = Math.floor((now - initialDate) / 60000);
                    let timeText;

                    if (diffInMinutes < 1) {
                        timeText = 'Just now';
                    } else if (diffInMinutes < 60) {
                        timeText = `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
                    } else {
                        const diffInHours = Math.floor(diffInMinutes / 60);
                        if (diffInHours < 24) {
                            timeText = `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                        } else {
                            const diffInDays = Math.floor(diffInHours / 24);
                            timeText = `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                        }
                    }

                    element.textContent = timeText;
                });
            }

            // Update time every minute
            updateTime();
            setInterval(updateTime, 60000);
        });
    </script>

    <script type="text/javascript">
        "use strict";


        // to rest the cretae group chat model

        document.querySelector(".chatRoomBtn").addEventListener("click",()=>{
            document.querySelectorAll("#onlineUlChatroom [type='checkbox']").forEach((ele)=>{
                ele.checked = false
            })
        })

        function handleClick(event) {
            event.preventDefault();

            // Remove focus from the anchor
            event.target.blur();

            // Set focus to another element (body) to ensure focus is removed
            document.body.focus();
        }

        // to show the skeleton loader in the mainchat
        if (localStorage.activeOperators) {
            // document.querySelector(".no-articles").classList.add("d-none")
            // document.querySelectorAll(".mainchat-skeleton-loader").forEach((ele) => {
            //     ele.classList.remove("d-none")
            // })
            // document.querySelector("#main-chat-content").classList.add("is-loading")
        }

        // Online users adding in the Chat Room model

        // For Check All
        document.querySelector('[name="example-checkbox1"]').onclick = (event) => {
            if (event.target.getAttribute('allchecked') == 'true') {
                document.querySelectorAll('#onlineUlChatroom input[type="checkbox"]').forEach((ele) => {
                    ele.checked = true
                })
                event.target.setAttribute('allchecked', false)
            } else {
                document.querySelectorAll('#onlineUlChatroom input[type="checkbox"]').forEach((ele) => {
                    ele.checked = false
                })
                event.target.setAttribute('allchecked', true)
            }
            if (document.querySelector("[name='chatRoom-checkbox']:checked")) {
                document.querySelector(".savebutton").disabled = false
            } else {
                document.querySelector(".savebutton").disabled = true
            }
        }

        // Check That all is checked are not
        document.querySelectorAll("#onlineUlChatroom .list-group-item[data-id] [name='chatRoom-checkbox']").forEach((
        ele) => {
            ele.onclick = () => {
                const checkboxes = document.querySelectorAll(
                    "#onlineUlChatroom .list-group-item[data-id] [name='chatRoom-checkbox']");
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                if (allChecked) {
                    document.querySelector('[name="example-checkbox1"]').checked = true
                } else {
                    document.querySelector('[name="example-checkbox1"]').checked = false
                }
                if (document.querySelector("[name='chatRoom-checkbox']:checked")) {
                    document.querySelector(".savebutton").disabled = false
                } else {
                    document.querySelector(".savebutton").disabled = true
                }
            }
        })

        // confirmation Function
        function confirmFunction(deleteUrl) {
            swal({
                    title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                    text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        localStorage.removeItem("activeOperators")
                        window.location.href = deleteUrl;
                    }
                })
        }

        // for adding the emojis
        document.addEventListener("DOMContentLoaded", function() {
            const emojiGrid = document.getElementById("emojiGrid");

            // Emojis data
            const emojisData = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌',
                '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔',
                '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '😮', '😯', '😦', '😧', '😨', '😰', '😱',
                '😳', '😵', '😡', '😠', '😤', '😖', '😆', '😋', '😷', '😎', '🤓', '🤠', '😸', '😺', '😻', '😼',
                '😽', '🙀', '😿', '😾', '👐', '🙌', '👏', '🤝', '👍', '👎', '👊', '✊', '🤛', '🤜', '🤞', '✌️',
                '🤘', '👌', '👈', '👉', '👆', '👇', '✋', '🤚', '🖐', '🖖', '👋', '🤙', '💪', '🖕', '✍️', '🙏',
                '🦶', '🦵', '💍', '💔', '❤️', '💙', '💚', '💛', '💜', '🧡', '💔', '❤️', '💕', '💞', '💓', '💗',
                '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉',
                '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋',
                '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢',
                '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇',
                '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️',
                '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️',
                '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊',
                '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘',
                '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪',
                '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝',
                '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥',
                '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘',
                '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞',
                '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩',
                '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏',
                '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟',
                '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉',
                '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍',
                '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦',
                '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟',
                '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗',
                '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉',
                '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋',
                '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢',
                '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇',
                '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️',
                '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️',
                '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊',
                '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘',
                '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪',
                '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝',
                '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥',
                '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈'
            ];

            let emojis = [...new Set(emojisData)]

            // Number of emojis per row
            const emojisPerRow = 8;

            // Generate emoji grid
            for (let i = 0; i < emojis.length; i++) {
                if (i % emojisPerRow === 0) {
                    // Start a new row
                    const newRow = document.createElement("li");
                    newRow.className = "d-flex";
                    emojiGrid.appendChild(newRow);
                }

                // Create emoji element
                const col = document.createElement("span");
                col.style.padding = "0.5rem"
                col.classList.add("dropdown-item", ); // Adjust the grid layout as needed
                col.onclick = () => {
                    insertEmoji(emojis[i])
                }
                col.innerHTML = `${emojis[i]}`;

                // Append emoji to the current row
                const currentRow = emojiGrid.lastElementChild;
                currentRow.appendChild(col);
            }
        })

        // To add option the Cannedmessages to model
        $.ajax({
            type: "get",
            url: '{{ route('admin.getCannedmessages') }}',
            success: function(data) {
                if (data.success == true) {

                    var selectElement = document.getElementById('cannedResponses');
                    selectElement.innerHTML = `<option></option>`

                    data.message.cannedmessages.forEach(function(option) {
                        var optionElement = document.createElement('option');
                        optionElement.value = option.messages;
                        optionElement.textContent = option.title;
                        selectElement.appendChild(optionElement);
                    });

                    $(document).ready(function() {
                        $('#cannedResponses').select2({
                            placeholder: "Canned Responses",
                            allowClear: true,
                            dropdownParent: $('#canned-responses')
                        });
                    });
                }
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });

        // To add value in the text area
        document.getElementById('cannedResponses').onchange = (ele => {
            document.querySelector("#auto-expand").value = ele.target.value.replace(/<[^>]*>/g, '').trim()
            // to remove the disabled from the Message send btn
            document.querySelector("#agentSendMessage").removeAttribute('disabled')
            document.querySelector("#agentSendMessage").classList.remove('disabled')

            // To Close the Model
            $('#canned-responses').modal('toggle');
        })
    </script>

    <script type="text/javascript">
        "use strict";
        // Variables
        var SITEURL = '{{ url('') }}';
        const autoUserInfo =JSON.parse('{!! Auth::user() !!}')

        console.log("autoUserInfo",autoUserInfo);


        function formatTime(inputTime) {
            const date = new Date(inputTime);

            const hours = date.getHours();
            const minutes = date.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

            const formattedTime = `${formattedHours}:${formattedMinutes}${ampm}`;
            return formattedTime;
        }

        let senderMessage = (data, img) => {
            let custLi = document.createElement("li");
            custLi.className =
                `chat-item-start ${data.message == `Group created by ${data.sender_username}` ? 'd-none' : ''}`
            custLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${img ? img : 'user-profile.png'})">
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                <span class="chatnameperson">${data.sender_username == autoUserInfo.name ? 'you' : data.sender_username}</span> <span class="msg-sent-time">${formatTime(data.created_at)}</span>
                            </span>
                            <div class="main-chat-msg">
                                ${data.message_type == "image" ? `
                                        <div onclick="AllFileViewer(this)" imageSrc="${data.message}" class="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? 'imageMessageLiveChat' : ''}" style="
                                            background-image: url('${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${SITEURL}/assets/images/svgs/file.svg`}');
                                            background-size: contain;
                                            height: ${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ?'15rem' :'8rem'};
                                            aspect-ratio: 1;
                                            background-repeat: no-repeat;
                                            background-color: transparent;
                                            background-position: center;
                                        ">
                                        </div>
                                        ` :
                                    `<div style="white-space: pre-line;"><p class="mb-0" >${data.message}</p></div>`
                                }
                            </div>
                        </div>
                    </div>
            `
            return custLi
        }

        let receiverMessage = (data, img) => {
            let agentLi = document.createElement("li");
            agentLi.className =
                `chat-item-start ${data.message == `Group created by ${data.sender_username == autoUserInfo.name ? 'you' : data.sender_username}` ? 'd-none' : ''}`
            agentLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${img ? img : 'user-profile.png'})">
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                ${data.sender_username == autoUserInfo.name ? 'you' : data.sender_username}
                                <span class="msg-sent-time">
                                    ${formatTime(data.created_at)}
                                    ${
                                        data.message_status == 'seen' ?
                                        `<span class="chat-read-mark d-inline-flex align-middle">
                                                <i class="ri-check-double-fill"></i>
                                            </span>` :
                                        data.message_status == 'delivered' ?
                                        `<span class="chat-read-icon d-inline-flex align-middle">
                                                <i class="ri-check-double-fill"></i>
                                            </span>` : data.message_status == 'sent' ?
                                        `<span class="chat-read-icon d-inline-flex align-middle">
                                                <i class="ri-check-fill"></i>
                                            </span>` : ``
                                    }
                                </span>
                            </span>
                            </span>
                            <div class="main-chat-msg">
                                ${data.message_type == "image" ? `
                                        <div onclick="AllFileViewer(this)" imageSrc="${data.message}" class="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? 'imageMessageLiveChat' : ''}" style="
                                            background-image: url('${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${SITEURL}/assets/images/svgs/file.svg`}');
                                            background-size: contain;
                                            height: ${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ?'15rem' :'8rem'};
                                            aspect-ratio: 1;
                                            background-repeat: no-repeat;
                                            background-color: transparent;
                                            background-position: center;
                                        ">
                                        </div>
                                    ` :
                                `<div style="white-space: pre-line;"><p class="mb-0" >${data.message}</p></div>`
                                }
                            </div>
                        </div>
                    </div>
            `

            return agentLi
        }

        // To viewe the Pdf Files
        let AllFileViewer = (ele) => {
            if (!ele.classList.contains("imageMessageLiveChat")) {
                window.open(ele.getAttribute("imagesrc"))
            }
        }

        // conversation Image Upload
        let liveChatFileUpload = "{{ setting('liveChatFileUpload') }}"
        let livechatMaxFileUpload = "{{ setting('AgentlivechatMaxFileUpload') }}"
        let livechatFileUploadMax = "{{ setting('AgentlivechatFileUploadMax') }}"
        let livechatFileUploadTypes = "{{ setting('AgentlivechatFileUploadTypes') }}"

        // Chat Image Upload
        if (document.querySelector("#chat-file-upload")) {
            document.querySelector("#chat-file-upload").addEventListener('change', () => {
                var fileInput = document.querySelector("#chat-file-upload");
                var file = fileInput.files[0];
                fileInput.value = ""
                var ThereIsError = false

                // For check the File Upload permissions
                if (livechatMaxFileUpload <= document.querySelectorAll(".image-uploaded .file-img").length) {
                    ThereIsError = {
                        errorMessage: "The maximum file upload limit has been exceeded."
                    };
                } else if (file.size > parseInt(livechatFileUploadMax) * 1024 * 1024) {
                    ThereIsError = {
                        errorMessage: `File size exceeds ${livechatFileUploadMax} MB. Please choose a smaller file.`
                    };
                } else if (livechatFileUploadTypes && !livechatFileUploadTypes.split(',').some(ext => file.name
                        .toLowerCase().toLowerCase().endsWith(ext.toLowerCase().trim()))) {
                    ThereIsError = {
                        errorMessage: `Invalid file extension. Please choose a file with ${livechatFileUploadTypes} extension(s).`
                    };
                } else {
                    ThereIsError = false
                }

                // For add the Upload indication
                let uploadingIndication = document.createElement("p")
                uploadingIndication.className = "fw-lighter ms-4"
                uploadingIndication.id = "uploadingIndication"
                uploadingIndication.innerHTML = `uploading...`

                // Upload The File
                if (file && !ThereIsError) {
                    // Adding the Uploding indication
                    document.querySelector(".image-uploaded").appendChild(uploadingIndication)

                    var formData = new FormData();
                    formData.append('chatFileUpload', file);

                    fetch('{{ route('admin.liveChatImageUpload') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            let uploadedFileName = data.uploadedfilename;
                            const imageDiv = document.createElement("div");
                            imageDiv.classList.add("file-img")
                            imageDiv.innerHTML = `
                            <img imageSrc="${SITEURL}/public/uploads/livechat/${uploadedFileName}" src="${uploadedFileName.toLowerCase().endsWith(".jpg") || uploadedFileName.toLowerCase().endsWith(".png") ? `${SITEURL}/public/uploads/livechat/${uploadedFileName}` : `${SITEURL}/assets/images/svgs/file.svg`}"
                                style="
                                width: 100%;
                                max-height: 55px;
                                border-radius: 5px;
                                ${uploadedFileName.toLowerCase().endsWith(".jpg") || uploadedFileName.toLowerCase().endsWith(".png") ? '' : 'height: 65px;'}"
                            >
                            <button class="btn-danger rounded-circle imageRemoveClick">
                                <i class="fe fe-x fs-12"></i>
                            </button>
                        `
                            // For the Image Remove Click
                            imageDiv.querySelector(".imageRemoveClick").onclick = (ele) => {
                                let fileImgElement = ele.currentTarget.closest(".file-img");
                                let data = {
                                    filename: uploadedFileName,
                                }

                                $.ajax({
                                    type: "post",
                                    url: '{{ route('admin.removeChatImage') }}',
                                    data: data,
                                    success: function(data) {
                                        // To remove the Image
                                        if (fileImgElement) {
                                            fileImgElement.remove();
                                        }
                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    }
                                });
                            }
                            // To remove the uploading indication
                            document.querySelector("#uploadingIndication").remove()
                            document.querySelector(".image-uploaded").appendChild(imageDiv)
                            document.querySelector("#agentSendMessage").classList.remove('disabled')
                            document.querySelector("#agentSendMessage").removeAttribute('disabled')
                        })
                        .catch(error => {
                            console.error('Error uploading file:', error);
                        });
                } else {
                    toastr.error(ThereIsError.errorMessage)
                }

            })
        }

        // Get the Emojis Values
        function insertEmoji(emoji) {
            let autoExpandElement = document.querySelector("#auto-expand").value
            document.querySelector("#auto-expand").value = autoExpandElement + emoji

            let agentSendMessageBtn = document.querySelector("#agentSendMessage")

            if (document.querySelector("#auto-expand").value) {
                agentSendMessageBtn.disabled = false
                agentSendMessageBtn.classList.remove('disabled')
            }
        }
        const autoID2 = '{{ Auth::user()->id }}'

        // Create Room button
        document.querySelector("#chatRoom .savebutton").onclick = () => {
            var checkboxes = document.querySelectorAll('#onlineUlChatroom input[type="checkbox"]');
            let selectedUsersArray = []
            let selectedUsersNmaesArray = []
            selectedUsersArray.push(autoID2)
            checkboxes.forEach(function(checkbox, index) {
                if (checkbox.checked) {
                    var dataId = checkbox.closest('li').getAttribute('data-id');
                    var usersName = checkbox.closest('li').querySelector('.font-weight-semibold').innerText;
                    if (dataId) {
                        selectedUsersArray.push(dataId)
                    }
                    if (index > 0) {
                        selectedUsersNmaesArray.push(usersName)
                    }
                }
            });
            $('#chatRoom').modal('hide')
            let data = {
                usersId: `[${String(selectedUsersArray)}]`,
                recieverUsersNames: selectedUsersNmaesArray.map(item => `'${item}'`).join(', '),
            }
            $.ajax({
                type: "post",
                url: SITEURL + "/admin/operators/groupbroadcastoperator",
                data: data,
                success: function(data) {
                    localStorage.setItem("activeOperators", data.group.unique_id)
                    location.reload()
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        // Available Agents search logic
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("availableAgentsSearchInput");
            const agentList = document.querySelector(".agents-list");
            const noUserMessage = document.getElementById(
            "noUserMessage"); // Assuming you have an element with the id "noUserMessage" for displaying the message

            // Clone the list items for resetting the order later
            const originalOrder = Array.from(agentList.children);

            searchInput.addEventListener("input", function() {
                const searchTerm = searchInput.value.trim().toLowerCase();

                // Filter the list based on the search term
                const filteredList = originalOrder.filter((li) => {
                    const name = li.querySelector(".font-weight-semibold").textContent
                .toLowerCase();
                    const email = li.querySelector(".text-muted").textContent.toLowerCase();
                    return name.includes(searchTerm) || email.includes(searchTerm);
                });

                // Clear the current list
                while (agentList.firstChild) {
                    agentList.removeChild(agentList.firstChild);
                }

                // Append the filtered list items back to the list
                if (filteredList.length > 0) {
                    filteredList.forEach((li) => {
                        agentList.appendChild(li);
                    });
                    noUserMessage.style.display = "none"; // Hide the message if there are users present
                } else {
                    noUserMessage.style.display = "block"; // Show the message if no user is present
                }

                // If the search input is empty, reset the order to the original
                if (searchTerm === "") {
                    originalOrder.forEach((li) => {
                        agentList.appendChild(li);
                    });
                    noUserMessage.style.display = "none"; // Hide the message if the search term is empty
                }
            });
        });

        // SideBar and Online operators loop click
        function sideMenuOpenClickFunction(ele) {
            // To add the skeleton loader
            document.querySelector("#main-chat-content").classList.add('is-loading')
            document.querySelector("#main-chat-content").innerHTML = `<div class="d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3 mainchat-skeleton-loader" id="operator-conversation-Info">
                <div class="me-2 lh-1">
                    <span class="avatar avatar-md brround">
                    </span>
                </div>
                <div class="flex-fill">
                    <p class="mb-0 fw-semibold fs-14">
                        <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">
                        </a>
                    </p>
                    <p class="text-muted mb-0 chatpersonstatus">
                    </p>
                </div>
                </div>
                <ul class="list-unstyled chat-content overflow-auto mainchat-skeleton-loader" id="operator-conversation" operator-id="1">
                    <li class="chat-day-label">
                        <span></span>
                    </li>
                    <li class="chat-item-start ">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-right: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start mt-2">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/1699439294.jpg)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="msg-sent-time">
                                </span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-left: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start mt-2">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/1699439294.jpg)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="msg-sent-time">
                                </span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-left: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start ">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-right: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start mt-2">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/1699439294.jpg)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="msg-sent-time">
                                </span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-left: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start ">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-right: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start mt-2">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="chatnameperson"></span> <span class="msg-sent-time"></span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-right: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="chat-item-start mt-2">
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/1699439294.jpg)">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                <span class="msg-sent-time">
                                </span>
                                </span>
                                <div class="main-chat-msg">
                                <div style="
                                    padding-left: 250px;
                                    ">
                                    <p class="mb-0"></p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>`

            // operators Click
            if (!ele.getAttribute("data-group-uniq")) {
                $.ajax({
                    type: "get",
                    url: SITEURL + `/admin/operators/singleoperator/${ele.getAttribute("data-id")}`,
                    success: function(data) {
                        let userconversation = data.userconversation
                        if (ele.querySelector(".chat-msg") && ele.querySelector(".unReadIndexNumber")) {
                            ele.querySelector(".chat-msg").classList.remove("font-weight-bold")
                        }
                        if (ele.querySelector(".unReadIndexNumber")) {
                            ele.querySelector(".unReadIndexNumber").remove()
                            // To Change the Make as read to Mark as unread
                            if (ele.querySelector(".markAsUnreadBtn") && ele.querySelector(".markAsUnreadBtn")
                                .innerText == 'Mark As Read') {
                                ele.querySelector('.markAsUnreadBtn').href = ele.querySelector(
                                    '.markAsUnreadBtn').href.replace("markasread", "markasunread")
                                ele.querySelector('.markAsUnreadBtn').innerHTML =
                                    `<i class="ri-chat-check-line align-middle me-2 fs-18"></i> Mark As Unread`
                            }
                        }

                        // Message conversation logic
                        let chatFooter = document.querySelector(".chat-footer")
                        chatFooter.classList.remove("d-none")

                        let mainChatContent = document.querySelector("#main-chat-content")
                        mainChatContent.classList.remove("d-none")
                        document.querySelector("#main-chat-content .no-articles")?.classList.add('d-none')
                        if (document.querySelector("#operator-conversation")) {
                            document.querySelector("#operator-conversation").remove()
                        }
                        if (document.querySelector("#operator-conversation-Info")) {
                            document.querySelector("#operator-conversation-Info").remove()
                        }

                        // For thr messages conversation
                        let conversation = document.createElement("ul");
                        conversation.className = "list-unstyled chat-content overflow-auto"
                        conversation.id = "operator-conversation"


                        // For the Chat data
                        function formatDateString(inputDateStr) {
                            const inputDate = new Date(inputDateStr);
                            const monthNames = [
                                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                            ];
                            const year = inputDate.getFullYear();
                            const month = monthNames[inputDate.getMonth()];
                            const day = inputDate.getDate();
                            const formattedDate = `${day},${month} ${year}`;

                            return formattedDate;
                        }
                        conversation.setAttribute('operator-id', ele.getAttribute("data-id"))
                        if (userconversation) {
                            let currentDate = null;
                            userconversation.map((chatdata) => {
                                const messageDate = formatDateString(chatdata.updated_at);
                                if (messageDate !== currentDate) {
                                    conversation.innerHTML += `
                                            <li class="chat-day-label">
                                                <span>${messageDate}</span>
                                            </li>
                                        `;
                                    currentDate = messageDate;
                                }
                                if (chatdata.sender_user_id != ele.getAttribute("data-id")) {
                                    conversation.appendChild(receiverMessage(chatdata, data.senderdata
                                        .image))
                                } else {
                                    conversation.appendChild(senderMessage(chatdata, data.receiverdata
                                        .image))
                                }
                            })
                        }

                        // For the receiver Info
                        let receiverInfo = document.createElement("div");
                        receiverInfo.className =
                            "d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3"
                        receiverInfo.id = "operator-conversation-Info"
                        receiverInfo.setAttribute('data-id', ele.getAttribute("data-id"))
                        receiverInfo.innerHTML = `
                                <div class="me-2 lh-1">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${data.receiverdata.image ? data.receiverdata.image : 'user-profile.png'})">
                                <span class="avatar-status ${Object.keys(agentMessageChannel.subscription.members.members).includes(ele.getAttribute("data-id")) ? 'bg-green' : 'bg-gray'} onlineOfflineIndicator "></span>
                                </span>
                                </div>
                                <div class="flex-fill">
                                <p class="mb-0 fw-semibold fs-14">
                                <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">${data.receiverdata.name}</a>
                                </p>
                                <p class="text-muted mb-0 chatpersonstatus">${Object.keys(agentMessageChannel.subscription.members.members).includes(ele.getAttribute("data-id")) ? 'online' : 'offline'}</p>
                                </div>
                                <div class="d-flex flex-wrap rightIcons">
                                <div class="dropdown ms-2 ${document.querySelector(`.checkforactive[data-id='${ele.getAttribute("data-id")}'] a[href*='conversationdelete']`) ? '' :'d-none'}">
                                <button aria-label="button" class="btn btn-icon btn-outline-light my-1 btn-wave waves-light waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="${document.querySelector(`.checkforactive[data-id='${ele.getAttribute("data-id")}'] a[href*='conversationdelete']`)?.getAttribute('href')}">
                                        Delete Chat
                                    </a>
                                </li>
                                </ul>
                                </div>
                                </div>
                            `

                        mainChatContent.appendChild(receiverInfo)
                        mainChatContent.appendChild(conversation)
                        // to remove the skeleton loader
                        document.querySelector("#main-chat-content").classList.remove("is-loading")

                        // To add the Image Viewer
                        if (document.querySelector(".imageMessageLiveChat")) {
                            // To Open the Image Viewer
                            document.querySelectorAll(".imageMessageLiveChat").forEach((element) => {
                                element.onclick = (ele) => {
                                    document.querySelector(".liveChatImageViewer").classList.remove(
                                        "d-none")
                                    document.querySelector(".liveChatImageViewer img").src = ele
                                        .target.getAttribute("imagesrc")
                                    document.querySelector(
                                            ".liveChatImageViewer .liveChatImageClose").onclick =
                                    () => {
                                            // To Close the Image Viewer
                                            document.querySelector(".liveChatImageViewer").classList
                                                .add("d-none")
                                        }
                                }
                            })
                        }

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector(
                            "#operator-conversation").scrollHeight)

                        // Set the sidebar active
                        localStorage.setItem("activeOperators", ele.getAttribute("data-id"))
                        document.querySelectorAll(".checkforactive").forEach((lielement) => {
                            lielement.classList.remove("active")
                            if (lielement.getAttribute("data-id") == ele.getAttribute("data-id")) {
                                lielement.classList.add("active")
                            }
                        })

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                })
            }

            // Group Click
            if (ele.getAttribute("data-group-uniq")) {
                $.ajax({
                    type: "get",
                    url: SITEURL + `/admin/operators/groupconversion/${ele.getAttribute("data-group-uniq")}`,
                    success: function(data) {
                        if (ele.querySelector(".chat-msg") && (ele.querySelector(".unReadIndexNumber") && ele
                                .querySelector(".unReadIndexNumber").innerText)) {
                            ele.querySelector(".chat-msg").classList.remove("font-weight-bold")
                        }
                        if (ele.querySelector(".unReadIndexNumber") && ele.querySelector(".unReadIndexNumber")
                            .innerText) {
                            ele.querySelector(".unReadIndexNumber").remove()
                        }

                        let userconversation = data.groupconversion
                        // Message conversation logic
                        let chatFooter = document.querySelector(".chat-footer")
                        chatFooter.classList.remove("d-none")

                        let mainChatContent = document.querySelector("#main-chat-content")
                        mainChatContent.classList.remove("d-none")
                        document.querySelector("#main-chat-content .no-articles")?.classList.add('d-none')

                        // To remove the messsage conversations
                        if (document.querySelector("#operator-conversation")) {
                            document.querySelector("#operator-conversation").remove()
                        }
                        // To remove the receiver Info
                        if (document.querySelector("#operator-conversation-Info")) {
                            document.querySelector("#operator-conversation-Info").remove()
                        }

                        // To make the include uses names in the correct way
                        function formatString(inputString) {
                            // Remove single quotes and split the string by commas
                            var parts = inputString.replace(/'/g, '').split(',');

                            // Trim each part and join them with a comma and space
                            var result = parts.map(function(part) {
                                return part.trim();
                            }).join(', ');

                            return result;
                        }

                        // For thr messages conversation
                        let conversation = document.createElement("ul");
                        conversation.className = "list-unstyled chat-content overflow-auto"
                        conversation.id = "operator-conversation"
                        conversation.setAttribute('group-id', ele.getAttribute("data-group-uniq"))
                        conversation.setAttribute('group-recievers_names', userconversation[userconversation
                            .length - 1].reciever_username)
                        conversation.setAttribute('group-recievers_id', userconversation[userconversation
                            .length - 1].receiver_user_id)

                        // For the Chat data
                        function formatDateString(inputDateStr) {
                            const inputDate = new Date(inputDateStr);
                            const monthNames = [
                                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                            ];
                            const year = inputDate.getFullYear();
                            const month = monthNames[inputDate.getMonth()];
                            const day = inputDate.getDate();
                            const formattedDate = `${day},${month} ${year}`;

                            return formattedDate;
                        }

                        if (userconversation) {
                            let currentDate = null;
                            let ChatNotification = false
                            userconversation.map((chatdata) => {
                                const messageDate = formatDateString(chatdata.updated_at);
                                if (messageDate !== currentDate) {
                                    conversation.innerHTML += `
                                        <li class="chat-day-label">
                                            <span>${messageDate}</span>
                                        </li>
                                    `;
                                    currentDate = messageDate;
                                    // For the ChatNotifications
                                    if (!ChatNotification) {
                                        // Created User Span
                                        const chatJoinNotify = document.createElement("li");
                                        chatJoinNotify.className = 'chat-join-notify'
                                        chatJoinNotify.innerHTML = `
                                        <span><b class="fw-bold">${chatdata.created_user_id == autoID ? 'You' : chatdata.sender_username}</b> created the group At ${formatTime(chatdata.created_at)}</span>
                                        `
                                        conversation.appendChild(chatJoinNotify)

                                        // Include Users Span
                                        chatdata.reciever_username.split(', ').map(name => name.replace(
                                            /'/g, '')).map((recieverUser => {
                                            let SpanElementJoinNotify = document
                                                .createElement("li")
                                            let AuthUserName = document.querySelector(
                                                '.user-info .mb-2').innerText
                                            SpanElementJoinNotify.className =
                                                'chat-join-notify'
                                            SpanElementJoinNotify.innerHTML =
                                                `<span><b class="fw-bold">${recieverUser == AuthUserName ? 'You' : recieverUser}</b> has joined the conversation in the group</span>`
                                            conversation.appendChild(SpanElementJoinNotify)
                                        }))

                                        ChatNotification = true
                                    }
                                }
                                if (chatdata.sender_user_id == '{{ Auth::user()->id }}') {
                                    conversation.appendChild(receiverMessage(chatdata,
                                        {!! json_encode(\App\Models\User::find(Auth::user()->id)->image) !!}))
                                } else {
                                    conversation.appendChild(senderMessage(chatdata, chatdata
                                        .sender_image))
                                }
                            })
                        }


                        // For the receiver Info
                        let receiverInfo = document.createElement("div");
                        receiverInfo.className =
                            "d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3"
                        receiverInfo.id = "operator-conversation-Info"
                        receiverInfo.setAttribute('data-id', ele.getAttribute("data-group-uniq"))
                        receiverInfo.innerHTML = `
                                <div class="avatar-list avatar-list-stacked me-3 d-flex">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png})">
                                </span>
                                </div>
                                <div class="flex-fill ms-3">
                                <p class="mb-0 fw-semibold fs-14">
                                <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open text-truncate" style="width: 502px;display: block;">${formatString(data.receiverUsersInfo)}</a>
                                </p>
                                </div>
                                <div class="d-flex flex-wrap rightIcons">
                                <div class="dropdown ms-2">
                                <button aria-label="button" class="btn btn-icon btn-outline-light my-1 btn-wave waves-light waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="operators/groupconversiondelete/${ele.getAttribute("data-group-uniq")}">Delete Chat</a></li>
                                </ul>
                                </div>
                                </div>
                        `

                        let iconElements = Array.from(ele.querySelector('.avatar-list-stacked')
                            .querySelectorAll(".avatar-sm"));
                        let modifiedHTMLArray = [];
                        iconElements.forEach((iconEle) => {
                            iconEle.classList.add('avatar');
                            iconEle.classList.remove('avatar-sm');
                            modifiedHTMLArray.push(iconEle.outerHTML);
                            iconEle.classList.add('avatar');
                            iconEle.classList.add('avatar-sm');
                        });

                        receiverInfo.querySelector('.avatar-list-stacked').innerHTML = modifiedHTMLArray.join(
                            '\n')

                        mainChatContent.appendChild(receiverInfo)
                        mainChatContent.appendChild(conversation)

                        // to remove the skeleton loader
                        document.querySelector("#main-chat-content").classList.remove("is-loading")

                        // To add the Image Viewer
                        if (document.querySelector(".imageMessageLiveChat")) {
                            // To Open the Image Viewer
                            document.querySelectorAll(".imageMessageLiveChat").forEach((element) => {
                                element.onclick = (ele) => {
                                    document.querySelector(".liveChatImageViewer").classList.remove(
                                        "d-none")
                                    document.querySelector(".liveChatImageViewer img").src = ele
                                        .target.getAttribute("imagesrc")
                                    document.querySelector(
                                            ".liveChatImageViewer .liveChatImageClose").onclick =
                                    () => {
                                            // To Close the Image Viewer
                                            document.querySelector(".liveChatImageViewer").classList
                                                .add("d-none")
                                        }
                                }
                            })
                        }

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector(
                            "#operator-conversation").scrollHeight)

                        // Set the sidebar active
                        localStorage.setItem("activeOperators", ele.getAttribute("data-group-uniq"))
                        document.querySelectorAll(".checkforactive").forEach((lielement) => {
                            lielement.classList.remove("active")
                            if (lielement.getAttribute("data-group-uniq") == ele.getAttribute(
                                    "data-group-uniq")) {
                                lielement.classList.add("active")
                            }
                        })
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        }


        document.querySelectorAll(".agent-detail,.checkforactive").forEach((ele) => {
            ele.onclick = () => {
                sideMenuOpenClickFunction(ele)
            }
        })

        // Prevent the li loop click from the dropdown
        document.querySelectorAll('#chat-msg-scroll .chat-actions').forEach((ele) => {
            ele.addEventListener('click', function(event) {
                event.stopPropagation();
            })

            // markAsUnreadBtn
            if (ele.querySelector('.markAsUnreadBtn')) {
                ele.querySelector('.markAsUnreadBtn').addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (localStorage.activeOperators == ele.closest('.checkforactive').getAttribute(
                            'data-group-uniq') || localStorage.activeOperators == ele.closest(
                            '.checkforactive').getAttribute('data-id')) {
                        localStorage.removeItem('activeOperators')
                    }
                })
            }

            // Delete Btn
            if (ele.querySelector('[href*="conversationdelete"]')) {
                ele.querySelector('[href*="conversationdelete"]').addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (localStorage.activeOperators == ele.closest('.checkforactive').getAttribute(
                            'data-group-uniq') || localStorage.activeOperators == ele.closest(
                            '.checkforactive').getAttribute('data-id')) {
                        localStorage.removeItem('activeOperators')
                    }
                })
            }
        })

        const autoUser = '{{ Auth::user()->name }}'
        const autoID = '{{ Auth::user()->id }}'
        let chatOpenAgent = []

        // Send Message button
        document.querySelector("#agentSendMessage").onclick = () => {
            let operatorConversation = document.querySelector("#operator-conversation")

            // Agent Message Send
            if (!operatorConversation.getAttribute('group-id')) {

                // To remove the no chat discussion message
                document.querySelector("#chat-msg-scroll .noDiscussionsProgress")?.remove()

                let PresentTimeFormatted =
                    `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
                let data = {
                    message: document.querySelector("#auto-expand").value,
                    receiverId: document.querySelector("#operator-conversation").getAttribute('operator-id'),
                    messageStatus: Object.keys(agentMessageChannel.subscription.members.members).includes(
                            operatorConversation.getAttribute('operator-id')) ?
                        chatOpenAgent.includes(parseInt(operatorConversation.getAttribute('operator-id'))) ?
                        `seen` : `delivered` : `sent`,
                }

                const messageDeliveryStatus = Object.keys(agentMessageChannel.subscription.members.members).includes(
                        operatorConversation.getAttribute('operator-id')) ?
                    chatOpenAgent.includes(parseInt(operatorConversation.getAttribute('operator-id'))) ? `
                <span class="chat-read-mark d-inline-flex align-middle">
                    <i class="ri-check-double-fill"></i>
                </span>
                ` :
                    `<span class="chat-read-icon d-inline-flex align-middle">
                    <i class="ri-check-double-fill"></i>
                </span>
                ` :
                    `<span class="chat-read-icon d-inline-flex align-middle">
                        <i class="ri-check-fill"></i>
                </span>`;


                let senderMessage = document.createElement("li");
                senderMessage.className = "chat-item-start"
                senderMessage.innerHTML = `
                <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(${document.querySelector(".leading-none img").src})">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                    ${autoUser}
                                    <span class="msg-sent-time">
                                        ${PresentTimeFormatted}
                                        ${messageDeliveryStatus}
                                    </span>
                                </span>
                                <div class="main-chat-msg">
                                    <div style="white-space: pre-line;">
                                        <p class="mb-0">${data.message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                `

                if (document.querySelector("#auto-expand").value.trim()) {
                    if (operatorConversation.lastElementChild && operatorConversation.lastElementChild.id ===
                        "agentTyping") {
                        operatorConversation.insertBefore(senderMessage, operatorConversation.lastElementChild);
                    } else {
                        operatorConversation.appendChild(senderMessage);
                    }

                    // After message sent show the message in the top
                    document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat) => {
                        if (operatorsChat.getAttribute('data-id') == document.querySelector(
                                "#operator-conversation").getAttribute('operator-id')) {
                            operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                            operatorsChat.querySelector(".chat-msg").innerText = data.message.replace(/\n/g,
                                ' ')
                            operatorsChat.querySelector(".chat-time").innerText = new Date()
                            .toLocaleTimeString()
                            if (operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                    '.d-inline-flex')) {
                                operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                    '.d-inline-flex').remove()
                            }
                            if (document.querySelector("#messageStatusDiv")) {
                                document.querySelector("#messageStatusDiv").remove()
                            }
                            // To remove the Make as unread button
                            if (operatorsChat.querySelector(".markAsUnreadBtn")) {
                                operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                            }
                            const messageStatus = document.createElement("div");
                            messageStatus.id = "messageStatusDiv"
                            messageStatus.innerHTML = messageDeliveryStatus
                            operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                            document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document
                                .querySelector("#chat-msg-scroll > li"));
                        }
                    })

                    // To Scroll Down the Conversation
                    operatorConversation.scrollBy(0, operatorConversation.scrollHeight)

                    // Message Send ajax request
                    $.ajax({
                        type: "post",
                        url: SITEURL + "/admin/operators/broadcastoperator",
                        data: data,
                        success: function(data) {
                            // New Message will add to sidebar
                            const existingLi = Array.from(document.querySelectorAll("#chat-msg-scroll>li"))
                                .find(li => li.getAttribute('data-id') == document.querySelector(
                                    "#operator-conversation").getAttribute('operator-id'));
                            if (!existingLi) {
                                const newMessageLiElement = document.createElement("li");
                                newMessageLiElement.className = "checkforactive active"
                                newMessageLiElement.setAttribute("data-id", document.querySelector(
                                    "#operator-conversation").getAttribute('operator-id'))
                                newMessageLiElement.innerHTML = `
                                <div class="d-flex align-items-center">
                                                <div class="me-2 lh-1">
                                                    <span class="avatar brround" style="${document.querySelector("#operator-conversation-Info .avatar-md").style.cssText.replace('"',"")}"></span>
                                                    </div>
                                                <div class="flex-fill">
                                                    <div class="mb-0 d-flex align-items-center justify-content-between">
                                                        <div class="font-weight-semibold">
                                                            <a href="javascript:void(0);">${document.querySelector("#main-chat-content .responsive-userinfo-open").innerText}</a>

                                                        </div>
                                                        <div class="float-end text-muted fw-normal fs-12 chat-time">${new Date().toLocaleTimeString()}</div>
                                                        <div class="dropdown chat-actions lh-1">
                                                            <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fe fe-more-vertical fs-18"></i>
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="operators/conversationdelete/${data.MessageSent.unique_id}"><i class="ri-chat-delete-line align-middle me-2 fs-18"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="chat-msg text-truncate fs-13 text-default">${data.MessageSent.message}</span>
                                                        ${messageDeliveryStatus}
                                                    </div>
                                                </div>
                                            </div>
                                `
                                newMessageLiElement.onclick = () => {
                                    sideMenuOpenClickFunction(newMessageLiElement);
                                }
                                document.querySelector("#chat-msg-scroll").insertBefore(newMessageLiElement,
                                    document.querySelector("#chat-msg-scroll > li"));

                            }
                        },
                        error: function(error) {
                            if (error.responseJSON) {
                                // toastr.error("The message field cannot be null")
                            }
                        }
                    });
                }

                // to slow the Image Upload Message
                let imageUploadTimeout = document.querySelector("#auto-expand").value ? 1000 : 0
                setTimeout(() => {
                    // For the Image Upload
                    if (document.querySelectorAll(".image-uploaded .file-img").length) {
                        document.querySelectorAll(".image-uploaded .file-img img").forEach((ele) => {
                            let data = {
                                message: ele.getAttribute('imageSrc'),
                                receiverId: document.querySelector("#operator-conversation")
                                    .getAttribute('operator-id'),
                                messageStatus: Object.keys(agentMessageChannel.subscription.members
                                        .members).includes(operatorConversation.getAttribute(
                                        'operator-id')) ?
                                    chatOpenAgent.includes(parseInt(operatorConversation
                                        .getAttribute('operator-id'))) ? `seen` : `delivered` :
                                    `sent`,
                                messageType: "image"
                            }

                            let imageMessage = document.createElement("li");
                            imageMessage.className = "chat-item-start"
                            imageMessage.innerHTML = `
                            <div class="chat-list-inner">
                                <div class="chat-user-profile">
                                    <span class="avatar avatar-md brround" style="background-image: url(${document.querySelector(".leading-none img").src})">
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <span class="chatting-user-info">
                                        You
                                        <span class="msg-sent-time">
                                            ${PresentTimeFormatted}
                                            ${messageDeliveryStatus}
                                        </span>
                                    </span>
                                    <div class="main-chat-msg">
                                        <div onclick="AllFileViewer(this)" imagesrc="${data.message}" class="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? 'imageMessageLiveChat' : ''}" style="
                                                background-image: url('${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${SITEURL}/assets/images/svgs/file.svg`}');
                                                background-size: contain;
                                                height: ${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ?'15rem' :'8rem'};
                                                aspect-ratio: 1;
                                                background-repeat: no-repeat;
                                                background-color: transparent;
                                                background-position: center; ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `

                            // To add the image Preview Onclick
                            if (imageMessage.querySelector(".imageMessageLiveChat")) {
                                // To Open the Image Viewer
                                imageMessage.querySelector(".imageMessageLiveChat").onclick = (ele) => {
                                    document.querySelector(".liveChatImageViewer").classList.remove(
                                        "d-none")
                                    document.querySelector(".liveChatImageViewer img").src = ele
                                        .target.getAttribute("imagesrc")
                                    document.querySelector(
                                            ".liveChatImageViewer .liveChatImageClose").onclick =
                                    () => {
                                            // To Close the Image Viewer
                                            document.querySelector(".liveChatImageViewer").classList
                                                .add("d-none")
                                        }
                                }
                            }

                            // To add The Image In the Chat
                            if (operatorConversation.lastElementChild && operatorConversation
                                .lastElementChild.id === "agentTyping") {
                                operatorConversation.insertBefore(imageMessage, operatorConversation
                                    .lastElementChild);
                            } else {
                                operatorConversation.appendChild(imageMessage);
                            }

                            // To Scroll Down the Conversation
                            document.querySelector("#operator-conversation").scrollBy(0, document
                                .querySelector("#operator-conversation").scrollHeight)
                            document.querySelector('.image-uploaded').innerHTML = ""

                            $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/operators/broadcastoperator",
                                data: data,
                                success: function(data) {
                                    // To remove the Added Images

                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                        })
                    }
                }, imageUploadTimeout);

                document.querySelector("#auto-expand").value = ''

            }

            // Group Message send
            if (operatorConversation.getAttribute('group-id')) {

                // message status
                // delivered Status Logic
                const onlineUsersarray = Object.keys(agentMessageChannel.subscription.members.members);
                const valuesToCheck = JSON.parse(document.querySelector("#operator-conversation").getAttribute(
                    'group-recievers_id'));
                const allValuesIncluded = valuesToCheck.every(value => onlineUsersarray.includes(String(value)));

                // To add the seen users id in the DB
                const seenUsersIds = [...new Set(chatOpenAgent.find(obj => obj[operatorConversation.getAttribute(
                    'group-id')])[operatorConversation.getAttribute('group-id')])]

                // Seen Status Logic
                const allAreinOpenstate = chatOpenAgent.find(obj => obj.hasOwnProperty(document.querySelector(
                    "#operator-conversation").getAttribute('group-id')))
                const seenStatusvalue = valuesToCheck.every(value => allAreinOpenstate[document.querySelector(
                    "#operator-conversation").getAttribute('group-id')].includes(value))

                let PresentTimeFormatted =
                    `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
                let data = {
                    message: document.querySelector("#auto-expand").value,
                    recieverUsersNames: document.querySelector("#operator-conversation").getAttribute(
                        'group-recievers_names'),
                    usersId: document.querySelector("#operator-conversation").getAttribute('group-recievers_id'),
                    messageStatus: seenStatusvalue ? 'seen' : allValuesIncluded ? 'delivered' : 'sent',
                    seenUserIds: `[${seenUsersIds}]`
                }

                const messageDeliveryStatus = data.messageStatus == 'seen' ? `
                <span class="chat-read-mark d-inline-flex align-middle">
                    <i class="ri-check-double-fill"></i>
                </span>
                ` : data.messageStatus == 'delivered' ?
                    `<span class="chat-read-icon d-inline-flex align-middle">
                    <i class="ri-check-double-fill"></i>
                </span>
                ` :
                    `<span class="chat-read-icon d-inline-flex align-middle">
                        <i class="ri-check-fill"></i>
                </span>`;


                let senderMessage = document.createElement("li");
                senderMessage.className = "chat-item-start"
                senderMessage.innerHTML = `
                <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(${document.querySelector(".leading-none img").src})">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                    ${autoUser}
                                    <span class="msg-sent-time">
                                        ${PresentTimeFormatted}
                                        ${messageDeliveryStatus}
                                    </span>
                                </span>
                                <div class="main-chat-msg">
                                    <div style="white-space: pre-line;">
                                        <p class="mb-0">${data.message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                `

                if (document.querySelector("#auto-expand").value.trim()) {
                    if (operatorConversation.lastElementChild && operatorConversation.lastElementChild.id ===
                        "agentTyping") {
                        operatorConversation.insertBefore(senderMessage, operatorConversation.lastElementChild);
                    } else {
                        operatorConversation.appendChild(senderMessage);
                    }

                    // To Scroll Down the Conversation
                    operatorConversation.scrollBy(0, operatorConversation.scrollHeight)

                    // After message sent show the message in the top
                    document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat) => {
                        if (operatorsChat.getAttribute('data-group-uniq') == document.querySelector(
                                "#operator-conversation").getAttribute('group-id')) {
                            operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                            operatorsChat.querySelector(".chat-msg").innerText = data.message.replace(/\n/g,
                                ' ')
                            operatorsChat.querySelector(".chat-time").innerText = new Date()
                            .toLocaleTimeString()
                            if (operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                    '.d-inline-flex')) {
                                operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                    '.d-inline-flex').remove()
                            }
                            if (document.querySelector("#messageStatusDiv")) {
                                document.querySelector("#messageStatusDiv").remove()
                            }
                            // To remove the Make as unread button
                            if (operatorsChat.querySelector(".markAsUnreadBtn")) {
                                operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                            }
                            const messageStatus = document.createElement("div");
                            messageStatus.className = "ms-auto me-2"
                            messageStatus.id = "messageStatusDiv"
                            messageStatus.innerHTML = messageDeliveryStatus
                            // operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                            operatorsChat.querySelector(".chat-msg").parentNode.insertBefore(messageStatus,
                                operatorsChat.querySelector(".chat-msg").parentNode.querySelector(
                                    '.avatar-list'))
                            document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document
                                .querySelector("#chat-msg-scroll > li"));
                        }
                    })

                    $.ajax({
                        type: "post",
                        url: SITEURL +
                            `/admin/operators/groupconversionstore/${operatorConversation.getAttribute('group-id')}`,
                        data: data,
                        success: function(data) {

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }

                // to slow the Image Upload Message
                let imageUploadTimeout = document.querySelector("#auto-expand").value ? 1000 : 0
                setTimeout(() => {
                    // For the Image Upload
                    if (document.querySelectorAll(".image-uploaded .file-img").length) {
                        document.querySelectorAll(".image-uploaded .file-img img").forEach((ele) => {
                            let data = {
                                message: ele.getAttribute('imageSrc'),
                                recieverUsersNames: document.querySelector("#operator-conversation")
                                    .getAttribute('group-recievers_names'),
                                usersId: document.querySelector("#operator-conversation")
                                    .getAttribute('group-recievers_id'),
                                messageStatus: seenStatusvalue ? 'seen' : allValuesIncluded ?
                                    'delivered' : 'sent',
                                seenUserIds: `[${seenUsersIds}]`,
                                messageType: "image"
                            }

                            let imageMessage = document.createElement("li");
                            imageMessage.className = "chat-item-start"
                            imageMessage.innerHTML = `
                            <div class="chat-list-inner">
                                <div class="chat-user-profile">
                                    <span class="avatar avatar-md brround" style="background-image: url(${document.querySelector(".leading-none img").src})">
                                    </span>
                                </div>
                                <div class="ms-3">
                                    <span class="chatting-user-info">
                                        You
                                        <span class="msg-sent-time">
                                            ${PresentTimeFormatted}
                                            ${messageDeliveryStatus}
                                        </span>
                                    </span>
                                    <div class="main-chat-msg">
                                        <div onclick="AllFileViewer(this)" imagesrc="${data.message}" class="${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? 'imageMessageLiveChat' : ''}" style="
                                                background-image: url('${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ? data.message : `${SITEURL}/assets/images/svgs/file.svg`}');
                                                background-size: contain;
                                                height: ${data.message.toLowerCase().endsWith(".jpg") || data.message.toLowerCase().endsWith(".png") ?'15rem' :'8rem'};
                                                aspect-ratio: 1;
                                                background-repeat: no-repeat;
                                                background-color: transparent;
                                                background-position: center;
                                                ">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            `

                            // To add the image Preview Onclick
                            if (imageMessage.querySelector(".imageMessageLiveChat")) {
                                // To Open the Image Viewer
                                imageMessage.querySelector(".imageMessageLiveChat").onclick = (ele) => {
                                    document.querySelector(".liveChatImageViewer").classList.remove(
                                        "d-none")
                                    document.querySelector(".liveChatImageViewer img").src = ele
                                        .target.getAttribute("imagesrc")
                                    document.querySelector(
                                            ".liveChatImageViewer .liveChatImageClose").onclick =
                                    () => {
                                            // To Close the Image Viewer
                                            document.querySelector(".liveChatImageViewer").classList
                                                .add("d-none")
                                        }
                                }
                            }

                            // To add The Image In the Chat
                            if (operatorConversation.lastElementChild && operatorConversation
                                .lastElementChild.id === "agentTyping") {
                                operatorConversation.insertBefore(imageMessage, operatorConversation
                                    .lastElementChild);
                            } else {
                                operatorConversation.appendChild(imageMessage);
                            }

                            // To Scroll Down the Conversation
                            document.querySelector("#operator-conversation").scrollBy(0, document
                                .querySelector("#operator-conversation").scrollHeight)

                            // To remove the Added Images
                            document.querySelector('.image-uploaded').innerHTML = ""

                            $.ajax({
                                type: "post",
                                url: SITEURL +
                                    `/admin/operators/groupconversionstore/${operatorConversation.getAttribute('group-id')}`,
                                data: data,
                                success: function(data) {


                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                            });
                        })
                    }
                }, imageUploadTimeout);

                document.querySelector("#auto-expand").value = ''
            }

        }

        // Sending Typing
        var debounceTimeout;
        var debounceTimeout2;
        var debounceTimeout3;

        document.querySelector("#auto-expand").oninput = (ele) => {
            let agentSendMessageBtn = document.querySelector("#agentSendMessage")

            // For the message Send Btn Disabled and Enabled
            if (ele.target.value) {
                agentSendMessageBtn.disabled = false
                agentSendMessageBtn.classList.remove('disabled')
            } else {
                agentSendMessageBtn.disabled = true
                agentSendMessageBtn.classList.add('disabled')
            }

            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                let data = {
                    message: null,
                    receiverId: document.querySelector("#operator-conversation").getAttribute(
                        'operator-id') ? document.querySelector("#operator-conversation").getAttribute(
                            'operator-id') : document.querySelector("#operator-conversation").getAttribute(
                            'group-id'),
                    typingMessage: ele.target.value
                }

                $.ajax({
                    type: "post",
                    url: SITEURL + "/admin/operators/agentbroadcastmessagetyping",
                    data: data,
                    success: function(data) {

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }, 500);
        }

        // Enter Message Send Function
        document.getElementById('auto-expand').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                document.getElementById('agentSendMessage').click();
            }
        });

        // Last Message For the Side bar
        let pastMessage = ""
        let SidebarPastUserId
        let sibeBarTypingAllowVar = true


        const agentMessageChannel = Echo.join('agentMessage')
        agentMessageChannel.listen('AgentMessageEvent', (socket) => {
            let groupIncludeDB = socket.groupInclude ? JSON.parse(socket.groupInclude) : []

            // Conversation Messages
            let operatorConversation = document.querySelector("#operator-conversation")
            if (
                (autoID == socket.receiverId && operatorConversation?.getAttribute("operator-id") == socket
                    .senderId && socket.message != null) || // Agent Conversation Messages
                (socket.receiverId == operatorConversation?.getAttribute("group-id") && socket.message != null &&
                    socket.senderId != autoID && groupIncludeDB.includes(parseInt(autoID))
                    ) // Group Conversation Messages
            ) {
                // For the current Time
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const period = hours >= 12 ? "PM" : "AM";

                const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;

                let operatorMessage = document.createElement("li");
                operatorMessage.className = "chat-item-start"
                operatorMessage.innerHTML = `
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${socket.senderImage ? socket.senderImage : 'user-profile.png'})">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                    <span class="chatnameperson">${socket.senderName}</span> <span class="msg-sent-time">${formattedTime}</span>
                                </span>
                                <div class="main-chat-msg">
                                    ${socket.messageType == "image" ? `
                                                        <div onclick="AllFileViewer(this)"imageSrc="${socket.message}" class="${socket.message.toLowerCase().endsWith(".jpg") || socket.message.toLowerCase().endsWith(".png") ? 'imageMessageLiveChat' : ''}" style="
                                                            background-image: url('${socket.message.toLowerCase().endsWith(".jpg") || socket.message.toLowerCase().endsWith(".png") ? socket.message : `${SITEURL}/assets/images/svgs/file.svg`}');
                                                            background-size: contain;
                                                            height: ${socket.message.toLowerCase().endsWith(".jpg") || socket.message.toLowerCase().endsWith(".png") ?'15rem' :'8rem'};
                                                            aspect-ratio: 1;
                                                            background-repeat: no-repeat;
                                                            background-color: transparent;
                                                            background-position: center;
                                                        ">
                                                        </div>
                                                    ` :
                                                `<div style="white-space: pre-line;"><p class="mb-0" >${socket.message}</p></div>`
                                    }
                                </div>
                            </div>
                        </div>
                    `

                if (document.querySelector("#agentTyping")) {
                    document.querySelector("#agentTyping").remove()
                }
                operatorConversation.appendChild(operatorMessage)

                // To get the seen indaction
                $.ajax({
                    type: "get",
                    url: SITEURL +
                        `/admin/operators/singleoperator/${document.querySelector("#operator-conversation-Info").getAttribute("data-id")}`,
                    success: function(data) {},
                    error: function(data) {},
                })

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector(
                    "#operator-conversation").scrollHeight)
            }

            //Typing induction in Conversation
            if (
                (autoID == socket.receiverId && operatorConversation?.getAttribute("operator-id") == socket
                    .senderId && socket.typingMessage != null && socket.senderId != autoID) ||
                // Agent Typing Condition
                (socket.receiverId == operatorConversation?.getAttribute("group-id") && socket.typingMessage !=
                    null && socket.senderId != autoID && groupIncludeDB.includes(parseInt(autoID))
                    ) // Group Typing Condition
            ) {
                if (document.querySelector("#agentTyping")) {
                    document.querySelector("#agentTyping").remove()
                }

                let operatorMessage = document.createElement("li");
                operatorMessage.className = "chat-item-start"
                operatorMessage.id = "agentTyping"
                operatorMessage.innerHTML = `
                        <div class="chat-list-inner">
                            <div class="chat-user-profile">
                                <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${socket.senderImage ? socket.senderImage : 'user-profile.png'})">
                                </span>
                            </div>
                            <div class="ms-3">
                                <span class="chatting-user-info">
                                    <span class="chatnameperson">${socket.senderName}</span>
                                </span>
                                <div class="main-chat-msg">
                                    <div class="d-flex">
                                        <b class="ms-3">Typing...</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `

                operatorConversation.appendChild(operatorMessage)

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector(
                    "#operator-conversation").scrollHeight)

                clearTimeout(debounceTimeout3);

                debounceTimeout3 = setTimeout(function() {
                    if (document.querySelector("#agentTyping")) {
                        document.querySelector("#agentTyping").remove()
                    }
                }, 5000)
            }

            // Typing induction in Sidebar
            let groupLiElementExisting = document.querySelector(
                `#chat-msg-scroll>li[data-group-uniq='${socket.receiverId}']`)
            // if(
            //     (autoID == socket.receiverId && socket.typingMessage != null && sibeBarTypingAllowVar) || // Agent Sidebar Typing Condition
            //     (groupLiElementExisting && groupIncludeDB.includes(parseInt(autoID)) && sibeBarTypingAllowVar) // Group Sidebar Typing Condition
            // ){
            //     document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat)=>{
            //         if(
            //             (!groupLiElementExisting && operatorsChat.getAttribute('data-id') == socket.senderId) || // Agent
            //             (operatorsChat.getAttribute('data-group-uniq') == socket.receiverId && socket.senderId != autoID && groupIncludeDB.includes(parseInt(autoID))) // Group
            //         ){
            //             if(!operatorsChat.querySelector(".chat-msg").innerText.includes('Typing ...') && ! operatorsChat.querySelector(".chat-msg").innerText.includes('Group created by')){
            //                 pastMessage = operatorsChat.querySelector(".chat-msg").innerText
            //             }

            //             SidebarPastUserId = socket.receiverId
            //             operatorsChat.querySelector(".chat-msg").innerText = groupLiElementExisting ? `${socket.senderName} Typing ...` : "Typing ..."

            //             clearTimeout(debounceTimeout2);

            //             debounceTimeout2 = setTimeout(function() {
            //                 // if(operatorsChat.querySelector(".chat-msg")){
            //                 //     operatorsChat.querySelector(".chat-msg").innerText = pastMessage
            //                 // }
            //             }, 5000)
            //         }
            //     })
            // }

            // Chat Open induction
            if (
                (socket.openedUser && !socket.message) || // Agent Condition
                (document.querySelector(`.checkforactive[data-group-uniq='${socket.openedUser}']`) && !socket
                    .message) // Group Condition
            ) {

                // To add the old message to the side bar
                if (document.querySelector(`.checkforactive[data-group-uniq='${SidebarPastUserId}']`)) {
                    document.querySelector(`.checkforactive[data-group-uniq='${SidebarPastUserId}']`).querySelector(
                        '.chat-msg').innerText = pastMessage
                }
                if (document.querySelector(`.checkforactive[data-id='${SidebarPastUserId}']`)) {
                    document.querySelector(`.checkforactive[data-id='${SidebarPastUserId}']`).querySelector(
                        '.chat-msg').innerText = pastMessage
                }

                if (socket.openedUser == autoID) {
                    if (!chatOpenAgent.includes(socket.senderId)) {
                        // Agent Array add
                        chatOpenAgent.push(socket.senderId)
                    }
                    if (chatOpenAgent.includes(parseInt(operatorConversation?.getAttribute("operator-id")))) {
                        document.querySelectorAll("#operator-conversation .chat-read-icon").forEach((ele) => {
                            // To add the Message view induction in chat
                            ele.classList.remove("chat-read-icon")
                            ele.classList.add("chat-read-mark")
                            ele.querySelector('i').className = "ri-check-double-fill"

                            // To add the Message view induction in sidebar
                            let SideBarProfile = document.querySelector(
                                `.checkforactive[data-id='${parseInt(operatorConversation?.getAttribute("operator-id"))}']`
                                ).querySelector(".chat-read-icon")
                            if (SideBarProfile) {
                                SideBarProfile.classList.add('chat-read-mark')
                                SideBarProfile.querySelector('i').className = "ri-check-double-fill"
                                SideBarProfile.classList.remove('chat-read-icon')
                            }
                        })
                    }
                } else {
                    const valueToRemove = socket.senderId;
                    chatOpenAgent = chatOpenAgent.filter(item => item !== valueToRemove);
                }


                // Group Array Add logic
                if (document.querySelector(`.checkforactive[data-group-uniq='${socket.openedUser}']`)) {
                    // Group Array Add
                    const existingEntryIndex = chatOpenAgent.findIndex(entry => Object.keys(entry)[0] === socket
                        .openedUser);
                    if (existingEntryIndex !== -1) {
                        const existingArray = chatOpenAgent[existingEntryIndex][socket.openedUser];
                        if (!existingArray.includes(socket.senderId)) {
                            existingArray.push(socket.senderId);

                            // Remove sender ID from other entries
                            chatOpenAgent.forEach((entry, index) => {
                                const key = Object.keys(entry)[0];
                                const existingArray = entry[key];

                                if (index !== existingEntryIndex) {
                                    const indexOfNumber = existingArray.indexOf(socket.senderId);
                                    if (indexOfNumber !== -1) {
                                        existingArray.splice(indexOfNumber, 1);
                                    }
                                }
                            });
                        }
                    } else {
                        // To remove the user id from old data
                        chatOpenAgent.forEach(entry => {
                            const key = Object.keys(entry)[0];
                            const existingArray = entry[key];

                            const indexOfNumber = existingArray.indexOf(socket.senderId);
                            if (indexOfNumber !== -1) {
                                existingArray.splice(indexOfNumber, 1);
                            }
                        });

                        chatOpenAgent.push({
                            [socket.openedUser]: [socket.senderId]
                        });
                    }

                    // To add the Message view induction in chat
                    if (document.querySelector("#operator-conversation")) {
                        const valuesToCheck = JSON.parse(document.querySelector("#operator-conversation")
                            .getAttribute('group-recievers_id'));
                        const existingEntryIndex = chatOpenAgent.find(obj => obj.hasOwnProperty(document
                            .querySelector("#operator-conversation").getAttribute('group-id')))
                        const allValuesIncluded = valuesToCheck ? valuesToCheck.every(value => existingEntryIndex[
                                document.querySelector("#operator-conversation").getAttribute('group-id')]
                            .includes(value)) : false;

                        if (document.querySelector(`#operator-conversation[group-id='${socket.openedUser}']`) &&
                            allValuesIncluded) {
                            // For the Side bar induction
                            let SideBarElement = document.querySelector(
                                `.checkforactive[data-group-uniq='${document.querySelector("#operator-conversation").getAttribute('group-id')}']`
                                )
                            if (SideBarElement.querySelector('.chat-read-icon')) {
                                SideBarElement.querySelector('.chat-read-icon').classList.add('chat-read-mark')
                                SideBarElement.querySelector('.chat-read-icon').classList.remove('chat-read-icon')
                            }


                            // For the conversation message status induction
                            document.querySelectorAll("#operator-conversation .chat-read-icon").forEach((ele) => {
                                ele.classList.remove("chat-read-icon")
                                ele.classList.add("chat-read-mark")
                                ele.querySelector('i').className = "ri-check-double-fill"
                            })
                        }
                    }

                }

                // To remove the user from group
                let isIdFound = false;

                function isNumberPresent() {
                    if (chatOpenAgent.includes(socket.senderId) || !document.querySelector(
                            `.checkforactive[data-group-uniq='${socket.openedUser}']`)) {
                        for (const openusers of chatOpenAgent) {
                            if (typeof openusers == "object") {
                                if (openusers[Object.keys(openusers)[0]].includes(socket.senderId)) {
                                    isIdFound = true
                                    break
                                }
                            }
                        }
                    }

                }
                isNumberPresent()
                if (isIdFound) {
                    // To remove the group
                    for (const openusers of chatOpenAgent) {
                        if (typeof openusers == "object") {
                            if (openusers[Object.keys(openusers)[0]].includes(socket.senderId)) {
                                openusers[Object.keys(openusers)[0]] = openusers[Object.keys(openusers)[0]].filter(
                                    item => item != socket.senderId)
                            }
                        }
                    }
                }
            }

            // Sidebar adding message top
            if (
                (autoID == socket.receiverId && socket.message != null) || // Agent
                (groupIncludeDB.includes(parseInt(autoID)) && socket.message != null) // Group
            ) {
                const existingLi = Array.from(document.querySelectorAll("#chat-msg-scroll>li")).find(li => li
                    .getAttribute('data-id') == socket.senderId);
                if (!existingLi && !socket.groupInclude) {
                    // location.reload()
                    let agentElement = document.createElement("li")
                    agentElement.className = "checkforactive"
                    agentElement.setAttribute("data-id", "2")
                    agentElement.innerHTML = `
                            <div class="d-flex align-items-center">
                                <div class="me-2 lh-1">
                                    <span class="avatar brround" style="
                                        background-image: url(${document.querySelector(`.agents-list [data-id='${socket.senderId}'] .avatar `).style.backgroundImage.match(/url\("?(.+?)"?\)/)[1]})
                                    ">
                                    <span class="avatar-status onlineOfflineIndicator bg-green"></span>
                                    </span>
                                </div>
                                <div class="flex-fill">
                                    <div class="mb-0 d-flex align-items-center justify-content-between">
                                        <div class="font-weight-semibold">
                                        <a href="javascript:void(0);">${document.querySelector(`.agents-list [data-id='${socket.senderId}'] .font-weight-semibold`).innerHTML}</a>
                                        </div>
                                        <div class="float-end text-muted fw-normal fs-12 chat-time">${new Date().toLocaleTimeString()}</div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="chat-msg text-truncate fs-13 text-default font-weight-bold">${socket.message}</span>
                                        <span class="badge bg-success-transparent rounded-circle float-end unReadIndexNumber">1</span>
                                    </div>
                                </div>
                            </div>
                        `
                    agentElement.onclick = () => {
                        sideMenuOpenClickFunction(agentElement);
                    }
                    document.querySelector("#chat-msg-scroll").insertBefore(agentElement, document.querySelector(
                        "#chat-msg-scroll > li"));

                } else {
                    document.querySelectorAll("#chat-msg-scroll>li").forEach((ele) => {
                        if (
                            (ele.getAttribute("data-id") == socket.senderId && autoID == socket
                                .receiverId && operatorConversation?.getAttribute("operator-id") != socket
                                .senderId) || // Agent Sidebar adding message top Condition
                            (ele.getAttribute("data-group-uniq") == socket.receiverId && groupIncludeDB
                                .includes(parseInt(autoID)) && socket.senderId != autoID
                                ) // Group Sidebar adding message top Condition
                        ) {
                            // To add the message to the sidebar
                            ele.querySelector(".chat-msg").innerText = socket.message
                            // To add the time to the sidebar
                            ele.querySelector('.chat-time').innerText = new Date().toLocaleTimeString()

                            if (ele.querySelector(".unReadIndexNumber")) {
                                ele.querySelector(".unReadIndexNumber").innerText = ele.querySelector(
                                    ".unReadIndexNumber").innerText ? parseInt(ele.querySelector(
                                    ".unReadIndexNumber").innerText) + 1 : 1
                            } else {
                                // To stop the Unread Index Number for the Open chat
                                if (operatorConversation?.getAttribute("group-id") != socket.receiverId) {
                                    let unReadIndexNumber = document.createElement('span')
                                    unReadIndexNumber.className =
                                        "badge bg-success-transparent rounded-circle float-end unReadIndexNumber me-1 ms-auto"
                                    unReadIndexNumber.innerText = 1
                                    if (ele.querySelector(".chat-msg").parentNode.querySelector(
                                            '.avatar-list')) {
                                        // To inset the group number top of the span
                                        ele.querySelector(".chat-msg").parentNode.insertBefore(
                                            unReadIndexNumber, ele.querySelector(".chat-msg").parentNode
                                            .querySelector('.avatar-list'))
                                    } else {
                                        ele.querySelector(".chat-msg").parentNode.appendChild(
                                            unReadIndexNumber)
                                    }
                                }
                            }
                            // To stop the bold Text to the Open chat
                            if (operatorConversation?.getAttribute("group-id") != socket.receiverId) {
                                ele.querySelector(".chat-msg").classList.add("font-weight-bold")
                            }
                            if (ele.parentNode.querySelector(".d-inline-flex")) {
                                ele.parentNode.querySelector(".d-inline-flex").remove()
                            }
                            // To add the recent Customer to top
                            ele.parentNode.insertBefore(ele, document.querySelector("#chat-msg-scroll>li"))
                            clearTimeout(debounceTimeout2);
                            // To add the message in the side bar when the user was tying speed
                            sibeBarTypingAllowVar = false
                            setInterval(() => {
                                sibeBarTypingAllowVar = true
                            }, 500);
                        }

                        // To Add the Agent Message in the SideBar
                        if (ele.getAttribute("data-id") == socket.senderId && autoID == socket.receiverId) {
                            // To add the message to the sidebar
                            ele.querySelector(".chat-msg").innerHTML = socket.message
                            // To add the time to the sidebar
                            ele.querySelector('.chat-time').innerText = new Date().toLocaleTimeString()
                            if (ele.querySelector("#messageStatusDiv")) {
                                ele.querySelector("#messageStatusDiv").remove()
                            }
                        }
                    })
                }
            }

            //Group created the SideBar Update Logic
            if (!document.querySelector(`.checkforactive[data-group-uniq='${socket.receiverId}']`) && socket
                .message && socket.groupInclude && JSON.parse(socket.groupInclude).includes(parseInt(autoID))) {
                location.reload()
            }
        })
        agentMessageChannel.here((users) => {

            // For the Active chat online status
            setTimeout(() => {
                users.map((user)=>{
                    let activeChatEle = document.querySelector("#operator-conversation-Info")
                    if(activeChatEle){
                        if(activeChatEle.getAttribute('data-id') && user.id == activeChatEle.getAttribute('data-id').trim()){
                            activeChatEle.querySelector(".chatpersonstatus").innerText = "online"
                            activeChatEle.querySelector(".onlineOfflineIndicator ").classList.add("bg-green")
                            activeChatEle.querySelector(".onlineOfflineIndicator ").classList.remove("bg-gray")
                        }
                    }
                })
            }, 5000);

            // To remove the skeleton loader
            document.querySelector("#agents-list")?.classList.remove("is-loading")

            let availableAgents = document.querySelector("#chat-user-details")

            // index of Online Agents
            if(availableAgents.querySelector(".card-header .AvailableOperatorsIndex")){
                availableAgents.querySelector(".card-header .AvailableOperatorsIndex").innerText = `(${parseInt(users.length) - 1})`
            }

            // Online Agents
            document.querySelectorAll(
                    "#chat-msg-scroll li.checkforactive,#agents-list li.agent-detail,#onlineUlChatroom li[data-id]")
                .forEach((ele) => {
                    let dataId = ele.getAttribute("data-id");
                    if (dataId) {
                        let id = parseInt(dataId);
                        if (users.some(item => item.id === id)) {
                            let onlineOfflineIndicator = ele.querySelector(".onlineOfflineIndicator")
                            onlineOfflineIndicator.classList.remove('bg-gray')
                            onlineOfflineIndicator.classList.add('bg-green')
                            if (ele.classList.contains("agent-detail")) {
                                document.querySelector(".agent-detail").parentNode.insertBefore(
                                    onlineOfflineIndicator.closest(".agent-detail"), document.querySelector(
                                        ".agent-detail"))
                            }
                            // For The Chart Room Online
                            if (ele.classList.contains("list-group-item")) {
                                document.querySelector("#onlineUlChatroom").insertBefore(ele, document
                                    .querySelector("#onlineUlChatroom .list-group-item[data-id]"))
                            }
                        } else {
                            let onlineOfflineIndicator = ele.querySelector(".onlineOfflineIndicator")
                            if (onlineOfflineIndicator) {
                                onlineOfflineIndicator.classList.add('bg-gray')
                                onlineOfflineIndicator.classList.remove('bg-green')
                            }
                        }
                    }
                })

            // Active operators induction with localStorage
            if (localStorage.activeOperators) {
                let activeOperators = localStorage.activeOperators
                document.querySelectorAll("#chat-msg-scroll>li").forEach((element) => {
                    // Agent Loop
                    if (element.getAttribute("data-id") == activeOperators) {
                        element.classList.add("active")
                        $.ajax({
                            type: "get",
                            url: SITEURL + `/admin/operators/singleoperator/${activeOperators}`,
                            success: function(data) {
                                let userconversation = data.userconversation

                                if (element.querySelector(".chat-msg") && (element
                                        .querySelector(".unReadIndexNumber") && element
                                        .querySelector(".unReadIndexNumber").innerText)) {
                                    element.querySelector(".chat-msg").classList.remove(
                                        "font-weight-bold")
                                }
                                if (element.querySelector(".unReadIndexNumber") && element
                                    .querySelector(".unReadIndexNumber").innerText) {
                                    element.querySelector(".unReadIndexNumber").remove()
                                }

                                // Message conversation logic
                                let chatFooter = document.querySelector(".chat-footer")
                                chatFooter.classList.remove("d-none")

                                let mainChatContent = document.querySelector(
                                    "#main-chat-content")
                                mainChatContent.classList.remove("d-none")
                                document.querySelector("#main-chat-content .no-articles")
                                    ?.classList.add('d-none')
                                if (document.querySelector("#operator-conversation")) {
                                    document.querySelector("#operator-conversation").remove()
                                }
                                if (document.querySelector("#operator-conversation-Info")) {
                                    document.querySelector("#operator-conversation-Info")
                                        .remove()
                                }

                                // For thr messages conversation
                                let conversation = document.createElement("ul");
                                conversation.className =
                                    "list-unstyled chat-content overflow-auto"
                                conversation.id = "operator-conversation"


                                // For the Chat data
                                function formatDateString(inputDateStr) {
                                    const inputDate = new Date(inputDateStr);
                                    const monthNames = [
                                        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                    ];
                                    const year = inputDate.getFullYear();
                                    const month = monthNames[inputDate.getMonth()];
                                    const day = inputDate.getDate();
                                    const formattedDate = `${day},${month} ${year}`;

                                    return formattedDate;
                                }
                                conversation.setAttribute('operator-id', activeOperators)
                                if (userconversation) {
                                    let currentDate = null;
                                    userconversation.map((chatdata) => {
                                        const messageDate = formatDateString(chatdata
                                            .updated_at);
                                        if (messageDate !== currentDate) {
                                            conversation.innerHTML += `
                                                    <li class="chat-day-label">
                                                        <span>${messageDate}</span>
                                                    </li>
                                                `;
                                            currentDate = messageDate;
                                        }
                                        if (chatdata.sender_user_id !=
                                            activeOperators) {
                                            conversation.appendChild(receiverMessage(
                                                    chatdata, data.senderdata.image
                                                    ))
                                        } else {
                                            conversation.appendChild(senderMessage(
                                                chatdata, data.receiverdata
                                                .image))
                                        }
                                    })
                                }

                                // For the receiver Info
                                let receiverInfo = document.createElement("div");
                                receiverInfo.className =
                                    "d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3"
                                receiverInfo.id = "operator-conversation-Info"
                                receiverInfo.setAttribute('data-id', activeOperators)
                                receiverInfo.innerHTML = `
                                        <div class="me-2 lh-1">
                                                        <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${data.receiverdata.image ? data.receiverdata.image : 'user-profile.png'})">
                                                        <span class="avatar-status ${Object.keys(agentMessageChannel.subscription.members.members).includes(activeOperators) ? 'bg-green' : 'bg-gray'} onlineOfflineIndicator "></span>
                                                        </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                        <p class="mb-0 fw-semibold fs-14">
                                                        <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">${data.receiverdata.name}</a>
                                                        </p>
                                                        <p class="text-muted mb-0 chatpersonstatus">${Object.keys(agentMessageChannel.subscription.members.members).includes(activeOperators) ? 'online' : 'offline'}</p>
                                                        </div>
                                                        <div class="d-flex flex-wrap rightIcons">
                                                        <div class="dropdown ms-2 ">
                                                        <button aria-label="button" class="btn btn-icon btn-outline-light my-1 btn-wave waves-light waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="${document.querySelector(`.checkforactive[data-id='${activeOperators}'] .dropdown-item`).getAttribute('onclick')}">Delete Chat</a>
                                                        </li>
                                                        </ul>
                                                        </div>
                                                        </div>
                                    `
                                mainChatContent.appendChild(receiverInfo)
                                mainChatContent.appendChild(conversation)

                                // to remove the skeleton loader
                                document.querySelector("#main-chat-content").classList.remove(
                                    "is-loading")

                                // To add the Image Viewer
                                if (document.querySelector(".imageMessageLiveChat")) {
                                    // To Open the Image Viewer
                                    document.querySelectorAll(".imageMessageLiveChat").forEach((
                                        element) => {
                                        element.onclick = (ele) => {
                                            document.querySelector(
                                                    ".liveChatImageViewer")
                                                .classList.remove("d-none")
                                            document.querySelector(
                                                    ".liveChatImageViewer img")
                                                .src = ele.target.getAttribute(
                                                    "imagesrc")
                                            document.querySelector(
                                                ".liveChatImageViewer .liveChatImageClose"
                                                ).onclick = () => {
                                                // To Close the Image Viewer
                                                document.querySelector(
                                                        ".liveChatImageViewer")
                                                    .classList.add("d-none")
                                            }
                                        }
                                    })
                                }

                                // To Scroll Down the Conversation
                                document.querySelector("#operator-conversation").scrollBy(0,
                                    document.querySelector("#operator-conversation")
                                    .scrollHeight)
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        })
                    }

                    // Group Loop
                    if (element.getAttribute("data-group-uniq") == activeOperators) {
                        element.classList.add("active")
                        $.ajax({
                            type: "get",
                            url: SITEURL +
                                `/admin/operators/groupconversion/${element.getAttribute("data-group-uniq")}`,
                            success: function(data) {
                                if (element.querySelector(".chat-msg") && (element
                                        .querySelector(".unReadIndexNumber") && element
                                        .querySelector(".unReadIndexNumber").innerText)) {
                                    element.querySelector(".chat-msg").classList.remove(
                                        "font-weight-bold")
                                }
                                if (element.querySelector(".unReadIndexNumber") && element
                                    .querySelector(".unReadIndexNumber").innerText) {
                                    element.querySelector(".unReadIndexNumber").remove()
                                }

                                let userconversation = data.groupconversion

                                // Message conversation logic
                                let chatFooter = document.querySelector(".chat-footer")
                                chatFooter.classList.remove("d-none")

                                let mainChatContent = document.querySelector(
                                    "#main-chat-content")
                                mainChatContent.classList.remove("d-none")
                                document.querySelector("#main-chat-content .no-articles")
                                    ?.classList.add('d-none')

                                // To remove the messsage conversations
                                if (document.querySelector("#operator-conversation")) {
                                    document.querySelector("#operator-conversation").remove()
                                }
                                // To remove the receiver Info
                                if (document.querySelector("#operator-conversation-Info")) {
                                    document.querySelector("#operator-conversation-Info")
                                        .remove()
                                }

                                // To make the include uses names in the correct way
                                function formatString(inputString) {
                                    // Remove single quotes and split the string by commas
                                    var parts = inputString.replace(/'/g, '').split(',');

                                    // Trim each part and join them with a comma and space
                                    var result = parts.map(function(part) {
                                        return part.trim();
                                    }).join(', ');

                                    return result;
                                }

                                // For thr messages conversation
                                let conversation = document.createElement("ul");
                                conversation.className =
                                    "list-unstyled chat-content overflow-auto"
                                conversation.id = "operator-conversation"
                                conversation.setAttribute('group-id', element.getAttribute(
                                    "data-group-uniq"))
                                conversation.setAttribute('group-recievers_names',
                                    userconversation[userconversation.length - 1]
                                    .reciever_username)
                                conversation.setAttribute('group-recievers_id',
                                    userconversation[userconversation.length - 1]
                                    .receiver_user_id)

                                // For the Chat data
                                function formatDateString(inputDateStr) {
                                    const inputDate = new Date(inputDateStr);
                                    const monthNames = [
                                        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                    ];
                                    const year = inputDate.getFullYear();
                                    const month = monthNames[inputDate.getMonth()];
                                    const day = inputDate.getDate();
                                    const formattedDate = `${day},${month} ${year}`;

                                    return formattedDate;
                                }

                                if (userconversation) {
                                    let currentDate = null;
                                    let ChatNotification = false
                                    userconversation.map((chatdata) => {
                                        const messageDate = formatDateString(chatdata
                                            .updated_at);
                                        if (messageDate !== currentDate) {
                                            conversation.innerHTML += `
                                            <li class="chat-day-label">
                                                <span>${messageDate}</span>
                                            </li>
                                        `;
                                            currentDate = messageDate;
                                            // For the ChatNotifications
                                            if (!ChatNotification) {
                                                // Created User Span
                                                const chatJoinNotify = document
                                                    .createElement("li");
                                                chatJoinNotify.className =
                                                    'chat-join-notify'
                                                chatJoinNotify.innerHTML = `
                                            <span><b class="fw-bold">${chatdata.created_user_id == autoID ? 'You' : chatdata.sender_username}</b> created the group At ${formatTime(chatdata.created_at)}</span>
                                            `
                                                conversation.appendChild(chatJoinNotify)

                                                // Include Users Span
                                                chatdata.reciever_username.split(', ')
                                                    .map(name => name.replace(/'/g, ''))
                                                    .map((recieverUser => {
                                                        let SpanElementJoinNotify =
                                                            document
                                                            .createElement("li")
                                                        let AuthUserName =
                                                            document
                                                            .querySelector(
                                                                '.user-info .mb-2'
                                                                ).innerText
                                                        SpanElementJoinNotify
                                                            .className =
                                                            'chat-join-notify'
                                                        SpanElementJoinNotify
                                                            .innerHTML =
                                                            `<span><b class="fw-bold">${recieverUser == AuthUserName ? 'You' : recieverUser}</b> has joined the conversation in the group</span>`
                                                        conversation
                                                            .appendChild(
                                                                SpanElementJoinNotify
                                                                )
                                                    }))

                                                ChatNotification = true
                                            }
                                        }
                                        if (chatdata.sender_user_id ==
                                            '{{ Auth::user()->id }}') {
                                            conversation.appendChild(receiverMessage(
                                                chatdata,
                                                {!! json_encode(\App\Models\User::find(Auth::user()->id)->image) !!}))
                                        } else {
                                            conversation.appendChild(senderMessage(
                                                    chatdata, chatdata.sender_image
                                                    ))
                                        }
                                    })
                                }

                                // For the receiver Info
                                let receiverInfo = document.createElement("div");
                                receiverInfo.className =
                                    "d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3"
                                receiverInfo.id = "operator-conversation-Info"
                                receiverInfo.setAttribute('data-id', element.getAttribute(
                                    "data-group-uniq"))
                                receiverInfo.innerHTML = `
                                        <div class="avatar-list avatar-list-stacked me-3 d-flex">
                                        <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png})">
                                        </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                        <p class="mb-0 fw-semibold fs-14">
                                        <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open text-truncate" style="width: 502px;display: block;">${formatString(data.receiverUsersInfo)}</a>
                                        </p>
                                        </div>
                                        <div class="d-flex flex-wrap rightIcons">
                                        <div class="dropdown ms-2">
                                        <button aria-label="button" class="btn btn-icon btn-outline-light my-1 btn-wave waves-light waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="operators/groupconversiondelete/${element.getAttribute("data-group-uniq")}">Delete Chat</a></li>
                                        </ul>
                                        </div>
                                        </div>
                                `

                                let iconElements = Array.from(element.querySelector(
                                    '.avatar-list-stacked').querySelectorAll(
                                    ".avatar-sm"));
                                let modifiedHTMLArray = [];
                                iconElements.forEach((iconEle) => {
                                    iconEle.classList.add('avatar');
                                    iconEle.classList.remove('avatar-sm');
                                    modifiedHTMLArray.push(iconEle.outerHTML);
                                    iconEle.classList.add('avatar');
                                    iconEle.classList.add('avatar-sm');
                                });

                                receiverInfo.querySelector('.avatar-list-stacked').innerHTML =
                                    modifiedHTMLArray.join('\n')

                                mainChatContent.appendChild(receiverInfo)
                                mainChatContent.appendChild(conversation)

                                // to remove the skeleton loader
                                document.querySelector("#main-chat-content").classList.remove(
                                    "is-loading")

                                // To add the Image Viewer
                                if (document.querySelector(".imageMessageLiveChat")) {
                                    // To Open the Image Viewer
                                    document.querySelectorAll(".imageMessageLiveChat").forEach((
                                        element) => {
                                        element.onclick = (ele) => {
                                            document.querySelector(
                                                    ".liveChatImageViewer")
                                                .classList.remove("d-none")
                                            document.querySelector(
                                                    ".liveChatImageViewer img")
                                                .src = ele.target.getAttribute(
                                                    "imagesrc")
                                            document.querySelector(
                                                ".liveChatImageViewer .liveChatImageClose"
                                                ).onclick = () => {
                                                // To Close the Image Viewer
                                                document.querySelector(
                                                        ".liveChatImageViewer")
                                                    .classList.add("d-none")
                                            }
                                        }
                                    })
                                }

                                // To Scroll Down the Conversation
                                document.querySelector("#operator-conversation").scrollBy(0,
                                    document.querySelector("#operator-conversation")
                                    .scrollHeight)


                                // To add the ID in Array
                                const existingEntryIndex = chatOpenAgent.findIndex(entry =>
                                    Object.keys(entry)[0] == activeOperators);
                                if (existingEntryIndex != -1) {
                                    const existingArray = chatOpenAgent[existingEntryIndex][
                                        activeOperators
                                    ];
                                    existingArray.push(parseInt(autoID));
                                } else {
                                    chatOpenAgent.push({
                                        [activeOperators]: [parseInt(autoID)]
                                    });
                                }
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            }else{
                if(document.querySelector(".checkforactive")){
                    document.querySelector(".checkforactive").click()
                }else{
                    document.querySelector(".no-articles").classList.remove("d-none");
                }
            }
        })
        agentMessageChannel.joining((user) => {

            // without reload the joining Agent will show reload

            let availableAgents = document.querySelector("#chat-user-details")

            // Online Agents
            document.querySelectorAll(
                "#chat-msg-scroll li.checkforactive,#agents-list li.agent-detail,#operator-conversation-Info,#onlineUlChatroom li[data-id]"
                ).forEach((ele) => {
                let dataId = ele.getAttribute("data-id");
                if (dataId) {
                    let id = parseInt(dataId);
                    if (user.id === id) {
                        let onlineOfflineIndicator = ele.querySelector(".onlineOfflineIndicator")
                        if (onlineOfflineIndicator) {
                            onlineOfflineIndicator.classList.remove('bg-gray')
                            onlineOfflineIndicator.classList.add('bg-green')
                        }
                        if (ele.classList.contains("agent-detail") && ele.id !=
                            'operator-conversation-Info') {
                            document.querySelector(".agent-detail").parentNode.insertBefore(
                                onlineOfflineIndicator.closest(".agent-detail"), document.querySelector(
                                    ".agent-detail"))
                            // index of Online Agents
                            availableAgents.querySelector(".card-header .AvailableOperatorsIndex").innerText = `(${parseInt(availableAgents.querySelector(".card-header .AvailableOperatorsIndex").innerText.match(/\d+/)[0]) + 1})`
                        }
                        // For the Chartroom online add
                        if (ele.classList.contains("list-group-item")) {
                            document.querySelector("#onlineUlChatroom").insertBefore(ele, document
                                .querySelector("#onlineUlChatroom .list-group-item[data-id]"))
                        }

                        // For the online info
                        if (ele.id == 'operator-conversation-Info') {
                            ele.querySelector(".chatpersonstatus").innerText = "online"
                        }
                    }
                }
            })

        })
        agentMessageChannel.leaving((user) => {
            // without reload the leaving Agent will show reload
            let availableAgents = document.querySelector("#chat-user-details")

            // Online Agents
            document.querySelectorAll(
                "#chat-msg-scroll li.checkforactive,#agents-list li.agent-detail,#operator-conversation-Info,#onlineUlChatroom li[data-id]"
                ).forEach((ele) => {
                let dataId = ele.getAttribute("data-id");
                if (dataId) {
                    let id = parseInt(dataId);
                    if (user.id === id) {
                        let onlineOfflineIndicator = ele.querySelector(".onlineOfflineIndicator")
                        if (onlineOfflineIndicator) {
                            onlineOfflineIndicator.classList.add('bg-gray')
                            onlineOfflineIndicator.classList.remove('bg-green')
                        }

                        if (ele.classList.contains("agent-detail") && ele.id !=
                            'operator-conversation-Info') {
                            // index of Online Agents
                            availableAgents.querySelector(".card-header .AvailableOperatorsIndex").innerText = `(${parseInt(availableAgents.querySelector(".card-header .AvailableOperatorsIndex").innerText.match(/\d+/)[0]) - 1})`
                        }

                        // For the Offline info
                        if (ele.id == 'operator-conversation-Info') {
                            ele.querySelector(".chatpersonstatus").innerText = "offline"
                        }
                    }
                }
            })

            // To remove the user Form the chat Apen Array
            chatOpenAgent = chatOpenAgent.filter(item => item !== user.id);
        })
    </script>
@endsection
