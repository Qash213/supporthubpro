@extends('layouts.adminmaster')

@section('styles')

    <!-- INTERNAl Summernote css -->
    <link rel="stylesheet" href="{{asset('build/assets/plugins/summernote/summernote.css')}}">

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader d-flex">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Canned Response Create')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="row">
        <div class="col-xl-9 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0 d-flex">
                    <h4 class="card-title">{{lang('Canned Response Create')}}</h4>
                </div>
                <form action="{{route('admin.cannedmessages.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">{{lang('Select Type')}}</label>
                            <select id="responsetype" class="form-control select2-show-search  select2 @error('responsetype') is-invalid @enderror" data-placeholder="{{lang('Select Roles')}}" name="responsetype">
                                <option label="{{lang('Select Type')}}"></option>
                                <option  value="ticket" selected>{{lang('Ticket Response')}}</option>
                                <option  value="livechat">{{lang('Livechat Response')}}</option>
                            </select>
                            @error('responsetype')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">{{lang('Canned Response Title')}}</label>
                            <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" >
                            @error('title')

                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ lang($message )}}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">{{lang('Canned Response Message')}}</label>
                            <textarea  name="message" class="form-control summernote @error('message') is-invalid @enderror"  rows="8" cols="50"></textarea>
                            @error('message')

                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                                <div class="switch_section">
                                    <div class="switch-toggle d-flex mt-4">
                                        <label class="form-label pe-2">{{lang('Status')}}:</label>
                                        <a class="onoffswitch2">
                                            <input type="checkbox"  name="statuscanned" id="myonoffswitch18" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                            <label for="myonoffswitch18" class="toggle-class onoffswitch2-label" ></label>
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group float-end">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save')}}</button>
                        </div>
                    </div>
                <form>

            </div>

        </div>
        <div class="col-xl-3 col-lg-12 col-md-12" id="cannedreponsefielddiv">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Canned Response Fields</h4>
                </div>
                <div class="card-body pt-2 ps-0 pe-0 pb-0">
                    <div class="table-responsive tr-lastchild">
                        <table class="table mb-0 table-information">
                            <tbody>

                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{app_name}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Application Name</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{site_url}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Site URL</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{ticket_id}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Ticket ID</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{ticket_user}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Customer <b>name</b> who has opened ticket</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{ticket_title}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Ticket Title</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{ticket_priority}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Ticket Priority</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{user_reply}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Employee's <b>name</b> who reply to the ticket</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50 text-bold">@php echo '{{user_role}}' @endphp</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">The Employee's Role</span>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')

    <!-- INTERNAL Summernote js  -->
    <script src="{{asset('build/assets/plugins/summernote/summernote.js')}}"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <script type="text/javascript">
        $(function() {
            "use strict";
            // Summernote js
            $('.summernote').summernote({
                placeholder: '',
                tabsize: 1,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    // ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    // ['height', ['height']],
                    // ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen']],
                    ['help', ['help']]
                ],
                disableDragAndDrop:true,
            });

            $('.select2').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            // select2 change function
            $('#responsetype').on('change', function() {
                var option = $(this).val();
                if (option == 'livechat') {
                    $('#cannedreponsefielddiv').hide();
                } else if (option == 'ticket') {
                    $('#cannedreponsefielddiv').show();
                }
            });
        });
    </script>

@endsection
