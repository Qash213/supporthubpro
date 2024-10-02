                            
                            <?php $__env->startSection('content'); ?>

                                <div class="px-4 pb-4 pt-0 text-center">
									<h3 class="mb-2"><?php echo e(lang('Login', 'menu')); ?></h3>
									<p class="text-muted fs-13 mb-1"><?php echo e(lang('Sign In to your account')); ?></p>
								</div>
								<form class="card-body pt-3 pb-0" id="login" action="<?php echo e(route('login')); ?>" method="post">

								<?php echo csrf_field(); ?>

								<?php echo view('honeypot::honeypotFormFields'); ?>

									<div class="form-group">
										<label class="form-label"><?php echo e(lang('Email')); ?> <span class="text-red">*</span></label>
										<input class="form-control  <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('Email')); ?>" type="email" id="email" value="<?php echo e(old('email')); ?>" name="email">
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
									<div class="form-group">
										<label class="form-label"><?php echo e(lang('Password')); ?> <span class="text-red">*</span></label>
										<input class="form-control  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('password')); ?>" type="password" id="password" name="password">
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
									<div class="form-group">
										<label class="custom-control form-checkbox">
											<input type="checkbox" class="custom-control-input" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
											<span class="custom-control-label"><?php echo e(lang('Remember Me')); ?></span>
										</label>
									</div>

									<?php if(setting('CAPTCHATYPE')=='manual'): ?>
										<?php if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes'): ?>

										<div class="form-group row">
											<div class="col-md-12 mb-3">
												<input type="text" id="captcha" class="form-control <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('Enter Captcha')); ?>" name="captcha">
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

											</div>
											<div class="col-md-12">
												<div class="captcha d-flex border">
													<span class="mx-auto mt-2"><?php echo captcha_img(''); ?></span>
													<button type="button" class="btn btn-secondary btn-icon captchabtn"><i class="fe fe-refresh-cw"></i></button>
												</div>
											</div>
										</div>
										<?php endif; ?>
									<?php endif; ?>

                                    <!--- if Enable the Google ReCaptcha --->
                                    <div class="form-group">
                                        <?php if(setting('CAPTCHATYPE')=='google'): ?>
                                            <?php if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes'): ?>

                                            <div class="g-recaptcha <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-sitekey="<?php echo e(setting('GOOGLE_RECAPTCHA_KEY')); ?>"></div>
                                            <?php if($errors->has('g-recaptcha-response')): ?>

                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong><?php echo e(lang($errors->first('g-recaptcha-response'))); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                    <!--- End Google ReCaptcha --->

									<div class="submit">
										<button class="btn btn-secondary btn-block"  type="submit" onclick="this.disabled=true;this.innerHTML=`<i class='fa fa-spinner fa-spin'></i>`;this.form.submit();"><?php echo e(lang('Login', 'menu')); ?></button>
									</div>
									<div class="text-center mt-3">
										<p class="mb-0"><a href="<?php echo e(route('password.request')); ?>"><?php echo e(lang('Forgot Password?')); ?></a></p>
									</div>
								</form>
                            <?php $__env->stopSection(); ?>
		<?php $__env->startSection('scripts'); ?>

		<!-- Captcha js-->
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<!-- copy username and password -->
		<script type="text/javascript">
            $(function() {
                "use strict";

                (function($){

                    $(".sprukoclick").on("click",function (e) {
                        e.preventDefault();

                        $("#email").val($(this).data("email"));

                        $("#password").val($(this).data("pswd"));

                    });

                    localStorage.removeItem('adminPanelSessionTimeout');
                    localStorage.removeItem('showAlertInactive');

                    // Variables
                    var facebook = "<?php echo e(route('social.login', 'facebook')); ?>";
                    var google = "<?php echo e(route('social.login', 'google')); ?>";
                    var twitter = "<?php echo e(route('social.login', 'twitter')); ?>";
                    var envato = "<?php echo e(route('social.login', 'envato')); ?>";



                    $(".captchabtn").on('click', function(e){
                        e.preventDefault();
                        $.ajax({
                            type:'GET',
                            url:'<?php echo e(route('captcha.reload')); ?>',
                            success: function(res){
                                $(".captcha span").html(res.captcha);
                            }
                        });
                    });

                    $(document).ready(function() {
                        $.ajax({
                            type:'GET',
                            url:'<?php echo e(route('captcha.reload')); ?>',
                            success: function(res){
                                $(".captcha span").html(res.captcha);
                            }
                        });
                    });
                })(jQuery);
            })
		</script>

		<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.custommaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>