@extends('layouts.adminmaster')

@section('styles')
    <link rel="stylesheet" href="{{ asset('build/assets/plugins/summernote/summernote.css') }}?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="{{ asset('build/assets/plugins/colorpickr/themes/nano.min.css') }}?v=<?php echo time(); ?>">
    <link href="{{ asset('build/assets/plugins/sweet-alert/sweetalert.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
    <style>
        .botresponsetime-form-group .input-group .select2-container--default .select2-selection--single {
                margin-inline-start: -1px;
                border-start-start-radius: 0;
                border-end-start-radius: 0;
        }
        .botresponsetime-form-group .input-group .select2-container .select2-selection--single {
            height: 2.5rem !important;
            width: 95px;
        }
        .botresponsetime-form-group .input-group .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px !important;
        }
        .botresponsetime-form-group .input-group .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.75rem !important;
        }
    </style>
@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span
                    class="font-weight-normal text-muted ms-2">{{ lang('Bot Response Setting', 'menu') }}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <div class="row">
        <!-- Bot Resoponse -->
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('Bot Response', 'setting')}}</h4>
                </div>
                <form method="post"  enctype="multipart/form-data" action="{{route('admin.botsettingstore')}}">
                    @csrf
                    @honeypot


                    <div class="form-group {{ $errors->has('botresponseenable') ? ' has-danger' : '' }}">
                        <div class="switch_section my-0 ps-3">
                            <div class="switch-toggle d-flex d-md-max-block mt-4">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="botresponseenable" id="botresponseenable" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('botresponseenable') == 'on') checked="" @endif>
                                    <label for="botresponseenable" class="toggle-class onoffswitch2-label" ></label>
                                </a>
                                <label class="form-label ps-3 ps-md-max-0">{{lang('Enable Bot Response', 'setting')}}</label>
                                <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you enable this setting, when the customer created tikcet, employees are not answered to that ticket below mentioned time then the "Bot Response" will send to customer.', 'setting')}})</i></small>
                            </div>

                        </div>
                        @if ($errors->has('botresponseenable'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('botresponseenable') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Bot Name')}} <span class="text-red">*</span></label>
                                    <input type="text" class="form-control @error('bot_name') is-invalid @enderror" placeholder="{{lang('Bot Name')}}" name="bot_name" value="{{setting('bot_name')}}" id="bot_name">
                                    <span id="bot_nameError" class="text-danger alert-message"></span>
                                    @error('bot_name')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="form-group botresponsetime-form-group">
                                    <label class="form-label">{{lang('Bot Response Time')}} ({{lang('In Minutes / Hours')}}) <span class="text-red">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type="number" maxlength="2" class="form-control input-group-prepend @error('botsresponse_time') is-invalid @enderror"  name="botsresponse_time"  value="{{old('botsresponse_time', setting('botsresponse_time')) }}">
                                        <div>
                                            <select class="custom-select select2" name="time_detection" id="time_detection">
                                                <option value="mintutes" {{ setting('time_detection') == 'mintutes' ? 'selected' : '' }}>{{ lang('Mintutes') }}</option>
                                                <option value="hours" {{ setting('time_detection') == 'hours' ? 'selected' : '' }}>{{ lang('Hours') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <span id="botsresponse_timeError" class="text-danger alert-message"></span>
                                    @error('botsresponse_time')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Working Hours Response')}} <span class="text-red">*</span></label>
                                    <textarea class="summernote d-none @error('response_description') is-invalid @enderror" name="response_description" aria-multiline="true">{{setting('response_description')}}</textarea>
                                    @error('response_description')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                    <span id="response_descriptionError" class="text-danger alert-message"></span>
                                    @error('response_description')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Non-Working Hours Response')}} <span class="text-red">*</span></label>
                                    <textarea class="summernote d-none @error('response_description_exclude_business_hours') is-invalid @enderror" name="response_description_exclude_business_hours" aria-multiline="true">{{setting('response_description_exclude_business_hours')}}</textarea>
                                    @error('response_description_exclude_business_hours')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                    <span id="response_description_exclude_business_hoursError" class="text-danger alert-message"></span>
                                    @error('response_description_exclude_business_hours')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">{{lang('Bot Image')}}</label>
                            <div class="input-group file-browser">
                                <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" accept="image/png, image/jpeg,image/jpg" >

                                @error('image')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                                @enderror

                            </div>
                            <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                        </div>
                        @if (setting('bot_image') != null)
                            <div class="file-image-1">
                                <div class="product-image custom-ul">
                                    <a href="#">
                                        <img src="{{asset('public/uploads/profile/botprofile/' .setting('bot_image'))}}" class="br-5" alt="{{setting('bot_image')}}">
                                    </a>
                                    <ul class="icons">
                                        <li><a href="javascript:(0);" class="bg-danger delete-image"><i class="fe fe-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer clearfix">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <label class="form-label">{{lang('Bot Dynamic fields')}}</label>
                            <div class="d-flex">
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_id &#125;&#125; </span>
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_username &#125;&#125; </span>
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_title &#125;&#125; </span>
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_status &#125;&#125; </span>
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_customer_url &#125;&#125; </span>
                                    <span class="badge bg-light text-dark me-2"> &#123;&#123; ticket_admin_url &#125;&#125; </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="form-group float-end mb-0 btn-list">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Bot Resoponse -->
    </div>




@endsection

@section('scripts')
    <!-- INTERNAL Summernote js  -->
    <script src="{{ asset('build/assets/plugins/summernote/summernote.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Index js-->
    @vite(['resources/assets/js/support/support-sidemenu.js'])
    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL color pickr -->
    <script src="{{ asset('build/assets/plugins/colorpickr/pickr.min.js') }}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}?v=<?php echo time(); ?>"></script>



    <script type="text/javascript">
        $(function() {
            "use strict";
            $('.summernote').summernote({
                placeholder: '',
                tabsize: 1,
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline',
                    'clear']], // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']], // ['height', ['height']],
                    ['view', ['fullscreen']],
                    ['help', ['help']]
                ],
            });

            //Delete Image
            $('body').on('click', '.delete-image', function () {

                swal({
                    title: `{{lang('Are you sure you want to remove the Bot profile image?', 'alerts')}}`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                        $.ajax({
                            type: "post",
                            // url: SITEURL + "/admin/botimagedelete/",
                            url: "{{ route('admin.botimagedelete') }}",
                            // url: route('admin.botimagedelete'),
                            success: function (data) {
                                toastr.success(data.success);
                                location.reload();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        })
    </script>
@endsection

