@extends('layouts.adminmaster')

@section('styles')

    <!-- INTERNAL Data table css -->
    <link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
	<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

	<link href="{{asset('build/assets/plugins/rater-js/rater-js.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />



    <style>
        /* .start-ratings-main .ratingIcon{
            height: 22px;
            width: 22px;
            fill: rgba(23, 38, 58, 0.17);
        }

        .start-ratings-main .ratingIcon{
            height: 22px;
            width: 22px;
            fill: rgba(23, 38, 58, 0.17);
        }

        .start-ratings-main .ratingIcon.checked{
            fill: orange;
        } */
    </style>

@endsection

@section('content')

<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Livechat Reports', 'menu')}}</span></h4>
    </div>
</div>
<!--End Page header-->

<div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card mb-0">
        <div class="card-header d-sm-max-flex border-0">
            <h4 class="card-title">{{lang('Livechat Reports List')}}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive spruko-delete">
                <button id="massdelete" class="btn btn-outline-light btn-sm mb-4 d-none data-table-btn"><i class="fe fe-trash"></i> {{lang('Delete')}}</button>

                <table class="table table-bordered border-bottom text-nowrap ticketdeleterow w-100" id="liveChatReviewTable">
                    <thead>
                        <tr>
                            <th  width="9">{{lang('Sl.No')}}</th>
                            <th width="10" >{{lang('User Title')}}</th>
                            <th width="10" >{{lang('Rating')}}</th>
                            <th width="10" >{{lang('Overall Rating')}}</th>
                            <th width="10" >{{lang('Total Answered chats')}}</th>
                            <th width="10" >{{lang('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$user->name}}</td>
                                <td>

                                    <div class="star-ratings start-ratings-main mb-2 mt-1  clearfix">
                                        @php
                                            $count = 0;
                                            foreach($reviewsData as $review) {
                                                if ($review->users_id == $user->id) {
                                                    $count += $review->starRating;
                                                }
                                            };
                                            // $totalrating = $count/$reviewsData->count();
                                            if($reviewsData->count() != 0){
                                                $totalrating = $count/$reviewsData->count();
                                            }else{
                                                $totalrating = 0;
                                            }
                                        @endphp

                                        <span class="star-rating" style="width: 100px; height: 20px; background-size: 20px;">
                                            <span class="star-value" style="background-size: 20px; width: {{ ($totalrating / 5) * 100 }}%;">
                                            </span>
                                        </span>

                                    </div>
                                </td>
                                @php
                                    $count = 0;
                                    foreach($reviewsData as $review) {
                                        if ($review->users_id == $user->id) {
                                            $count++;
                                        }
                                    };
                                @endphp
                                <td>{{$count}}</td>
                                <td>{{$user->TotalAnsweredTicket ? $user->TotalAnsweredTicket : 0}}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{route('admin.livechatEmpliyerRatings',['id' => $user->id])}}" class="action-btns1"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="" data-bs-original-title="View"
                                        aria-label="View">
                                        <i class="feather feather-eye text-primary"></i>
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
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/rater-js/rater-js.js')}}"></script>

@vite(['resources/assets/js/select2.js'])

<!-- INTERNAL Sweet-Alert js-->
<script src="{{asset('build/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

<script>
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

    $('#liveChatReviewTable').dataTable({
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
            { "orderable": false, "targets":[ 0,1] }
        ],
    });


</script>


@endsection
