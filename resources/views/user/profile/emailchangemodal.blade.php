<!-- email change -->
<div class="modal fade"  id="emailmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{lang('Email Update')}}</h5>
                <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="emailchangestore" method="POST" enctype="multipart/form-data">
                @csrf
                @honeypot

                <div class="modal-body">
                    <input type="hidden" name="email" id="emailid">
                    <div class="form-group">
                        <label class="form-label fs-16">{{lang('Enter Password')}}</label>
                        <small>{{lang('To ensure additional security measures, kindly verify your password as we`re being extra safe.')}}</small>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <span class="text-red" id="passwordError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="btnsave">{{lang('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End email change  -->
