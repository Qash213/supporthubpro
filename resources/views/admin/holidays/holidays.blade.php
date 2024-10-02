@extends('layouts.adminmaster')

@section('styles')

    <!-- INTERNAL Data table css -->
    <link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Datepicker css-->
    <link href="{{asset('build/assets/plugins/modal-datepicker/datepicker.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAl color css -->
    <link rel="stylesheet" href="{{asset('build/assets/plugins/colorpickr/themes/nano.min.css')}}?v=<?php echo time(); ?>">

@endsection

@section('content')


{{-- page header start --}}
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <div class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Holiday Announcement')}}</span></div>
    </div>
</div>
{{-- page header start --}}

{{-- card start --}}
<div class="card">
    <div class="card-header  border-0">
        <h4 class="card-title">{{lang('Holidays Lists')}}</h4>
        <div class="card-options mt-sm-max-2 d-md-max-block">
            @can('Holidays Create')

            <a href="#" class="btn btn-success mb-md-max-2 me-3 text-capitalize" id="addholidaymodal"><i class="feather feather-user-plus"></i> {{lang('add holiday')}}</a>
            @endcan
            @can('Holidays Import Access')
            <a href="{{route('holidays.import')}}" class="btn btn-info mb-md-max-2 me-3"><i class="feather feather-download"></i> {{lang('Import Holidays List')}}</a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive spruko-delete">
            @can('Holidays Delete')

            <button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn" style="display: none;"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
            @endcan

            <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="support-articlelists">
                <thead>
                    <tr>
                        <th  width="10">{{lang('Sl.No')}}</th>
                        @can('Holidays Delete')

                            <th width="10" >
                                <input type="checkbox"  id="customCheckAll">
                                <label  for="customCheckAll"></label>
                            </th>
                        @endcan
                        @cannot('Holidays Delete')

                            <th width="10" >
                                <input type="checkbox"  id="customCheckAll" disabled>
                                <label  for="customCheckAll"></label>
                            </th>
                        @endcannot

                        <th >{{lang('ocassion')}}</th>
                        <th >{{lang('Day')}}</th>
                        <th >{{lang('Date')}}</th>
                        <th >{{lang('Status')}}</th>
                        <th >{{lang('Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        use Carbon\Carbon;
                        $count =1;
                    @endphp
                   @foreach($holidays as $holiday)
                   <tr class="odd">
                       <td class="sorting_1">{{$count++}}</td>
                       <td class="sorting_1">
                           @if(Auth::user()->can('Holidays Delete'))
								<input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$holiday->id}}" />
							@else
                                <input type="checkbox" name="spruko_checkbox[]" class="checkall" value="{{$holiday->id}}" disabled />
							@endif
                       </td>
                       <td class="font-weight-semibold">{{$holiday['occasion']}}</td>
                       <td>
                            @if($holiday['enddate'] == $holiday['startdate'])
                                {{\Carbon\Carbon::parse($holiday['startdate'])->format('l') }}
                            @else
                                {{\Carbon\Carbon::parse($holiday['startdate'])->format('l')." -- ".\Carbon\Carbon::parse($holiday['enddate'])->format('l')}}
                            @endif
                        </td>
                       <td>{{Carbon::parse($holiday['startdate'])->format('d-m-Y')}} @if($holiday['enddate'] != null){{" -- ".Carbon::parse($holiday['enddate'])->format('d-m-Y')}} @endif</td>
                       <td>
                            @if(Auth::user()->can('Holidays Edit'))
                               @if($holiday->status == '1')
                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="status" data-id="{{$holiday->id}}" id="myonoffswitch{{$holiday->id}}" value="1" class="custom-switch-input tswitch" checked>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                @else
                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="status" data-id="{{$holiday->id}}" id="myonoffswitch{{$holiday->id}}" value="1" class="custom-switch-input tswitch" >
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                @endif
                            @endif
                       </td>
                       <td>
                            <div class="d-flex">
                                @if(Auth::user()->can('Holidays Edit'))
                                    <a href="javascript:void(0);" class="action-btns1" id="editholidaymodal" data-id="{{$holiday['id']}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Edit')}}">
    									<i class="feather feather-edit text-primary"></i>
    								</a>
                                @endif

                                @if(Auth::user()->can('Holidays Delete'))
                                    <a href="javascript:void(0);" class="action-btns1 deleteholiday" id="deleteholidaymodal" data-id="{{$holiday['id']}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{lang('Delete')}}">
    									<i class="feather feather-trash-2 text-danger"></i>
    								</a>
                                @endif
                            </div>
                       </td>
                   </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- card end --}}

