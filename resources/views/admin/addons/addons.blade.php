@extends('layouts.adminmaster')
@section('styles')
    <!-- INTERNAL Data table css -->
    <link href="{{ asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}?v=<?php echo time(); ?>"
        rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.css') }}?v=<?php echo time(); ?>" rel="stylesheet" />
@endsection

@section('content')


    {{-- ADDONS TABLE START --}}
    {{-- page header start --}}
    <div class="page-header d-sm-flex d-block">
        <div class="page-leftheader">
            <div class="page-title">{{ lang('Addons') }}</div>
        </div>

            <div class="card-options mt-sm-max-2 d-md-max-block">
                @can('Addon Add')
                    <a href="#" class="btn btn-secondary mb-md-max-2 me-3 text-capitalize" id="addaddonmodal">
                        {{ lang('Upload Addon') }}</a>
                @endcan
            </div>

    </div>
    {{-- page header start --}}

    {{-- card start --}}
    <div class="card">
        <div class="card-header  border-0">
            <h4 class="card-title">{{ lang('Addons') }}</h4>

        </div>

        <div class="card-body">
            <div class="table-responsive spruko-delete">
                {{-- @can('Addons Delete')

            <button id="massdeletenotify" class="btn btn-outline-light btn-sm mb-4 data-table-btn"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>
            @endcan --}}

                <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="addonTable">
                    <thead>
                        <tr>
                            <th width="10">{{ lang('Sl.No') }}</th>
                            {{-- @can('Addons Delete')

                            <th width="10" >
                                <input type="checkbox"  id="customCheckAll">
                                <label  for="customCheckAll"></label>
                            </th>
                        @endcan --}}
                            {{-- @cannot('Addons Delete')

                            <th width="10" >
                                <input type="checkbox"  id="customCheckAll" disabled>
                                <label  for="customCheckAll"></label>
                            </th>
                        @endcannot --}}

                            <th>{{ lang('Addon Type') }}</th>
                            <th>{{ lang('Name') }}</th>
                            <th>{{ lang('Icon') }}</th>
                            <th>{{ lang('Version') }}</th>
                            <th>{{ lang('Status') }}</th>
                            <th>{{ lang('Last Updated') }}</th>
                            <th>{{ lang('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            use Carbon\Carbon;
                            $count = 1;
                            $dataArray = [];
                            $storageAddonid = [];

                        @endphp
                        {{-- INSTALLED ADDONS --}}
                        {{-- Storage Addons --}}
                        @foreach ($storageAddons as $addon)
                            @php
                                $dataArray[] = $addon['name'];
                                $countOfStorageAddons = count($storageAddons);
                                $storageAddonid[] = 'storageAddonstatus-'.$addon->id;
                            @endphp
                            <tr class="odd">
                                <td class="sorting_1">{{ $count++ }}</td>
                                    <td class="font-weight-semibold">{{ lang('Storage') }}</td>

                                <td class="font-weight-semibold">{{ $addon['name'] }}</td>
                                <td>
                                    <img src="{{ asset($addon['image']) }}" width="50px" height="50px" alt="erre">

                                </td>
                                <td>
                                    {{ $addon['version'] }}
                                </td>
                                <td>

                                    <label class="custom-switch form-switch mb-0">
                                        @can('Addon Edit')
                                            <input type="checkbox" name="status" data-id="{{ $addon->id }}" id="storageAddonstatus-{{ $addon->id }}" value="1" class="custom-switch-input tswitch"
                                                @if (addonstatus($addon['handler'])) checked="" @endif>
                                            <span class="custom-switch-indicator"></span>
                                        @endcan
                                        @cannot('Addon Edit')
                                        ~
                                        @endcannot

                                    </label>
                                </td>
                                <td>
                                    <span>{{ \Carbon\Carbon::parse($addon->created_at)->format('F j, Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="javascript:void(0);" class="action-btns1"
                                            id="updatemodal-{{ $addon->id }}" data-id="{{ $addon->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ lang('Update credentials') }}">
                                            <i class="feather feather-edit text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- paymentAddons --}}

                        @foreach ($paymentAddons as $addon)
                            @php
                                $dataArray[] = $addon['name'];
                                $countOfStorageAddons = count($storageAddons);

                            @endphp
                            <tr class="odd">
                                <td class="sorting_1">{{ $count++ }}</td>
                                <td class="font-weight-semibold">{{ lang('Payment') }}</td>
                                <td class="font-weight-semibold">{{ $addon['name'] }}</td>
                                <td>
                                    <img src="{{ asset($addon['image']) }}" width="50px" height="50px" alt="erre">

                                </td>
                                <td>
                                    {{ $addon['version'] }}
                                </td>
                                <td>

                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="status" data-id="{{ $addon->id }}"
                                            id="addonstatus" value="1" class="custom-switch-input tswitch"
                                            @if (addonstatus($addon['handler'])) checked="" @endif>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                <td>
                                    <span>{{ \Carbon\Carbon::parse($addon->created_at)->format('F j, Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="javascript:void(0);" class="action-btns1"
                                            id="updatemodal-{{ $addon->id }}" data-id="{{ $addon->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ lang('Update credentials') }}">
                                            <i class="feather feather-edit text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- generalAddons --}}
                        @foreach ($generalAddons as $addon)
                            @php
                                $dataArray[] = $addon['name'];
                                $countOfStorageAddons = count($storageAddons);

                            @endphp
                            <tr class="odd">
                                <td class="sorting_1">{{ $count++ }}</td>
                                <td class="font-weight-semibold">{{ lang('General') }}</td>
                                <td class="font-weight-semibold">{{ $addon['name'] }}</td>
                                <td>
                                    <img src="{{ asset($addon['image']) }}" width="50px" height="50px" alt="erre">

                                </td>
                                <td>
                                    {{ $addon['version'] }}
                                </td>
                                <td>

                                    <label class="custom-switch form-switch mb-0">
                                        <input type="checkbox" name="status" data-id="{{ $addon->id }}"
                                            id="addonstatus" value="1" class="custom-switch-input tswitch"
                                            @if (addonstatus($addon['handler'])) checked="" @endif>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                                <td>
                                    <span>{{ \Carbon\Carbon::parse($addon->created_at)->format('F j, Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="javascript:void(0);" class="action-btns1"
                                            id="updatemodal-{{ $addon->id }}" data-id="{{ $addon->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ lang('Update credentials') }}">
                                            <i class="feather feather-edit text-primary"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        {{-- AVAILABLE ADDONS --}}

                        @foreach ($addons as $addon)

                            @if (count($addondata) > 0)

                                @if (in_array($addon['name'], $dataArray))
                                @else
                                    <tr class="odd">
                                        <td class="sorting_1">{{ $count++ }}</td>

                                        <td class="font-weight-semibold">{{ lang($addon['type']) }}</td>

                                        <td class="font-weight-semibold">{{ lang($addon['name']) }}</td>
                                        <td>
                                            <img src=" {{ $addon['icon'] }}" width="50px" height="50px" alt="erre">
                                        </td>
                                        <td>
                                            {{ $addon['version'] }}
                                        </td>
                                        <td>
                                            <label class="custom-switch form-switch mb-0">

                                                <span class=" mx-4">~~</span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class=" mx-4">~~</span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ $addon['link'] }}" class="btn btn-success" target="_blank">
                                                    {{lang($addon['buttonName'])}}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr class="odd">
                                    <td class="sorting_1">{{ $count++ }}</td>

                                    <td class="font-weight-semibold">{{ lang($addon['type']) }}</td>

                                    <td class="font-weight-semibold">{{ lang($addon['name']) }}</td>
                                    <td>
                                        <img src=" {{ $addon['icon'] }}" width="50px" height="50px" alt="erre">
                                    </td>
                                    <td>
                                        {{ $addon['version'] }}
                                    </td>
                                    <td>
                                        <label class="custom-switch form-switch mb-0">

                                            <span class=" mx-4">~~</span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class=" mx-4">~~</span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ $addon['link'] }}" class="btn btn-success" target="_blank">
                                                {{ lang($addon['buttonName']) }}</a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- card end --}}



    {{-- ADDONS TABLE END --}}
@endsection

@section('modal')
    @include('admin.addons.modal')
    @include('admin.addons.update')
@endsection

@section('scripts')
    <!-- INTERNAL Data tables -->
    <script src="{{ asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/datatable/dataTables.responsive.min.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/datatable/datatablebutton.min.js') }}?v=<?php echo time(); ?>"></script>
    <script src="{{ asset('build/assets/plugins/datatable/buttonbootstrap.min.js') }}?v=<?php echo time(); ?>"></script>
    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />


    <script src="{{ asset('build/assets/plugins/jquery/jquery-ui.js') }}?v=<?php echo time(); ?>"></script>

    <script type="text/javascript">
        $(function() {
            "use strict";

            // Variables
            var SITEURL = '{{ url('') }}';

            // Csrf Field
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            let prev = {!! json_encode(lang('Previous')) !!};
            let next = {!! json_encode(lang('Next')) !!};
            let nodata = {!! json_encode(lang('No data available in table')) !!};
            let noentries = {!! json_encode(lang('No entries to show')) !!};
            let showing = {!! json_encode(lang('showing page')) !!};
            let ofval = {!! json_encode(lang('of')) !!};
            let maxRecordfilter = {!! json_encode(lang('- filtered from ')) !!};
            let maxRecords = {!! json_encode(lang('records')) !!};
            let entries = {!! json_encode(lang('entries')) !!};
            let show = {!! json_encode(lang('Show')) !!};
            let search = {!! json_encode(lang('Search...')) !!};
            // Datatable

            $('#addonTable').dataTable({
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
                    info: `${showing} _PAGE_ ${ofval} _PAGES_`,
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


            //trigger next modal
            $("#addaddonmodal").on("click", function() {
                $('#addonform').trigger("reset");
                $('#addonsave').html("Add");
                $('.modal-title').html("{{ lang('Upload Addon') }}");
                $('#addonmodal').modal('show');
            });

            //trigger update modal
            @foreach ($addondata as $addon)
                var isRequestInProgress = false;
                document.querySelector('#updatemodal-{{ $addon->id }}').addEventListener('click', function() {
                    if (isRequestInProgress) {
                        return;
                    }

                    isRequestInProgress = true;
                    document.querySelector('#updateform').reset();
                    var addonid = this.getAttribute('data-id');
                    var $this = this;
                    $this.disabled = true;

                    document.querySelector('#addonData').innerHTML = '';

                    fetch(SITEURL + '/admin/addons/' + addonid)
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {

                            document.querySelector('#updatemodall').style.display = 'block';
                            $('.modal-title').html("{{ lang('Update') }} " + data[1] +
                                " {{ lang('Credentials') }}");
                            document.querySelector('#addonid').value = data[2];
                            document.querySelector('#updatesave').innerHTML = "Save";
                            var i = 1;
                            for (var element of data[0]) {
                                var formGroup = document.createElement('div');
                                formGroup.className = 'form-group';
                                var label = document.createElement('label');
                                label.className = 'form-label';
                                label.innerHTML = '<span class="addon" id="label"></span> ' + element +
                                    '<span class="text-red">*</span>';
                                var input = document.createElement('input');
                                input.type = 'text';
                                input.name = element;
                                input.className = 'form-control';
                                input.placeholder = 'Enter ' + element;
                                input.id = 'id' + i;
                                input.required = true;
                                var errorMessage = document.createElement('span');
                                errorMessage.className = 'text-red';
                                errorMessage.id = element + 'Error';
                                formGroup.appendChild(label);
                                formGroup.appendChild(input);
                                formGroup.appendChild(errorMessage);
                                document.querySelector('#addonData').appendChild(formGroup);
                                i++;
                            }
                            if (data[3]) {
                                if (document.querySelector('#id1')) {
                                    document.querySelector('#id1').value = data[3]['access_key_id'];
                                }
                                if (document.querySelector('#id2')) {
                                    document.querySelector('#id2').value = data[3]['secret_access_key'];
                                }
                                if (document.querySelector('#id3')) {
                                    document.querySelector('#id3').value = data[3]['default_region'];
                                }
                                if (document.querySelector('#id4')) {
                                    document.querySelector('#id4').value = data[3]['bucket'];
                                }
                                if (document.querySelector('#id5')) {
                                    document.querySelector('#id5').value = data[3]['endpoint'];
                                }
                            }
                            new bootstrap.Modal(document.querySelector('#updatemodall')).show();
                            isRequestInProgress = false;
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            isRequestInProgress = false;
                        });
                });
            @endforeach

            //update credentials
            $('body').on("click", "#updatesave", function() {
                var form = document.getElementById('updateform');
                var formData = new FormData(form);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('update.credentials') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        toastr.success('{{ lang('Updated successfully .', 'alerts') }}');
                        location.reload();

                    },
                    error: function(data) {
                        toastr.error('{{ lang('Please fill required fields.', 'alerts') }}');
                    }
                });
            });

            //status on and off
            @foreach ($addondata as $addon)
                $('body').on('change', '#addonstatus-{{ $addon->id }}', function() {
                    var _id = $(this).data("id");

                    var status = $(this).prop('checked') == true ? '1' : '0';
                        if(status == '1'){
                            swal({
                            title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                            text: "{{lang('If you enable it, this will be Active.', 'alerts')}}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                            })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/addons/statuschange/" + _id,
                                data: {
                                    'status': status
                                },
                                success: function(data) {

                                    if (data == 1) {
                                        toastr.error('{{ lang('Please setup credentials .', 'alerts') }}');
                                        $('#addonstatus-{{ $addon->id }}').prop('checked', false);

                                    } else {
                                        if(data ==3){
                                            toastr.error('{{ lang('Something went wrong .', 'alerts') }}');
                                            $('#addonstatus-{{ $addon->id }}').prop('checked', false);
                                        }else{

                                            toastr.success('{{ lang('Updated successfully .', 'alerts') }}');
                                        }

                                    }

                                    // location.reload();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                        });


                            }else{
                                $('#addonstatus-{{ $addon->id }}').prop('checked', false);
                            }


                        });
                    }else{
                        $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/addons/statuschange/" + _id,
                                data: {
                                    'status': status
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        toastr.error('{{ lang('Please setup credentials .', 'alerts') }}');
                                        $('#addonstatus-{{ $addon->id }}').prop('checked', false);

                                    } else {

                                        toastr.success('{{ lang('Updated successfully .', 'alerts') }}');
                                    }

                                    // location.reload();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                        });
                    }
                });



            @endforeach



            //storage addon status
            @foreach ($storageAddons as $addon)
                $('body').on('change', '#storageAddonstatus-{{ $addon->id }}', function() {
                    var _id = $(this).data("id");

                    var status = $(this).prop('checked') == true ? '1' : '0';
                        if(status == '1'){
                            swal({
                            title: `{{lang('Are you sure you want to continue?', 'alerts')}}`,
                            text: "{{lang('If you enable it, this will become the default storage.', 'alerts')}}",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                            })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/addons/statuschange/" + _id,
                                data: {
                                    'status': status
                                },
                                success: function(data) {

                                    if (data == 1) {
                                        toastr.error('{{ lang('Please setup credentials .', 'alerts') }}');
                                        $('#storageAddonstatus-{{ $addon->id }}').prop('checked', false);

                                    } else {
                                        if(data ==3){
                                            toastr.error('{{ lang('Something went wrong .', 'alerts') }}');
                                            $('#storageAddonstatus-{{ $addon->id }}').prop('checked', false);
                                        }else{
                                            toastr.success('{{ lang('Updated successfully .', 'alerts') }}');
                                            location.reload();

                                        }

                                    }


                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                        });


                            }else{
                                $('#storageAddonstatus-{{ $addon->id }}').prop('checked', false);
                            }


                        });
                    }else{
                        $.ajax({
                                type: "post",
                                url: SITEURL + "/admin/addons/statuschange/" + _id,
                                data: {
                                    'status': status
                                },
                                success: function(data) {
                                    if (data == 1) {
                                        toastr.error('{{ lang('Please setup credentials .', 'alerts') }}');
                                        $('#storageAddonstatus-{{ $addon->id }}').prop('checked', false);

                                    } else {

                                        toastr.success('{{ lang('Successfully disabled .', 'alerts') }}');
                                    }

                                    // location.reload();
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                }
                        });
                    }
                });



            @endforeach

            // Application Purchase Code Validation
            var isRequestInValid = false;
            $("body").on('keyup', '#applicationPurchasecode', function() {

                let value = $(this).val();
                let item = "application";
                if (value != '') {
                    if(value.length == '36'){

                        if (isRequestInValid) {
                            return;
                        }
                        isRequestInValid = true;
                        $.ajax({
                            url: "{{ route('application.EnavatoVerify') }}",
                            method: "POST",
                            data: {data: value,item:item},

                            dataType:"json",

                            success: function (data) {

                                if(data.valid){
                                    $("#purchasecode").prop("disabled", false);
                                    // Enable the addon file input
                                    // $("#addon").prop("disabled", false);
                                    toastr.success(data.success);
                                }else{
                                    $("#purchasecode").prop("disabled", true);
                                    toastr.error(data.error);
                                }
                                isRequestInValid = false;

                            },
                            error: function (data) {

                            }
                        });
                    }else if(value.length > 36){
                        toastr.error('Invalid Purchase Code.');
                        $("#purchasecode").prop("disabled", true);
                        $("#addon").prop("disabled", true);
                    }else{
                        $("#purchasecode").prop("disabled", true);
                        $("#addon").prop("disabled", true);
                    }
                }else{
                    toastr.error('Purchase Code field is Required');
                    $('.purchasecode').attr('disabled', true);
                    $("#addon").prop("disabled", true);
                    $('#envato_id').css('border', '1px solid #e13a3a');

                }
            });



            // Addon Purchase Code Validation
            var isRequestAddon = false;
            $("body").on('keyup', '#purchasecode', function() {

                let value = $(this).val();
                let item = "addon";

                if (value != '') {
                    if(value.length == '36'){

                        if (isRequestAddon) {
                            return;
                        }
                        isRequestAddon = true;
                        $.ajax({
                            url: "{{ route('application.EnavatoVerify') }}",
                            method: "POST",
                            data: {data: value,item:item},

                            dataType:"json",

                            success: function (data) {

                                if(data.valid){
                                    // $("#purchasecode").prop("disabled", false);
                                    // Enable the addon file input

                                    $("#addon").prop("disabled", false);
                                    $("#addonsave").prop("disabled", false);
                                    toastr.success(data.success);
                                }else{
                                    $("#purchasecode").prop("disabled", true);
                                    toastr.error(data.error);
                                }
                                isRequestAddon = false;

                            },
                            error: function (data) {

                            }
                        });
                    }else if(value.length > 36){
                        toastr.error('Invalid purchase code.');
                        $("#addon").prop("disabled", true);
                        $("#addonsave").prop("disabled", true);
                    }else{
                        $("#addon").prop("disabled", true);
                        $("#addonsave").prop("disabled", true);
                    }
                }else{
                    toastr.error('Purchase Code field is Required');
                    $("#addon").prop("disabled", true);
                    $("#addonsave").prop("disabled", true);
                    $('#envato_id').css('border', '1px solid #e13a3a');
                }
            });



            //addons save
            $('body').on("click", "#addonsave", function() {
                var form = document.getElementById('addonform');
                var formData = new FormData(form);
                $('#addonform').submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    // Your form handling code here
                });
                $('#loadingIndicator').show();

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('addon.setup') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    success: function(data) {

                        var toastrMessage = '';
                        switch (data) {
                            case 0:
                                toastrMessage = `{{ lang('Invalid Purchase code.', 'alerts') }}`;
                                break;
                            case 1:
                                toastrMessage =
                                    `{{ lang('Invalid file format. Please upload a ZIP file.', 'alerts') }}`;
                                break;
                            case 2:
                                toastrMessage = `{{ lang('Failed to open the ZIP file.', 'alerts') }}`;
                                break;
                            case 3:
                                toastrMessage = `{{ lang('uhelpAddon.json file missing.', 'alerts') }}`;
                                break;
                            case 4:
                                toastrMessage = `{{ lang('Failed to save addon.', 'alerts') }}`;
                                break;
                            case 5:
                                toastrMessage =
                                    `{{ lang('Something went wrong please try again.', 'alerts') }}`;
                                break;
                            case 6:
                                toastrMessage = `{{ lang('Addon already exists.', 'alerts') }}`;
                                break;
                            case 7:
                                toastrMessage =
                                    `{{ lang('Invalid purchase code for this item .', 'alerts') }}`;
                                break;
                            case 8:
                                toastrMessage = `{{ lang('Invalid Purchase code.', 'alerts') }}`;
                                break;
                            case 9:
                                toastrMessage = `{{ lang('Purchase code is already used.', 'alerts') }}`;
                                break;
                            case 10:
                                toastrMessage = `{{ lang('The buyer name of the addon does not match the buyer name of the application. Please verify and ensure that both names match or contact support for assistance.', 'alerts') }}`;
                                break;
                            case 11:
                                toastrMessage = `{{ lang('The purchase code is valid, but it is associated with a different installation URL. Please make sure you are using the correct purchase code for this installation.', 'alerts') }}`;
                                break;
                            default:
                                $('#addonmodal').modal('hide');
                                $('#loadingIndicator').hide();
                                toastr.success(data.success);
                                location.reload();
                                return;
                        }

                        $('#loadingIndicator').hide();
                        toastr.error(toastrMessage);

                    },
                    error: function(data) {
                        $('#loadingIndicator').hide();
                        toastr.error('{{ lang('Please fill required fields.', 'alerts') }}');
                    }
                });
            });
        })
    </script>
@endsection
