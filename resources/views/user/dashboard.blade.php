@extends('layouts.usermaster')

@section('styles')

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />



@endsection

@section('content')
<section>
    <div class="bannerimg cover-image" data-bs-image-src="{{asset('build/assets/images/photos/banner1.jpg')}}">
        <div class="header-text mb-0">
            <div class="container ">
                <div class="row text-white">
                    <div class="col">
                        <h1 class="mb-0">{{lang('Dashboard', 'menu')}}</h1>
                    </div>
                    <div class="col col-auto">
                        <ol class="breadcrumb text-center">
                            <li class="breadcrumb-item">
                                <a href="{{url('/')}}" class="text-white-50">{{lang('Home', 'menu')}}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#" class="text-white">{{lang('Dashboard', 'menu')}}</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section -->

<!--Dashboard List-->
<section>
    <div class="cover-image sptb">
        <div class="container">
            <div class="row">
                @include('includes.user.verticalmenu')
                <div class="col-xl-9">
                    <!--- Custom notification -->
                    @php $customernotify = auth()->guard('customer')->user()->unreadNotifications()->where('data->status', 'mail')->get();  @endphp
                    @if($customernotify->isNotEmpty())
                    <div class="alert alert-warning-light br-13  border-0 d-flex align-items-center" role="alert">
                        <div class="d-flex">
                            <svg class="alt-notify  me-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="#eec466"
                                    d="M19,20H5a3.00328,3.00328,0,0,1-3-3V7A3.00328,3.00328,0,0,1,5,4H19a3.00328,3.00328,0,0,1,3,3V17A3.00328,3.00328,0,0,1,19,20Z" />
                                <path fill="#e49e00"
                                    d="M22,7a3.00328,3.00328,0,0,0-3-3H5A3.00328,3.00328,0,0,0,2,7V8.061l9.47852,5.79248a1.00149,1.00149,0,0,0,1.043,0L22,8.061Z" />
                            </svg>
                        </div>
                        <ul class="notify vertical-scroll5 custom-ul ht-0 me-5">
                            @if(auth()->guard('customer')->user())
                            @forelse( $customernotify as $notification)
                            @if ($notification->data['status'] == 'mail')
                            <li class="item">
                                <p class="fs-13 mb-0">{{$notification->data['mailsubject']}}
                                    {{Str::limit($notification->data['mailtext'], '400', '...')}} <a href="{{route('customer.notiication.view', $notification->id)}}"
                                    class="ms-3 text-blue mark-as-read">{{lang('Read more')}}</a></p>
                            </li>
                            @endif
                            @empty
                            @endforelse
                            @endif
                        </ul>
                        <div class="d-flex ms-6 sprukocnotify">
                            <button class="btn-close ms-2 mt-0 text-warning" data-bs-dismiss="alert"
                                aria-hidden="true">×</button>
                        </div>
                    </div>
                    @endif
                    <!--- End Custom notification -->

                    @if(setting('ANNOUNCEMENT_USER') == 'only_login_user' || setting('ANNOUNCEMENT_USER') == 'all_users')
                        @foreach ($announcement as $anct)
                            @if ($anct->status == 1)
                                <div class="alert alert-announcement p-5" role="alert" style="background: {{$anct->primary_color}}; color:{{$anct->secondary_color}};">
                                    <button type="submit" class="btn-close ms-5 text-danger float-end notifyclose" style="color:{{$anct->secondary_color}};" data-id="{{$anct->id}}">×</button>
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="lh-1 svg-icon flex-shrink-0" style="background: {{ str_replace(', 1)', ', 0.1)', $anct->secondary_color) }}; color:{{$anct->secondary_color}};">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                        </div>
                                        <div>
                                            <h5 class="fw-semibold mb-1" style="color:{{$anct->secondary_color}};">{{$anct->title}}</h5>
                                            <span>{!!$anct->notice!!}</span>
                                            @if ($anct->buttonlable != null && $anct->buttonurl != null)
                                                <div class="ms-auto mt-auto">
                                                    <a href="{{ $anct->buttonurl }}" target="_blank" class="btn btn-info">{{ $anct->buttonlable }}<i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="alert alert-light-warning note br-13" role="alert"  style="background: linear-gradient(to right, {{$anct->primary_color}}, {{$anct->secondary_color}});">
                                    <div class="d-flex align-items-top">
                                        <div class="me-2">
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Annonutment" aria-label="Annonutment">
                                                <svg class="svg-info" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="m-0"><b>{{$anct->title}}:-</b> {!!$anct->notice!!}</p>
                                        </div>
                                        <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" data-id="{{$anct->id}}">×</button>
                                    </div>
                                </div> --}}
                            @endif
                        @endforeach

                        @foreach ($announcements as $ancts)
                            @php
                            $announceDay = explode(',', $ancts->announcementday);
                            $now = today()->format('D');

                            @endphp
                            @foreach ($announceDay as $announceDays)
                                @if ($ancts->status == 1 && $announceDays == $now)
                                    <div class="alert alert-announcement p-5" role="alert" style="background: {{$ancts->primary_color}}; color:{{$ancts->secondary_color}};">
                                        <button type="submit" class="btn-close ms-5 text-danger float-end notifyclose" style="color:{{$ancts->secondary_color}};" data-id="{{$ancts->id}}">×</button>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="lh-1 svg-icon flex-shrink-0" style="background: {{ str_replace(', 1)', ', 0.1)', $ancts->secondary_color) }}; color:{{$ancts->secondary_color}};">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,160h40a40,40,0,0,0,0-80H160Z" opacity="0.2"/><path d="M160,80V200.67a8,8,0,0,0,3.56,6.65l11,7.33a8,8,0,0,0,12.2-4.72L200,160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M40,200a8,8,0,0,0,13.15,6.12C105.55,162.16,160,160,160,160h40a40,40,0,0,0,0-80H160S105.55,77.84,53.15,33.89A8,8,0,0,0,40,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                                            </div>
                                            <div>
                                                <h5 class="fw-semibold mb-1" style="color:{{$ancts->secondary_color}};">{{$ancts->title}}</h5>
                                                <span>{!!$ancts->notice!!}</span>
                                                @if ($ancts->buttonlable != null && $ancts->buttonurl != null)
                                                    <div class="ms-auto mt-auto">
                                                        <a href="{{ $ancts->buttonurl }}" target="_blank" class="btn btn-info">{{ $ancts->buttonlable }}<i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="alert alert-light-warning note br-13" role="alert"  style="background: linear-gradient(to right, {{$ancts->primary_color}}, {{$ancts->secondary_color}});">
                                        <div class="d-flex align-items-top">
                                            <div class=" me-2">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Annonutment" aria-label="Annonutment">
                                                    <svg class="svg-info" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                                </a>
                                            </div>
                                            <div>
                                                <p class="m-0"><b>{{$ancts->title}}:-</b> {!!$ancts->notice!!}</p>
                                            </div>
                                            <button type="submit" class="btn-close ms-5 float-end text-white notifyclose" data-id="{{$ancts->id}}">×</button>
                                        </div>
                                    </div> --}}
                                @endif
                            @endforeach
                        @endforeach
                    @endif

                    <div class="row">

                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="card">
                                <span>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="mt-0 text-start">
                                                    <span class="fs-16 font-weight-semibold">{{lang('Total Tickets',
                                                        'menu')}}</span>
                                                    <h3 class="mb-0 mt-1 text-primary fs-25">{{$tickets->count()}}</h3>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="icon1 bg-primary-transparent my-auto float-end"> <i
                                                        class="las la-ticket-alt"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="card">
                                <span>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="mt-0 text-start">
                                                    <span class="fs-16 font-weight-semibold">{{lang('Active Tickets',
                                                        'menu')}}</span>
                                                    <h3 class="mb-0 mt-1 text-success fs-25">

                                                        {{$active->count()}}

                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="icon1 bg-success-transparent my-auto float-end"> <i
                                                        class="ri-ticket-2-line"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="card">
                                <span>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="mt-0 text-start">
                                                    <span class="fs-16 font-weight-semibold">{{lang('On-Hold Tickets',
                                                        'menu')}}</span>
                                                    <h3 class="mb-0 mt-1 text-secondary fs-25">{{$onhold->count()}}</h3>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="icon1 bg-warning-transparent my-auto  float-end"> <i
                                                        class="ri-coupon-2-line"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="card">
                                <span>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="mt-0 text-start">
                                                    <span class="fs-16 font-weight-semibold">{{lang('Closed Tickets',
                                                        'menu')}}</span>
                                                    <h3 class="mb-0 mt-1 text-secondary fs-25">{{$closed->count()}}</h3>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="icon1 bg-danger-transparent my-auto  float-end"> <i
                                                        class="ri-coupon-2-line"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card mb-0">
                                <div class="card-header border-0 d-flex">
                                    <h4 class="card-title">{{lang('Tickets Summary')}}</h4>
                                    @if(setting('CUSTOMER_TICKET') == 'no')

                                    <div class="float-end ms-auto"><a href="{{route('client.ticket')}}" class="btn btn-secondary ms-auto"><i class="fa fa-paper-plane-o me-2"></i>{{lang('Create Ticket', 'menu')}}</a></div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100"
                                            id="userdashboard">
                                            <thead>
                                                <tr>
                                                <th >{{lang('Sl.No')}}</th>
                                                <th >{{lang('Ticket Details')}}</th>
                                                <th >{{lang('Status')}}</th>
                                                <th >{{lang('Actions')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach ($tickets as $ticket)
                                                <tr {{$ticket->replystatus == 'Waiting' || $ticket->replystatus == 'Botreplied'? 'class=bg-success-transparent': ''}}>
                                                    <td>{{$i++}}</td>
                                                    <td class="overflow-hidden ticket-details">
                                                        <div class="d-flex align-items-center">
                                                            <div class="">
                                                                <a href="{{route('loadmore.load_data',encrypt($ticket->ticket_id))}}" class="fs-14 d-block font-weight-semibold">{{$ticket->subject}}</a>

                                                                <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                                                    <li class="pe-2 text-muted">#{{$ticket->ticket_id}}</span>
                                                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i> {{$ticket->created_at->timezone(Auth::user()->timezone)->format(setting('date_format'))}}</li>

                                                                    @if($ticket->priority != null)
                                                                        @if($ticket->priority == "Low")
                                                                            <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($ticket->priority)}}</li>

                                                                        @elseif($ticket->priority == "High")
                                                                            <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($ticket->priority)}}</li>

                                                                        @elseif($ticket->priority == "Critical")
                                                                            <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($ticket->priority)}}</li>

                                                                        @else
                                                                            <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($ticket->priority)}}</li>
                                                                        @endif
                                                                    @else
                                                                        ~
                                                                    @endif

                                                                    @if($ticket->category_id != null)
                                                                        @if($ticket->category != null)

                                                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Category')}}"><i class="fe fe-grid me-1 fs-14" ></i>{{Str::limit($ticket->category->name, '40')}}</li>

                                                                        @else

                                                                        ~
                                                                        @endif
                                                                    @else

                                                                        ~
                                                                    @endif
                                                                    @if($ticket->last_reply == null)
                                                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($ticket->created_at)->diffForHumans()}}</li>

                                                                    @else
                                                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($ticket->last_reply)->diffForHumans()}}</li>

                                                                    @endif

                                                                    @if($ticket->purchasecodesupport != null)
                                                                        @if($ticket->purchasecodesupport == 'Supported')

                                                                        <li class="px-2 text-success font-weight-semibold">{{lang('Support Active')}}</li>
                                                                        @endif
                                                                        @if($ticket->purchasecodesupport == 'Expired')

                                                                        <li class="px-2 text-danger-dark font-weight-semibold">{{lang('Support Expired')}}</li>
                                                                        @endif

                                                                    @endif

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($ticket->status == "New")

                                                        <span class="badge badge-burnt-orange">{{lang($ticket->status)}}</span>

                                                        @elseif($ticket->status == "Re-Open")

                                                        <span class="badge badge-teal">{{lang($ticket->status)}}</span>

                                                        @elseif($ticket->status == "Inprogress")

                                                        <span class="badge badge-info">{{lang($ticket->status)}}</span>

                                                        @elseif($ticket->status == "On-Hold")

                                                        <span class="badge badge-warning">{{lang($ticket->status)}}</span>

                                                        @else

                                                        <span class="badge badge-danger">{{lang($ticket->status)}}</span>

                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class = "d-flex">
                                                            <a href="{{route('loadmore.load_data',encrypt($ticket->ticket_id))}}" class="action-btns1" data-bs-toggle="tooltip" data-placement="top" title="{{lang('View Ticket')}}"><i class="feather feather-edit text-primary"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Dashboard List-->

