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
            <th class="wpx-40 text-center"><?php echo e(lang('Sl.No')); ?></th>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Ticket Delete')): ?>
                <th class="wpx-40 text-center">
                    <input type="checkbox" id="customCheckAll">
                    <label for="customCheckAll"></label>
                </th>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('Ticket Delete')): ?>
                <th class="wpx-40 text-center">
                    <input type="checkbox" id="customCheckAll" disabled>
                    <label for="customCheckAll"></label>
                </th>
            <?php endif; ?>

            <th><?php echo e(lang('Ticket Details')); ?></th>
            <th><?php echo e(lang('User')); ?></th>
            <th><?php echo e(lang('Status')); ?></th>
            <th><?php echo e(lang('Assign To')); ?></th>
            <th><?php echo e(lang('Actions')); ?></th>

        </tr>
    </thead>
    <tbody id="refresh">
        <?php $i = 1 ?>
        <?php $__currentLoopData = $ticketdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tickets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <tr <?php echo e($tickets->replystatus == 'Replied' ? 'class=bg-success-transparent' : ''); ?>

                
                <?php if($tickets->ticketviolation == 'on'): ?> class="bg-danger-transparent" <?php endif; ?>>
                <td class="wpx-40 text-center">
                    <?php echo e($i++); ?>

                </td>
                <td class="wpx-40 text-center">
                    <?php if(Auth::user()->can('Ticket Delete')): ?>
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="<?php echo e(encrypt($tickets->id)); ?>" />
                    <?php else: ?>
                        <input type="checkbox" name="student_checkbox[]" class="checkall" value="<?php echo e(encrypt($tickets->id)); ?>"
                            disabled />
                    <?php endif; ?>
                </td>
                <td class="overflow-hidden ticket-details">
                    <div class="d-flex align-items-center">
                        <div class="ribbone-card">
                            <?php if($tickets->importantticket == 'on' && $tickets->status != 'Closed'): ?>
                                <div class="power-ribbone power-ribbone-top-left">
                                    <span class="bg-warning"><i class="fa fa-star"></i></span>
                                </div>
                            <?php endif; ?>

                            <?php if($tickets->ticketnote->isEmpty()): ?>
                                <?php if($tickets->overduestatus != null): ?>
                                    <div class="ribbon ribbon-top-right1 text-danger">
                                        <span class="bg-danger text-white"><?php echo e($tickets->overduestatus); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="ribbon ribbon-top-right text-warning">
                                    <span class="bg-warning text-white"><?php echo e(lang('Note')); ?></span>
                                </div>

                                <?php if($tickets->overduestatus != null): ?>
                                    <div class="ribbon ribbon-top-right1 text-danger">
                                        <span class="bg-danger text-white"><?php echo e($tickets->overduestatus); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <a href="<?php echo e(url('admin/ticket-view/' . encrypt($tickets->ticket_id))); ?>"
                                class="fs-14 d-block font-weight-semibold"><?php echo e($tickets->subject); ?></a>

                            <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                <li class="pe-2 text-muted">#<?php echo e($tickets->ticket_id); ?></span>
                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="<?php echo e(lang('Date')); ?>"><i class="fe fe-calendar me-1 fs-14"></i>
                                    <?php echo e(\Carbon\Carbon::parse($tickets->created_at)->timezone(timeZoneData())->format(setting('date_format'))); ?>

                                </li>

                                <?php if($tickets->priority != null): ?>
                                    <?php if($tickets->priority == 'Low'): ?>
                                        <li class="ps-5 pe-2 preference preference-low" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(lang('Priority')); ?>">
                                            <?php echo e(lang($tickets->priority)); ?></li>
                                    <?php elseif($tickets->priority == 'High'): ?>
                                        <li class="ps-5 pe-2 preference preference-high" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(lang('Priority')); ?>">
                                            <?php echo e(lang($tickets->priority)); ?></li>
                                    <?php elseif($tickets->priority == 'Critical'): ?>
                                        <li class="ps-5 pe-2 preference preference-critical" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(lang('Priority')); ?>">
                                            <?php echo e(lang($tickets->priority)); ?></li>
                                    <?php else: ?>
                                        <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(lang('Priority')); ?>">
                                            <?php echo e(lang($tickets->priority)); ?></li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    ~
                                <?php endif; ?>

                                <?php if($tickets->category_id != null): ?>
                                    <?php if($tickets->category != null): ?>
                                        <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="<?php echo e(lang('Category')); ?>"><i
                                                class="fe fe-grid me-1 fs-14"></i><?php echo e(Str::limit($tickets->category->name, '40')); ?>

                                        </li>
                                    <?php else: ?>
                                        ~
                                    <?php endif; ?>
                                <?php else: ?>
                                    ~
                                <?php endif; ?>

                                <?php if($tickets->last_reply == null): ?>
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?php echo e(lang('Last Replied')); ?>"><i
                                            class="fe fe-clock me-1 fs-14"></i><?php echo e(\Carbon\Carbon::parse($tickets->created_at)->diffForHumans()); ?>

                                    </li>
                                <?php else: ?>
                                    <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?php echo e(lang('Last Replied')); ?>"><i
                                            class="fe fe-clock me-1 fs-14"></i><?php echo e(\Carbon\Carbon::parse($tickets->last_reply)->diffForHumans()); ?>

                                    </li>
                                <?php endif; ?>

                                <?php if($tickets->purchasecodesupport != null): ?>
                                    <?php if($tickets->purchasecodesupport == 'Supported'): ?>
                                        <li class="px-2 text-success font-weight-semibold"><?php echo e(lang('Support Active')); ?>

                                        </li>
                                    <?php endif; ?>
                                    <?php if($tickets->purchasecodesupport == 'Expired'): ?>
                                        <li class="px-2 text-danger-dark font-weight-semibold">
                                            <?php echo e(lang('Support Expired')); ?></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </div>
                </td>
                <td>
                    
                    <?php echo e($tickets->cust->username); ?> (<?php echo e(lang($tickets->cust->userType)); ?>) <?php if($tickets->cust->voilated == 'on'): ?>
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($tickets->status == 'New'): ?>
                        <span class="badge badge-burnt-orange"><?php echo e(lang($tickets->status)); ?></span>
                    <?php elseif($tickets->status == 'Re-Open'): ?>
                        <span class="badge badge-teal"><?php echo e(lang($tickets->status)); ?></span>
                    <?php elseif($tickets->status == 'Inprogress'): ?>
                        <span class="badge badge-info"><?php echo e(lang($tickets->status)); ?></span>
                    <?php elseif($tickets->status == 'On-Hold'): ?>
                        <span class="badge badge-warning"><?php echo e(lang($tickets->status)); ?></span>
                    <?php else: ?>
                        <span class="badge badge-danger"><?php echo e(lang($tickets->status)); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(Auth::user()->can('Ticket Assign')): ?>
                        <?php if($tickets->status == 'Suspend' || $tickets->status == 'Closed'): ?>
                            <div class="btn-group">
                                <?php if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null): ?>
                                    <?php if($tickets->ticketassignmutliples->count() == 1): ?>
                                        <?php
                                            $assigneddata = $tickets->ticketassignmutliples->first();
                                            $assigneduserdata = \App\Models\User::find($assigneddata->toassignuser_id);
                                        ?>
                                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                            disabled> <?php echo e($assigneduserdata->name); ?> (<?php echo e(lang('Other Assign')); ?>) <span class="caret"></span></button>
                                    <?php else: ?>
                                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                            disabled><?php echo e(lang('Multi Assign')); ?> <span class="caret"></span></button>
                                    <?php endif; ?>
                                    <button data-id="<?php echo e(encrypt($tickets->id)); ?>" class="btn btn-outline-primary"
                                        id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="" data-bs-original-title="<?php echo e(lang('Unassign')); ?>"
                                        aria-label="Unassign"><i class="fe fe-x"></i></button>

                                <?php elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null): ?>
                                    <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        disabled><?php echo e($tickets->selfassign->name); ?> (self) <span
                                            class="caret"></span></button>
                                    <button data-id="<?php echo e(encrypt($tickets->id)); ?>" class="btn btn-outline-primary"
                                        id="btnremove" disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="" data-bs-original-title="<?php echo e(lang('Unassign')); ?>"
                                        aria-label="Unassign"><i class="fe fe-x"></i></button>
                                <?php else: ?>
                                    <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        disabled><?php echo e(lang('Assign')); ?><span class="caret"></span></button>
                                <?php endif; ?>

                            </div>
                        <?php else: ?>
                            <?php if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id == null): ?>
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown"><?php echo e(lang('Assign')); ?> <span
                                            class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-plus-title"><?php echo e(lang('Assign')); ?> <b aria-hidden="true"
                                                class="fa fa-angle-up"></b></li>
                                        <li>
                                            <a href="javascript:void(0);" id="selfassigid"
                                                data-id="<?php echo e(encrypt($tickets->id)); ?>"><?php echo e(lang('Self Assign')); ?></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-id="<?php echo e(encrypt($tickets->id)); ?>"
                                                id="assigned">
                                                <?php echo e(lang('Other Assign')); ?>

                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="btn-group">
                                    <?php if($tickets->ticketassignmutliples->isNotEmpty() && $tickets->selfassignuser_id == null): ?>
                                        <?php if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null): ?>
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown"><?php echo e(lang('Assign')); ?> <span
                                                    class="caret"></span></button>
                                        <?php else: ?>
                                            <?php
                                                $assigneddata = $tickets->ticketassignmutliples->first();
                                                $assigneduserdata = \App\Models\User::find($assigneddata->toassignuser_id);
                                            ?>
                                            <?php if($tickets->ticketassignmutliples->count() == 1): ?>
                                                <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown"> <?php echo e($assigneduserdata->name); ?> (<?php echo e(lang('Other Assign')); ?>) <span
                                                        class="caret"></span></button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                    data-bs-toggle="dropdown"><?php echo e(lang('Multi Assign')); ?> <span
                                                        class="caret"></span></button>
                                            <?php endif; ?>

                                            <a href="javascript:void(0)" data-id="<?php echo e(encrypt($tickets->id)); ?>"
                                                class="btn btn-outline-primary btn-sm" id="btnremove"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                data-bs-original-title="<?php echo e(lang('Unassign')); ?>"
                                                aria-label="Unassign"><i class="fe fe-x"></i></a>
                                        <?php endif; ?>
                                    <?php elseif($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassignuser_id != null): ?>
                                        <?php if($tickets->ticketassignmutliples->isEmpty() && $tickets->selfassign == null): ?>
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown"><?php echo e(lang('Assign')); ?> <span
                                                    class="caret"></span></button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                                data-bs-toggle="dropdown"><?php echo e($tickets->selfassign->name); ?> (self)
                                                <span class="caret"></span></button>
                                            <a href="javascript:void(0)" data-id="<?php echo e(encrypt($tickets->id)); ?>"
                                                class="btn btn-outline-primary btn-sm" id="btnremove"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                                data-bs-original-title="<?php echo e(lang('Unassign')); ?>"
                                                aria-label="Unassign"><i class="fe fe-x"></i></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-outline-primary dropdown-toggle btn-sm"
                                            data-bs-toggle="dropdown"><?php echo e(lang('Assign')); ?> <span
                                                class="caret"></span></button>
                                    <?php endif; ?>

                                    <ul class="dropdown-menu" role="menu">
                                        <li class="dropdown-plus-title"><?php echo e(lang('Assign')); ?> <b aria-hidden="true"
                                                class="fa fa-angle-up"></b></li>
                                        <li>
                                            <a href="javascript:void(0);" id="selfassigid"
                                                data-id="<?php echo e(encrypt($tickets->id)); ?>"><?php echo e(lang('Self Assign')); ?></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" data-id="<?php echo e(encrypt($tickets->id)); ?>"
                                                id="assigned">
                                                <?php echo e(lang('Other Assign')); ?>

                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        ~
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(Auth::user()->can('Ticket Edit')): ?>
                        <a href="<?php echo e(url('admin/ticket-view/' . encrypt($tickets->ticket_id))); ?>"
                            class="btn btn-sm action-btns edit-testimonial"><i
                                class="feather feather-eye text-primary" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="<?php echo e(lang('View')); ?>"></i></a>
                    <?php else: ?>
                        ~
                    <?php endif; ?>
                    <?php if(Auth::user()->can('Ticket Delete')): ?>
                        <a href="javascript:void(0)" data-id="<?php echo e(encrypt($tickets->id)); ?>" class="btn btn-sm action-btns"
                            id="show-delete"><i class="feather feather-trash-2 text-danger"
                                data-id="<?php echo e(encrypt($tickets->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="<?php echo e(lang('Delete')); ?>"></i></a>
                    <?php else: ?>
                        ~
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php echo e($ticketdata->links('admin.viewticket.pagination')); ?>

