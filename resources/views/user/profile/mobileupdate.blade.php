<!-- Mobile number update -->
<div class="modal fade"  id="mobileupdatemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{lang('Mobile Update')}}</h5>
                <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="emailchangestore" method="POST" enctype="multipart/form-data">
                @csrf
                @honeypot

                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label fs-16">{{lang('Enter Mobile Number')}}</label>
                        <div class="d-flex gap-3">
                            <input type="text" class="form-control" value="{{ old('phone', Auth::guard('customer')->user()->phone) }}" id="mobileNumber">
                            <a href="javascript:void(0);" id="sendOTP" onclick="sendOTP(this);" class="btn btn-primary">{{ lang('Send Otp') }}</a>
                        </div>
                        <span class="text-danger d-none" id="resendTimeout">{{ lang('Resend OTP in ') }} <span id="timer"></span></span>
                        <span class="text-red" id="alreadyExists"></span>

                        <div class="mt-3 d-none" id="showenterotp">
                            <label class="form-label fs-16">{{lang('Enter OTP')}}</label>
                            <input type="text" name="OTPMobile" id="OTPMobile" placeholder="Enter OTP" maxlength="6" class="form-control">
                            <span class="text-danger" id="invalidotp"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-outline-danger"
                        data-bs-dismiss="modal">{{ lang('Close') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Mobile number update  -->
