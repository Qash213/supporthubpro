<?php $__env->startSection('content'); ?>

		<!-- Section -->
		<section>
			<div class="bannerimg cover-image" data-bs-image-src="<?php echo e(asset('build/assets/images/photos/banner1.jpg')); ?>">
				<div class="header-text mb-0">
					<div class="container">
						<div class="row text-white">
							<div class="col">
								<h1><?php echo e($category->name); ?></h1>
							</div>
							<div class="col col-auto">
								<ol class="breadcrumb text-center">
									<li class="breadcrumb-item">
										<a href="#" class="text-white-50"><?php echo e(lang('Home', 'menu')); ?></a>
									</li>
									<li class="breadcrumb-item active">
										<a href="#" class="text-white"><?php echo e(lang('Category')); ?></a>
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Section -->

		<!--Category Page-->
		<section>
			<div class="cover-image sptb">
				<div class="container">
					<div class="row">
						<div class="col-xl-8">
							<div class="card p-0">
								<div class="search-background category-search p-0">
									<input type="text" class="form-control input-lg" name="search_name" id="search_name"  placeholder="<?php echo e(lang('Ask your Questions...')); ?>">
									<button class="btn"><i class="fe fe-search"></i></button>

									<div id="searchList">

									</div>
								</div>
								<?php echo csrf_field(); ?>
							</div>
							<div class="card">
								<div class="card-header border-0">
									<h4 class="card-title mb-2"><?php echo e($category->name); ?></h4>
								</div>
								<div class="card-body pt-1">
										<ul class="list-unstyled list-article mb-0">
											<?php $__currentLoopData = $categorys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $category->articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php if($article->status === 'Published'): ?>

											<?php if($article->articleslug != null): ?>

											<li>
												<a class="" href="<?php echo e(url('/article/' . $article->articleslug)); ?>"><i class="typcn typcn-document-text"></i><span class="categ-text"><?php echo e($article->title); ?></span></a>
											</li>

											<?php else: ?>

											<li>
												<a class="" href="<?php echo e(url('/article/' . $article->id)); ?>"><i class="typcn typcn-document-text"></i><span class="categ-text"><?php echo e($article->title); ?></span></a>
											</li>
											<?php endif; ?>
											<?php endif; ?>

											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</ul>
								</div>
							</div>
						</div>
						<div class="col-xl-4">
							<div class="card ">
								<div class="card-header  border-0">
									<h4 class="card-title"><?php echo e(lang('Recent Articles')); ?></h4>
								</div>
								<div class="card-body">
									<div class="list-catergory">
										<ul class="item-list item-list-scroll mb-0 custom-ul">
											<?php $__currentLoopData = $recentarticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentarticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

											<li class="item mb-4  position-relative">
												<?php if($recentarticle->articleslug != null): ?>

												<a href="<?php echo e(url('/article/' . $recentarticle->articleslug)); ?> " class=" admintickets"></a>

												<?php else: ?>

												<a href="<?php echo e(url('/article/' . $recentarticle->id)); ?> " class=" admintickets"></a>
												<?php endif; ?>
												<div class="d-flex">
													<div class="me-7">
														<i class="typcn typcn-document-text item-list-icon"></i>
													</div>
													<div class="">
														<span
															class=""><?php echo e(Str::limit($recentarticle->title, '70')); ?>

														</span>
													</div>
													<div class="ms-auto">
															<span class="badge badge-light badge-md fs-10"><i
																class="fa fa-eye me-1"></i><?php echo e($recentarticle->views); ?></span>

													</div>
												</div>
											</li>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

										</ul>
									</div>
								</div>
							</div>

							<div class="card mb-0">
								<div class="card-header  border-0">
									<h4 class="card-title"><?php echo e(lang('Popular Articles')); ?></h4>
								</div>
								<div class="card-body">
									<div class="list-catergory">
										<ul class="item-list item-list-scroll mb-0 custom-ul">
											<?php $__currentLoopData = $populararticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $populararticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li class="item mb-4 position-relative">
												<?php if($populararticle->articleslug != null): ?>

												<a href="<?php echo e(url('/article/' . $populararticle->articleslug)); ?> " class=" admintickets"></a>

												<?php else: ?>

												<a href="<?php echo e(url('/article/' . $populararticle->id)); ?> " class=" admintickets"></a>
												<?php endif; ?>
												<div class="d-flex">
													<div class="me-7">
														<i class="typcn typcn-document-text item-list-icon"></i>
													</div>
													<div class="">
														<span
															class=""><?php echo e(Str::limit($populararticle->title, '70')); ?>

														</span>
													</div>
													<div class="ms-auto">
														<span class="badge badge-light badge-md fs-10">
															<i class="fa fa-eye me-1"></i>
															<?php echo e($populararticle->views); ?>

														</span>

													</div>
												</div>
											</li>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

										</ul>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Category Page-->



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

	<script type="text/javascript">

        $(function() {
            "use strict";

            (function($){

                // close the data search
                document.querySelector('.page-main').addEventListener('click', ()=>{
                    $('#searchList').fadeOut();
                    $('#searchList').html('');
                });

                // search the data
                $('#search_name').keyup(function () {

                    var data = $(this).val();
                    if (data != '') {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "<?php echo e(url('/search')); ?>",
                            method: "POST",
                            data: {data: data, _token: _token},

                            dataType:"json",

                            success: function (data) {

                                $('#searchList').fadeIn();
                                $('#searchList').html(data);
                                const ps3 = new PerfectScrollbar('.sprukohomesearch', {
                                    useBothWheelAxes:true,
                                    suppressScrollX:true,
                                });
                            },
                            error: function (data) {

                            }
                        });
                    }
                });

            })(jQuery);
        })
	</script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.usermaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/categorypage.blade.php ENDPATH**/ ?>