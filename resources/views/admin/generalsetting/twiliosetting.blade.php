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


@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Twilio Settings', 'menu')}}</span></h4>
	</div>
</div>
<!--End Page header-->

<!--Twilio Settings -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card">

		<form method="post"  enctype="multipart/form-data" action="{{route('admin.twiliosettingstore')}}">
            @csrf
            @honeypot

            <div class="card-header border-0">
                <h4 class="card-title">{{lang('Twilio Settings')}}</h4>
                <div class="card-options card-header-styles">
                    <div class="form-group {{ $errors->has('twilioenable') ? ' has-danger' : '' }}">
                        <div class="switch_section my-0 ps-3">
                            <div class="switch-toggle d-flex d-md-max-block mt-4">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="twilioenable" id="twilioenable" class=" toggle-class onoffswitch2-checkbox" value="yes" @if(setting('twilioenable') == 'on') checked="" @endif>
                                    <label for="twilioenable" class="toggle-class onoffswitch2-label" ></label>
                                </a>
                            </div>

                        </div>
                        @if ($errors->has('twilioenable'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('twilioenable') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Twilio Authentication Id')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('twilio_auth_id') is-invalid @enderror" placeholder="{{lang('Twilio Authentication Id')}}" name="twilio_auth_id" value="{{setting('twilio_auth_id')}}" id="twilio_auth_id">
                            <span id="twilio_auth_idError" class="text-danger alert-message"></span>
                            @error('twilio_auth_id')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Twilio Authentication Token')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('twilio_auth_token') is-invalid @enderror" placeholder="{{lang('Twilio Authentication Token')}}" name="twilio_auth_token" value="{{setting('twilio_auth_token')}}" id="twilio_auth_token">
                            <span id="twilio_auth_tokenError" class="text-danger alert-message"></span>
                            @error('twilio_auth_token')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{lang('Twilio Phone Number')}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control @error('twilio_auth_phone_number') is-invalid @enderror" placeholder="{{lang('Twilio Phone Number')}}" name="twilio_auth_phone_number" value="{{setting('twilio_auth_phone_number')}}" id="twilio_auth_phone_number">
                            <span id="twilio_auth_phone_numberError" class="text-danger alert-message"></span>
                            @error('twilio_auth_phone_number')

                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror
                        </div>
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
<!-- End Twilio Settings -->

<!-- Email Template List -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card ">
        <div class="card-header border-0">
            <h4 class="card-title">{{ lang('Message Template', 'menu') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered border-bottom text-nowrap w-100" id="support-articlelists">
                    <thead>
                        <tr>
                            <th width="10">{{ lang('Sl.No') }}</th>
                            <th>{{ lang('Title') }}</th>
                            <th>{{ lang('Last Updated on') }}</th>
                            <th>{{ lang('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($smstemplates as $emailtemplate)
                            <tr id="row_{{ $emailtemplate->id }}">
                                <td>{{ $i++ }}</td>
                                <td>{{ $emailtemplate->title }}</td>
                                <td>{{ $emailtemplate->updated_at }}</td>
                                <td>
                                    <div class = "d-flex">
                                        @can('Email Template Edit')
                                            <a href="{{ route('admin.smstemplate.edit', $emailtemplate->id) }}"
                                                class="action-btns1">
                                                <i class="feather feather-edit text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ lang('Edit') }}"></i>
                                            </a>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Email Template List -->

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

<script type="text/javascript">
    $(function() {
        "use strict";

        let prev = {!! json_encode(lang('Previous')) !!};
        let next = {!! json_encode(lang('Next')) !!};
        let nodata = {!! json_encode(lang('No data available in table')) !!};
        let noentries = {!! json_encode(lang('No entries to show')) !!};
        let showing = {!! json_encode(lang('showing page')) !!};
        let ofval = {!! json_encode(lang('of')) !!};
        let maxRecordfilter = {!! json_encode(lang('- filtered from ')) !!};
        let maxRecords = {!! json_encode(lang('records')) !!};
        let entries = {!! json_encode(lang('entries')) !!};
        let show = {!! json_encode(lang('Show')) !!};
        let search = {!! json_encode(lang('Search...')) !!};
        // Datatable
        $('#support-articlelists').dataTable({
            order: [],
            responsive: true,
            language: {
                searchPlaceholder: search,
                scrollX: "100%",
                sSearch: '',
                paginate: {
                    previous: prev,
                    next: next
                },
                emptyTable: nodata,
                infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                infoEmpty: noentries,
                lengthMenu: `${show} _MENU_ ${entries} `,
            },
        });

        // select2 js in datatable
        $('.form-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
    })
</script>


@endsection
