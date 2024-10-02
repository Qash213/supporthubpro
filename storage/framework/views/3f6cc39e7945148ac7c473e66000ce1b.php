<?php $__env->startSection('styles'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

							<!-- Section -->
							<section>
								<div class="bannerimg cover-image" data-bs-image-src="<?php echo e(asset('build/assets/images/photos/banner1.jpg')); ?>">
									<div class="header-text mb-0">
										<div class="container">
											<div class="row text-white">
												<div class="col">
													<h1 class="mb-0"><?php echo e(lang('FAQ’s', 'menu')); ?></h1>
												</div>
												<div class="col col-auto">
													<ol class="breadcrumb text-center d-flex align-items-center justify-content-center">
														<li class="breadcrumb-item">
															<a href="#" class="text-white-50"><?php echo e(lang('Home', 'menu')); ?></a>
														</li>
														<li class="breadcrumb-item active">
															<a href="#" class="text-white"><?php echo e(lang('FAQ’s')); ?></a>
														</li>
													</ol>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
							<!-- Section -->

							<!--Section-->
							<section>
								<div class="cover-image mt-0 sptb">
									<div class="container">

										<?php $__currentLoopData = $faqcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faqcat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

										<?php
										$faq = $faqcat->faqdetails()->where('status', '1')->paginate('5');

										?>
										<?php if($faq->isNotEmpty()): ?>
                                            <div class="accordion-group d-flex justify-content-between">
                                                <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#dee5f7"/><path fill="#565b95" d="M15 10A3.0001 3.0001 0 0 0 9.40234 8.499a.99981.99981 0 0 0 1.73047 1.002A1.00022 1.00022 0 1 1 12 11a.99943.99943 0 0 0-1 1v1a1 1 0 0 0 2 0v-.18433A2.99487 2.99487 0 0 0 15 10zM12 17a.9994.9994 0 0 1-.37988-.08008 1.14718 1.14718 0 0 1-.33008-.21 1.16044 1.16044 0 0 1-.21-.33008A.83154.83154 0 0 1 11 16a1.39038 1.39038 0 0 1 .01953-.2002.65026.65026 0 0 1 .06055-.17968.74157.74157 0 0 1 .08984-.18067A1.61105 1.61105 0 0 1 11.29 15.29a1.04667 1.04667 0 0 1 1.41992 0A1.0321 1.0321 0 0 1 13 16a.9994.9994 0 0 1-.08008.37988.90087.90087 0 0 1-.54.54A.9994.9994 0 0 1 12 17z"/></svg><?php echo e($faqcat->faqcategoryname); ?></p>
                                                <?php if($faq > '5'): ?>

                                                <div>
                                                    <a href="<?php echo e(url('faq/'.$faqcat->id)); ?>" class="btn btn-sm btn-light ms-auto"><?php echo e(lang('View All')); ?></a>
                                                </div>

                                                <?php endif; ?>

                                            </div>
                                            <div class="accordion accordionExample<?php echo e($faqcat->id); ?>" >
                                                <div class="row mb-5">
                                                    <?php $__currentLoopData = $faq; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faqs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <div class="col-xl-12">
                                                        <div class="accordion-item">
                                                                <h2 class="accordion-header" id="heading<?php echo e($faqs->id); ?>">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($faqs->id); ?>" aria-expanded="true" aria-controls="collapse<?php echo e($faqs->id); ?>">
                                                                    <?php echo e($faqs->question); ?>

                                                                </button>
                                                                </h2>
                                                                <div id="collapse<?php echo e($faqs->id); ?>" class="accordion-collapse collapse " aria-labelledby="heading<?php echo e($faqs->id); ?>" data-bs-parent=".accordionExample<?php echo e($faqcat->id); ?>">
                                                                    <div class="accordion-body">
                                                                    <?php if($faqs->privatemode == 1): ?>
                                                                        <?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()): ?>

                                                                        <?php echo $faqs->answer; ?>

                                                                        <?php else: ?>

                                                                        <div class="alert alert-light-warning ">
                                                                            <p class="privatearticle">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                                            <?php echo e(lang('You must be logged in and have valid account to access this content.')); ?>

                                                                            </p>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>

                                                                    <?php echo $faqs->answer; ?>

                                                                    <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                                    </div>
                                                </div>
                                            <?php endif; ?>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if(App\Models\FAQ::get()->all() == null): ?>
                                            <div class="card no-articles">
                                                <div class="card-body p-8">
                                                    <div class="main-content text-center">
                                                        <div class="notification-icon-container p-4">
                                                            <img src="<?php echo e(asset('build/assets/images/noarticle.png')); ?>" alt="">
                                                        </div>
                                                        <h4 class="mb-1"><?php echo e(lang('There are no new FAQ’s')); ?></h4>
                                                        <p class="text-muted"><?php echo e(lang('This faq section will be updated shortly.')); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>


                                        <?php if($faqcats->all() == null): ?>
                                            <div class="card no-articles">
                                                <div class="card-body p-8">
                                                    <div class="main-content text-center">
                                                        <div class="notification-icon-container p-4">
                                                            <img src="<?php echo e(asset('build/assets/images/noarticle.png')); ?>" alt="">
                                                        </div>
                                                        <h4 class="mb-1"><?php echo e(lang('There are no new FAQ’s')); ?></h4>
                                                        <p class="text-muted"><?php echo e(lang('This faq section will be updated shortly.')); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
									</div>

								</div>
							</section>
							<!--Section-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

		<!-- INTERNAL Vertical-scroll js-->
		<script src="<?php echo e(asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')); ?>"></script>







<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.usermaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/faqpage.blade.php ENDPATH**/ ?>