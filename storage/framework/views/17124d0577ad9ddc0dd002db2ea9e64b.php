<?php echo e(\Carbon\Carbon::now(Auth::user()->timezone)->format(setting('time_format')) ?? 'logout'); ?>

<?php /**PATH /var/www/html/resources/views/admin/superadmindashboard/timeupdate.blade.php ENDPATH**/ ?>