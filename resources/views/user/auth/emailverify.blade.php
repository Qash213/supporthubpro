@extends('layouts.custommaster')
@section('content')
<div class="pb-0 px-5 pt-0 text-center">
   <h3 class="mb-2">{{lang('Email verification?')}}</h3>
   <p class="text-muted fs-13 mb-1">{{lang('Enter the email address that is linked to your account.')}}</p>
</div>
<form class="card-body pt-3 pb-0" id="forgot" action="{{route('user.emailverificationstore')}}" method="post">
   @csrf
   @honeypot
   <div class="form-group">
      <label class="form-label">{{lang('Email')}}</label>
      <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{lang('Email')}}" type="email">
      @error('email')
      <span class="invalid-feedback d-block" role="alert">
      <strong>{{ lang($message) }}</strong>
      </span>
      @enderror
   </div>
   <div class="submit">
      <input class="btn btn-secondary btn-block" type="submit" value="{{lang('Get Link')}}" onclick="this.disabled=true;this.form.submit();">
   </div>
   <!-- <div class="text-center mt-4">
      <p class="text-dark mb-0"><a class="text-primary ms-1" href="{{url('/')}}">{{lang('Send me Back')}}</a></p>
      </div> -->
</form>
@endsection