@endsection

@section('scripts')

<!-- INTERNAL Vertical-scroll js-->
<script src="{{asset('build/assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])

<script type="text/javascript">
    $(function() {
        "use strict";

        (function($){

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            let prev = {!! json_encode(lang("Previous")) !!};
            let next = {!! json_encode(lang("Next")) !!};
            let nodata = {!! json_encode(lang("No data available in table")) !!};
            let noentries = {!! json_encode(lang("No entries to show")) !!};
            let showing = {!! json_encode(lang("showing page")) !!};
            let ofval = {!! json_encode(lang("of")) !!};
            let maxRecordfilter = {!! json_encode(lang("- filtered from ")) !!};
            let maxRecords = {!! json_encode(lang("records")) !!};
            let entries = {!! json_encode(lang("entries")) !!};
            let show = {!! json_encode(lang("Show")) !!};
            let search = {!! json_encode(lang("Search...")) !!};
            // Datatable
            $('#userdashboard').dataTable({
                language: {
                    searchPlaceholder: search,
                    scrollX: "100%",
                    sSearch: '',
                    paginate: {
                    previous: prev,
                    next: next
                    },
                    emptyTable : nodata,
                    infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                    info: `${showing} _PAGE_ ${ofval} _PAGES_`,
                    infoEmpty: noentries,
                    lengthMenu: `${show} _MENU_ ${entries} `,
                },
                order:[],
                columnDefs: [
                    { "orderable": false, "targets":[ 0,1,3] }
                ],
            });

            $('.form-select').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $(".vertical-scroll5").bootstrapNews({
                newsPerPage: 1,
                autoplay: true,
                pauseOnHover: true,
                navigation: false,
                direction: 'down',
                newsTickerInterval: 2500,
                onToDo: function () {
                }
            });

            let notifyClose = document.querySelectorAll('.notifyclose');
            notifyClose.forEach(ele => {
                if(ele){
                    let id = ele.getAttribute('data-id');
                    if(getCookie(id)){
                        ele.closest('.alert').classList.add('d-none');
                    }else{
                        ele.addEventListener('click', setCookie);
                    }
                }
            })


            function setCookie($event) {
                const d = new Date();
                let id = $event.currentTarget.getAttribute('data-id');
                d.setTime(d.getTime() + (30 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();
                document.cookie = id + "=" + 'announcement_close' + ";" + expires + ";path=/";
                $event.currentTarget.closest('.alert').classList.add('d-none');
            }

            function getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                }
                return '';
            }


        })(jQuery);
    })
</script>

@endsection