<script type="text/javascript">
    $(function() {

        let prev = <?php echo json_encode(lang("Previous")); ?>;
        let next = <?php echo json_encode(lang("Next")); ?>;
        let nodata = <?php echo json_encode(lang('No data available in table')); ?>;
        let noentries = <?php echo json_encode(lang('No entries to show')); ?>;
        let showing = <?php echo json_encode(lang('showing page')); ?>;
        let ofval = <?php echo json_encode(lang('of')); ?>;
        let maxRecordfilter = <?php echo json_encode(lang('- filtered from ')); ?>;
        let maxRecords = <?php echo json_encode(lang('records')); ?>;
        let entries = <?php echo json_encode(lang('entries')); ?>;
        let show = <?php echo json_encode(lang('Show')); ?>;
        let search = <?php echo json_encode(lang('Search...')); ?>;
        let currentpagenumber = <?php echo json_encode($ticketdata->currentPage()); ?>;
        let lastpagenumber = <?php echo json_encode($ticketdata->lastPage()); ?>;

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

        let end = <?php echo json_encode($perPage, 15, 512) ?>;
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


        let paginationexists = <?php echo json_encode($ticketdata->hasPages(), 15, 512) ?>;
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
<?php /**PATH /var/www/html/resources/views/admin/superadmindashboard/tabledatainclude.blade.php ENDPATH**/ ?>