<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
	<head>

		<?php echo $__env->make('includes.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<?php if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes'): ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(setting('GOOGLE_ANALYTICS')); ?>"></script>
		<script>
            $(function() {
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo e(setting('GOOGLE_ANALYTICS')); ?>');
            })
		</script>
		<?php endif; ?>

	</head>

	<body class="<?php echo e(getIsRtl()); ?>

	<?php if(setting('SPRUKOADMIN_C') == 'off'): ?>
		<?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?>
	<?php else: ?>
		<?php if(Auth::guard('customer')->check()): ?>
			<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null): ?>
			<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1): ?> dark-mode <?php endif; ?>
			<?php endif; ?>
		<?php else: ?>
			<?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
		">

				<?php echo $__env->make('includes.user.mobileheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<?php echo $__env->make('includes.user.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<div class="page">
					<div class="page-main">

						<div class="modal effect-scale fade"  id="customerautologout" data-bs-backdrop="static">
							<div class="modal-dialog modal-dialog-centered text-center" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-body px-6 py-5 text-center">
										<div class="mb-5 text-center">
											<div class="mb-5">
												<span class="avatar avatar-xl brround bg-warning-transparent"><i class="ri-alert-line fw-normal"></i></span>
											</div>
											<div>
                                                <h4 class="mb-1"><?php echo e(lang('Session Timeout')); ?></h4>
												<p class="fw-semibold mb-1"><?php echo e(lang('You have been inactive since last')); ?> <?php echo e(setting('customer_inactive_auto_logout_time')); ?><?php echo e(lang(' minutes. Do you wish to stay?')); ?> </p>


												<span class="d-block text-muted fw-normal"><?php echo e(lang('Your session will be timed out in')); ?> <h3 class="countdown mb-0"></h3><span><?php echo e(lang('seconds')); ?></span>


											</div>
										</div>
										<button class="btn btn-primary w-lg clientstayin"><?php echo e(lang('Stay Signed In')); ?></button>
									</div>
								</div>
							</div>
						</div>

							<?php echo $__env->yieldContent('content'); ?>


					</div>
				</div>

				<?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php echo $__env->make('includes.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<?php if(auth()->guard()->guest()): ?>
		<?php if(customcssjs('CUSTOMCHATENABLE') == 'enable'): ?>
		<?php if(customcssjs('CUSTOMCHATUSER') == 'public'): ?>
		<?php echo customcssjs('CUSTOMCHAT') ?>
		<?php endif; ?>
		<?php endif; ?>
		<?php else: ?>
		<?php if(customcssjs('CUSTOMCHATENABLE') == 'enable'): ?>
		<?php if(customcssjs('CUSTOMCHATUSER') == 'user'): ?>
		<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()): ?>
		<?php echo customcssjs('CUSTOMCHAT') ?>
		<?php endif; ?>
		<?php endif; ?>
		<?php if(customcssjs('CUSTOMCHATUSER') == 'public'): ?>
		<?php echo customcssjs('CUSTOMCHAT') ?>
		<?php endif; ?>
		<?php endif; ?>
		<?php endif; ?>



	<?php if(Session::has('error')): ?>
	<script>
		toastr.error("<?php echo Session::get('error'); ?>");
	</script>
	<?php elseif(Session::has('success')): ?>
	<script>
		toastr.success("<?php echo Session::get('success'); ?>");
	</script>
	<?php elseif(Session::has('info')): ?>
	<script>
		toastr.info("<?php echo Session::get('info'); ?>");
	</script>
	<?php elseif(Session::has('warning')): ?>
	<script>
		toastr.warning("<?php echo Session::get('warning'); ?>");
	</script>
	<?php endif; ?>

	<?php if(setting('REGISTER_POPUP') == 'yes'): ?>
	<?php if(!Auth::guard('customer')->check()): ?>

	<?php echo $__env->make('user.auth.modalspopup.register', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<?php echo $__env->make('user.auth.modalspopup.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<?php echo $__env->make('user.auth.modalspopup.forgotpassword', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php endif; ?>
	<?php endif; ?>

	<?php if(setting('GUEST_TICKET') == 'yes'): ?>

		<?php echo $__env->make('guestticket.guestmodal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<?php endif; ?>

	<?php echo $__env->yieldContent('modal'); ?>

</body>

</html>
<?php /**PATH /var/www/html/resources/views/layouts/usermaster.blade.php ENDPATH**/ ?>