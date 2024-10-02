@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}?v=<?php echo time(); ?>">

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('FAQ’s', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->

<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
			<h4 class="card-title">{{lang('Edit FAQ’s', 'menu')}}</h4>
		</div>
        <form action="{{route('faq.store')}}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="faq_id" id="faq_id" value="{{$faq->id}}">
            @csrf
            @honeypot
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Question')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('question') is-invalid @enderror" placeholder="{{lang('FAQ Question')}}" name="question" id="question" value="{{$faq->question}}" autofocus required>
                    @error('question')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{lang('Answer')}} <span class="text-red">*</span></label>
                    <textarea class="summernote d-none @error('answer') is-invalid @enderror" placeholder="{{lang('FAQ Answer')}}" name="answer" id="answer" aria-multiline="true">{{$faq->answer}}</textarea>
                    @error('answer')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Select Faq Category')}} <span class="text-red">*</span></label>
                    <!-- <select name="faqcat_name" class="form-control form-select" id="faqcat_name" data-placeholder="{{lang('Select Faq Category')}}"> -->
                    <select class="form-control select2-show-search  select2 @error('faqcatsname') is-invalid @enderror" data-placeholder="{{lang('Select Faq Category')}}" name="faqcatsname" id="faqcatsname">
                        @foreach($faqcategorys as $faqcategory)
                            <option></option>
                            <option value="{{$faqcategory->id}}" @if ($faqcategory->id == $faq->faqcat_id) selected @endif >{{$faqcategory->faqcategoryname}}</option>
                        @endforeach
                    </select>
                    @error('faqcatsname')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex mt-4">
                            <label class="form-label pe-2">{{lang('Status')}}</label>
                            <a class="onoffswitch2">
                                <input type="checkbox"  name="status" id="status" class=" toggle-class onoffswitch2-checkbox" {{ $faq->status == 1 ? 'checked' : '' }}>
                                <label for="status" class="toggle-class onoffswitch2-label" ></label>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="custom-control form-checkbox">
                        <input type="checkbox" class="custom-control-input" name="privatemode" id="privatemode"  {{ $faq->privatemode == 1 ? 'checked' : '' }}>
                        <span class="custom-control-label">{{lang('Privacy Mode')}}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-danger" id="btnclose" onclick="cancelPost()" data-bs-dismiss="modal">{{lang('Close')}}</a>
                <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Updating <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Update')}}</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('modal')

@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

<!-- INTERNAL Summernote js  -->
<script src="{{asset('build/assets/plugins/summernote/summernote.js')}}"></script>

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])
@vite(['resources/assets/js/support/support-articles.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script type="text/javascript">
    $(function() {
        "use strict";

        (function($)  {

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let prev = {!! json_encode(lang("Previous")) !!};
            let next = {!! json_encode(lang("Next")) !!};
            let nodata = {!! json_encode(lang("No data available in table")) !!};
            let noentries = {!! json_encode(lang("No entries to show")) !!};
            let showing = {!! json_encode(lang("showing page")) !!};
            let ofval = {!! json_encode(lang("of")) !!};
            let maxRecordfilter = {!! json_encode(lang("- filtered from ")) !!};
            let maxRecords = {!! json_encode(lang("records")) !!};
            let entries = {!! json_encode(lang("entries")) !!};
            let show = {!! json_encode(lang("Show")) !!};
            let search = {!! json_encode(lang("Search...")) !!};
            // Datatable
            $('#support-articlelists').dataTable({
                language: {
                    searchPlaceholder: search,
                    scrollX: "100%",
                    sSearch: '',
                    paginate: {
                    previous: prev,
                    next: next
                    },
                    emptyTable : nodata,
                    infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                    info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                    infoEmpty: noentries,
                    lengthMenu: `${show} _MENU_ ${entries} `,
                },
                order:[],
                columnDefs: [
                    { "orderable": false, "targets":[ 0,1,4] }
                ],
            });

        })(jQuery);
    })
</script>

@endsection
