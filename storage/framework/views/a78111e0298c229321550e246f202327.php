<?php $__env->startSection('styles'); ?>
    <!-- INTERNAL Data table css -->
    <link href="<?php echo e(asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')); ?>?v=<?php echo time(); ?>"
        rel="stylesheet" />
    <link href="<?php echo e(asset('build/assets/plugins/datatable/responsive.bootstrap5.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="<?php echo e(asset('build/assets/plugins/sweet-alert/sweetalert.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Datepicker css-->
    <link href="<?php echo e(asset('build/assets/plugins/modal-datepicker/datepicker.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="<?php echo e(asset('build/assets/plugins/sweet-alert/sweetalert.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span
                    class="font-weight-normal text-muted ms-2"><?php echo e(lang('Email Setting', 'menu')); ?></span></h4>
        </div>
    </div>
    <!--End Page header-->

    <!-- Send Test Mail -->
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title"><?php echo e(lang('Send Test Mail')); ?></h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo e(route('settings.email.sendtestmail')); ?>" id="my-form" autocomplete="off"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <?php echo method_field('post'); ?>

                    <div class="row">
                        <div class="form-group col-md-6 <?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                            <input class="form-control" name="email" placeholder="<?php echo e(lang('Enter Mail')); ?>" type="email"
                                value="<?php echo e(old('email', setting('email'))); ?>" id="example-email-input">

                            <?php if($errors->has('email')): ?>
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong><?php echo e($errors->first('email')); ?></strong>
                                </span>
                            <?php endif; ?>

                        </div>
                        <div class="form-group col-md-6 <?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                            <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Sending <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();"><?php echo e(lang('Send')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Send Test Mail -->

    <!-- Email Setting -->
    <div class="col-xl-12 col-lg-12 col-md-12">

       	<!--- Custom notification -->

        <div class="alert alert-warning-light br-13 mt-6 align-items-center border-0 d-flex" role="alert">
            <div class="d-flex">

            </div>
            <ul class="notify vertical-scroll5 custom-ul ht-0 me-5">

                <li class="item">
                    <p class="fs-14 mb-0">
                        <?php echo e(lang("Ensure that your Email Setting, specifically SMTP/Send Mail and IMAP Settings, should utilize only single-domain emails, even if you are using different email id's (e.g., if using gmail.com, ensure both settings correspond to that domain.)")); ?> </p>
                </li>

            </ul>
            <div class="d-flex ms-6 sprukocnotify">
                <button class="btn-close ms-2 mt-0 text-warning" data-bs-dismiss="alert"
                    aria-hidden="true">×</button>
            </div>
        </div>

        <!--- End Custom notification -->
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title"><?php echo e(lang('SMTP/Send Mail', 'menu')); ?></h4>
            </div>
            <form method="POST" enctype="multipart/form-data" name="emailsetting_form" id="emailsetting_form">
                <div class="card-body">
                    <?php echo csrf_field(); ?>

                    <?php echo view('honeypot::honeypotFormFields'); ?>
                    <input type="hidden" class="form-control" name="id" Value="">
                    <div class="row" id="selectmail">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(lang('Email')); ?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" name="mail_username" id="mail_username"
                                    Value="<?php echo e(old('mail_username', setting('mail_username'))); ?>">
                                <span class="text-danger" id="mailusernameError"></span>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-5">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(lang('Mail Driver')); ?></label>
                                <select name="mail_driver" id="mail_driver" class="form-control select2" required>
                                    <?php $__currentLoopData = ['smtp' => 'SMTP', 'sendmail' => 'Send Mail']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(old('mail_driver', setting('mail_driver')) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($lang); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>

                                <?php if($errors->has('mail_driver')): ?>
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong><?php echo e($errors->first('mail_driver')); ?></strong>
                                    </span>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="card-footer ">
                    <div class="form-group  float-end">
                        <button class="btn btn-secondary imapsave"
                            id="formemailsetting"><?php echo e(lang('Save Changes')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Email Setting -->

    <!-- Email To Ticket Enable -->
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title"><?php echo e(lang('Email To Ticket')); ?></h4>
            </div>
            <form method="post" action="<?php echo e(route('admin.enableemailtoticket')); ?>" enctype="multipart/form-data">
                <div class="card-body">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="switch_section">
                            <div class="switch-toggle d-flex d-md-max-block">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="IMAP_STATUS" id="IMAP_STATUS"
                                        class=" toggle-class onoffswitch2-checkbox" value="on"
                                        <?php if(setting('IMAP_STATUS') == 'on'): ?> checked="" <?php endif; ?>>
                                    <label for="IMAP_STATUS" class="toggle-class onoffswitch2-label"></label>
                                </a>
                                <label
                                    class="form-label ps-3 ps-md-max-0"><?php echo e(lang('Enable Email to ticket', 'setting')); ?></label>
                                <small
                                    class="text-muted ps-2 ps-md-max-0"><i>(<?php echo e(lang('Configure IMAP settings and enable this "Email to Ticket" feature so that customers will be able to generate support tickets through their email correspondence.', 'setting')); ?>)</i></small>
                            </div>
                        </div>

                        <div class="switch_section">
                            <div class="switch-toggle d-flex d-md-max-block">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="IMAP_EMAIL_AUTO_DELETE" id="IMAP_EMAIL_AUTO_DELETE"
                                        class=" toggle-class onoffswitch2-checkbox" value="on"
                                        <?php if(setting('IMAP_EMAIL_AUTO_DELETE') == 'on'): ?> checked="" <?php endif; ?>>
                                    <label for="IMAP_EMAIL_AUTO_DELETE" class="toggle-class onoffswitch2-label"></label>
                                </a>
                                <label
                                    class="form-label ps-3 ps-md-max-0"><?php echo e(lang('Auto-delete Email Upon Ticket Creation', 'setting')); ?></label>
                                <small
                                    class="text-muted ps-2 ps-md-max-0"><i>(<?php echo e(lang('By enabling this switch, emails will be permanently deleted from your inbox once a ticket is created in the support system.', 'setting')); ?>)</i></small>
                            </div>
                        </div>
                        <div class="switch_section">
                            <div class="switch-toggle d-flex d-md-max-block">
                                <a class="onoffswitch2">
                                    <input type="checkbox" name="IMAP_EMAIL_PROCESS_LIMIT_SWITCH" id="IMAP_EMAIL_PROCESS_LIMIT_SWITCH"
                                        class=" toggle-class onoffswitch2-checkbox" value="on"
                                        <?php if(setting('IMAP_EMAIL_PROCESS_LIMIT_SWITCH') == 'on'): ?> checked="" <?php endif; ?>>
                                    <label for="IMAP_EMAIL_PROCESS_LIMIT_SWITCH" class="toggle-class onoffswitch2-label"></label>
                                </a>
                                <label
                                    class="form-label ps-3 ps-md-max-0"><?php echo e(lang('Maximum Emails to Read', 'setting')); ?></label>
                                <small
                                    class="text-muted ps-2 ps-md-max-0"><i>(<?php echo e(lang('Use the below input field to specify the maximum number of emails to process at a time. Setting a value here determines the batch size for creating tickets from incoming emails, ensuring efficient handling of customer inquiries.', 'setting')); ?>)</i></small>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 ms-7 ps-3 ">
                            <div
                                class="form-group d-flex d-md-max-block <?php echo e($errors->has('IMAP_EMAIL_TEMPLATE_LIMIT') ? ' is-invalid' : ''); ?>">
                                <input type="number" maxlength="2" class="form-control wd-5 w-lg-max-30 ms-2 "
                                    name="IMAP_EMAIL_TEMPLATE_LIMIT"
                                    value="<?php echo e(old('IMAP_EMAIL_TEMPLATE_LIMIT', setting('IMAP_EMAIL_TEMPLATE_LIMIT'))); ?>"  min="0" oninput="validity.valid||(value='');">
                            </div>
                            <?php if($errors->has('IMAP_EMAIL_TEMPLATE_LIMIT')): ?>
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong><?php echo e($errors->first('IMAP_EMAIL_TEMPLATE_LIMIT')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <div class="form-group  float-end">
                        <button type="submit" class="btn btn-secondary" onclick="this.disabled=true;this.innerHTML=`Sending <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();"><?php echo e(lang('Save Changes')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Email To Ticket Enable -->

    

    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title"><?php echo e(lang('IMAP Settings')); ?></h4>

                <div class="card-options mt-sm-max-2 d-md-max-block">
                    <a href="#" class="btn btn-secondary mb-md-max-2 me-3 text-capitalize" id="addimapmodal">
                        <?php echo e(lang('Add new')); ?></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive spruko-delete">


                    <button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"
                        style="display: none;"><i class="fe fe-trash"></i> <?php echo e(lang('Delete')); ?></button>


                    <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100"
                        id="support-articlelists">
                        <thead>
                            <tr>
                                <th width="10"><?php echo e(lang('Sl.No')); ?></th>


                                <th width="10">
                                    <input type="checkbox" id="customCheckAll">
                                    <label for="customCheckAll"></label>
                                </th>


                                <th><?php echo e(lang('Email')); ?></th>
                                <th><?php echo e(lang('IMAP Host ')); ?></th>
                                <th><?php echo e(lang('Category')); ?></th>
                                <th><?php echo e(lang('Status')); ?></th>
                                <th><?php echo e(lang('Actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                use Carbon\Carbon;
                                $count = 1;
                                $holidays = [];

                            ?>
                            <?php $__currentLoopData = $imaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="odd">
                                    <td class="sorting_1"><?php echo e($count++); ?></td>
                                    <td class="sorting_1">
                                        <input type="checkbox" name="spruko_checkbox[]" class="checkall"
                                            value="<?php echo e($imap->id); ?>" />
                                    </td>
                                    <td class="font-weight-semibold"><?php echo e($imap['imap_username']); ?></td>
                                    <td class="font-weight-semibold"><?php echo e($imap['imap_host']); ?></td>
                                    <td class="font-weight-semibold">

                                        <?php
                                            if ($imap['category_id']) {
                                                $category = App\Models\Ticket\Category::where('status', '1')->find($imap['category_id']);
                                            } else {
                                                $category = null;
                                            }
                                        ?>

                                        <?php if($category): ?>
                                            <?php echo e($category->name); ?>

                                        <?php else: ?>
                                            <span> ~~ </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($imap->status == '1'): ?>
                                            <label class="custom-switch form-switch mb-0">
                                                <input type="checkbox" name="status" data-id="<?php echo e($imap->id); ?>"
                                                    id="myonoffswitch<?php echo e($imap->id); ?>" value="1"
                                                    class="custom-switch-input tswitch" checked>
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        <?php else: ?>
                                            <label class="custom-switch form-switch mb-0">
                                                <input type="checkbox" name="status" data-id="<?php echo e($imap->id); ?>"
                                                    id="myonoffswitch<?php echo e($imap->id); ?>" value="1"
                                                    class="custom-switch-input tswitch">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="action-btns1" id="editimapsettings"
                                                data-id="<?php echo e($imap['id']); ?>" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="<?php echo e(lang('Edit')); ?>">
                                                <i class="feather feather-edit text-primary"></i>
                                            </a>

                                            <a href="javascript:void(0);" class="action-btns1 deleteholiday"
                                                id="deleteimap" data-id="<?php echo e($imap['id']); ?>" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="<?php echo e(lang('Delete')); ?>">
                                                <i class="feather feather-trash-2 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Email Setting -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('admin.email.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!--- select2 js -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/js/select2.js']); ?>

    <!-- INTERNAL Data tables -->
    <script src="<?php echo e(asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')); ?>?v=<?php echo time(); ?>"></script>
    <script src="<?php echo e(asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')); ?>?v=<?php echo time(); ?>"></script>
    <script src="<?php echo e(asset('build/assets/plugins/datatable/dataTables.responsive.min.js')); ?>?v=<?php echo time(); ?>"></script>
    <script src="<?php echo e(asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')); ?>?v=<?php echo time(); ?>"></script>
    <script src="<?php echo e(asset('build/assets/plugins/datatable/datatablebutton.min.js')); ?>?v=<?php echo time(); ?>"></script>
    <script src="<?php echo e(asset('build/assets/plugins/datatable/buttonbootstrap.min.js')); ?>?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="<?php echo e(asset('build/assets/plugins/sweet-alert/sweetalert.min.js')); ?>?v=<?php echo time(); ?>"></script>

    <script src="<?php echo e(asset('build/assets/plugins/jquery/jquery-ui.js')); ?>?v=<?php echo time(); ?>"></script>

    <script type="text/javascript">

        $(function() {
            "use strict";

            var SITEURL = '<?php echo e(url('')); ?>';
            (function($) {

                // submit button function
                let optionvar = $('#mail_driver').val();
                if (optionvar == 'sendmail') {
                    sendmail()

                } else if (optionvar == 'smtp') {

                    smtp()
                }
                // Csrf field
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // submit button
                $('body').on('submit', '#emailsetting_form', function(e) {
                    e.preventDefault();
                    $('#formemailsetting').html(
                        `<?php echo e(lang('Loading..', 'menu')); ?> <i class="fa fa-spinner fa-spin"></i>`);
                    $('#formemailsetting').prop('disabled', true);
                    var optionclick = $('#mail_driver').val();
                    var formData = new FormData(this);
                    $('#mailhostError').html('')
                    $('#mailportError').html('');
                    $('#mailusernameError').html('');
                    $('#mailpasswordError').html('');
                    $('#mailencryptionError').html('');
                    $('#fromnameError').html('');
                    $('#fromaddressError').html('');

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo e(route('settings.email.store')); ?>',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            $('#mailhostError').html('')
                            $('#mailportError').html('');
                            $('#mailusernameError').html('');
                            $('#mailpasswordError').html('');
                            $('#mailencryptionError').html('');
                            $('#fromnameError').html('');
                            $('#fromaddressError').html('');
                            $('#formemailsetting').prop('disabled', false);
                            $('#formemailsetting').html(`<?php echo e(lang('Save Changes')); ?>`);
                            toastr.success(data.success);
                        },
                        error: function(data) {
                            if (data?.responseJSON?.imapconnectionError == 'notconnected') {
                                toastr.error(data.responseJSON.error);
                            }
                            $('#mailhostError').html('')
                            $('#mailportError').html('');
                            $('#mailusernameError').html('');
                            $('#mailpasswordError').html('');
                            $('#mailencryptionError').html('');
                            $('#fromnameError').html('');
                            $('#fromaddressError').html('');

                            $('#mailhostError').html(data.responseJSON.errors?.mail_host)
                            $('#mailportError').html(data.responseJSON.errors?.mail_port);
                            $('#mailusernameError').html(data.responseJSON.errors?.mail_username);
                            $('#mailpasswordError').html(data.responseJSON.errors?.mail_password);
                            $('#mailencryptionError').html(data.responseJSON.errors?.mail_encryption);
                            $('#fromnameError').html(data.responseJSON.errors?.mail_from_name);
                            $('#fromaddressError').html(data.responseJSON.errors?.mail_from_address);
                            $('#imaphostError').html(data.responseJSON.errors?.imap_host);
                            $('#imapportError').html(data.responseJSON.errors?.imap_port);
                            $('#imapencryptionError').html(data.responseJSON.errors?.imap_encryption);
                            $('#imapprotocalError').html(data.responseJSON.errors?.imap_protocol);
                            $('#imappasswordError').html(data.responseJSON.errors?.imap_password);

                            $('#formemailsetting').prop('disabled', false);
                            $('#formemailsetting').html(`<?php echo e(lang('Save Changes')); ?>`);
                        }
                    });




                });


                let prev = <?php echo json_encode(lang('Previous')); ?>;
                let next = <?php echo json_encode(lang('Next')); ?>;
                let nodata = <?php echo json_encode(lang('No data available in table')); ?>;
                let noentries = <?php echo json_encode(lang('No entries to show')); ?>;
                let showing = <?php echo json_encode(lang('showing page')); ?>;
                let ofval = <?php echo json_encode(lang('of')); ?>;
                let maxRecordfilter = <?php echo json_encode(lang('- filtered from ')); ?>;
                let maxRecords = <?php echo json_encode(lang('records')); ?>;
                let entries = <?php echo json_encode(lang('entries')); ?>;
                let show = <?php echo json_encode(lang('Show')); ?>;
                let search = <?php echo json_encode(lang('Search...')); ?>;
                // Datatable
                $('#support-articlelists').dataTable({
                    language: {
                        searchPlaceholder: search,
                        scrollX: "100%",
                        sSearch: '',
                        paginate: {
                            previous: prev,
                            next: next
                        },
                        emptyTable: nodata,
                        infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                        info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                        infoEmpty: noentries,
                        lengthMenu: `${show} _MENU_ ${entries} `,
                    },
                    order: [],
                    columnDefs: [{
                        "orderable": false,
                        "targets": [0, 1, 4]
                    }],
                });



                $('.form-select').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });



                    //checkbox script
                $(document).ready(function() {

                    $(document).on('click', '#customCheckAll', function() {
                        $('.checkall').prop('checked', this.checked);
                        updateMassDeleteVisibility();
                    });

                    $(document).on('click', '.checkall', function() {
                        updateCustomCheckAll();
                        updateMassDeleteVisibility();
                    });


                    $(document).on('click', '.pagination a', function() {

                        setTimeout(function() {
                            updateMassDeleteVisibility();
                        }, 100);
                    });


                    $('#customCheckAll').prop('checked', false);


                    function updateMassDeleteVisibility() {
                        if ($('.checkall:checked').length === 0) {
                            $('#massdeletenotify').hide();
                        } else {
                            $('#massdeletenotify').show();
                        }
                    }


                    function updateCustomCheckAll() {
                        var totalCheckboxes = $('.checkall').length;
                        var checkedCheckboxes = $('.checkall:checked').length;

                        if (checkedCheckboxes === totalCheckboxes) {
                            $('#customCheckAll').prop('checked', true);
                        } else {
                            $('#customCheckAll').prop('checked', false);
                        }
                    }
                });


                //new imap show modal
                $("#addimapmodal").on("click", function() {

                    document.querySelector("#imapCheck")?.classList.remove("d-none")
                    document.querySelector("#smtpchecked")?.classList.add("d-none")
                    $('#imapform').trigger("reset");
                    document.getElementById('imap_id').value = '';
                    // $('#addonsave').html("Add");
                    $('#imap_username_error').html("");
                    $('#imaphostError').html("");
                    $('#imapportError').html("");
                    $('#imapencryptionError').html("");
                    $('#imapprotocalError').html("");
                    $('#imappasswordError').html("");
                    $('#imapmodal').modal('show');
                    $('#category').val('').change();

                    $.ajax({
                        type: "POST",
                        url: '<?php echo e(route('smtp.check')); ?>',
                        success: function (data) {
                            if(data ==1){
                                document.querySelector("#imapCheck")?.classList.add("d-none")
                                document.querySelector("#smtpchecked")?.classList.remove("d-none")
                            }

                        },
                        error: function (data) {
                            if (data?.responseJSON?.imapconnectionError)
                                $('#imapmodal').modal('hide');
                                toastr.error(data?.responseJSON?.error);
                        }
                    });
                });

                //imap save
                $('body').on("click", "#imapsave", function() {

                    var form = document.getElementById('imapform');
                    var formData = new FormData(form);
                    $('#imapform').submit(function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Your form handling code here
                    });
                    $('#imapsave').html(`<?php echo e(lang('Loading..', 'menu')); ?> <i class="fa fa-spinner fa-spin"></i>`);
                    $('#imapsave').prop('disabled', true);

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '<?php echo e(route('settings.imapstore')); ?>',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: function(data) {
                            $('#imapsave').prop('disabled', false);
                            $('#imapsave').html(`<?php echo e(lang('Save')); ?>`);
                            $('#imapmodal').modal('hide');
                            toastr.success(data.success);
                            location.reload();

                        },
                        error: function(data) {


                            if (data?.responseJSON?.errors?.imap_username)
                                if (data?.responseJSON?.errors?.imap_username[0] ==
                                    "The imap username has already been taken.")
                                    $('#imap_username_error').html(
                                        'The imap email has already been taken.');
                                else
                                    $('#imap_username_error').html('The email field is required.');
                            else
                                $('#imap_username_error').html('');

                            if (data?.responseJSON?.errors?.imap_host)
                                $('#imaphostError').html(data?.responseJSON?.errors?.imap_host[0]);
                            else
                                $('#imaphostError').html('');
                            if (data?.responseJSON?.errors?.imap_port)
                                $('#imapportError').html(data?.responseJSON?.errors?.imap_port[0]);
                            else
                                $('#imapportError').html('');
                            if (data?.responseJSON?.errors?.imap_encryption)
                                $('#imapencryptionError').html(data?.responseJSON?.errors
                                    ?.imap_encryption[0]);
                            else
                                $('#imapencryptionError').html('');

                            if (data?.responseJSON?.errors?.imap_protocol)
                                $('#imapprotocalError').html(data?.responseJSON?.errors?.imap_protocol[
                                    0]);
                            else
                                $('#imapprotocalError').html('');
                            if (data?.responseJSON?.errors?.imap_password)
                                $('#imappasswordError').html(data?.responseJSON?.errors?.imap_password[
                                    0]);
                            else
                                $('#imappasswordError').html('');

                            $('#imapsave').prop('disabled', false);
                            $('#imapsave').html(`<?php echo e(lang('Save')); ?>`);

                            if (data?.responseJSON?.category)
                                $('#CategoryError').html(data?.responseJSON?.error);
                            else {

                                if (data?.responseJSON?.imapconnectionError)
                                    toastr.error(data?.responseJSON?.error);
                                else
                                    toastr.error(
                                        '<?php echo e(lang('Please fill required fields.', 'alerts')); ?>');
                            }


                        }
                    });
                });

                // imap status change
                $('body').on('click', '.tswitch', function() {

                    var _id = $(this).data("id");
                    var status = $(this).prop('checked') == true ? '1' : '0';
                    $.ajax({
                        type: "post",
                        url: SITEURL + "/admin/imaps/statuschange/" + _id,
                        data: {
                            'status': status
                        },
                        success: function(data) {
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });

                //edit imap settings
                $('body').on('click', '#editimapsettings', function() {
                    $('#imapform').trigger("reset");
                    $('#imap_username_error').html("");
                    $('#imaphostError').html("");
                    $('#imapportError').html("");
                    $('#imapencryptionError').html("");
                    $('#imapprotocalError').html("");
                    $('#imappasswordError').html("");
                    var imap_id = $(this).data('id');
                    $.get('imaps/' + imap_id, function(data) {
                        $('#imap_id').val(data.id);
                        $('#imap_username').val(data.imap_username);
                        $('#imap_host').val(data.imap_host);
                        $('#imap_port').val(data.imap_port);
                        $('#imap_encryption').val(data.imap_encryption);
                        $('#imap_protocol').val(data.imap_protocol);
                        $('#imap_password').val(data.imap_password);
                        $('#category').val(data.category_id).change();
                        document.querySelector("#imapstatus").checked = data.status == 1 ? true : false
                        $('#imapmodal').modal('show');
                    });
                });

                // Delete the imap
                $('body').on('click', '#deleteimap', function() {
                    var imap_id = $(this).data("id");
                    swal({
                            title: `<?php echo e(lang('Are you sure you want to continue?', 'alerts')); ?>`,
                            text: "<?php echo e(lang('This might erase your records permanently', 'alerts')); ?>",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.get('imaps/delete/' + imap_id, function(data) {
                                    toastr.success('<?php echo e(lang('successfully deleted .', 'alerts')); ?>');
                                    location.reload();

                                });

                            }
                        });
                });

                // Imap Mass Delete
                $('body').on('click', '#massdeletenotify', function() {
                    var id = [];
                    $('.checkall:checked').each(function() {
                        id.push($(this).val());
                    });
                    if (id.length > 0) {
                        swal({
                                title: `<?php echo e(lang('Are you sure you want to continue?', 'alerts')); ?>`,
                                text: "<?php echo e(lang('This might erase your records permanently', 'alerts')); ?>",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: "<?php echo e(route('imaps.alldelete')); ?>",
                                        method: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function(data) {
                                            toastr.success(data.success);
                                            location.reload();
                                        },
                                        error: function(data) {

                                        }
                                    });
                                }
                            });
                    } else {
                        toastr.error('<?php echo e(lang('Please select at least one check box.', 'alerts')); ?>');
                    }

                });


                // select2 change function
                $('#mail_driver').on('change', function() {
                    var option = $(this).val();
                    if (option == 'sendmail') {
                        sendmail()
                    } else if (option == 'smtp') {

                        smtp()

                    }
                });

            })(jQuery);
            // if sendmail get related inputs
            function sendmail() {

                let selectmail = document.querySelector('#selectmail');

                $('.fromail')?.remove();
                // mailfromname
                let divcol12user = document.createElement('div');
                divcol12user.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-6 fromail');
                let formgroupuser = document.createElement('div');
                formgroupuser.setAttribute('class', 'form-group');
                let formmlabeluser = document.createElement('label');
                formmlabeluser.setAttribute('class', 'form-label');
                formmlabeluser.innerHTML = '<?php echo e(lang('From Name')); ?> <span class="text-red">*</span>';
                let inputuser = document.createElement('input');
                inputuser.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputuser.setAttribute('name', 'mail_from_name');
                inputuser.setAttribute('type', 'text');
                inputuser.setAttribute('value', '<?php echo e(old('mail_from_name', setting('mail_from_name'))); ?>');
                let spanerror = document.createElement('span');
                spanerror.setAttribute('class', 'text-red');
                spanerror.setAttribute('id', 'fromnameError');

                // mailfromemail
                let divcol12email = document.createElement('div');
                divcol12email.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-6 fromail');
                let formgroupemail = document.createElement('div');
                formgroupemail.setAttribute('class', 'form-group');
                let formmlabelemail = document.createElement('label');
                formmlabelemail.setAttribute('class', 'form-label');
                formmlabelemail.innerHTML = '<?php echo e(lang('From Email')); ?> <span class="text-red">*</span>';
                let inputemail = document.createElement('input');
                inputemail.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputemail.setAttribute('name', 'mail_from_address');
                inputemail.setAttribute('type', 'email');
                inputemail.setAttribute('value', `<?php echo e(old('mail_from_address', setting('mail_from_address'))); ?>`);
                let spanerror1 = document.createElement('span');
                spanerror1.setAttribute('class', 'text-red');
                spanerror1.setAttribute('id', 'fromaddressError');
                // mailfromname
                selectmail.append(divcol12user);
                divcol12user.append(formgroupuser);
                formgroupuser.append(formmlabeluser);
                formgroupuser.append(inputuser);
                formgroupuser.append(spanerror);
                // mailfromemail
                selectmail.append(divcol12email);
                divcol12email.append(formgroupemail);
                formgroupemail.append(formmlabelemail);
                formgroupemail.append(inputemail);
                formgroupemail.append(spanerror1);

            }

            // if smtp get related inputs
            function smtp() {
                let selectmail = document.querySelector('#selectmail');
                $('.fromail')?.remove();
                // mailhost
                let div12mailhost = document.createElement('div');
                div12mailhost.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailhost = document.createElement('div');
                formgroupmailhost.setAttribute('class', 'form-group');
                let formmlabelmailhost = document.createElement('label');
                formmlabelmailhost.setAttribute('class', 'form-label');
                formmlabelmailhost.innerHTML = '<?php echo e(lang('Mail Host')); ?> <span class="text-red">*</span>';
                let inputmailhost = document.createElement('input');
                inputmailhost.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailhost.setAttribute('name', 'mail_host');
                inputmailhost.setAttribute('type', 'text');
                inputmailhost.setAttribute('value', `<?php echo e(old('mail_host', setting('mail_host'))); ?>`);
                let spanerror2 = document.createElement('span');
                spanerror2.setAttribute('class', 'text-red')
                spanerror2.setAttribute('id', 'mailhostError')

                // mailport
                let div12mailport = document.createElement('div');
                div12mailport.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailport = document.createElement('div');
                formgroupmailport.setAttribute('class', 'form-group');
                let formmlabelmailport = document.createElement('label');
                formmlabelmailport.setAttribute('class', 'form-label');
                formmlabelmailport.innerHTML = '<?php echo e(lang('Mail Port')); ?> <span class="text-red">*</span>';
                let inputmailport = document.createElement('input');
                inputmailport.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailport.setAttribute('name', 'mail_port');
                inputmailport.setAttribute('type', 'text');
                inputmailport.setAttribute('value', `<?php echo e(old('mail_port', setting('mail_port'))); ?>`);
                let spanerror3 = document.createElement('span');
                spanerror3.setAttribute('class', 'text-red');
                spanerror3.setAttribute('id', 'mailportError');

                // mailpassword
                let div12mailpassword = document.createElement('div');
                div12mailpassword.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailpassword = document.createElement('div');
                formgroupmailpassword.setAttribute('class', 'form-group');
                let formmlabelmailpassword = document.createElement('label');
                formmlabelmailpassword.setAttribute('class', 'form-label');
                formmlabelmailpassword.innerHTML = '<?php echo e(lang('Mail Password')); ?> <span class="text-red">*</span>';
                let inputmailpassword = document.createElement('input');
                inputmailpassword.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailpassword.setAttribute('name', 'mail_password');
                inputmailpassword.setAttribute('type', 'password');
                inputmailpassword.setAttribute('value', `<?php echo e(old('mail_password', setting('mail_password'))); ?>`);
                let spanerror5 = document.createElement('span');
                spanerror5.setAttribute('class', 'text-red');
                spanerror5.setAttribute('id', 'mailpasswordError');

                // mailencryption
                let div12mailencryption = document.createElement('div');
                div12mailencryption.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailencryption = document.createElement('div');
                formgroupmailencryption.setAttribute('class', 'form-group');
                let formmlabelmailencryption = document.createElement('label');
                formmlabelmailencryption.setAttribute('class', 'form-label');
                formmlabelmailencryption.innerHTML = '<?php echo e(lang('Mail Encryption')); ?> <span class="text-red">*</span>';
                let inputmailencryption = document.createElement('select');
                inputmailencryption.setAttribute('class',
                    `form-control select2form <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailencryption.setAttribute('name', 'mail_encryption');
                let spanerror6 = document.createElement('span');
                spanerror6.setAttribute('class', 'text-red');
                spanerror6.setAttribute('id', 'mailencryptionError');


                // mailfromname
                let div12mailfromname = document.createElement('div');
                div12mailfromname.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailfromname = document.createElement('div');
                formgroupmailfromname.setAttribute('class', 'form-group');
                let formmlabelmailfromname = document.createElement('label');
                formmlabelmailfromname.setAttribute('class', 'form-label');
                formmlabelmailfromname.innerHTML = '<?php echo e(lang('From Name')); ?> <span class="text-red">*</span>';
                let inputmailfromname = document.createElement('input');
                inputmailfromname.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailfromname.setAttribute('name', 'mail_from_name');
                inputmailfromname.setAttribute('type', 'text');
                inputmailfromname.setAttribute('value', `<?php echo e(old('mail_from_name', setting('mail_from_name'))); ?>`);
                let spanerror7 = document.createElement('span');
                spanerror7.setAttribute('class', 'text-red');
                spanerror7.setAttribute('id', 'fromnameError');

                // mailfromemail
                let div12mailfromemail = document.createElement('div');
                div12mailfromemail.setAttribute('class', 'col-sm-12 col-md-6 col-lg-6 col-xl-3 col-xxl-3 fromail');
                let formgroupmailfromemail = document.createElement('div');
                formgroupmailfromemail.setAttribute('class', 'form-group');
                let formmlabelmailfromemail = document.createElement('label');
                formmlabelmailfromemail.setAttribute('class', 'form-label');
                formmlabelmailfromemail.innerHTML = '<?php echo e(lang('From Email')); ?> <span class="text-red">*</span>';
                let inputmailfromemail = document.createElement('input');
                inputmailfromemail.setAttribute('class',
                    `form-control <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>`
                    );
                inputmailfromemail.setAttribute('name', 'mail_from_address');
                inputmailfromemail.setAttribute('type', 'email');
                inputmailfromemail.setAttribute('value', `<?php echo e(old('mail_from_address', setting('mail_from_address'))); ?>`);
                let spanerror8 = document.createElement('span');
                spanerror8.setAttribute('class', 'text-red');
                spanerror8.setAttribute('id', 'fromaddressError');

                // mailhost
                selectmail.append(div12mailhost);
                div12mailhost.append(formgroupmailhost);
                formgroupmailhost.append(formmlabelmailhost);
                formgroupmailhost.append(inputmailhost);
                formgroupmailhost.append(spanerror2);
                // mailport
                selectmail.append(div12mailport);
                div12mailport.append(formgroupmailport);
                formgroupmailport.append(formmlabelmailport);
                formgroupmailport.append(inputmailport);
                formgroupmailport.append(spanerror3);
                // mailpassword
                selectmail.append(div12mailpassword);
                div12mailpassword.append(formgroupmailpassword);
                formgroupmailpassword.append(formmlabelmailpassword);
                formgroupmailpassword.append(inputmailpassword);
                formgroupmailpassword.append(spanerror5);
                // mailencryption
                selectmail.append(div12mailencryption);
                div12mailencryption.append(formgroupmailencryption);
                formgroupmailencryption.append(formmlabelmailencryption);
                formgroupmailencryption.append(inputmailencryption);
                formgroupmailencryption.append(spanerror6);
                // mailfromname
                selectmail.append(div12mailfromname);
                div12mailfromname.append(formgroupmailfromname);
                formgroupmailfromname.append(formmlabelmailfromname);
                formgroupmailfromname.append(inputmailfromname);
                formgroupmailfromname.append(spanerror7);
                // mailfromemail
                selectmail.append(div12mailfromemail);
                div12mailfromemail.append(formgroupmailfromemail);
                formgroupmailfromemail.append(formmlabelmailfromemail);
                formgroupmailfromemail.append(inputmailfromemail);
                formgroupmailfromemail.append(spanerror8);


                //
                $('.select2form').select2();
                const optionvalue = ["ssl", "tls"]
                $.each(optionvalue, function(index, optionvalues) {
                    if ("<?php echo e(setting('mail_encryption')); ?>" == optionvalues) {
                        $('.select2form').append('<option value="' + optionvalues + '" selected>' + optionvalues +
                            '</option>');
                    } else {
                        $('.select2form').append('<option value="' + optionvalues + '">' + optionvalues + '</option>');
                    }
                })
            }

        })

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminmaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/email/email.blade.php ENDPATH**/ ?>