<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="<?php echo e(substr(strip_tags($articles->message),0,150) ? substr(strip_tags($articles->message),0,150) :''); ?>"
			name="description">
		<meta content="<?php echo e(substr($articles->title,0,60) ? substr($articles->title,0,60) :''); ?>" name="title">
		<meta name="keywords" content="<?php echo e($articles->tags ? $articles->tags :''); ?>" />
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

		<!-- Title -->
		<title><?php echo e($articles->title); ?></title>

		<?php if($title->image4 == null): ?>

		<!--Favicon -->
		<link rel="icon" href="<?php echo e(asset('uploads/logo/favicons/favicon.ico')); ?>" type="image/x-icon"/>
		<?php else: ?>

		<!--Favicon -->
		<link rel="icon" href="<?php echo e(asset('uploads/logo/favicons/'.$title->image4)); ?>" type="image/x-icon"/>
		<?php endif; ?>

		<?php if(getIsRtl() == 'rtl'): ?>

		<!-- Bootstrap css -->
		<link href="<?php echo e(asset('build/assets/plugins/bootstrap/css/bootstrap.rtl.css')); ?>" rel="stylesheet" />
		<?php else: ?>

		<!-- Bootstrap css -->
		<link href="<?php echo e(asset('build/assets/plugins/bootstrap/css/bootstrap.css')); ?>" rel="stylesheet" />
		<?php endif; ?>

		<!-- Style css -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/updatestyle/updatestyles.scss']); ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/custom-theme/dark.scss']); ?>

		<!-- Animate css -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/custom-theme/custom/animated.css']); ?>

		<!-- P-scroll bar css-->
		<link href="<?php echo e(asset('build/assets/plugins/p-scrollbar/p-scrollbar.css')); ?>" rel="stylesheet" />

		<!---Icons css-->
		<link href="<?php echo e(asset('build/assets/plugins/icons/icons.css')); ?>?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- Select2 css -->
		<link href="<?php echo e(asset('build/assets/plugins/select2/select2.min.css')); ?>" rel="stylesheet" />

		<!--INTERNAL Toastr css -->
		<link href="<?php echo e(asset('build/assets/plugins/toastr/toastr.css')); ?>" rel="stylesheet" />

		<!-- INTERNAL owl-carousel css-->
		<link href="<?php echo e(asset('build/assets/plugins/owl-carousel/owl-carousel.css')); ?>" rel="stylesheet" />


		<!-- GALLERY CSS -->
		<link href="<?php echo e(asset('build/assets/plugins/simplelightbox/simplelightbox.css')); ?>" rel="stylesheet">

		<!-- Color Change -->
		<style>
			:root {
				--primary: <?php echo setting('theme_color') ?>;
				--secondary: <?php echo setting('theme_color_dark') ?>;
			}
		</style>

		<!-- Custom css-->
		<style>

			<?php echo customcssjs('CUSTOMCSS') ?>

		</style>

		<?php if(setting('GOOGLEFONT_DISABLE') == 'off'): ?>

		<!-- Google Fonts -->
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
		</style>

		<?php endif; ?>

		<!-- Jquery js-->
		<script src="<?php echo e(asset('build/assets/plugins/jquery/jquery.min.js')); ?>"></script>

	</head>
	<body class="<?php echo e(getIsRtl()); ?> <?php if(setting('SPRUKOADMIN_C') == 'off'): ?>
	<?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?>
<?php else: ?>
	<?php if(Auth::guard('customer')->check()): ?>
		<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting != null): ?>
		<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->custsetting->darkmode == 1): ?> dark-mode <?php endif; ?>
		<?php endif; ?>
	<?php else: ?>
		<?php if(setting('DARK_MODE') == 1): ?> dark-mode <?php endif; ?>
	<?php endif; ?>
