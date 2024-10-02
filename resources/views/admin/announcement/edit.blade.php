@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}?v=<?php echo time(); ?>">

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Datepicker css-->
<link href="{{asset('build/assets/plugins/modal-datepicker/datepicker.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAl color css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/colorpickr/themes/nano.min.css')}}?v=<?php echo time(); ?>">

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Announcements', 'menu')}}</span></h4>
	</div>
</div>
<!--End Page header-->

<!-- Add Announcement -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Edit Announcement')}}</h4>
		</div>
        <form method="POST" enctype="multipart/form-data" id="testimonial_form" name="testimonial_form">
            <input type="hidden" name="testimonial_id" value="{{$announcementData->id}}" id="$announcementData->id">
            @csrf
            @honeypot
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="title" id="name" value="{{$announcementData->title}}">
                    <span id="nameError" class="text-danger alert-message"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Notice Text')}}</label>
                    <textarea class="form-control"  name = "notice" id="description" >{{$announcementData->notice}}</textarea>
                    <span id="descriptionError" class="text-danger alert-message"></span>
                </div>

                <div class="row">
                    <h5>{{lang('Reference Button')}} : </h5>
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Button Lable', 'general')}} <span>({{ lang('optional') }})</span></label>
                            <input class="form-control" name="buttonlable" type="text" value="{{$announcementData->buttonlable}}" id="buttonlable">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Button Url', 'general')}} <span>({{ lang('optional') }})</span></label>
                            <input class="form-control" name="buttonurl" type="text" value="{{$announcementData->buttonurl}}" id="buttonurl">
                            <span id="buttonurlError" class="text-danger alert-message"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h4>{{lang('Announcement Days')}} : </h4>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Start Date')}}: <span class="text-red">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="feather feather-calendar"></i>
                                </div>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" value="{{ $announcementData->startdate != null ? \Carbon\Carbon::parse($announcementData->startdate)?->format('Y-m-d') : '' }}" name="startdate" id="startdate" autocomplete="off" readonly>
                            </div>
                            <span id="startdateError" class="text-danger alert-message"></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('End Date')}}: <span class="text-red">*</span></label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="feather feather-calendar"></i>
                                </div>
                                <input class="form-control fc-datepicker" placeholder="YYYY-MM-DD" type="text" value="{{ $announcementData->enddate ? \Carbon\Carbon::parse($announcementData->enddate)?->format('Y-m-d') : '' }}" name="enddate" id="enddate"  autocomplete="off" readonly>
                            </div>
                            <span id="enddateError" class="text-danger alert-message"></span>
                        </div>
                    </div>
                    <span class="text-center">Or</span>
                    <div class="form-group">
                        <label class="form-label">{{lang('Repeated Announcement Day')}} : <span class="text-red">*</span></label>
                        <select class="form-control custom-select form-select @error('announcementday') is-invalid @enderror" data-placeholder="{{lang('Select Day')}}" multiple name="announcementday[]" id="announcementday">
                            <option value=""></option>
                            @foreach($normalDay as $normalDays)
                                @if(in_array($normalDays , $announceDay))
                                    <option value="{{$normalDays}}" selected>{{$normalDays}}</option>
                                @else
                                    <option value="{{$normalDays}}">{{$normalDays}}</option>
                                @endif
                            @endforeach

                        </select>
                        @error('announcementday')

                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ lang($message) }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Background Color', 'general')}} <span class="text-red">*</span></label>
                            <input class="form-control" name="primary_color" type="text" value="{{ $announcementData->primary_color != null ? $announcementData->primary_color : '' }}" id="primary_color-input">

                            @if ($errors->has('primary_color'))

                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('primary_color') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="" class="form-label">{{lang('Text Color', 'general')}} <span class="text-red">*</span></label>
                            <input class="form-control" name="secondary_color" type="text" value="{{ $announcementData->secondary_color != null ? $announcementData->secondary_color : '' }}" id="secondary_color-input">

                            @if ($errors->has('secondary_color'))

                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('secondary_color') }}</strong>
                                </span>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                            <label class="form-label pe-2">{{lang('Status')}}</label>
                            <a class="onoffswitch2">
                                <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1"  {{$announcementData->status == 1 ? 'checked' : ''}}>
                                <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" ></label>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Save Changes')}}</button>
            </div>
        </form>
	</div>
</div>
<!-- End Add Announcement -->




@endsection

@section('modal')

@include('admin.announcement.model')

@endsection

@section('scripts')

