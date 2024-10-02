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
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Total Active Tickets', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->


<!--- Ticket List Blocks --->
<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card">
                <a href="{{route('admin.activeticket.allactiveinprogresstickets')}}" class="admintickets"></a>
                <div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
                    <div>
                        <p class="fs-13 mb-1 font-weight-semibold">{{lang('In-Progress')}}</p>
                        <h4 class="mb-0">{{$allactiveinprogresstickets}}
                            <span class="text-muted fs-11">
                                <span class="text-danger fs-11 mt-2 me-1">
                                    <i class="feather feather-arrow-up-right me-1 bg-danger-transparent p-1 brround"></i>
                                    {{$allactiveoverduetickets}}
                                </span>
                                {{lang('Overdue')}}
                            </span>
                        </h4>
                    </div>
                    <div class="ticket-state-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-secondary" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path opacity="0.2" d="M15.87793,9.53613l0.65771-0.65723C17.47644,7.94305,18.00372,6.66974,18,5.34277V2c0.00012-0.55212-0.44733-0.99988-0.99945-1C17.00037,1,17.00018,1,17,1H7C6.44788,0.99988,6.00012,1.44733,6,1.99945C6,1.99963,6,1.99982,6,2v3.34277C5.9964,6.6701,6.52386,7.94373,7.46484,8.87988l0.65674,0.65527C8.68597,10.09686,9.00232,10.86096,9,11.65723v1.00977c-0.00146,0.64874-0.21167,1.27979-0.59961,1.7998l-1.40088,1.86719C6.35272,17.20032,6.00226,18.25189,6,19.33301V22c-0.00012,0.55212,0.44733,0.99988,0.99945,1C6.99963,23,6.99982,23,7,23h2c-0.55212,0.00012-0.99988-0.44733-1-0.99945C8,22.00037,8,22.00018,8,22v-2.66699c-0.00024-0.56793,0.0965-1.13171,0.28613-1.66699C8.42761,17.26672,8.80536,16.99988,9.229,17H14.771c0.42365-0.00012,0.80139,0.26672,0.94287,0.66602C15.9035,18.20129,16.00024,18.76508,16,19.33301V22c0.00012,0.55212-0.44733,0.99988-0.99945,1H17c0.55212,0.00012,0.99988-0.44733,1-0.99945c0-0.00018,0-0.00037,0-0.00055v-2.66699c-0.0022-1.08118-0.35284-2.13287-1-2.99902l-1.3999-1.86719c-0.38824-0.51984-0.59869-1.15094-0.6001-1.7998v-1.00977C14.99756,10.86133,15.31372,10.09753,15.87793,9.53613z M14.53564,8.87891l-0.65771,0.65723C13.3158,10.09784,13,10.85986,13,11.65454V12h-2v-0.34277c0-0.79742-0.31726-1.56213-0.88177-2.12543L9.46484,8.87988C8.69171,8.10162,8.19904,7.08862,8.06415,6h7.8717C15.80115,7.08832,15.30859,8.10101,14.53564,8.87891z"/><path d="M13,12v-0.34547c0-0.79464,0.31582-1.55671,0.87793-2.1184l0.65771-0.65722C15.3086,8.10102,15.80114,7.08832,15.93585,6h-7.8717c0.13488,1.08865,0.62757,2.10165,1.40069,2.87988l0.6534,0.65194C10.68275,10.09508,11,10.85977,11,11.65723V12H13z"/><path opacity="0.2" d="M19,3H5C4.44772,3,4,2.55228,4,2s0.44772-1,1-1h14c0.55228,0,1,0.44772,1,1S19.55228,3,19,3z"/><path d="M15,23H9c-0.55214,0.00014-0.99986-0.44734-1-0.99948C8,22.00035,8,22.00017,8,22v-2.667c-0.00026-0.56791,0.09651-1.13169,0.28613-1.667C8.42762,17.2667,8.80537,16.99987,9.229,17h5.542c0.42363-0.00013,0.80138,0.2667,0.94287,0.666c0.18962,0.53531,0.28639,1.09909,0.28613,1.667V22c0.00014,0.55214-0.44734,0.99986-0.99948,1C15.00035,23,15.00017,23,15,23z"/><path opacity="0.2" d="M19,23H5c-0.55228,0-1-0.44772-1-1s0.44772-1,1-1h14c0.55228,0,1,0.44772,1,1S19.55228,23,19,23z"/></svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card">
                <a href="{{route('admin.activeticket.allactivereopentickets')}}" class="admintickets"></a>
                <div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
                    <div>
                        <p class="fs-13 mb-1 font-weight-semibold">{{lang('Re-Open')}}</p>
                        <h4 class="mb-0">{{$allactivereopentickets}}</h4>
                    </div>
                    <div class="ticket-state-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-danger" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" opacity="0.2"/><path d="M10 17a.99943.99943 0 0 1-1-1V8a1 1 0 0 1 2 0v8A.99943.99943 0 0 1 10 17zM14 17a.99943.99943 0 0 1-1-1V8a1 1 0 0 1 2 0v8A.99943.99943 0 0 1 14 17z"/></svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card">
                <a href="{{route('admin.activeticket.allactiveonholdtickets')}}" class="admintickets"></a>
                <div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
                    <div>
                        <p class="fs-13 mb-1 font-weight-semibold">{{lang('On-Hold')}}</p>
                        <h4 class="mb-0">{{$allactiveonholdtickets}}</h4>
                    </div>
                    <div class="ticket-state-svg">
                        <svg xmlns="http://www.w3.org/2000/svg"  class="svg-info" viewBox="0 0 24 24"><path d="M8.625,8.5h-4.5a.99943.99943,0,0,1-1-1V3a1,1,0,0,1,2,0V6.5h3.5a1,1,0,0,1,0,2Z"/><path d="M21 13a.99943.99943 0 0 1-1-1A7.995 7.995 0 0 0 5.0791 8.001.99981.99981 0 0 1 3.34863 6.999 9.99473 9.99473 0 0 1 22 12 .99943.99943 0 0 1 21 13zM19.875 22a.99943.99943 0 0 1-1-1V17.5h-3.5a1 1 0 0 1 0-2h4.5a.99943.99943 0 0 1 1 1V21A.99943.99943 0 0 1 19.875 22z"/><path opacity="0.2" d="M12,22A10.01177,10.01177,0,0,1,2,12a1,1,0,0,1,2,0,7.995,7.995,0,0,0,14.9209,3.999.99981.99981,0,0,1,1.73047,1.002A10.03228,10.03228,0,0,1,12,22Z"/></svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card">
                <a href="{{route('admin.activeticket.allactiveassignedtickets')}}" class="admintickets"></a>
                <div class="card-body d-flex align-items-center justify-content-between  p-4 px-5">
                    <div>
                        <p class="fs-13 mb-1 font-weight-semibold">{{lang('Assigned')}}</p>
                        <h4 class="mb-0">{{$allactiveassignedtickets}}</h4>
                    </div>
                    <div class="ticket-state-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-orange" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" opacity="0.2"/><circle cx="12" cy="16" r="1"/><path d="M12,13a1,1,0,0,1-1-1V8a1,1,0,0,1,2,0v4A1,1,0,0,1,12,13Z"/></svg>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--- End Ticket List Blocks --->