@endsection
@section('modal')
    @include('admin.holidays.modal')

@endsection

@section('scripts')
    <!-- INTERNAL Data tables -->
    <script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/datatablebutton.min.js')}}?v=<?php echo time(); ?>"></script>
    <script src="{{asset('build/assets/plugins/datatable/buttonbootstrap.min.js')}}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}?v=<?php echo time(); ?>"></script>

    <script src="{{asset('build/assets/plugins/jquery/jquery-ui.js')}}?v=<?php echo time(); ?>"></script>

    <!-- INTERNAL color pickr -->
    <script src="{{ asset('build/assets/plugins/colorpickr/pickr.min.js') }}?v=<?php echo time(); ?>"></script>


    <script type="text/javascript">
        $(function() {
            "use strict";

            // Variables
            var SITEURL = '{{url('')}}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            window.setColorPicker = (elem, defaultValue) => {
                elem = document.querySelector(elem);
                let pickr = Pickr.create({
                    el: elem,
                    default: defaultValue,
                    theme: 'nano', // or 'monolith', or 'nano'
                    useAsButton: true,
                    swatches: [
                        '#217ff3',
                        '#11cdef',
                        '#fb6340',
                        '#f5365c',
                        '#f7fafc',
                        '#212529',
                        '#2dce89'
                    ],
                    components: {
                        // Main components
                        preview: true,
                        opacity: true,
                        hue: true,
                        // Input / output Options
                        interaction: {
                            hex: true,
                            rgba: true,
                            // hsla: true,
                            // hsva: true,
                            // cmyk: true,
                            input: true,
                            clear: true,
                            silent: true,
                            preview: true,
                        }
                    }
                });
                pickr.on('init', pickr => {
                    elem.value = pickr.getSelectedColor().toRGBA().toString(0);
                }).on('change', color => {
                    elem.value = color.toRGBA().toString(0);
                });

                return pickr;

            }
            // Color Pickr variables
            setTimeout(() => {
                let themeColor = setColorPicker('#primary_color-input', document.querySelector('#primary_color-input')?.value);
                let themeColorDark = setColorPicker('#secondary_color-input', document.querySelector('#secondary_color-input').value);
            }, 100);

            // Datepicker
            setTimeout(() => {
                $('#startdate').datepicker({
                        dateFormat: 'yy-mm-dd',
                        prevText: '<i class="fa fa-angle-left"></i>',
                        nextText: '<i class="fa fa-angle-right"></i>',
                        minDate: 0,
                        firstDay: {{setting('start_week')}},

                        onSelect: function (selectedDate) {

                            var diff = ($("#enddate").datepicker("getDate") -
                                $("#startdate").datepicker("getDate")) /
                                1000 / 60 / 60 / 24 + 1; // days
                            if ($("#enddate").datepicker("getDate") != null) {
                                $('#count').html(diff);
                                $('#days').val(diff);
                            }
                            $('#enddate').datepicker('option', 'minDate', selectedDate);
                        }
                    });
            }, 100);
            setTimeout(() => {
                $('#enddate').datepicker({
                    dateFormat: 'yy-mm-dd',
                    prevText: '<i class="fa fa-angle-left"></i>',
                    nextText: '<i class="fa fa-angle-right"></i>',
                    firstDay: {{setting('start_week')}},
                    onSelect: function (selectedDate) {

                        $('#startdate').datepicker('option', 'maxDate', selectedDate);

                        var diff = ($("#enddate").datepicker("getDate") -
                            $("#startdate").datepicker("getDate")) /
                            1000 / 60 / 60 / 24 + 1; // days
                        if ($("#startdate").datepicker("getDate") != null) {
                            $('#count').html(diff);
                            $('#days').val(diff);
                        }
                    }
                });
            }, 100);

            // Holiday status change
            $('body').on('click', '.tswitch', function () {
                var _id = $(this).data("id");
                var status = $(this).prop('checked') == true ? '1' : '0';
                    $.ajax({
                        type: "post",
                        url: SITEURL + "/admin/holidays/statuschange/"+_id,
                        data: {'status': status},
                        success: function (data) {
                            toastr.success(data.success);
                            location.reload();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
            });

            //trigger next modal
            $("#addholidaymodal").on( "click", function() {
                $('#Holidayform').trigger("reset");
                $('#holidaysave').html("Add");
                $('.modal-title').html("{{lang('Add Holiday Announcement')}}");
                $('#holidaymodal').modal('show');

            });


            //edit holiday
            $('body').on('click', '#editholidaymodal', function () {
            $('#Holidayform').trigger("reset");
            var holiday_id = $(this).data('id');
            $.get('holidays/' + holiday_id , function (data) {
                    $('#startdateError').html('');
                    $('#enddateError').html('');
                    $('.modal-title').html("{{lang('Edit Holiday Announcement')}}");
                    $('#holiday_id').val(data.id);
                    $('#occasion').val(data.occasion);
                    $('#startdate').val(data.startdate.substr(0, 10));
                    $('#enddate').val(data.enddate.substr(0, 10));
                    $('#holidaydescription').val(data.holidaydescription);
                    $('#primary_color-input').val(data.primaray_color);
                    $('#secondary_color-input').val(data.secondary_color);
                    document.querySelector("#holidaystatus").checked = data.status == 1 ? true : false
                    $('#holidaysave').html("Save");
                    $('#holidaymodal').modal('show');
                });
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
            $('#support-articlelists').dataTable({
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
                    { "orderable": false, "targets":[ 0,1,4] }
                ],
            });

            $('.form-select').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });


            //checkbox script
            $(document).ready(function() {

                $(document).on('click', '#customCheckAll', function() {
                    $('.checkall').prop('checked', this.checked);
                    updateMassDeleteVisibility();
                });

                $(document).on('click', '.checkall', function() {
                    updateCustomCheckAll();
                    updateMassDeleteVisibility();
                });


                $(document).on('click', '.pagination a', function() {

                    setTimeout(function() {
                        updateMassDeleteVisibility();
                    }, 100);
                });


                $('#customCheckAll').prop('checked', false);


                function updateMassDeleteVisibility() {
                    if ($('.checkall:checked').length === 0) {
                        $('#massdeletenotify').hide();
                    } else {
                        $('#massdeletenotify').show();
                    }
                }


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

            $('body').on( "click" ,"#holidaysave", function() {
                var form = document.getElementById('Holidayform');
                var formData = new FormData(form);
                $('#holidaysave').html('Sending ... <i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{route('saveholidays')}}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,

                    success:function(data){
                        $('#holidaysave').html('Save Changes');
                        $('#holidaymodal').modal('hide');
                        location.reload();
                        toastr.success(data.success);

                    },
                    error:function(data){
                        $('#holidaysave').html('Save Changes');
                        toastr.error('{{lang('Please fill required fields.', 'alerts')}}');
                    }
                });
            });

            // Delete the Holiday
            $('body').on('click', '#deleteholidaymodal', function () {
                var holiday_id = $(this).data("id");

                swal({
                    title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                    text: "{{lang('This might erase your records permanently', 'alerts')}}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                    $.get('holidays/delete/' + holiday_id , function (data) {
                        toastr.success('{{lang('successfully deleted .', 'alerts')}}');
                        location.reload();

                    });

                    }
                });
            });

            // Mass Delete
            $('body').on('click', '#massdeletenotify', function () {
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
                                url:"{{ url('admin/holidays/massdelete')}}",
                                method:"GET",
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
        })
    </script>
@endsection

