
@if ($allowreply)
    @if ($ticket->status != 'Closed' && $ticket->status != 'Suspend' )
        <div class="card" >

            @if($comments->isNotEmpty())

            @if($ticketdraft != null)<span class="badge badge-warning-light py-1 ms-2 clear-drafts"><i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="" aria-label="Ticket Draft"></i> {{lang('Draft')}}
                <a href="javascript:void(0)" class="ms-2" data-id="{{$ticketdraft->id}}" id="draftdelete" data-bs-toggle="tooltip" data-placement="top" title="Delete"><i class="feather feather-trash-2 text-danger fs-16"></i></a></span>@endif

                <div class="panel-group1" id="accordion1">
                    <div class="panel panel-default overflow-hidden br-7">
                        <div class="panel-heading1 panel-arrows">
                            <h4 class="panel-title1">
                            <a class="accordion-toggle collapsed bg-secondary" data-bs-toggle="collapse"
                                data-parent="#accordion" href="#collapseFour" aria-expanded="false">
                            <i class="feather feather-edit-2"></i>

                            {{lang('Reply Ticket')}}</a>
                            </h4>
                        </div>

                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-expanded="false">
                            <div class="panel-body p-0">
                            @else
                                <div class="card-header  border-0 justify-content-between">
                                    <h4 class="card-title">{{lang('Reply Ticket')}}@if($ticketdraft != null)<span class="badge badge-warning-light py-1 ms-2"><i class="feather feather-edit-2" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="" aria-label="Ticket Draft"></i> {{lang('Draft')}}
                                        <a href="javascript:void(0)" class="ms-2" data-id="{{$ticketdraft->id}}" id="draftdelete" data-bs-toggle="tooltip" data-placement="top" title="Delete"><i class="feather feather-trash-2 text-danger fs-16"></i></a>

                                        </span>
                                        @endif</h4>
                                    <span id="replyStatus"></span>
                                </div>
                            @endif

                            <form method="POST" action="{{url('admin/ticket/'. encrypt($ticket->ticket_id))}}" id="draft_form" enctype="multipart/form-data">
                                @csrf
                                @honeypot

                                @if($ticketdraft != null)
                                    <input type="hidden" name="draft_id" value="{{ $ticketdraft->id }}">
                                @endif

                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="card-body status cannedresponse-custom">
                                    <div class="col-md-12 col-sm-12 ps-0 ps-lg-1 pb-5 pe-0">
                                        <div class="d-lg-flex justify-content-between">
                                            <div class="d-flex flex-fill flex-xl-nowrap flex-wrap align-items-center">
                                                <label class="form-label me-2 flex-shrink-0">{{lang('Canned Response')}}</label>
                                                <div class="flex-1 mb-2 mb-lg-0">
                                                    <select name="cannedmessage" id="cannedmessagess" class="cannedmessage select2 form-control mw"  data-placeholder="{{lang('Select Canned Messages')}}">
                                                        <option value="" label="Select  Canned Messages"></option>
                                                        @foreach ($cannedmessages as $cannedmessage=>$cm)
                                                        <option value="{{$cannedmessage}}">{{$cm->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <span id="replyStatus"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <textarea class="summernote form-control @error('comment') is-invalid @enderror" rows="6" cols="100" name="comment" id="summernoteempty" aria-multiline="true">@if($ticketdraft != null) {!! $ticketdraft->description !!} @else {{old('comment')}} @endif</textarea>
                                        <span class="text-danger" id="descriptionError"></span>
                                        @error('comment')
                                        <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ lang($message) }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">{{lang('Upload File', 'filesetting')}}</label>
                                        <div class="file-browser">
                                        <div class="needsclick dropzone" id="document-dropzone" data-id="@if($ticketdraft != null){{$ticketdraft->getMedia('ticketdrafts')->count() >= 1 ? $ticketdraft->getMedia('ticketdrafts')->count() : 0}} @else 0 @endif"></div>
                                        </div>
                                        <small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
                                    </div>
                                    @if($ticketdraft != null)
                                    <div class="d-flex align-items-center">
                                        @foreach ($ticketdraft->getMedia('ticketdrafts') as $ticketdrafts)
                                            <div class="file-image-1 m-1 removespruko{{$ticketdrafts->id}}">
                                                <div class="product-image">
                                                {{-- here we have to get provider files --}}
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ route('getImage.url', ['imagePath' =>$ticketdrafts->id.'*'.$ticketdrafts->file_name,'storage_disk'=>$ticketdrafts->disk ?? 'public'])}}" class="br-5" alt="{{$ticketdrafts->file_name}}">

                                                    </a>
                                                    <ul class="icons">
                                                        <li>
                                                            <a href="javascript:void(0)" class="action-btns" data-id="{{$ticketdrafts->id}}" id="ticketdraftimg" data-bs-toggle="tooltip" data-placement="top" title="Delete"><i class="feather feather-trash-2 text-danger fs-16"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="custom-controls-stacked d-md-flex" id="text">
                                            <label class="form-label mt-1 me-5">{{lang('Status')}}</label>
                                            <label class="custom-control form-radio success me-4">
                                            @if($ticket->status == 'Re-Open')
                                            <input type="radio" class="custom-control-input hold sprukostatuschange" name="status"  id="Inprogress1" value="Inprogress"
                                            {{ $ticket->status == 'Re-Open' ? 'checked' : '' }} >
                                            <span class="custom-control-label">{{lang('Inprogress')}}</span>
                                            @elseif($ticket->status == 'Inprogress')
                                            <input type="radio" class="custom-control-input hold sprukostatuschange" name="status"  id="Inprogress2" value="{{$ticket->status}}"
                                            {{ $ticket->status == 'Inprogress' ? 'checked' : '' }} >
                                            <span class="custom-control-label">{{lang('Inprogress')}}</span>
                                            @else
                                            <input type="radio" class="custom-control-input hold sprukostatuschange" name="status" id="Inprogress3" value="Inprogress"
                                            {{ $ticket->status == 'New' ? 'checked' : '' }} >
                                            <span class="custom-control-label">{{lang('Inprogress')}}</span>
                                            @endif
                                            </label>
                                            <label class="custom-control form-radio success me-4">
                                            <input type="radio" class="custom-control-input hold sprukostatuschange" name="status" id="closed" value="Solved" >
                                            <span class="custom-control-label">{{lang('Solved')}}</span>
                                            </label>
                                            <label class="custom-control form-radio success me-4">
                                            <input type="radio" class="custom-control-input sprukostatuschange" name="status" id="onhold" value="On-Hold" @if(old('status') == 'On-Hold') checked @endif {{ $ticket->status == 'On-Hold' ? 'checked' : '' }}>
                                            <span class="custom-control-label">{{lang('On-Hold')}}</span>
                                            </label>
                                        </div>
                                        {{-- @if(setting('ticketrating') == 'off')
                                            <div class="switch_section d-none" id="ratingonoff">
                                                <div class="d-flex d-md-max-block mt-4 ms-0">
                                                    <a class="onoffswitch2">
                                                        <input type="checkbox" name="rating_on_off" id="rating_on_off" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if($ticket->rating_on_off == 'on') checked="" @endif>
                                                        <label for="rating_on_off" class="toggle-class onoffswitch2-label" ></label>
                                                    </a>
                                                    <label class="form-label ps-3 ps-md-max-0">{{lang('Rating page to customer')}}</label>
                                                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you Enable this switch, you stop rating page to the customer')}})</i></small>
                                                </div>
                                            </div>
                                        @endif --}}


                                        @if(setting('ticketrating') == 'off')
                                            <div class="switch_section d-none" id="ratingonoff">
                                                <div class="d-flex d-md-max-block mt-4 ms-0">
                                                    <a class="onoffswitch2">
                                                        <input type="checkbox" name="rating_on_off" id="rating_on_off" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('ticketrating') == 'off') checked="" @endif>
                                                        <label for="rating_on_off" class="toggle-class onoffswitch2-label" ></label>
                                                    </a>
                                                    <label class="form-label ps-3 ps-md-max-0">{{lang('Rating page to customer')}}</label>
                                                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this switch, you can stop rating page to the customer')}})</i></small>
                                                </div>
                                            </div>
                                        @endif
                                        @if(setting('USER_REOPEN_ISSUE') == 'yes')
                                            <div class="switch_section d-none" id="reopenonoff">
                                                <div class="d-flex d-md-max-block mt-4 ms-0">
                                                    <a class="onoffswitch2">
                                                        <input type="checkbox" name="reopen_on_off" id="reopen_on_off" class=" toggle-class onoffswitch2-checkbox sprukoregister" value="yes" @if(setting('USER_REOPEN_ISSUE') == 'yes') checked="" @endif>
                                                        <label for="reopen_on_off" class="toggle-class onoffswitch2-label" ></label>
                                                    </a>
                                                    <label class="form-label ps-3 ps-md-max-0">{{lang('Ticket Re-open')}}</label>
                                                    <small class="text-muted ps-2 ps-md-max-0"><i>({{lang('If you disable this switch, you can stop the reopen option to the customer for this ticket.')}})</i></small>
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <button type="button" class="btn btn-gray ms-auto" id="draftsave">{{lang('Save As Draft')}}</button>
                                    <button type="submit" class="btn btn-secondary deletelocalstorage ms-2" id="btnsprukodisable" value="{{lang('Reply Ticket')}}"  onclick="this.disabled=true;this.innerHTML=`{{lang('Reply Ticket')}} <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();">{{lang('Reply Ticket')}}</button>
                                </div>
                            </form>
                            @if($comments->isNotEmpty())
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
@endif
