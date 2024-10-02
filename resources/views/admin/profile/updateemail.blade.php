@extends('layouts.custommaster')
@section('content')
    <div class="pb-0 px-5 pt-0 text-center">
        <h3 class="mb-2">{{lang('Update Your Email?')}}</h3>
        <p class="text-muted fs-13 mb-1">{{lang('Enter the new email address to update.')}}</p>
    </div>
    <form class="card-body pt-3 pb-0" enctype="multipart/form-data" id="emailstore" method="POST">
        @csrf

        @honeypot

        <input type="hidden" name="oldemail" id="oldemail" value="{{$oldemail}}">
        <div class="form-group">
            <label class="form-label">{{lang('Enter Your New Email')}}</label>
            <input class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{lang('Email')}}" type="email">
            @error('email')

                <span class="invalid-feedback" role="alert">
                    <strong>{{ lang($message) }}</strong>
                </span>
            @enderror

        </div>
        <div class="submit">
            <button class="btn btn-secondary btn-block" type="submit" id="emailbtnsave">{{lang('Submit')}}</button>
        </div>
        <!-- <div class="text-center mt-4">
            <p class="text-dark mb-0"><a class="text-primary ms-1" href="{{url('/')}}">{{lang('Send me Back')}}</a></p>
        </div> -->
    </form>
    <form class="card-body pt-3 pb-0 newone d-none" id="newone" action="{{route('adminemail.updateotpverify',$oldemail)}}" method="post">
        @csrf

        @honeypot

        <div class="form-group">
            <label class="form-label">{{lang('Enter OTP')}}</label>
            <input class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" placeholder="{{lang('OTP')}}" type="email">
            @error('otp')

                <span class="invalid-feedback" role="alert">
                    <strong>{{ lang($message) }}</strong>
                </span>
            @enderror

        </div>
        <div class="submit">
            <input class="btn btn-secondary btn-block" type="submit" value="{{lang('Submit')}}" onclick="this.disabled=true;this.form.submit();">
        </div>
        <!-- <div class="text-center mt-4">
            <p class="text-dark mb-0"><a class="text-primary ms-1" href="{{url('/')}}">{{lang('Send me Back')}}</a></p>
        </div> -->
    </form>
@endsection

@section('modal')

	@include('admin.profile.emailupdatemodal')

@endsection

@section('scripts')

    <!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])


<script type="text/javascript">
    $(function() {
        "use strict";

        (function($){

            // Variables
            var SITEURL = '{{url('')}}';
            let err =  {!! json_encode( Session::get('error') ) !!}
            if (err == 'Invalid OTP') {
                document.getElementById("emailstore").classList.add("d-none");
                document.getElementById("newone").classList.remove("d-none");
            }

            // Csrf Field
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('submit', '#emailstore', function (e) {
                e.preventDefault();

                var fewSeconds = 2;
                $('#emailbtnsave').html('<i class="fa fa-spinner fa-spin"></i>');
                $('#emailbtnsave').prop('disabled', true);
                    setTimeout(function(){
                        $('#emailbtnsave').prop('disabled', false);
                    }, fewSeconds*1000);
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: SITEURL + "/admin/adminnewemailstore",
                    data: formData,
                    success: function (data) {
                        if(data?.email == 'already'){
                            location.reload();
                            toastr.error(data.error);
                        }

                        if(data?.otp == 'exists'){
                            document.getElementById("emailstore").classList.add("d-none")
                            document.getElementById("newone").classList.remove("d-none")
                            toastr.success(data.success);
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                        if(data?.responseJSON?.errors?.email[0]){
                            // location.reload();
                            toastr.error("{{lang('The email ID you have entered is invalid. Please enter a valid email ID to update your email address.')}}");
                        }
                    }
                });
            });

        })(jQuery);
    })
</script>

@endsection
