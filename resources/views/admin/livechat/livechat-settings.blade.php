@extends('layouts.adminmaster')
@section('styles')
    <style>
        .rtl .notify-sound {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
    </style>

    <!-- Select2 css -->
    <link href="{{ asset('build/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />


    <!-- INTERNAl Tag css -->
    <link href="{{ asset('build/assets/plugins/taginput/bootstrap-tagsinput.css') }}?v=<?php echo time(); ?>"
        rel="stylesheet" />
    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection
@section('content')
    @php
        $domainname = url('/');
    @endphp
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span
                    class="font-weight-normal text-muted ms-2">{{ lang('Live-chat Setting', 'setting') }}</span></h4>
        </div>
    </div>
    <div class="row">
        {{-- Start SSL data  --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('SSL Data', 'setting')}}</h4>
                </div>
                <form action="{{ route('admin.livechatssldstore') }}" method="POST">
                    @csrf

                    <div class="card-body pt-2">
                        <div class="alert bg-warning-transparent text-dark mx-3 p-5" role="alert">
                            <h4 class="mb-2">{{ lang('How does it work?') }}
                            </h4>
                            <p class="mb-0 fs-14">
                                {{ lang('The "SSL Data Setting" section facilitates secure connection establishment between the application and the user’s server through SSL certificates. Admins need to input the SSL Certificate and SSL Key data obtained from their server into the designated text areas. Once the data is provided and saved, the section transitions into a disabled mode, indicating a successful connection setup. This ensures secure transmission of data between the application and the server, enhancing data protection and integrity.') }}
                            </p><br>
                            <p class="mb-0 fs-14">
                                <b>{{ lang('Note') }} : {{ lang('If you are familiar with what SSL Certificate and SSL Key are, then please reach out to your hosting provider.') }}</b>
                            </p>
                        </div>
                        <div class="d-flex">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('SSL Certificate')}} <span class="text-red">*</span></label>
                                    <textarea type="text" name="sslcertificate" rows="10" class="form-control @error('sslcertificate') is-invalid @enderror" placeholder="Enter SSL certificate" @if(setting('serverssldomainname') == $domainname) readonly @endif>{{setting('serversslcertificate')}}</textarea>
                                    @error('sslcertificate')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('SSL Key')}} <span class="text-red">*</span></label>
                                    <textarea type="text" name="sslkey" rows="10" class="form-control @error('sslkey') is-invalid @enderror" placeholder="Enter SSL certificate" @if(setting('serverssldomainname') == $domainname) readonly @endif>{{setting('serversslkey')}}</textarea>
                                    @error('sslkey')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 card-footer ">
                        <div class="form-group float-end ">
                        <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();" @if(setting('serverssldomainname') == $domainname) disabled=true; @endif>{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End SSL data  --}}
        {{-- LiveChat  --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('LiveChat', 'setting')}}</h4>
                </div>
                <form id="livechat_enable_form">
                    @csrf
                    <div class="card-body pt-2">
                        <div class="alert bg-warning-transparent text-dark m p-5" role="alert">
                            <h4 class="mb-2">{{ lang('Usage') }} :
                            </h4>
                            <p class="mb-0 fs-14">
                                {{ lang('Enter the desired port number received from your hosting provider in the input field, for example, "8443" or "8445." Ensure that the provided port number is available and open on your server. If the port is not open, contact your hosting provider to make it accessible on your server.') }}
                            </p><br>
                            <p class="mb-0 fs-14">
                                <b>{{ lang('Note') }} : {{ lang('Users utilizing the Linux OS on their server must ensure they have "SUDO" access for proper configuration.') }}</b>
                            </p>
                        </div>
                        <div class="switch_section my-0">
                            <div class="switch-toggle d-flex d-md-max-block mt-4 ms-0">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="liveChat_hidden" id="liveChat_hidden" class=" toggle-class onoffswitch2-checkbox" @if(setting('liveChatHidden') == 'false') checked="" @endif @if(setting('serverssldomainname') == null || setting('serverssldomainname') != $domainname) disabled=true; @endif>
                                    <label for="liveChat_hidden" class="toggle-class onoffswitch2-label" ></label>
                                </a>
                                <label class="form-label ps-3 ps-md-max-0">{{lang('Enable LiveChat', 'setting')}}</label>
                                <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('Enabling this setting will make the live chat function visible to customers, giving them the ability to begin a chat.', 'setting')}})</i></small>
                            </div>
                        </div>
                        <div class="form-group d-flex d-md-max-block">

                            <label  class="form-label">{{lang('Enter Port number for LiveChat', 'setting')}}</label>
                            <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('The liveChat connection will be established by the given port number.', 'setting')}})</i></small>
                        </div>
                        <input type="number" placeholder="{{lang('Port number for LiveChat')}}" name="liveChatPort" class="form-control @error('liveChatPort') is-invalid @enderror" value="{{ old('liveChatPort', setting('liveChatPort')) }}" @if(setting('serverssldomainname') == null || setting('serverssldomainname') != $domainname) readonly @endif>
                        <span class="text-danger" id="liveChatPortError"></span>
                    </div>
                    <div class="col-md-12 card-footer ">
                        <div class="form-group float-end ">
                        <button class="btn btn-secondary" id="livechatsubmitbtn" @if(setting('serverssldomainname') == null || setting('serverssldomainname') != $domainname) disabled=true; @endif>{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End LiveChat  --}}
        {{-- script Settings --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Live Chat Script', 'setting') }}</h4>
                </div>
                <div class="card-body pt-0">
                    <label><small><i>({{ lang('Copy the provided script tag link. Navigate to "App Setting->External Chat" and paste the copied script tag link into the "External Chat" text area, and enable the "External Chat Enable/Disable" switch. Administrators can seamlessly integrate the chat icon into the application’s landing page, enhancing customer engagement and support capabilities. And moreover, you can also use this below script tag link on any other website, but make sure that you paste this code snippet just before the </body> tag. ') }})</i></small></label>
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control liveChatScriptLink" name="mail_username" readonly
                                id="mail_username" value="<script src='{{ url('') }}/build/assets/plugins/livechat/liveChat.js' wsPort='{{ setting('liveChatPort') }}' domainName='{{ url('') }}' defer></script>" autocomplete="off">
                            <button class="btn btn-primary p-2 liveChatScriptLinkCopyBtn">
                                <i class="fa fa-clone" data-bs-toggle="tooltip" title=""
                                    data-bs-original-title="fa fa-clone" aria-label="fa fa-clone"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End script Settings --}}
        <!-- Customer File Setting-->
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <form method="POST" action="{{ route('admin.liveChatFileSettings') }}" enctype="multipart/form-data">
                    <div class="card-header border-0 align-items-baseline">
                        <h4 class="card-title">{{ lang('Customer Live Chat File Setting', 'setting') }}</h4>
                        <small class="ms-2 text-primary cursor-pointer notificationButton font-weight-bold d-none"
                            style="cursor: pointer;">{{ lang('Enable notification permission', 'setting') }}</small>
                        <div class="form-group mb-0 d-flex ms-auto">
                            <div class="switch_section my-0">
                                <div class="switch-toggle d-flex m-0 ms-0">

                                    <a class="onoffswitch2">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox"
                                                @if (setting('liveChatFileUpload') == '1') checked="" @endif
                                                name="liveChatFileUpload" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </a>

                                    <div class="ps-3">
                                        <label class="form-label">{{ lang('Customer File Upload') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group ">
                                    <label class="form-label">{{ lang('Maximum File Upload’s', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2" class="form-control @error('livechatMaxFileUpload') is-invalid @enderror"
                                            name="livechatMaxFileUpload" value="{{ setting('livechatMaxFileUpload') }}">
                                        @error('livechatMaxFileUpload')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group mb-0 {{ $errors->has('fileuploadmax') ? ' has-danger' : '' }}">
                                    <label
                                        class="form-label">{{ lang('Live Chat File Upload’s Maximum Size', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2" class="form-control @error('livechatFileUploadMax') is-invalid @enderror"
                                            name="livechatFileUploadMax" value="{{ setting('livechatFileUploadMax') }}">
                                        <span class="ms-2 font-weight-bold">{{ lang('MB', 'filesetting') }}</span>
                                        @error('livechatFileUploadMax')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group mb-0 {{ $errors->has('fileuploadtypes') ? ' has-danger' : '' }}">
                                    <label
                                        class="form-label">{{ lang('Live Chat File Types Allowed', 'filesetting') }}</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control" id="tags" data-role="tagsinput"
                                            name="livechatFileUploadTypes"
                                            value="{{ setting('livechatFileUploadTypes') }}">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Customer End File Setting-->

        <!-- Agent File Setting-->
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <form method="POST" action="{{ route('admin.liveChatFileSettings') }}" enctype="multipart/form-data">
                <div class="card-header border-0 align-items-baseline">
                    <h4 class="card-title">{{ lang('Operator Live Chat File Setting', 'setting') }}</h4>
                    <small class="ms-2 text-primary cursor-pointer notificationButton font-weight-bold d-none"
                        style="cursor: pointer;">{{ lang('Enable notification permission') }}</small>
                    <div class="form-group mb-0 d-flex ms-auto">
                        <div class="switch_section my-0">
                            <div class="switch-toggle d-flex m-0">

                                <a class="onoffswitch2">
                                    <label class="custom-switch m-0">
                                        <input type="checkbox" name="liveChatAgentFileUpload"
                                            @if (setting('liveChatAgentFileUpload') == '1') checked="" @endif
                                            class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </a>

                                <div class="ps-3">
                                    <label
                                        class="form-label">{{ lang('Operator File Upload', 'setting') }}</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group ">
                                    <label class="form-label">{{ lang('Maximum File Upload’s', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2" class="form-control @error('AgentlivechatMaxFileUpload') is-invalid @enderror"
                                            name="AgentlivechatMaxFileUpload"
                                            value="{{ setting('AgentlivechatMaxFileUpload') }}">
                                        @error('AgentlivechatMaxFileUpload')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group mb-0 {{ $errors->has('fileuploadmax') ? ' has-danger' : '' }}">
                                    <label
                                        class="form-label">{{ lang('Live Chat File Upload’s Maximum Size', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2" class="form-control @error('AgentlivechatFileUploadMax') is-invalid @enderror"
                                            name="AgentlivechatFileUploadMax"
                                            value="{{ setting('AgentlivechatFileUploadMax') }}">
                                        <span class="ms-2 font-weight-bold">{{ lang('MB', 'filesetting') }}</span>
                                        @error('AgentlivechatFileUploadMax')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group mb-0 {{ $errors->has('fileuploadtypes') ? ' has-danger' : '' }}">
                                    <label
                                        class="form-label">{{ lang('Live Chat File Types Allowed', 'filesetting') }}</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control" id="tags" data-role="tagsinput"
                                            name="AgentlivechatFileUploadTypes"
                                            value="{{ setting('AgentlivechatFileUploadTypes') }}">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Agent File Setting-->

        {{-- Live Chat Sounds Settings --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Live Chat Sounds', 'setting') }}</h4>
                    {{-- <a href='{{ route('admin.livechatNotificationsSonds') }}'
                                    class="btn ms-3 btn-danger">{{ lang('View Uploaded Sounds') }}</a> --}}
                </div>
                <form method="POST" action="{{ route('admin.liveChatNotificationsSound') }}"
                    enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf

                        <div class="">
                            <label class="form-label">{{ lang('Upload Your Sound') }} <small><i>({{ lang('You can add your custom sounds for the notifications when a new chat is created or a new reply is created.') }})</i></small></label>
                            <div class="d-flex">
                                <div class="input-group file-browse">
                                    <input class="form-control @error('uploadSound') is-invalid @enderror"
                                        name="uploadSound" type="file" autocomplete="off">
                                    @error('uploadSound')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <a href='{{ route('admin.livechatNotificationsSonds') }}'
                                    class="btn ms-3 btn-danger">{{ lang('View Sounds') }}</a>
                            </div>
                            <small class="text-muted"><i>{{lang('The file should be in mp3 format', 'filesetting')}}</i></small>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Live Chat Sounds Settings --}}

        {{-- LiveChat Notifications Settings --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <form method="POST" action="{{ route('admin.liveChatNotificationsSetting') }}"
                    enctype="multipart/form-data">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{ lang('Live Chat Notifications Setting', 'setting') }}</h4>
                        <div class="form-group mb-0 ms-auto">
                            <div class="switch_section my-0">
                                <div class="switch-toggle d-flex m-0">
                                    <a class="onoffswitch2">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" name="notificationsSounds"
                                                class="custom-switch-input"
                                                @if (setting('notificationsSounds') == '1') checked="" @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </a>
                                    <div class="ps-3">
                                        <label
                                            class="form-label">{{ lang('Live Chat Notifications', 'setting') }}</label>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('CUSTOMER_TICKET'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('CUSTOMER_TICKET') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf

                        <div class="form-group mt-2">
                            <label class="form-label">{{ lang('Notification Type', 'setting') }} <small><i>({{ lang('You can choose different sounds for incoming messages and customer replies, manage web notifications for chats by enabling or disabling them, and customize the notification sound to play once or in a continuous loop.') }})</i></small></label>
                            <select name="notificationType" id="notificationType"
                                class="form-control select2 select2-show-search" required>
                                <option value="Loop" @if (setting('notificationType') == 'Loop') selected="selected" @endif>
                                    {{ lang('Loop') }}</option>
                                <option value="Single" @if (setting('notificationType') == 'Single') selected="selected" @endif>
                                    {{ lang('Single') }}</option>
                            </select>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ lang('Notification type') }}</th>
                                        <th>{{ lang('Web notification') }}</th>
                                        <th>{{ lang('notification sound') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ lang('New message') }}</td>
                                        <td>
                                            <label class="custom-switch m-0">
                                                <input type="checkbox" name="newMessageWebNot"
                                                    @if (setting('newMessageWebNot') == '1') checked="" @endif
                                                    class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </td>
                                        <td class="notify-sound">
                                            <select name="newMessageSound"
                                                class="form-control NewMessageSound allSoundsList select2-show-search select2">
                                                @foreach ($sounds as $sound)
                                                    @if ($sound->name == setting('newMessageSound'))
                                                        <option value="{{ $sound->name }}" selected="selected">
                                                            {{ $sound->name }}</option>
                                                    @else
                                                        <option value="{{ $sound->name }}">{{ $sound->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ lang('New Chat request') }}</td>
                                        <td>
                                            <label class="custom-switch m-0">
                                                <input type="checkbox" name="newChatRequestWebNot"
                                                    @if (setting('newChatRequestWebNot') == '1') checked="" @endif
                                                    class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </td>
                                        <td class="notify-sound">
                                            <select name="newChatRequestSound"
                                                class="form-control NewMessageSound allSoundsList select2-show-search select2">
                                                @foreach ($sounds as $sound)
                                                    @if ($sound->name == setting('newChatRequestSound'))
                                                        <option value="{{ $sound->name }}" selected="selected">
                                                            {{ $sound->name }}</option>
                                                    @else
                                                        <option value="{{ $sound->name }}">{{ $sound->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End LiveChat Notifications Settings --}}

        {{-- Operators Notifications Settings --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <form method="POST" action="{{ route('admin.operatorsNotificationsSetting') }}"
                    enctype="multipart/form-data">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{ lang('Operators Notifications Setting', 'setting') }}</h4>

                        <div class="form-group mb-0 ms-auto">
                            <div class="switch_section my-0">
                                <div class="switch-toggle d-flex m-0">
                                    <a class="onoffswitch2">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" name="operatorsNotificationsSounds"
                                                class="custom-switch-input"
                                                @if (setting('operatorsNotificationsSounds') == '1') checked="" @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </a>
                                    <div class="ps-3">
                                        <label
                                            class="form-label">{{ lang('Operators Notifications', 'setting') }}</label>
                                    </div>
                                </div>
                            </div>
                            @if ($errors->has('CUSTOMER_TICKET'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('CUSTOMER_TICKET') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @csrf
                        <label for=""> <small><i>({{ lang('You can choose different sounds for incoming chats and operator responses, toggle web notifications on or off for different chats, and specify if the notification sound plays once or continuously.') }})</i></small></label>
                        <div class="table-responsive">
                            <table class="table table-bordered card-table table-vcenter text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ lang('Notification type') }}</th>
                                        <th>{{ lang('Web notification') }}</th>
                                        <th>{{ lang('notification sound') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ lang('Operator to Operator') }}</td>
                                        <td>
                                            <label class="custom-switch m-0">
                                                <input type="checkbox" name="operatorsAgentToAgentWebNot"
                                                    @if (setting('operatorsAgentToAgentWebNot') == '1') checked="" @endif
                                                    class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </td>
                                        <td class="notify-sound">
                                            <select name="operatorsAgentToAgentSound"
                                                class="form-control operatorsNewMessageSound allSoundsList select2-show-search select2">
                                                @foreach ($sounds as $sound)
                                                    @if ($sound->name == setting('operatorsAgentToAgentSound'))
                                                        <option value="{{ $sound->name }}" selected="selected">
                                                            {{ $sound->name }}</option>
                                                    @else
                                                        <option value="{{ $sound->name }}">{{ $sound->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ lang('Group Chat') }}</td>
                                        <td>
                                            <label class="custom-switch m-0">
                                                <input type="checkbox" name="operatorsGroupChatWebNot"
                                                    @if (setting('operatorsGroupChatWebNot') == '1') checked="" @endif
                                                    class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </td>
                                        <td class="notify-sound">
                                            <select name="operatorsGroupChatSound"
                                                class="form-control operatorsNewMessageSound allSoundsList select2-show-search select2">
                                                @foreach ($sounds as $sound)
                                                    @if ($sound->name == setting('operatorsGroupChatSound'))
                                                        <option value="{{ $sound->name }}" selected="selected">
                                                            {{ $sound->name }}</option>
                                                    @else
                                                        <option value="{{ $sound->name }}">{{ $sound->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Operators Notifications Settings --}}

        {{-- Chat Flow Settings --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Live Chat Flow Setting', 'setting') }}</h4><small class="ms-1"><i>({{ lang('If ’For a single unique user’ is selected previous livechat customers need not go through the livechat flow. If ’Every 24 hours’ is selected previous customers also have will have to through the livechat flow.') }})</i></small>
                </div>
                <form method="POST" action="{{ route('admin.liveChatFlowSettings') }}" enctype="multipart/form-data">
                    <div class="card-body pt-0">
                        <label for=""></label>
                        @csrf
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group mb-0">
                                <select name="liveChatFlowload" class="form-control select2-show-search select2">
                                    <option value="every-24-hours"
                                        @if (setting('liveChatFlowload') == 'every-24-hours') selected="selected" @endif>{{ lang('Every 24 hours') }}
                                    </option>
                                    <option value="for-a-single-unique-user"
                                        @if (setting('liveChatFlowload') == 'for-a-single-unique-user') selected="selected" @endif>{{ lang('For a single unique user') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Chat Flow Settings --}}

        {{-- LiveChat Icon Size --}}
        <div class="col-xl-6 col-lg-6">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Live Chat Icon Size', 'setting') }}</h4>

                </div>
                <small class="ms-5"><i>({{ lang('This setting determines the size of the chat icon displayed to customers: selecting "small" shows a small icon, while "large" shows a larger icon.') }})</i></small>
                <form action="{{ route('admin.liveChatIconSize') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="form-label">{{ lang('Select Size', 'setting') }}</label>
                            <select name="livechatIconSize" id="livechatIconSize"
                                class="form-control select2 select2-show-search" required>
                                <option value="small" @if (setting('livechatIconSize') == 'small') selected="selected" @endif>
                                    {{ lang('small') }}</option>
                                <option value="large" @if (setting('livechatIconSize') == 'large') selected="selected" @endif>
                                    {{ lang('large') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 card-footer ">
                        <div class="form-group float-end ">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End LiveChat Icon Size --}}

        <!-- LiveChat position -->
        <div class="col-xl-6 col-lg-6">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('LiveChat Position', 'setting') }}</h4> <small class="ms-1"><i>({{ lang('This setting determines the position of the chat icon on the browser.') }})</i></small>
                </div>
                <form action="{{ route('admin.liveChatPosition') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="form-label">{{ lang('Select LiveChat Position', 'setting') }}</label>
                            <select name="livechatPosition" id="livechatPosition"
                                class="form-control select2 select2-show-search" required>
                                <option value="right" @if (setting('livechatPosition') == 'right') selected="selected" @endif>
                                    {{ lang('right') }}</option>
                                <option value="left" @if (setting('livechatPosition') == 'left') selected="selected" @endif>
                                    {{ lang('left') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 card-footer ">
                        <div class="form-group float-end ">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End LiveChat position -->

        {{-- Live Chat Offline Setting --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Live Chat Offline/Online Setting', 'setting') }}</h4>
                </div>
                <form method="POST" action="{{ route('admin.liveChatOfflineSetting') }}" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <div class="col-xl-12 col-md-6 border-bottom">
                            <div class="form-group">
                                <label class="form-label">{{ lang('Online status Message') }} <span class="text-red">*</span> <small><i>({{ lang('The message in the input below will be sent to customers as a greeting when live chat operators are online.') }})</i></small></label>
                                <input type="text" class="form-control " placeholder="Subject"
                                    name="OnlineStatusMessage" value="{{ setting('OnlineStatusMessage') }}"
                                    id="OnlineStatusMessage" autocomplete="off">
                            </div>
                        </div>
                        <div class="switch_section my-0 ps-3">
                            <div class="switch-toggle d-flex d-md-max-block mt-4 ms-0">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="offlineDisplayLiveChat"
                                        @if (setting('offlineDisplayLiveChat') == '1') checked="" @endif id="contact"
                                        class=" toggle-class onoffswitch2-checkbox enablemenus" autocomplete="off">
                                    <label for="contact" class="toggle-class onoffswitch2-label"></label>
                                </a>
                                <label class="form-label ps-3 ps-md-max-0">{{ lang('Display LiveChat when offline') }} <small><i>({{ lang('If enabled, this setting allows customers to see the live chat icon even during offline hours.') }})</i></small></label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ lang('Offline status Message') }} <span class="text-red">*</span> <small><i>({{ lang('This message will be sent to customers as a greeting when they start a new chat outside business hours or on holidays.') }})</i></small></label>
                                <input type="text" class="form-control " placeholder="Subject"
                                    name="OfflineStatusMessage" value="{{ setting('OfflineStatusMessage') }}"
                                    id="OfflineStatusMessage" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ lang('Offline Message') }} <span class="text-red">*</span> <small><i>({{ lang('This message will be sent to customers as a reply to their message outside business hours or on holidays.') }})</i></small></label>
                                <textarea type="text" class="form-control " placeholder="Subject" name="OfflineMessage" value="Consent"
                                    id="OfflineMessage" autocomplete="off" rows="3">{{ setting('OfflineMessage') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Live Chat Offline Setting --}}


        <!-- Auto Solve Setting-->
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <form method="POST" action="{{ route('admin.livechatAutoSave') }}" enctype="multipart/form-data">
                    <div class="card-header border-0">
                        <h4 class="card-title">{{ lang('Auto Solve', 'setting') }}</h4>
                        <div class="form-group mb-0 d-flex ms-auto">
                            <div class="switch_section my-0">
                                <div class="switch-toggle d-flex m-0">

                                    <a class="onoffswitch2">
                                        <label class="custom-switch m-0">
                                            <input type="checkbox" name="enableAutoSlove"
                                                @if (setting('enableAutoSlove') == '1') checked="" @endif
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </a>

                                    <div class="ps-3">
                                        <label
                                            class="form-label">{{ lang('Enable Auto Solve', 'setting') }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <label><small class="ms-1"><i>({{ lang('If you activate this feature, a notification will be sent to the customer’s email informing them that a response has been sent when there is no reply from their end within the specified time in the first box, measured in minutes. The chat will be automatically closed if the customer fails to respond to the previous email within the time specified in the second input.') }})</i></small></label>
                        @csrf

                        <div class="row">

                            <div class="col-sm-12 col-md-6 col-xxl-4">
                                <div class="form-group ">
                                    <label
                                        class="form-label">{{ lang('No response from the customer email sender timer', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2"
                                            class="form-control @error('autoSloveEmailTimer') is-invalid @enderror"
                                            name="autoSloveEmailTimer" value="{{ setting('autoSloveEmailTimer') }}">
                                        <span class="ms-2 font-weight-bold">{{ lang('Minutes') }}</span>
                                    </div>
                                    @error('autoSloveEmailTimer')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6 col-xxl-4">
                                <div class="form-group mb-0 ">
                                    <label
                                        class="form-label">{{ lang('After sending the email, resolve the timer', 'filesetting') }}</label>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" maxlength="2"
                                            class="form-control @error('autoSloveCloseTimer') is-invalid @enderror"
                                            name="autoSloveCloseTimer" value="{{ setting('autoSloveCloseTimer') }}">
                                        <span class="ms-2 font-weight-bold">{{ lang('Minutes') }}</span>
                                    </div>
                                    @error('autoSloveCloseTimer')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Auto Solve Setting-->

        <!-- Auto Delete livechat Setting-->
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Auto Delete livechat', 'setting') }}</h4>
                </div>
                <form method="POST" action="{{ route('admin.livechatAutoDelete') }}" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf

                        <div class="">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group ">
                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex mt-4">
                                            <a class="onoffswitch2">
                                                <input type="checkbox" id="autodeletelivechat"
                                                    name="AUTO_DELETE_LIVECHAT_ENABLE" value="on"
                                                    class=" toggle-class onoffswitch2-checkbox"
                                                    @if (setting('AUTO_DELETE_LIVECHAT_ENABLE') == 'on') checked="" @endif>
                                                <label for="autodeletelivechat"
                                                    class="toggle-class onoffswitch2-label"></label>
                                            </a>
                                            <div class="ps-3">
                                                <label class="form-label">{{ lang('Auto Delete Livechat', 'setting') }} <small class="text-muted "><i>({{ lang('If enabled, chats older than the specified number of days will be automatically deleted.', 'setting') }})</i></small></label>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('AUTO_DELETE_LIVECHAT_ENABLE'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $errors->first('AUTO_DELETE_LIVECHAT_ENABLE') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div
                                    class="form-group d-flex d-md-max-block {{ $errors->has('AUTO_DELETE_LIVECHAT_IN_MONTHS') ? ' is-invalid' : '' }}">
                                    <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2"
                                        name="AUTO_DELETE_LIVECHAT_IN_MONTHS"
                                        value="{{ old('AUTO_DELETE_LIVECHAT_IN_MONTHS', setting('AUTO_DELETE_LIVECHAT_IN_MONTHS')) }}"
                                        min="0" oninput="validity.valid||(value='');">
                                    <label
                                        class="form-label mt-2 ms-2">{{ lang('Auto Delete Livechats In Days', 'setting') }}</label>
                                </div>
                                @if ($errors->has('AUTO_DELETE_LIVECHAT_IN_MONTHS'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('AUTO_DELETE_LIVECHAT_IN_MONTHS') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                    </div>
                    <div class="card-footer ">
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Auto Delete livechat Setting-->

        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Livechat Feedback', 'setting') }}</h4>
                </div>
                <form method="POST" action="{{ route('admin.livechatFeedbackDropdown') }}"
                    enctype="multipart/form-data">
                    <div class="card-body pt-0">

                        @csrf

                        @honeypot
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Feedback Qeustion') }} <span class="text-red">*</span> <small><i>({{ lang('This is the feedback question for a customer when they are close the chat it will show along with the rating.') }})</i></small></label>
                                    <input type="text" class="form-control @error('LivechatCustFeedbackQuestion') is-invalid @enderror" placeholder="Enter Feedback question"
                                        name="LivechatCustFeedbackQuestion" value="{{ setting('LivechatCustFeedbackQuestion') }}"
                                        id="LivechatCustFeedbackQuestion" autocomplete="off">

                                    @error('LivechatCustFeedbackQuestion')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Feedback Options', 'filesetting') }} <small><i>({{ lang('This will be visible to customers when they attempt to close the conversation in the feedback form. It displays a question and corresponding options for customers to provide their feedback.') }})</i></small></label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control " id="tags" data-role="tagsinput" name="livechatFeedbackDropdown" value="{{ setting('livechatFeedbackDropdown') }}">
                                        @if ($errors->has('fileuploadtypes'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fileuploadtypes') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Set a text when multiple operators are online', 'setting') }}</h4>
                </div>
                <form method="POST" action="{{ route('admin.LivechatCustWelcomeMsg') }}"
                    enctype="multipart/form-data">
                    <div class="card-body pt-0">

                        @csrf

                        @honeypot
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Multiple Operators Text') }} <span class="text-red">*</span> <small><i>({{ lang('When there are multiple livechat operators, customers in the livechat window will see the text provided in the input field below.') }})</i></small></label>
                                    <input type="text" class="form-control @error('LivechatCustWelcomeMsg') is-invalid @enderror" placeholder="Enter Feedback question"
                                        name="LivechatCustWelcomeMsg" value="{{ setting('LivechatCustWelcomeMsg') }}"
                                        id="LivechatCustWelcomeMsg" autocomplete="off">

                                    @error('LivechatCustWelcomeMsg')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL TAG js-->
    <script src="{{ asset('build/assets/plugins/taginput/bootstrap-tagsinput.js') }}?v=<?php echo time(); ?>"></script>

    <script>
        if (Notification.permission == 'denied' || Notification.permission == 'default') {
            document.querySelectorAll(".notificationButton").forEach((ele) => {
                ele.classList.remove("d-none")
                ele.onclick = () => {
                    Notification.requestPermission().then(function(permission) {
                        if (permission === "granted") {
                            toastr.success("Notification permission granted!")
                            document.querySelectorAll(".notificationButton").forEach((ele) => {
                                ele.classList.add("d-none")
                            })
                        }
                    });
                }
            })
        }

        // To copy in the Click Bord
        document.querySelector(".liveChatScriptLinkCopyBtn").onclick = () => {
            var copyText = document.querySelector(".liveChatScriptLink");

            if (navigator.clipboard) {
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                navigator.clipboard.writeText(copyText.value)
                    .then(() => {
                        console.log('Text successfully copied to clipboard');
                    })
                    .catch(err => {
                        console.error('Unable to copy text to clipboard', err);
                    });
            } else {
                console.warn('Clipboard API not supported, copying to clipboard may not work.');
            }
        }

        // For The Notifications sound

        let currentAudio;
        document.querySelectorAll(".allSoundsList").forEach((element) => {
            element.onchange = (ele) => {
                // Stop the current audio if it exists
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                }

                // Create a new audio element
                let audioElement = document.createElement('audio');
                audioElement.id = "audioPlayer";
                audioElement.innerHTML = `
                    <source src="{{ url('') }}/public/uploads/livechatsounds/${ele.target.value}">
                `;

                // Play the new audio
                audioElement.play();

                // Set the new audio as the current audio
                currentAudio = audioElement;
            };
        })

        // Notifications sound check Input Logic
        document.querySelector('[name="notificationsSounds"]').onclick = () => {
            if (document.querySelector('[name="notificationsSounds"]').checked) {
                document.querySelector("[name='newMessageWebNot']").disabled = false
                document.querySelector("[name='newChatRequestWebNot']").disabled = false
            } else {
                document.querySelector("[name='newMessageWebNot']").checked = false
                document.querySelector("[name='newChatRequestWebNot']").checked = false
                document.querySelector("[name='newMessageWebNot']").disabled = true
                document.querySelector("[name='newChatRequestWebNot']").disabled = true
            }
        }

        $(function() {
            (() => {
                // Csrf Field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('body').on('submit', '#livechat_enable_form', function(e) {
                    e.preventDefault();
                    var actionType = $('#livechatsubmitbtn').val();
                    var fewSeconds = 2;
                    $('#livechatsubmitbtn').html('Saving ... <i class="fa fa-spinner fa-spin"></i>');
                    $('#livechatsubmitbtn').prop('disabled', true);
                    setTimeout(function() {
                        $('#livechatsubmitbtn').prop('disabled', false);
                        $('#livechatsubmitbtn').html('Save Changes');
                    }, fewSeconds * 1000);
                    var formData = new FormData(this);
                    $.ajax({
                        type: 'POST',
                        url: '{{ url('admin/livechat/livechat-credentials') }}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {
                            $('#liveChatPortError').html('');
                            $('#livechat_enable_form').trigger("reset");
                            $('#livechatsubmitbtn').html('Save Changes');
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {
                            console.log('error data',data.responseJSON.error);
                            $('#liveChatPortError').html('');
                            $('#liveChatPortError').html(data?.responseJSON?.errors?.liveChatPort);
                            $('#livechatsubmitbtn').html('Save Changes');
                            if(data?.responseJSON?.error){
                                toastr.error(data.responseJSON.error);
                            }
                        }
                    });
                });
            })();
        })

    </script>
@endsection
