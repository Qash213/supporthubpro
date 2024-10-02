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
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('My Assigned Tickets', 'menu')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->


    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card ">
            <div class="card-header border-0">
                <h4 class="card-title">{{lang('My Assigned Tickets', 'menu')}}</h4>
            </div>
            <div class="card-body" >
                <div class="table-responsive spruko-delete">
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

                // Csrf field
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
                                    var oTable = $('#assignedticket').dataTable();
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
                    }else{
                        toastr.error('{{lang('Please select at least one check box.', 'alerts')}}');
                    }
                });
                //Mass Delete

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
                            var oTable = $('#myassignedticket').dataTable();
                            oTable.fnDraw(false);
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function(data){
                            $('#AssignError').html('');
                            // $('#AssignError').html(data.responseJSON.errors.assigned_user_id);
                            $('#AssignError').html("The assigned agent field is required");
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
                                    var oTable = $('#myassignedticket').dataTable();
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