<?php endif; ?>">

				<?php echo $__env->make('includes.user.mobileheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<?php echo $__env->make('includes.user.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<div class="page">
					<div class="page-main">
						<div class="containerheight">
							<!-- Section -->
							<section>
								<div class="bannerimg cover-image" data-bs-image-src="<?php echo e(asset('build/assets/images/photos/banner1.jpg')); ?>">
									<div class="header-text mb-0">
										<div class="container">
											<div class="row text-white">
												<div class="col my-2">
													<h1><?php echo e(lang('Knowledge')); ?></h1>
												</div>
												<div class="col col-auto">
													<ol class="breadcrumb text-center">
														<li class="breadcrumb-item">
															<a href="#" class="text-white-50"><?php echo e(lang('Home', 'menu')); ?></a>
														</li>
														<li class="breadcrumb-item">
															<a href="<?php echo e(route('knowledge')); ?>" class="text-white"><?php echo e(lang('Knowledge')); ?></a>
														</li>
														<?php if($articles->category != null): ?>
														<?php if($articles->category->categoryslug != null): ?>

														<li class="breadcrumb-item ">
															<a href="<?php echo e(url('category/'. $articles->category->categoryslug)); ?>" class="text-white"> <?php echo e($articles->category->name); ?></a>
														</li>
														<?php else: ?>

														<li class="breadcrumb-item ">
															<a href="<?php echo e(url('category/'. $articles->category->id	)); ?>" class="text-white"> <?php echo e($articles->category->name); ?></a>
														</li>
														<?php endif; ?>
														<?php else: ?>
															<li class="breadcrumb-item ">
															~
															</li>
														<?php endif; ?>

														<?php if($articles->subcategory != null): ?>
														<li class="breadcrumb-item ">
															<a href="#" class="text-white"><?php echo e($articles->subcategorys->subcategoryname); ?></a>
														</li>
														<?php endif; ?>
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
								<div class="cover-image sptb">
									<div class="container">
										<div class="row">
											<div class="col-xl-8">
												<div class="card">
													<div class="px-5 pb-0 pt-5 pos-relative">
														<div class="w-lg-90 w-md-lg-85 w-100">
															<h4 class="card-title mb-2"><?php echo e($articles->title); ?></h4>
															<ul class="mb-0 d-flex flex-wrap fs-13 custom-ul">
																<li class="me-5">
																	<span><i class="feather feather-clock text-muted me-1"></i><?php echo e(lang('Last Created On')); ?> <span
																			class="text-muted"><?php echo e(\Carbon\Carbon::parse($articles->created_at)->format('M d, Y')); ?></span></span>
																</li>
																<li class="me-5" data-placement="top" data-bs-toggle="tooltip" title=""
																	data-bs-original-title="<?php echo e(lang('Views')); ?>">
																	<?php if((setting('article_count') == 'on')): ?>
                                                                        <span><i class="feather feather-eye text-muted me-1"></i><?php echo $articles->views; ?></span>
                                                                    <?php endif; ?>
																</li>
															</ul>
														</div>
														<div class="klview-icons btn-group">
															<span class="btn btn-white btn-sm"><i
																	class="fa fa-thumbs-up text-success"></i> <?php echo e($like->count()); ?></span>
															<span class="btn btn-white btn-sm"><i
																	class="fa fa-thumbs-down text-danger"></i> <?php echo e($dislike->count()); ?></span>
														</div>
													</div>
													<div class="card-body pt-0">
														<?php if($articles->privatemode == 1): ?>
															<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()): ?>
															<div class="mb-4 description mt-3">
																<?php if($articles->featureimage != null): ?>
																<?php if($articles->featureimage == 'frontend.jpg'): ?>

																<img class="imagecenter" src="<?php echo e(asset('uploads/featureimage/demo/frontend.jpg')); ?>" alt="">
																<?php else: ?>

																<img class="imagecenter" src="<?php echo e(route('getImage.url', ['imagePath' =>'uploads*featureimage*'.$articles->featureimage,'storage_disk'=>$articles->storage_disk ?? 'public'])); ?>" class="br-5" alt="<?php echo e($articles->featureimage); ?>" alt="<?php echo e($articles->featureimage); ?>">
																<?php endif; ?>
																<?php endif; ?>
																<div class="mt-3"><?php echo ucfirst($articles->message); ?></div>

																<div class="row">
																	<div class="col-xl-12">
																		<div class="row galleryopen">
                                                                            <div class="uhelp-attach-container flex-wrap">
                                                                                <?php $__currentLoopData = $articles->getMedia('article'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                $a = explode('.', $article->file_name);
                                                                                $aa = $a[1];
                                                                                ?>

                                                                                <div class="border d-table rounded attach-container-width mb-2">
                                                                                    <div class="d-flex align-items-center file-attach-uhelp">
                                                                                        <div class="me-2">
                                                                                            <?php if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG'): ?>
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                                                </svg>
                                                                                            <?php elseif($aa == 'pdf'): ?>
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                                                </svg>
                                                                                            <?php elseif($aa == 'csv'): ?>
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                                                </svg>
                                                                                            <?php elseif($aa == 'png'): ?>
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                                                </svg>
                                                                                            <?php else: ?>
                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                                                </svg>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                                                            <p class="file-attach-name text-truncate mb-0"><?php echo e($a[0]); ?></p>.<?php echo e($a[1]); ?>

                                                                                        </div>
                                                                                        <a href="<?php echo e(route('imageurl', array($article->id,$article->file_name))); ?>" target="_blank" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                                        class="fe fe-eye text-muted fs-12"></i></a>
                                                                                        <a href="<?php echo e(route('imagedownload', array($article->id,$article->file_name))); ?>" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                                        class="fe fe-download text-muted fs-12"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </div>
                                                                        </div>
																	</div>
																</div>
															</div>
															<?php else: ?>

															<div class="alert alert-light-warning mt-3">
																<p class="privatearticle">
																<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
																You must be logged in and have valid account to access this content.
																</p>
															</div>
															<?php endif; ?>
														<?php else: ?>
														<div class="mb-4 description mt-3">
															<?php if($articles->featureimage != null): ?>
															<?php if($articles->featureimage == 'frontend.jpg'): ?>

															<img class="imagecenter" src="<?php echo e(asset('uploads/featureimage/demo/frontend.jpg')); ?>" alt="">
															<?php else: ?>
															<img class="imagecenter" src="<?php echo e(route('getImage.url', ['imagePath' =>'uploads*featureimage*'.$articles->featureimage,'storage_disk'=>$articles->storage_disk ?? 'public'])); ?>" class="br-5" alt="<?php echo e($articles->featureimage); ?>" alt="<?php echo e($articles->featureimage); ?>">
															<?php endif; ?>
															<?php endif; ?>
															<div class="mt-3"><?php echo ucfirst($articles->message); ?></div>

															<div class="row">
																<div class="col-xl-12">
																	<div class="row galleryopen">
                                                                        <div class="uhelp-attach-container flex-wrap">
                                                                            <?php $__currentLoopData = $articles->getMedia('article'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                            $a = explode('.', $article->file_name);
                                                                            $aa = $a[1];
                                                                            ?>

                                                                            <div class="border d-table rounded attach-container-width mb-2">
                                                                                <div class="d-flex align-items-center file-attach-uhelp">
                                                                                    <div class="me-2">
                                                                                        <?php if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG'): ?>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                                            </svg>
                                                                                        <?php elseif($aa == 'pdf'): ?>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                                            </svg>
                                                                                        <?php elseif($aa == 'csv'): ?>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                                            </svg>
                                                                                        <?php elseif($aa == 'png'): ?>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                                            </svg>
                                                                                        <?php else: ?>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                                            </svg>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                                                        <p class="file-attach-name text-truncate mb-0"><?php echo e($a[0]); ?></p>.<?php echo e($a[1]); ?>

                                                                                    </div>
                                                                                    <a href="<?php echo e(route('imageurl', array($article->id,$article->file_name))); ?>" target="_blank" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                                    class="fe fe-eye text-muted fs-12"></i></a>
                                                                                    <a href="<?php echo e(route('imagedownload', array($article->id,$article->file_name))); ?>" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                                    class="fe fe-download text-muted fs-12"></i></a>
                                                                                </div>
                                                                            </div>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                    </div>
																</div>
															</div>
														</div>
														<?php endif; ?>
													</div>

													<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()): ?>

													<div class="card-body d-md-flex">
														<div>
															<span class=""><?php echo e(lang('Was this article useful to you?')); ?></span>
															<button class="btn btn-success btn-sm likedislike" type="button" value="like" data-name="<?php echo e($articles->id); ?>" <?php if($viewrating != null): ?> <?php echo e($viewrating->rating == '1' ? 'disabled' : ''); ?> <?php endif; ?>>
																<i class="fa fa-thumbs-up"></i>
															</button>
															<button class="btn btn-danger btn-sm likedislike" type="button" value="dislike" data-name="<?php echo e($articles->id); ?>" <?php if($viewrating != null): ?> <?php echo e($viewrating->rating == '-1' ? 'disabled' : ''); ?> <?php endif; ?>>
																<i class="fa fa-thumbs-down"></i>
															</button>
															<a href="<?php echo e(url('likes/'.$articles->id)); ?>">

															</a>
															<a href="<?php echo e(url('dislikes/'.$articles->id)); ?>">

															</a>
														</div>
														<div class="ms-auto">
                                                            <?php if((setting('article_count') == 'on')): ?>
                                                                <span><?php echo e(lang('Views')); ?>:</span>
                                                                <span class="font-weight-semibold ms-1">
                                                                    <?php echo $articles->views; ?>

                                                                </span>
                                                            <?php endif; ?>
														</div>
													</div>

													<?php else: ?>
														<div class="card-body d-md-flex">
															<div class="ms-auto">
                                                                <?php if((setting('article_count') == 'on')): ?>
                                                                    <span><?php echo e(lang('Views')); ?>:</span>
                                                                    <span class="font-weight-semibold ms-1">
                                                                        <?php echo $articles->views; ?>

                                                                    </span>
                                                                <?php endif; ?>
															</div>
														</div>
													<?php endif; ?>


												</div>

											</div>
											<div class="col-xl-4">
												<div class="card p-0">
													<div class="search-background article-search p-0">
														<input type="text" class="form-control input-lg" name="search_name" id="search_name"  placeholder="<?php echo e(lang('Ask your Questions...')); ?>">
														<button class="btn"><i class="fe fe-search"></i></button>

														<div id="searchList">

														</div>
													</div>
													<?php echo csrf_field(); ?>
												</div>
												<div class="card ">
													<div class="card-header  border-0">
														<h4 class="card-title"><?php echo e(lang('Recent Articles')); ?></h4>
													</div>
													<div class="card-body">
														<div class="list-catergory ">
															<ul class="item-list item-list-scroll mb-0 custom-ul">
																<?php $__currentLoopData = $recentarticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentarticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<li class="item mb-4 position-relative">
																	<?php if($recentarticle->articleslug != null): ?>

																	<?php if($recentarticle->subcategory != null): ?>
																		<?php if($recentarticle->subcategorys): ?>
																		<small class="fs-12 d-block text-muted ms-7"><?php echo e($recentarticle->subcategorys->subcategoryname); ?></small>
																		<?php endif; ?>
																	<?php endif; ?>
																	<a href="<?php echo e(url('/article/' . $recentarticle->articleslug)); ?> " class=" admintickets"></a>

																	<?php else: ?>

																	<?php if($recentarticle->subcategory != null): ?>
																		<?php if($recentarticle->subcategorys): ?>
																		<small class="fs-12 d-block text-muted ms-7"><?php echo e($recentarticle->subcategorys->subcategoryname); ?></small>
																		<?php endif; ?>
																	<?php endif; ?>

																	<a href="<?php echo e(url('/article/' . $recentarticle->id)); ?> " class=" admintickets"></a>
																	<?php endif; ?>
																	<div class="d-flex">
																		<div class="me-7">
																			<i class="typcn typcn-document-text item-list-icon"></i>

																		</div>
																		<div class="">
																			<span class=""><?php echo e(Str::limit($recentarticle->title,'40')); ?> </span>
																		</div>
																		<div class=" ms-auto">
                                                                            <?php if((setting('article_count') == 'on')): ?>
																				<span class="badge badge-light badge-md fs-10">
                                                                                    <i class="fa fa-eye me-1"></i><?php echo e($recentarticle->views); ?>

                                                                                </span>
                                                                            <?php endif; ?>
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

																	<?php if($populararticle->subcategory != null): ?>
																		<?php if($populararticle->subcategorys): ?>
																		<small class="fs-12 d-block text-muted"><?php echo e($populararticle->subcategorys->subcategoryname); ?></small>
																		<?php endif; ?>
																	<?php endif; ?>
																	<a href="<?php echo e(url('/article/' . $populararticle->articleslug)); ?> " class=" admintickets"></a>

																	<?php else: ?>

																	<?php if($populararticle->subcategory != null): ?>
																		<?php if($populararticle->subcategorys): ?>
																		<small class="fs-12 d-block text-muted"><?php echo e($populararticle->subcategorys->subcategoryname); ?></small>
																		<?php endif; ?>
																	<?php endif; ?>

																	<a href="<?php echo e(url('/article/' . $populararticle->id)); ?> " class=" admintickets"></a>
																	<?php endif; ?>
																	<div class="d-flex">
																		<div class="me-7">
																			<i class="typcn typcn-document-text item-list-icon"></i>
																		</div>
																		<div class="">
																			<span class=""><?php echo e(Str::limit($populararticle->title, '40')); ?> </span>
																		</div>
																		<div class="ms-auto">
                                                                            <?php if((setting('article_count') == 'on')): ?>
																				<span class="badge badge-light badge-md fs-10">
																					<i class="fa fa-eye me-1"></i><?php echo e($populararticle->views); ?>

                                                                                </span>
                                                                            <?php endif; ?>
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
							<!--Section-->
						</div>
					</div>
				</div>

				<?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<!-- Back to top -->
		<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

		<!--Moment js-->
		<script src="<?php echo e(asset('build/assets/plugins/moment/moment.js')); ?>"></script>

		<!-- Bootstrap4 js-->
		<script src="<?php echo e(asset('build/assets/plugins/bootstrap/popper.min.js')); ?>"></script>
		<script src="<?php echo e(asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>

		<!-- P-scroll js-->
		<script src="<?php echo e(asset('build/assets/plugins/p-scrollbar/p-scrollbar.js')); ?>"></script>

		<!-- Select2 js -->
		<script src="<?php echo e(asset('build/assets/plugins/select2/select2.full.min.js')); ?>"></script>

		<!--INTERNAL Horizontalmenu js -->
		<script src="<?php echo e(asset('build/assets/plugins/horizontal-menu/horizontal-menu.js')); ?>"></script>

		<!--INTERNAL Sticky js -->
		<script src="<?php echo e(asset('build/assets/plugins/sticky/sticky2.js')); ?>"></script>

		<?php echo $__env->yieldContent('scripts'); ?>

		<!--INTERNAL Toastr js -->
		<script src="<?php echo e(asset('build/assets/plugins/toastr/toastr.min.js')); ?>"></script>


		<!-- INTERNAL Vertical-scroll js-->
		<script src="<?php echo e(asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')); ?>"></script>


		<!-- GALLERY JS -->
		<script src="<?php echo e(asset('build/assets/plugins/simplelightbox/simplelightbox.js')); ?>"></script>
		<script src="<?php echo e(asset('build/assets/plugins/simplelightbox/light-box.js')); ?>"></script>

		<!-- Custom js-->
		<script src="<?php echo e(asset('build/assets/js/custom.js')); ?>"></script>


		<!-- Custom js-->
		<script type="text/javascript">
            $(function() {
                "use strict";

                // Variables
                var SITEURL = '<?php echo e(url('')); ?>';

                // Csrf Field
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                <?php echo customcssjs('CUSTOMJS') ?>

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

                $('body').on('click', '.likedislike', function(){
                    let dataname = $(this).data('name');
                    let datavalue = $(this).val();
                    $.ajax({
                        url:"<?php echo e(route('likedislike')); ?>",
                        method:"post",
                        data:{
                            'dataname':dataname,
                            'datavalue':datavalue,
                        },
                        success:function(data)
                        {
                            if(data.data == 'success'){
                                location.reload();
                            }
                        },
                        error:function(data){
                            console.log(data);
                        }
                    });
                });
            })

		</script>

			<?php if(auth()->guard()->guest()): ?>
			<?php if(customcssjs('CUSTOMCHATENABLE') == 'enable'): ?>
			<?php if(customcssjs('CUSTOMCHATUSER') == 'public'): ?>
			<?php echo customcssjs('CUSTOMCHAT') ?>
			<?php endif; ?>
			<?php endif; ?>
			<?php else: ?>
			<?php if(customcssjs('CUSTOMCHATENABLE') == 'enable'): ?>
			<?php if(Auth::guard('customer')->check() && Auth::guard('customer')->user()): ?>
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

		<?php echo $__env->make('user.auth.modalspopup.register', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<?php echo $__env->make('user.auth.modalspopup.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		<?php echo $__env->make('user.auth.modalspopup.forgotpassword', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html>
<?php /**PATH /var/www/html/resources/views/knowledge-view.blade.php ENDPATH**/ ?>