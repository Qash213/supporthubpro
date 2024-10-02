        <!--Register Modal-->
        <div class="modal fade" id="registermodal">
            <div class="modal-dialog register-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(lang('Register', 'menu')); ?></h5>
                        <button class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="single-page customerpage">
                            <div class="wrapper wrapper2 box-shadow-0 border-0">

                                <?php if(
                                        $socialAuthSettings->envato_status == 'enable' ||
                                        $socialAuthSettings->google_status == 'enable' ||
                                        $socialAuthSettings->microsoft_status == 'enable'
                                    ): ?>

                                    <div class="login-icons card-body pt-3 pb-0 text-center justify-content-center">
                                        <?php if($socialAuthSettings->envato_status == 'enable'): ?>
                                            <a class="btn header-buttons text-start social-icon-2 btn-lg btn-lime text-white mb-4 btn-block p-0"
                                                href="javascript:;" data-bs-toggle="tooltip"
                                                title="<?php echo e(lang('Login with Envato')); ?>"
                                                onclick="window.location.href = envato;"
                                                data-bs-original-title="<?php echo e(lang('Login with Envato')); ?>">
                                                <div class="d-inline w-15 justify-content-center">
                                                    <svg class="px-4 py-2 my-auto border-end border-white-1"
                                                        style="enable-background:new 0 0 512 512;" version="1.1"
                                                        viewBox="0 0 512 512" xml:space="preserve"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <g id="_x38_5-envato">
                                                            <g>
                                                                <g>
                                                                    <g>
                                                                        <path fill="#fff"
                                                                            d="M401.225,19.381c-17.059-8.406-103.613,1.196-166.01,61.218      c-98.304,98.418-95.947,228.089-95.947,228.089s-3.248,13.335-17.086-6.011c-30.305-38.727-14.438-127.817-12.651-140.23      c2.508-17.494-8.615-17.999-13.243-12.229c-109.514,152.46-10.616,277.288,54.136,316.912c75.817,46.386,225.358,46.354,284.922-85.231C509.547,218.042,422.609,29.875,401.225,19.381L401.225,19.381z M401.225,19.381">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g id="Layer_1"></g>
                                                    </svg>
                                                </div>

                                                <span
                                                    class="px-4 py-2 my-auto text-white"><?php echo e(lang('Login with Envato')); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($socialAuthSettings->google_status == 'enable'): ?>
                                            <a class="btn header-buttons text-start social-icon-2 btn-lg btn-google text-white mb-4 btn-block p-0"
                                                href="javascript:;" data-bs-toggle="tooltip"
                                                title="<?php echo e(lang('Login with Google')); ?>"
                                                onclick="window.location.href = google;"
                                                data-bs-original-title="<?php echo e(lang('Login with Google')); ?>">
                                                <div
                                                    class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                                                    <i class="fa fa-google"></i>
                                                </div>
                                                <span
                                                    class="px-4 py-2 my-auto text-white"><?php echo e(lang('Login with Google')); ?></span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($socialAuthSettings->microsoft_status == 'enable'): ?>
                                            <a class="btn header-buttons text-start social-icon-2 btn-lg btn-facebook text-white mb-4 btn-block p-0"
                                                href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Microsoft')); ?>"
                                                onclick="window.location.href = microsoft;"
                                                data-bs-original-title="<?php echo e(lang('Login with Microsoft')); ?>">
                                                <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                                                    <i class="fa fa-windows" aria-hidden="true"></i>
                                                </div>
                                                <span class="px-4 py-2 my-auto text-white"><?php echo e(lang('Login with Microsoft')); ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-center mt-5 login-form">
                                        <div class="divider">
                                            <?php echo e(lang('Or')); ?>

                                        </div>
                                    </div>

                                <?php endif; ?>

                                <div class="card-body border-top-0 pt-4">
                                    <?php if(setting('REGISTER_DISABLE') == 'off'): ?>
                                        <div class="alert alert-light-warning br-13 border-0 text-center"
                                            role="alert">

                                            <span class=""><?php echo e(setting('login_disable_statement')); ?></span>

                                        </div>
                                    <?php endif; ?>
                                    <form id="registerform">
                                        <?php echo csrf_field(); ?>

                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-grouphas-feedback ">
                                                    <label class="form-label"><?php echo e(lang('First Name')); ?> <span
                                                            class="text-red">*</span></label>
                                                    <input class="form-control " placeholder="<?php echo e(lang('Firstname')); ?>"
                                                        type="text" name="firstname" required="">
                                                    <span class="text-danger">
                                                        <strong id="firstnameerror"></strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="form-label"><?php echo e(lang('Last Name')); ?> <span
                                                            class="text-red">*</span></label>
                                                    <input class="form-control " placeholder="<?php echo e(lang('Lastname')); ?>"
                                                        type="text" name="lastname">
                                                    <span class="text-danger">
                                                        <strong id="lastnameerror"></strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label class="form-label" for="emailInput"><?php echo e(lang('Email')); ?> <span
                                                    class="text-red">*</span></label>
                                            <input class="form-control" id="emailInput"
                                                placeholder="<?php echo e(lang('Email')); ?>" type="email" name="email">
                                            <span class="text-danger">
                                                <strong id="emailerror"></strong>
                                            </span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label class="form-label"><?php echo e(lang('Password')); ?> <span
                                                    class="text-red">*</span></label>
                                            <input class="form-control" placeholder="<?php echo e(lang('password')); ?>"
                                                type="password" name="password">
                                            <span class="text-danger">
                                                <strong id="passworderror"></strong>
                                            </span>
                                        </div>
                                        <div class="form-group has-feedback ">
                                            <label class="form-label"><?php echo e(lang('Confirm Password')); ?> <span
                                                    class="text-red">*</span></label>
                                            <input class="form-control" placeholder="<?php echo e(lang('password')); ?>"
                                                type="password" name="password_confirmation">
                                            <span class="invalid-feedback d-block" role="alert"
                                                id="password_confirmationError">
                                                <strong></strong>
                                            </span>
                                        </div>
                                        <?php
                                            use App\Models\Customfield;
                                            $customfields = Customfield::whereIn('displaytypes', [
                                                'both',
                                                'registerform',
                                            ])
                                                ->where('status', 1)
                                                ->get();
                                        ?>
                                        <?php if($customfields->isNotEmpty()): ?>
                                            <?php $__currentLoopData = $customfields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customfield): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-group ">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label"><?php echo e($customfield->fieldnames); ?>

                                                                <?php if($customfield->fieldrequired == '1'): ?>
                                                                    <span class="text-red">*</span>
                                                                <?php endif; ?>
                                                            </label>

                                                            <?php if($customfield->fieldtypes == 'text'): ?>
                                                                <input type="<?php echo e($customfield->fieldtypes); ?>"
                                                                    class="form-control"
                                                                    name="custom_<?php echo e($customfield->id); ?>"
                                                                    id=""
                                                                    <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                                            <?php endif; ?>
                                                            <?php if($customfield->fieldtypes == 'email'): ?>
                                                                <input type="<?php echo e($customfield->fieldtypes); ?>"
                                                                    class="form-control"
                                                                    name="custom_<?php echo e($customfield->id); ?>"
                                                                    id=""
                                                                    <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                                            <?php endif; ?>
                                                            <?php if($customfield->fieldtypes == 'textarea'): ?>
                                                                <textarea name="custom_<?php echo e($customfield->id); ?>" class="form-control" id="" cols="30" rows="5"
                                                                    <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>></textarea>
                                                            <?php endif; ?>
                                                            <?php if($customfield->fieldtypes == 'checkbox'): ?>
                                                                <?php
                                                                    $coptions = explode(
                                                                        ',',
                                                                        $customfield->fieldoptions,
                                                                    );
                                                                ?>
                                                                <?php $__currentLoopData = $coptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $coption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <label
                                                                        class="custom-control form-checkbox d-inline-block me-3">
                                                                        <input type="<?php echo e($customfield->fieldtypes); ?>"
                                                                            class="custom-control-input <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>"
                                                                            name="custom_<?php echo e($customfield->id); ?>[]"
                                                                            value="<?php echo e($coption); ?>"
                                                                            id="">

                                                                        <span
                                                                            class="custom-control-label"><?php echo e($coption); ?></span>
                                                                    </label>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                            <?php if($customfield->fieldtypes == 'select'): ?>
                                                                <select name="custom_<?php echo e($customfield->id); ?>"
                                                                    id=""
                                                                    class="form-control select2-show-search"
                                                                    data-placeholder="<?php echo e(lang('Select')); ?>"
                                                                    <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                                                    <?php
                                                                        $seoptions = explode(
                                                                            ',',
                                                                            $customfield->fieldoptions,
                                                                        );
                                                                    ?>
                                                                    <option></option>
                                                                    <?php $__currentLoopData = $seoptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seoption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($seoption); ?>">
                                                                            <?php echo e($seoption); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            <?php endif; ?>
                                                            <?php if($customfield->fieldtypes == 'radio'): ?>
                                                                <?php
                                                                    $roptions = explode(
                                                                        ',',
                                                                        $customfield->fieldoptions,
                                                                    );
                                                                ?>
                                                                <?php $__currentLoopData = $roptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <label
                                                                        class="custom-control form-radio d-inline-block me-3">
                                                                        <input type="<?php echo e($customfield->fieldtypes); ?>"
                                                                            class="custom-control-input"
                                                                            name="custom_<?php echo e($customfield->id); ?>"
                                                                            value="<?php echo e($roption); ?>"
                                                                            <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                                                        <span
                                                                            class="custom-control-label"><?php echo e($roption); ?></span>
                                                                    </label>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                        <div class="form-group has-feedback">
                                            <label class="custom-control form-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="agreed"
                                                    name="agree_terms">
                                                <span class="custom-control-label"><?php echo e(lang('I agree with')); ?> <a
                                                        href="<?php echo e(setting('terms_url')); ?>"
                                                        class="text-primary" target="_blank"><?php echo e(lang('Terms of services')); ?></a>
                                                </span>
                                            </label>
                                            <span class="text-danger" role="alert">
                                                <strong id="exampleError"></strong>
                                            </span>
                                        </div>


                                        <?php if(setting('CAPTCHATYPE') == 'manual'): ?>
                                            <?php if(setting('RECAPTCH_ENABLE_REGISTER') == 'yes'): ?>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <input type="text" id="captcha"
                                                            class="form-control <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                            placeholder="<?php echo e(lang('Enter Captcha')); ?>" name="captcha">
                                                        <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong><?php echo e(lang($message)); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        <span class="text-danger" role="alert">
                                                            <strong id="captchaError"></strong>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="captcha">
                                                            <span><?php echo captcha_img(''); ?></span>
                                                            <button type="button"
                                                                class="btn btn-outline-info btn-sm captchabtn"><i
                                                                    class="fe fe-refresh-cw"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!--- if Enable the Google ReCaptcha --->
                                        <?php if(setting('CAPTCHATYPE') == 'google'): ?>
                                            <?php if(setting('RECAPTCH_ENABLE_REGISTER') == 'yes'): ?>
                                                <div class="form-group">
                                                    <div class="g-recaptcha <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        data-sitekey="<?php echo e(setting('GOOGLE_RECAPTCHA_KEY')); ?>"></div>
                                                    <span class="text-danger" role="alert">
                                                        <strong id="googleError"></strong>
                                                    </span>

                                                    <!-- <?php if($errors->has('g-recaptcha-response')): ?>
