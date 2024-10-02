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
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Create Page', 'menu')}}</span></h4>
	</div>
</div>
<!--End Page header-->

<!-- Privacy Policy & Terms of Use List -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
        <div class="card-header border-0">
			<h4 class="card-title">{{lang('Create Pages', 'menu')}}</h4>
		</div>
        <form method="POST" enctype="multipart/form-data" action="{{route('pages.storepage')}}">
            <input type="hidden" name="pages_id" id="pages_id">
            @csrf
            @honeypot
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{lang('Name')}} <span class="text-red">*</span></label>
                    <input type="text" class="form-control @error('pagename') is-invalid @enderror" name="pagename" id="pagename" >
                    @error('pagename')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{lang('Description')}} <span class="text-red">*</span></label>
                    <textarea class="form-control summernote @error('pagedescription') is-invalid @enderror"  name="pagedescription" id="pagedescription"></textarea>
                    @error('pagedescription')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-controls-stacked d-md-flex  d-md-max-block">
                        <label class="form-label mt-1 me-4">{{lang('View On:')}} <span class="text-red">*</span></label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="both">
                            <span class="custom-control-label">{{lang('View On Both')}}</span>
                        </label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="header">
                            <span class="custom-control-label">{{lang('View on header')}}</span>
                        </label>
                        <label class="custom-control form-radio success me-4">
                            <input type="radio" class="custom-control-input" name="display" value="footer">
                            <span class="custom-control-label">{{lang('View on footer')}}</span>
                        </label>
                    </div>
                    @error('display')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ lang($message) }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="switch_section">
                        <div class="switch-toggle d-flex  d-md-max-block mt-4 ms-0 ps-0">
                            <label class="form-label pe-1 me-6">{{lang('Status')}}:</label>
                            <a class="onoffswitch2">
                                <input type="checkbox"  name="status" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" "></label>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Close')}}</a> -->
                <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save')}}</button>
            </div>
        </form>
	</div>
</div>
<!-- End Privacy Policy & Terms of Use List -->

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

<!--File BROWSER -->
@vite(['resources/assets/js/form-browser.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>


<script type="text/javascript">
    $(function() {
        "use strict";

        (function($)  {

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf field
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        })(jQuery);
    })
</script>

@endsection

@section('modal')

	@include('admin.generalpage.model')

@endsection
