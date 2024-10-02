@extends('layouts.usermaster')

		@section('styles')

		<!-- INTERNAl Summernote css -->
		<link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}">

		<link href="{{asset('build/assets/plugins/dropzone/dropzone.css')}}" rel="stylesheet" />

		@endsection

				@section('content')

				<!-- Section -->
                <section>
                    <div class="bannerimg cover-image" data-bs-image-src="{{asset('build/assets/images/photos/banner1.jpg')}}">
                        <div class="header-text mb-0">
                            <div class="container">
                                <div class="row text-white">
                                    <div class="col">
                                        <h1 class="mb-0">{{lang('Guest Ticket')}}</h1>
                                    </div>
                                    <div class="col col-auto">
                                        <ol class="breadcrumb text-center">
                                            <li class="breadcrumb-item">
                                                <a href="{{url('/')}}" class="text-white-50">{{lang('Home', 'menu')}}</a>
                                            </li>
                                            <li class="breadcrumb-item active">
                                                <a href="#" class="text-white">{{lang('Guest Ticket')}}</a>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Section -->

                <!--Section-->
                <section>
                    <div class="cover-image sptb">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xl-9">
                                    <!--Office hours Modal-->
                                    <div class="modal fade" id="Office-hours">
                                        <div class="modal-dialog forget-modal" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{lang('Support Hours')}}</h5>
                                                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <div class="single-page customerpage">
                                                        <div class="card-body ">
                                                            <ul class="custom-ul text-justify pricing-body text-muted ps-0 mb-4">

                                                                @forelse ($holidays as $holiday)
                                                                    @php
                                                                        $datetimeall = [];
                                                                        for ($i = (int)\Carbon\Carbon::parse($holiday->startdate)->format('d'); $i <= (int)\Carbon\Carbon::parse($holiday->enddate)->format('d'); $i++){
                                                                            $datetimeformat = \Carbon\Carbon::parse(now()->format('Y-m-') . '' . $i);
                                                                            array_push($datetimeall, $datetimeformat->format('D'));
                                                                        }
                                                                    @endphp
                                                                @empty

                                                                @endforelse

                                                                @foreach(bussinesshour() as $bussiness)
                                                                    @if($bussiness->weeks != null)
                                                                        <li class="mb-2">
                                                                            <div class="row br-5 notify-days-cal align-items-center p-2 br-5 border text-center {{now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks ? 'bg-success-transparent' : '' }}">
                                                                                <div class="col-xxl-3 col-xl-3 col-sm-12 ps-0">

                                                                                    <span class="badge {{now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks ? 'bg-success' : 'bg-info' }}   fs-13 font-weight-normal  w-100 ">{{lang($bussiness->weeks)}}</span>

                                                                                </div>
                                                                                <div class="col-xxl-3 col-xl-4 col-sm-12">
                                                                                    @if(now()->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks)

                                                                                    <span class="{{$bussiness->status != 'Closed' ? 'text-success' : 'text-success' }} fs-12 ms-2">{{lang('Today')}}</span>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-xxl-6 col-xl-5 col-sm-12 px-0">
                                                                                    @if($bussiness->status == "Closed")
                                                                                        <span class="text-danger fs-12 ms-2">{{lang($bussiness->status)}}</span>
                                                                                    @else

                                                                                        @if($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on')


                                                                                                @if(in_array($bussiness->weeks,$datetimeall))
                                                                                                    <span class="text-danger fs-12 ms-2">{{lang('Closed')}}</span>
                                                                                                @else
                                                                                                    <span class="ms-0 fs-13">{{$bussiness->starttime}}
                                                                                                    @if($bussiness->starttime !== null && $bussiness->endtime != null )
                                                                                                    <span class="fs-10 mx-1">- </span>
                                                                                                    @endif
                                                                                                    </span>
                                                                                                    @if($bussiness->starttime !== null && $bussiness->endtime )
                                                                                                    <span class="ms-0">{{$bussiness->endtime}}</span>
                                                                                                    @endif
                                                                                                @endif


                                                                                        @else
                                                                                            @if($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on' && in_array($bussiness->weeks,$datetimeall))
                                                                                                <span class="text-danger fs-12 ms-2">{{lang('Closed')}}</span>
                                                                                            @else
                                                                                                <span class="ms-0 fs-13">{{$bussiness->starttime}}
                                                                                                @if($bussiness->starttime !== null && $bussiness->endtime != null )
                                                                                                <span class="fs-10 mx-1">- </span>
                                                                                                @endif
                                                                                                </span>
                                                                                                @if($bussiness->starttime !== null && $bussiness->endtime )
                                                                                                <span class="ms-0">{{$bussiness->endtime}}</span>
                                                                                                @endif
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    @endif

                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Office hours Modal  -->
                                    @if($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on')
                                        @foreach ($holidays as $anct)

                                            <div class="alert alert-holiday p-5" role="alert" style="background: {{$anct->primaray_color}}; border-color: {{$anct->primaray_color}}; color:{{$anct->secondary_color}};">
                                                <button type="submit" class="btn-close ms-5 float-end text-danger notifyclose" style="color:{{$anct->secondary_color}};" data-id="{{$anct->id}}">×</button>
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="lh-1 svg-icon flex-shrink-0" style="background: {{ str_replace(', 1)', ', 0.1)', $anct->secondary_color) }}; color:{{$anct->secondary_color}};">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 10H4V19H20V10ZM15.0355 11.136L16.4497 12.5503L11.5 17.5L7.96447 13.9645L9.37868 12.5503L11.5 14.6716L15.0355 11.136ZM7 5H4V8H20V5H17V6H15V5H9V6H7V5Z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1 holiday-title" style="color:{{$anct->secondary_color}};">{{$anct->occasion}}</h5>
                                                        <span>{!!$anct->holidaydescription!!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @php
                                            $createdtime = now();
                                        @endphp
                                        @foreach(bussinesshour() as $bussiness)
                                            @if($createdtime->timezone(setting('default_timezone'))->format('D') == $bussiness->weeks)
                                                @if(strtotime($bussiness->starttime) <= strtotime($createdtime->timezone(setting('default_timezone'))->format('h:i A')) && strtotime($bussiness->endtime) >= strtotime($createdtime->timezone(setting('default_timezone'))->format('h:i A'))|| $bussiness->starttime == "24H")

                                                @else

                                                    <div class="alert alert-offline p-5" role="alert">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="lh-1 svg-icon flex-shrink-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><circle cx="128" cy="128" r="96" opacity="0.2"/><path d="M128,224a96,96,0,1,1,96-96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M128,224s-40-32-40-96,40-96,40-96,40,32,40,96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="37.46" y1="96" x2="218.54" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="37.46" y1="160" x2="128" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="168" y1="168" x2="216" y2="216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="216" y1="168" x2="168" y2="216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                                            </div>
                                                            <div>
                                                                <h5 class="fw-semibold mb-1 offline-title">{{lang('We Are Offline')}}</h5>
                                                                <span>{{lang('Hey there! We’re currently offline, but don’t worry, we’ll be back soon to assist you. In the meantime, feel free to explore our knowledge base for answers to common questions. If you have an urgent matter, please create a ticket, and we’ll get back to you promptly once we’re back online. Thank you for your patience!')}} -
                                                                <a href="javascript:void(0);" class="font-weight-semibold text-decoration-underline text-secondary"  data-bs-target="#Office-hours" data-bs-toggle="modal">{{lang('Support Hours')}}</a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                        @endforeach
                                    @endif

                                    <div class="card">
                                        <div class="card-header  border-0">
                                            <h4 class="card-title">{{lang('Guest Ticket')}}</h4>
                                        </div>
                                        <form  method="post" id="guest_form" enctype="multipart/form-data">
                                            @csrf
                                            @honeypot
                                            <input type="hidden" name="productname" id="itemname">
                                            <div class="card-body pb-0">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Email')}} <span class="text-red">*</span></label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{lang('Email')}}" name="email" >
                                                            <span id="EmailError" class="text-danger alert-message" ></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(setting('cc_email') == 'on')
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label mb-0 mt-2">{{lang('CC')}} </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <input type="email" class="form-control @error('ccmail') is-invalid @enderror" placeholder="{{lang('CC Email')}}" value="{{ old('ccmail') }}" name="ccmail" id="ccmail">
                                                                <div><small class="text-muted"> {{lang('You are allowed to send only a single CC.')}}</small></div>
                                                                <span id="ccEmailError" class="text-danger alert-message" ></span>
                                                                @error('ccmail')

                                                                    <span class="invalid-feedback d-block" role="alert">
                                                                        <strong>{{ lang($message) }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Subject')}} <span class="text-red">*</span></label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" id="subject" maxlength="{{setting('TICKET_CHARACTER')}}" class="form-control @error('subject') is-invalid @enderror" placeholder="{{lang('Subject')}}" name="subject" value="{{ old('subject') }}">
                                                            <small class="text-muted float-end mt-1" id="subjectmaxtext">{{lang('Maximum')}} <b>{{setting('TICKET_CHARACTER')}}</b> {{lang('Characters')}}</small>
                                                            <div>
                                                                <span id="SubjectError" class="text-danger alert-message"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Category')}} <span class="text-red">*</span></label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select  class="form-control select2-show-search  select2 @error('category') is-invalid @enderror"  data-placeholder="{{lang('Select Category')}}" name="category" id="category">
                                                                <option label="{{lang('Select Category')}}"></option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span id="CategoryError" class="text-danger alert-message"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="selectssSubCategory" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Sub-Category')}}</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select  class="form-control select2-show-search select2 asdf"  data-placeholder="{{lang('Select SubCategory')}}" name="subscategory" id="subscategory" >

                                                            </select>
                                                            <span id="subsCategoryError" class="text-danger alert-message"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="selectSubCategory">
                                                </div>

                                                <div class="form-group" id="envatopurchase">
                                                </div>

                                                @if(setting('ENVATO_ON') == 'on')
                                                    <div class="row d-none" id="hideelement">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Envato Item Name')}}<span class="text-red">*</span></label>
                                                        </div>
                                                        <div class="col-md-9 mb-4">
                                                            <input type="text" id="productname" class="form-control" placeholder="Envato Project Name">

                                                        </div>
                                                    </div>

                                                @endif
                                                @if($customfields->isNotEmpty())
                                                    @foreach($customfields as $customfield)

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label mb-0 mt-2">{{$customfield->fieldnames}}
                                                                    @if($customfield->fieldrequired == '1')

                                                                    <span class="text-red">*</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                            <div class="col-md-9">

                                                                @if($customfield->fieldtypes == 'text')

                                                                    <input type="{{$customfield->fieldtypes}}" maxlength="255" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
                                                                @endif
                                                                @if($customfield->fieldtypes == 'email')

                                                                    <input type="{{$customfield->fieldtypes}}" class="form-control" name="custom_{{$customfield->id}}" id="" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
                                                                @endif
                                                                @if($customfield->fieldtypes == 'textarea')

                                                                    <textarea name="custom_{{$customfield->id}}" maxlength="255" class="form-control" id="" cols="30" rows="4" {{$customfield->fieldrequired == '1' ? 'required' : ''}}></textarea>
                                                                @endif
                                                                @if($customfield->fieldtypes == 'checkbox')

                                                                    @php
                                                                        $coptions = explode(',', $customfield->fieldoptions)
                                                                    @endphp
                                                                    @foreach($coptions as $key => $coption)
                                                                    <label class="custom-control custom-checkbox d-inline-block me-3">
                                                                        <input type="{{$customfield->fieldtypes}}" class="custom-control-input {{$customfield->fieldrequired == '1' ? 'required' : ''}}"  name="custom_{{$customfield->id}}[]" value="{{$coption}}" id="" >

                                                                        <span class="custom-control-label">{{$coption}}</span>
                                                                    </label>

                                                                    @endforeach


                                                                @endif
                                                                @if($customfield->fieldtypes == 'select')
                                                                    <select name="custom_{{$customfield->id}}" id="" class="form-control select2-show-search" data-placeholder="{{lang('Select')}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
                                                                        @php
                                                                            $seoptions = explode(',', $customfield->fieldoptions)
                                                                        @endphp
                                                                        <option></option>
                                                                        @foreach($seoptions as $seoption)

                                                                        <option value="{{$seoption}}">{{$seoption}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                                @if($customfield->fieldtypes == 'radio')
                                                                @php
                                                                    $roptions = explode(',', $customfield->fieldoptions)
                                                                @endphp
                                                                @foreach($roptions as $roption)
                                                                <label class="custom-control custom-radio d-inline-block me-3">
                                                                    <input type="{{$customfield->fieldtypes}}" class="custom-control-input" name="custom_{{$customfield->id}}" value="{{$roption}}" {{$customfield->fieldrequired == '1' ? 'required' : ''}}>
                                                                    <span class="custom-control-label">{{$roption}}</span>
                                                                </label>


                                                                @endforeach

                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>

                                                    @endforeach
                                                @endif
                                                <div class="form-group ticket-summernote ">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Description')}} <span class="text-red">*</span></label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <textarea class=" form-control summernote @error('message') is-invalid @enderror" name="message"></textarea>

                                                            <span id="MessageError" class="text-danger alert-message"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label class="form-label mb-0 mt-2">{{lang('Upload File', 'filesetting')}}</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="form-group mb-0">
                                                                <div class="needsclick dropzone" id="document-dropzone"></div>
                                                            </div>
                                                            <small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if(setting('CAPTCHATYPE')=='manual')
                                                    @if(setting('RECAPTCH_ENABLE_GUEST')=='yes')
                                                    <div class="form-group mt-4">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label mb-0 mt-2">{{lang('Enter Captcha')}} <span class="text-red">*</span></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group row">
                                                                    <div class="col-md-3">
                                                                        <input type="text" id="captcha" class="form-control @error('captcha') is-invalid @enderror" placeholder="{{lang('Enter Captcha')}}" name="captcha">
                                                                        @error('captcha')
                                                                            <span class="invalid-feedback d-block" role="alert">
                                                                                <strong>{{ lang($message) }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="captcha">
                                                                            <span>{!! captcha_img('') !!}</span>
                                                                            <button type="button" class="btn btn-outline-info btn-sm captchabtn"><i class="fe fe-refresh-cw"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif

                                                <!--- if Enable the Google ReCaptcha --->
                                                @if(setting('CAPTCHATYPE')=='google')
                                                    @if(setting('RECAPTCH_ENABLE_GUEST')=='yes')
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="form-group mb-0 mt-4">
                                                                    <div class="g-recaptcha" data-sitekey="{{setting('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                                                    @if ($errors->has('g-recaptcha-response'))
                                                                        <span class="invalid-feedback d-block" role="alert">
                                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @endif
                                                @endif

                                                <div class="form-group @error('agree_terms') is-invalid @enderror">
                                                    <label class="custom-control form-checkbox">
                                                        <input type="checkbox" class="custom-control-input " value="agreed" name="agree_terms">
                                                        <span class="custom-control-label">{{lang('I agree with')}}<a href="{{setting('terms_url')}}" class="text-primary" target="_blank">{{lang('Terms & Services')}}</a></span>
                                                    </label>
                                                    <span class="text-red" id="agreetermsError"></span>
                                                </div>

                                            </div>

                                            <div class="card-footer">
                                                <div class="form-group float-end">
                                                    <button  type="submit"  class="btn btn-secondary btn-lg purchasecode" id="createticketbtn"> {{lang('Create Ticket', 'menu')}} </button>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--Section-->

				@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>


		<!-- INTERNAL Summernote js  -->
		<script src="{{asset('build/assets/plugins/summernote/summernote.js')}}"></script>

		<!-- INTERNAL Index js-->
        @vite(['resources/assets/js/support/support-sidemenu.js'])
        @vite(['resources/assets/js/support/support-createticket.js'])
        @vite(['resources/assets/js/select2.js'])

		<!-- INTERNAL Dropzone js-->
		<script src="{{asset('build/assets/plugins/dropzone/dropzone.js')}}"></script>

		<!-- INTERNAL Bootstrap-MaxLength js-->
		<script src="{{asset('build/assets/plugins/bootstrapmaxlength/bootstrap-maxlength.min.js')}}?v=<?php echo time(); ?>">
		</script>



		<script type="text/javascript">

            Dropzone.autoDiscover = false;
            $(function() {
                'use strict';

                // Variables
                var SITEURL = '{{url('')}}';

                var licensekey;

                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // when category change its get the subcat list
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
                            if(data.subcategoriess){
                                $('#subscategory').html(data.subcategoriess)
                                $('#selectssSubCategory').show()
                            }
                            else{
                                $('#selectssSubCategory').hide();
                                $('#subscategory').html('')
                            }

                            //projectlist
                            if(data.subCatStatus.length === 0){
                                $('#selectssSubCategory').hide();
                            }
                            if(data.subcategories.length >= 1){

                                $('#selectSubCategory')?.empty();
                                document.querySelector("#selectssSubCategory").classList.remove("d-none")
                                let selectDiv = document.querySelector('#selectSubCategory');
                                let Divrow = document.createElement('div');
                                Divrow.setAttribute('class','row mt-4');
                                let Divcol3 = document.createElement('div');
                                Divcol3.setAttribute('class','col-md-3');
                                let selectlabel =  document.createElement('label');
                                selectlabel.setAttribute('class','form-label mb-0 mt-2')
                                selectlabel.innerText = "{{lang('Project')}}";
                                let divcol9 = document.createElement('div');
                                divcol9.setAttribute('class', 'col-md-9');
                                let selecthSelectTag =  document.createElement('select');
                                selecthSelectTag.setAttribute('class','form-control select2-show-search');
                                selecthSelectTag.setAttribute('id', 'subcategory');
                                selecthSelectTag.setAttribute('name', 'project');
                                selecthSelectTag.setAttribute('data-placeholder','Select Projects');
                                let selectoption = document.createElement('option');
                                selectoption.setAttribute('label','Select Projects')
                                selectDiv.append(Divrow);
                                Divrow.append(Divcol3);
                                Divcol3.append(selectlabel);
                                divcol9.append(selecthSelectTag);
                                selecthSelectTag.append(selectoption);
                                Divrow.append(divcol9);
                                $('.select2-show-search').select2();
                                $.each(data.subcategories,function(index,subcategory){
                                $('#subcategory').append('<option value="'+subcategory.name+'">'+subcategory.name+'</option>');
                                })
                            }
                            else{

                                $('#selectSubCategory')?.empty();
                                if(data.subcatstatusexisting != 'statusexisting'){
                                    document.querySelector("#selectssSubCategory").classList.add("d-none");
                                }else{
                                    document.querySelector("#selectssSubCategory").classList.remove("d-none");
                                }

                            }
                            @if(setting('ENVATO_ON') == 'on')
                            //Envato Access
                            if(data.envatosuccess.length >= 1){
                                $('#envato_id').val('');
                                $('#envato_id')?.empty();
                                $('#envatopurchase .row')?.remove();
                                let selectDiv = document.querySelector('#envatopurchase');
                                let Divrow = document.createElement('div');
                                Divrow.setAttribute('class','row mt-4');
                                let Divcol3 = document.createElement('div');
                                Divcol3.setAttribute('class','col-md-3');
                                let selectlabel =  document.createElement('label');
                                selectlabel.setAttribute('class','form-label mb-0 mt-2')
                                selectlabel.innerHTML = "Envato Purchase Code <span class='text-red'>*</span>";
                                let divcol9 = document.createElement('div');
                                divcol9.setAttribute('class', 'col-md-9');
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
                                $('#hideelement').addClass('d-none');
                                $('#envato_id').val('');
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

                $(".captchabtn").click(function(e){
                    e.preventDefault();
                    $.ajax({
                        type:'GET',
                        url:'{{route('captcha.reload')}}',
                        success: function(res){
                            $(".captcha span").html(res.captcha);
                        }
                    });
                });

                $(document).ready(function() {
                    $.ajax({
                        type:'GET',
                        url:'{{route('captcha.reload')}}',
                        success: function(res){
                            $(".captcha span").html(res.captcha);
                        }
                    });
                });

                $('body').on('submit', '#guest_form', function (e) {
                    e.preventDefault();
                    $('#SubjectError').html('');
                    $('#MessageError').html('');
                    $('#EmailError').html('');
                    $('#CategoryError').html('');
                    $('#verifyotpError').html('');
                    $('#agreetermsError').html('');
                    $('#createticketbtn').html(`Loading.. <i class="fa fa-spinner fa-spin"></i>`);
                    $('#createticketbtn').prop('disabled', true);

                    var formData = new FormData(this);
                    formData.set('envato_id', licensekey);

                    let checked  = document.querySelectorAll('.required:checked').length;
                    var isValid = checked > 0;
                    if(document.querySelectorAll('.required').length == '0'){
                        ajax(formData);
                    }else{

                        if(isValid){
                            ajax(formData);
                        }else{
                            $('#createticketbtn').prop('disabled', false);
                            $('#createticketbtn').html(`{{lang('Create Ticket', 'menu')}}`);
                            toastr.error('{{lang('Check the all field(*) required', 'alerts')}}')
                        }
                    }
                });



                function ajax(formData){

                    $.ajax({
                        type:'POST',
                        dataType: "json",
                        url: SITEURL + "/guest/openticketnootp",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,

                        success: (data) => {


                            if(data.guest == 'pass'){
                                $('#SubjectError').html('');
                                $('#MessageError').html('');
                                $('#EmailError').html('');
                                $('#CategoryError').html('');
                                $('#verifyotpError').html('');
                                $('#agreetermsError').html('');
                                toastr.success(data.success);
                                if(localStorage.getItem('guestsubject') || localStorage.getItem('guestmessage') || localStorage.getItem('guestemail')){
                                    localStorage.removeItem("guestsubject");
                                    localStorage.removeItem("guestmessage");
                                    localStorage.removeItem("guestemail");

                                }
                                window.location.replace('{{url('guest/ticketdetails/')}}' + '/' + data.data.id)
                            }

                            if(data.email == 'already')
                            {
                                $('#createticketbtn').prop('disabled', false);
                                $('#createticketbtn').html(`{{lang('Create Ticket', 'menu')}}`);
                                toastr.error(data.error);
                            }

                            if(data.ticcreaterestrict == 'on')
                            {
                                $('#createticketbtn').prop('disabled', false);
                                $('#createticketbtn').html(`{{lang('Create Ticket', 'menu')}}`);
                                toastr.error(data.error);
                            }

                            if(data.message	== "domainblock"){
                                $('#createticketbtn').prop('disabled', false);
                                $('#createticketbtn').html(`{{lang('Create Ticket', 'menu')}}`);
                                $('#EmailError').html(data.error);
                            }

                            if(data.message == 'envatoerror')
                            {
                                toastr.error(data.error);
                                window.location.reload();
                            }

                            if(data.message == 'subcaterror')
                            {
                                toastr.error(data.error);
                                window.location.reload();
                            }

                        },
                        error: function(data){
                            if(data.responseJSON.message !== "Server Error"){
                                if(data.responseJSON.message === 'accessdenied'){
                                    document.write(
                                        `<div class="page error-bg">
                                            <div class="page-content m-0">
                                                <div class="container text-center">
                                                    <div class="display-1 text-danger mb-5 font-weight-bold"><i class="fa fa-ban" aria-hidden="true"></i></div>
                                                    <h1 class="h3  mb-3 font-weight-semibold">{{lang('Access Denied', 'errorpages')}}</h1>
                                                    <p class="h5 font-weight-normal mb-7 leading-normal">{{lang('It Seems Like You Are Not Authorized To Access This Page', 'errorpages')}}</p>
                                                </div>
                                            </div>
                                        </div>`
                                    );
                                }

                                toastr.error(data.responseJSON.message);
                                $('#SubjectError').html(data.responseJSON.errors.subject);
                                $('#MessageError').html(data.responseJSON.errors.message);
                                $('#EmailError').html(data.responseJSON.errors.email);
                                $('#CategoryError').html(data.responseJSON.errors.category);
                                $('#verifyotpError').html(data.responseJSON.errors.verifyotp);
                                $('#agreetermsError').html(data.responseJSON.errors.agree_terms);
                                $('#createticketbtn').html('Create Ticket');
                                $('#createticketbtn').prop('disabled', false);
                                if(data.responseJSON.errors.agree_terms) {
                                    $('#createticketbtn').html('Create Ticket');
                                    $('#createticketbtn').prop('disabled', false);
                                }
                            }
                            else{
                                toastr.error('Ticket Creation Failed, Please Create new Ticket');
                                setTimeout(()=>{
                                    window.location.reload();
                                }, 500)
                            }
                        }
                    });
                }

                // summernote
                $('.note-editable').on('keyup', function(e){

                    localStorage.setItem('guestmessage', e.target.innerHTML)
                })

                $('#subject').on('keyup', function(e){
                    if(e.target.value.length == {{setting('TICKET_CHARACTER')}}){
                        $('#subjectmaxtext').removeClass('text-muted')
                        $('#subjectmaxtext').addClass('text-red');
                    }else{
                        $('#subjectmaxtext').removeClass('text-red')
                        $('#subjectmaxtext').addClass('text-muted');
                    }
                    localStorage.setItem('guestsubject', e.target.value)
                })

                $(window).on('load', function(){
                    if(localStorage.getItem('guestsubject') || localStorage.getItem('guestmessage')){

                        document.querySelector('#subject').value = localStorage.getItem('guestsubject');
                        document.querySelector('.summernote').innerHTML = localStorage.getItem('guestmessage');
                        document.querySelector('.note-editable').innerHTML = localStorage.getItem('guestmessage');
                    }
                });

                let notifyClose = document.querySelectorAll('.notifyclose');
                notifyClose.forEach(ele => {
                    if(ele){
                        let id = ele.getAttribute('data-id');
                        if(getCookie(id)){
                            ele.closest('.alert').classList.add('d-none');
                        }else{
                            ele.addEventListener('click', setCookie);
                        }
                    }
                })


                function setCookie($event) {
                    const d = new Date();
                    let id = $event.currentTarget.getAttribute('data-id');
                    d.setTime(d.getTime() + (30 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = id + "=" + 'announcement_close' + ";" + expires + ";path=/";
                    $event.currentTarget.closest('.alert').classList.add('d-none');
                }

                function getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i < ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return '';
                }


                @if(setting('GUEST_FILE_UPLOAD_ENABLE') == 'yes')

                    var uploadedDocumentMap = {}
                    var dropzoneElements = document.querySelectorAll("#document-dropzone");
                    dropzoneElements.forEach((element)=>{
                        new Dropzone(element,{
                            url: '{{route('guest.imageupload')}}',
                            maxFiles: '{{setting('MAX_FILE_UPLOAD')}}',
                            maxFilesize: '{{setting('FILE_UPLOAD_MAX')}}', // MB
                            addRemoveLinks: true,
                            acceptedFiles: '{{setting('FILE_UPLOAD_TYPES')}}',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function (file, response) {
                                if(element.closest('form').querySelectorAll("[name='ticket[]'").length){
                                    element.closest('form').querySelectorAll("[name='ticket[]'").forEach((eleimg)=>{
                                        if(eleimg.getAttribute('orinalName') == response.original_name){
                                            toastr.error("You are already selected this.");
                                            this.removeFile(file);
                                            return;
                                        }
                                    })
                                }
                                $('form').append('<input type="hidden" name="ticket[]" orinalName="' + response.original_name + '" value="' + response.name + '">')
                                uploadedDocumentMap[file.name] = response.name
                            },
                            removedfile: function (file) {
                                file.previewElement.remove()
                                var name = ''
                                if (typeof file.file_name !== 'undefined') {
                                name = file.file_name
                                } else {
                                name = uploadedDocumentMap[file.name]
                                }
                                $('form').find('input[name="ticket[]"][value="' + name + '"]').remove()
                            },
                            init: function () {
                                @if(isset($project) && $project->document)
                                    var files =
                                        {!! json_encode($project->document) !!}
                                    for (var i in files) {
                                        var file = files[i]
                                        this.options.addedfile.call(this, file)
                                        file.previewElement.classList.add('dz-complete')
                                        $('form').append('<input type="hidden" name="ticket[]" value="' + file.file_name + '">')
                                    }
                                @endif
                            }
                        })
                    })

                @endif

                // Purchase Code Validation
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
                                    $('#productname').val(data.name);
                                    $('#productname').attr('readonly', true);
                                    $('#itemname').val(data.name);
                                    $('#hideelement').removeClass('d-none');
                                    $('#envato_id').css('border', '1px solid #02f577');
                                    $('#envato_support').val('Supported');
                                    licensekey = data.key
                                    toastr.success(data.message);
                                }
                                if(data.valid == 'expried'){
                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'on')

                                    $('.purchasecode').attr('disabled', true);
                                    $('#productname').val(data.name);
                                    $('#productname').attr('readonly', true);
                                    $('#itemname').val(data.name);
                                    $('#hideelement').removeClass('d-none');
                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                    $('#envato_support').val('Expired');
                                    toastr.error(data.message);
                                    @endif
                                    @if(setting('ENVATO_EXPIRED_BLOCK') == 'off')
                                    $('#envato_id').addClass('is-valid');
                                    $('#envato_id').attr('readonly', true);
                                    $('.purchasecode').removeAttr('disabled');
                                    $('#productname').val(data.name);
                                    $('#productname').attr('readonly', true);
                                    $('#itemname').val(data.name);
                                    $('#hideelement').removeClass('d-none');
                                    $('#envato_id').css('border', '1px solid #02f577');
                                    $('#envato_support').val('Expired');
                                    licensekey = data.key
                                    toastr.warning(data.message);
                                    @endif

                                }
                                if(data.valid == 'false'){
                                    $('.purchasecode').attr('disabled', true);
                                    $('#envato_id').css('border', '1px solid #e13a3a');
                                    toastr.error(data.message);
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
            })
		</script>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>


		@endsection
