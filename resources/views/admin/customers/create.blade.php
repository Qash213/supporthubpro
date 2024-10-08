
@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAl Tag css -->
		<link href="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Customer')}}</span></h4>
								</div>
							</div>
							<!--End Page header-->

							<!-- Create Customers -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Create Customer')}}</h4>
									</div>
									<form method="POST" action="{{url('/admin/customer/create')}}" enctype="multipart/form-data">
										<div class="card-body" >
											@csrf

											@honeypot
											<div class="row">
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('First Name')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname"  value="{{old('firstname')}}" >
														@error('firstname')

															<span class="invalid-feedback d-block" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Last Name')}} <span class="text-red">*</span></label>
														<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname"  value="{{old('lastname')}}" >
														@error('lastname')

															<span class="invalid-feedback d-block" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Email')}} <span class="text-red">*</span></label>
														<input type="email" class="form-control  @error('email') is-invalid @enderror" name="email"  value="{{old('email')}}" >
														@error('email')

															<span class="invalid-feedback d-block" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Password')}} <small class="text-muted"><i>({{lang('Please copy the Password')}})</i></small></label>
														<input class="form-control @error('password') is-invalid @enderror" type="text"  name="password" value="{{str_random(10)}}"  readonly>
														@error('password')

															<span class="invalid-feedback d-block" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror
													</div>
												</div>
												<div class="col-sm-6 col-md-6">
													<div class="form-group">
														<label class="form-label">{{lang('Mobile Number')}}</label>
														<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"  value="{{old('phone')}}"  >
														@error('phone')

															<span class="invalid-feedback d-block" role="alert">
																<strong>{{ lang($message) }}</strong>
															</span>
														@enderror

													</div>
												</div>
                                                @if ($customfields->isNotEmpty())
                                                    <h5 class="my-2">{{lang('Customfields')}}</h5>
                                                    @foreach ($customfields as $customfield)
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $customfield->fieldnames }}
                                                                    @if ($customfield->fieldrequired == '1')
                                                                        <span class="text-red">*</span>
                                                                    @endif
                                                                </label>

                                                                @if ($customfield->fieldtypes == 'text')
                                                                    <input type="{{ $customfield->fieldtypes }}" class="form-control"
                                                                        name="custom_{{ $customfield->id }}"
                                                                        {{ $customfield->fieldrequired == '1' ? 'required' : '' }}>
                                                                @endif
                                                                @if ($customfield->fieldtypes == 'email')
                                                                    <input type="{{ $customfield->fieldtypes }}" class="form-control"
                                                                        name="custom_{{ $customfield->id }}"
                                                                        {{ $customfield->fieldrequired == '1' ? 'required' : '' }}>
                                                                @endif
                                                                @if ($customfield->fieldtypes == 'textarea')
                                                                    <textarea name="custom_{{ $customfield->id }}" class="form-control" cols="30" rows="5"
                                                                        {{ $customfield->fieldrequired == '1' ? 'required' : '' }}></textarea>
                                                                @endif
                                                                @if ($customfield->fieldtypes == 'checkbox')
                                                                    @php
                                                                        $coptions = explode(',', $customfield->fieldoptions);
                                                                    @endphp
                                                                    @foreach ($coptions as $key => $coption)
                                                                        <label class="custom-control form-checkbox d-inline-block me-3">
                                                                            <input type="{{ $customfield->fieldtypes }}"
                                                                                class="custom-control-input {{ $customfield->fieldrequired == '1' ? 'required' : '' }}"
                                                                                name="custom_{{ $customfield->id }}[]" value="{{ $coption }}">

                                                                            <span class="custom-control-label">{{ $coption }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                @endif
                                                                @if ($customfield->fieldtypes == 'select')
                                                                    <select name="custom_{{ $customfield->id }}"
                                                                        class="form-control select2 select2-show-search"
                                                                        data-placeholder="{{ lang('Select') }}"
                                                                        {{ $customfield->fieldrequired == '1' ? 'required' : '' }}>
                                                                        @php
                                                                            $seoptions = explode(',', $customfield->fieldoptions);
                                                                        @endphp
                                                                        <option></option>
                                                                        @foreach ($seoptions as $seoption)
                                                                            <option value="{{ $seoption }}">{{ $seoption }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                                @if ($customfield->fieldtypes == 'radio')
                                                                    @php
                                                                        $roptions = explode(',', $customfield->fieldoptions);
                                                                    @endphp
                                                                    @foreach ($roptions as $roption)
                                                                        <label class="custom-control form-radio d-inline-block me-3">
                                                                            <input type="{{ $customfield->fieldtypes }}" class="custom-control-input"
                                                                                name="custom_{{ $customfield->id }}" value="{{ $roption }}"
                                                                                {{ $customfield->fieldrequired == '1' ? 'required' : '' }}>
                                                                            <span class="custom-control-label">{{ $roption }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
											</div>
										</div>
										<div class="col-md-12 card-footer">
											<div class="form-group float-end">
                                                <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Saving <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Create Customer')}}</button>
											</div>
										</div>
									</form>

								</div>
							</div>
							<!-- Create Customers -->
							@endsection

		@section('scripts')

		<!--File BROWSER -->
        @vite(['resources/assets/js/form-browser.js'])

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>
        @vite(['resources/assets/js/select2.js'])

		<!-- INTERNAL Index js-->
        @vite(['resources/assets/js/support/support-sidemenu.js'])

		<!-- INTERNAL TAG js-->
		<script src="{{asset('build/assets/plugins/taginput/bootstrap-tagsinput.js')}}?v=<?php echo time(); ?>"></script>

		@endsection
