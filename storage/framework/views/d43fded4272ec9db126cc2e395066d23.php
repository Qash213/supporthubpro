<?php if($paginator->hasPages()): ?>
    <nav aria-label="navigation" class="mt-3" style="position: absolute; right: 23px; bottom: 9px;">
        <ul class="pagination custom-ul">
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled"><span class="page-link"><?php echo e(lang('Previous')); ?>

                    <span class="sr-only"><?php echo e(lang('Previous')); ?></span></span></li>
            <?php else: ?>
                <li class="page-item" ><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="<?php echo e($paginator->currentPage() - 1); ?>" rel="prev"><?php echo e(lang('Previous')); ?>

                    <span class="sr-only"><?php echo e(lang('Previous')); ?></span></a></li>
            <?php endif; ?>



            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if(is_string($element)): ?>
                    <li class=" page-item disabled"><span><?php echo e($element); ?></span></li>
                <?php endif; ?>



                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class=" page-item active "><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="<?php echo e($page); ?>"><?php echo e($page); ?></a></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="<?php echo e($page); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item" ><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="<?php echo e($paginator->currentPage() + 1); ?>" rel="next"><?php echo e(lang('Next')); ?>

                    <span class="sr-only"><?php echo e(lang('Next')); ?></span></a></li>
            <?php else: ?>
                <li class=" page-item disabled"><span class="page-link"><?php echo e(lang('Next')); ?>

                    <span class="sr-only"><?php echo e(lang('Next')); ?></span></span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/admin/viewticket/pagination.blade.php ENDPATH**/ ?>