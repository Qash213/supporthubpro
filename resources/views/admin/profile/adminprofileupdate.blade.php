
@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAl Tag css -->
<link href="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Edit Profile', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->

<!-- Edit Profile Page-->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title">{{lang('Edit Profile', 'menu')}}</h4>
            </div>
            <div class="card-body" >
                <form method="POST" action="{{url('/admin/profile')}}" enctype="multipart/form-data">
                        @csrf
                        @honeypot

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('First Name')}}</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{Auth::user()->firstname}}" required>
                                    @error('firstname')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Last Name')}}</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{Auth::user()->lastname }}" required>
                                    @error('lastname')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Email')}}</label>
                                    <input type="email" class="form-control" Value="{{Auth::user()->email}}" disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label"> {{lang('Employee ID')}}</label>
                                    <input type="email" class="form-control" Value="{{Auth::user()->empid}}" disabled>

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Mobile Number')}}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"  value="{{old('phone',Auth::user()->phone)}}" >
                                    @error('phone')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Languages')}}</label>
                                    <input type="text" class="form-control @error('languages') is-invalid @enderror sprukotags" value="{{old('languages', Auth::user()->languagues)}}" name="languages[]" data-role="tagsinput" />
                                    @error('languages')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Skills')}}</label>
                                    <input type="text" class="form-control @error('skills') is-invalid @enderror sprukotags" value="{{old('skills', Auth::user()->skills)}}" name="skills[]" data-role="tagsinput" />
                                    @error('skills')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Country')}}</label>
                                    <select name="country" class="form-control select2 select2-show-search" id="">
                                        @foreach($countries as $country)
                                        <option value="{{$country->name}}" {{$country->name == Auth::user()->country ? 'selected' : ''}}>{{$country->name}}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Timezone')}}</label>
                                    <select name="timezone" class="form-control select2 select2-show-search" id="">
                                        @foreach($timezones  as $group => $timezoness)
                                            <option value="{{$timezoness->timezone}}" {{$timezoness->timezone == Auth::user()->timezone ? 'selected' : ''}}>{{$timezoness->timezone}} {{$timezoness->utc}}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Upload Image')}}</label>
                                    <div class="input-group file-browser">
                                        <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" accept="image/png, image/jpeg,image/jpg" >

                                        @error('image')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                    <small class="text-muted"><i>{{lang('The file size should not be more than 5MB', 'filesetting')}}</i></small>
                                </div>
                            </div>
                            <div class="col-md-12 card-footer">
                                <div class="form-group float-end mb-0">
                                    <button type="submit" class="btn btn-secondary">{{lang('Save Changes')}}</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Profile Page-->
@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])
@vite(['resources/assets/js/select2.js'])

<!-- INTERNAL TAG js-->
<script src="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.js')}}?v=<?php echo time(); ?>"></script>

@endsection
