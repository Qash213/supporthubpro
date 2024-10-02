
@foreach ($comments as $comment)
    {{--Admin Reply status--}}
    @if($comment->user_id != null)

        @if ($comment->user_id == '741741741')
            <div class="card-body">
                <div class="d-lg-flex gap-1">
                    @if (setting('bot_image') == null)
                        <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                    @else
                        <img src="{{asset('uploads/profile/botprofile/'.setting('bot_image'))}}"  class="media-object brround avatar-lg me-3" alt="default">
                    @endif

                    <div class="d-flex">
                        <div class="media-body">

                            <h5 class="mt-1 mb-1 font-weight-semibold">{{ setting('bot_name') }} <span class="badge badge-primary-light badge-md ms-2">{{ lang('Bot') }}</span></h5>

                            <small class="text-muted"><i class="feather feather-clock"></i>
                                @if(\Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('Y-m-d') == now()->timezone(timeZoneData())->format('Y-m-d'))
                                    {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                    @if($ticket->tickettype != 'emalitoticket')
                                    <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('D, d M Y, h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                    @if($ticket->tickettype != 'emalitoticket')
                                    <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                    @endif
                                @endif
                                @if($ticket->tickettype != 'emalitoticket')
                                <span class="badge badge-success-light badge-md ms-2">@if($comment->lastseen != null)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                    <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                    <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                    </svg>
                                {{lang('Seen')}} {{lang(\Carbon\Carbon::parse($comment->lastseen)->timezone(timeZoneData())->diffForHumans())}}@endif</span>
                                @endif
                            </small>
                            <div class="fs-13 mb-0 mt-1 text-break pe-5 custom-text custom-text-content">
                                {!! $comment->comment !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @else
            @if ($loop->first)

                <div class="card-body">
                    <div class="d-lg-flex gap-1">
                        @if ($comment->user != null)
                            @if ($comment->user->image == null)

                                <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                            @else
                                <img class="media-object brround avatar-lg me-3" alt="{{$comment->user->image}}" src="{{ route('getprofile.url', ['imagePath' =>$comment->user->image,'storage_disk'=>$comment->user->storage_disk ?? 'public']) }}">

                            @endif
                        @else

                            <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                        @endif

                        <div class="d-flex">
                            <div class="media-body">
                                @if($comment->user != null)

                                <h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->user->name }}@if(!empty($comment->user->getRoleNames()[0]))<span class="badge badge-primary-light badge-md ms-2">{{ $comment->user->getRoleNames()[0] }}</span>@endif</h5>
                                @else

                                <h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>
                                @endif

                                <small class="text-muted"><i class="feather feather-clock"></i>
                                    @if(\Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('Y-m-d') == now()->timezone(timeZoneData())->format('Y-m-d'))
                                        {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                        @if($ticket->tickettype != 'emalitoticket')
                                        <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                        @endif
                                    @else
                                        {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('D, d M Y, h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                        @if($ticket->tickettype != 'emalitoticket')
                                        <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                        @endif
                                    @endif
                                    @if($ticket->tickettype != 'emalitoticket')
                                    <span class="badge badge-success-light badge-md ms-2">@if($comment->lastseen != null)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                        </svg>
                                    {{lang('Seen')}} {{lang(\Carbon\Carbon::parse($comment->lastseen)->timezone(timeZoneData())->diffForHumans())}}@endif</span>
                                    @endif
                                </small>
                                <div class="fs-13 mb-0 mt-1 text-break custom-text-content">
                                    {!! $comment->comment !!}
                                </div>
                                <div class="editsupportnote-icon animated text-break" id="supportnote-icon-{{$comment->id}}">
                                    <form action="{{url('admin/ticket/editcomment/'.$comment->id)}}" method="POST">
                                        @csrf
                                        <textarea class="editsummernote" name="editcomment"> {{$comment->comment}}</textarea>
                                        <div class="form-group">
                                            <label class="form-label">{{lang('Upload File', 'filesetting')}}</label>
                                            <div class="file-browser">
                                            {{-- <div class="needsclick dropzone" id="document-dropzone" data-id="{{$comment->id}}"></div> --}}
                                            <div class="needsclick dropzone" id="document-dropzone" data-id="{{$comment->getMedia('comments')->count() >= 1 ? $comment->getMedia('comments')->count() : 0}}"></div>
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @foreach ($comment->getMedia('comments') as $commentss)


                                            <div class="file-image-1 m-1 editimagedelete{{$commentss->id}}">
                                                <div class="product-image">
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ route('getImage.url', ['imagePath' =>$commentss->id.'*'.$commentss->file_name,'storage_disk'=>$commentss->disk ?? 'public'])}}" class="br-5" alt="{{$commentss->file_name}}">
                                                    </a>
                                                    <ul class="icons">
                                                        <li><a href="javascript:(0);" class="bg-danger imagedel" data-id="{{$commentss->id}}"><i class="fe fe-trash"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
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
                                                <input type="radio" class="custom-control-input hold sprukostatuschange" name="status" id="closed" value="Solved" {{ $ticket->status == 'Closed' ? 'checked' : '' }}>
                                                <span class="custom-control-label">{{lang('Solved')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                <input type="radio" class="custom-control-input sprukostatuschange" name="status" id="onhold" value="On-Hold" @if(old('status') == 'On-Hold') checked @endif {{ $ticket->status == 'On-Hold' ? 'checked' : '' }}>
                                                <span class="custom-control-label">{{lang('On-Hold')}}</span>
                                                </label>
                                            </div>
                                            @if(setting('ticketrating') == 'off')
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
                                            @endif
                                        </div>

                                        <div class="btn-list mt-1">

                                            <button type="submit" class="btn btn-secondary deletelocalstorage" onclick="this.disabled=true;this.innerHTML=`Updating <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();" value="Update">{{lang('Update')}}</button>
                                            <button type="button" class="btn btn-default" onclick="showEditForm('{{$comment->id}}')" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>

                                @if (Auth::id() == $comment->user_id)

                                    <div class="row galleryopen mt-4">
                                        <div class="uhelp-attach-container flex-wrap">
                                            @foreach ($comment->getMedia('comments') as $commentss)
                                                @php
                                                    $a = explode('.', $commentss->file_name);
                                                    $aa = $a[1];
                                                @endphp

                                                <div class="border d-table rounded attach-container-width mb-2 commentimagedelete{{$commentss->id}}">
                                                    <div class="d-sm-flex align-items-center file-attach-uhelp">
                                                        <div class="me-2">
                                                            @if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                </svg>
                                                            @elseif($aa == 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                </svg>
                                                            @elseif($aa == 'csv')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                </svg>
                                                            @elseif($aa == 'png')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                            <p class="file-attach-name text-truncate mb-0">{{ $a[0] }}</p>.{{ $a[1] }}
                                                        </div>

                                                    <div class="d-flex mt-2 mt-sm-0">
                                                        <a href="{{route('imageurl', array($commentss->id,$commentss->file_name))}}" target="_blank" class=" uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                        class="fe fe-eye text-muted fs-12"></i></a>

                                                        <a href="{{route('imagedownload', array($commentss->id,$commentss->file_name))}}" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                class="fe fe-download text-muted fs-12"></i></a>

                                                        {{-- <a href="javascript:void(0)" class="action-btns uhelp-attach-acion p-2 rounded border lh-1  d-flex align-items-center justify-content-center" data-id="{{$commentss->id}}" id="commentimage" data-bs-toggle="tooltip" data-placement="top" title="Delete "><i class="feather feather-trash-2 text-muted fs-12"></i></a> --}}
                                                    </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                @else

                                    <div class="row galleryopen mt-4">
                                        <div class="uhelp-attach-container flex-wrap">
                                            @foreach ($comment->getMedia('comments') as $commentss)
                                                @php
                                                    $a = explode('.', $commentss->file_name);
                                                    $aa = $a[1];
                                                @endphp

                                                <div class="border d-table rounded attach-container-width mb-2">
                                                    <div class="d-sm-flex align-items-center file-attach-uhelp">
                                                        <div class="me-2">
                                                            @if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                </svg>
                                                            @elseif($aa == 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                </svg>
                                                            @elseif($aa == 'csv')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                </svg>
                                                            @elseif($aa == 'png')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                            <p class="file-attach-name text-truncate mb-0">{{ $a[0] }}</p>.{{ $a[1] }}
                                                        </div>

                                                    <div class="d-flex mt-2 mt-sm-0">
                                                        <a href="{{route('imageurl', array($commentss->id,$commentss->file_name))}}" target="_blank" class=" uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                            class="fe fe-eye text-muted fs-12"></i></a>
                                                        <a href="{{route('imagedownload', array($commentss->id,$commentss->file_name))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                class="fe fe-download text-muted fs-12"></i></a>
                                                    </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>


                        @if($ticket->status == 'Closed')
                            @can('Article Create')
                                <div class="ms-auto d-flex align-items-center">
                                    @if($comment->lastseen == null)
                                        <div class="d-flex align-items-center">

                                            <span class="action-btns supportnote-icon" onclick="showEditForm('{{$comment->id}}')"><i class="feather feather-edit text-primary fs-16"></i></span>
                                            <a href="javascript:void(0)" class="action-btns" data-id="{{$comment->id}}" id="deletecomment" data-bs-toggle="tooltip" data-placement="top" title="Delete Comment"><i class="feather feather-trash-2 text-danger fs-16"></i></a>
                                        </div>
                                    @endif
                                    <div class="ms-2">
                                        <input type="checkbox" name="spruko_checkbox[]" class="tickettoarticle" value="{{$comment->id}}" />
                                    </div>
                                </div>
                            @endcan


                        @else
                            @if (Auth::id() == $comment->user_id || Auth::user()->getRoleNames()[0] == 'superadmin')
                                @if($comment->display != null  || $ticket->status == "Re-Open")

                                    @if($comment->lastseen == null)
                                        @php
                                            $assignee = $ticket->ticketassignmutliples->pluck('toassignuser_id')->toArray();
                                        @endphp
                                        @if (in_array(Auth::id(), $assignee) || $ticket->selfassignuser_id == Auth::id() || Auth::user()->getRoleNames()[0] == 'superadmin' || Auth::id() == $comment->user_id)
                                            <div class="ms-auto">

                                                <div class="d-flex">
                                                    <span class="action-btns supportnote-icon" onclick="showEditForm('{{$comment->id}}')"><i class="feather feather-edit text-primary fs-16"></i></span>
                                                    <a href="javascript:void(0)" class="action-btns" data-id="{{$comment->id}}" id="deletecomment" data-bs-toggle="tooltip" data-placement="top" title="Delete Comment"><i class="feather feather-trash-2 text-danger fs-16"></i></a>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                @endif
                            @endif
                        @endif

                    </div>
                </div>

            @else

                <div class="card-body">
                    <div class="d-lg-flex gap-1">
                        @if($comment->user != null)
                            @if ($comment->user->image == null)

                            <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                            @else

                                <img class="media-object brround avatar-lg me-3" alt="{{$comment->user->image}}"src="{{ route('getprofile.url', ['imagePath' =>$comment->user->image,'storage_disk'=>$comment->user->storage_disk ?? 'public']) }}">
                            @endif
                        @else
                        <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                        @endif
                        <div class="d-flex">
                            <div class="media-body">

                                @if($comment->user != null)

                                <h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->user->name }}@if(!empty($comment->user->getRoleNames()[0]))<span class="badge badge-primary-light badge-md ms-2">{{ $comment->user->getRoleNames()[0] }}</span>@endif</h5>
                                @else
                                    <h5 class="mt-1 mb-1 font-weight-semibold text-muted">~</h5>
                                @endif

                                <small class="text-muted"><i class="feather feather-clock"></i>
                                    @if(\Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('Y-m-d') == now()->timezone(timeZoneData())->format('Y-m-d'))
                                        {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                        @if($ticket->tickettype != 'emalitoticket')
                                        <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                        @endif
                                    @else
                                        {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('D, d M Y, h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                                        @if($ticket->tickettype != 'emalitoticket')
                                        <span class="badge badge-light badge-md ms-2 text-dark">@if($comment->lastseen == null)<i class="fa fa-check me-1"></i>{{lang('Sent')}}@endif</span>
                                        @endif
                                    @endif
                                    @if($ticket->tickettype != 'emalitoticket')
                                    <span class="badge badge-success-light badge-md ms-2">@if($comment->lastseen != null)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                        </svg>
                                    {{lang('Seen')}} {{lang(\Carbon\Carbon::parse($comment->lastseen)->timezone(timeZoneData())->diffForHumans())}}@endif</span>
                                    @endif
                                </small>
                                <div class="fs-13 mb-0 mt-1 text-break custom-text-content">
                                    {!! $comment->comment !!}
                                </div>

                                <div class="editsupportnote-icon animated text-break" id="supportnote-icon-{{$comment->id}}">
                                    <form action="{{url('admin/ticket/editcomment/'.$comment->id)}}" method="POST">
                                        @csrf

                                        <textarea class="editsummernote w-100" name="editcomment"> {{$comment->comment}}</textarea>

                                        <div class="form-group">
                                            <label class="form-label">{{lang('Upload File', 'filesetting')}}</label>
                                            <div class="file-browser">
                                            <div class="needsclick dropzone" id="document-dropzone" data-id="{{$comment->getMedia('comments')->count() >= 1 ? $comment->getMedia('comments')->count() : 0}}"></div>
                                            {{-- <div class="needsclick dropzone" id="document-dropzone" data-id="{{$comment->id}}"></div> --}}
                                            </div>
                                            <small class="text-muted"><i>{{lang('The file size should not be more than', 'filesetting')}} {{setting('FILE_UPLOAD_MAX')}}{{lang('MB', 'filesetting')}}</i></small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @foreach ($comment->getMedia('comments') as $commentss)


                                            <div class="file-image-1 m-1 editimagedelete{{$commentss->id}}">
                                                <div class="product-image">
                                                    <a href="javascript:void(0);">
                                                        <img src="{{ route('getImage.url', ['imagePath' =>$commentss->id.'*'.$commentss->file_name,'storage_disk'=>$commentss->disk ?? 'public'])}}" class="br-5" alt="{{$commentss->file_name}}">
                                                    </a>
                                                    <ul class="icons">
                                                        <li><a href="javascript:(0);" class="bg-danger imagedel" data-id="{{$commentss->id}}"><i class="fe fe-trash"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
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
                                                <input type="radio" class="custom-control-input hold sprukostatuschange" name="status" id="closed" value="Solved" {{ $ticket->status == 'Closed' ? 'checked' : '' }}>
                                                <span class="custom-control-label">{{lang('Solved')}}</span>
                                                </label>
                                                <label class="custom-control form-radio success me-4">
                                                <input type="radio" class="custom-control-input sprukostatuschange" name="status" id="onhold" value="On-Hold" @if(old('status') == 'On-Hold') checked @endif {{ $ticket->status == 'On-Hold' ? 'checked' : '' }}>
                                                <span class="custom-control-label">{{lang('On-Hold')}}</span>
                                                </label>
                                            </div>
                                            @if(setting('ticketrating') == 'off')
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
                                            @endif
                                        </div>

                                        <div class="btn-list mt-1">
                                            <button type="submit" class="btn btn-secondary deletelocalstorage" onclick="this.disabled=true;this.innerHTML=`Updating <i class='fa fa-spinner fa-spin'></i>`;this.form.submit();" value="Update">{{lang('Update')}}</button>
                                            <button type="button" class="btn btn-default" onclick="showEditForm('{{$comment->id}}')" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="row galleryopen mt-4">
                                    <div class="uhelp-attach-container flex-wrap">
                                        @foreach ($comment->getMedia('comments') as $commentss)
                                            @php
                                                $a = explode('.', $commentss->file_name);
                                                $aa = $a[1];
                                            @endphp

                                            <div class="border d-table rounded attach-container-width mb-2 commentimagedelete{{$commentss->id}}">
                                                <div class="d-sm-flex align-items-center file-attach-uhelp">
                                                    <div class="me-2">
                                                        @if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                            </svg>
                                                        @elseif($aa == 'pdf')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                            </svg>
                                                        @elseif($aa == 'csv')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                            </svg>
                                                        @elseif($aa == 'png')
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                        <p class="file-attach-name text-truncate mb-0">{{ $a[0] }}</p>.{{ $a[1] }}
                                                    </div>

                                                    <div class="d-flex mt-2 mt-sm-0">
                                                        <a href="{{route('imageurl', array($commentss->id,$commentss->file_name))}}" target="_blank" class=" uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                            class="fe fe-eye text-muted fs-12"></i></a>
                                                        <a href="{{route('imagedownload', array($commentss->id,$commentss->file_name))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                class="fe fe-download text-muted fs-12"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->getRoleNames()[0] == 'superadmin')
                            @if($ticket->status != 'Closed')
                                @if($comment->lastseen == null)
                                    <div class="ms-auto">
                                        <div class="d-flex">
                                            <span class="action-btns supportnote-icon" onclick="showEditForm('{{$comment->id}}')"><i class="feather feather-edit text-primary fs-16"></i></span>
                                            <a href="javascript:void(0)" class="action-btns" data-id="{{$comment->id}}" id="deletecomment" data-bs-toggle="tooltip" data-placement="top" title="Delete Comment"><i class="feather feather-trash-2 text-danger fs-16"></i></a>
                                        </div>
                                    </div>

                                @endif
                            @endif
                        @endif
                        @if($ticket->status == 'Closed')

                            @can('Article Create')
                                <div class="ms-auto d-flex align-items-center">
                                    <input type="checkbox" name="spruko_checkbox[]" class="tickettoarticle" value="{{$comment->id}}" />
                                </div>
                            @endcan
                        @endif
                    </div>
                </div>
            @endif

        @endif
        {{--Admin Reply status end--}}

    {{--Customer Reply status--}}
    @else

        <div class="card-body">
            <div class="d-lg-flex gap-1">
                @if ($comment->cust->image == null)

                <img src="{{asset('uploads/profile/user-profile.png')}}"  class="media-object brround avatar-lg me-3" alt="default">
                @else

                <img class="media-object brround avatar-lg me-3" alt="{{$comment->cust->image}}" src="{{ route('getprofile.url', ['imagePath' =>$comment->cust->image,'storage_disk'=>$comment->cust->storage_disk ?? 'public']) }}">
                @endif
                <div class="d-flex">
                    <div class="media-body">
                        <h5 class="mt-1 mb-1 font-weight-semibold">{{ $comment->cust->username }}<span class="badge badge-danger-light badge-md ms-2">{{ $comment->cust->userType }}</span></h5>
                        <small class="text-muted"><i class="feather feather-clock"></i>
                            @if(\Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('Y-m-d') == now()->timezone(timeZoneData())->format('Y-m-d'))
                                {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                            @else
                                {{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->format('D, d M Y, h:i A') }} ({{ \Carbon\Carbon::parse($comment->created_at)->timezone(timeZoneData())->diffForHumans() }})
                            @endif
                        </small>
                        <div class="fs-13 mb-0 mt-1 text-break custom-text-content">
                            @if ($ticket->tickettype == 'emalitoticket')
                                {!! nl2br(e($comment->comment)) !!}
                            @else
                                {!! $comment->comment !!}
                            @endif

                        </div>
                        <div class="row galleryopen mt-4">
                            <div class="d-flex flex-wrap">
                                <div class="uhelp-attach-container flex-wrap">
                                    @if($comment->emailcommentfile != null)
                                        @if($comment->emailcommentfile == 'mismatch')
                                            <div class="border d-table rounded attach-container-width mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('File upload failed, Please make sure that the file size is within the allowed limits and that the file format is supported.')}}">
                                                <div class="d-flex align-items-center file-attach-uhelp mt-1">
                                                    <div class="me-2">
                                                        <a href="#" class="uhelp-attach-acion d-flex align-items-center justify-content-center"><i class="fe feather-alert-circle text-danger fs-20"></i></a>
                                                    </div>
                                                    <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                        <p class="file-attach-name text-danger mb-0">Upload Failed</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @php
                                                $arraytype = explode(',', $comment->emailcommentfile);
                                            @endphp
                                            @foreach($arraytype as $arraytypes)
                                                @php
                                                    $arrayextension = explode('.', $arraytypes);
                                                    $finalextension = $arrayextension[1];
                                                @endphp
                                                <div class="border d-table rounded attach-container-width mb-2">
                                                    <div class="d-flex align-items-center file-attach-uhelp">
                                                        <div class="me-2">
                                                            <!-- <img src="{{asset('uploads/emailtoticket/'.$comment->emailcommentfile)}}" alt="img" class="header-text3 call-to-action-image img-fluid"> -->
                                                            @if($finalextension == 'jpg' || $finalextension == 'jpeg' || $finalextension == 'JPG')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                                </svg>
                                                            @elseif($finalextension == 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                                </svg>
                                                            @elseif($finalextension == 'csv')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                                </svg>
                                                            @elseif($finalextension == 'png')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                                </svg>
                                                            @else
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                            <p class="file-attach-name text-truncate mb-0">{{ $arrayextension[0] }}</p>.{{ $arrayextension[1] }}
                                                        </div>
                                                        <a href="{{route('emtcimageurl', array($comment->id,$arraytypes))}}" target="_blank" class="uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                            class="fe fe-eye text-muted fs-12"></i></a>
                                                        <a href="{{route('emtcimagedownload', array($comment->id,$arraytypes))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                                class="fe fe-download text-muted fs-12"></i></a>
                                                    </div>
                                                </div>
                                            @endforeach

                                        @endif
                                    @endif

                                    @foreach ($comment->getMedia('comments') as $commentss)
                                        @php
                                            $a = explode('.', $commentss->file_name);
                                            $aa = $a[1];
                                        @endphp
                                        <div class="border d-table rounded attach-container-width mb-2">
                                            <div class="d-sm-flex align-items-center file-attach-uhelp">
                                                <div class="me-2">
                                                    @if($aa == 'jpg' || $aa == 'jpeg' || $aa == 'JPG')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-jpg" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-4.34 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.507.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.24v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.066-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.347.158.48.275.133.117.238.253.314.407ZM0 14.786c0 .164.027.319.082.465.055.147.136.277.243.39.11.113.245.202.407.267.164.062.354.093.569.093.42 0 .748-.115.984-.345.238-.23.358-.566.358-1.005v-2.725h-.791v2.745c0 .202-.046.357-.138.466-.092.11-.233.164-.422.164a.499.499 0 0 1-.454-.246.577.577 0 0 1-.073-.27H0Zm4.92-2.86H3.322v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475.108-.201.161-.427.161-.677 0-.25-.052-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.546 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H4.11v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Z"/>
                                                        </svg>
                                                    @elseif($aa == 'pdf')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                                        </svg>
                                                    @elseif($aa == 'csv')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-csv" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM3.517 14.841a1.13 1.13 0 0 0 .401.823c.13.108.289.192.478.252.19.061.411.091.665.091.338 0 .624-.053.859-.158.236-.105.416-.252.539-.44.125-.189.187-.408.187-.656 0-.224-.045-.41-.134-.56a1.001 1.001 0 0 0-.375-.357 2.027 2.027 0 0 0-.566-.21l-.621-.144a.97.97 0 0 1-.404-.176.37.37 0 0 1-.144-.299c0-.156.062-.284.185-.384.125-.101.296-.152.512-.152.143 0 .266.023.37.068a.624.624 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.092 1.092 0 0 0-.2-.566 1.21 1.21 0 0 0-.5-.41 1.813 1.813 0 0 0-.78-.152c-.293 0-.551.05-.776.15-.225.099-.4.24-.527.421-.127.182-.19.395-.19.639 0 .201.04.376.122.524.082.149.2.27.352.367.152.095.332.167.539.213l.618.144c.207.049.361.113.463.193a.387.387 0 0 1 .152.326.505.505 0 0 1-.085.29.559.559 0 0 1-.255.193c-.111.047-.249.07-.413.07-.117 0-.223-.013-.32-.04a.838.838 0 0 1-.248-.115.578.578 0 0 1-.255-.384h-.765ZM.806 13.693c0-.248.034-.46.102-.633a.868.868 0 0 1 .302-.399.814.814 0 0 1 .475-.137c.15 0 .283.032.398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.441 1.441 0 0 0-.489-.272 1.838 1.838 0 0 0-.606-.097c-.356 0-.66.074-.911.223-.25.148-.44.359-.572.632-.13.274-.196.6-.196.979v.498c0 .379.064.704.193.976.131.271.322.48.572.626.25.145.554.217.914.217.293 0 .554-.055.785-.164.23-.11.414-.26.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.799.799 0 0 1-.118.363.7.7 0 0 1-.272.25.874.874 0 0 1-.401.087.845.845 0 0 1-.478-.132.833.833 0 0 1-.299-.392 1.699 1.699 0 0 1-.102-.627v-.495Zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879l-1.327 4Z"/>
                                                        </svg>
                                                    @elseif($aa == 'png')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-png" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-3.76 8.132c.076.153.123.317.14.492h-.776a.797.797 0 0 0-.097-.249.689.689 0 0 0-.17-.19.707.707 0 0 0-.237-.126.96.96 0 0 0-.299-.044c-.285 0-.506.1-.665.302-.156.201-.234.484-.234.85v.498c0 .234.032.439.097.615a.881.881 0 0 0 .304.413.87.87 0 0 0 .519.146.967.967 0 0 0 .457-.096.67.67 0 0 0 .272-.264c.06-.11.091-.23.091-.363v-.255H8.82v-.59h1.576v.798c0 .193-.032.377-.097.55a1.29 1.29 0 0 1-.293.458 1.37 1.37 0 0 1-.495.313c-.197.074-.43.111-.697.111a1.98 1.98 0 0 1-.753-.132 1.447 1.447 0 0 1-.533-.377 1.58 1.58 0 0 1-.32-.58 2.482 2.482 0 0 1-.105-.745v-.506c0-.362.067-.678.2-.95.134-.271.328-.482.582-.633.256-.152.565-.228.926-.228.238 0 .45.033.636.1.187.066.348.158.48.275.133.117.238.253.314.407Zm-8.64-.706H0v4h.791v-1.343h.803c.287 0 .531-.057.732-.172.203-.118.358-.276.463-.475a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.475-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.381.574.574 0 0 1-.238.24.794.794 0 0 1-.375.082H.788v-1.406h.66c.218 0 .389.06.512.182.123.12.185.295.185.521Zm1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4h.747Z"/>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                                                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center text-muted fs-12 me-3">
                                                    <p class="file-attach-name text-truncate mb-0">{{ $a[0] }}</p>.{{ $a[1] }}
                                                </div>

                                            <div class="d-flex mt-2 mt-sm-0">
                                                <a href="{{route('imageurl', array($commentss->id,$commentss->file_name))}}" target="_blank" class=" uhelp-attach-acion p-2 rounded border lh-1 me-1 d-flex align-items-center justify-content-center"><i
                                                                    class="fe fe-eye text-muted fs-12"></i></a>
                                                <a href="{{route('imagedownload', array($commentss->id,$commentss->file_name))}}" class="uhelp-attach-acion p-2 rounded border lh-1 d-flex align-items-center justify-content-center"><i
                                                        class="fe fe-download text-muted fs-12"></i></a>
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
    {{--Customer Reply status end--}}
@endforeach
