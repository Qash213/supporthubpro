<div class="modal fade" id="updatemodall" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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


                        <div class="card-body border-top-0 pt-4 form-fields-container">

                            <form id="updateform" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="" name="addonid" id="addonid">
                                <div class="row" id="addonData">

                                </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:void(0);" class="btn btn-outline-primary"
                                data-bs-dismiss="modal">Close</a>
                            <button class="btn btn-primary updatesave" id="updatesave" name="updatesave"></button>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
