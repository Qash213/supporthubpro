<div class="modal fade" id="addonmodal">
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

                            <form id="addonform" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Application purchase code') }}<span
                                                class="text-red">*</span></label>
                                        <input type="text" name="applicationPurchasecode" class="form-control"
                                            placeholder="Enter your application purchase code" id="applicationPurchasecode" required >
                                        <span class="text-red" id="applicationPurchasecode"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">{{ lang('Addon purchase code') }}<span
                                                class="text-red">*</span></label>
                                        <input type="text" name="purchasecode" class="form-control"
                                            placeholder="Enter your addon purchase code" id="purchasecode" required disabled>
                                        <span class="text-red" id="purchasecodeError"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Add ZIP File<span class="text-red ">*</span></label>
                                        <div class="custom-file-upload">
                                            <input type="file" name="addon" id="addon"
                                                class="form-control square-file-input" accept=".zip" required disabled>

                                        </div>
                                        <span class="text-red" id="addonError"></span>
                                    </div>
                                    <div class="form-group" id="loadingIndicator" style="display: none;">
                                        <i class="fa fa-spinner fa-spin fa-2x" ></i>
                                        <span>please don't refresh it take some time .</span>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:void(0);" class="btn btn-outline-primary"
                                data-bs-dismiss="modal">{{ lang('Close') }}</a>
                            <button class="btn btn-primary addonsave" id="addonsave" disabled>{{ lang('Save') }}</button>
                        </div>
                      
                        </form>


                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</div>
{{-- <style>
    .square-file-input {
        width: 100%;
        /* Adjust the width to make it square */
        height: 150px;
        /* Adjust the height to make it square */
        overflow: hidden;
        /* Hide overflowing content if necessary */
        /* position: absolute; Positioning for overlaying the label */
        opacity: 2;
        /* Make the input transparent */
        cursor: pointer;
        /* Change cursor to pointer on hover */
        justify-content: center;
        text-align: center;


    }
</style> --}}
