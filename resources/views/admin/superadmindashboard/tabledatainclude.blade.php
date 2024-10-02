<style>
    .power-ribbone {
        width: 2.5rem;
        height: 2.5rem;
        overflow: hidden;
        position: absolute;
        z-index: 8;
    }
    .power-ribbone span {
        position: absolute;
        display: block;
        width: 5.125rem;
        padding: 0.5rem 0 0.25rem 0;
        color: #fff;
        font: 500 1rem/1 Lato, sans-serif;
        text-shadow: 0 0.0625rem 0.0625rem rgba(0,0,0,0.2);
        text-transform: capitalize;
        text-align: center;
    }

    .power-ribbone-top-left {
        inset-block-start: -0.375rem;
        inset-inline-start: -0.5625rem;
    }
    .power-ribbone-top-left span {
        inset-inline-end: -11px;
        inset-block-start: -14px;
        transform: rotate(-45deg);
    }
    .power-ribbone-top-left span i {
        transform: rotate(45deg);
        padding-block-start: 13px;
        font-size: 10px;
        padding-inline-start: 10px;
    }
</style>
<table class="table table-bordered border-bottom text-nowrap ticketdeleterow supportticket-list w-100"
    id="ticketdatatable">
    <thead>
        <tr>
            <th class="wpx-40 text-center">{{ lang('Sl.No') }}</th>
            @can('Ticket Delete')
                <th class="wpx-40 text-center">
                    <input type="checkbox" id="customCheckAll">
                    <label for="customCheckAll"></label>
                </th>
            @endcan
            @cannot('Ticket Delete')
                <th class="wpx-40 text-center">
                    <input type="checkbox" id="customCheckAll" disabled>
                    <label for="customCheckAll"></label>
                </th>
            @endcannot

            <th>{{ lang('Ticket Details') }}</th>
            <th>{{ lang('User') }}</th>
            <th>{{ lang('Status') }}</th>
            <th>{{ lang('Assign To') }}</th>
            <th>{{ lang('Actions') }}</th>

        </tr>
    </thead>
    <tbody id="refresh">
        @php $i = 1 @endphp
        @foreach ($ticketdata as $tickets)

            <tr {{ $tickets->replystatus == 'Replied' ? 'class=bg-success-transparent' : '' }}
                {{-- @if ($tickets->replystatus == 'Bot-Replied')  class="bg-warning-transparent" @endif --}}
                @if ($tickets->ticketviolation == 'on') class="bg-danger-transparent" @endif>
                <td class="wpx-40 text-center">
                    {{ $i++ }}
                </td>
                <td class="wpx-40 text-center">
                    @if (Auth::user()->can('Ticket Delete'))
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{ encrypt($tickets->id) }}" />
                    @else
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="{{ encrypt($tickets->id) }}"
                            disabled />
                    @endif
                </td>
                <td class="overflow-hidden ticket-details">
                    <div class="d-flex align-items-center">
                        <div class="ribbone-card">
                            @if ($tickets->importantticket == 'on' && $tickets->status != 'Closed')
                                <div class="power-ribbone power-ribbone-top-left">
                                    <span class="bg-warning"><i class="fa fa-star"></i></span>
                                </div>
                            @endif

                            @if ($tickets->ticketnote->isEmpty())
                                @if ($tickets->overduestatus != null)
                                    <div class="ribbon ribbon-top-right1 text-danger">
                                        <span class="bg-danger text-white">{{ $tickets->overduestatus }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="ribbon ribbon-top-right text-warning">
                                    <span class="bg-warning text-white">{{ lang('Note') }}</span>
                                </div>

                                @if ($tickets->overduestatus != null)
                                    <div class="ribbon ribbon-top-right1 text-danger">
                                        <span class="bg-danger text-white">{{ $tickets->overduestatus }}</span>
                                    </div>
                                @endif
                            @endif

                            <a href="{{ url('admin/ticket-view/' . encrypt($tickets->ticket_id)) }}"
                                class="fs-14 d-block font-weight-semibold">{{ $tickets->subject }}</a>

                            <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                <li class="pe-2 text-muted">#{{ $tickets->ticket_id }}</span>
                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ lang('Date') }}"><i class="fe fe-calendar me-1 fs-14"></i>
                                    {{ \Carbon\Carbon::parse($tickets->created_at)->timezone(timeZoneData())->format(setting('date_format')) }}
                                </li>

                                @if ($tickets->priority != null)
                                    @if ($tickets->priority == 'Low')
                                        <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ lang('Priority') }}">
                                            {{ lang($tickets->priority) }}</li>
                                    @elseif($tickets->priority == 'High')
                                        <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ lang('Priority') }}">
                                            {{ lang($tickets->priority) }}</li>
                                    @elseif($tickets->priority == 'Critical')
                                        <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ lang('Priority') }}">
                                            {{ lang($tickets->priority) }}</li>
                                    @else
                                        <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ lang('Priority') }}">
                                            {{ lang($tickets->priority) }}</li>
                                    @endif
                                @else
                                    ~
                                @endif

                                @if ($tickets->category_id != null)
                                    @if ($tickets->category != null)
                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ lang('Category') }}"><i
                                                class="fe fe-grid me-1 fs-14"></i>{{ Str::limit($tickets->category->name, '40') }}
                                        </li>
                                    @else
                                        ~
                                    @endif
                                @else
                                    ~
                                @endif

                                @if ($tickets->last_reply == null)
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ lang('Last Replied') }}"><i
                                            class="fe fe-clock me-1 fs-14"></i>{{ \Carbon\Carbon::parse($tickets->created_at)->diffForHumans() }}
                                    </li>
                                @else
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ lang('Last Replied') }}"><i
                                            class="fe fe-clock me-1 fs-14"></i>{{ \Carbon\Carbon::parse($tickets->last_reply)->diffForHumans() }}
                                    </li>
                                @endif

                                @if ($tickets->purchasecodesupport != null)
                                    @if ($tickets->purchasecodesupport == 'Supported')
                                        <li class="px-2 text-success font-weight-semibold">{{ lang('Support Active') }}
                                        </li>
                                    @endif
                                    @if ($tickets->purchasecodesupport == 'Expired')
                                        <li class="px-2 text-danger-dark font-weight-semibold">
                                            {{ lang('Support Expired') }}</li>
                                    @endif
                                @endif

                            </ul>
                        </div>
                    </div>
                </td>
                <td>
                    {{-- {{ $tickets->cust->username }} @if ($tickets->ticketviolation == 'on')
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                    @endif --}}
                    {{ $tickets->cust->username }} ({{ lang($tickets->cust->userType) }}) @if ($tickets->cust->voilated == 'on')
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                    @endif
                </td>
                <td>
                    @if ($tickets->status == 'New')
                        <span class="badge badge-burnt-orange">{{ lang($tickets->status) }}</span>
                    @elseif($tickets->status == 'Re-Open')
                        <span class="badge badge-teal">{{ lang($tickets->status) }}</span>
                    @elseif($tickets->status == 'Inprogress')
                        <span class="badge badge-info">{{ lang($tickets->status) }}</span>
                    @elseif($tickets->status == 'On-Hold')
                        <span class="badge badge-warning">{{ lang($tickets->status) }}</span>
                    @else
                        <span class="badge badge-danger">{{ lang($tickets->status) }}</span>
                    @endif
                </td>
                <td>
                    @if (Auth::user()->can('Ticket Assign'))
                        @if ($tickets->status == 'Suspend' || $tickets->status == 'Closed')
                            <div class="btn-group">
                                @if ($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)
                                    @if ($tickets->ticketassignmutliples->count() == 1)
                                        @php
                                            $assigneddata = $tickets->ticketassignmutliples->first();
                                            $assigneduserdata = \App\Models\User::find($assigneddata->toassignuser_id);
                                        @endphp
                                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                            disabled> {{ $assigneduserdata->name }} ({{ lang('Other Assign') }}) <span class="caret"></span></button>
                                    @else
                                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                            disabled>{{ lang('Multi Assign') }} <span class="caret"></span></button>
                                    @endif
                                    <button data-id="{{ encrypt($tickets->id) }}" class="btn btn-outline-primary"
                                        id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="" data-bs-original-title="{{ lang('Unassign') }}"
                                        aria-label="Unassign"><i class="fe fe-x"></i></button>

                                @elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)
                                    <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        disabled>{{ $tickets->selfassign->name }} (self) <span
                                            class="caret"></span></button>
                                    <button data-id="{{ encrypt($tickets->id) }}" class="btn btn-outline-primary"
                                        id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="" data-bs-original-title="{{ lang('Unassign') }}"
                                        aria-label="Unassign"><i class="fe fe-x"></i></button>
                                @else
                                    <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        disabled>{{ lang('Assign') }}<span class="caret"></span></button>
                                @endif

                            </div>
                        @else
                            @if ($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id == null)
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown">{{ lang('Assign') }} <span
                                            class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-plus-title">{{ lang('Assign') }} <b aria-hidden="true"
                                                class="fa fa-angle-up"></b></li>
                                        <li>
                                            <a href="javascript:void(0);" id="selfassigid"
                                                data-id="{{ encrypt($tickets->id) }}">{{ lang('Self Assign') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-id="{{ encrypt($tickets->id) }}"
                                                id="assigned">
                                                {{ lang('Other Assign') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="btn-group">
                                    @if ($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null)
                                        @if ($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown">{{ lang('Assign') }} <span
                                                    class="caret"></span></button>
                                        @else
                                            @php
                                                $assigneddata = $tickets->ticketassignmutliples->first();
                                                $assigneduserdata = \App\Models\User::find($assigneddata->toassignuser_id);
                                            @endphp
                                            @if ($tickets->ticketassignmutliples->count() == 1)
                                                <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown"> {{ $assigneduserdata->name }} ({{ lang('Other Assign') }}) <span
                                                        class="caret"></span></button>
                                            @else
                                                <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown">{{ lang('Multi Assign') }} <span
                                                        class="caret"></span></button>
                                            @endif

                                            <a href="javascript:void(0)" data-id="{{ encrypt($tickets->id) }}"
                                                class="btn btn-outline-primary btn-sm" id="btnremove"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                data-bs-original-title="{{ lang('Unassign') }}"
                                                aria-label="Unassign"><i class="fe fe-x"></i></a>
                                        @endif
                                    @elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null)
                                        @if ($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null)
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown">{{ lang('Assign') }} <span
                                                    class="caret"></span></button>
                                        @else
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown">{{ $tickets->selfassign->name }} (self)
                                                <span class="caret"></span></button>
                                            <a href="javascript:void(0)" data-id="{{ encrypt($tickets->id) }}"
                                                class="btn btn-outline-primary btn-sm" id="btnremove"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                data-bs-original-title="{{ lang('Unassign') }}"
                                                aria-label="Unassign"><i class="fe fe-x"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                            data-bs-toggle="dropdown">{{ lang('Assign') }} <span
                                                class="caret"></span></button>
                                    @endif

                                    <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-plus-title">{{ lang('Assign') }} <b aria-hidden="true"
                                                class="fa fa-angle-up"></b></li>
                                        <li>
                                            <a href="javascript:void(0);" id="selfassigid"
                                                data-id="{{ encrypt($tickets->id) }}">{{ lang('Self Assign') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-id="{{ encrypt($tickets->id) }}"
                                                id="assigned">
                                                {{ lang('Other Assign') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        @endif
                    @else
                        ~
                    @endif
                </td>
                <td>
                    @if (Auth::user()->can('Ticket Edit'))
                        <a href="{{ url('admin/ticket-view/' . encrypt($tickets->ticket_id)) }}"
                            class="btn btn-sm action-btns edit-testimonial"><i
                                class="feather feather-eye text-primary" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ lang('View') }}"></i></a>
                    @else
                        ~
                    @endif
                    @if (Auth::user()->can('Ticket Delete'))
                        <a href="javascript:void(0)" data-id="{{ encrypt($tickets->id) }}" class="btn btn-sm action-btns"
                            id="show-delete"><i class="feather feather-trash-2 text-danger"
                                data-id="{{ encrypt($tickets->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ lang('Delete') }}"></i></a>
                    @else
                        ~
                    @endif
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
                    console.log(data);
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

        function initializeTooltips() {
            var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipElements.forEach(function (element) {
                new bootstrap.Tooltip(element);
            });
        }
        initializeTooltips();
    })
</script>
