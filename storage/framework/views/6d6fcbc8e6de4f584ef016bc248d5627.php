<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <?php echo $__env->make('includes.admin.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(setting('GOOGLE_ANALYTICS_ENABLE') == 'yes'): ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(setting('GOOGLE_ANALYTICS')); ?>"></script>
        <script>
            $(function() {
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', '<?php echo e(setting('GOOGLE_ANALYTICS')); ?>');
            })
        </script>
    <?php endif; ?>

</head>

<body
    class="app sidebar-mini
	<?php echo e(getIsRtl()); ?>

	<?php if(setting('SPRUKOADMIN_P') == 'off'): ?> <?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?>
<?php else: ?>
<?php if(Auth::check() && Auth::user()->darkmode == 1): ?> dark-mode <?php endif; ?>

	<?php endif; ?>
	<?php if(setting('sidemenu_icon_style') == 'on'): ?> icon-overlay sidenav-toggled <?php endif; ?>
	">



    <div class="page">
        <div class="page-main">
            <?php echo $__env->make('includes.admin.verticalmenu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="app-content main-content">
                <div class="side-app">
                    <?php echo $__env->make('includes.admin.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <?php if(setting('MAINTENANCE_MODE') == 'on'): ?>

                        <div class="alert alert-danger sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-hourglass-half fa-spin me-2 fs-15" aria-hidden="true"></i>
                            <?php echo e(lang('This application is in maintenance mode. We are performing scheduled maintenance.')); ?>

                        </div>

                    <?php endif; ?>

                    <?php if(setting('mail_host') == 'smtp.mailtrap.io' && Auth::user()->getRoleNames()[0] == 'superadmin'): ?>
                        <div class="alert alert-warning sprukoclosebtn mt-5 fs-15">
                            <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                            <?php echo e(lang('It is necessary to set up your email settings first in order to send and receive emails.')); ?>

                            <div class="">
                                <a href="<?php echo e(route('email.setting.alert')); ?>" class="btn btn-dark btn-sm mt-2"
                                    target="_blank"> <i class="fa fa-cogs me-2 fs-15"
                                        aria-hidden="true"></i><?php echo e(lang('Email Setup')); ?> </a>
                                <a href="https://youtu.be/2jwH9P9-R4E" class="btn btn-dark btn-sm mt-2" target="_blank">
                                    <i class="fa fa-link me-2 fs-15"
                                        aria-hidden="true"></i><?php echo e(lang('Setup Reference ')); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                        $cronset = \App\Models\Setting::where('key', 'cronjob_set')->first();
                        $cronworking = $cronset->updated_at->addDay(1) >= \Carbon\Carbon::now();
                    ?>

                    <?php if($cronworking != true || $cronset->value == 'installed'): ?>
                        <?php if(Auth::user()->getRoleNames()[0] == 'superadmin'): ?>
                            <div class="alert alert-info sprukoclosebtn mt-5 fs-15">
                                <i class="fa fa-exclamation-triangle me-2 fs-18" aria-hidden="true"></i>
                                <?php echo e(lang('It is necessary to set up your cron job first in order for the auto functions to work.')); ?>

                                <div class="">
                                    <a href="https://youtu.be/uqkZsQdU_TE" class="btn btn-dark btn-sm mt-2"
                                        target="_blank"> <i class="fa fa-link me-2 fs-15"
                                            aria-hidden="true"></i><?php echo e(lang('Setup Reference ')); ?></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="modal effect-scale fade" id="adminautologout" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-body px-6 py-5 text-center">
                                    <div class="mb-5 text-center">
                                        <div class="mb-5">
                                            <span class="avatar avatar-xl brround bg-warning-transparent"><i
                                                    class="ri-alert-line fw-normal"></i></span>
                                        </div>
                                        <div>
                                            <h4 class="mb-1"><?php echo e(lang('Session Timeout')); ?></h4>
                                            <p class="fw-semibold mb-1">
                                                <?php echo e(lang('You have been inactive since last')); ?>

                                                <?php echo e(setting('admin_users_inactive_auto_logout_time')); ?><?php echo e(lang(' minutes. Do you wish to stay?')); ?>

                                            </p>


                                            <span
                                                class="d-block text-muted fw-normal"><?php echo e(lang('Your session will be timed out in')); ?>

                                                <h3 class="countdown mb-0"></h3><span><?php echo e(lang('seconds')); ?></span>


                                        </div>
                                    </div>
                                    <button
                                        class="btn btn-primary w-lg adminstayin"><?php echo e(lang('Stay Signed In')); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $__env->yieldContent('content'); ?>

                </div>
            </div><!-- end app-content-->
        </div>
        <?php echo $__env->make('includes.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>

    <?php echo $__env->make('includes.admin.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
    <?php elseif(Session::has('adminreplied')): ?>
        <script>
            toastr.success("<?php echo Session::get('adminreplied'); ?>");
        </script>
        <?php
            Session::pull('adminreplied', 'The response to the ticket was successful.');
        ?>
    <?php endif; ?>

    <?php echo $__env->yieldContent('modal'); ?>

</body>

</html>
<?php /**PATH /var/www/html/resources/views/layouts/adminmaster.blade.php ENDPATH**/ ?>