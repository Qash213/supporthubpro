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
		<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Trashed Tickets', 'menu')}}</span></h4>
	</div>
</div>
<!--End Page header-->


<div class="col-xl-12 col-lg-12 col-md-12">
	<div class="card ">
		<div class="card-header border-0">
			<h4 class="card-title">{{lang('Trashed Tickets', 'menu')}}</h4>
		</div>
		<div class="card-body" >
			<div class="table-responsive spruko-delete">
				<div class="data-table-btn">
					@can('Ticket Delete')

					<button id="massdelete" class="btn btn-outline-light btn-sm mb-4 ticketdeleterow" style="display: none;"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
					@endcan
					<button id="massrestore" class="btn btn-outline-light btn-sm mb-4 ticketdeleterow"><i class="feather feather-rotate-ccw"></i> {{lang('Restore')}}</button>
				</div>
                <div class="fetchedtabledata">
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

            // TICKET RESTORE SCRIPT
            $('body').on('click', '#show-restore', function () {
                var _id = $(this).data("id");
                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might restore your record', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: SITEURL + "/admin/tickettrashedrestore/"+_id,
                            success: function (data) {
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
            // TICKET RESTORE SCRIPT END

            // TICKET DELETE SCRIPT
            $('body').on('click', '#show-delete', function () {
                var _id = $(this).data("id");
                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might delete your records permanently', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "post",
                            url: SITEURL + "/admin/tickettrasheddestroy/"+_id,
                            success: function (data) {
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
                                url:"{{ url('admin/trashedticket/delete')}}",
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


            //Mass retore
            $('body').on('click', '#massrestore', function () {
                var id = [];
                $('.checkall:checked').each(function(){
                    id.push($(this).val());
                });
                if(id.length > 0){
                    swal({
                        title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                        text: "{{lang('This might restore your record', 'alerts')}}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url:"{{ url('admin/trashedticket/restore')}}",
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

        })(jQuery);
    })
</script>

@endsection
