
@extends('layouts.adminmaster')

@section('styles')

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
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Call Action')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <!--Call Action Section -->
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <form method="POST" action="{{url('/admin/call-to-action')}}" enctype="multipart/form-data">
                @csrf

                @honeypot

                <div class="card-header border-0 d-sm-max-flex">
                    <h4 class="card-title">{{lang('Call Action Section')}}</h4>
                    <div class="card-options card-header-styles mt-sm-max-2">
                        <small class="me-1 mt-1">{{lang('Show Section')}}</small>
                        <div class="float-end mt-0">
                            <div class="switch-toggle">
                                <a class="onoffswitch2">
                                    <input type="checkbox"  name="callcheck" id="callchecks" class=" toggle-class onoffswitch2-checkbox" value="on" @if($callaction->callcheck == 'on')  checked=""  @endif>
                                    <label for="callchecks" class="toggle-class onoffswitch2-label" ></label>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" >
                    <input type="hidden" class="form-control" name="id" Value="{{$callaction->id}}">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="form-label">{{lang('Title')}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" Value="{{$callaction->title}}">
                                @error('title')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="form-label">{{lang('Subtitle')}}</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror" name="subtitle" Value="{{$callaction->subtitle}}" >
                                @error('subtitle')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="form-label">{{lang('Button-Text')}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control @error('buttonname') is-invalid @enderror" name="buttonname" Value="{{$callaction->buttonname}}">
                                @error('buttonname')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="form-label">{{lang('Button-Url')}} <span class="text-red">*</span></label>
                                <input type="text" class="form-control @error('buttonurl') is-invalid @enderror" name="buttonurl" placeholder="{{lang('www.example.com')}}" Value="{{$callaction->buttonurl}}">
                                @error('buttonurl')

                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">
                                <div class="@error('image') is-invalid @enderror">
                                    <label class="form-label">{{lang('Upload Image')}}</label>
                                    <div class="input-group file-browser">
                                        <input class="form-control " name="image" type="file" >
                                    </div>
                                    <small class="text-muted"><i>{{lang('Filesize should not be morethan 10MB', 'filesetting')}}</i></small>
                                </div>
                                @error('image')

                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ lang($message) }}</strong>
                                </span>
                                @enderror

                            </div>
                            @if($callaction->image != null)
                                <div class="file-image-1 imageshowdiv">
                                    <div class="product-image custom-ul">
                                        <a href="#">
                                            <img id="featureImage" src="{{route('getImage.url', ['imagePath' =>'uploads*callaction*'.$callaction->image,'storage_disk'=>$callaction->storage_disk ?? 'public'])}}" alt="{{$callaction->image}}" width="80" height="80">
                                        </a>
                                        <ul class="icons">
                                            <li><a href="javascript:(0);" class="bg-danger delete-image" data-id="{{ $callaction->id }}"><i class="fe fe-trash"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12 card-footer ">
                    <div class="form-group float-end">
                        <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--End Call Action Section -->
@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>


<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>

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

            // Delete call to action Image
            $('body').on('click', '.delete-image', function () {
                var callactionId = $(this).data("id");
                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might erase your records permanently', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: SITEURL + "/admin/call-to-action/imagedestroy/" +callactionId,
                            success: function (data) {
                                toastr.success(data.success);
                                $('.imageshowdiv').hide();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

        })(jQuery);
    })
</script>

@endsection
