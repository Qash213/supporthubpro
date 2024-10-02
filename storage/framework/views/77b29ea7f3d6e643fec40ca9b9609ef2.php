<?php $__env->startSection('content'); ?>

    <div class="pb-3 px-5 pt-0 text-center">
        <h3 class="mb-2"><?php echo e(lang('Login', 'menu')); ?></h3>
        <p class="text-muted fs-13 mb-1"><?php echo e(lang('Sign In to your account')); ?></p>
    </div>

    <?php if(setting('login_disable') == 'on'): ?>
        <div class="alert alert-light-warning br-13 border-0 text-center mx-5" role="alert">

            <span class=""><?php echo e(setting('login_disable_statement')); ?></span>

        </div>
    <?php endif; ?>
    <?php if(
            $socialAuthSettings->envato_status == 'enable' ||
            $socialAuthSettings->google_status == 'enable' ||
            $socialAuthSettings->microsoft_status == 'enable'
        ): ?>
        <div class="login-icons card-body pt-3 pb-0 text-center justify-content-center">
            <?php if($socialAuthSettings->envato_status == 'enable'): ?>
                <a class="btn header-buttons text-start social-icon-2 btn-lg btn-lime text-white mb-4 btn-block p-0"
                    href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Envato')); ?>"
                    <?php if(setting('login_disable') == 'off'): ?> onclick="window.location.href = envato;" <?php endif; ?>
                    data-bs-original-title="<?php echo e(lang('Login with Envato')); ?>">
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

                    <span class="px-4 py-2 my-auto text-white"><?php echo e(lang('Login with Envato')); ?></span>
                </a>
            <?php endif; ?>
            <?php if($socialAuthSettings->google_status == 'enable'): ?>
                <a class="btn header-buttons text-start social-icon-2 btn-lg btn-google text-white mb-4 btn-block p-0"
                    href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Google')); ?>"
                    <?php if(setting('login_disable') == 'off'): ?> onclick="window.location.href = google;" <?php endif; ?>
                    data-bs-original-title="<?php echo e(lang('Login with Google')); ?>">
                    <div class="d-inline-flex w-7 border-end border-white-1 px-4 py-2 my-auto justify-content-center">
                        <i class="fa fa-google"></i>
                    </div>
                    <span class="px-4 py-2 my-auto text-white"><?php echo e(lang('Login with Google')); ?></span>
                </a>
            <?php endif; ?>
            <?php if($socialAuthSettings->microsoft_status == 'enable'): ?>
                <a class="btn header-buttons text-start social-icon-2 btn-lg btn-facebook text-white mb-4 btn-block p-0"
                    href="javascript:;" data-bs-toggle="tooltip" title="<?php echo e(lang('Login with Microsoft')); ?>"
                    <?php if(setting('login_disable') == 'off'): ?> onclick="window.location.href = microsoft;" <?php endif; ?>
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


        <form id="login_form" name="login_form" method="post">
            <?php echo csrf_field(); ?>
            <?php echo view('honeypot::honeypotFormFields'); ?>

            <div class="form-group">
                <label class="form-label"><?php echo e(lang('Email')); ?> <span class="text-red">*</span></label>
                <input class="form-control" placeholder="<?php echo e(lang('Email')); ?>" type="email" name="email" required=""
                    id="username" value="<?php echo e(old('email')); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label"><?php echo e(lang('Password')); ?> <span class="text-red">*</span></label>
                <input class="form-control" placeholder="<?php echo e(lang('Password')); ?>" type="password" id="password"
                    name="password" required="">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger d-block"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <?php if(setting('CAPTCHATYPE') == 'manual'): ?>
                <?php if(setting('RECAPTCH_ENABLE_LOGIN') == 'yes'): ?>
                    <div class="form-group row">
                        <div class="col-md-12 mb-3">
                            <input type="text" id="captcha" class="form-control <?php $__errorArgs = ['captcha'];
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
                            <div class="captcha d-flex border">
                                <span class="mx-auto mt-2"><?php echo captcha_img(''); ?></span>
                                <button type="button" class="btn btn-secondary btn-icon captchabtn"><i
                                        class="fe fe-refresh-cw"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

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
                            data-callback="recaptchaCallback" data-sitekey="<?php echo e(setting('GOOGLE_RECAPTCHA_KEY')); ?>"></div>
                        <span class="text-red" id="captchaError"></span>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="form-group">
                    <label class="custom-control form-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" id="remember"
                            <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <span class="custom-control-label"><?php echo e(lang('Remember Me')); ?></span>
                    </label>
                </div>

            </div>
            <!--- End Google ReCaptcha --->

            <div class="submit">
                <button type="submit" class="btn btn-secondary btn-block"
                    onclick="this.disabled=true;this.innerHTML=`Logging <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();"><?php echo e(lang('Login')); ?></button>
            </div>
            <div class="text-center mt-3">
                <a href="<?php echo e(url('customer/forgotpassword')); ?>"
                    class="text-primary pb-2"><?php echo e(lang('Forgot Password?')); ?></a>
                <?php if(setting('REGISTER_DISABLE') == 'on'): ?>
                    <p class="text-dark mb-0"><?php echo e(lang('Don’t have account?')); ?><a class="text-primary ms-1"
                            href="<?php echo e(route('auth.register')); ?>"><?php echo e(lang('Register', 'menu')); ?></a></p>
                <?php endif; ?>

                <p class="text-dark mb-0"><a class="text-primary ms-1"
                        href="<?php echo e(url('/')); ?>"><?php echo e(lang('Back to home')); ?></a></p>
            </div>
        </form>

    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <!-- Captcha js-->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script type="text/javascript">
        "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        <?php if(setting('login_disable') == 'on'): ?>
            document.querySelectorAll('.social-icon-2')?.forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    toastr.warning('<?php echo e('Login is temporarily disabled'); ?>')
                    return;
                });
            });
        <?php endif; ?>

        // Variables
        var microsoft = "<?php echo e(route('social.login', 'microsoft')); ?>";
        var google = "<?php echo e(route('social.login', 'google')); ?>";
        var envato = "<?php echo e(route('social.login', 'envato')); ?>";

        $(function() {
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

            })(jQuery);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.custommaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/user/auth/login.blade.php ENDPATH**/ ?>