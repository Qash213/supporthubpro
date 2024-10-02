        <!--Login Modal-->
        <div class="modal fade" id="forgotmodal">
            <div class="modal-dialog forget-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(lang('Forgot Password?')); ?></h5>
                        <button class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="single-page customerpage">
                            <div class="wrapper wrapper2 box-shadow-0 border-0">
                                <form class="card-body pt-3" id="forgot_form" name="forgot_form"
                                    method="post">
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
unset($__errorArgs, $__bag); ?>" name="email"
                                            value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(lang('Email')); ?>" type="email" id="email">
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
                                        

                                        <button type="button" class="btn btn-secondary btn-block" id="forgotbtnsave" onclick="forgots();"><?php echo e(lang('Submit')); ?></button>
                                    </div>
                                    <div class="text-center mt-4">
                                        <p class="text-dark mb-0"><?php echo e(lang('Already have an account?')); ?><a class="text-primary ms-1" href="#"
                                                data-bs-toggle="modal" id="login2" data-bs-target="#loginmodal"><?php echo e(lang('Login', 'menu')); ?></a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Login Modal  -->

        <script type="text/javascript">
            $(function() {
                "use strict";

                //set button id on click to hide first modal
                $("#login2").on( "click", function() {
                    $('#forgotmodal').modal('hide');
                    $('#forgot_form').trigger("reset");

                });
                //trigger next modal
                $("#login2").on( "click", function() {
                    $('#loginmodal').modal('show');

                });

            })

            // Forgot paswword js
            function forgots(){
                $('#forgotbtnsave').html('<i class="fa fa-spinner fa-spin"></i>');
                if($('#email').val() == "")
                {
                    toastr.error('Please Enter Your Email');
                    $('#forgotbtnsave').html('Submit');
                    return false;
                }
                var data = $("#forgot_form").serialize();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                        type : 'POST',
                        url: '<?php echo e(route('ajax.forgot')); ?>',
                        data : data,
                    success : function(response)
                    {
                        $('#forgotbtnsave').html('Submit');
                        if(response == 1)
                        {
                            $('#forgotmodal').modal('hide');
                            $('#forgot_form').trigger("reset");
                            toastr.success('<?php echo e(lang('The password reset link has been sent to your email.', 'alerts')); ?>');
                        }
                        else if(response == 3)
                        {
                            toastr.error('<?php echo e(lang('These credentials do not match our records.', 'alerts')); ?>');
                        }
                    },
                    error: function(response){
                        toastr.error('<?php echo e(lang('Please enter correct credentials.', 'alerts')); ?>');
                        $('#forgotbtnsave').html('Submit');
                    }

                });
            }
        </script>
<?php /**PATH /var/www/html/resources/views/user/auth/modalspopup/forgotpassword.blade.php ENDPATH**/ ?>