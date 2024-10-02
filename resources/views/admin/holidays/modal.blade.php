<div class="modal fade" id="holidaymodal">
    <div class="modal-dialog register-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="single-page customerpage">
                    <div class="wrapper wrapper2 box-shadow-0 border-0">


                        <div class="card-body border-top-0 pt-4">

                            <form  id="Holidayform">
                                <input type="hidden" name="holiday_id" id="holiday_id">
                                @csrf
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">{{lang('Holiday Occasion')}}<span class="text-red">*</span></label>
                                        <input type="text" name="occasion" class="form-control" placeholder="occasion title" id="occasion">
                                        <span class="text-red" id="occasionError"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{lang('Start Date')}}: <span class="text-red">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="feather feather-calendar"></i>
                                                </div>
                                                <input class="form-control fc-datepicker" placeholder="{{lang('YYYY-MM-DD')}}" type="text"  name="startdate" id="startdate" autocomplete="off">
                                            </div>
                                            <span id="startdateError" class="text-danger alert-message"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">{{lang('End Date')}}: <span class="text-red">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="feather feather-calendar"></i>
                                                </div>
                                                <input class="form-control fc-datepicker" placeholder="{{lang('YYYY-MM-DD')}}" type="text" name="enddate" id="enddate"  autocomplete="off">
                                            </div>
                                            <span id="enddateError" class="text-danger alert-message"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">{{lang('Holiday Description')}}<span class="text-red">*</span></label>
                                        <textarea type="text" name="holidaydescription" class="form-control" placeholder="occasion title" id="holidaydescription"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label for="" class="form-label">{{lang('Background Color', 'general')}} <span class="text-red">*</span></label>
                                                <input class="form-control {{ $errors->has('primary_color') ? ' is-invalid' : '' }}" name="primary_color" type="text" value="rgba(230, 250, 232, 1)" id="primary_color-input">

                                                @if ($errors->has('primary_color'))

                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('primary_color') }}</strong>
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ">
                                                <label for="" class="form-label">{{lang('Text Color', 'general')}} <span class="text-red">*</span></label>
                                                <input class="form-control {{ $errors->has('secondary_color') ? ' is-invalid' : '' }}" name="secondary_color"  type="text" value="rgba(0, 0, 0, 1)" id="secondary_color-input">

                                                @if ($errors->has('secondary_color'))

                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('secondary_color') }}</strong>
                                                    </span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="switch_section">
                                            <div class="switch-toggle d-flex mt-4">
                                                <label class="form-label pe-2">{{lang('Status')}}</label>
                                                <a class="onoffswitch2">
                                                    <input type="checkbox"  name="status" id="holidaystatus" class=" toggle-class onoffswitch2-checkbox" value="1" >
                                                    <label for="holidaystatus" class="toggle-class onoffswitch2-label" ></label>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:void(0);" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</a>
                                    <button type="button" class="btn btn-primary holidaysave" id="holidaysave"></button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
