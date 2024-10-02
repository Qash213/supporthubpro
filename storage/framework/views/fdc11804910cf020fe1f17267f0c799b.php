                            	<?php $__env->startSection('content'); ?>

								<!--Forgot Password -->
                            	<div class="pb-0 px-5 pt-0 text-center">
									<h1 class="mb-2"><?php echo e(lang('Forgot Password?')); ?></h1>
									<p class="text-muted fs-13 mb-1"><?php echo e(lang('Enter the email address that is linked to your account.')); ?></p>
								</div>
								<form class="card-body pt-3 pb-0" id="forgot" action="<?php echo e(route('password.email')); ?>" method="post">
                                	<?php echo csrf_field(); ?>

									<?php echo view('honeypot::honeypotFormFields'); ?>

									<div class="form-group">
										<label class="form-label"><?php echo e(lang('Email')); ?></label>
										<input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(lang('Email')); ?>" type="email">
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
									<div class="submit">
                                        <input class="btn btn-secondary btn-block" type="submit" value="<?php echo e(lang('Submit')); ?>" onclick="this.disabled=true;this.form.submit();">
									</div>
									<div class="text-center mt-4">
										<p class="text-dark mb-0"><a class="text-primary ms-1" href="<?php echo e(url()->previous()); ?>"><?php echo e(lang('Send me Back')); ?></a></p>
									</div>
								</form>
								<!--End Forgot Password -->

                            	<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.custommaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/auth/passwords/forgotpassword.blade.php ENDPATH**/ ?>