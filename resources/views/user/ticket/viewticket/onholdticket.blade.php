@extends('layouts.usermaster')

@section('styles')

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

<!-- Section -->
<section>
    <div class="bannerimg cover-image" data-bs-image-src="{{asset('build/assets/images/photos/banner1.jpg')}}">
        <div class="header-text mb-0">
            <div class="container ">
                <div class="row text-white">
                    <div class="col">
                        <h1 class="mb-0">{{lang('On-Hold Tickets', 'menu')}}</h1>
                    </div>
                    <div class="col col-auto">
                        <ol class="breadcrumb text-center">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-white-50">{{lang('Home', 'menu')}}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#" class="text-white">{{lang('On-Hold Tickets', 'menu')}}</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section -->

<!--On-Hold Ticket List-->
<section>
    <div class="cover-image sptb">
        <div class="container ">
            <div class="row">
                @include('includes.user.verticalmenu')
                <div class="col-xl-9">
                    <div class="card mb-0">
                        <div class="card-header border-0">
                            <h4 class="card-title">{{lang('On-Hold Tickets', 'menu')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100"
                                    id="onholdtickets">
                                    <thead>
                                        <tr class="">
                                            <th>{{lang('Sl.No')}}</th>
                                            <th>{{lang('Ticket Details')}}</th>
                                            <th>{{lang('Status')}}</th>
                                            <th>{{lang('Actions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($onholdtickets as $onholdticket)
                                        <tr {{$onholdticket->replystatus == 'Waiting'? 'class=bg-success-transparent': ''}}>
                                            <td>{{$i++}}</td>
                                            <td class="overflow-hidden ticket-details">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <a href="{{route('loadmore.load_data',encrypt($onholdticket->ticket_id))}}" class="fs-14 d-block font-weight-semibold">{{$onholdticket->subject}}</a>

                                                        <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                                            <li class="pe-2 text-muted">#{{$onholdticket->ticket_id}}</span>
                                                            <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i> {{\Carbon\Carbon::parse($onholdticket->created_at)->timezone(Auth::user()->timezone)->format(setting('date_format'))}}</li>

                                                            @if($onholdticket->priority != null)
                                                                @if($onholdticket->priority == "Low")
                                                                    <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($onholdticket->priority)}}</li>

                                                                @elseif($onholdticket->priority == "High")
                                                                    <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($onholdticket->priority)}}</li>

                                                                @elseif($onholdticket->priority == "Critical")
                                                                    <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($onholdticket->priority)}}</li>

                                                                @else
                                                                    <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($onholdticket->priority)}}</li>
                                                                @endif
                                                            @else
                                                                ~
                                                            @endif

                                                            @if($onholdticket->category_id != null)
                                                                @if($onholdticket->category != null)

                                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Category')}}"><i class="fe fe-grid me-1 fs-14" ></i>{{Str::limit($onholdticket->category->name, '40')}}</li>

                                                                @else

                                                                ~
                                                                @endif
                                                            @else

                                                                ~
                                                            @endif

                                                            @if($onholdticket->last_reply == null)
                                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($onholdticket->created_at)->diffForHumans()}}</li>

                                                            @else
                                                            <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($onholdticket->last_reply)->diffForHumans()}}</li>

                                                            @endif

                                                            @if($onholdticket->purchasecodesupport != null)
                                                            @if($onholdticket->purchasecodesupport == 'Supported')

                                                            <li class="px-2 text-success font-weight-semibold">{{lang('Support Active')}}</li>
                                                            @if($onholdticket->purchasecodesupport == 'Expired')

                                                            <li class="px-2 text-danger-dark font-weight-semibold">{{lang('Support Expired')}}</li>
                                                            @endif
                                                            @endif
                                                            @endif

                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($onholdticket->status == "New")

                                                <span class="badge badge-burnt-orange">{{lang($onholdticket->status)}}</span>

                                                @elseif($onholdticket->status == "Re-Open")

                                                <span class="badge badge-teal">{{lang($onholdticket->status)}}</span>

                                                @elseif($onholdticket->status == "Inprogress")

                                                <span class="badge badge-info">{{lang($onholdticket->status)}}</span>

                                                @elseif($onholdticket->status == "On-Hold")

                                                <span class="badge badge-warning">{{lang($onholdticket->status)}}</span>

                                                @else

                                                <span class="badge badge-danger">{{lang($onholdticket->status)}}</span>

                                                @endif
                                            </td>
                                            <td>
                                                <div class = "d-flex">
                                                    <a href="{{route('loadmore.load_data',encrypt($onholdticket->ticket_id))}}" class="action-btns1" data-bs-toggle="tooltip" data-placement="top" title="{{lang('View Ticket')}}"><i class="feather feather-edit text-primary"></i></a>
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
</section>
<!--On-Hold Ticket List-->

@endsection

@section('scripts')

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>

<!-- INTERNAL Index js-->
@vite(['resources/assets/js/support/support-sidemenu.js'])
@vite(['resources/assets/js/support/support-admindash.js'])

<script type="text/javascript">
    $(function() {
        "use strict";

        // Variables
        var SITEURL = '{{url('')}}';

        (function($){

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
            $('#onholdtickets').dataTable({
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


        })(jQuery);
    })
</script>

@endsection
