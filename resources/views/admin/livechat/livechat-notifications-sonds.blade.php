@extends('layouts.adminmaster')
@section('styles')

    <!-- INTERNAL Data table css -->
    <link href="{{ asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Notifications Sounds', 'menu')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="card mb-0">
            <div class="card-header d-sm-max-flex border-0">
                <h4 class="card-title">{{lang('Notifications Sounds List')}}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive spruko-delete">
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
                                    {{lang('Article Title')}}
                                </th>
                                <th class="w-5">{{lang('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($sounds as $sound)
                            <tr>
                                <td>{{$i++}}</td>
                                <td><input type="checkbox" name="article_checkbox[]" class="checkall" value="{{$sound->name}}" /></td>
                                <td>
                                    {{$sound->name}}
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class=" btn btn-primary soundPlayBtn me-2" data-id="{{$sound->name}}" onclick="soundPlayFunction(this)">
                                            {{lang('Test')}}
                                        </a>

                                        <a href="javascript:void(0)" class="action-btns1" data-id="{{$sound->name}}"
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

            // Checkbox check all
            $('#customCheckAll').on('click', function() {
                $('.checkall').prop('checked', this.checked);

                if($('.checkall:checked').length){
                    document.querySelector("#massdelete").classList.remove("d-none")
                }else{
                    document.querySelector("#massdelete").classList.add("d-none")
                }
            });



            $('#customCheckAll').prop('checked', false);

            $('.checkall').on('click', function(){

                if($('.checkall:checked').length){
                    document.querySelector("#massdelete").classList.remove("d-none")
                }else{
                    document.querySelector("#massdelete").classList.add("d-none")
                }

                if($('.checkall:checked').length == $('.checkall').length){
                    $('#customCheckAll').prop('checked', true);
                }else{
                    $('#customCheckAll').prop('checked', false);
                }
            });

            $('body').on('click', '#show-delete', function() {
                var _id = $(this).data("id");
                swal({
                    title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                    text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "get",
                            url: "{{ route('admin.livechatNotificationsSondsDelete') }}",
                            data:{'id':_id},
                            success: function(data) {
                                toastr.success(data.success);
                                location.reload();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            },
                        });
                    }
                });

            });

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
                                url:"{{ url('admin/livechat-notifications-masssounds-delete')}}",
                                method:"GET",
                                data:{id:id},
                                success:function(data)
                                {
                                    toastr.success(data.message);
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

        });
    </script>

    <script>
        let currentAudio;
        let soundPlayFunction = (event) => {
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
            }

            // Create a new audio element
            let audioElement = document.createElement('audio');
            audioElement.id = "audioPlayer";
            audioElement.innerHTML = `
                <source src="{{url('')}}/uploads/livechatsounds/${event.getAttribute('data-id')}">
            `;

            // Play the new audio element
            audioElement.play();

            currentAudio = audioElement;
        };
    </script>

@endsection
