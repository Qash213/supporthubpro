							<?php $__env->startSection('content'); ?>

							<!-- Section -->
							<section>
								<div class="bannerimg cover-image" data-bs-image-src="<?php echo e(asset('build/assets/images/photos/banner1.jpg')); ?>">
									<div class="header-text mb-0">
										<div class="container">
											<div class="row text-white">
												<div class="col">
													<h1><?php echo e(lang('Contact Us', 'menu')); ?></h1>
												</div>
												<div class="col col-auto">
													<ol class="breadcrumb text-center">
														<li class="breadcrumb-item">
															<a href="<?php echo e(url('/')); ?>" class="text-white-50"><?php echo e(lang('Home', 'menu')); ?></a>
														</li>
														<li class="breadcrumb-item active">
															<a href="#" class="text-white"><?php echo e(lang('Contact Us', 'menu')); ?></a>
														</li>
													</ol>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
							<!-- Section -->

							<!--Contact Us Page-->
							<section>
								<div class="cover-image sptb">
									<div class="container">
										<div class="row">
											<div class="col-lg-6 col-xl-6 col-md-12 mx-auto d-block">
												<div class="card mb-0">
													<div class="card-body">
														<h3 class="font-weight-semibold2 mb-5"><?php echo e(lang('Let’s get in Touch')); ?></h3>
														<form method="POST" action="<?php echo e(url('/contact-us')); ?>">
															<?php echo csrf_field(); ?>

															<?php echo view('honeypot::honeypotFormFields'); ?>

															<div class="form-group">
																<input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('Name')); ?>" type="text" name="name" value="<?php echo e(old('name')); ?>">
																<?php $__errorArgs = ['name'];
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
																<input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(lang('Email')); ?>" type="email" name="email" value="<?php echo e(old('email')); ?>">
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
																<input class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(lang('Mobile Number')); ?>" type="text" name="phone_number" value="<?php echo e(old('phone_number')); ?>">
																<?php $__errorArgs = ['phone_number'];
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
																<input class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(lang('Subject')); ?>" type="text" name="subject" value="<?php echo e(old('subject')); ?>">
																<?php $__errorArgs = ['subject'];
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
																<textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(lang('Message')); ?>" rows="6" name="message" ><?php echo e(old('message')); ?></textarea>
																<?php $__errorArgs = ['message'];
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
															<?php if(setting('CAPTCHATYPE')=='manual'): ?>
																<?php if(setting('RECAPTCH_ENABLE_CONTACT')=='yes'): ?>

																<div class="form-group row">
																	<div class="col-md-6">
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
																	<div class="col-md-6">
																		<div class="captcha">
																			<span><?php echo captcha_img(''); ?></span>
																			<button type="button" class="btn btn-outline-info btn-sm captchabtn"><i class="fe fe-refresh-cw"></i></button>
																		</div>
																	</div>
																</div>
																<?php endif; ?>
															<?php endif; ?>

															<!--- if Enable the Google ReCaptcha --->
															<div class="form-group">
																<?php if(setting('CAPTCHATYPE')=='google'): ?>
																	<?php if(setting('RECAPTCH_ENABLE_CONTACT')=='yes'): ?>

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
																			<strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
																		</span>
																	<?php endif; ?>
																	<?php endif; ?>
																<?php endif; ?>

															</div>
															<!--- End Google ReCaptcha --->

															<div class="text-start">
                                                                <button type="submit" class="btn btn-secondary waves-effect waves-light" onclick="this.disabled=true;this.innerHTML=`Sending <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();"><?php echo e(lang('Send Message')); ?></button>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
							<!--Contact Us Page-->
							<?php $__env->stopSection(); ?>

		<?php $__env->startSection('scripts'); ?>

		<!-- INTERNAL Vertical-scroll js-->
		<script src="<?php echo e(asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')); ?>?v=<?php echo time(); ?>"></script>

		<!-- Captcha js-->
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<!-- Captcha js-->
		<script type="text/javascript">
            $(function() {
                "use strict";

                (function($)  {

                    $(".captchabtn").on('click', function(e){
                        e.preventDefault();
                        $.ajax({
                            type:'GET',
                            url:'captchareload',
                            success: function(res){
                                $(".captcha span").html(res.captcha);
                            }
                        });
                    });

                    $(document).ready(function() {
                        $.ajax({
                            type:'GET',
                            url:'captchareload',
                            success: function(res){
                                $(".captcha span").html(res.captcha);
                            }
                        });
                    });

                })(jQuery);
            });
		</script>

		<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.usermaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/contactform/contactus.blade.php ENDPATH**/ ?>