<!-- email change -->
<div class="modal fade"  id="2fapassmodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >{{lang('Two Factor Security')}}</h5>
                <a  href="{{ url()->current() }}">
                    <span aria-hidden="true">×</span>
                </a>
            </div>
            <form id="2fapasswordverify" method="POST" enctype="multipart/form-data">
                @csrf
                @honeypot

                <div class="modal-body">
                    <input type="hidden" name="emailcheckstatus" id="emailcheckstatus">
                    <input type="hidden" name="cust_id" id="cust_id">
                    <div class="form-group">
                        <label class="form-label">{{lang('Enter Password')}}</label>
                        <small>{{lang('To ensure additional security measures, kindly verify your password as we`re being extra safe.')}}</small>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <span class="text-red" id="passwordError"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" id="2fapassbtnsave">{{lang('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End email change  -->
