<!--Login Modal-->
<div class="modal fade" id="loginmodal">
    <div class="modal-dialog login-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(lang('Login', 'menu')); ?></h5>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <?php if(setting('login_disable') == 'on'): ?>
                    <div class="alert alert-light-warning br-13 border-0 text-center" role="alert">
                        <span class=""><?php echo e(setting('login_disable_statement')); ?></span>
                    </div>
                <?php endif; ?>
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
                                        title="<?php echo e(lang('Login with Envato')); ?>" onclick="window.location.href = envato;"
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
                                        title="<?php echo e(lang('Login with Google')); ?>" onclick="window.location.href = google;"
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
                            <?php if(setting('only_social_logins') == 'off'): ?>
                                <div class="text-center mt-5 login-form">
                                    <div class="divider">
                                        <?php echo e(lang('Or')); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="card-body border-top-0 pt-4">


                            <?php if(setting('only_social_logins') == 'off'): ?>

                                <form id="login_form" name="login_form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo view('honeypot::honeypotFormFields'); ?>

                                    <div class="form-group">
                                        <label class="form-label"><?php echo e(lang('Email')); ?> <span
                                                class="text-red">*</span></label>
                                        <input class="form-control " placeholder="<?php echo e(lang('Email')); ?>" type="email"
                                            name="email" required="" id="username">
                                        <div id="err" style="color: red"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo e(lang('Password')); ?> <span
                                                class="text-red">*</span></label>
                                        <input class="form-control" placeholder="<?php echo e(lang('password')); ?>"
                                            type="password" id="password" name="password" required="">
                                        <div id="passworderr" style="color: red"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="custom-control form-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remember"
                                                id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                            <span class="custom-control-label"><?php echo e(lang('Remember Me')); ?></span>
                                        </label>
                                    </div>
                                    <?php if(setting('CAPTCHATYPE') == 'manual'): ?>
                                        <?php if(setting('RECAPTCH_ENABLE_LOGIN') == 'yes'): ?>
                                            <div class="form-group row">
                                                <div class="col-md-12 mb-3">
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
                                                    <div id="captchaerr" style="color: red"></div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="captcha d-flex border mt-2">
                                                        <span class="mx-auto"><?php echo captcha_img(''); ?></span>
                                                        <button type="button"
                                                            class="btn btn-secondary btn-icon captchabtn"><i
                                                                class="fe fe-refresh-cw"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <!--- if Enable the Google ReCaptcha --->
                                    <div class="form-group">
                                        <?php if(setting('CAPTCHATYPE') == 'google'): ?>
                                            <?php if(setting('RECAPTCH_ENABLE_LOGIN') == 'yes'): ?>
                                                <div class="g-recaptcha <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    data-callback="recaptchaCallback"
                                                    data-sitekey="<?php echo e(setting('GOOGLE_RECAPTCHA_KEY')); ?>"></div>
                                                <span class="text-red" id="captchaError"></span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                    <!--- End Google ReCaptcha --->
                                    <div class="submit">
                                        <button type="button" class="btn btn-secondary btn-block" id="loginbtnsave"
                                            onclick="login();"><?php echo e(lang('Login')); ?></button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="#" id="forgot1"
                                            class="text-primary pb-2"><?php echo e(lang('Forgot Password?')); ?></a>
                                        <?php if(setting('REGISTER_DISABLE') == 'on'): ?>
                                            <p class="text-dark mb-0"><?php echo e(lang('Don’t have account?')); ?><a
                                                    class="text-primary ms-1" href="#" data-bs-toggle="modal"
                                                    id="register1"
                                                    data-bs-target="#registermodal"><?php echo e(lang('Register', 'menu')); ?></a>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Login Modal  -->

<script type="text/javascript">
    "use strict";

    var microsoft = "<?php echo e(route('social.login', 'microsoft')); ?>";
    var google = "<?php echo e(route('social.login', 'google')); ?>";
    var envato = "<?php echo e(route('social.login', 'envato')); ?>";


    $(function() {
        //set button id on click to hide first modal
        $("#register1").on("click", function() {
            $('#loginmodal').modal('hide');
            $('#login_form').trigger("reset");

        });

        //trigger next modal
        $("#register1").on("click", function() {
            $('#registermodal').modal('show');

        });

        $("#forgot1").on("click", function() {
            $('#loginmodal').modal('hide');
            $('#login_form').trigger("reset");

        });

        //trigger next modal
        $("#forgot1").on("click", function() {
            $('#forgotmodal').modal('show');

        });

        // Captcha Js
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

        // Captcha Js on load function
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '<?php echo e(route('captcha.reload')); ?>',
                success: function(res) {
                    $(".captcha span").html(res.captcha);
                }
            });
        });
    })

    // Login Js submit Js
    function login() {
        $('#loginbtnsave').html('<i class="fa fa-spinner fa-spin"></i>');
        if ($('#username').val() == "") {
            $('#err').html('<?php echo e(lang('Please enter the email')); ?>');
            $('#loginbtnsave').html('Loigin');
            return false;
        }

        if ($('#password').val() == "") {
            $('#passworderr').html('<?php echo e(lang('Please enter the password.')); ?>');
            $('#loginbtnsave').html('Loigin');
            return false;
        }

        var data = $("#login_form").serialize();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '<?php echo e(route('client.do_ajaxlogin')); ?>',
            data: data,
            success: function(response) {
                if (response?.errors) {
                    $("#err").hide().html(response.errors.email).fadeIn('slow');

                    $("#captchaerr").hide().html("Invalid CAPTCHA. Please try again.").fadeIn('slow');
                    if (response?.errors["g-recaptcha-response"]?.[0]) {
                        $("#captchaError").html(response.errors["g-recaptcha-response"][0]);
                    }

                    if (response?.errors == 'Your domain is blocked.') {
                        toastr.error(response.errors);
                    }
                }
                if (response == 1) {
                    window.location.replace('<?php echo e(route('client.dashboard')); ?>');
                } else if (response == 3) {
                    toastr.error(
                        '<?php echo e(lang('The username or password you entered is incorrect. Please Check', 'alerts')); ?>'
                        );
                    $("#err").hide().html(
                        "<?php echo e(lang('The username or password you entered is incorrect. Please Check', 'alerts')); ?>"
                        ).fadeIn('slow');
                } else if (response.error == 4) {
                    toastr.error('<?php echo e(lang('Please verify your email.', 'alerts')); ?>');
                } else if (response == 5) {
                    toastr.error(
                        '<?php echo e(lang('Your account is currently inactive. Please contact the admin.', 'alerts')); ?>'
                        );
                } else if (response == 30) {
                    toastr.warning('<?php echo e(lang('Temporary Login disable', 'alerts')); ?>');
                }
                $('#loginbtnsave').html('Loigin');
            }
        });
    }
</script>

<!-- Captcha Js -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php /**PATH /var/www/html/resources/views/user/auth/modalspopup/login.blade.php ENDPATH**/ ?>