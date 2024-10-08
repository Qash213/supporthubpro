  		<!-- Category List-->
          <div class="modal fade sprukosearchcategory"  id="addcategory" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" ></h5>
						<button  class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<form method="POST" enctype="multipart/form-data" id="sprukocategory_form" name="sprukocategory_form">
                        <input type="hidden" name="ticket_id" class="ticket_id">
                        @csrf
                        @honeypot
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">{{lang('Select Category')}}</label>
                                <div class="custom-controls-stacked d-md-flex" >
									<select class="form-control select4-show-search" data-placeholder="{{lang('Select category')}}" name="category" id="sprukocategorylist" >

									</select>
								</div>
								<span id="CategoryError" class="text-danger"></span>
                            </div>
							<div class="form-group" id="envatopurchase">
							</div>
							@if(setting('ENVATO_ON') == 'on')
								<div class="row d-none mb-3" id="hideelement">
										<label class="form-label mb-2 ">{{lang('Envato Item Name')}}<span class="text-red">*</span></label>
									<div class="custom-controls-stacked d-md-flex">
										<input type="text" id="productname" name ="productname" class="form-control" placeholder="Envato Project Name">

									</div>
								</div>

							@endif


							@if(true)
							<div class="form-group" id="selectssSubCategory" style="display: none;">

								<label class="form-label mb-0 mt-2">{{lang('Subcategory')}}</label>
								<select  class="form-control subcategoryselect"  data-placeholder="{{lang('Select SubCategory')}}" name="subscategory" id="subscategory">

								</select>
								<span id="subsCategoryError" class="text-danger alert-message"></span>

							</div>
							@endif
								<div class="form-group" id="selectSubCategory">
								</div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">{{lang('Close')}}</a>
                            <button type="submit" class="btn btn-secondary sprukoapiblock" id="categorybtnsave" >{{lang('Save')}}</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
		<!-- End Category List  -->
