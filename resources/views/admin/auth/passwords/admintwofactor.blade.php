@extends('layouts.custommaster')
@section('content')
    <div class="pb-0 px-5 pt-0 text-center">
        <h3 class="mb-2">{{lang('2 - Step Verification')}}</h1>
    </div>
    <form class="card-body pt-3 pb-0" action="{{route('user.emp2faotpverify')}}" method="get">
        @csrf
        <input type="hidden" value ="{{$email}}" name="email">

        @honeypot

        <div class="form-group">
            <div class="text-center">
                <label class="form-label">{{lang('Please enter the OTP sent to your registered email.')}}</label>
            </div>
            <input class="form-control @error('OTP') is-invalid @enderror" name="otp" value="{{ old('otp') }}" placeholder="{{lang('OTP')}}" type="number">
            @error('otp')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ lang($message) }}</strong>
                </span>
            @enderror

        </div>
        <div class="submit">
            <input class="btn btn-secondary btn-block" type="submit" value="{{lang('Submit')}}" >
        </div>
        <div class="text-center mt-4">
            <p class="text-dark mb-0"><a class="text-primary ms-1" href="{{route('admin.resendotp',['email' =>$email])}}">{{lang('Resend OTP')}}</a></p>
        </div>
    </form>
@endsection
