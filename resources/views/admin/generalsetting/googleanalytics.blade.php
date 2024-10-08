
@extends('layouts.adminmaster')



							@section('content')


							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Google Analytics', 'menu')}}</span></h4>
								</div>
							</div>
							<!--End Page header-->

							<!--Google Analytics-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Google Analytics', 'menu')}}</h4>
									</div>
									<form method="POST" action="{{ route('settings.googleanalytics') }}" enctype="multipart/form-data">
										@csrf

										@honeypot
										<div class="card-body" >
											<div class="col-sm-6 col-md-6">
												<div class="switch_section">
													<div class="switch-toggle d-flex">
														<a class="onoffswitch2">
															<input type="checkbox" id="myonoffswitch18" name="GOOGLE_ANALYTICS_ENABLE" class=" toggle-class onoffswitch2-checkbox" @if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes') checked @endif>
															<label for="myonoffswitch18" class="toggle-class onoffswitch2-label"></label>
														</a>
														<label class="form-label ps-3">{{lang('Enable Google Analytics')}}</label>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 ">

												<div class="form-group {{ $errors->has('GOOGLE_ANALYTICS') ? ' has-danger' : '' }}">
													<label class="form-label">{{lang('Tracking ID')}}</label>
													<input type="text"  class="form-control {{ $errors->has('GOOGLE_ANALYTICS') ? ' is-invalid' : '' }}"  name="GOOGLE_ANALYTICS"  value="{{old('GOOGLE_ANALYTICS', setting('GOOGLE_ANALYTICS')) }}">
													@if ($errors->has('GOOGLE_ANALYTICS'))

														<span class="invalid-feedback d-block" role="alert">
															<strong>{{ $errors->first('GOOGLE_ANALYTICS') }}</strong>
														</span>
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
							<!--End Google Analytics-->
							@endsection