<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card">
        <div class="card-header border-0">
            <h4 class="card-title">{{lang('Total Active Tickets', 'menu')}}</h4>
        </div>
        <div class="card-body overflow-scroll">
            <div class="spruko-delete">
                @can('Ticket Delete')

                <button id="massdelete" class="btn btn-outline-light btn-sm mb-4 data-table-btn" style="display: none;"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
                @endcan
                <div class="fetchedtabledata">
                    @include('admin.superadmindashboard.tabledatainclude')
                </div>
            </div>
        </div>
    </div>
</div>

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
@vite(['resources/assets/js/support/support-admindash.js'])
@vite(['resources/assets/js/select2.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>


<script type="text/javascript">
    $(function() {
        "use strict";

        (function($)  {

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.form-select').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
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
                                var oTable = $('#activeticket').dataTable();
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


            // when user click its get modal popup to assigned the ticket
            $('body').on('click', '#assigned', function () {
                var assigned_id = $(this).data('id');

                $('.select2_modalassign').select2({
                    dropdownParent: ".sprukosearch",
                    minimumResultsForSearch: '',
                    placeholder: "Search",
                    width: '100%'
                });

                $.get('assigned/' + assigned_id , function (data) {
                    $('#AssignError').html('');
                    $('#assigned_id').val(data.assign_data.id);
                    $(".modal-title").text('{{lang('Assign To Agent')}}');
                    $('#username').html(data.table_data);
                    if(data.assign_user_exist == 'no'){
                        $('#username').val([]).trigger('change')
                    }
                    $('#addassigned').modal('show');
                });
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
                        var oTable = $('#activeticket').dataTable();
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
                                var oTable = $('#activeticket').dataTable();
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
@section('modal')

@include('admin.modalpopup.assignmodal')

@endsection
