<table class="table table-bordered border-bottom text-nowrap w-100" id="ticketdatatable">
    <thead >
        <tr >
            <th >{{lang('Sl.No')}}</th>
            @can('Ticket Delete')

            <th width="10" >
                <input type="checkbox"  id="customCheckAll">
                <label  for="customCheckAll"></label>
            </th>
            @endcan
            @cannot('Ticket Delete')

            <th width="10" >
                <input type="checkbox"  id="customCheckAll" disabled>
                <label  for="customCheckAll"></label>
            </th>
            @endcannot

            <th class="ticket-dets">
                {{lang('Ticket Details')}}
            </th>
            <th>{{lang('User')}}</th>
            <th>{{lang('Status')}}</th>
            <th>{{lang('Actions')}}</th>
        </tr>
    </thead>
    <tbody id="refresh">
        @php $i = 1; @endphp
        @foreach ($ticketdata as $tickets)
            <tr {{$tickets->replystatus == 'Replied'? 'class=bg-success-transparent': ''}}  @if($tickets->replystatus == "Botreplied") style="background-color: {{setting('botStripColour')}};" @endif @if ($tickets->ticketviolation == 'on') class="bg-danger-transparent" @endif>
                <td class="wpx-40 text-center">
                    {{$i++}}
                </td>
                <td class="wpx-40 text-center">
                    @if(Auth::user()->can('Ticket Delete'))
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{encrypt($tickets->id)}}" />
                    @else
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{encrypt($tickets->id)}}" disabled />
                    @endif
                </td>
                <td class="overflow-hidden ticket-details">
                    <div class="d-flex align-items-center">
                        <div class="">
                            @if($tickets->ticketnote->isEmpty())
                                @if($tickets->overduestatus != null)

                                <div class="ribbon ribbon-top-right1 text-danger">
                                    <span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
                                </div>

                                @endif
                            @else

                                <div class="ribbon ribbon-top-right text-warning">
                                    <span class="bg-warning text-white">{{lang('Note')}}</span>
                                </div>

                                @if($tickets->overduestatus != null)
                                <div class="ribbon ribbon-top-right1 text-danger">
                                    <span class="bg-danger text-white">{{$tickets->overduestatus}}</span>
                                </div>
                                @endif

                            @endif

                            <a href="{{route('admin.tickettrashedview', encrypt($tickets->id))}}" class="fs-14 d-block font-weight-semibold">{{$tickets->subject}}</a>

                            <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                <li class="pe-2 text-muted">#{{$tickets->ticket_id}}</span>
                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Date')}}"><i class="fe fe-calendar me-1 fs-14"></i> {{$tickets->created_at->timezone(Auth::user()->timezone)->format(setting('date_format'))}}</li>

                                @if($tickets->priority != null)
                                    @if($tickets->priority == "Low")
                                        <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($tickets->priority)}}</li>

                                    @elseif($tickets->priority == "High")
                                        <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($tickets->priority)}}</li>

                                    @elseif($tickets->priority == "Critical")
                                        <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($tickets->priority)}}</li>

                                    @else
                                        <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Priority')}}">{{lang($tickets->priority)}}</li>
                                    @endif
                                @else
                                    ~
                                @endif

                                @if($tickets->category_id != null)
                                    @if($tickets->category != null)

                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Category')}}"><i class="fe fe-grid me-1 fs-14" ></i>{{Str::limit($tickets->category->name, '40')}}</li>

                                    @else

                                    ~
                                    @endif
                                @else

                                    ~
                                @endif

                                @if($tickets->last_reply == null)
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($tickets->created_at)->diffForHumans()}}</li>

                                @else
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Last Replied')}}"><i class="fe fe-clock me-1 fs-14"></i>{{\Carbon\Carbon::parse($tickets->last_reply)->diffForHumans()}}</li>

                                @endif

                                @if($tickets->purchasecodesupport != null)
                                @if($tickets->purchasecodesupport == 'Supported')

                                <li class="px-2 text-success font-weight-semibold">{{lang('Support Active')}}</li>
                                @if($tickets->purchasecodesupport == 'Expired')

                                <li class="px-2 text-danger-dark font-weight-semibold">{{lang('Support Expired')}}</li>
                                @endif
                                @endif
                                @endif

                            </ul>
                        </div>
                    </div>
                </td>
                <td>
                    {{$tickets->cust->username}}  ({{ lang($tickets->cust->userType) }}) @if ($tickets->cust->voilated == 'on')
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                    @endif
                </td>
                <td>
                    @if($tickets->status == "New")

                    <span class="badge badge-burnt-orange">{{lang($tickets->status)}}</span>

                    @elseif($tickets->status == "Re-Open")

                    <span class="badge badge-teal">{{lang($tickets->status)}}</span>

                    @elseif($tickets->status == "Inprogress")

                    <span class="badge badge-info">{{lang($tickets->status)}}</span>

                    @elseif($tickets->status == "On-Hold")

                    <span class="badge badge-warning">{{lang($tickets->status)}}</span>

                    @else

                    <span class="badge badge-danger">{{lang($tickets->status)}}</span>

                    @endif
                </td>
                <td>
                    <div class="d-flex">
                        <a href="{{route('admin.tickettrashedview', encrypt($tickets->id))}}"  class="action-btns1" ><i class="feather feather-eye text-info" data-id="{{encrypt($tickets->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('View')}}"></i></a>
                        <a href="javascript:void(0)" data-id="{{encrypt($tickets->id)}}" class="action-btns1" id="show-delete" ><i class="feather feather-trash-2 text-danger" data-id="{{encrypt($tickets->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Delete')}}"></i></a>
                        <a href="javascript:void(0)" data-id="{{encrypt($tickets->id)}}" class="action-btns1" id="show-restore" ><i class="feather feather-rotate-ccw text-success" data-id="{{encrypt($tickets->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Restore')}}"></i></a>
                    </div>

                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $ticketdata->links('admin.viewticket.pagination') }}
