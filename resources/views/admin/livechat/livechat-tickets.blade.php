@extends('layouts.adminmaster')
@section('styles')

    <!-- INTERNAL Data table css -->
    <link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Livechat customer tickets', 'menu')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card mb-0">
            <div class="card-header d-sm-max-flex border-0">
                <h4 class="card-title">{{lang('Livechat customer tickets')}}</h4>
            </div>
            <div class="card-body">
                {{-- <div class="table-responsive spruko-delete">
                    <button id="massdelete" class="btn btn-outline-light btn-sm mb-4 d-none data-table-btn"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>

                    <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="liveChatNotiSoundTable">
                        <thead>
                            <tr>
                                <th  width="9">{{lang('Sl.No')}}</th>
                                <th width="10" >
                                    <input type="checkbox"  id="customCheckAll">
                                    <label  for="customCheckAll"></label>
                                </th>
                                <th width="10" >
                                    {{lang('Ticket Details')}}
                                </th>
                                <th class="w-5">{{lang('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{$i++}}</td>
                                <td><input type="checkbox" name="article_checkbox[]" class="checkall" value="{{$ticket->ticket_id}}" /></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <a href="{{url('')}}/admin/ticket-view/{{$ticket->ticket_id}}" class="fs-14 d-block font-weight-semibold">{{$ticket->subject}}</a>
                                            <ul class="fs-13 font-weight-normal d-flex custom-ul">
                                                <li class="pe-2 text-muted">
                                                    #{{$ticket->ticket_id}}
                                                </li>
                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Date">
                                                    <i class="fe fe-calendar me-1 fs-14"></i>
                                                    {{\Carbon\Carbon::parse($ticket->created_at)->format('Y-m-d')}}
                                                </li>
                                                @if($ticket->priority)
                                                <li class="ps-5 pe-2 preference preference-medium" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Priority">{{$ticket->priority}}</li>
                                                @endif
                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Category"><i class="fe fe-grid me-1 fs-14"></i>LiveChat</li>
                                                <li class="px-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Last Replied"><i class="fe fe-clock me-1 fs-14"></i>{{$ticket->created_at->diffForHumans()}}</li>
                                            </ul>
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{url('')}}/admin/ticket-view/{{$ticket->ticket_id}}" class="action-btns1 " data-id="{{$ticket->subject}}">
                                            <i class="feather feather-eye text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Edit')}}"></i>
                                        </a>

                                        <a href="javascript:void(0)" class="action-btns1" data-id="{{$ticket->ticket_id}}"
                                            id="show-delete" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{lang('Delete')}}">
                                            <i class="feather feather-trash-2 text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}
                <div class="spruko-delete">
                    @can('Ticket Delete')

                    <button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn" style="display: none;"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
                    @endcan
                    <div class="table-responsive fetchedtabledata custom-data-responsive">
                        @include('admin.superadmindashboard.tabledatainclude')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <!-- INTERNAL Data tables -->
    <script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>


    @vite(['resources/assets/js/select2.js'])

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>

    <script>
        $(function() {
            "use strict";

            (function($)  {
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
                $('#liveChatNotiSoundTable').dataTable({
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

                // Variables
                var SITEURL = '{{url('')}}';

                // Csrf Field
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Checkbox check all
                $('#customCheckAll').on('click', function() {
                    $('.checkall').prop('checked', this.checked);

                    if($('.checkall:checked').length){
                        document.querySelector("#massdelete").classList.remove("d-none")
                    }else{
                        document.querySelector("#massdelete").classList.add("d-none")
                    }
                });

                // when user click its get modal popup to assigned the ticket
                $('body').on('click', '#assigned', function () {
                    var assigned_id = $(this).data('id');
                    $('.select2_modalassign').select2({
                        dropdownParent: ".sprukosearch",
                        minimumResultsForSearch: '',
                        placeholder: "Search",
                        width: '100%'
                    });

                    var APP_URL = {!! json_encode(url('/')) !!}
                    $.get(APP_URL+'/admin/assigned/' + assigned_id , function (data) {
                        $('#assigned_id').val(data.assign_data.id);
                        $(".modal-title").text('Assign To Agent');
                        $('#username').html(data.table_data);
                        if(data.assign_user_exist == 'no'){
                            $('#username').val([]).trigger('change')
                        }
                        $('#addassigned').modal('show');
                    });
                });

                // TICKET DELETE SCRIPT
                $('body').on('click', '#show-delete', function () {
                    var _id = $(this).data("id");
                    swal({
                        title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                        text: "{{lang('This might erase your records permanently', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                                type: "get",
                                url: SITEURL + "/admin/delete-ticket/"+_id,
                                success: function (data) {
                                    toastr.success(data.success);
                                    var oTable = $('#myticket').dataTable();
                                    oTable.fnDraw(false);
                                    location.reload();
                                },
                                error: function (data) {
                                console.log('Error:', data);
                                }
                            });
                        }
                    });
                });
                // TICKET DELETE SCRIPT END

                //Mass Delete
                $('body').on('click', '#massdelete', function () {

                    var id = [];
                    $('.checkall:checked').each(function(){
                        id.push($(this).val());
                    });
                    if(id.length > 0){
                        swal({
                            title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                            text: "{{lang('This might erase your records permanently', 'alerts')}}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url:"{{ url('admin/ticket/delete/tickets')}}",
                                    method:"POST",
                                    data:{id:id},
                                    success:function(data)
                                    {

                                        toastr.success(data.success);
                                        location.reload();

                                    },
                                    error:function(data){

                                    }
                                });
                            }
                        });
                    }
                    else{
                        toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
                    }

                });

                // Assigned Submit button
                $('body').on('submit', '#assigned_form', function (e) {
                    e.preventDefault();
                    var actionType = $('#btnsave').val();
                    var fewSeconds = 2;
                    $('#btnsave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                    $('#btnsave').prop('disabled', true);
                        setTimeout(function(){
                            $('#btnsave').prop('disabled', false);
                        }, fewSeconds*1000);
                    var formData = new FormData(this);
                    $.ajax({
                        type:'POST',
                        url: SITEURL + "/admin/assigned/create",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,

                        success: (data) => {
                            $('#AssignError').html('');
                            $('#assigned_form').trigger("reset");
                            $('#addassigned').modal('hide');
                            $('#btnsave').html('{{lang('Save Changes')}}');
                            var oTable = $('#myticket').dataTable();
                            oTable.fnDraw(false);
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data){
                            $('#AssignError').html('');
                            $('#AssignError').html("The assigned agent field is required");
                            // $('#AssignError').html(data.responseJSON.errors.assigned_user_id);
                            $('#btnsave').html('{{lang('Save Changes')}}');
                        }
                    });
                });

                // Remove the assigned from the ticket
                $('body').on('click', '#btnremove', function () {
                    var asid = $(this).data("id");
                    swal({
                        title: `{{lang('Are you sure you want to unassign this agent?', 'alerts')}}`,
                        text: "{{lang('This agent may no longer exist for this ticket.', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: SITEURL + "/admin/assigned/update/"+asid,
                                success: function (data) {
                                    var oTable = $('#myticket').dataTable();
                                    oTable.fnDraw(false);
                                    toastr.success(data.success);
                                    location.reload();
                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                }
                            });

                        }
                    });
                });

                $(document).ready(function() {

                    $(document).on('click', '#customCheckAll', function() {
                        $('.checkall').prop('checked', this.checked);
                        updateMassDeleteVisibility();
                    });

                    // Handle individual checkboxes
                    $(document).on('click', '.checkall', function() {
                        updateCustomCheckAll();
                        updateMassDeleteVisibility();
                    });

                    // Handle pagination controls
                    $(document).on('click', '.pagination a', function() {
                        // Assuming '.pagination a' is the selector for your pagination controls
                        setTimeout(function() {
                            updateMassDeleteVisibility();
                        }, 100);
                    });

                    // Initialize the "Select All" checkbox to unchecked
                    $('#customCheckAll').prop('checked', false);

                    // Function to update the visibility of the mass delete button
                    function updateMassDeleteVisibility() {
                        if ($('.checkall:checked').length === 0) {
                            $('#massdelete').hide();
                        } else {
                            $('#massdelete').show();
                        }
                    }

                    // Function to update the state of the "Select All" checkbox
                    function updateCustomCheckAll() {
                        var totalCheckboxes = $('.checkall').length;
                        var checkedCheckboxes = $('.checkall:checked').length;

                        if (checkedCheckboxes === totalCheckboxes) {
                            $('#customCheckAll').prop('checked', true);
                        } else {
                            $('#customCheckAll').prop('checked', false);
                        }
                    }
                });

                $('body').on('click','#selfassigid', function(e){

                    e.preventDefault();

                    let id = $(this).data('id');

                    $.ajax({
                        method:'POST',
                        url: '{{route('admin.selfassign')}}',
                        data: {
                            id : id,
                        },
                        success: (data) => {
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data){

                        }
                    });
                })
            })(jQuery);
        })
    </script>

@endsection
