@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL multiselecte css-->
<link href="{{asset('build/assets/plugins/multipleselect/multiple-select.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/multi/multi.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAl color css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/colorpickr/themes/nano.min.css')}}?v=<?php echo time(); ?>">

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}?v=<?php echo time(); ?>">

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

<!-- INTERNAl Tag css -->
<link href="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">
            <span class="font-weight-normal text-muted ms-2">{{lang('Customers', 'menu')}}</span>
        </h4>
    </div>
    <div class="page-rightheader ms-md-auto">
        <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
            <div class="d-flex">
                <div class="btn-list">
                    @can('Custom Notifications Employee')

                    <a href="{{route('mail.employee')}}" class="btn btn-success">{{lang('Compose for Employees')}}</a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">{{lang('Compose Notification For Customers')}}</h3>
            </div>
            <form action="{{route('mail.customersend')}}" method="post" onsubmit="submitCustomer()">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label class="form-label">{{lang('To')}} <span class="text-red">*</span></label>
                                <div class="custom-controls-stacked d-md-flex @error('users') is-invalid @enderror"  id="projectdisable">
                                    <select multiple="multiple" class="filter-multi"  id="users"  name="users[]" >
                                        @foreach ($users as $item)

                                            <option value="{{$item->id}}" @if(old('users') == $item->id) selected @endif>{{$item->username}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                @error('users')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label class="form-label">{{lang('Subject')}} <span class="text-red">*</span></label>

                                <input type="text" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject')}}" name="subject">
                                @error('subject')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class=" align-items-center">
                                        <label class="  form-label">{{lang('Tag')}} <span class="text-red">*</span></label>
                                        <input type="text" class="form-control @error('tag') is-invalid @enderror" value="{{old('tag')}}" name="tag" data-role="tagsinput">
                                        @error('tag')

                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                    <label class="form-label">{{lang('Select Tag Color')}} <span class="text-red">*</span></label>
                                    <div>
                                        <input type="text" class="form-control @error('selecttagcolor') is-invalid @enderror" value="rgba(116, 53, 53, 1)" name="selecttagcolor" id="selecttagcolor">
                                        @error('selecttagcolor')

                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ lang($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label class="col-sm-2 form-label">{{lang('Message')}} <span class="text-red">*</span></label>

                                <textarea rows="10" class="summernote form-control @error('message') is-invalid @enderror" name="message">{{old('message')}}</textarea>
                                @error('message')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-sm-flex">
                    <div class="btn-list ms-auto">
                        <button id="customer-submit" type="submit" class="btn btn-primary btn-space">{{lang('Send Message')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




@endsection

@section('scripts')

    <!-- INTERNAL multiselecte js-->
<script src="{{asset('build/assets/plugins/multipleselect/multiple-select.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/multipleselect/multi-select.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL color pickr -->
<script src="{{ asset('build/assets/plugins/colorpickr/pickr.min.js') }}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Summernote js  -->
<script src="{{asset('build/assets/plugins/summernote/summernote.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<!-- INTERNAL TAG js-->
<script src="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.js')}}?v=<?php echo time(); ?>"></script>

<script type="text/javascript">
    $(function() {
        "use strict";

        // Summernote
        $('.summernote').summernote({
            placeholder: '',
            tabsize: 1,
            height: 200,
            toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']], ['fontsize', ['fontsize']], ['color', ['color']], ['para', ['ul', 'ol', 'paragraph']], // ['height', ['height']],
            ['table', ['table']], ['insert', ['link']], ['view', ['fullscreen']], ['help', ['help']]],
            callbacks: {
                onImageUpload: function(e){}
            },
        });

        (() => {

            //  color pickr code
            // Simple example, see optional options for more configuration.
            window.setColorPicker = (elem, defaultValue) => {
                elem = document.querySelector(elem);
                let pickr = Pickr.create({
                    el: elem,
                    default: defaultValue,
                    theme: 'nano', // or 'monolith', or 'nano'
                    useAsButton: true,
                    swatches: [
                        '#217ff3',
                        '#11cdef',
                        '#fb6340',
                        '#f5365c',
                        '#f7fafc',
                        '#212529',
                        '#2dce89'
                    ],
                    components: {
                        // Main components
                        preview: true,
                        opacity: true,
                        hue: true,
                        // Input / output Options
                        interaction: {
                            hex: true,
                            rgba: true,
                            // hsla: true,
                            // hsva: true,
                            // cmyk: true,
                            input: true,
                            clear: true,
                            silent: true,
                            preview: true,
                        }
                    }
                });
                pickr.on('init', pickr => {
                    elem.value = pickr.getSelectedColor().toRGBA().toString(0);
                }).on('change', color => {
                    elem.value = color.toRGBA().toString(0);
                });

                return pickr;

            }

            // Color Pickr variables
            let selecttagcolor = setColorPicker('#selecttagcolor', document.querySelector('#selecttagcolor').value);

        })();
    })

    function submitCustomer() {
        $('#customer-submit').html(`Sending.. <i class="fa fa-spinner fa-spin"></i>`);
        $('#customer-submit').prop('disabled', true);
    }
</script>

@endsection

