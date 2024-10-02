                                <div class="page-rightheader d-flex ms-md-auto">

                                    <!-- @if ($ticket->status == 'Closed')
									<button type="buttom" class="btn btn-sm btn-light me-2 d-none" id="ticket_to_article" value="">
											<i class="feather feather-book me-3 fs-18 my-auto text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Create Article') }}"></i>
											<span>{{ lang('Ticket To Article') }} </span>
											</button>
											<a href="{{ route('admin.article.ticket', $ticket->ticket_id) }}" class="btn btn-sm btn-light me-2"  id="ticket_to_article">
											<i class="feather feather-book me-3 fs-18 my-auto text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ lang('Create Article') }}"></i>
											<span>{{ lang('Ticket To Article') }} </span>
											</a>
									@endif -->

                                    @if($ticket->status != 'Closed')
                                        <a href="javascript:void(0)" data-importstatus="{{$ticket->importantticket ?? 'off'}}" data-id="{{encrypt($ticket->id)}}" id="importantticket" class="importantticket  px-3 btn btn-light me-2">
                                        @if ($ticket->importantticket == 'on')
                                            <i class="fa fa-star fs-18 my-auto text-warning" data-importstatus="{{$ticket->importantticket ?? 'off'}}" data-id="{{encrypt($ticket->id)}}"></i>
                                        @else
                                            <i class="fa fa-star-o fs-18 my-auto text-muted" data-importstatus="{{$ticket->importantticket ?? 'off'}}" data-id="{{encrypt($ticket->id)}}"></i>
                                        @endif
                                        </a>
                                    @endif

                                    <div class="btn-list">

                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fe fe-more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @can('Ticket Delete')
                                                    <a href="javascript:void(0)" data-id="{{ encrypt($ticket->id) }}"
                                                        class="dropdown-item" id="show-delete">
                                                        <i class="fa fa-trash me-3 fs-18 my-auto text-muted"
                                                            data-id="{{ encrypt($ticket->id) }}"></i>
                                                        {{ lang('Delete Ticket') }}
                                                    </a>
                                                @endcan

                                                @if($ticket->status != 'Closed')
                                                    <a href="javascript:void(0)" data-id="{{encrypt($ticket->id)}}" id="forceclose" class="dropdown-item forceclose">
                                                    <i class="fa fa-times-circle me-3 fs-18 my-auto text-muted" data-id="{{encrypt($ticket->id)}}"></i>
                                                    {{lang('Force close')}}
                                                    </a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
