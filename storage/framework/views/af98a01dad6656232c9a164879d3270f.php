<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="ltr">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="<?php echo e($seopage->description ? $seopage->description :''); ?>" name="description">
		<meta content="<?php echo e($seopage->author ? $seopage->author :''); ?>" name="author">
		<meta name="keywords" content="<?php echo e($seopage->keywords ? $seopage->keywords :''); ?>"/>
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

		<!-- Title -->
		<title><?php echo e($title->title); ?></title>

		<?php if($title->image4 == null): ?>

		<!--Favicon -->
		<link rel="icon" href="<?php echo e(asset('uploads/logo/favicons/favicon.ico')); ?>" type="image/x-icon"/>
		<?php else: ?>

		<!--Favicon -->
		<link rel="icon" href="<?php echo e(asset('uploads/logo/favicons/'.$title->image4)); ?>" type="image/x-icon"/>
		<?php endif; ?>

		<?php if(getIsRtl() == 'rtl'): ?>

		<!-- Bootstrap css -->
		<link href="<?php echo e(asset('build/assets/plugins/bootstrap/css/bootstrap.rtl.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />
		<?php else: ?>

		<!-- Bootstrap css -->
		<link href="<?php echo e(asset('build/assets/plugins/bootstrap/css/bootstrap.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />
		<?php endif; ?>

		<!-- Style css -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/updatestyle/updatestyles.scss']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/custom-theme/dark.scss']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/custom-theme/skin-modes.scss']); ?>

		<!-- Animate css -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/custom-theme/custom/animated.css']); ?>

		<!---Icons css-->
		<link href="<?php echo e(asset('build/assets/plugins/icons/icons.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="<?php echo e(asset('build/assets/plugins/toastr/toastr.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Jquery js-->
		<script src="<?php echo e(asset('build/assets/plugins/jquery/jquery.min.js')); ?>?v=<?php echo time(); ?>"></script>

		<?php echo $__env->yieldContent('styles'); ?>

		<style>
			:root {
		--primary:<?php echo setting('theme_color') ?>;
		--secondary:<?php echo setting('theme_color_dark') ?>;
			}

		</style>

		<style>

			<?php echo customcssjs('CUSTOMCSS'); ?>

		</style>

		<?php if(setting('GOOGLEFONT_DISABLE') == 'off'): ?>

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

		</style>

		<?php endif; ?>

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

	<body class="<?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?> <?php echo e(getIsRtl()); ?>">
        <div class="uhelp-announcement-alertgroup">
            <?php if($holidays->isNotEmpty() && setting('24hoursbusinessswitch') != 'on'): ?>
                <?php $__currentLoopData = $holidays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($anct->status == 1): ?>
                        <div class="alert alert-holiday" role="alert" style="background: <?php echo e($anct->primaray_color); ?>; border-color: <?php echo e($anct->primaray_color); ?>; color:<?php echo e($anct->secondary_color); ?>;">
                            <div class="container">
                                <button type="submit" class="btn-close ms-5 float-end notifyclose" style="color:<?php echo e($anct->secondary_color); ?>;" data-id="<?php echo e($anct->id); ?>">×</button>
                                <div class="d-flex align-items-top">
                                    <div class="uhelp-announcement me-2 svg-icon" style="background: <?php echo e(str_replace(', 1)', ', 0.1)', $anct->secondary_color)); ?>; color:<?php echo e($anct->secondary_color); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px"  fill="currentColor"><path d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 10H4V19H20V10ZM15.0355 11.136L16.4497 12.5503L11.5 17.5L7.96447 13.9645L9.37868 12.5503L11.5 14.6716L15.0355 11.136ZM7 5H4V8H20V5H17V6H15V5H9V6H7V5Z"></path></svg>
                                </div>
                                <div class="d-flex align-items-top">
                                    <div class="notice-heading d-flex align-items-top flex-fill">
                                        <div>
                                            <div class="fs-18 font-weight-bold holiday-title flex-fill" style="color:<?php echo e($anct->secondary_color); ?>;"><?php echo e($anct->occasion); ?><span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                            <div class="mb-0  uhelp-alert-content alert-notice"><?php echo $anct->holidaydescription; ?>

                                                <?php if($anct->buttonon == 1): ?>
                                                <a class="btn btn-sm ms-2  text-decoration-underline" href="<?php echo e($anct->buttonurl); ?>" target="_blank"><?php echo e($anct->buttonname); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if(setting('ANNOUNCEMENT_USER') == 'non_login_users' || setting('ANNOUNCEMENT_USER') == 'all_users'): ?>
                <?php $__currentLoopData = $announcement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($anct->status == 1): ?>
                        <div class="alert alert-announcement" role="alert" style="background: <?php echo e($anct->primary_color); ?>; color:<?php echo e($anct->secondary_color); ?>;">
                            <div class="container">
                                <button type="submit" class="btn-close ms-5 float-end  notifyclose" style="color:<?php echo e($anct->secondary_color); ?>;" data-id="<?php echo e($anct->id); ?>">×</button>
                                <div class="d-flex align-items-top">
                                    <div class="uhelp-announcement lh-1 svg-icon flex-shrink-0 me-2" style="background: <?php echo e(str_replace(', 1)', ', 0.1)', $anct->secondary_color)); ?>; color:<?php echo e($anct->secondary_color); ?>;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                    </div>
                                    <div class="d-flex align-items-top">
                                        <div class="notice-heading d-flex align-items-top flex-fill">
                                            <div>
                                                <div class="fs-18 font-weight-bold  flex-fill" style="color:<?php echo e($anct->secondary_color); ?>;"><?php echo e($anct->title); ?><span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                                <div class="mb-0  uhelp-alert-content alert-notice"><?php echo $anct->notice; ?>

                                                    <?php if($anct->buttonon == 1): ?>
                                                        <a class="btn btn-sm ms-2  text-decoration-underline" href="<?php echo e($anct->buttonurl); ?>" target="_blank"><?php echo e($anct->buttonname); ?></a>
                                                        <?php endif; ?>
                                                        <?php if($anct->buttonlable != null && $anct->buttonurl != null): ?>
                                                        <div class="ms-auto mt-auto">
                                                            <a href="<?php echo e($anct->buttonurl); ?>" target="_blank" class="btn btn-info mt-2 btn-sm"><?php echo e($anct->buttonlable); ?><i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ancts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $announceDay = explode(',', $ancts->announcementday);
                    $now = today()->format('D');

                    ?>
                    <?php $__currentLoopData = $announceDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announceDays): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($ancts->status == 1 && $announceDays == $now): ?>
                            <div class="alert alert-announcement" role="alert" style="background: <?php echo e($ancts->primary_color); ?>; color:<?php echo e($ancts->secondary_color); ?>;">
                                <div class="container">
                                    <button type="submit" class="btn-close ms-5 float-end  notifyclose" style="color:<?php echo e($ancts->secondary_color); ?>;" data-id="<?php echo e($ancts->id); ?>">×</button>
                                    <div class="d-flex align-items-top">
                                        <div class="uhelp-announcement lh-1 svg-icon flex-shrink-0 me-2" style="background: <?php echo e(str_replace(', 1)', ', 0.1)', $ancts->secondary_color)); ?>; color:<?php echo e($ancts->secondary_color); ?>;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        </div>
                                        <div class="d-flex align-items-top">
                                            <div class="notice-heading d-flex align-items-top flex-fill">
                                                <div>
                                                    <div class="fs-18 font-weight-bold  flex-fill" style="color:<?php echo e($ancts->secondary_color); ?>;"><?php echo e($ancts->title); ?><span class=" opacity-50 mx-2"><i class="ti ti-minus"></i></span></div>

                                                    <div class="mb-0  uhelp-alert-content alert-notice"><?php echo $ancts->notice; ?>

                                                        <?php if($ancts->buttonon == 1): ?>
                                                        <a class="btn btn-sm ms-2  text-decoration-underline" href="<?php echo e($ancts->buttonurl); ?>" target="_blank"><?php echo e($ancts->buttonname); ?></a>
                                                        <?php endif; ?>
                                                        <?php if($ancts->buttonlable != null && $ancts->buttonurl != null): ?>
                                                            <div class="ms-auto mt-auto">
                                                                <a href="<?php echo e($ancts->buttonurl); ?>" target="_blank" class="btn btn-info mt-2 btn-sm"><?php echo e($ancts->buttonlable); ?><i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

		<div class="page login-bg1">
            <div class="page-single">
                <div class="container">
                    <div class="row justify-content-center py-4">
                        <div class="col-sm-12">
                            <div class="card authentication-card py-5 mx-auto">
                                <div class="pt-0 pb-1 text-center">

                                    <a class="header-brand ms-0" href="<?php echo e(url('/')); ?>">
                                        <?php if($title->image !== null): ?>

                                        <img src="<?php echo e(asset('uploads/logo/logo/'.$title->image)); ?>" class="header-brand-img custom-logo-dark"
                                            alt="<?php echo e($title->image); ?>">
                                        <?php else: ?>
                                        <img src="<?php echo e(asset('uploads/logo/logo/logo-white.png')); ?>" class="header-brand-img custom-logo-dark"
                                            alt="logo">
                                        <?php endif; ?>
                                        <?php if($title->image1 !== null): ?>

                                            <img src="<?php echo e(asset('uploads/logo/darklogo/'.$title->image1)); ?>" class="header-brand-img custom-logo"
                                            alt="<?php echo e($title->image1); ?>">
                                        <?php else: ?>

                                        <img src="<?php echo e(asset('uploads/logo/darklogo/logo.png')); ?>" class="header-brand-img custom-logo"
                                            alt="logo">

                                        <?php endif; ?>

                                    </a>
                                </div>

                                <?php echo $__env->yieldContent('content'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


		<!-- Bootstrap4 js-->
		<script src="<?php echo e(asset('build/assets/plugins/bootstrap/popper.min.js')); ?>?v=<?php echo time(); ?>"></script>
		<script src="<?php echo e(asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')); ?>?v=<?php echo time(); ?>"></script>

		<script>

			<?php echo customcssjs('CUSTOMJS') ?>
		</script>



		<!--INTERNAL Toastr js -->
		<script src="<?php echo e(asset('build/assets/plugins/toastr/toastr.min.js')); ?>?v=<?php echo time(); ?>"></script>

        <script type="text/javascript">
            $(function() {
                "use strict";
                (function($){
                    let notifyClose = document.querySelectorAll('.notifyclose');
                    notifyClose.forEach(ele => {
                    if(ele){
                    let id = ele.getAttribute('data-id');
                    if(getCookie(id)){
                    ele.closest('.alert').classList.add('d-none');
                    }else{
                    ele.addEventListener('click', setCookie);
                    }
                    }
                    })


                    function setCookie($event) {
                    const d = new Date();
                    let id = $event.currentTarget.getAttribute('data-id');
                    d.setTime(d.getTime() + (30 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = id + "=" + 'announcement_close' + ";" + expires + ";path=/";
                    $event.currentTarget.closest('.alert').classList.add('d-none');
                    }

                    function getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                    }
                    return '';
                    }
                })(jQuery);
            })
        </script>

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
			<?php echo $__env->yieldContent('scripts'); ?>

			<?php echo $__env->yieldContent('modal'); ?>
	</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/custommaster.blade.php ENDPATH**/ ?>