
@extends('layouts.adminmaster')

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
	<div class="page-leftheader">
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Customer')}}</span></h4>
	</div>
</div>
<!--End Page header-->

<!-- Customer Edit -->
<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Edit Customer')}}</h4>
		</div>
		<form method="POST" action="{{url('/admin/customer/' . encrypt($user->id))}}" enctype="multipart/form-data">
			<div class="card-body" >
				@csrf

				@honeypot
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('First Name')}} <span
                                class="text-red">*</span></label></label>
							<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname"  value="{{ $user->firstname, old('firstname') }}" >
							@error('firstname')

								<span class="invalid-feedback d-block" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Last Name')}} <span
                                class="text-red">*</span></label></label>
							<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"  value="{{$user->lastname, old('lastname') }}" >
							@error('lastname')

								<span class="invalid-feedback d-block" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Username')}}</label>
							<input type="text" class="form-control" name="name"  value="{{$user->username }}" readonly>
						</div>
					</div>
					<div class="col-sm-6 col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Email')}} <span
                                class="text-red">*</span></label></label>
							<input type="email @error('email') is-invalid @enderror" class="form-control" name="email" Value="{{$user->email, old('email')}}">
							@error('email')

								<span class="invalid-feedback d-block" role="alert">
									<strong>{{ lang($message) }}</strong>
								</span>
							@enderror

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Country')}}</label>
                            <select name="country" class="form-control select2 select2-show-search">
                                @foreach($countries as $country)
                                    <option value="{{$country->name}}" {{$country->name == $user->country ? 'selected' : ''}}>{{$country->name}}</option>
                                @endforeach
                            </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">{{lang('Timezones')}}</label>
							<select name="timezone" class="form-control select2 select2-show-search">
                                @foreach($timezones  as $group => $timezoness)
                                    <option value="{{$timezoness->timezone}}" {{$timezoness->timezone == $user->timezone ? 'selected' : ''}}>{{$timezoness->timezone}} {{$timezoness->utc}}</option>
                                @endforeach
                            </select>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label class="form-label">{{lang('Status')}}</label>
							<select class="form-control  select2" data-placeholder="{{lang('Select Status')}}" name="status">
								<option label="{{lang('Select Status')}}"></option>
								@if ($user->status === '1')

								<option value="{{$user->status}}" @if ($user->status === '1') selected @endif>{{lang('Active')}}</option>
								<option value="0">{{lang('Inactive')}}</option>
								@else

								<option value="{{$user->status}}" @if ($user->status === '0') selected @endif>{{lang('Inactive')}}</option>
								<option value="1">{{lang('Active')}}</option>
								@endif

							</select>
						</div>
					</div>
					<div class="switch_section">
						<div class="switch-toggle d-flex mt-4">
							<a class="onoffswitch2">
								<input type="checkbox" name="voilated" id="myonoffswitch181" class=" toggle-class onoffswitch2-checkbox sprukoswitch"  @if($user->voilated == 'on') checked="" @endif>
								<label for="myonoffswitch181" class="toggle-class onoffswitch2-label"></label>
							</a>
							<label class="form-label ps-3"> {{lang('Violated Customer')}} </label>
						</div>
					</div>
                    @if($customfield->isNotEmpty())
                        <h3>{{lang('Customfields')}}</h3>
                        @foreach($customfield as $customfields)
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{$customfields->fieldnames}}</label>

                                    @if($customfields->fieldtypes == 'text' || $customfields->fieldtypes == 'email')
                                        <input type="{{$customfields->fieldtypes}}" class="form-control" name="customfield_{{$customfields->fieldnames}}" Value="{{$customfields->privacymode == '1' ? decrypt($customfields->values) : $customfields->values}}">
                                    @endif

                                    @if($customfields->fieldtypes == 'textarea')
                                        <textarea name="customfield_{{$customfields->fieldnames}}" class="form-control" cols="30" rows="5">{{$customfields->privacymode == '1' ? decrypt($customfields->values) : $customfields->values}}</textarea>
                                    @endif

                                    @if($customfields->fieldtypes == 'checkbox')
                                        @php
                                            $coptions = explode(',', $customfields->fieldoptions);
                                            if($customfields->privacymode == '1'){
                                                $valueoption = explode(',', decrypt($customfields->values));
                                            }else{
                                                $valueoption = explode(',', $customfields->values);
                                            }
                                        @endphp
                                        @foreach($coptions as $key => $coption)
                                        <label class="custom-control form-checkbox d-inline-block me-3">
                                            <input type="{{$customfields->fieldtypes}}" class="custom-control-input"  name="customfield_{{$customfields->fieldnames}}[]" value="{{$coption}}" {{ in_array($coption, $valueoption) ? 'checked' : '' }}>

                                            <span class="custom-control-label">{{$coption}}</span>
                                        </label>

                                        @endforeach
                                    @endif

                                    @if($customfields->fieldtypes == 'select')
                                        <select name="customfield_{{$customfields->fieldnames}}" class="form-control select2 select2-show-search" data-placeholder="{{lang('Select')}}">
                                            @php
                                                $seoptions = explode(',', $customfields->fieldoptions);

                                                if($customfields->privacymode == '1'){
                                                    $selectedvalues = explode(',', decrypt($customfields->values));
                                                }else{
                                                    $selectedvalues = explode(',', $customfields->values);
                                                }
                                            @endphp
                                            <option></option>
                                            @foreach($seoptions as $seoption)

                                                <option value="{{$seoption}}" {{in_array($seoption, $selectedvalues) ? 'selected' : ''}}>{{$seoption}}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @if($customfields->fieldtypes == 'radio')
                                        @php
                                            $roptions = explode(',', $customfields->fieldoptions);

                                            if($customfields->privacymode == '1'){
                                                $radiovalues = explode(',', decrypt($customfields->values));
                                            }else{
                                                $radiovalues = explode(',', $customfields->values);
                                            }
                                        @endphp
                                        @foreach($roptions as $roption)
                                            <label class="custom-control form-radio d-inline-block me-3">
                                                <input type="{{$customfields->fieldtypes}}" class="custom-control-input" name="customfield_{{$customfields->fieldnames}}" value="{{$roption}}" {{in_array($roption, $radiovalues) ? 'checked' : ''}}>
                                                <span class="custom-control-label">{{$roption}}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
				</div>

			</div>

			<div class="card-footer">
				<div class="form-group float-end">
                    <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Save Changes')}}</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- End Customer Edit -->

@endsection

@section('scripts')

<!-- INTERNAL select2 js-->
@vite(['resources/assets/js/select2.js'])
@endsection
