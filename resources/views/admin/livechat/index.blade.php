@extends('layouts.adminmaster')
@section('styles')
<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<style>
    .file-img {
        position: relative;
    }
    .file-img button {
        position: absolute;
        inset-block-start: -4px;
        inset-inline-end: -4px;
        height: 20px;
        width: 20px;
        padding: 0px;
        border: 0;
    }

    .liveChatImageViewer{
        display: block;
        position: fixed;
        z-index: 9999999999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.9);
    }

    .liveChatImageClose{
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

    .liveChatImageTag{
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
</style>

<!-- Select2 css -->
<link href="{{asset('build/assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

@endsection

@section('content')
<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ $operatorID ? lang('Operators Chats', 'menu') : lang('New Chats', 'menu') }}</span></h4>
    </div>
</div>
<!--End Page header-->

{{-- sideBar --}}
<div class="main-chart-wrapper">
    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header border pb-5">
                    {{-- <h4 class="card-title"> --}}
                        @if($operatorID)
                            <div class="d-flex align-items-center ms-2">
                                @if($user->find($operatorID)->image == null)
                                    <span class="avatar brround" style="background-image: url(../uploads/profile/user-profile.png)"></span>
                                @else
                                    <span class="avatar brround" style="background-image: url(../uploads/profile/{{$user->find($operatorID)->image}})"></span>
                                @endif
                                <div class="d-inline">
                                <h5 class="font-weight-semibold mb-0 ms-2 mt-3">{{$user->find($operatorID)->name}} </h5>
                                <small class="text-muted ms-2 fs-11"> ({{ lang('My Opened Chats') }})</small>
                                </div>
                            </div>
                        @else
                            <h4 class="card-title font-weight-semibold mb-0">{{ lang('New Chats') }}</h4>
                        @endif
                    {{-- </h4> --}}
                </div>
                <div class="chat-info new-chat-info">
                    <div>
                        <ul class="list-unstyled mb-0 chat-user-tab-list chat-users-tab overflow-auto" id="chat-msg-scroll">
                            @php
                                $emptyConversation = true;
                            @endphp

                            @foreach ($filteredLiveCust as $LiveCust)
                                @if($LiveCust && !$LiveCust->lastMessage->delete)
                                {{$emptyConversation = false}}
                                <li class="checkforactive" data-id={{$LiveCust->id}}>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2 lh-1">
                                            <span class="avatar brround" id="new-chat-user-bg" style="background-color: {{ randomColorGenerator(0.5) }}">
                                                @php
                                                    $currentOnlineUsers = setting('liveChatCustomerOnlineUsers');
                                                    $onlineUsersArray = explode(',', $currentOnlineUsers);
                                                    $userOnline = in_array($LiveCust->id, $onlineUsersArray);
                                                @endphp
                                                @if($userOnline)
                                                    <span class="avatar-status bg-green"></span>
                                                @else
                                                    <span class="avatar-status "></span>
                                                @endif
                                                <span class="new-chat-user-letter">{{ strtoupper(substr($LiveCust->username, 0, 1)) }}</span>
                                            </span>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="mb-0 d-flex align-items-center justify-content-between">
                                                <div class="font-weight-semibold">
                                                    <a href="javascript:void(0);">{{$LiveCust->username}}</a>
                                                </div>
                                                <div class="float-end text-muted fw-normal fs-12 chat-time" data-initial-24time='{{ \Carbon\Carbon::parse($LiveCust->lastMessage->created_at)->timezone(setting('default_timezone')) }}'>
                                                    {{ $LiveCust->lastMessage->created_at->diffForHumans() }}
                                                </div>
                                                <div class="dropdown chat-actions lh-1">
                                                    <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="">
                                                        <i class="fe fe-more-vertical fs-18"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" style="">
                                                        <li custId={{$LiveCust->id}} class="reAssignModalTrigger" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><a class="dropdown-item" href="javascript:void(0);"><i
                                                            class="ri-user-shared-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>{{ lang('Re-Assign') }}</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" deleteRouteLink="{{route('admin.livechatConversationDelete')}}?unqid={{$LiveCust->cust_unique_id}}"><i
                                                            class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>{{ lang('Delete') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">

                                                @if($LiveCust->unreadIndexNumber)
                                                    <span class="chat-msg text-truncate fs-13 text-default custrecentmessage font-weight-bold"
                                                    data-id="100">{{ $LiveCust->lastMessage->message }}</span>
                                                    <span class="ms-auto me-2 badge bg-success-transparent rounded-circle unReadIndexNumber">{{$LiveCust->unreadIndexNumber}}</span>
                                                @else
                                                    <span class="chat-msg text-truncate fs-13 text-default custrecentmessage"
                                                    data-id="100">{{ $LiveCust->lastMessage->message }}</span>
                                                @endif
                                            @if(json_decode($LiveCust->engage_conversation))
                                                @if(collect(json_decode($LiveCust->engage_conversation))->count() <= 2)
                                                        <div class="avatar-list avatar-list-stacked me-3">
                                                            @foreach (json_decode($LiveCust->engage_conversation) as $key =>$LiveCustmer)
                                                                @if($key < 2)
                                                                    @if($LiveCustmer->image)
                                                                        <span class="avatar brround avatar-sm"
                                                                        style="background-image: url(../uploads/profile/{{$LiveCustmer->image}})"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$LiveCustmer->name}}"
                                                                        reddy="" data-bs-original-title="" title=""></span>
                                                                    @else
                                                                        <span class="avatar brround avatar-sm"
                                                                            style="background-image: url(../uploads/profile/user-profile.png)"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$LiveCustmer->name}}"
                                                                            reddy="" data-bs-original-title="" title=""></span>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        @else
                                                            <div class="avatar-list avatar-list-stacked me-3">
                                                                @foreach (json_decode($LiveCust->engage_conversation) as $key =>$LiveCustmer)
                                                                    @if($key < 2)
                                                                        @if($LiveCustmer->image)
                                                                            <span class="avatar brround avatar-sm"
                                                                            style="background-image: url(../uploads/profile/{{$LiveCustmer->image}})"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$LiveCustmer->name}}"
                                                                            reddy="" data-bs-original-title="" title=""></span>
                                                                        @else
                                                                            <span class="avatar brround avatar-sm"
                                                                                style="background-image: url(../uploads/profile/user-profile.png)"
                                                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$LiveCustmer->name}}"
                                                                                reddy="" data-bs-original-title="" title=""></span>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                @php
                                                                    $conversationData = json_decode($LiveCust->engage_conversation);
                                                                    $namesString = implode(', ', array_map(fn($item) => $item->firstname . ' ' . $item->lastname, $conversationData));
                                                                @endphp
                                                                    <span class="avatar brround bg-light text-dark avatar-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{$namesString}}">+{{collect(json_decode($LiveCust->engage_conversation))->count()-2}}</span>
                                                            </div>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                            @if($emptyConversation)
                                <div class="text-center mt-5 p-1 bg-warning-transparent text-default">
                                    <span>{{ lang('As of now, there are no chat discussions in progress') }}</span>
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-header border border-bottom-0 pb-5">
                    {{-- <h4 class="card-title"> --}}
                        {{-- @if($operatorID)
                            <div class="d-flex align-items-center ms-2">
                                @if($user->find($operatorID)->image == null)
                                    <span class="avatar brround" style="background-image: url(../uploads/profile/user-profile.png)"></span>
                                @else
                                    <span class="avatar brround" style="background-image: url(../uploads/profile/{{$user->find($operatorID)->image}})"></span>
                                @endif
                                <div class="d-inline">
                                <h5 class="font-weight-semibold mb-0 ms-2 mt-3">{{$user->find($operatorID)->name}} </h5>
                                <small class="text-muted ms-2 fs-11"> ({{ lang('My Opened Chats') }})</small>
                                </div>
                            </div>
                        @else --}}
                            <h4 class="card-title font-weight-semibold mb-0">{{ lang('Operators') }}</h4>
                        {{-- @endif --}}
                    {{-- </h4> --}}
                </div>
                <div class="chat-user-details new-chats-operators-details chat-opertaors-section bg-transparent p-0 mt-2 px-2" >
                    {{-- Operators div --}}
                    @if($user->isNotEmpty())
                        <div class="mb-5"><input class="form-control" id="availableAgentsSearchInput" placeholder="Search For Operator" type="text"></div>
                        <div class="noUser" id="noUserMessage" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3">
                                    <p> {{ lang('No user found') }} </p>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled agents-list overflow-y-scroll" id="agents-list">
                            @foreach($user as $users)
                                <li class="agent-detail" data-id="{{$users->id}}">
                                    <a href="{{route('admin.livechat')}}?operatorID={{$users->id}}" class="streched-link">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex gap-3">
                                                <div>
                                                    @if($users->image == null)
                                                        <span class="avatar brround" style="background-image: url(../uploads/profile/user-profile.png)">
                                                        </span>
                                                    @else
                                                        <span class="avatar brround" style="background-image: url(../uploads/profile/{{$users->image}})">
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-weight-semibold">{{$users->name}}</span>
                                                    <span class="d-block text-muted fs-12 text-break">{{$users->email}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if($user->isEmpty())
                        <div class="text-center mt-5 p-1 bg-warning-transparent text-default">
                            <span>{{ lang('Operators are not found') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="main-chat-area main-chat-area-new bg-white">
                <div id="main-chat-content">
                    <div class="card shadow-none no-articles d-none" style="
                        height: calc(100vh - 8rem);
                        background-color: transparent;
                        "
                    >
                        <div class="card-body p-8">
                            <div class="main-content text-center" >
                                <div class="notification-icon-container p-4">
                                    <img src="{{ asset('build/assets/images/noarticle.png') }}" alt="">
                                </div>
                                <h4 class="mb-1">{{ lang('Currently, no active chat discussions at the moment') }}</h4>
                                <p class="text-muted">{{ lang('There are currently no ongoing chat discussions at this time.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-footer bg-transparent shadow-none d-none border-top">
                    <div class="chat-reply-area chat-replyoptions textareaDiv d-none">
                        <div class="form-group create-ticket d-none">
                            <label for="livechatTicketSubject" class="form-label">
                                {{ lang('Subject') }}
                            <span class="text-red">*</span>
                            </label>
                            <input type="text" class="form-control" id="livechatTicketSubject" placeholder="Enter Ticket Subject">
                        </div>

                        <div class="form-group d-none">
                            <div class="row">
                                <div class="">
                                    <label class="form-label">{{lang('Category')}} <span class="text-red">*</span></label>
                                </div>
                                <div class="">
                                    <select
                                        class="form-control select2-show-search  select2 @error('category') is-invalid @enderror"
                                        data-placeholder="{{lang('Select Category')}}" name="category" id="category">
                                        <option label="{{lang('Select Category')}}"></option>
                                        @foreach ($categories as $category)

                                        <option value="{{ $category->id }}" @if(old('category')) selected @endif>{{ $category->name }}</option>
                                        @endforeach

                                    </select>
                                    <span id="CategoryError" class="text-danger alert-message"></span>
                                    @error('category')

                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="selectssSubCategory" style="display: none;">

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label mb-0 mt-2">{{lang('SubCategory')}}</label>
                                </div>
                                <div class="col-md-12">
                                    <select  class="form-control select2-show-search select2"  data-placeholder="{{lang('Select SubCategory')}}" name="subscategory" id="subscategory">

                                    </select>
                                    <span id="subsCategoryError" class="text-danger alert-message"></span>
                                </div>
                            </div>

                        </div>

                        <div class="form-group" id="selectSubCategory">
                        </div>
                        <div class="form-group" id="envatopurchase">
                        </div>

                        <label for="livechatTicketSubject" class="form-label create-ticket-description d-none">
                            {{ lang('Description') }}
                        <span class="text-red">*</span>
                        </label>
                        <textarea class="form-control mb-2 textarea-chatoptions" id="auto-expand" rows="1" placeholder="Type your message here..."></textarea>
                        <div class="image-uploaded d-flex gap-2 flex-wrap"></div>
                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Canned responses">
                                <a aria-label="anchor" class="btn-emoji" href="" data-bs-target="#canned-responses" data-bs-toggle="modal">
                                    <i class="ri-message-line"></i>
                                </a>
                            </div>
                            <div id="chat-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Chat">
                                <a aria-label="anchor" class="btn-emoji" href="javascript:void(0)">
                                    <i class="ri-chat-3-line text-primary"></i>
                                </a>
                            </div>
                            <div id="create-ticket-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Create Ticket">
                                <a aria-label="anchor" class="btn-emoji" href="javascript:void(0)">
                                    <i class="ri-ticket-line"></i>
                                </a>
                            </div>
                            <div class="d-flex align-items-center ms-auto gap-2">
                                <a aria-label="anchor" class="btn-emoji allEmojisBtn dropdown" data-bs-toggle="dropdown" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Emojis" data-bs-original-title="" title="">
                                    <i class="ri-emotion-line"></i>
                                </a>
                                <ul class="dropdown-menu" id="emojiGrid" style="height: 200px; overflow-y: scroll;"></ul>
                                <a aria-label="anchor" class="btn-attach" type="file" onclick="{document.querySelector('#chat-file-upload').click()}" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Upload" data-bs-original-title="" title="">
                                    <i class="ri-attachment-line"></i>
                                </a>
                                <input type="file" id="chat-file-upload" class="d-none" name="chat-file-upload" accept="image/png, image/jpeg" />
                                <button aria-label="anchor" disabled="true" id="agentSendMessage" class="btn-reply border rounded-3 btn-outline-primary disabled" href="javascript:void(0)">
                                    <i class="ri-send-plane-2-fill fs-20"></i>
                                </button>
                                <button aria-label="anchor" disabled="true" id="agentCreateTicket" class="d-none btn-reply border rounded-3 btn-outline-primary disabled" href="javascript:void(0)">
                                    <i class="ri-send-plane-2-fill fs-20"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="chat-reply-area chat-replyoptions text-center engageConversation">
                        <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center mx-auto">{{ lang('Join this conversation') }} <i class="ri-discuss-line ms-1"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="chat-user-details chat-opertaors-section bg-transparent p-0" >
                <div class="card d-none shadow-none" id="chat-user-details">
                    <div class="card-header pb-5 justify-content-between">
                        <h4 class="card-title">{{ lang('Customer Info') }}</h4>
                    </div>
                    <div class="card-body">
                    </div>
                </div>

                <div class="card d-none">
                    <div class="card-header pb-4 d-flex align-items-center border-0">
                        <h6>{{ lang('File Upload Permission') }}</h6>
                        <div class="switch-toggle  ms-auto">
                            <a class="onoffswitch2 ">
                                <input type="checkbox" id="fileUploadPermission" class="toggle-class onoffswitch2-checkbox">
                                <label for="fileUploadPermission" class="toggle-class onoffswitch2-label"></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Chat Info div --}}


    {{-- Re-Assign Model --}}

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">{{ lang('Reassign the conversation') }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="form-group select2-lg">
                    <label for="form-label">{{ lang('Reassign Users') }}</label>
                        <select class="form-control select2" placeholder="..." id="reassignSelect">
                        <option></option>
                        @foreach($user as $users)
                        @if($users->image)
                            <option value={{$users->id}} data-kt-select2-country="../uploads/profile/{{$users->image}}">{{$users->name}}</option>
                            @else
                            <option value={{$users->id}} data-kt-select2-country="../uploads/profile/user-profile.png">{{$users->name}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="reassignSelectBtn">{{ lang('Reassign') }}</button>
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
                        <label class="form-label">{{ lang('Canned Responses') }}<a href="" data-bs-target="#canned-add-responses" data-bs-toggle="modal" class="fw-semibold font-weight-semibold text-primary text-decoration-underline float-end"></a></label>
                        <select name="livechatPosition" id="cannedResponses" class="form-control select2 select2-show-search" required>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Offcanvas Model --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        {{-- <div class="offcanvas-header border-bottom">
          <h5 class="offcanvas-title" id="offcanvasExampleLabel">{{ lang('Customer Info') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">×</button>
        </div> --}}
        <div class="offcanvas-body p-0">
            <div class="chat-user-details chat-opertaors-section bg-transparent p-0" >
                {{-- Customer Info div --}}
                <div class="card d-none shadow-none" id="chat-user-details">
                    <div class="card-header pb-5 justify-content-between">
                        <h4 class="card-title">{{ lang('Customer Info') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">×</button>
                    </div>
                    <div class="card-body">
                    </div>
                </div>

                <div class="card d-none">
                    <div class="card-header pb-4 d-flex align-items-center border-0">
                        <h6>{{ lang('File Upload Permission') }}</h6>
                        <div class="switch-toggle  ms-auto">
                            <a class="onoffswitch2 ">
                                <input type="checkbox" id="fileUploadPermission" class="toggle-class onoffswitch2-checkbox">
                                <label for="fileUploadPermission" class="toggle-class onoffswitch2-label"></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

    <!-- INTERNAL Web Socket -->
    <script domainName='{{url('')}}' wsPort="{{setting('liveChatPort')}}" src="{{ asset('build/assets/plugins/livechat/web-socket.js') }}"></script>

    <!-- Select2 js -->
    {{-- <script src="{{asset('build/assets/plugins/select2/select2.full.min.js')}}"></script> --}}

    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

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

    <script>
        // Get the Emojis Values
        function insertEmoji(emoji) {
                let autoExpandElement = document.querySelector("#auto-expand").value
                document.querySelector("#auto-expand").value = autoExpandElement + emoji
        }

        // Adding the Emojis
        document.addEventListener("DOMContentLoaded", function () {
                const emojiGrid = document.getElementById("emojiGrid");

                // Emojis data
                const emojisData = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '😮', '😯', '😦', '😧', '😨', '😰', '😱', '😳', '😵', '😡', '😠', '😤', '😖', '😆', '😋', '😷', '😎', '🤓', '🤠', '😸', '😺', '😻', '😼', '😽', '🙀', '😿', '😾', '👐', '🙌', '👏', '🤝', '👍', '👎', '👊', '✊', '🤛', '🤜', '🤞', '✌️', '🤘', '👌', '👈', '👉', '👆', '👇', '✋', '🤚', '🖐', '🖖', '👋', '🤙', '💪', '🖕', '✍️', '🙏', '🦶', '🦵', '💍', '💔', '❤️', '💙', '💚', '💛', '💜', '🧡', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈', '🙉', '🙊', '💪', '👈', '👉', '👆', '👇', '🖕', '🤘', '🤞', '🤟', '🤙', '👊', '👋', '👏', '👐', '✋', '🤚', '🤝', '🙏', '💍', '💔', '❤️', '💕', '💞', '💓', '💗', '💖', '💘', '💝', '💟', '💤', '💢', '💣', '💥', '💫', '💦', '💨', '🕳️', '💧', '💩', '🙈'];

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
                    col.classList.add("dropdown-item",); // Adjust the grid layout as needed
                    col.onclick = ()=>{
                        insertEmoji(emojis[i])
                    }
                    col.innerHTML = `${emojis[i]}`;

                    // Append emoji to the current row
                    const currentRow = emojiGrid.lastElementChild;
                    document.querySelector("#agentSendMessage").disabled = false
                    document.querySelector("#agentSendMessage").classList.remove('disabled')
                    currentRow.appendChild(col);

                }
        })
    </script>

    <script>
        // Available Agents search logic
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("availableAgentsSearchInput");
            const agentList = document.querySelector(".agents-list");
            const noUserMessage = document.getElementById("noUserMessage"); // Assuming you have an element with the id "noUserMessage" for displaying the message

            // Clone the list items for resetting the order later
            const originalOrder = Array.from(agentList.children);

            searchInput.addEventListener("input", function () {
                const searchTerm = searchInput.value.trim().toLowerCase();

                // Filter the list based on the search term
                const filteredList = originalOrder.filter((li) => {
                    const name = li.querySelector(".font-weight-semibold").textContent.toLowerCase();
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

        // To add option the Cannedmessages to model
        $.ajax({
            type: "get",
            url: '{{route('admin.getCannedmessages')}}',
            success: function (data) {
                if(data.success == true){
                    var selectElement = document.getElementById('cannedResponses');
                    selectElement.innerHTML = "<option></option>"

                    data.message.cannedmessages.forEach(function(option) {
                        var optionElement = document.createElement('option');
                        optionElement.value = option.messages;
                        optionElement.textContent = option.title;
                        selectElement.appendChild(optionElement);
                    });

                    $(document).ready(function() {
                        $('#cannedResponses').select2({
                            placeholder: "Canned Responses",
                            allowClear:true,
                            dropdownParent: $('#canned-responses')
                        });
                    });
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

        // To add value in the text area
        document.getElementById('cannedResponses').onchange=(ele=>{
            let inputNodeElement = document.createElement("input")
            inputNodeElement.innerHTML = ele.target.value

            document.querySelector("#auto-expand").value = inputNodeElement.textContent.replace(/<[^>]*>/g, '').trim()
            // to remove the disabled from the Message send btn
            document.querySelector("#agentSendMessage").removeAttribute('disabled')
            document.querySelector("#agentSendMessage").classList.remove('disabled')

            // to remove the disabled from the Create Ticket btn
            if(document.querySelector("#livechatTicketSubject").value.trim()){
                document.querySelector("#agentCreateTicket").removeAttribute('disabled')
                document.querySelector("#agentCreateTicket").classList.remove('disabled')
            }

            // To Close the Model
            $('#canned-responses').modal('toggle');
            $('#cannedResponses').select2('destroy');
            document.querySelector("#cannedResponses").selectedIndex = null
            $('#cannedResponses').select2({
                placeholder: {
                    id: '',
                    text: 'Canned Responses'
                },
                allowClear: true
            });

            setTimeout(() => {
                document.querySelector("#auto-expand").focus();
            }, 1000);

        })

        // To open the Create ticket Form
        document.querySelector("#create-ticket-btn").onclick = (event)=>{
            event.currentTarget.querySelector("i").classList.add("text-primary")
            document.querySelector(".chat-reply-area #chat-btn i").classList.remove("text-primary")
            document.querySelector(".chat-reply-area .create-ticket").classList.remove("d-none")
            document.querySelector(".chat-reply-area #auto-expand").classList.remove("border-0")
            document.querySelector(".chat-reply-area #auto-expand").classList.add("border-1","my-2")
            document.querySelector("#agentCreateTicket").classList.remove("d-none")
            document.querySelector("#agentSendMessage").classList.add("d-none")
            document.querySelector(".create-ticket-description").classList.remove("d-none")

            // For the category
            document.querySelector("#category").closest('.form-group').classList.remove("d-none")
            document.querySelector("#envatopurchase").classList.remove("d-none")
            document.querySelector("#selectSubCategory").classList.remove("d-none")
        }

        // For the Chat Btn
        document.querySelector("#chat-btn").onclick = ()=>{
            event.currentTarget.querySelector("i").classList.add("text-primary")
            document.querySelector(".chat-reply-area #create-ticket-btn i").classList.remove("text-primary")
            document.querySelector(".chat-reply-area .create-ticket").classList.add("d-none")
            document.querySelector(".chat-reply-area #auto-expand").classList.add("border-0")
            document.querySelector(".chat-reply-area #auto-expand").classList.remove("border-1","my-2")
            document.querySelector("#agentCreateTicket").classList.add("d-none")
            document.querySelector("#agentSendMessage").classList.remove("d-none")
            document.querySelector(".create-ticket-description").classList.add("d-none")


            // For the category
            document.querySelector("#category").closest('.form-group').classList.add("d-none")
            document.querySelector("#envatopurchase").classList.add("d-none")
            document.querySelector("#selectSubCategory").classList.add("d-none")
            document.querySelector("#selectssSubCategory").style.display = "none"
        }

        // Enter Subject key Upcheck
        document.getElementById('livechatTicketSubject').addEventListener('keyup', function (event) {
            let agentCreateTicketBtn = document.querySelector("#agentCreateTicket")

            let envatoId = true

            if(document.querySelector("#envato_id")){
                envatoId = document.querySelector("#envato_id").getAttribute("readonly") == 'readonly'
            }

            // To Remove the Disabled For The Ticket Create Btn
            if(document.querySelector("#auto-expand").value &&
                event.target.value &&
                document.querySelector("#category").value &&
                envatoId
            ){
                agentCreateTicketBtn.disabled = false
                agentCreateTicketBtn.classList.remove('disabled')
            }else{
                agentCreateTicketBtn.disabled = true
                agentCreateTicketBtn.classList.add('disabled')
            }
        });

        // For the category Change
        document.querySelector("#category").onchange = (ele)=>{
            let agentCreateTicketBtn = document.querySelector("#agentCreateTicket")

            let envatoId = true
            setTimeout(() => {
                if(document.querySelector("#envato_id")){
                    envatoId = document.querySelector("#envato_id").getAttribute("readonly") == 'readonly'
                }

                // To Remove the Disabled For The Ticket Create Btn
                if(document.querySelector("#auto-expand").value &&
                document.querySelector("#livechatTicketSubject").value &&
                document.querySelector("#category").value &&
                envatoId
                ){
                    agentCreateTicketBtn.disabled = false
                    agentCreateTicketBtn.classList.remove('disabled')
                }else{
                    agentCreateTicketBtn.disabled = true
                    agentCreateTicketBtn.classList.add('disabled')
                }
            }, 1000);
        }

        // For the Category
        $('#category').on('change',function(e) {
                var cat_id = e.target.value;
                $('#selectssSubCategory').hide();
                $.ajax({
                    url:"{{ route('guest.subcategorylist') }}",
                    type:"POST",
                        data: {
                        cat_id: cat_id
                        },
                        cache : false,
                        async: true,
                    success:function (data) {
                        if(data.subcategoriess != ''){
                            $('#subscategory').html(data.subcategoriess)
                            $('#selectssSubCategory').show()
                        }
                        else{
                            $('#selectssSubCategory').hide();
                            $('#subscategory').html('')
                        }
                        //projectlist
                        // if(data.subcategories.length >= 1){

                        //     $('#subcategory')?.empty();
                        //     document.querySelector("#selectssSubCategory").classList.remove("d-none");
                        //     let selectDiv = document.querySelector('#selectSubCategory');
                        //     let Divrow = document.createElement('div');
                        //     Divrow.setAttribute('class','row mt-4');
                        //     let Divcol3 = document.createElement('div');
                        //     Divcol3.setAttribute('class','col-md-3');
                        //     let selectlabel =  document.createElement('label');
                        //     selectlabel.setAttribute('class','form-label mb-0 mt-2')
                        //     selectlabel.innerText = "{{lang('Project')}}";
                        //     let divcol9 = document.createElement('div');
                        //     divcol9.setAttribute('class', 'col-md-9');
                        //     let selecthSelectTag =  document.createElement('select');
                        //     selecthSelectTag.setAttribute('class','form-control select2-show-search');
                        //     selecthSelectTag.setAttribute('id', 'subcategory');
                        //     selecthSelectTag.setAttribute('name', 'project');
                        //     selecthSelectTag.setAttribute('data-placeholder','Select Projects');
                        //     let selectoption = document.createElement('option');
                        //     selectoption.setAttribute('label','Select Projects')
                        //     selectDiv.append(Divrow);
                        //     Divrow.append(Divcol3);
                        //     Divcol3.append(selectlabel);
                        //     divcol9.append(selecthSelectTag);
                        //     selecthSelectTag.append(selectoption);
                        //     Divrow.append(divcol9);
                        //     $('.select2-show-search').select2();
                        //     $.each(data.subcategories,function(index,subcategory){
                        //     $('#subcategory').append('<option value="'+subcategory.name+'">'+subcategory.name+'</option>');
                        //     })
                        // }
                        // else{
                        //     $('#subcategory')?.empty();
                        //     if(data.subcatstatusexisting != 'statusexisting'){
                        //         document.querySelector("#selectssSubCategory").classList.add("d-none");
                        //     }else{
                        //         document.querySelector("#selectssSubCategory").classList.remove("d-none");
                        //     }
                        // }
                        if(data.subCatStatus.length === 0){
                            $('#selectssSubCategory').hide();
                        }

                        if(data.subcategories.length >= 1){
                            $('#selectSubCategory')?.empty();
                            $('#subcategory')?.empty();
                            document.querySelector("#selectssSubCategory").classList.remove("d-none")
                            let selectDiv = document.querySelector('#selectSubCategory');
                            let Divrow = document.createElement('div');
                            Divrow.setAttribute('class','removecategory');
                            let selectlabel =  document.createElement('label');
                            selectlabel.setAttribute('class','form-label')
                            selectlabel.innerText = "Projects";
                            let selecthSelectTag =  document.createElement('select');
                            selecthSelectTag.setAttribute('class','form-control select2-show-search');
                            selecthSelectTag.setAttribute('id', 'subcategory');
                            selecthSelectTag.setAttribute('name', 'project');
                            selecthSelectTag.setAttribute('data-placeholder','Select Projects');
                            let selectoption = document.createElement('option');
                            selectoption.setAttribute('label','Select Projects')
                            selectDiv.append(Divrow);
                            Divrow.append(selectlabel);
                            Divrow.append(selecthSelectTag);
                            selecthSelectTag.append(selectoption);
                            //
                            $('.select2-show-search').select2();
                            $.each(data.subcategories,function(index,subcategory){
                            $('#subcategory').append('<option value="'+subcategory.name+'">'+subcategory.name+'</option>');
                            })
                        }
                        else{
                            $('#subcategory')?.empty();

                            if(data.subcatstatusexisting != 'statusexisting'){
                                document.querySelector("#selectssSubCategory").classList.add("d-none");
                            }else{
                                document.querySelector("#selectssSubCategory").classList.remove("d-none");
                            }
                            $('#selectSubCategory .removecategory')?.remove();
                        }
                        @if(setting('ENVATO_ON') == 'on')
                        //Envato Access
                        if(data.envatosuccess.length >= 1){
                            $('#envato_id')?.empty();
                            $('#envatopurchase .row')?.remove();
                            let selectDiv = document.querySelector('#envatopurchase');
                            let Divrow = document.createElement('div');
                            Divrow.setAttribute('class','row mt-4');
                            let Divcol3 = document.createElement('div');
                            Divcol3.setAttribute('class','col-md-12');
                            let selectlabel =  document.createElement('label');
                            selectlabel.setAttribute('class','form-label mb-0 mt-2')
                            selectlabel.innerHTML = "Envato Purchase Code <span class='text-red'>*</span>";
                            let divcol9 = document.createElement('div');
                            divcol9.setAttribute('class', 'col-md-12');
                            let selecthSelectTag =  document.createElement('input');
                            selecthSelectTag.setAttribute('class','form-control');
                            selecthSelectTag.setAttribute('type','search');
                            selecthSelectTag.setAttribute('id', 'envato_id');
                            selecthSelectTag.setAttribute('name', 'envato_id');
                            selecthSelectTag.setAttribute('placeholder', 'Enter Your Purchase Code');
                            let selecthSelectInput =  document.createElement('input');
                            selecthSelectInput.setAttribute('type','hidden');
                            selecthSelectInput.setAttribute('id', 'envato_support');
                            selecthSelectInput.setAttribute('name', 'envato_support');
                            selectDiv.append(Divrow);
                            Divrow.append(Divcol3);
                            Divcol3.append(selectlabel);
                            divcol9.append(selecthSelectTag);
                            divcol9.append(selecthSelectInput);
                            Divrow.append(divcol9);
                            $('.purchasecode').attr('disabled', true);

                        }else{
                            $('#envato_id')?.empty();
                            $('#envatopurchase .row')?.remove();
                            $('.purchasecode').removeAttr('disabled');
                        }
                        @endif
                    },
                    error:(data)=>{

                    }
                });
        });

        let envatoIdStatus = ""

        $("body").on('keyup', '#envato_id', function() {
                let value = $(this).val();
                if (value != '') {
                    if(value.length == '36'){
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "{{ route('guest.envatoverify') }}",
                            method: "POST",
                            data: {data: value, _token: _token},

                            dataType:"json",

                            success: function (data) {
                                if(data.valid == 'true'){
                                    $('#envato_id').addClass('is-valid');
                                    $('#envato_id').attr('readonly', true);
                                    $('.purchasecode').removeAttr('disabled');
                                    $('#envato_id').css('border', '1px solid #02f577');
                                    $('#envato_support').val('Supported');
                                    $('#expired_note').addClass('d-none');
                                    licensekey = data.key
                                    toastr.success(data.message);
                                    envatoIdStatus = "Supported"
                                    if(document.querySelector("#auto-expand").value &&
                                        document.querySelector("#category").value
                                    ){
                                        document.querySelector("#agentCreateTicket").disabled = false
                                        document.querySelector("#agentCreateTicket").classList.remove('disabled')
                                    }else{
                                        document.querySelector("#agentCreateTicket").disabled = true
                                        document.querySelector("#agentCreateTicket").classList.add('disabled')
                                    }
                                }
                                if(data.valid == 'expried'){
                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'on')

                                    $('.purchasecode').attr('disabled', true);
                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                    $('#envato_support').val('Expired');
                                    $('#expired_note').removeClass('d-none');
                                    toastr.error(data.message);
                                    document.querySelector("#agentCreateTicket").disabled = true
                                    document.querySelector("#agentCreateTicket").classList.add('disabled')
                                    @endif
                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
                                    $('#envato_id').addClass('is-valid');
                                    $('#envato_id').attr('readonly', true);
                                    $('.purchasecode').removeAttr('disabled');
                                    $('#envato_id').css('border', '1px solid #02f577');
                                    $('#expired_note123').removeClass('d-none');
                                    $('#envato_support').val('Expired');
                                    licensekey = data.key
                                    toastr.warning(data.message);
                                    envatoIdStatus = "Expired"
                                    if(document.querySelector("#auto-expand").value &&
                                        document.querySelector("#category").value
                                    ){
                                        document.querySelector("#agentCreateTicket").disabled = false
                                        document.querySelector("#agentCreateTicket").classList.remove('disabled')
                                    }else{
                                        document.querySelector("#agentCreateTicket").disabled = true
                                        document.querySelector("#agentCreateTicket").classList.add('disabled')
                                    }
                                    @endif

                                }
                                if(data.valid == 'false'){
                                    $('.purchasecode').attr('disabled', true);
                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                    toastr.error(data.message);
                                    document.querySelector("#agentCreateTicket").disabled = true
                                    document.querySelector("#agentCreateTicket").classList.add('disabled')
                                }


                            },
                            error: function (data) {

                            }
                        });
                    }
                }else{
                    toastr.error('Purchase Code field is Required');
                    $('.purchasecode').attr('disabled', true);
                    $('#envato_id').css('border', '1px solid #e13a3a');
                }
        });

        // When User try to Delete the chat to ask the are you sure alert
        document.querySelectorAll("[deleteroutelink]").forEach((ele)=>{
            ele.onclick = ()=>{
                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('The conversation will be deleted', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((res)=>{
                    if(res){
                        $.ajax({
                            type: "get",
                            url: ele.getAttribute("deleteroutelink"),
                            success: function (data) {
                                // let sideBarElement = document.querySelector(`[deleteroutelink="${ele.getAttribute("deleteroutelink")}"]`).closest(".checkforactive")
                                // document.querySelector(`#operator-conversation-Info[data-id='${sideBarElement.getAttribute('data-id')}']`).remove()
                                // document.querySelector(`#operator-conversation[operator-id='${sideBarElement.getAttribute('data-id')}']`).remove()
                                // document.querySelector(".chat-footer").classList.add("d-none")
                                // sideBarElement.remove()
                                // toastr.success("Chat Deleted")
                                localStorage.removeItem('livechatCustomer');
                                toastr.success("Chat Deleted");
                                location.reload();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        })
                    }
                })
            }
        })



        // For the Select2
        var optionFormat = function(item){
            if ( !item.id ) {
                return item.text;
            }

            var span = document.createElement('span');
            var imgUrl = item.element.getAttribute('data-kt-select2-country');
            var template = '';

            template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        $('#reassignSelect').select2({
            templateSelection: optionFormat,
            templateResult: optionFormat,
            placeholder: "Reassign to:",
            allowClear:true,
            dropdownParent: $('#staticBackdrop')
        });
    </script>

<script type="text/javascript">
    "use strict";

    // Variables
    var SITEURL = '{{url('')}}';
    const autoID = '{{Auth::user()->id}}'
    const autoUserInfo =JSON.parse('{!! Auth::user() !!}')
    const AllUserDetails = JSON.parse('{!! $user !!}')

    let customerData

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

    // File Upload Permission Check Btn
    document.querySelector("#fileUploadPermission").onclick = ()=>{
        let data = {
            permission : document.querySelector("#fileUploadPermission").checked,
            custUser : document.querySelector("#fileUploadPermission").getAttribute('data-id')
        }

        $.ajax({
            type: "post",
            url: '{{route('admin.liveChatCustFileUpload')}}',
            data: data,
            success: function (data) {
                if(data.success){
                    toastr.success(data.message)
                    location.reload()
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    // For the LiveChat Ticket Create
    document.querySelector("#agentCreateTicket").onclick = ()=>{
        document.querySelector("#agentCreateTicket").disabled = true
        document.querySelector("#agentCreateTicket").classList.add('disabled')
        let data ={
            email : customerData.email,
            username : customerData.username,
            subject : document.querySelector("#livechatTicketSubject").value,
            category : document.querySelector("#category").value,
            envato_id : document.querySelector("#envato_id") ? document.querySelector("#envato_id").value : "undefined",
            message : document.querySelector("#auto-expand").value,
            agree_terms : "agreed",
            ticket_source: "Livechat",
            ticket: [

            ],
        }

        // To add the subscategory
        if(document.querySelector("#subscategory").value){
            data.subscategory = document.querySelector("#subscategory").value
        }

        // To add the Envato status
        if(envatoIdStatus){
            data.envato_support = envatoIdStatus
        }

        // For the Images Add
        if(document.querySelector(".image-uploaded [imagesrc]")){
            document.querySelectorAll(".image-uploaded [imagesrc]").forEach((ele)=>{
                const parts = ele.getAttribute('imagesrc').split("/");
                const filename = parts[parts.length - 1];
                data.ticket.push(filename)
                ele.closest(".file-img").remove()
            })
        }

        $.ajax({
            type: "POST",
            url: '{{route('guest.openticketnootp')}}',
            data: data,
            success: function (responceData) {

                if(responceData?.error){
                    toastr.error(responceData.error);
                }
                if(responceData?.success){
                    toastr.success(responceData.success);
                    // To send the Ticket Created message
                    let PresentTimeFormatted = `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
                    let data ={
                        message : `\n ${responceData.success}  \n\nLink : ${responceData.data.customer_url}` ,
                        cust_id :customerData.id,
                        customerId: customerData.id
                    }


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
                                        You
                                        <span class="msg-sent-time">
                                            ${PresentTimeFormatted}
                                            <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
                                        </span>
                                    </span>
                                    <div class="main-chat-msg">
                                        <div style="white-space: pre-line;" class="text-start">
                                            <p class="mb-0 text-break">${data.message}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `
                    let operatorConversation = document.querySelector("#operator-conversation")

                    if(responceData.success){
                        if(operatorConversation.lastElementChild && operatorConversation.lastElementChild.id === "agentTyping") {
                            operatorConversation.insertBefore(senderMessage, operatorConversation.lastElementChild);
                        } else {
                            operatorConversation.appendChild(senderMessage);
                        }

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                        // After message sent show the message in the top
                        document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat)=>{
                            if(operatorsChat.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id')){
                                operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                                operatorsChat.querySelector(".chat-msg").innerText = data.message.replace(/\n/g, ' ')
                                operatorsChat.querySelector(".chat-time").innerText = new Date().toLocaleTimeString()
                                if(operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex')){
                                    operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex').remove()
                                }
                                if(document.querySelector("#messageStatusDiv")){
                                    document.querySelector("#messageStatusDiv").remove()
                                }
                                // To remove the Make as unread button
                                if(operatorsChat.querySelector(".markAsUnreadBtn")){
                                    operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                                }
                                const messageStatus = document.createElement("div");
                                messageStatus.id = "messageStatusDiv"
                                // operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                                document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document.querySelector("#chat-msg-scroll > li"));
                            }
                        })

                        // To Scroll Down the Conversation
                        operatorConversation.scrollBy(0, operatorConversation.scrollHeight)
                    }

                    $.ajax({
                        type: "post",
                        url: SITEURL + "/livechat/broadcast-message",
                        data: data,
                        success: function (data) {
                            if(localStorage.getItem("livechatMyopenedCustomer")){
                                localStorage.setItem("livechatSolvedCustomer",localStorage.getItem("livechatMyopenedCustomer"));
                                localStorage.removeItem("livechatMyopenedCustomer");
                            }
                            location.href = `${SITEURL}/admin/livechat/mark-as-solved?id=${customerData.id}`
                        },
                        error: function (error) {
                            if(error.responseJSON){
                                toastr.error("The message field cannot be null")
                            }
                        }
                    });
                }
            },
            error: function (data) {
            console.log('Error:', data);
            }
        });
    }

    // conversation Image Upload
    let liveChatFileUpload = "{{setting('liveChatFileUpload')}}"
    let livechatMaxFileUpload = "{{setting('AgentlivechatMaxFileUpload')}}"
    let livechatFileUploadMax = "{{setting('AgentlivechatFileUploadMax')}}"
    let livechatFileUploadTypes = "{{setting('AgentlivechatFileUploadTypes')}}"

    // Chat Image Upload
    if(document.querySelector("#chat-file-upload")){
        document.querySelector("#chat-file-upload").addEventListener('change',()=>{
            var fileInput = document.querySelector("#chat-file-upload");
            var file = fileInput.files[0];
            fileInput.value = ""
            var ThereIsError = false

            // For check the File Upload permissions
            if(livechatMaxFileUpload <= document.querySelectorAll(".image-uploaded .file-img").length){
                ThereIsError = { errorMessage: "The maximum file upload limit has been exceeded." };
            }else if(file.size > parseInt(livechatFileUploadMax) * 1024 * 1024) {
                ThereIsError = { errorMessage: `File size exceeds ${livechatFileUploadMax} MB. Please choose a smaller file.` };
            }else if(livechatFileUploadTypes && !livechatFileUploadTypes.split(',').some(ext => file.name.toLowerCase().toLowerCase().endsWith(ext.toLowerCase().trim()))) {
                ThereIsError = { errorMessage: `Invalid file extension. Please choose a file with ${livechatFileUploadTypes} extension(s).` };
            }else{
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

                fetch('{{route('admin.liveChatImageUpload')}}',{
                    method: 'POST',
                    body: formData,
                    headers:{
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const imageDiv = document.createElement("div");
                    let uploadedFilename = data.uploadedfilename;
                    imageDiv.classList.add("file-img")
                    imageDiv.innerHTML = `
                        <img imageSrc="${SITEURL}/public/uploads/livechat/${uploadedFilename}" src="${uploadedFilename.toLowerCase().endsWith(".jpg") || uploadedFilename.toLowerCase().endsWith(".png") ? `${SITEURL}/public/uploads/livechat/${uploadedFilename}` : `${SITEURL}/assets/images/svgs/file.svg`}"
                            style="
                            width: 100%;
                            max-height: 55px;
                            border-radius: 5px;
                            ${uploadedFilename.toLowerCase().endsWith(".jpg") || uploadedFilename.toLowerCase().endsWith(".png") ? '' : 'height: 65px;'}"
                        >
                        <button class="btn-danger rounded-circle imageRemoveClick">
                            <i class="fe fe-x fs-12"></i>
                        </button>
                    `
                    // For the Image Remove Click
                    imageDiv.querySelector(".imageRemoveClick").onclick = (ele)=>{
                        let fileImgElement = ele.currentTarget.closest(".file-img");
                        let data ={
                            filename : uploadedFilename,
                        }

                        $.ajax({
                            type: "post",
                            url: '{{route('admin.removeChatImage')}}',
                            data: data,
                            success: function (data) {
                                // To remove the Image
                                if (fileImgElement) {
                                    fileImgElement.remove();
                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                    // TO remove the uploading indication
                    document.querySelector("#uploadingIndication").remove()
                    document.querySelector(".image-uploaded").appendChild(imageDiv)
                    document.querySelector("#agentSendMessage").classList.remove('disabled')
                    document.querySelector("#agentSendMessage").removeAttribute('disabled')
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                });
            }else{
                toastr.error(ThereIsError.errorMessage)
            }

        })
    }

    // For the Customer Message Component
    let customerMessage = (data)=>{
            let custLi = document.createElement("li");
            custLi.className = "chat-item-start"
            custLi.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="${document.querySelector(`.checkforactive[data-id='${data.livechat_cust_id}'] #new-chat-user-bg`)?.getAttribute('style')}"> <span class="new-chat-user-letter">${data.livechat_username.slice(0,1).toUpperCase()}</span>
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                <span class="chatnameperson">${data.livechat_username}</span>
                                <span class="msg-sent-time">${formatTime(data.created_at)}</span>
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
                                `<div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${data.message}</p></div>`
                                }
                            </div>
                        </div>
                    </div>
            `
        return custLi
    }

    // To viewe the Pdf Files
    let AllFileViewer = (ele)=>{
        window.open(ele.getAttribute("imagesrc"))
    }

    // For the Agent Message Component
    let AgentMessage = (data)=>{
        let agentLi = document.createElement("li");
        agentLi.className = "chat-item-start"
        agentLi.innerHTML = `
                <div class="chat-list-inner">
                    <div class="chat-user-profile">
                        <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${data.sender_image ? data.sender_image : 'user-profile.png'})">
                        </span>
                    </div>
                    <div class="ms-3">
                        <span class="chatting-user-info">
                                ${data.livechat_username.replace(autoUserInfo.name,'You')}
                                <span class="msg-sent-time">
                                    ${formatTime(data.created_at)}
                                    ${data.status == 'seen' ?
                                    '<span class="chat-read-mark align-middle d-inline-flex"><i class="ri-check-double-line"></i></span>' :
                                     !data.status ? '<span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>' : ''
                                    }
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
                            `<div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break" >${data.message}</p></div>`
                            }
                        </div>
                    </div>
                </div>
        `
        return agentLi
    }

    // For The Comment Component
    let CommentMessage = (data)=>{
        let agentLi = document.createElement("li");
        agentLi.className = "chat-join-notify"
        agentLi.innerHTML = `<span>${data.message.replace(autoUserInfo.name,'You')} ${formatTime(data.created_at)}</span>`
        return agentLi
    }

    let FeedbackComment = (data)=>{
        data = JSON.parse(data.message)
        let agentLi = document.createElement("li");
        agentLi.className = "chat-join-notify"
        agentLi.innerHTML = `
        <span>Your chat has been rated ${data.starRating} out of 5 by the customer</span>
        `
        return agentLi
    }

    // SideBar and Online operators loop click
    function sideMenuOpenClickFunction(ele){
        // To Prevent the engage Conversation Btn
        document.querySelector('.textareaDiv').classList.add('d-none')
        document.querySelector('.engageConversation').classList.remove('d-none')

        // operators Click
        $.ajax({
            type: "get",
            url: SITEURL + `/livechat/singlecustdata/${ele.getAttribute("data-id")}`,
            data : {
                author : 'agent',
            },
            success: function (data) {
                let userconversation = data.livechatdata
                customerData = data.livechatcust

                // To remove the Font Bold
                if(ele.querySelector(".chat-msg") && (ele.querySelector(".chat-msg").classList.contains('font-weight-bold') || ele.querySelector(".unReadIndexNumber"))){
                    ele.querySelector(".chat-msg").classList.remove("font-weight-bold")
                }

                if(ele.querySelector(".unReadIndexNumber")){
                    ele.querySelector(".unReadIndexNumber").remove()
                    // To Change the Make as read to Mark as unread
                    if(ele.querySelector(".markAsUnreadBtn") && ele.querySelector(".markAsUnreadBtn").innerText == 'Mark As Read'){
                        ele.querySelector('.markAsUnreadBtn').href = ele.querySelector('.markAsUnreadBtn').href.replace("markasread","markasunread")
                        ele.querySelector('.markAsUnreadBtn').innerHTML = `<i class="ri-chat-check-line align-middle me-2 fs-18"></i> Mark As Unread`
                        ele.querySelector('.markAsreadBtn').onclick = ()=>{
                            localStorage.removeItem('livechatCustomer')
                        }
                    }
                }

                // Message conversation logic
                let chatFooter = document.querySelector(".chat-footer")
                chatFooter.classList.remove("d-none")

                // To check that if the user was include in the conversation
                if(customerData.engage_conversation){
                    JSON.parse(customerData.engage_conversation).map((ele)=>{
                        if(ele.id == autoID){
                            chatFooter.querySelector('.textareaDiv').classList.remove('d-none')
                            chatFooter.querySelector('.engageConversation').classList.add('d-none')
                        }
                    })
                }

                let mainChatContent = document.querySelector("#main-chat-content")
                mainChatContent.classList.remove("d-none")
                document.querySelector("#main-chat-content .no-articles").classList.add('d-none')
                if(document.querySelector("#operator-conversation")){
                    document.querySelector("#operator-conversation").remove()
                }
                if(document.querySelector("#operator-conversation-Info")){
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
                conversation.setAttribute('operator-id',ele.getAttribute("data-id"))

                // For the Chat Flow Created Date
                let currentDate = null;

                // For the Chat Bot Messages
                if(!data.livechatcust.deletedMessage){
                    // For the Chat Flow Created Date
                    const messageDate = formatDateString(data.livechatcust.created_at);
                    conversation.innerHTML += `
                        <li class="chat-day-label">
                        <span>${messageDate}</span>
                        </li>
                    `;
                    currentDate = messageDate;

                    JSON.parse(data.livechatcust.chat_flow_messages).map((flowMes)=>{
                        let updatedFlowMes = flowMes
                        updatedFlowMes.created_at = data.livechatcust.created_at
                        updatedFlowMes.status = "seen"
                        if(flowMes.authMessage == "agent"){
                            updatedFlowMes.livechat_username = "chatBot"
                            conversation.appendChild(AgentMessage(updatedFlowMes))
                        }else{
                            updatedFlowMes.livechat_username = data.livechatcust.username
                            conversation.appendChild(customerMessage(updatedFlowMes))
                        }
                    })
                }

                // For the Agent Messages
                if(userconversation){
                    userconversation.map((chatdata)=>{
                    const messageDate = formatDateString(chatdata.created_at);
                    if (messageDate !== currentDate) {
                        conversation.innerHTML += `
                        <li class="chat-day-label">
                        <span>${messageDate}</span>
                        </li>
                        `;
                        currentDate = messageDate;
                    }
                    // For the Agent Messages
                    if(chatdata.livechat_cust_id && chatdata.status != 'comment' && chatdata.message_type != "feedBack"){
                        conversation.appendChild(customerMessage(chatdata))
                    }else{
                        if(chatdata.status != 'comment' && chatdata.message_type != "feedBack"){
                            conversation.appendChild(AgentMessage(chatdata))
                        }
                        if(chatdata.status == 'comment' && chatdata.message_type != "feedBack"){
                            conversation.appendChild(CommentMessage(chatdata))
                        }
                        if(chatdata.message_type == "feedBack"){
                            conversation.appendChild(FeedbackComment(chatdata))
                        }
                        }

                    })
                }

                // For the receiver Info
                let receiverInfo = document.createElement("div");
                receiverInfo.className = "d-flex align-items-center py-2 px-3 rounded border-bottom bg-white mb-3"
                receiverInfo.id = "operator-conversation-Info"
                receiverInfo.setAttribute('data-id',ele.getAttribute("data-id"))
                receiverInfo.innerHTML =`
                    <div class="me-2 lh-1">
                    <span class="avatar avatar-md brround" style="${document.querySelector(`.checkforactive[data-id='${customerData.id}'] #new-chat-user-bg`)?.getAttribute('style')}">
                        <span class="new-chat-user-letter">${customerData.username.slice(0,1).toUpperCase()}</span>
                        ${customerData.liveChatCustomerOnlineUsers.split(',').includes(ele.getAttribute("data-id")) ?
                        '<span class="avatar-status bg-green"></span>'
                        :
                        '<span class="avatar-status"></span>'}
                    </span>
                    </div>
                    <div class="flex-fill">
                    <p class="mb-0 font-weight-semibold fs-14">
                    <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">${customerData.username}</a>
                    ${customerData.livechatTickets ? `<a class="badge badge-md bg-primary-transparent" href='{{route('admin.livechatTickets')}}?id=${customerData.gustId}'>${customerData.livechatTickets} Tickets Created</a>` : ``}
                    </p>
                    ${customerData.liveChatCustomerOnlineUsers.split(',').includes(ele.getAttribute("data-id")) ? '<p class="text-green mb-0 chatpersonstatus">online</p>' : '<p class="text-muted mb-0 chatpersonstatus">offline</p>'}
                    </div>
                    <div class="d-flex flex-wrap rightIcons">
                    <div class="dropdown ms-2 ">
                    <button aria-label="button" class="btn btn-icon btn-outline-light my-1 btn-wave waves-light waves-effect waves-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        ${ele.querySelector(".dropdown-menu").innerHTML}
                    </ul>
                    </div>
                    </div>
                `

                // To added the reAssign click Event
                receiverInfo.querySelector(".reAssignModalTrigger").onclick = ()=>{
                    var custId = receiverInfo.querySelector(".reAssignModalTrigger").getAttribute('custId');
                    var modalElement = document.getElementById('staticBackdrop');
                    modalElement.setAttribute('custId', custId);
                }

                mainChatContent.appendChild(receiverInfo)
                mainChatContent.appendChild(conversation)

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                // Set the sidebar active
                localStorage.setItem("livechatCustomer",ele.getAttribute("data-id"))
                document.querySelectorAll(".checkforactive").forEach((lielement)=>{
                        lielement.classList.remove("active")
                        if(lielement.getAttribute("data-id") == ele.getAttribute("data-id")){
                            lielement.classList.add("active")
                        }
                })

                let chatUserDetails = document.querySelector("#chat-user-details");
                chatUserDetails.classList.remove("d-none");

                // For the Side bar client Info
                if (document.querySelector("#chat-client-info")) {
                document.querySelector("#chat-client-info").remove();
                }

                let chatUserDetailsUl = document.createElement("ul");
                chatUserDetailsUl.className = "list-unstyled chat-client-info";
                chatUserDetailsUl.id = "chat-client-info";
                chatUserDetailsUl.innerHTML = `
                                                <li>
                                                <div class="d-flex gap-2 align-items-start text-break"><i class="ri-user-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.username}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-mail-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.email}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-phone-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.mobile_number}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-computer-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.browser_info}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-map-pin-range-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.login_ip}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-earth-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>${customerData.full_address}</div>
                                                </li>
                                                <li>
                                                    <div class="d-flex gap-2 align-items-start text-break"><i class="ri-map-pin-line font-weight-semibold flex-shrink-0 lh-1 align-middle icon-style1"></i>
                                                        ${customerData.city ? customerData.city + "," : ''} ${customerData.state ? customerData.state + "," : ''} ${customerData.country}
                                                    </div>
                                                </li>
                `;

                chatUserDetails.querySelector(".card-body").appendChild(chatUserDetailsUl);

                // File permission Check Btn
                if(document.querySelector('#fileUploadPermission')){
                    document.querySelector('#fileUploadPermission').closest('.card').classList.remove('d-none')
                    document.querySelector('#fileUploadPermission').setAttribute("data-id",ele.getAttribute("data-id"))
                    if(customerData.file_upload_permission){
                        document.querySelector('#fileUploadPermission').checked = true
                    }else{
                        document.querySelector('#fileUploadPermission').checked = false
                    }
                }

                // When User try to Delete the chat to ask the are you sure alert
                document.querySelectorAll("[deleteroutelink]").forEach((ele)=>{
                    ele.onclick = ()=>{
                        swal({
                            title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                            text: "{{lang('The conversation will be deleted', 'alerts')}}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        }).then((res)=>{
                            if(res){
                                $.ajax({
                                        type: "get",
                                        url: ele.getAttribute("deleteroutelink"),
                                        success: function (data) {
                                            // let sideBarElement = document.querySelector(`[deleteroutelink="${ele.getAttribute("deleteroutelink")}"]`).closest(".checkforactive")
                                            // document.querySelector(`#operator-conversation-Info[data-id='${sideBarElement.getAttribute('data-id')}']`).remove()
                                            // document.querySelector(`#operator-conversation[operator-id='${sideBarElement.getAttribute('data-id')}']`).remove()
                                            // document.querySelector(".chat-footer").classList.add("d-none")
                                            // sideBarElement.remove()
                                            // toastr.success("Chat Deleted")
                                            localStorage.removeItem('livechatCustomer');
                                            toastr.success("Chat Deleted");
                                            location.reload();
                                        },
                                        error: function (data) {
                                            console.log('Error:', data);
                                        }
                                    })
                            }
                        })
                    }
                })

            },
            error: function (data) {
                console.log('Error:', data);
            }
        })
    }

    // For the Side Bar Click
    document.querySelectorAll(".checkforactive").forEach((ele)=>{
        ele.onclick = ()=>{
            document.querySelector('.checkforactive.moveout')?.classList.add('removemovedout')
            setTimeout(() => {
                if(document.querySelector('.checkforactive.moveout.removemovedout')){
                    document.querySelector('.checkforactive.moveout.removemovedout').remove();
                    localStorage.removeItem('livechatCustomer');
                    location.reload();
                }
            }, 1500);
            sideMenuOpenClickFunction(ele)
        }
    })

    // Prevent the li loop click from the dropdown
    document.querySelectorAll('#chat-msg-scroll .chat-actions').forEach((ele)=>{
            ele.addEventListener('click', function (event) {
                event.stopPropagation();
            })

            // markAsUnreadBtn
            if(ele.querySelector('.markAsUnreadBtn')){
                ele.querySelector('.markAsUnreadBtn').addEventListener('click', function (event) {
                    event.stopPropagation();
                    if(localStorage.livechatCustomer == ele.closest('.checkforactive').getAttribute('data-group-uniq') || localStorage.livechatCustomer == ele.closest('.checkforactive').getAttribute('data-id')){
                        localStorage.removeItem('livechatCustomer')
                    }
                })
            }

            // Delete Btn
            if(ele.querySelector('[href*="conversationdelete"]')){
                ele.querySelector('[href*="conversationdelete"]').addEventListener('click', function (event) {
                    event.stopPropagation();
                    if(localStorage.livechatCustomer == ele.closest('.checkforactive').getAttribute('data-group-uniq') || localStorage.livechatCustomer == ele.closest('.checkforactive').getAttribute('data-id')){
                        localStorage.removeItem('livechatCustomer')
                    }
                })
            }
    })

    // Active liveChat Message
    let newchatcustVal = localStorage.livechatCustomer ?? document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id");
    if(newchatcustVal){
        // document.querySelector(".no-articles").classList.add("d-none");
        if(document.querySelector(`.checkforactive[data-id="${newchatcustVal}"]`)){
            sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${newchatcustVal}"]`))
        }else{
            if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
            }else{
                document.querySelector(".no-articles").classList.remove("d-none");
            }
        }
    }

    if(!newchatcustVal || '{{ $emptyConversation }}' == true){
        document.querySelector(".no-articles").classList.remove("d-none");
    }

     // For the review open
     if(localStorage.reviewlivechatCustomer){
        let nodeElement = document.createElement("li")
        nodeElement.className = "checkforactive"
        nodeElement.setAttribute("data-id",localStorage.reviewlivechatCustomer)
        nodeElement.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="me-2 lh-1">
                <span class="avatar brround" style="background-image: url(../uploads/profile/user-profile.png)">
                <span class="avatar-status "></span>
                </span>
            </div>
            <div class="flex-fill">
                <div class="mb-0 d-flex align-items-center justify-content-between">
                    <div class="font-weight-semibold">
                        <a href="javascript:void(0);">Spruko Technologies Private Limited</a>
                    </div>
                    <div class="float-end text-muted fw-normal fs-12 chat-time">
                        1 week ago
                    </div>
                    <div class="dropdown chat-actions lh-1">
                        <a aria-label="anchor" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="">
                        <i class="fe fe-more-vertical fs-18"></i>
                        </a>
                        <ul class="dropdown-menu" style="">
                        <li custid="7" class="reAssignModalTrigger" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><a class="dropdown-item" href="javascript:void(0);"><i class="ri-user-shared-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>Re-Assign</a>
                        </li>
                        <li>
                            <a class="dropdown-item" deleteroutelink="https://localhost/livechat/admin/livechat/conversation-delete?unqid=bYLott4Bz"><i class="ri-delete-bin-fill align-middle me-2 fs-17 text-muted chat-info-optionicon"></i>Delete</a>
                        </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="chat-msg text-truncate fs-13 text-default custrecentmessage" data-id="100">ngfng</span>
                </div>
            </div>
        </div>
        `
        sideMenuOpenClickFunction(nodeElement)
        localStorage.removeItem('reviewlivechatCustomer')
    }

    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    const period = hours >= 12 ? "PM" : "AM";

    const formattedTime = `${((hours + 11) % 12) + 1}:${minutes}${period}`;
    const autoUser = '{{Auth::user()->name}}'

     // Send Message button
     document.querySelector("#agentSendMessage").onclick = ()=>{
            afterMessageSend = false
            let operatorConversation = document.querySelector("#operator-conversation")

            // Agent Message Send
            let PresentTimeFormatted = `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
            let data ={
                message : document.querySelector("#auto-expand").value,
                cust_id :customerData.id,
                customerId: customerData.id
            }


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
                                You
                                <span class="msg-sent-time">
                                    ${PresentTimeFormatted}
                                    <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
                                </span>
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start">
                                    <p class="mb-0 text-break">${data.message}</p>
                                </div>
                            </div>
                        </div>
                    </div>
            `

            if(document.querySelector("#auto-expand").value.trim()){
                if(operatorConversation.lastElementChild && operatorConversation.lastElementChild.id === "agentTyping") {
                    operatorConversation.insertBefore(senderMessage, operatorConversation.lastElementChild);
                } else {
                    operatorConversation.appendChild(senderMessage);
                }

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                // After message sent show the message in the top
                document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat)=>{
                    if(operatorsChat.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id')){
                        operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                        operatorsChat.querySelector(".chat-msg").innerText = data.message.replace(/\n/g, ' ')
                        operatorsChat.querySelector(".chat-time").innerText = new Date().toLocaleTimeString()
                        if(operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex')){
                            operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex').remove()
                        }
                        if(document.querySelector("#messageStatusDiv")){
                            document.querySelector("#messageStatusDiv").remove()
                        }
                        // To remove the Make as unread button
                        if(operatorsChat.querySelector(".markAsUnreadBtn")){
                            operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                        }
                        const messageStatus = document.createElement("div");
                        messageStatus.id = "messageStatusDiv"
                        // operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                        document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document.querySelector("#chat-msg-scroll > li"));
                    }
                })

                // To Scroll Down the Conversation
                operatorConversation.scrollBy(0, operatorConversation.scrollHeight)
            }



            if(document.querySelector("#auto-expand").value.trim()){
                $.ajax({
                    type: "post",
                    url: SITEURL + "/livechat/broadcast-message",
                    data: data,
                    success: function (data) {

                        // New Message will add to sidebar
                        const existingLi = Array.from(document.querySelectorAll("#chat-msg-scroll>li")).find(li => li.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id'));
                        if(!existingLi){
                            const newMessageLiElement = document.createElement("li");
                            newMessageLiElement.className = "checkforactive active"
                            newMessageLiElement.setAttribute("data-id",document.querySelector("#operator-conversation").getAttribute('operator-id'))
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
                            newMessageLiElement.onclick = ()=>{
                                sideMenuOpenClickFunction(newMessageLiElement);
                            }
                            document.querySelector("#chat-msg-scroll").insertBefore(newMessageLiElement, document.querySelector("#chat-msg-scroll > li"));

                        }
                    },
                    error: function (error) {
                        if(error.responseJSON){
                            toastr.error("The message field cannot be null")
                        }
                    }
                });
            }

            // to slow the Image Upload Message
            let imageUploadTimeout = document.querySelector("#auto-expand").value ? 1000 : 0
            setTimeout(() => {
                // For the Image Upload
                if(document.querySelectorAll(".image-uploaded .file-img").length){
                    document.querySelectorAll(".image-uploaded .file-img img").forEach((ele)=>{
                        let data ={
                            message : ele.getAttribute('imageSrc'),
                            cust_id :customerData.id,
                            customerId: customerData.id,
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
                                        <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
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
                        if(imageMessage.querySelector(".imageMessageLiveChat")){
                            // To Open the Image Viewer
                            imageMessage.querySelector(".imageMessageLiveChat").onclick = (ele)=>{
                                document.querySelector(".liveChatImageViewer").classList.remove("d-none")
                                document.querySelector(".liveChatImageViewer img").src = ele.target.getAttribute("imagesrc")
                                document.querySelector(".liveChatImageViewer .liveChatImageClose").onclick = ()=>{
                                    // To Close the Image Viewer
                                    document.querySelector(".liveChatImageViewer").classList.add("d-none")
                                }
                            }
                        }

                        // To add The Image In the Chat
                        if(operatorConversation.lastElementChild && operatorConversation.lastElementChild.id === "agentTyping") {
                            operatorConversation.insertBefore(imageMessage, operatorConversation.lastElementChild);
                        } else {
                            operatorConversation.appendChild(imageMessage);
                        }

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                        $.ajax({
                            type: "post",
                            url: SITEURL + "/livechat/broadcast-message",
                            data: data,
                            success: function (data) {
                                // To remove the Added Images
                                document.querySelector('.image-uploaded').innerHTML = ""
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    })
                }
            }, imageUploadTimeout);

            document.querySelector("#auto-expand").value = ''

            setTimeout(() => {
                afterMessageSend = true
            }, 500);
    }

    // Send Message button
    document.querySelector("#agentSendMessage").onclick = ()=>{
            afterMessageSend = false
            let operatorConversation = document.querySelector("#operator-conversation")

            // Agent Message Send
            let PresentTimeFormatted = `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`;
            let data ={
                message : document.querySelector("#auto-expand").value,
                cust_id :customerData.id,
                customerId: customerData.id
            }


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
                                You
                                <span class="msg-sent-time">
                                    ${PresentTimeFormatted}
                                    <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
                                </span>
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start">
                                    <p class="mb-0 text-break">${data.message}</p>
                                </div>
                            </div>
                        </div>
                    </div>
            `

            if(document.querySelector("#auto-expand").value.trim()){
                if(operatorConversation.lastElementChild && operatorConversation.lastElementChild.id === "agentTyping") {
                    operatorConversation.insertBefore(senderMessage, operatorConversation.lastElementChild);
                } else {
                    operatorConversation.appendChild(senderMessage);
                }

                // To Scroll Down the Conversation
                document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                // After message sent show the message in the top
                document.querySelectorAll("#chat-msg-scroll > li").forEach((operatorsChat)=>{
                    if(operatorsChat.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id')){
                        operatorsChat.querySelector(".chat-msg").classList.remove("font-weight-bold")
                        operatorsChat.querySelector(".chat-msg").innerText = data.message.replace(/\n/g, ' ')
                        operatorsChat.querySelector(".chat-time").innerText = new Date().toLocaleTimeString()
                        if(operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex')){
                            operatorsChat.querySelector(".chat-msg").parentNode.querySelector('.d-inline-flex').remove()
                        }
                        if(document.querySelector("#messageStatusDiv")){
                            document.querySelector("#messageStatusDiv").remove()
                        }
                        // To remove the Make as unread button
                        if(operatorsChat.querySelector(".markAsUnreadBtn")){
                            operatorsChat.querySelector(".markAsUnreadBtn").parentNode.remove()
                        }
                        const messageStatus = document.createElement("div");
                        messageStatus.id = "messageStatusDiv"
                        // operatorsChat.querySelector(".chat-msg").parentNode.appendChild(messageStatus)
                        document.querySelector("#chat-msg-scroll").insertBefore(operatorsChat, document.querySelector("#chat-msg-scroll > li"));
                    }
                })

                // To Scroll Down the Conversation
                operatorConversation.scrollBy(0, operatorConversation.scrollHeight)
            }



            if(document.querySelector("#auto-expand").value.trim()){
                $.ajax({
                    type: "post",
                    url: SITEURL + "/livechat/broadcast-message",
                    data: data,
                    success: function (data) {

                        // New Message will add to sidebar
                        const existingLi = Array.from(document.querySelectorAll("#chat-msg-scroll>li")).find(li => li.getAttribute('data-id') == document.querySelector("#operator-conversation").getAttribute('operator-id'));
                        if(!existingLi){
                            const newMessageLiElement = document.createElement("li");
                            newMessageLiElement.className = "checkforactive active"
                            newMessageLiElement.setAttribute("data-id",document.querySelector("#operator-conversation").getAttribute('operator-id'))
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
                            newMessageLiElement.onclick = ()=>{
                                sideMenuOpenClickFunction(newMessageLiElement);
                            }
                            document.querySelector("#chat-msg-scroll").insertBefore(newMessageLiElement, document.querySelector("#chat-msg-scroll > li"));

                        }
                    },
                    error: function (error) {
                        if(error.responseJSON){
                            toastr.error("The message field cannot be null")
                        }
                    }
                });
            }

            // to slow the Image Upload Message
            let imageUploadTimeout = document.querySelector("#auto-expand").value ? 1000 : 0
            setTimeout(() => {
                // For the Image Upload
                if(document.querySelectorAll(".image-uploaded .file-img").length){
                    document.querySelectorAll(".image-uploaded .file-img img").forEach((ele)=>{
                        let data ={
                            message : ele.getAttribute('imageSrc'),
                            cust_id :customerData.id,
                            customerId: customerData.id,
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
                                        <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
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
                        if(imageMessage.querySelector(".imageMessageLiveChat")){
                            // To Open the Image Viewer
                            imageMessage.querySelector(".imageMessageLiveChat").onclick = (ele)=>{
                                document.querySelector(".liveChatImageViewer").classList.remove("d-none")
                                document.querySelector(".liveChatImageViewer img").src = ele.target.getAttribute("imagesrc")
                                document.querySelector(".liveChatImageViewer .liveChatImageClose").onclick = ()=>{
                                    // To Close the Image Viewer
                                    document.querySelector(".liveChatImageViewer").classList.add("d-none")
                                }
                            }
                        }

                        // To add The Image In the Chat
                        if(operatorConversation.lastElementChild && operatorConversation.lastElementChild.id === "agentTyping") {
                            operatorConversation.insertBefore(imageMessage, operatorConversation.lastElementChild);
                        } else {
                            operatorConversation.appendChild(imageMessage);
                        }

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

                        $.ajax({
                            type: "post",
                            url: SITEURL + "/livechat/broadcast-message",
                            data: data,
                            success: function (data) {
                                // To remove the Added Images
                                document.querySelector('.image-uploaded').innerHTML = ""
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    })
                }
            }, imageUploadTimeout);

            document.querySelector("#auto-expand").value = ''

            setTimeout(() => {
                afterMessageSend = true
            }, 500);
    }

    // Enter Message Send Function
    document.getElementById('auto-expand').addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            document.getElementById('agentSendMessage').click();
        }
    });

    // Engage Conversation button click
    document.querySelector('.engageConversation button').onclick = ()=>{

            $('.engageConversation button').html('{{ lang("Joining") }} ... <i class="fa fa-spinner fa-spin"></i>');
            $('.engageConversation button').prop('disabled', true);

            let data ={
                users : JSON.stringify([autoUserInfo]),
                custId : document.querySelector("#operator-conversation").getAttribute('operator-id')
            }

            $.ajax({
                type: "post",
                url: '{{route('admin.engageConversation')}}',
                data: data,
                success: function (data) {
                    document.querySelector('.textareaDiv').classList.remove('d-none');
                    document.querySelector('.engageConversation').classList.add('d-none');
                    document.querySelector('.checkforactive.active')?.classList.add('moveout');
                    // let ele = document.querySelector(`.checkforactive[data-id="${localStorage.livechatCustomer}"]`);
                    // ele?.querySelector(".chat-msg").classList.remove("font-weight-bold");

                    // if(ele.querySelector(".unReadIndexNumber")){
                    //     ele.querySelector(".unReadIndexNumber").remove()
                    //     // To Change the Make as read to Mark as unread
                    //     if(ele.querySelector(".markAsUnreadBtn") && ele.querySelector(".markAsUnreadBtn").innerText == 'Mark As Read'){
                    //         ele.querySelector('.markAsUnreadBtn').href = ele.querySelector('.markAsUnreadBtn').href.replace("markasread","markasunread")
                    //         ele.querySelector('.markAsUnreadBtn').innerHTML = `<i class="ri-chat-check-line align-middle me-2 fs-18"></i> Mark As Unread`
                    //         ele.querySelector('.markAsreadBtn').onclick = ()=>{
                    //             localStorage.removeItem('livechatCustomer')
                    //         }
                    //     }
                    // }

                    $('.engageConversation button').html('{{ lang("Join this conversation") }} <i class="ri-discuss-line ms-1"></i>');
                    $('.engageConversation button').prop('disabled', false);
                    // let redirectRout = '{{route('admin.myOpenedChats')}}'
                    // location.href = redirectRout
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
    }

    // To add the Chat Unq Id to the Assign modal
    document.querySelectorAll(".reAssignModalTrigger").forEach((ele)=>{
        ele.onclick = ()=>{
            var custId = ele.getAttribute('custId');
            var modalElement = document.getElementById('staticBackdrop');
            modalElement.setAttribute('custId', custId);
        }
    })

    // reassign Select Submit click
    $('#reassignSelectBtn').on('click', function () {
        var selectedValue = $('#reassignSelect').val();
        let data ={
                assignUser : selectedValue,
                custId : document.querySelector('#staticBackdrop').getAttribute("custId")
            }
        $.ajax({
            type: "post",
            url: '{{route('admin.conversationReassign')}}',
            data: data,
            success: function (data) {
                // document.querySelector(`.checkforactive[data-id="${data.custId}"]`)?.remove()
                // document.querySelector(`#operator-conversation-Info[data-id="${data.custId}"]`)?.remove()
                // document.querySelector(`#operator-conversation[operator-id="${data.custId}"]`)?.remove()
                // document.querySelector(".chat-footer")?.classList.add("d-none")
                localStorage.removeItem('livechatCustomer');
                toastr.success("This chat is re-assigned");
                location.reload();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    // Operator typing indaction
    var debounceTimeout2;
    var afterMessageSend = true

    document.querySelector("#auto-expand").oninput = (ele)=>{
            let agentSendMessageBtn = document.querySelector("#agentSendMessage")

            // For the message Send Btn Disabled and Enabled
            if(ele.target.value){
                agentSendMessageBtn.disabled = false
                agentSendMessageBtn.classList.remove('disabled')
            }else{
                agentSendMessageBtn.disabled = true
                agentSendMessageBtn.classList.add('disabled')
            }

            clearTimeout(debounceTimeout2);
            debounceTimeout2 = setTimeout(function() {
                if(afterMessageSend){
                    let data ={
                        message : null,
                        username : autoUserInfo.name,
                        id : autoID,
                        customerId : document.querySelector("#operator-conversation").getAttribute("operator-id"),
                        typingMessage : document.querySelector("#auto-expand").value,
                        agentInfo : JSON.stringify(autoUserInfo)
                    }

                    $.ajax({
                        type: "post",
                        url: SITEURL + "/livechat/broadcast-message-typing",
                        data: data,
                        success: function (data) {
                            console.log(data);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }, 500);
    }

    var debounceTimeout;
    var debounceTimeout3;
    var debounceTimeout4;
    var pastMessage = ""

    Echo.channel('liveChat').listen('ChatMessageEvent',(socket)=>{


        // Conversation Messages
        let liveChatConversation = document.querySelector("#operator-conversation")

        // For Customer Online Update
        if(socket.customerId && socket.onlineStatusUpdate){
            if(socket.onlineStatusUpdate == 'offline'){
                document.querySelector(`#chat-msg-scroll [data-id='${socket.customerId}'] .avatar-status`)?.classList.remove("bg-green")
                if(document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`)){
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).innerText = "offline"
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .avatar-status`).classList.remove("bg-green")
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).classList.remove('text-green')
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).classList.add('text-muted')
                }
            }
            if(socket.onlineStatusUpdate == 'online'){
                document.querySelector(`#chat-msg-scroll [data-id='${socket.customerId}'] .avatar-status`)?.classList.add("bg-green")
                if(document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`)){
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).innerText = "online"
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .avatar-status`).classList.add("bg-green")
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).classList.add('text-green')
                    document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"] .chatpersonstatus`).classList.remove('text-muted')
                }
            }
        }

        // For the New Customer was created
        if(socket.message == "newUser" && !socket.customerId){
            setTimeout(() => {
                location.reload()
            }, 500);
        }

        // To Update the Message in the Side Bar
        if(document.querySelector(`.checkforactive[data-id='${socket.customerId}']`) && socket.message){
            document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .chat-msg`).innerHTML = socket.message
            document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .chat-time`).innerText = `${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}`
            if(!document.querySelector(`.checkforactive[data-id='${socket.customerId}']`).classList.contains("active")){
                document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .chat-msg`).classList.add("font-weight-bold")
            }
            clearTimeout(debounceTimeout4);
            pastMessage.__proto__.customerId = ""

            // For the update unread index number
            if(socket.customerId == socket.id && !document.querySelector(`.checkforactive[data-id='${socket.customerId}']`).classList.contains("active")){
                let unReadIndexNumberVar = document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .unReadIndexNumber`)
                if(unReadIndexNumberVar){
                    unReadIndexNumberVar.innerText = parseInt(unReadIndexNumberVar.innerText) + 1
                }else{
                    var chatMsgSpan = document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .chat-msg`);
                    var newSpan = document.createElement('span');
                    newSpan.className = 'ms-auto me-2 badge bg-success-transparent rounded-circle unReadIndexNumber';
                    newSpan.textContent = '1';
                    chatMsgSpan.parentNode.insertBefore(newSpan, chatMsgSpan.nextSibling);
                }
            }

            // To add the Top in the Sidebar
            document.querySelector("#chat-msg-scroll").insertBefore(document.querySelector(`.checkforactive[data-id='${socket.customerId}']`), document.querySelector("#chat-msg-scroll > li"))
        }


        // Update the Users in the sidebar when join, reassign, and leave. and also comments
        if(document.querySelector(`.checkforactive[data-id='${socket.customerId}']`) && socket.engageUser && socket.agentInfo && socket.comments){
            let ChatIncludeUsers = JSON.parse(socket.agentInfo);
            ChatIncludeUsers.forEach((e, i)=>{
                if(e.id == autoID){
                    document.querySelector(`.checkforactive[data-id='${socket.customerId}']`).classList.add('moveout')

                    if (document.querySelector('.main-chart-wrapper').querySelector('#operator-conversation-Info').getAttribute('data-id') == socket.customerId) {
                        document.querySelector('.textareaDiv').classList.remove('d-none');
                        document.querySelector('.engageConversation').classList.add('d-none');
                    }
                } else {

                    let avatarDiv = document.createElement("div")
                    ChatIncludeUsers.map((ele,index)=>{
                        if(index <= 1){
                            let avatarSpan = document.createElement("span")
                            avatarSpan.className = "avatar brround avatar-sm"
                            avatarSpan.style.backgroundImage = `url(../uploads/profile/${ele.image ? ele.image : 'user-profile.png'})`
                            avatarSpan.innerText = ""
                            avatarSpan.setAttribute("data-bs-toggle","tooltip")
                            avatarSpan.setAttribute("data-bs-placement","top")
                            avatarSpan.setAttribute("data-bs-title",ele.name)
                            avatarDiv.appendChild(avatarSpan)
                        }
                    })
                    if(ChatIncludeUsers.length > 2){
                        let avatarSpan = document.createElement("span")
                        avatarSpan.className = "avatar brround bg-light text-dark avatar-sm"
                        avatarSpan.innerText = `+${ChatIncludeUsers.length-2}`
                        avatarSpan.setAttribute("data-bs-toggle","tooltip")
                        avatarSpan.setAttribute("data-bs-placement","top")
                        let fullNames = ChatIncludeUsers.map(item => `${item.firstname} ${item.lastname}`);
                        avatarSpan.setAttribute("data-bs-title",fullNames)
                        avatarDiv.appendChild(avatarSpan)
                    }

                    // To add in the Sidebar
                    if(document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .avatar-list`)){
                        document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .avatar-list`).innerHTML = avatarDiv.innerHTML

                        // The Leave button Update .
                        if(avatarDiv.querySelectorAll("span").length > 1){
                            let leaveLiElement = document.createElement("li")
                            leaveLiElement.innerHTML = `
                            <a class="dropdown-item" href="{{route('admin.conversationLeave')}}?id=${socket.customerId}">
                                <i class="ri-chat-delete-line align-middle me-2 fs-18"></i>
                                Leave
                            </a>
                            `
                            document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .dropdown-menu`).appendChild(leaveLiElement)
                        }else{
                            document.querySelectorAll(`.checkforactive[data-id='${socket.customerId}'] .dropdown-menu li`).forEach((element)=>{
                                if(element.textContent.replace(/\s+/g, ' ').trim() == 'Leave'){
                                    element.remove()
                                }
                            })
                        }

                        document.querySelector(`.checkforactive[data-id="${socket.customerId}"] .custrecentmessage`).innerHTML = socket.comments
                        if(!document.querySelector(`.checkforactive[data-id='${socket.customerId}']`).classList.contains("active")){
                            document.querySelector(`.checkforactive[data-id="${socket.customerId}"] .custrecentmessage`).classList.add("font-weight-bold")
                        }
                        document.querySelector(`.checkforactive[data-id="${socket.customerId}"] .chat-time`).innerHTML = formattedTime
                    }
                    // To adding comments in the conversation
                    if(document.querySelector(`#operator-conversation[operator-id="${socket.customerId}"]`)){

                        let commentMessage = document.createElement("li");
                        commentMessage.className = "chat-join-notify"
                        commentMessage.innerHTML = `
                        <span>${socket.comments.replace(autoUserInfo.name,'You')} ${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                        `

                        if(document.querySelector("#typingIndication")){
                            document.querySelector("#typingIndication").remove()
                        }

                        liveChatConversation.appendChild(commentMessage)

                        // To Scroll Down the Conversation
                        document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)
                    }
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            })

        }

        // For the Slove the Chat remove that chat in the side bar
        if(socket.comments && socket.comments.includes('Solved') && socket.customerId){
            document.querySelector(`.checkforactive[data-id="${socket.customerId}"]`)?.remove()
            document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"]`)?.remove()
            document.querySelector(`#operator-conversation[operator-id="${socket.customerId}"]`)?.remove()
            document.querySelector(".engageConversation")?.classList.add("d-none")
            document.querySelector(".chat-footer")?.classList.add("d-none")
            location.reload();
        }

        // To remove the customer from the sidebar if any user join
        if(document.querySelector(`.checkforactive[data-id="${socket.customerId}"]`) && socket.engageUser && socket.id != autoID && !location.href.includes('operatorID')){
            document.querySelector(`.checkforactive[data-id="${socket.customerId}"]`)?.remove()
            document.querySelector(`#operator-conversation-Info[data-id="${socket.customerId}"]`)?.remove()
            document.querySelector(`#operator-conversation[operator-id="${socket.customerId}"]`)?.remove()
            document.querySelector(".engageConversation")?.classList.add("d-none")
            if(document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")){
                sideMenuOpenClickFunction(document.querySelector(`.checkforactive[data-id="${document.querySelector("#chat-msg-scroll li")?.getAttribute("data-id")}"]`))
            }else{
                location.reload()
            }
        }

        // For The Message statusUpdate
        if(socket.userMessageStatusUpdate && socket.customerId){
            document.querySelectorAll(`#operator-conversation[operator-id="${socket.customerId}"] .msg-sent-time .chat-read-icon`).forEach((element)=>{
                element.classList.add('chat-read-mark')
                element.classList.remove('chat-read-icon')
                element.querySelector('.ri-check-fill').className = "ri-check-double-line"
            })
        }

        // For the Messages Add
        if(socket.messageType != "feedBack" && (socket.customerId == liveChatConversation?.getAttribute("operator-id") || socket.id == liveChatConversation?.getAttribute("operator-id")) && socket.message && socket.message != "newUser" && socket.id != autoID){
            let custMessage = document.createElement("li");
            if(AllUserDetails.some(obj => obj.id == socket.id && obj.name == socket.userName) || socket.messageType == "welcomeMessage"){
                custMessage.className = "chat-item-start"
                custMessage.innerHTML = `
                <div class="chat-list-inner">
                    <div class="chat-user-profile">
                        ${socket.agentInfo ? `<span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${socket.agentInfo.image ? socket.agentInfo.image : 'user-profile.png'})"></span>` :
                                            `<span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/user-profile.png)"></span>`}
                    </div>
                    <div class="ms-3">
                        <span class="chatting-user-info">
                                ${socket.userName ? socket.userName : 'chatBot'}
                                <span class="msg-sent-time">
                                    ${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}
                                    <span class="chat-read-icon align-middle d-inline-flex"><i class="ri-check-fill"></i></span>
                                </span>
                        </span>
                        <div class="main-chat-msg">
                            <div style="white-space: pre-line;" class="text-start"><p class="mb-0 text-break">${socket.message}</p></div>
                        </div>
                    </div>
                </div>
                `
            }else{
                custMessage.className = AllUserDetails.some(obj => obj.id === socket.id) ? "chat-item-start" : "chat-item-start"
                custMessage.innerHTML = `
                    <div class="chat-list-inner">
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md brround" style="${document.querySelector(`.checkforactive[data-id='${socket.customerId}'] #new-chat-user-bg`)?.getAttribute('style')}">
                                <span class="new-chat-user-letter">${socket.userName.slice(0,1).toUpperCase()}</span>
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="chatting-user-info">
                                <span class="chatnameperson">${socket.userName}</span> <span class="msg-sent-time">${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                            </span>
                            <div class="main-chat-msg">
                                <div style="white-space: pre-line;" class="text-start">
                                    <p class="mb-0 text-break">${socket.message}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            }

            if(document.querySelector("#typingIndication")){
                document.querySelector("#typingIndication").remove()
            }

            liveChatConversation?.appendChild(custMessage)

            // To add the seen Indaction.
            $.ajax({
                type: "get",
                url: SITEURL + `/livechat/singlecustdata/${document.querySelector("#operator-conversation").getAttribute("operator-id")}`,
                success: function (data) {

                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });

            // To Scroll Down the Conversation
            document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

        }

        // For the feedBack Form comment add
        if(socket.messageType == "feedBack" && socket.customerId == liveChatConversation?.getAttribute("operator-id") && socket.message){
            let FeedbackMessage = JSON.parse(socket.message)
            let agentLi = document.createElement("li");
            agentLi.className = "chat-join-notify"
            agentLi.innerHTML = `
            <span>Your chat has been rated ${FeedbackMessage.starRating} out of 5 by the customer</span>
            `

            liveChatConversation.appendChild(agentLi)
            // To Scroll Down the Conversation
            document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

        }

        // To add Typing Indication for the customer
        if(socket.typingMessage && liveChatConversation?.getAttribute("operator-id") == socket.customerId && !socket.agentInfo){
            let typingIndication = document.querySelector("#typingIndication")

            if(typingIndication){
                typingIndication.remove()
            }

            let custMessage = document.createElement("li");
            custMessage.className = "chat-item-start"
            custMessage.id = "typingIndication"
            custMessage.innerHTML = `
                <div class="chat-list-inner">
                    <div class="chat-user-profile">
                        <span class="avatar avatar-md brround" style="${document.querySelector(`.checkforactive[data-id='${socket.customerId}'] #new-chat-user-bg`)?.getAttribute('style')}">
                            <span class="new-chat-user-letter">${socket.userName.slice(0,1).toUpperCase()}</span>
                        </span>
                    </div>
                    <div class="ms-3">
                        <span class="chatting-user-info">
                            <span class="chatnameperson">${socket.userName}</span> <span class="msg-sent-time">${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                        </span>
                        <div class="main-chat-msg">
                            <div class="d-flex">
                                <p class="mb-0">${socket.typingMessage}</p>
                                <b class="ms-3">Typing...</b>
                            </div>
                        </div>
                    </div>
                </div>
            `

            if(socket.typingMessage != "empty"){
                liveChatConversation.appendChild(custMessage)
            }else{
                typingIndication?.remove()
            }

            // To Scroll Down the Conversation
            document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)


            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                if(document.querySelector("#typingIndication")){
                    document.querySelector("#typingIndication").querySelector(".main-chat-msg b").innerText = "Typing Ended"
                }
              }, 5000);
        }

        // To add Typing Indication for the Agent
        if(socket.typingMessage && liveChatConversation?.getAttribute("operator-id") == socket.customerId && socket.agentInfo && socket.id != autoID){
            let typingIndication = document.querySelector("#typingIndication")

            if(typingIndication){
                typingIndication.remove()
            }

            let agentMessage = document.createElement("li");
            agentMessage.className = "chat-item-start"
            agentMessage.id = "typingIndication"
            agentMessage.innerHTML = `
                <div class="chat-list-inner">
                    <div class="chat-user-profile">
                        <span class="avatar avatar-md brround" style="background-image: url(../uploads/profile/${JSON.parse(socket.agentInfo).image ? JSON.parse(socket.agentInfo).image : 'user-profile.png'})"></span>
                    </div>
                    <div class="ms-3">
                        <span class="chatting-user-info">
                            <span class="chatnameperson">${socket.userName}</span> <span class="msg-sent-time">${((new Date().getHours() + 11) % 12) + 1}:${new Date().getMinutes()}${new Date().getHours() >= 12 ? "PM" : "AM"}</span>
                        </span>
                        <div class="main-chat-msg">
                            <div class="d-flex">
                                <p class="mb-0">${socket.typingMessage}</p>
                                <b class="ms-3">Typing...</b>
                            </div>
                        </div>
                    </div>
                </div>
            `

            if(socket.typingMessage != "empty"){
                liveChatConversation.appendChild(agentMessage)
            }else{
                typingIndication?.remove()
            }

            // To Scroll Down the Conversation
            document.querySelector("#operator-conversation").scrollBy(0, document.querySelector("#operator-conversation").scrollHeight)

            clearTimeout(debounceTimeout3);
            debounceTimeout3 = setTimeout(function() {
                if(document.querySelector("#typingIndication")){
                    document.querySelector("#typingIndication").querySelector(".main-chat-msg b").innerText = "Typing Ended"
                }
            }, 5000);
        }

        // For the Typing Indication updating in sidebar
        if(document.querySelector(`.checkforactive[data-id='${socket.customerId}']`) && socket.typingMessage && socket.id != autoID){
            if(!document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .custrecentmessage`).innerText.includes('Typing ...')){
                pastMessage = document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .custrecentmessage`).innerText
            }

            document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .custrecentmessage`).innerText = `${socket.userName} Typing ...`


            clearTimeout(debounceTimeout4);
            debounceTimeout4 = setTimeout(function() {
                document.querySelector(`.checkforactive[data-id='${socket.customerId}'] .custrecentmessage`).innerText = pastMessage
            }, 5000)

        }

    })


</script>
@endsection

