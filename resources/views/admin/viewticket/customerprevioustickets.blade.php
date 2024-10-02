@extends('layouts.adminmaster')

@section('styles')

<!-- INTERNAL Data table css -->
<link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

<!-- INTERNAL Sweet-Alert css -->
<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
<style>
    .custom-data-responsive nav {
        position: static !important;
        float: right !important;
        margin-block-end: 5px;
        margin-top: -28px !important;
    }
</style>
@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block px-3">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Previous Tickets', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->
<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3">
        <div class="card user-pro-list overflow-hidden">
            <div class="card-body">
                <div class="user-pic text-center">
                    @if ($users->image == null)

                    <img src="{{asset('uploads/profile/user-profile.png')}}" class="avatar avatar-xxl brround" alt="">

                    @else
                    <img src="{{ route('getprofile.url', ['imagePath' =>$users->image,'storage_disk'=>$users->storage_disk ?? 'public']) }}" class="avatar avatar-xxl brround" alt="">
                    @endif
                    <div class="pro-user mt-3">
                        <h5 class="pro-user-username text-dark mb-1 fs-16">{{$users->username}}</h5>
                        <h6 class="pro-user-desc text-muted fs-12">{{$users->email}}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title"> {{lang('Personal Details')}}</h4>
            </div>
            <div class="card-body px-0 pb-0">

                <div class="table-responsive tr-lastchild">
                    <table class="table mb-0 table-information">
                        <tbody>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Name')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->username}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Email')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->email}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Customer Type')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->userType}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Login Type')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->logintype}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Status')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->status}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Phone Number')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->phone}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Login IP')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->last_login_ip}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Customer Country')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->country}}</td>
                            </tr>
                            <tr>
                                <td class="py-2">
                                    <span class="font-weight-semibold w-50"> {{lang('Customer Timezone')}} </span>
                                </td>
                                <td class="py-2 ps-4">{{$users->timezone}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xl-9 px-3">
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
                                        <h3 class="mb-0 mt-1 text-primary fs-25">{{$total->count()}}</h3>
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
        <div >
            <div class="card ">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('Previous Tickets Of Customer', 'menu')}}</h4>
                </div>
                <div class="card-body" >
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

@section('modal')

@include('admin.modalpopup.assignmodal')

@endsection
