<?php $__env->startSection('styles'); ?>
    <!-- Select2 css -->
    <link href="<?php echo e(asset('build/assets/plugins/select2/select2.min.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="pb-0 px-5 pt-0 text-center">
        <h3 class="mb-2"><?php echo e(lang('Register', 'menu')); ?></h3>
        <p class="text-muted fs-13 mb-1"><?php echo e(lang('Create new account')); ?></p>
    </div>
    <?php if(
            $socialAuthSettings->envato_status == 'enable' ||
            $socialAuthSettings->google_status == 'enable' ||
            $socialAuthSettings->microsoft_status == 'enable'
        ): ?>
        <div class="login-icons card-body pt-3 pb-0 text-center justify-content-center">
            <?php if($socialAuthSettings->envato_status == 'enable'): ?>
                <a class="btn header-buttons text-start social-icon-2 btn-lg btn-lime text-white mb-4 btn-block p-0"
                    href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Envato')); ?>"
                    onclick="window.location.href = envato;" data-bs-original-title="<?php echo e(lang('Login with Envato')); ?>">
                    <div class="d-inline w-15 justify-content-center">
                        <svg class="px-4 py-2 my-auto border-end border-white-1" style="enable-background:new 0 0 512 512;"
                            version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
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

                    <span class="px-4 py-2 my-auto text-white">Login with Envato</span>
                </a>
            <?php endif; ?>
            <?php if($socialAuthSettings->google_status == 'enable'): ?>
                <a class="btn header-buttons text-start social-icon-2 btn-lg btn-google text-white mb-4 btn-block p-0"
                    href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Google')); ?>"
                    onclick="window.location.href = google;" data-bs-original-title="<?php echo e(lang('Login with Google')); ?>">
                    <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                        <i class="fa fa-google"></i>
                    </div>
                    <span class="px-4 py-2 my-auto text-white">Login with Google</span>
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
                Or
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body border-top-0 pt-3 pb-0">
        <?php if(setting('REGISTER_DISABLE') == 'off'): ?>
            <div class="alert alert-light-warning br-13 border-0 text-center" role="alert">

                <span class=""><?php echo e(setting('login_disable_statement')); ?></span>

            </div>
        <?php endif; ?>
        <form method="post" id="register_form">

            <?php echo view('honeypot::honeypotFormFields'); ?>

            <div class="form-group row">
                <div class="col-sm-6 col-md-6 mb-2 mb-sm-0">
                    <label class="form-label"><?php echo e(lang('First Name')); ?> <span class="text-red">*</span></label>
                    <input class="form-control <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(lang('Firstname')); ?>" type="text" name="firstname"
                        value="<?php echo e(old('firstname')); ?>">
                    <span class="text-red" id="firstnameError"></span>
                    <?php $__errorArgs = ['firstname'];
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

                </div>
                <div class="col-sm-6 col-md-6">
                    <label class="form-label"><?php echo e(lang('Last Name')); ?> <span class="text-red">*</span></label>
                    <input class="form-control <?php $__errorArgs = ['lastname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(lang('Lastname')); ?>" type="text" name="lastname" value="<?php echo e(old('lastname')); ?>">
                    <span class="text-red" id="lastnameError"></span>
                    <?php $__errorArgs = ['lastname'];
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

                </div>

            </div>
            <div class="form-group">
                <label class="form-label"><?php echo e(lang('Email')); ?> <span class="text-red">*</span></label>
                <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('Email')); ?>"
                    type="email" name="email" value="<?php echo e(old('email')); ?>" autocomplete="username">
                <span class="text-red" id="emailError"></span>
                <?php $__errorArgs = ['email'];
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

            </div>
            <div class="form-group row">
                <div class="col-sm-6 col-md-6">
                    <label class="form-label"><?php echo e(lang('Password')); ?> <span class="text-red">*</span></label>
                    <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(lang('password')); ?>" type="password" name="password"
                        autocomplete="new-password">
                    <span class="text-red" id="passwordError"></span>
                    <?php $__errorArgs = ['password'];
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

                </div>
                <div class="col-sm-6 col-md-6">
                    <label class="form-label"><?php echo e(lang('Confirm Password')); ?> <span class="text-red">*</span></label>
                    <input class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="<?php echo e(lang('password')); ?>" type="password" name="password_confirmation"
                        autocomplete="new-password">
                    <span class="text-red" id="passwordconfirmError"></span>
                    <?php $__errorArgs = ['password_confirmation'];
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
                </div>
            </div>
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
                                    <input type="<?php echo e($customfield->fieldtypes); ?>" class="form-control"
                                        name="custom_<?php echo e($customfield->id); ?>"
                                        <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                <?php endif; ?>
                                <?php if($customfield->fieldtypes == 'email'): ?>
                                    <input type="<?php echo e($customfield->fieldtypes); ?>" class="form-control"
                                        name="custom_<?php echo e($customfield->id); ?>"
                                        <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                <?php endif; ?>
                                <?php if($customfield->fieldtypes == 'textarea'): ?>
                                    <textarea name="custom_<?php echo e($customfield->id); ?>" class="form-control" cols="30" rows="5"
                                        <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>></textarea>
                                <?php endif; ?>
                                <?php if($customfield->fieldtypes == 'checkbox'): ?>
                                    <?php
                                        $coptions = explode(',', $customfield->fieldoptions);
                                    ?>
                                    <?php $__currentLoopData = $coptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $coption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="custom-control form-checkbox d-inline-block me-3">
                                            <input type="<?php echo e($customfield->fieldtypes); ?>"
                                                class="custom-control-input <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>"
                                                name="custom_<?php echo e($customfield->id); ?>[]" value="<?php echo e($coption); ?>">

                                            <span class="custom-control-label"><?php echo e($coption); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if($customfield->fieldtypes == 'select'): ?>
                                    <select name="custom_<?php echo e($customfield->id); ?>"
                                        class="form-control select2 select2-show-search"
                                        data-placeholder="<?php echo e(lang('Select')); ?>"
                                        <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                        <?php
                                            $seoptions = explode(',', $customfield->fieldoptions);
                                        ?>
                                        <option></option>
                                        <?php $__currentLoopData = $seoptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seoption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($seoption); ?>"><?php echo e($seoption); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                <?php endif; ?>
                                <?php if($customfield->fieldtypes == 'radio'): ?>
                                    <?php
                                        $roptions = explode(',', $customfield->fieldoptions);
                                    ?>
                                    <?php $__currentLoopData = $roptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="custom-control form-radio d-inline-block me-3">
                                            <input type="<?php echo e($customfield->fieldtypes); ?>" class="custom-control-input"
                                                name="custom_<?php echo e($customfield->id); ?>" value="<?php echo e($roption); ?>"
                                                <?php echo e($customfield->fieldrequired == '1' ? 'required' : ''); ?>>
                                            <span class="custom-control-label"><?php echo e($roption); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>



            <?php if(setting('CAPTCHATYPE') == 'manual'): ?>
                <?php if(setting('RECAPTCH_ENABLE_REGISTER') == 'yes'): ?>
                    <div class="form-group row <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <div class="col-sm-6 col-md-6">
                            <input type="text" id="captcha" class="form-control "
                                placeholder="<?php echo e(lang('Enter Captcha')); ?>" name="captcha">

                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="captcha">
                                <span><?php echo captcha_img(''); ?></span>
                                <button type="button" class="btn btn-outline-info btn-sm captchabtn"><i
                                        class="fe fe-refresh-cw"></i></button>
                            </div>
                        </div>
                    </div>
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
                <?php endif; ?>
            <?php endif; ?>

            <!--- if Enable the Google ReCaptcha --->
            <div class="form-group">
                <?php if(setting('CAPTCHATYPE') == 'google'): ?>
                    <?php if(setting('RECAPTCH_ENABLE_REGISTER') == 'yes'): ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo e(setting('GOOGLE_RECAPTCHA_KEY')); ?>"></div>

                        <!-- <?php if($errors->has('g-recaptcha-response')): ?>
    <span class="invalid-feedback text-danger" role="alert">
                    <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                </span>
    <?php endif; ?> -->
                        <span class="text-danger" id="captchaError"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="form-group <?php $__errorArgs = ['agree_terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <label class="custom-control form-checkbox">
                    <input type="checkbox" class="custom-control-input " value="agreed" name="agree_terms">
                    <span class="custom-control-label"><?php echo e(lang('I agree with')); ?> <a href="<?php echo e(setting('terms_url')); ?>"
                            class="text-primary" target="_blank">
                            <?php echo e(lang('Terms of services')); ?></a></span>
                </label>
            </div>
            <span class="text-red" id="agreetermsError"></span>
            <?php $__errorArgs = ['agree_terms'];
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

            <div class="submit">

                <button type="submit" class="btn btn-secondary btn-block"
                    id="register_button"><?php echo e(lang('Create Account')); ?></button>

            </div>

            <div class="text-center mt-4">
                <p class="text-dark mb-0"><?php echo e(lang('Already have an account?')); ?><a class="text-primary ms-1"
                        href="<?php echo e(url('customer/login')); ?>"><?php echo e(lang('Login', 'menu')); ?></a></p>
                <p class="text-dark mb-0"><a class="text-primary ms-1"
                        href="<?php echo e(url('/')); ?>"><?php echo e(lang('Send me Back')); ?></a></p>

            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <!-- Captcha js-->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Select2 js -->
    <script src="<?php echo e(asset('build/assets/plugins/select2/select2.full.min.js')); ?>?v=<?php echo time(); ?>"></script>


    <link href="<?php echo e(asset('build/assets/plugins/select2/select2.min.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

    <script type="text/javascript">
        $(function() {
            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Variables
            var microsoft = "<?php echo e(route('social.login', 'microsoft')); ?>";
            var google = "<?php echo e(route('social.login', 'google')); ?>";
            var envato = "<?php echo e(route('social.login', 'envato')); ?>";

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

                setTimeout(() => {
                    $('.select2-show-search').select2({
                        minimumResultsForSearch: '',
                        placeholder: "Search",
                        width: '100%'
                    });
                }, 500)

                $('body').on('submit', '#register_form', function(e) {
                    e.preventDefault();
                    $('#firstnameError').html('');
                    $('#lastnameError').html('');
                    $('#emailError').html('');
                    $('#passwordError').html('');
                    $('#passwordconfirmError').html('');
                    $('#agreetermsError').html('');
                    $('#register_button').html(`Loading.. <i class="fa fa-spinner fa-spin"></i>`);
                    $('#register_button').prop('disabled', true);


                    var formData = new FormData(this);
                    let checked = document.querySelectorAll('.required:checked').length;
                    var isValid = checked > 0;
                    if (document.querySelectorAll('.required').length == '0') {
                        ajax(formData);
                    } else {

                        if (isValid) {
                            ajax(formData);
                        } else {
                            $('#register_button').prop('disabled', false);
                            $('#register_button').html(`<?php echo e(lang('Create Ticket', 'menu')); ?>`);
                            toastr.error('<?php echo e(lang('Check the all field(*) required', 'alerts')); ?>')
                        }
                    }
                });


                function ajax(formData) {

                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: "<?php echo e(route('auth.register')); ?>",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: (data) => {
                            if (data.error) {
                                toastr.error(data.error);
                            }
                            if (data.success) {
                                toastr.success(data.success);
                                window.location.replace('<?php echo e(url('customer/login')); ?>')
                            }


                        },
                        error: function(data) {
                            if (data.error) {
                                toastr.error(data.error);
                            }
                            if (data.responseJSON && data.responseJSON.errors) {
                                if (data.responseJSON.errors["g-recaptcha-response"]) {
                                    $('#capchaError').html(data.responseJSON.errors[
                                        "g-recaptcha-response"][0]);
                                }
                                $('#firstnameError').html(data.responseJSON.errors.firstname);
                                $('#lastnameError').html(data.responseJSON.errors.lastname);
                                $('#emailError').html(data.responseJSON.errors.email);
                                $('#passwordError').html(data.responseJSON.errors.password);
                                $('#passwordconfirmError').html(data.responseJSON.errors
                                    .password_confirmation);
                                $('#agreetermsError').html(data.responseJSON.errors.agree_terms);
                                $('#register_button').prop('disabled', false);
                                $('#register_button').html(`<?php echo e(lang('Create Account', 'menu')); ?>`);
                            }
                        }
                    });
                }

            })(jQuery);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customregistermaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/user/auth/register.blade.php ENDPATH**/ ?>