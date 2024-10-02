@extends('layouts.adminmaster')

@section('styles')

    <!-- INTERNAL Data table css -->
    <link href="{{asset('build/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <!-- INTERNAL Sweet-Alert css -->
	<link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

    <style>
        .start-ratings-main .ratingIcon{
            height: 22px;
            width: 22px;
            fill: rgba(23, 38, 58, 0.17);
        }

        .start-ratings-main .ratingIcon.checked{
            fill: orange;
        }

    </style>

@endsection

@section('content')

    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
        <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{lang('Livechat Operator View Reports')}}</span></h4>
        </div>
    </div>
    <!--End Page header-->

    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-12">
            <div class="card user-pro-list overflow-hidden">
                <div class="card-body">
                    <div class="user-pic text-center">
                        @if ($users->image == null)

                        <img src="{{asset('uploads/profile/user-profile.png')}}" class="avatar avatar-xxl brround" alt="">

                        @else
                        <img src="{{asset('uploads/profile/'.$users->image)}}" class="avatar avatar-xxl brround" alt="">

                        @endif
                        <div class="pro-user mt-3">
                            <h5 class="pro-user-username text-dark mb-1 fs-16">{{$users->name}}</h5>
                            <h6 class="pro-user-desc text-muted fs-12">{{$users->email}}</h6>
                            @if(!empty($users->getRoleNames()[0]))
                            <h6 class="pro-user-desc text-muted fs-12">{{ $users->getRoleNames()[0]}}</h6>
                            @endif
                            {{-- <div class="profilerating" data-rating="{{$avg}}"></div> --}}
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
                                        <span class="font-weight-semibold w-50"> {{lang('Employee ID')}}</span>
                                    </td>
                                    <td class="py-2 ps-4">{{$users->empid}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Name')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{$users->name}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Role Name')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @if(!empty($users->getRoleNames()[0]))

                                         {{$users->getRoleNames()[0]}}
                                         @endif

                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Email')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{$users->email}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Phone')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{$users->phone}}</td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Languages')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @php
                                        $values = explode(",", $users->languagues);

                                        @endphp

                                        <ul class="custom-ul">
                                            @foreach ($values as $value)

                                            <li class="tag mb-1">{{ucfirst($value)}}</li>

                                            @endforeach

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50">{{lang('Skills')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">
                                        @php
                                        $values = explode(",", $users->skills);
                                        @endphp

                                        <ul class="custom-ul">
                                            @foreach ($values as $value)

                                            <li class="tag mb-1">{{ucfirst($value)}}</li>

                                            @endforeach

                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2">
                                        <span class="font-weight-semibold w-50"> {{lang('Location')}} </span>
                                    </td>
                                    <td class="py-2 ps-4">{{$users->country}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{lang('Livechat Operator View Reports')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-bottom text-nowrap table-bordered w-100" id="reports">
                            <thead>
                                <tr>
                                    <th width="10">{{lang('Sl.No')}}</th>
                                    <th>{{lang('Rating')}}</th>
                                    <th>{{lang('problem Rectifiedt')}}</th>
                                    <th>{{lang('Feed Back Data')}}</th>
                                    <th>{{lang('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($reviewsData as $review)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            <div class="star-ratings start-ratings-main mb-2 mt-1  clearfix">
                                            @for($startIcon = 0; $startIcon < intval($review->starRating) ; $startIcon++)
                                                <svg class="ratingIcon checked" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                                C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                                c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>
                                            @endfor
                                            @for($startIcon = 0; $startIcon < intval(5-$review->starRating) ; $startIcon++)
                                                <svg class="ratingIcon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="star"><path d="M22,10.1c0.1-0.5-0.3-1.1-0.8-1.1l-5.7-0.8L12.9,3c-0.1-0.2-0.2-0.3-0.4-0.4C12,2.3,11.4,2.5,11.1,3L8.6,8.2L2.9,9
                                                C2.6,9,2.4,9.1,2.3,9.3c-0.4,0.4-0.4,1,0,1.4l4.1,4l-1,5.7c0,0.2,0,0.4,0.1,0.6c0.3,0.5,0.9,0.7,1.4,0.4l5.1-2.7l5.1,2.7
                                                c0.1,0.1,0.3,0.1,0.5,0.1l0,0c0.1,0,0.1,0,0.2,0c0.5-0.1,0.9-0.6,0.8-1.2l-1-5.7l4.1-4C21.9,10.5,22,10.3,22,10.1z"></path></svg>
                                            @endfor
                                            </div>
                                        </td>
                                        <td>
                                            {{$review->problemRectified}}
                                        </td>
                                        <td>{{$review->feedBackData}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a data-id={{$review->cust_id}}
                                                class="action-btns1" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title=""
                                                data-bs-original-title="View" aria-label="View">
                                                <i class="feather feather-eye text-primary"></i>
                                                </a>

                                                <a href="{{route('admin.livechatDeleteFeedback',['id' => $review->id])}}"
                                                class="btn btn-sm action-btns">
                                                <i class="feather feather-trash-2 text-danger"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="" data-bs-original-title="Delete" aria-label="Delete"
                                                ></i></a>
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

    </div>

@endsection

@section('scripts')

<!-- INTERNAL Data tables -->
<script src="{{asset('build/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('build/assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

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

    $('#reports').dataTable({
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

    document.querySelectorAll(".action-btns1").forEach((ele)=>{
        ele.onclick = ()=>{
            localStorage.reviewlivechatCustomer = ele.getAttribute('data-id')
            location.href = "{{route('admin.livechat')}}"
        }
    })


</script>


@endsection
