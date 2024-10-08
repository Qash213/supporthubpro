            <!-- Add testimonial-->
            <div class="modal fade"  id="addtestimonial" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" ></h5>
                            <button  class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form method="POST" enctype="multipart/form-data" id="testimonial_form" name="testimonial_form">
                            <input type="hidden" name="testimonial_id" id="testimonial_id">
                            @csrf
                            @honeypot
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{lang('Name')}} <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" >
                                    <span id="nameError" class="text-danger alert-message"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{lang('Description')}} <span class="text-red">*</span></label>
                                    <textarea class="form-control"  name="description" id="description" ></textarea>
                                    <span id="descriptionError" class="text-danger alert-message"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{lang('Designation')}} <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="designation" id="designation" >
                                    <span id="designationError" class="text-danger alert-message"></span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{lang('Upload Image')}}</label>
                                    <div class="input-group file-browser">
                                        <input class="form-control " id="image" name="image" type="file">
                                    </div>
                                    <small class="text-muted"><i>{{lang('Filesize should not be morethan 10MB', 'filesetting')}}</i></small>
                                    <div>
                                        <span id="imageError" class="text-danger alert-message"></span>
                                    </div>
                                </div>
                                <div class="file-image-1 d-none" id="testimonialimgdiv">
                                    <div class="product-image custom-ul">
                                        <a href="#">
                                            <img id="testinmonilalimage" src="" alt="Testimonial Image" width="80" height="80">
                                        </a>
                                        <ul class="icons">
                                            <li><a href="javascript:(0);" class="bg-danger delete-image" data-id=""><i class="fe fe-trash"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Close')}}</a>
                                <button type="submit" class="btn btn-secondary" id="btnsave"  >{{lang('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End  Add testimonial  -->