<span class="invalid-feedback d-block" role="alert">
                                                            <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                                                        </span>
<?php endif; ?> -->
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div>
                                            
                                            <button type="button" class="btn btn-secondary btn-block"
                                                id="submitForm"><?php echo e(lang('Create Account')); ?></button>

                                        </div>
                                        <div class="text-center mt-4">
                                            <p class="text-dark mb-0"><?php echo e(lang('Already have an account?')); ?><a
                                                    class="text-primary ms-1" href="#" data-bs-toggle="modal"
                                                    id="login"
                                                    data-bs-target="#loginmodal"><?php echo e(lang('Login', 'menu')); ?></a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Register Modal  -->

        <script type="text/javascript">
                "use strict";

                var microsoft = "<?php echo e(route('social.login', 'microsoft')); ?>";
                var google = "<?php echo e(route('social.login', 'google')); ?>";
                var envato = "<?php echo e(route('social.login', 'envato')); ?>";

                $(function() {
                    //set button id on click to hide first modal
                    $("#login").on("click", function() {

                        $('#registermodal').modal('hide');
                        $('#registerform').trigger("reset");

                    });

                    //trigger next modal
                    $("#login").on("click", function() {
                        $('#loginmodal').modal('show');

                    });

                    (function($) {

                        $(".captchabtn").on('click', function(e) {
                            e.preventDefault();
                            $.ajax({
                                type: 'GET',
                                url: '<?php echo e(route('captcha.reload')); ?>',
                                success: function(res) {
                                    $(".captcha span").html(res.captcha);
                                }
                            });
                        });

                        $(document).ready(function() {
                            $.ajax({
                                type: 'GET',
                                url: '<?php echo e(route('captcha.reload')); ?>',
                                success: function(res) {
                                    $(".captcha span").html(res.captcha);
                                }
                            });
                        });

                        // End Register Js
                        $('#registermodal').on('click', '#submitForm', function() {
                            var registerForm = $("#registerform");
                            var formData = registerForm.serialize();

                            $('#submitForm').html('<i class="fa fa-spinner fa-spin"></i>');
                            $('#firstnameerror').html("");
                            $('#lastnameerror').html("");
                            $('#emailerror').html("");
                            $('#passworderror').html("");
                            $('#exampleError').html("");
                            $('#captchaError').html("");
                            $('#googleError').html("");
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            let checked = document.querySelectorAll('.required:checked').length;
                            var isValid = checked > 0;
                            if (document.querySelectorAll('.required').length == '0') {
                                ajax(formData);
                            } else {

                                if (isValid) {
                                    ajax(formData);
                                } else {
                                    $('#submitForm').html('Create Account');
                                    toastr.error('<?php echo e(lang('Check the all field(*) required', 'alerts')); ?>')
                                }
                            }

                        });


                        function ajax(formData) {
                            $.ajax({
                                url: '<?php echo e(url('customer/register1')); ?>',
                                type: 'POST',
                                data: formData,
                                success: function(data) {
                                    $('#submitForm').html('Create Account');
                                    if (data.errors) {
                                        if (data.errors.firstname) {
                                            $('#firstnameerror').html(data.errors.firstname[0]);
                                        }
                                        if (data.errors.lastname) {
                                            $('#lastnameerror').html(data.errors.lastname[0]);
                                        }
                                        if (data.errors.email) {
                                            $('#emailerror').html(data.errors.email[0]);
                                        }
                                        if (data.errors.password) {
                                            $('#passworderror').html(data.errors.password[0]);
                                        }
                                        if (data.errors.agree_terms) {
                                            $('#exampleError').html(data.errors.agree_terms[0]);
                                        }
                                        if (data.errors.captcha) {
                                            $('#captchaError').html(data.errors.captcha[0]);
                                        }
                                        if (data.errors["g-recaptcha-response"][0]) {
                                            $('#googleError').html(data.errors["g-recaptcha-response"][0]);
                                        }

                                    }
                                    if (data.success) {

                                        toastr.success(
                                            "<?php echo e(lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')); ?>"
                                            );
                                        $('#registermodal').modal('hide');
                                        $('#registerform').trigger("reset");
                                    }
                                },
                            });
                        }



                    })(jQuery);
                })
        </script>

        <!-- Captrcha Js-->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php /**PATH /var/www/html/resources/views/user/auth/modalspopup/register.blade.php ENDPATH**/ ?>