@extends('layouts.adminmaster')

@section('styles')
    <!-- INTERNAl Summernote css -->
    <link rel="stylesheet" href="{{ asset('build/assets/plugins/summernote/summernote.css') }}?v=<?php echo time(); ?>">

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span
                    class="font-weight-normal text-muted ms-2">{{ lang('Email Templates', 'menu') }}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <div class="row">
        <!-- Email Template Edit -->
        <div class="col-xl-8 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0 d-flex">
                    <h4 class="card-title">{{ lang('Email Template') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.email.update', [$template->id]) }}"
                        enctype="multipart/form-data">
                        @csrf

                        @honeypot
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Email Subject') }} <span
                                            class="text-red">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                        name="subject" Value="{{ old('subject', $template->subject) }}">
                                    @error('subject')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ lang('Email Body') }} <span
                                            class="text-red">*</span></label>
                                    <textarea class="form-control summernote @error('body') is-invalid @enderror" placeholder="{{ lang('FAQ Answer') }}"
                                        name="body" id="answer" aria-multiline="true">
																{{ old('body', $template->body) }}
															</textarea>
                                    @error('body')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                    @if (session('bodyNull'))
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang(session('bodyNull')) }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-12 card-footer ">
                                <div class="form-group float-end mb-0">
                                    <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Email Template Fields') }}</h4>
                </div>
                <div class="card-body">
                    @php
                        $templatefields = explode(',', $template->variables_used);
                    @endphp
                    <div class="row">
                        @foreach ($templatefields as $templatefield)
                            <div class="col-md-6 col-sm-12 p-1">
                                <div class="border br-3 p-2">
                                    <div class="fs-14 font-weight-semibold"> &#123;&#123; {{ $templatefield }} &#125;&#125;
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <!-- Email Template Edit -->
    </div>
@endsection

@section('scripts')
    <!-- INTERNAL Summernote js  -->
    <script src="{{ asset('build/assets/plugins/summernote/summernote.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>

    <!-- INTERNAL Index js-->
    @vite(['resources/assets/js/support/support-sidemenu.js'])
    @vite(['resources/assets/js/support/support-createticket.js'])
@endsection
