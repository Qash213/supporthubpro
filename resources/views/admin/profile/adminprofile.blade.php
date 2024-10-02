@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAl Tag css -->
    <link href="{{ asset('build/assets/plugins/taginput/bootstrap-tagsinput.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection

@section('content')
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Profile', 'menu') }}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <!-- Profile Page-->
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-12">
            <div class="card user-pro-list overflow-hidden">
                <div class="card-body">
                    <div class="user-pic text-center">

                        @if (Auth::user()->image == null)

                            <span class="avatar avatar-xxl brround"
                                style="background-image: url(../uploads/profile/user-profile.png)">
                                <span class="avatar-status bg-green"></span>
                            </span>
                        @else
                            <span class="avatar avatar-xxl brround"
                                style="background-image: url('{{ route('getprofile.url', ['imagePath' => Auth::user()->image, 'storage_disk' => Auth::user()->storage_disk ?? 'public']) }}')">
                                <span class="avatar-status bg-green"></span>
                            </span>
                        @endif
                        <div class="pro-user mt-3">
                            <h5 class="pro-user-username text-dark mb-1 fs-16">{{ Auth::user()->name }}</h5>
                            <h6 class="pro-user-desc text-muted fs-12">{{ Auth::user()->email }}</h6>
                            @if (!empty(Auth::user()->getRoleNames()[0]))
                                <h6 class="pro-user-desc text-muted fs-12">{{ Auth::user()->getRoleNames()[0] }}</h6>
                            @endif
                            <div class="profilerating" data-rating="{{ $avg }}"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title"> {{ lang('Personal Details') }}</h4>
                </div>
                <div class="card-body px-0 pb-0">

                    <div class="table-responsive tr-lastchild">
                        <table class="table mb-0 table-information">
                            <tbody>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Employee ID') }}</span>
                                    </td>
                                    <td class="py-2 ps-4">{{ Auth::user()->empid }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Name') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Role') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @if (!empty(Auth::user()->getRoleNames()[0]))

                                            {{ Auth::user()->getRoleNames()[0] }}
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Email') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Phone') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{ Auth::user()->phone }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Languages') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @php
                                            $values = explode(',', Auth::user()->languagues);

                                        @endphp

                                        <ul class="custom-ul">
                                            @foreach ($values as $value)
                                                <li class="tag mb-1">{{ ucfirst($value) }}</li>
                                            @endforeach

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50">{{ lang('Skills') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @php
                                            $values = explode(',', Auth::user()->skills);
                                        @endphp

                                        <ul class="custom-ul">
                                            @foreach ($values as $value)
                                                <li class="tag mb-1">{{ ucfirst($value) }}</li>
                                            @endforeach

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{ lang('Location') }} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{ Auth::user()->country }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if (setting('SPRUKOADMIN_P') == 'on')

                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="card-title"> {{ lang('Personal Setting') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="switch_section">
                            <div class="switch-toggle d-flex mt-4">
                                <a class="onoffswitch2">
                                    <input type="checkbox" data-id="{{ Auth::id() }}" name="checkbox"
                                        id="myonoffswitch181" class=" toggle-class onoffswitch2-checkbox sprukoswitch"
                                        @if (Auth::check() && Auth::user()->darkmode == 1) checked="" @endif>
                                    <label for="myonoffswitch181" class="toggle-class onoffswitch2-label"
                                        data-id="{{ Auth::id() }}"></label>
                                </a>
                                <label class="form-label ps-3"> {{ lang('Switch to Dark-Mode') }} </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Setting -->
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title"> {{ lang('Setting') }}</h4>
                </div>
                <div class="card-body">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                            <a class="onoffswitch2">
                                <input type="checkbox" data-id="{{ Auth::id() }}" name="emailnotificationon"
                                    id="emailnotificationon" class=" toggle-class onoffswitch2-checkbox"
                                    @if (Auth::check() && Auth::user()->usetting != null) @if (Auth::check() && Auth::user()->usetting->emailnotifyon == 1) checked="" @endif
                                    @endif>
                                <label for="emailnotificationon" class="toggle-class onoffswitch2-label"
                                    data-id="{{ Auth::id() }}"></label>
                            </a>
                            <label class="form-label ps-3"> {{ lang('Email Notification On/Off') }} </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Setting --->

        </div>
        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title"> {{ lang('Profile Details') }}</h4>
                </div>
                @if (Auth::user()->can('Profile Edit'))
                    <form method="POST" action="{{ url('/admin/profile') }}" enctype="multipart/form-data">
                        @csrf
                        @honeypot
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('First Name') }}</label>
                                        <input type="text"
                                            class="form-control @error('firstname') is-invalid @enderror" name="firstname"
                                            value="{{ Auth::user()->firstname }}">
                                        @error('firstname')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Last Name') }}</label>
                                        <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                            name="lastname" value="{{ Auth::user()->lastname }}">
                                        @error('lastname')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Email') }} @if (Auth::user()->can('Email Update'))<a
                                                    class="action-btns1 ms-2 border-0"
                                                    data-id="{{ Auth::user()->email }}" id="emailchange"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i
                                                        class="feather feather-edit text-primary"></i></a>@endif
                                        </label>
                                        <input type="email" class="form-control" Value="{{ Auth::user()->email }}"
                                            disabled>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"> {{ lang('Employee ID') }}</label>
                                        <input type="email" class="form-control" Value="{{ Auth::user()->empid }}"
                                            disabled>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Mobile Number') }}</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Languages') }}</label>
                                        <input type="text"
                                            class="form-control @error('languages') is-invalid @enderror sprukotags"
                                            value="{{ old('languages', Auth::user()->languagues) }}" name="languages"
                                            data-role="tagsinput" />
                                        @error('languages')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Skills') }}</label>
                                        <input type="text"
                                            class="form-control @error('skills') is-invalid @enderror sprukotags"
                                            value="{{ old('skills', Auth::user()->skills) }}" name="skills"
                                            data-role="tagsinput" />
                                        @error('skills')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Country') }}</label>
                                        <select name="country" class="form-control select2 select2-show-search">

                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}"
                                                    {{ $country->name == Auth::user()->country ? 'selected' : '' }}>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Timezone') }}</label>

                                        <select name="timezone" class="form-control select2 select2-show-search">

                                            @foreach ($timezones as $group => $timezoness)
                                                <option value="{{ $timezoness->timezone }}"
                                                    {{ $timezoness->timezone == Auth::user()->timezone ? 'selected' : '' }}>
                                                    {{ $timezoness->timezone }} {{ $timezoness->utc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Upload Image') }}</label>
                                        <div class="input-group file-browser">
                                            <input class="form-control @error('image') is-invalid @enderror"
                                                name="image" type="file" accept="image/png, image/jpeg,image/jpg">

                                            @error('image')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ lang($message) }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                        <small
                                            class="text-muted"><i>{{ lang('The file size should not be more than 5MB', 'filesetting') }}</i></small>
                                    </div>
                                    @if (Auth::user()->image != null)
                                        <div class="file-image-1 removesprukoi{{ Auth::user()->id }}">
                                            <div class="product-image custom-ul">
                                                <a href="#">
                                                    <img src="{{ route('getprofile.url', ['imagePath' => Auth::user()->image, 'storage_disk' => Auth::user()->storage_disk ?? 'public']) }}"
                                                        class="br-5" alt="{{ Auth::user()->image }}">
                                                </a>
                                                <ul class="icons">
                                                    <li><a href="javascript:(0);" class="bg-danger delete-image"
                                                            data-id="{{ Auth::user()->id }}"><i
                                                                class="fe fe-trash"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 card-footer">
                            <div class="form-group float-end">
                                <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                            </div>
                        </div>
                    </form>
                @else
                    @csrf
                    @honeypot
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('First Name') }}</label>
                                    <input type="text" class="form-control" name="firstname"
                                        value="{{ Auth::user()->firstname }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Last Name') }}</label>
                                    <input type="text" class="form-control" name="lastname"
                                        value="{{ Auth::user()->lastname }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Email') }}</label>
                                    <input type="email" class="form-control" Value="{{ Auth::user()->email }}"
                                        disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Employee ID') }}</label>
                                    <input type="email" class="form-control" Value="{{ Auth::user()->empid }}"
                                        disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Mobile Number') }}</label>
                                    <input type="text" class="form-control " name="phone"
                                        value="{{ old('phone', Auth::user()->phone) }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Languages') }}</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->languagues }}"
                                        name="languages" data-role="tagsinput" disabled />
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Skills') }}</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->skills }}"
                                        name="skills" data-role="tagsinput" disabled />
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Country') }}</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->country }}"
                                        disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{ lang('Timezone') }}</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->timezone }}"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Upload Image') }}</label>
                                    <div class="input-group file-browser">
                                        <input class="form-control @error('image') is-invalid @enderror" name="image"
                                            type="file" accept="image/png, image/jpeg,image/jpg" disabled>

                                        @error('image')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <small
                                        class="text-muted"><i>{{ lang('The file size should not be more than 5MB', 'filesetting') }}</i></small>
                                </div>
                                @if (Auth::user()->image != null)
                                    <div class="file-image-1 removesprukoi{{ Auth::user()->id }}">
                                        <div class="product-image custom-ul">
                                            <a href="#">
                                                <img src="{{ route('getprofile.url', ['imagePath' => Auth::user()->image, 'storage_disk' => Auth::user()->storage_disk ?? 'public']) }}"
                                                    class="br-5" alt="{{ Auth::user()->image }}">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if (setting('Employe_google_two_fact') == 'on' || setting('Employe_email_two_fact') == 'on')
                <div class="card">
                    <div class="card-header border-bottom-0 pb-0">
                        <div class="card-title"> {{ lang('Two Factor Authentication') }}</div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="d-sm-flex d-block gap-3 align-items-center">
                                            @if (setting('Employe_google_two_fact') == 'on')
                                                <div class="switch_section px-0">
                                                    <div class="switch-toggle d-flex align-items-center">
                                                        <a class="onoffswitch2">
                                                            <input type="checkbox" data-id="{{ Auth::id() }}"
                                                                name="emptwofactor" id="emptwofactor"
                                                                class="toggle-class onoffswitch2-checkbox emptwofactor"
                                                                autocomplete="off"
                                                                @if (Auth::check() && Auth::user()->twofactorauth == 'googletwofact') checked="" @endif>
                                                            <label for="emptwofactor"
                                                                class="toggle-class onoffswitch2-label mb-0"
                                                                data-id="{{ Auth::id() }}"></label>
                                                        </a>
                                                        <label class="form-label ps-3 mb-0">{{ lang('Use Google Authenticator') }}</label>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (setting('Employe_email_two_fact') == 'on')
                                                <div class="switch_section px-0">
                                                    <div class="switch-toggle d-flex align-items-center">
                                                        <a class="onoffswitch2">
                                                            <input type="checkbox" data-id="{{ Auth::id() }}"
                                                                name="empemailtwofactor" id="empemailtwofactor"
                                                                class="toggle-class onoffswitch2-checkbox sprukoempemailtwofactor"
                                                                autocomplete="off"
                                                                @if (Auth::check() && Auth::user()->twofactorauth == 'emailtwofact') checked="" @endif>
                                                            <label for="empemailtwofactor"
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
                                @if (setting('Employe_google_two_fact') == 'on' && Auth::user()->twofactorauth == 'googletwofact')
                                    <div class="mt-5" id="configured">
                                        <div class="alert bg-success-transparent text-dark " role="alert">
                                            <h5 class="mb-4">
                                                {{ lang('Google two factor authentication is already configured.') }}</h5>
                                            <button type="button"
                                                class="btn btn-primary reconfig">{{ lang('Reconfigure') }}</button>
                                            <button class="btn btn-danger removetf">{{ lang('Remove') }}</button>

                                        </div>
                                    </div>
                                @endif
                                @if (setting('Employe_email_two_fact') == 'on' && Auth::user()->twofactorauth == 'emailtwofact')
                                    <div class="mt-5 " id="emailtwofac">
                                        <div class="alert bg-warning-transparent text-dark p-5" role="alert">
                                            <h4 class="mb-2">{{ lang('How does email otp authenticator works ?') }}
                                            </h4>
                                            <p class="mb-0">
                                                {{ lang('Two-Factor Authentication (2FA) is an option that provides an
                                                                                                                                            extra
                                                                                                                                            layer of security to your account in addition to your email and
                                                                                                                                            password. When Two-Factor Authentication is enabled, your account cannot be
                                                                                                                                            accessed
                                                                                                                                            by anyone, even if they have stolen your password.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @include('admin.auth.passwords.changepassword')

        </div>
    </div>
    <!--End Profile Page-->
@endsection

@section('modal')
    @include('admin.profile.emailupdatemodal')
    @include('admin.profile.2fapasswordmodal')
@endsection


@section('scripts')
    <!-- INTERNAL Vertical-scroll js-->
    <script src="{{ asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js') }}?v=<?php echo time(); ?>">
    </script>

    <!-- INTERNAL Index js-->
        @vite(['resources/assets/js/support/support-sidemenu.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL TAG js-->
    <script src="{{ asset('build/assets/plugins/taginput/bootstrap-tagsinput.js') }}?v=<?php echo time(); ?>"></script>

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

                // Profile Rating
                $(".profilerating").starRating({
                    readOnly: true,
                    // totalStars: 5,
                    starSize: 20,
                    emptyColor: '#ffffff',
                    activeColor: '#F2B827',
                    strokeColor: '#F2B827',
                    strokeWidth: 15,
                    useGradient: false

                });

                // DarkMode switch js
                $('.sprukoswitch').on('change', function() {
                    var dark = $('#myonoffswitch181').prop('checked') == true ? '1' : '';
                    if (dark == '1') {
                        document.querySelector('body').classList.add('dark-mode');
                    } else {
                        document.querySelector('body').classList.remove('dark-mode');
                    }
                    var user_id = $(this).data('id');
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: '{{ url('/admin/usersettings') }}',
                        data: {
                            'dark': dark,
                            'user_id': user_id
                        },
                        success: function(data) {
                            toastr.success('{{ lang('Updated Successfully!', 'alerts') }}');
                        }
                    });
                });

                @if (Auth::user()->image != null)

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
                                        type: "post",
                                        url: SITEURL + "/admin/image/remove/" + _id,
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
                @endif

                $('body').on('change', '#emailnotificationon', function(e) {
                    e.preventDefault();

                    let emailvalue = $(this).prop('checked') == true ? '1' : '0',
                        userid = $(this).data('id');

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ url('/admin/emailonoff') }}',
                        data: {
                            'emailvalue': emailvalue,
                            'userid': userid,
                        },
                        success: function(data) {
                            toastr.success('{{ lang('Updated Successfully!', 'alerts') }}')
                            // window.location.reload();
                        }
                    });

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
                        url: SITEURL + "/admin/adminemailchange",
                        data: formData,
                        success: function(data) {
                            if (data?.message == 'wrongpassword') {
                                $('#passwordError').html(data.error);
                                $('#btnsave').html('Submit');
                            }
                            if (data?.message == 'linksend') {
                                location.reload();
                                toastr.success(data.success);
                            }
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                // two factor auth start
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
                        if (document.querySelector("#configured")) {
                            document.querySelector("#configured").remove();
                        }
                        if (document.querySelector("#emptwofactor") != null && document.querySelector("#emptwofactor")
                            .checked != false) {
                            document.querySelector("#emptwofactor").checked = false;
                        }
                        if (document.querySelector("#TwoFactorAuthentication")) {
                            document.querySelector("#TwoFactorAuthentication").remove();
                        }

                        var emailtwofact = $('#empemailtwofactor').prop('checked') == true ? '1' : '';
                        var cust_id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('user.emailtwofactor') }}',
                            data: formData,
                            success: function(data) {
                                if(data?.message == 'wrongpassword'){
                                    toastr.error(data.error);
                                    location.reload();
                                }else{
                                    if (data.disabled) {
                                        if (document.querySelector("#TwoFactorAuthentication")) {
                                            document.querySelector("#TwoFactorAuthentication").remove();
                                        }
                                        if (document.querySelector("#emailtwofac")) {
                                            document.querySelector("#emailtwofac").remove();
                                        }
                                        toastr.success(data.success);
                                        location.reload();
                                    } else {
                                        let element = document.querySelector("#emptwofactorapp").parentNode
                                            .parentNode.parentNode
                                        let nodeElement = document.createElement("div");
                                        nodeElement.className = "col-md-12 mt-4 "
                                        nodeElement.id = "TwoFactorAuthentication"
                                        nodeElement.innerHTML = `<div class="mt-5">
                                            <div class="alert bg-warning-transparent text-dark p-5" role="alert">
                                                <h4 class="mb-2">How does email otp authenticator works ?</h4>
                                                <p class="mb-0">Two-Factor Authentication (2FA) is an option that provides an extra
                                                    layer of security to your account in addition to your email and
                                                    password. When Two-Factor Authentication is enabled, your account cannot be accessed
                                                    by anyone, even if they have stolen your password.</p>
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

                    if(what2fastatus == 'googletwofactor'){
                        $("#2fapassmodal").modal('hide');
                        if (document.querySelector("#configured")) {
                            document.querySelector("#configured").remove();
                        }
                        if (document.querySelector("#emailtwofac")) {
                            document.querySelector("#emailtwofac").remove();
                        }
                        if (document.querySelector("#empemailtwofactor") != null && document.querySelector(
                                "#empemailtwofactor").checked) {
                            document.querySelector("#empemailtwofactor").checked = false;
                        }

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('emptwofactqr') }}',
                            data: formData,
                            success: function(data) {
                                if(data?.message == 'wrongpassword'){
                                    toastr.error(data.error);
                                    location.reload();
                                }else{
                                    if (data?.workprogress == 'workingmode') {
                                        if (document.querySelector("#TwoFactorAuthentication")) {
                                            document.querySelector("#TwoFactorAuthentication").remove();
                                        }
                                        let element = document.querySelector("#emptwofactorapp").parentNode
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
                                                    <input id="one_time_password" type="number" class="form-control" name="one_time_password" required required autofocus autocomplete="off" >
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-primary" id="otpverify">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`
                                        element.appendChild(nodeElement)

                                        document.querySelector("#otpverify").addEventListener("click", () => {
                                            var cust_id = {{ Auth::id() }};
                                            var secret_key = document.getElementById("secret_key_value")
                                                .value;
                                            var otp = document.getElementById("one_time_password")
                                                .value;

                                            $.ajax({
                                                type: "POST",
                                                dataType: 'json',
                                                url: '{{ route('empgoogle2faotp.verify') }}',
                                                data: {
                                                    'otp': otp,
                                                    'id': cust_id,
                                                    'secret_key_value': secret_key
                                                },
                                                success: function(response) {
                                                    if (response == 0) {
                                                        toastr.error(
                                                            '{{ lang('Invalid otp.', 'alerts') }}'
                                                        );
                                                    }
                                                    if (response == 1) {
                                                        document.querySelector(
                                                                "#TwoFactorAuthentication")
                                                            .remove()
                                                        location.reload();
                                                        toastr.success(
                                                            '{{ lang('GoogleTwo factor authentication activated.', 'alerts') }}'
                                                        );
                                                        location.reload();

                                                    }

                                                },
                                                error: function(data) {
                                                    toastr.error('{{ lang('please enter your otp.', 'alerts') }}');
                                                    location.reload();
                                                }
                                            });




                                        })
                                        toastr.success(
                                            '{{ lang('Setup your authenticator app.', 'alerts') }}');
                                    } else {
                                        if (document.querySelector("#TwoFactorAuthentication")) {
                                            document.querySelector("#TwoFactorAuthentication").remove()
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

                    if(what2fastatus == 'google2faremove'){
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ route('emptwofactqr') }}',
                            data: formData,
                            success: function(data) {
                                if(data?.message == 'wrongpassword'){
                                    toastr.error(data.error);
                                    location.reload();
                                }else{
                                    if (document.querySelector("#emptwofactor") != null && document
                                        .querySelector("#emptwofactor").checked != false) {
                                        document.querySelector("#emptwofactor").checked = false;
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

                //remove google twofactor authentication
                $('.removetf').on('click', function() {
                    $('#2fapasswordverify').trigger("reset");
                    what2fastatus = 'google2faremove';
                    $("#emailcheckstatus").val('');
                    $("#cust_id").val('{{ Auth::id() }}');
                    $("#2fapassmodal").modal('show');
                });

                // email twofactor
                $('.sprukoempemailtwofactor').on('change', function() {
                    var authgoogle2fastaus = '{{ Auth::user()->twofactorauth }}';
                    if(authgoogle2fastaus == 'googletwofact'){
                        toastr.error('first of all you need to disable the google two factor authentication');
                        location.reload();
                    }else{
                        $('#2fapasswordverify').trigger("reset");
                        what2fastatus = 'emailtwofactor';
                        $("#emailcheckstatus").val($('#empemailtwofactor').prop('checked') == true ? '1' : '');
                        $("#cust_id").val($(this).data('id'));
                        $("#2fapassmodal").modal('show');
                    }
                });
                // end email twofactor

                // google twofactor
                $('.emptwofactor,.reconfig').on('click', function() {

                    var authemail2fastaus = '{{ Auth::user()->twofactorauth }}';
                    if(authemail2fastaus == 'emailtwofact'){
                        toastr.error('first of all you need to disable the email two factor authentication');
                        location.reload();
                    }else{
                        $('#2fapasswordverify').trigger("reset");
                        what2fastatus = 'googletwofactor';
                        $("#emailcheckstatus").val($('#emptwofactor').prop('checked') == true ? '1' : '');
                        $("#cust_id").val('{{ Auth::id() }}');
                        $("#2fapassmodal").modal('show');
                    }

                });
                // two factor auth end

            })(jQuery);
        })
    </script>
@endsection