<!-- INTERNAL Summernote js  -->
<script src="{{asset('build/assets/plugins/summernote/summernote.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])
@vite(['resources/assets/js/support/support-articles.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>

<script src="{{asset('build/assets/plugins/jquery/jquery-ui.js')}}?v=<?php echo time(); ?>"></script>

 <!-- INTERNAL color pickr -->
 <script src="{{ asset('build/assets/plugins/colorpickr/pickr.min.js') }}?v=<?php echo time(); ?>"></script>


<!-- INTERNALdatepicker js-->

<script type="text/javascript">
    $(function() {
        (function($)  {
            "use strict";

            // Variables
            var SITEURL = '{{url('')}}';
            var now = Date.now();

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
            let themeColor = setColorPicker('#primary_color-input', document.querySelector('#primary_color-input').value);
            let themeColorDark = setColorPicker('#secondary_color-input', document.querySelector('#secondary_color-input').value);

            // Csrf Field
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            setTimeout(()=>{
                document.querySelector(".select2-selection--multiple").onclick = ()=>{
                    $('#startdate').val('');
                    $('#enddate').val('');
                    if(document.getElementById("extraoption")){
                        document.getElementById("extraoption").remove()
                    }
                }
            }, 1)

            $('body').on('click', '#startdate', function(e){
                document.querySelectorAll(".select2-selection__choice",".select2-results__option--highlighted").forEach((ele)=>{
                    ele.remove()
                })
            });

            $('body').on('click', '#enddate', function(e){
                document.querySelectorAll(".select2-selection__choice").forEach((ele)=>{
                    ele.remove()
                })
            });


            // Datepicker
            $('#startdate').datepicker({
                dateFormat: 'yy-mm-dd',
                prevText: '<i class="fa fa-angle-left"></i>',
                nextText: '<i class="fa fa-angle-right"></i>',
                minDate: 0,
                firstDay: {{setting('start_week')}},

                onSelect: function (selectedDate) {

                    var diff = ($("#enddate").datepicker("getDate") -
                        $("#startdate").datepicker("getDate")) /
                        1000 / 60 / 60 / 24 + 1; // days
                    if ($("#enddate").datepicker("getDate") != null) {
                        $('#count').html(diff);
                        $('#days').val(diff);
                    }
                    $('#enddate').datepicker('option', 'minDate', selectedDate);
                }
            });
            $('#enddate').datepicker({
                dateFormat: 'yy-mm-dd',
                prevText: '<i class="fa fa-angle-left"></i>',
                nextText: '<i class="fa fa-angle-right"></i>',
                firstDay: {{setting('start_week')}},
                onSelect: function (selectedDate) {

                    $('#startdate').datepicker('option', 'maxDate', selectedDate);

                    var diff = ($("#enddate").datepicker("getDate") -
                        $("#startdate").datepicker("getDate")) /
                        1000 / 60 / 60 / 24 + 1; // days
                    if ($("#startdate").datepicker("getDate") != null) {
                        $('#count').html(diff);
                        $('#days').val(diff);
                    }
                }
            });

            // Select2
            $('.form-select').select2({
                multiple: true,
                minimumResultsForSearch: Infinity,
                width:'100%',
                closeOnSelect: false
            });


            $('#description').summernote({
                height: 100,
                toolbar:[["style",["style"]],["font",["bold","underline","clear"]],["fontname",["fontname"]],["fontsize",["fontsize"]],["color",["color"]],["para",["ul","ol","paragraph"]],["table",["table"]],["insert",["link"]],["view",["fullscreen"]],["help",["help"]]],
            });

            // Announcement submit form
            $('body').on('submit', '#testimonial_form', function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                $('#btnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    type:'POST',
                    url: SITEURL + "/admin/announcement/update",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#btnsave').html('{{lang('Save Changes')}}');
                        toastr.success(data.success);
                        window.location.replace("{{url('/admin/announcement')}}");
                    },
                    error: function(data){
                        $('#btnsave').html('{{lang('Save Changes')}}');
                        if(data.responseJSON.error){
                            toastr.error(data.responseJSON.error);
                        }
                        $('#nameError').html(data.responseJSON.errors.title);
                        $('#descriptionError').html(data.responseJSON.errors.notice);
                        $('#startdateError').html(data.responseJSON.errors.startdate);
                        $('#enddateError').html(data.responseJSON.errors.enddate);
                    }
                });
            });

        })(jQuery);
    })
</script>

@endsection
