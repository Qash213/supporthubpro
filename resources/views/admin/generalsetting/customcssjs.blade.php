
@extends('layouts.adminmaster')



							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Custom CSS & JS', 'menu')}}</span></h4>
								</div>
							</div>
							<!--End Page header-->

							<!--Custom CSS & JS-->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0">
										<h4 class="card-title">{{lang('Custom CSS & JS', 'menu')}}</h4>
									</div>
									<form method="POST" action="{{route('settings.custom.cssjs')}}" enctype="multipart/form-data">
										@csrf

										@honeypot

										<div class="card-body" >
											<input type="hidden" class="form-control" name="id" Value="">
											<div class="col-sm-12 col-md-12">
												<div class="form-group">
													<label class="form-label">{{lang('Custom CSS')}}</label>
													<textarea name="customcss" class="form-control @error('customcss') is-invalid @enderror" cols="30" rows="10" placeholder="{{lang('Custom Css')}}">{{customcssjs('CUSTOMCSS')}}</textarea>
													@error('customcss')

														<span class="invalid-feedback d-block" role="alert">
															<strong>{{ lang($message) }}</strong>
														</span>
													@enderror

												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<div class="form-group">
													<label class="form-label">{{lang('Custom JS')}}</label>
													<textarea name="customjs" class="form-control @error('customjs') is-invalid @enderror" cols="30" rows="10" placeholder="{{lang('Custom Js')}}">{{customcssjs('CUSTOMJS')}}</textarea>
													@error('customjs')

														<span class="invalid-feedback d-block" role="alert">
															<strong>{{ lang($message) }}</strong>
														</span>
													@enderror

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
							<!--End Custom CSS & JS-->
							@endsection


