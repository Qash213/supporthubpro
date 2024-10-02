@extends('layouts.usermaster')
@section('styles')
    <link href="{{asset('build/assets/plugins/intl-tel-input/build/css/intlTelInput.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

    <!-- Section -->
    <section>
        <div class="bannerimg cover-image" data-bs-image-src="{{ asset('build/assets/images/photos/banner1.jpg') }}">
            <div class="header-text mb-0">
                <div class="container ">
                    <div class="row text-white">
                        <div class="col">
                            <h1 class="mb-0">{{ lang('Edit Profile') }}</h1>
                        </div>
                        <div class="col col-auto">
                            <ol class="breadcrumb text-center">
                                <li class="breadcrumb-item">
                                    <a href="#" class="text-white-50">{{ lang('Home', 'menu') }}</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    <a href="#" class="text-white">{{ lang('Edit Profile') }}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section -->

    <!--Profile Page -->
    <section>
        <div class="cover-image sptb">
            <div class="container ">
                <div class="row">
                    @include('includes.user.verticalmenu')

                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="card-title">{{ lang('Profile Details') }}</h4>
                            </div>
                            <form method="POST" action="{{ route('client.profilesetup') }}" enctype="multipart/form-data">
                                @csrf

                                @honeypot
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('First Name') }} <span
                                                        class="text-red">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('firstname') is-invalid @enderror"
                                                    name="firstname"
                                                    value="{{ old('firstname', Auth::guard('customer')->user()->firstname) }}"
                                                    readonly>
                                                @error('firstname')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ lang($message) }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Last Name') }} <span
                                                        class="text-red">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('lastname') is-invalid @enderror"
                                                    name="lastname"
                                                    value="{{ old('lastname', Auth::guard('customer')->user()->lastname) }}"
                                                    readonly>
                                                @error('lastname')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ lang($message) }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Username') }}</label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="username"
                                                    Value="{{ Auth::guard('customer')->user()->username }}" readonly>
                                                @error('username')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ lang($message) }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Email') }}
                                                    @if (setting('cust_email_update') == 'on')<a
                                                            class="action-btns1 ms-2 border-0"
                                                            data-id="{{ Auth::guard('customer')->user()->email }}"
                                                            id="emailchange" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Edit"><i
                                                                class="feather feather-edit text-primary"></i></a>
                                                    @endif
                                                </label>
                                                <input type="email" class="form-control"
                                                    Value="{{ Auth::guard('customer')->user()->email }}" readonly>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Mobile Number') }}
                                                    @if (setting('cust_mobile_update') == 'on' && setting('twilioenable') == 'on')
                                                    <a class="action-btns1 ms-2 border-0" id="mobileupdate" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Mobile Number Update"><i
                                                                class="feather feather-edit text-primary"></i></a>
                                                        @if (Auth::guard('customer')->user()->phoneVerified != 1)
                                                            <small class="text-danger">{{ lang('Please verify your mobile number') }}</small>
                                                        @endif
                                                    @endif
                                                </label>
                                                @if (setting('cust_mobile_update') == 'on' && setting('twilioenable') == 'on')
                                                    <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror {{ Auth::guard('customer')->user()->phoneVerified === 1 ? 'is-valid' : 'is-invalid' }}"
                                                    value="{{ old('phone', Auth::guard('customer')->user()->phone) }}"
                                                    name="phone" readonly>
                                                @else
                                                    <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone', Auth::guard('customer')->user()->phone) }}"
                                                    name="phone">
                                                @endif

                                                @error('phone')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ lang($message) }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Country') }}</label>
                                                <select name="country" class="form-control select2 select2-show-search">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->name }}"
                                                            {{ $country->name == Auth::guard('customer')->user()->country ? 'selected' : '' }}>
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ lang('Timezone') }}</label>
                                                <select name="timezone" class="form-control select2 select2-show-search">
                                                    @foreach ($timezones as $group => $timezoness)
                                                        <option value="{{ $timezoness->timezone }}"
                                                            {{ $timezoness->timezone == Auth::guard('customer')->user()->timezone ? 'selected' : '' }}>
                                                            {{ $timezoness->timezone }} {{ $timezoness->utc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @if (setting('PROFILE_USER_ENABLE') == 'yes')

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ lang('Upload Image') }}</label>
                                                    <div class="input-group file-browser">
                                                        <input class="form-control @error('image') is-invalid @enderror"
                                                            name="image" type="file"
                                                            accept="image/png, image/jpeg,image/jpg">
                                                        @error('image')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ lang($message) }}</strong>
                                                            </span>
                                                        @enderror

                                                    </div>
                                                    <small
                                                        class="text-muted"><i>{{ lang('The file size should not be more than 5MB', 'filesetting') }}</i></small>
                                                </div>
                                                @if (Auth::guard('customer')->user()->image != null)
                                                    <div
                                                        class="file-image-1 removesprukoi{{ Auth::guard('customer')->user()->id }}">
                                                        <div class="product-image custom-ul">
                                                            <a href="#">
                                                                <img src="{{ route('getprofile.url', ['imagePath' => Auth::guard('customer')->user()->image, 'storage_disk' => Auth::guard('customer')->user()->storage_disk ?? 'public']) }}"
                                                                    class="br-5"
                                                                    alt="{{ Auth::guard('customer')->user()->image }}">

                                                            </a>
                                                            <ul class="icons">
                                                                <li><a href="javascript:(0);"
                                                                        class="bg-danger delete-image"
                                                                        data-id="{{ Auth::guard('customer')->user()->id }}"><i
                                                                            class="fe fe-trash"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif


                                        @if ($customfield->isNotEmpty())
                                            <h4>{{ lang('Customfields') }}</h4>
                                            @foreach ($customfield as $customfields)
                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ $customfields->fieldnames }}</label>

                                                        @if ($customfields->fieldtypes == 'text' || $customfields->fieldtypes == 'email')
                                                            <input type="{{ $customfields->fieldtypes }}"
                                                                class="form-control"
                                                                name="customfield_{{ $customfields->fieldnames }}"
                                                                Value="{{ $customfields->privacymode == '1' ? decrypt($customfields->values) : $customfields->values }}"
                                                                readonly>
                                                        @endif

                                                        @if ($customfields->fieldtypes == 'textarea')
                                                            <textarea name="customfield_{{ $customfields->fieldnames }}" class="form-control" cols="30" rows="5"
                                                                disabled>{{ $customfields->privacymode == '1' ? decrypt($customfields->values) : $customfields->values }}</textarea>
                                                        @endif

                                                        @if ($customfields->fieldtypes == 'checkbox')
                                                            @php
                                                                $coptions = explode(',', $customfields->fieldoptions);
                                                                if ($customfields->privacymode == '1') {
                                                                    $valueoption = explode(
                                                                        ',',
                                                                        decrypt($customfields->values),
                                                                    );
                                                                } else {
                                                                    $valueoption = explode(',', $customfields->values);
                                                                }
                                                            @endphp
                                                            @foreach ($coptions as $key => $coption)
                                                                <label
                                                                    class="custom-control form-checkbox d-inline-block me-3">
                                                                    <input type="{{ $customfields->fieldtypes }}"
                                                                        class="custom-control-input"
                                                                        name="customfield_{{ $customfields->fieldnames }}[]"
                                                                        value="{{ $coption }}"
                                                                        {{ in_array($coption, $valueoption) ? 'checked' : '' }}
                                                                        disabled>

                                                                    <span
                                                                        class="custom-control-label">{{ $coption }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endif

                                                        @if ($customfields->fieldtypes == 'select')
                                                            <select name="customfield_{{ $customfields->fieldnames }}"
                                                                class="form-control select2 select2-show-search"
                                                                data-placeholder="{{ lang('Select') }}" disabled>
                                                                @php
                                                                    $seoptions = explode(
                                                                        ',',
                                                                        $customfields->fieldoptions,
                                                                    );

                                                                    if ($customfields->privacymode == '1') {
                                                                        $selectedvalues = explode(
                                                                            ',',
                                                                            decrypt($customfields->values),
                                                                        );
                                                                    } else {
                                                                        $selectedvalues = explode(
                                                                            ',',
                                                                            $customfields->values,
                                                                        );
                                                                    }
                                                                @endphp
                                                                <option></option>
                                                                @foreach ($seoptions as $seoption)
                                                                    <option value="{{ $seoption }}"
                                                                        {{ in_array($seoption, $selectedvalues) ? 'selected' : '' }}>
                                                                        {{ $seoption }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif

                                                        @if ($customfields->fieldtypes == 'radio')
                                                            @php
                                                                $roptions = explode(',', $customfields->fieldoptions);

                                                                if ($customfields->privacymode == '1') {
                                                                    $radiovalues = explode(
                                                                        ',',
                                                                        decrypt($customfields->values),
                                                                    );
                                                                } else {
                                                                    $radiovalues = explode(',', $customfields->values);
                                                                }
                                                            @endphp
                                                            @foreach ($roptions as $roption)
                                                                <label
                                                                    class="custom-control form-radio d-inline-block me-3">
                                                                    <input type="{{ $customfields->fieldtypes }}"
                                                                        class="custom-control-input"
                                                                        name="customfield_{{ $customfields->fieldnames }}"
                                                                        value="{{ $roption }}"
                                                                        {{ in_array($roption, $radiovalues) ? 'checked' : '' }}
                                                                        disabled>
                                                                    <span
                                                                        class="custom-control-label">{{ $roption }}</span>
                                                                </label>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-12 card-footer ">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary float-end mb-3"
                                        onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{ lang('Save Changes') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if (setting('SPRUKOADMIN_C') == 'on')

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">{{ lang('Personal setting') }}</div>
                                </div>
                                <div class="card-body">

                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex mt-4">
                                            <a class="onoffswitch2">
                                                <input type="checkbox" data-id="{{ Auth::guard('customer')->id() }}"
                                                    name="darkmode" id="darkmode"
                                                    class=" toggle-class onoffswitch2-checkbox sprukolayouts"
                                                    value="off"
                                                    @if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null) @if (Auth::guard('customer')->user()->custsetting->darkmode == '1') checked="" @endif
                                                    @endif>
                                                <label for="darkmode" class="toggle-class onoffswitch2-label"></label>
                                            </a>
                                            <label class="form-label ps-3">{{ lang('Switch to Dark-Mode') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                        @if (setting('twilioenable') == 'on')

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">{{ lang('Ticket Updates via Mobile SMS') }}</div>
                                </div>
                                <div class="card-body">

                                    <div class="switch_section">
                                        <div class="switch-toggle d-flex mt-4">
                                            <a class="onoffswitch2">
                                                <input type="checkbox" data-id="{{ Auth::guard('customer')->id() }}"
                                                    data-mobileverified="{{ Auth::guard('customer')->user()->phoneVerified }}"
                                                    name="custsmsenable" id="custsmsenable"
                                                    class=" toggle-class onoffswitch2-checkbox custsmsenable"
                                                    value="off"
                                                    @if (Auth::guard('customer')->check() && Auth::guard('customer')->user() != null) @if (Auth::guard('customer')->user()->phonesmsenable == '1') checked="" @endif
                                                    @endif>
                                                <label for="custsmsenable" class="toggle-class onoffswitch2-label"></label>
                                            </a>
                                            <label class="form-label ps-3">{{ lang('Recieve Ticket alerts') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                        @if (setting('Customer_google_two_fact') == 'on' || setting('Customer_email_two_fact') == 'on')
                            <div class="card">
                                <div class="card-header border-bottom-0 pb-0">
                                    <div class="card-title">{{ lang('Two Factor Authentication') }}</div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12">
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="d-sm-flex d-block gap-3 align-items-center">
                                                        @if (setting('Customer_google_two_fact') == 'on')
                                                            <div class="switch_section px-0">
                                                                <div class="switch-toggle d-flex align-items-center">
                                                                    <a class="onoffswitch2">
                                                                        <input type="checkbox"
                                                                            data-id="{{ Auth::guard('customer')->id() }}"
                                                                            name="twofactor" id="twofactor"
                                                                            class="toggle-class onoffswitch2-checkbox sprukotwofact"
                                                                            autocomplete="off"
                                                                            @if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null) @if (Auth::guard('customer')->user()->custsetting->twofactorauth == 'googletwofact') checked="" @endif
                                                                            @endif>
                                                                        <label for="twofactor"
                                                                            class="toggle-class onoffswitch2-label mb-0 "></label>
                                                                    </a>
                                                                    <label
                                                                        class="form-label ps-3 mb-0">{{ lang('Use Google Authenticator') }}</label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if (setting('Customer_email_two_fact') == 'on')
                                                            <div class="switch_section px-0">
                                                                <div class="switch-toggle d-flex align-items-center">
                                                                    <a class="onoffswitch2">
                                                                        <input type="checkbox"
                                                                            data-id="{{ Auth::guard('customer')->id() }}"
                                                                            name="emailtwofactor" id="emailtwofactor"
                                                                            class="toggle-class onoffswitch2-checkbox sprukoemailtwofactor"
                                                                            autocomplete="off"
                                                                            @if (Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null) @if (Auth::guard('customer')->user()->custsetting->twofactorauth == 'emailtwofact') checked="" @endif
                                                                            @endif>
                                                                        <label for="emailtwofactor"
                                                                            class="toggle-class onoffswitch2-label mb-0"></label>
                                                                    </a>
                                                                    <label
                                                                        class="form-label ps-3 mb-0">{{ lang('Use Email OTP') }}</label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="emptwofactorapp"></div>
                                            @if (setting('Customer_google_two_fact') == 'on' &&
                                                    Auth::guard('customer')->user()->custsetting != null &&
                                                    Auth::guard('customer')->user()->custsetting->twofactorauth == 'googletwofact')
                                                <div class="mt-5" id="configured">
                                                    <div class="alert bg-success-transparent text-dark " role="alert">
                                                        <h5 class="mb-4">
                                                            {{ lang('Google two factor authentication is already
                                                                                                                        configured.') }}
                                                        </h5>
                                                        <button type="button"
                                                            class="btn btn-primary reconfig">{{ lang('Reconfigure') }}</button>
                                                        <button
                                                            class="btn btn-danger removetf">{{ lang('Remove') }}</button>

                                                    </div>
                                                </div>
                                            @endif

                                            @if (setting('Customer_email_two_fact') == 'on' &&
                                                    Auth::guard('customer')->user()->custsetting != null &&
                                                    Auth::guard('customer')->user()->custsetting->twofactorauth == 'emailtwofact')
                                                <div class="mt-5 " id="emailtwofac">
                                                    <div class="alert bg-warning-transparent text-dark p-5"
                                                        role="alert">
                                                        <h4 class="mb-2">
                                                            {{ lang('How does email otp authenticator work?') }}</h4>
                                                        <p class="mb-0">
                                                            {{ lang('Two-Factor Authentication (2FA) is an option that
                                                                                                                        provides an extra
                                                                                                                        layer of security to your account in addition to your email and
                                                                                                                        password. When Two-Factor Authentication is enabled, your
                                                                                                                        account cannot be accessed
                                                                                                                        by anyone, even if they have your password.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @include('user.auth.passwords.changepassword')

                        @if (setting('cust_profile_delete_enable') == 'on')
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">{{ lang('Delete Account') }}</div>
                                </div>
                                <div class="card-body">
                                    <p>{{ lang('Once you delete your account, you can not access your account with the same credentials. You need to re-register your account.') }}
                                    </p>
                                    <label class="custom-control form-checkbox">
                                        <input type="checkbox" class="custom-control-input " value="agreed"
                                            name="agree_terms" id="sprukocheck">
                                        <span class="custom-control-label">{{ lang('I agree with') }} <a
                                                href="{{ setting('terms_url') }}" class="text-primary" target="_blank">
                                                {{ lang('Terms of services') }}</a> </span>
                                    </label>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-danger my-1" data-id="{{ Auth::guard('customer')->id() }}"
                                        id="accountdelete">{{ lang('Delete Account') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Profile Page -->

@endsection

@section('modal')
    @include('user.profile.emailchangemodal')
    @include('admin.profile.2fapasswordmodal')
    @include('user.profile.mobileupdate')
@endsection

@section('scripts')
    <!-- INTERNAL Vertical-scroll js-->
    <script src="{{ asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/intl-tel-input/build/js/intlTelInput.min.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Index js-->
    @vite(['resources/assets/js/support/support-sidemenu.js'])
    @vite(['resources/assets/js/select2.js'])


    <script type="text/javascript">
        $(function() {
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

                // Email id change
                $('body').on('click', '#emailchange', function() {
                    var _id = $(this).data("id");

                    $("#emailid").val(_id);
                    $("#emailmodal").modal('show');
                });


                $('body').on('submit', '#emailchangestore', function(e) {
                    e.preventDefault();

                    var fewSeconds = 2;
                    $('#btnsave').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#btnsave').prop('disabled', true);
                    setTimeout(function() {
                        $('#btnsave').prop('disabled', false);
                    }, fewSeconds * 1000);
                    var formData = $(this).serialize();


                    $.ajax({
                        type: "POST",
                        url: SITEURL + "/customer/customeremailchange",
                        data: formData,
                        success: function(data) {

                            if (data?.message == 'wrongpassword') {
                                $('#passwordError').html(data.error);
                                $('#btnsave').html('Submit');
                            }
                            if (data?.message == 'linksend') {
                                // location.reload();
                                toastr.success(data.success);
                                $("#emailmodal").modal('hide');
                            }
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                // Profile Account Delete
                $('body').on('click', '#accountdelete', function() {
                    var _id = $(this).data("id");

                    swal({
                            title: `{{ lang('Warning! You are about to delete your account.') }}`,
                            text: "{{ lang('This action can not be undo. This will permanently delete your account') }}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    type: "post",
                                    url: SITEURL + "/customer/deleteaccount/" + _id,
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



                // Switch to dark mode js
                $('.sprukolayouts').on('change', function() {
                    var dark = $('#darkmode').prop('checked') == true ? '1' : '';
                    var cust_id = $(this).data('id');

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ url('/customer/custsettings') }}',
                        data: {
                            'dark': dark,
                            'cust_id': cust_id
                        },
                        success: function(data) {
                            location.reload();
                            toastr.success('{{ lang('Updated successfully', 'alerts') }}');
                        }
                    });
                });

                // Switch to dark mode js
                $('.custsmsenable').on('change', function() {
                    var custsmsenabledata = $('#custsmsenable').prop('checked') == true ? '1' : '';
                    var cust_id = $(this).data('id');
                    var mobilenumberverify = $(this).data('mobileverified');

                    if(mobilenumberverify == 0){
                        toastr.error('{{ lang('First you need verify your mobile number in the profile details.', 'alerts') }}');
                        location.reload();
                    }

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ url('/customer/custtwiliosetting') }}',
                        data: {
                            'custsmsenabledata': custsmsenabledata,
                            'cust_id': cust_id
                        },
                        success: function(data) {
                            location.reload();
                            toastr.success('{{ lang('Updated successfully', 'alerts') }}');
                        }
                    });
                });


                // Two factor auth start
                var what2fastatus;
                $('body').on('submit', '#2fapasswordverify', function(e) {
                    e.preventDefault();

                    var fewSeconds = 2;
                    $('#2fapassbtnsave').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#2fapassbtnsave').prop('disabled', true);
                    setTimeout(function() {
                        $('#2fapassbtnsave').prop('disabled', false);
                    }, fewSeconds * 1000);
                    var formData = $(this).serialize();

                    if (what2fastatus == 'emailtwofactor') {
                        if (document.querySelector("#twofactor") != null && document.querySelector(
                                "#twofactor").checked != false) {
                            document.querySelector("#twofactor").checked = false;
                        }
                        if (document.querySelector("#configured")) {
                            document.querySelector("#configured").remove();
                        }
                        if (document.querySelector("#TwoFactorAuthentication")) {
                            document.querySelector("#TwoFactorAuthentication").remove();
                        }
                        if (document.querySelector("#emailtwofac")) {
                            document.querySelector("#emailtwofac").remove();
                        }

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('emailtwofactor.setting') }}',
                            data: formData,
                            success: function(data) {
                                if (data?.message == 'wrongpassword') {
                                    toastr.error(data.error);
                                    location.reload();
                                } else {
                                    if (data.disabled) {
                                        if (document.querySelector(
                                                "#TwoFactorAuthentication")) {
                                            document.querySelector(
                                                "#TwoFactorAuthentication").remove();
                                        }

                                        toastr.success(data.success);
                                        location.reload();
                                    } else {

                                        let element = document.querySelector(
                                                "#emptwofactorapp").parentNode
                                            .parentNode.parentNode
                                        let nodeElement = document.createElement("div");
                                        nodeElement.className = "col-md-12 mt-4 "
                                        nodeElement.id = "TwoFactorAuthentication"
                                        nodeElement.innerHTML = `<div class="mt-5">
                                                <div class="alert bg-warning-transparent text-dark p-5" role="alert">
                                                    <h4 class="mb-2">{{ lang('How does email otp authenticator works ?') }}</h4>
                                                    <p class="mb-0">{{ lang('Two-Factor Authentication (2FA) is an option that provides an extra
                                                                                                            layer of security to your account in addition to your email and
                                                                                                            password. When Two-Factor Authentication is enabled, your account cannot be accessed
                                                                                                            by anyone, even if they have stolen your password.') }}</p>
                                                </div>
                                            </div>`
                                        element.appendChild(nodeElement)

                                        toastr.success(data.success);
                                        location.reload();
                                    }
                                }
                            }
                        });
                    }

                    if (what2fastatus == 'googletwofactor') {
                        $("#2fapassmodal").modal('hide');
                        if (document.querySelector("#configured")) {
                            document.querySelector("#configured").remove();
                        }

                        if (document.querySelector("#emailtwofactor") != null && document.querySelector(
                                "#emailtwofactor").checked != false) {
                            document.querySelector("#emailtwofactor").checked = false;
                        }
                        if (document.querySelector("#TwoFactorAuthentication")) {
                            document.querySelector("#TwoFactorAuthentication").remove();
                        }
                        if (document.querySelector("#emailtwofac")) {
                            document.querySelector("#emailtwofac").remove();
                        }

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('google2faqr.login') }}',
                            data: formData,
                            success: function(data) {
                                if (data?.message == 'wrongpassword') {
                                    toastr.error(data.error);
                                    location.reload();
                                } else {
                                    if (data?.workprogress == 'workingmode') {
                                        if (document.querySelector("#emailtwofac")) {
                                            document.querySelector("#emailtwofac").remove();
                                        }
                                        if (document.querySelector("#emailtwofactor")) {
                                            document.querySelector("#emailtwofactor")
                                                .checked = false;
                                        }
                                        let element = document.querySelector(
                                                "#emptwofactorapp").parentNode
                                            .parentNode.parentNode
                                        let nodeElement = document.createElement("div");
                                        nodeElement.className = "col-md-12 mt-4 "
                                        nodeElement.id = "TwoFactorAuthentication"
                                        nodeElement.innerHTML = `<div class="d-flex align-items-start mt-6 flex-wrap">
                                            <div class="qr-code">
                                                ${data.QR_Image}
                                            </div>
                                            <div>
                                                <h5 class="fw-semibold">Set up Google Authenticator</h5>
                                                <p class="mb-0">Set up your two factor authentication by scanning the QR code.
                                                    Alternatively, you can use the code </p>
                                                <div class="mb-4 mt-2">
                                                    <span class="badge fs-12 bg-light text-default p-2">${data.secret}</span>
                                                </div>
                                                <div class="fs-13">You must set up your Google Authenticator app before continuing.
                                                    You will be unable to login otherwise</div>
                                                <div class="mb-3 fs-13">Please enter the <span class="font-weight-bold">OTP</span>
                                                    generated on your Authenticator App.
                                                    Ensure you submit the current one because it refreshes every <span class="text-danger">30 seconds<sup>*</sup></span>
                                                </div>
                                                <label for="one_time_password" class="control-label text-success font-weight-semibold mb-1">One Time
                                                    Password</label>
                                                <div class="d-flex align-item-end gap-3">
                                                    <div class="w-50">
                                                        <input id="secret_key_value" type="hidden" name="secret_key_value" value="${data.secret}">
                                                        <input id="one_time_password" type="number" class="form-control" Placeholder="Enter only numbers" name="one_time_password" required required autofocus autocomplete="off" >
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary" id="otpverify">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`
                                        element.appendChild(nodeElement)

                                        var cust_id = {{ Auth::guard('customer')->id() }};

                                        document.querySelector("#otpverify")
                                            .addEventListener("click", () => {
                                                var otp = document.getElementById(
                                                    'one_time_password').value;
                                                var secret_key = document
                                                    .getElementById("secret_key_value")
                                                    .value;

                                                if (otp == '') {
                                                    toastr.error(
                                                        '{{ lang('Please enter otp to verify it.', 'alerts') }}'
                                                        );
                                                } else {
                                                    $.ajax({
                                                        type: "POST",
                                                        dataType: 'json',
                                                        url: '{{ route('google2fa.otpverify') }}',
                                                        data: {
                                                            'otp': otp,
                                                            'id': cust_id,
                                                            'secret_key_value': secret_key
                                                        },
                                                        success: function(
                                                            response) {
                                                            if (response ==
                                                                0) {
                                                                toastr
                                                                    .error(
                                                                        '{{ lang('Invalid otp.', 'alerts') }}'
                                                                    );
                                                            }
                                                            if (response ==
                                                                1) {
                                                                if (document
                                                                    .querySelector(
                                                                        "#TwoFactorAuthentication"
                                                                    )) {
                                                                    document
                                                                        .querySelector(
                                                                            "#TwoFactorAuthentication"
                                                                        )
                                                                        .remove();
                                                                }
                                                                let element =
                                                                    document
                                                                    .querySelector(
                                                                        "#emptwofactorapp"
                                                                        )
                                                                    .parentNode
                                                                    .parentNode
                                                                    .parentNode
                                                                let nodeElement =
                                                                    document
                                                                    .createElement(
                                                                        "div"
                                                                        );
                                                                nodeElement
                                                                    .className =
                                                                    "col-md-12 mt-4 "
                                                                nodeElement
                                                                    .id =
                                                                    "TwoFactorAuthentication"
                                                                nodeElement
                                                                    .innerHTML = `<div class="mt-5" id="configured">
                                                                                    <div class="alert bg-success-transparent text-dark " role="alert">
                                                                                    <h5 class="mb-4">Google two factor authentication is already configured. </h5>
                                                                                    <button type="button" class="btn btn-primary reconfig">Reconfigure</button>
                                                                                    <button class="btn btn-danger remove">Remove</button>

                                                                                    </div>
                                                                                    </div>`
                                                                element
                                                                    .appendChild(
                                                                        nodeElement
                                                                        )

                                                                document
                                                                    .querySelector(
                                                                        ".reconfig"
                                                                        )
                                                                    .onclick =
                                                                    () => {
                                                                        document
                                                                            .querySelector(
                                                                                "#configured"
                                                                                )
                                                                            .remove();
                                                                    }

                                                                location
                                                                    .reload();
                                                                toastr
                                                                    .success(
                                                                        '{{ lang('GoogleTwo factor authentication activated.', 'alerts') }}'
                                                                    );

                                                            }

                                                        },
                                                        error: function(data) {
                                                            console.log(
                                                                'Error:',
                                                                data);
                                                        }
                                                    });
                                                }


                                            })
                                        toastr.success(
                                            '{{ lang('Updated successfully', 'alerts') }}'
                                            );
                                    } else {
                                        if (document.querySelector(
                                                "#TwoFactorAuthentication")) {
                                            document.querySelector(
                                                "#TwoFactorAuthentication").remove()

                                        }

                                        location.reload();
                                        toastr.success(data.success);
                                    }
                                }

                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }

                    if (what2fastatus == 'google2faremove') {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('google2faqr.login') }}',
                            data: formData,
                            success: function(data) {
                                if (data?.message == 'wrongpassword') {
                                    toastr.error(data.error);
                                    location.reload();
                                } else {
                                    // location.reload();
                                    if (document.querySelector("#twofactor") != null &&
                                        document.querySelector("#twofactor").checked !=
                                        false) {
                                        document.querySelector("#twofactor").checked =
                                        false;
                                    }
                                    if (document.querySelector("#configured")) {
                                        document.querySelector("#configured").remove();
                                    }
                                    toastr.success(data.success);
                                    location.reload();
                                }
                            }
                        });
                    }
                });

                // remove google two factor authentication
                $('.removetf').on('click', function() {

                    $('#2fapasswordverify').trigger("reset");
                    what2fastatus = 'google2faremove';
                    $("#emailcheckstatus").val('');
                    $("#cust_id").val('{{ Auth::guard('customer')->id() }}');
                    $("#2fapassmodal").modal('show');
                });
                // End remove google two factor authentication

                // Email two factor authentication
                $('.sprukoemailtwofactor').on('change', function() {
                    var authgoogle2fastaus =
                        '{{ Auth::guard('customer')->user()->custsetting->twofactorauth }}';
                    if (authgoogle2fastaus == 'googletwofact') {
                        toastr.error(
                            'first of all you need to disable the google two factor authentication');
                        location.reload();
                    } else {
                        $('#2fapasswordverify').trigger("reset");
                        what2fastatus = 'emailtwofactor';
                        $("#emailcheckstatus").val($('#emailtwofactor').prop('checked') == true ? '1' :
                            '');
                        $("#cust_id").val($(this).data('id'));
                        $("#2fapassmodal").modal('show');
                    }
                });
                // End Email two factor authentication


                // Google two factor authentication
                $('.sprukotwofact, .reconfig').on('click', function() {

                    var authemail2fastaus =
                        '{{ Auth::guard('customer')->user()->custsetting->twofactorauth }}';
                    if (authemail2fastaus == 'emailtwofact') {
                        toastr.error(
                            'first of all you need to disable the email two factor authentication');
                        location.reload();
                    } else {
                        $('#2fapasswordverify').trigger("reset");
                        what2fastatus = 'googletwofactor';
                        $("#emailcheckstatus").val($('#twofactor').prop('checked') == true ? '1' : '');
                        $("#cust_id").val('{{ Auth::guard('customer')->id() }}');
                        $("#2fapassmodal").modal('show');
                    }

                });
                // Google two factor authentication
                // Two factor auth end

                //Delete Image
                $('body').on('click', '.delete-image', function() {
                    var _id = $(this).data("id");

                    swal({
                            title: `{{ lang('Are you sure you want to remove the profile image?', 'alerts') }}`,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    type: "delete",
                                    url: SITEURL + "/customer/image/remove/" + _id,
                                    success: function(data) {
                                        toastr.success(data.success);
                                        location.reload();
                                    },
                                    error: function(data) {
                                        console.log('Error:', data);
                                    }
                                });
                            }
                        });
                });

            })(jQuery);

            // If no tick in check box in disable in the delete button
            var checker = document.getElementById('sprukocheck');
            var sendbtn = document.getElementById('accountdelete');
            if (sendbtn) {

                if (!this.checked) {
                    sendbtn.style.pointerEvents = "auto";
                    sendbtn.style.cursor = "not-allowed";
                } else {
                    sendbtn.style.cursor = "pointer";
                }
                sendbtn.disabled = !this.checked;

                checker.onchange = function() {

                    sendbtn.disabled = !this.checked;
                    if (!this.checked) {
                        sendbtn.style.pointerEvents = "auto";
                        sendbtn.style.cursor = "not-allowed";
                    } else {
                        sendbtn.style.cursor = "pointer";
                    }
                }
            };
        })

        @if (setting('twilioenable') == 'on')
                let locationDetails;

            // const phoneElement = document.querySelector("#mobileNumber");

            // if (document.querySelector('.showMobileEdit')) {
            //     document.querySelector('.showMobileEdit').addEventListener('click', () => {
            //         phoneElement.disabled = false;
            //         document.querySelector('#successHide').classList.remove('d-none');
            //         document.querySelector('#successHide').classList.add('d-flex');
            //     })
            // }

            // const locationDetails = window.intlTelInput(phoneElement, {
            //     showSelectedDialCode: true,
            //     initialCountry: @json(Auth::guard('customer')->user()->countryCode) ? @json(Auth::guard('customer')->user()->countryCode) : 'auto',
            //     geoIpLookup: async callback => {
            //         await fetch("https://ipapi.co/json")
            //             .then(res => res.json())
            //             .then(data => callback(data.country_code))
            //             .catch(() => callback("us"));
            //     },
            //     utilsScript: "{{ asset('build/assets/plugins/intl-tel-input/build/js/utils.js') }}"
            // });
            // phoneElement.addEventListener('focusin', () => {
            //     document.querySelector("#profileSave").disabled = true;
            // });

            // setTimeout(() => {
            //     document.querySelector('.iti.iti--allow-dropdown.iti--show-selected-dial-code').style.width = '100%';
            // }, 50);

            // new bootstrap.Modal(document.querySelector('#custEmailEditModal'))
            // let mobileupdatemodal = new bootstrap.Modal(document.querySelector('#mobileupdatemodal'));
            document.querySelector('#mobileupdate').addEventListener('click',()=>{
                const phoneElement = document.querySelector("#mobileNumber");

                    if (document.querySelector('.showMobileEdit')) {
                        document.querySelector('.showMobileEdit').addEventListener('click', () => {
                            phoneElement.disabled = false;
                            document.querySelector('#successHide').classList.remove('d-none');
                            document.querySelector('#successHide').classList.add('d-flex');
                        })
                    }

                    locationDetails = window.intlTelInput(phoneElement, {
                        showSelectedDialCode: true,
                        initialCountry: @json(Auth::guard('customer')->user()->countryCode) ? @json(Auth::guard('customer')->user()->countryCode) : 'auto',
                        geoIpLookup: async callback => {
                            await fetch("https://ipapi.co/json")
                                .then(res => res.json())
                                .then(data => callback(data.country_code))
                                .catch(() => callback("us"));
                        },
                        utilsScript: "{{ asset('build/assets/plugins/intl-tel-input/build/js/utils.js') }}"
                    });

                    setTimeout(() => {
                        document.querySelector('.iti.iti--allow-dropdown.iti--show-selected-dial-code').style.width = '100%';
                    }, 50);

                    new bootstrap.Modal(document.querySelector('#mobileupdatemodal')).show();
            });

            let sendOTP = (ele) => {
                ele.classList.add('disabled');
                ele.innerHTML = '{{ lang('loading..') }}';
                var number = locationDetails.getNumber();
                document.querySelector('#alreadyExists').innerHTML = '';
                // document.querySelector('#invalidotp').innerHTML = '';
                fetch('{{ url('/customer/profile/sendotp') }}', {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'phone': number,
                    })
                })
                .then(function(response) {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(function(data) {
                    console.log(data);
                    ele.innerHTML = '{{ lang('Resend OTP') }}';
                    if (data.error === 'reload') {
                        location.reload();
                    }
                    if (data?.errors?.phone) {
                        document.querySelector('#alreadyExists').innerHTML = data.errors.phone;
                        ele.innerHTML = '{{ lang('Send OTP') }}';
                        ele.classList.remove('disabled');
                    }
                    if (data.mainError) {
                        document.querySelector('#alreadyExists').innerHTML = data.mainError;
                        ele.innerHTML = '{{ lang('Send OTP') }}';
                        ele.classList.remove('disabled');
                    }
                    if (data.success) {
                        document.querySelector('#showenterotp').classList.remove('d-none');
                        document.querySelector('#resendTimeout').classList.remove('d-none');
                        let timeLeft = 30; // Set initial time remaining
                        let timerElement = document.getElementById("timer");

                        // Function to update timer display and decrement time
                        function updateTimer() {
                            timeLeft--;
                            timerElement.textContent = timeLeft;

                            if (timeLeft === 0) {
                                clearInterval(intervalId);
                                ele.classList.remove('disabled');
                                document.querySelector('#resendTimeout').classList.add('d-none');
                            }
                        }

                        let intervalId = setInterval(updateTimer, 1000);
                        document.querySelector('#OTPMobile').addEventListener('input', () => {
                            let toVerifyOTP = document.querySelector('#OTPMobile');

                            let inputValue = toVerifyOTP.value.trim();

                            if (inputValue.length === 6) {
                                toVerifyOTP.disabled = true;
                                fetch('{{ url('/customer/profile/verifyOTP') }}', {
                                        method: "POST",
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            'otp': toVerifyOTP.value,
                                            'phone': number,
                                            'countryCode': locationDetails
                                                .getSelectedCountryData(),
                                        })
                                    })
                                    .then(function(response) {
                                        if (response.ok) {
                                            return response.json();
                                        } else {
                                            throw new Error('Network response was not ok');
                                        }
                                    })
                                    .then(function(data) {
                                        console.log(data);
                                        if (data.error === 'Invalid OTP') {
                                            toVerifyOTP.disabled = false;
                                            document.querySelector('#invalidotp').innerHTML = data.error;
                                            toastr.error(data.error);
                                        }
                                        if (data.success) {
                                            toVerifyOTP.disabled = false;
                                            document.querySelector('#invalidotp').innerHTML = '';
                                            toastr.success(data.success);
                                            location.reload();
                                            document.querySelector('#successHide').style.display = 'none';
                                        }
                                    })
                                    .catch(function(error) {
                                        console.error('Error:', error);
                                    });
                            }
                        })
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
            }
        @endif
    </script>
@endsection
