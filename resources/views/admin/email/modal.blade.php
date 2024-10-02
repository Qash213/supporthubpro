<div class="modal fade" id="imapmodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ lang('IMAP Settings') }}</h5>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="d-none my-4" id="imapCheck">
                <div class="col-sm-12 ">
                    <div class="form-group text-center">
                        <strong>{{ lang('This may take a while, please wait...') }}<i
                                class="fa fa-spinner fa-spin"></i></strong>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0 " id="smtpchecked">
                <div class="single-page customerpage">
                    <div class="wrapper wrapper2 box-shadow-0 border-0">


                        <form id="imapform" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body border-top-0 pt-4">

                                <input type="hidden" name="imap_id" id="imap_id">
                                <div class="row ">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('Email') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="imap_username"
                                                id="imap_username">
                                            <span class="text-danger" id="imap_username_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('IMAP Host') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="imap_host" id="imap_host">
                                            <span class="text-danger" id="imaphostError"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('IMAP Port') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="imap_port" id="imap_port">
                                            <span class="text-danger" id="imapportError"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('IMAP Encryption') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="imap_encryption"
                                                id="imap_encryption">
                                            <span class="text-danger" id="imapencryptionError"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('IMAP Protocol') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="imap_protocol"
                                                id="imap_protocol">
                                            <span class="text-danger" id="imapprotocalError"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <div class="form-group">
                                            <label class="form-label">{{ lang('IMAP Password') }} <span
                                                    class="text-red">*</span></label>
                                            <input type="password" class="form-control" name="imap_password"
                                                id="imap_password">
                                            <span class="text-danger" id="imappasswordError"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">

                                        @php
                                            $category = App\Models\Ticket\Category::whereIn('display', ['ticket', 'both'])
                                                ->where('status', '1')
                                                ->get();

                                        @endphp


                                        <div class="form-group">
                                            <label class="form-label">{{ lang('Category') }}</label>
                                            <select
                                                class="form-control select2form select2formfsfsd @error('category') is-invalid @enderror"
                                                data-placeholder="{{ lang('Select Category') }}" name="category"
                                                id="category">
                                                <option label="{{ lang('Select Category') }}"></option>
                                                @foreach ($category as $categorys)
                                                    <option value="{{ $categorys->id }}">{{ $categorys->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            <span id="CategoryError" class="text-danger alert-message"></span>

                                        </div>
                                    </div>
                                    <div class="col-sm-6  mt-4">

                                        <div class="form-group">
                                            <div class="switch_section">
                                                <div class="switch-toggle d-flex">
                                                    <label class="form-label pe-2">{{ lang('Status') }}</label>
                                                    <a class="onoffswitch2">
                                                        <input type="checkbox" name="status" id="imapstatus"
                                                            class=" toggle-class onoffswitch2-checkbox"
                                                            value="1">
                                                        <label for="imapstatus"
                                                            class="toggle-class onoffswitch2-label"></label>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="javascript:void(0);" class="btn btn-outline-primary"
                                    data-bs-dismiss="modal">{{ lang('Close') }}</a>
                                <button class="btn btn-primary imapsave" id="imapsave">{{ lang('Save') }}</button>
                            </div>

                        </form>


                    </div>

                </div>
            </div>
        </div>
        <script>
            $(function() {
                $('.select2form').select2({
                    allowClear: true
                });
            })
        </script>
    </div>
</div>
