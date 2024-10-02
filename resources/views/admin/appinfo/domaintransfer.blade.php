@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Summernote css -->
<link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}?v=<?php echo time(); ?>">

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<style>
    .btn-br-begin-0{
        border-start-start-radius: 0;
        border-end-start-radius: 0;
    }
</style>
@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Domain Transfer', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->

<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card custom-card mb-7">
            <div class="card-header border-0 block">
                <div class="card-title">
                    {{lang('Download Complete Files')}}
                </div>
            </div>
            <div class="card-body pt-0">
                <span class="badge bg-danger steps-badge">{{lang('Step')}}: 1
                </span>
                <p>{{lang('Transfer all website files, including images, scripts, and other assets, via FTP.')}}</p>
                <button fileExt="{{ env('APP_NAME') . '.zip' }}" downloadhref="{{ route('admin.downloadProject') }}" class="btn btn-primary downloadfilesbtn">{{lang('Download FTP Files')}}</button>
            </div>
        </div>
        <div class="card custom-card mb-7">
            <div class="card-header border-0">
                <div class="card-title">
                    {{lang('Download Database')}}
                </div>
            </div>
            <div class="card-body pt-0">
                <span class="badge bg-danger steps-badge">{{lang('Step')}}: 2
                </span>
                <p>{{lang('Transfer the website\'s database containing crucial information such as content, user data, and settings.')}}</p>
                <button fileExt="{{ env('DB_DATABASE') . '.sql' }}" downloadhref="{{ route('admin.exportDatabase') }}" class="btn btn-primary downloadfilesbtn" >{{lang('Export')}}</button>
            </div>
        </div>
        <div class="card custom-card">
            <div class="card-header border-0">
                <div class="card-title">
                    {{lang('Migration Token')}}
                </div>
            </div>
            <div class="card-body pt-1">
                <span class="badge bg-danger steps-badge">{{lang('Step')}}: 3
                </span>
                <p>
                    {{lang('The Migration Token is a unique identifier generated for seamless transfer of your application from one domain to another. This token ensures a smooth transition by securely authorizing the migration process, allowing you to effortlessly transfer your website, including files and databases, to your desired destination. Additionally, upon activation of the new domain, the Migration Token automatically cancels the license associated with the previous domain, ensuring that access to the application is restricted exclusively to the newly activated domain.')}}
                </p>
                <div class="bg-warning-transparent p-2 text-warning mb-3 rounded  text-dark">
                    <span class="font-weight-semibold">{{lang('Note')}}:</span> {{lang('Once the new domain is activated, the previous domain\'s license is automatically revoked, preventing access to the application from the old domain.')}}
                </div>
                <div class="p-2 border rounded">
                    {{lang('Note')}}:{{lang('To Receive a mail should need to complete "SMTP" Setup')}}.
                </div>
            </div>
            <div class="card-footer">
                    <div id="aftertokengeneratediv">
                        <span class="mx-auto">
                            <div class="d-flex">
                                <div class="d-none" id="tokengendiv">
                                    <span class="mt-2"> <b>{{ lang('Token') }}</b> : </span>
                                    <h5 style="display : inline-block" id="tokenvalue" class="ms-2 pt-2"></h5>
                                </div>
                                <div id="generateandviewdiv">
                                    @if(setting('isToken') == 1)
                                        <div class="d-flex align-items-center">
                                            <div class="alert bg-warning-transparent text-dark p-2 mb-0 token-alert w-75" role="alert">
                                                ************
                                            </div>
                                            <button id="showtoken" class="btn btn-sm btn-primary btn-br-begin-0"><i class="feather feather-eye "></i></button>
                                        </div>
                                    @else
                                        <div id="beforetokengeneratediv" class="ms-2 mb-5">
                                            <a href="javascript:void(0);" class="btn btn-primary mx-auto d-flex float-end" id="generatingid">{{ lang('Generate Token') }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </span>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal effect-scale fade" id="filesdownlodingmodal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-body px-6 py-5 text-center">
                <div class="mb-5 text-center">
                    <div class="mb-5">
                        <span class="avatar avatar-xl brround bg-warning-transparent"><i
                                class="ri-alert-line fw-normal"></i></span>
                    </div>
                    <div>
                        <h4 class="fw-semibold mb-1">
                            <strong>{{ lang('This may take a while, please wait...') }} <i class="fa fa-spinner fa-spin"></i></strong>
                        </h4>
                        <p>{{ lang('Do not Refresh or Do not go back while downloading files.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        (function($)  {
            "use strict";

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#generatingid').on('click', function() {
                $('#generatingid').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('admin.tokenGenerate') }}',
                    success: function(data) {

                        $('#tokengendiv').removeClass('d-none');
                        $('#generateandviewdiv').addClass('d-none');
                        $('#tokenvalue').html(data);
                        toastr.success('{{ lang('Token Generated Successfully.', 'alerts') }}');
                    },
                    error: function(data) {
                        if(data.responseJSON?.error){
                            toastr.error(data.responseJSON?.error);
                            location.reload();
                        }
                    }
                });
            });

            $('.downloadfilesbtn').on('click', function() {
                $('#filesdownlodingmodal').modal('show');

                var downloadLink = $(this).attr('downloadhref');
                var fileExtData = $(this).attr('fileExt');

                var xhr = new XMLHttpRequest();
                xhr.open('GET', downloadLink, true);
                xhr.responseType = 'blob';

                let saveOrOpenBlob = function(blob) {
                    var fileName = fileExtData;
                    var tempEl = document.createElement("a");
                    document.body.appendChild(tempEl);
                    tempEl.style = "display: none";
                    var url = window.URL.createObjectURL(blob);
                    tempEl.href = url;
                    tempEl.download = fileName;
                    tempEl.click();
                    window.URL.revokeObjectURL(url);
                }

                xhr.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percentComplete = Math.round((e.loaded / e.total) * 100);
                    $('.progress-bar').css('width', percentComplete + '%');
                }
                };

                xhr.onload = function() {
                    var blob = xhr.response;
			        saveOrOpenBlob(blob);
                    toastr.success('{{ lang('Download Completed successfully.', 'alerts') }}');
                    $('#filesdownlodingmodal').modal('hide');

                };

                xhr.send();
            });

            $('#showtoken').on('click', function() {
                $('#showtoken').html('<i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('admin.requesttoken') }}',
                    success: function(data) {
                        $('#tokengendiv').removeClass('d-none');
                        $('#generateandviewdiv').addClass('d-none');
                        $('#tokenvalue').html(data.token);
                        toastr.success('{{ lang('Token fetched Successfully.', 'alerts') }}');
                    },
                    error: function(data) {
                        console.log('error',data);
                    }
                });
            });

            $('.form-select').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

        })(jQuery);
    })
</script>

@endsection