<script type="text/javascript">
    $(function() {

        let prev = {!! json_encode(lang("Previous")) !!};
        let next = {!! json_encode(lang("Next")) !!};
        let nodata = {!! json_encode(lang('No data available in table')) !!};
        let noentries = {!! json_encode(lang('No entries to show')) !!};
        let showing = {!! json_encode(lang('showing page')) !!};
        let ofval = {!! json_encode(lang('of')) !!};
        let maxRecordfilter = {!! json_encode(lang('- filtered from ')) !!};
        let maxRecords = {!! json_encode(lang('records')) !!};
        let entries = {!! json_encode(lang('entries')) !!};
        let show = {!! json_encode(lang('Show')) !!};
        let search = {!! json_encode(lang('Search...')) !!};
        let currentpagenumber = {!! json_encode($ticketdata->currentPage()) !!};
        let lastpagenumber = {!! json_encode($ticketdata->lastPage()) !!};

        $('#ticketdatatable').dataTable({
            language: {
                searchPlaceholder: search,
                scrollX: "100%",
                sSearch: '',
                paginate: {
                    previous: prev,
                    next: next
                },
                emptyTable: nodata,
                infoFiltered: `${maxRecordfilter} _MAX_ ${maxRecords}`,
                info: `${showing} ${currentpagenumber} ${ofval} ${lastpagenumber}`,
                infoEmpty: noentries,
                lengthMenu: `${show} _MENU_ ${entries} `,
            },
            order: [],
            columnDefs: [{
                "orderable": false,
                "targets": [0, 1, 4]
            }],
        });

        $('.form-select').select2({
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        let end = @json($perPage);
        $('.form-select').val(end).trigger('change');

        $('.form-select').on('select2:select', function(e) {
            var selectedData = e.params.data;

            $.ajax({
                url: location.origin + location.pathname + `?page=1&per_page=${selectedData.text}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });

        $('.paginationDatafetch').on('click', function() {
            var selectedpage = $(this).data('id');

            $.ajax({
                url: location.origin + location.pathname + `?page=${selectedpage}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('.fetchedtabledata').html(data.rendereddata);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });


        let paginationexists = @json($ticketdata->hasPages());
        if (paginationexists) {
            document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'none';
        } else {
            document.querySelector('.dataTables_wrapper .dataTables_paginate').style.display = 'block';
        }
    })
</script>
