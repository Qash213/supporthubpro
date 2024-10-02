@extends('layouts.adminmaster')

@section('styles')
    <link href="{{asset('build/assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
    <style>
        .chatresponses .table {
            background-color: #fff
        }

        .chatresponses .btn-icon {
            line-height: 31px
        }

        .dark-mode .chatresponses .table {
            background-color: #191d43 !important;
        }
    </style>
@endsection

@section('content')
    <!--Page header-->
    <div class="page-header d-xl-flex d-block justify-content-between">
            <div class="page-leftheader">
                <h4 class="page-title"><span class="font-weight-normal text-muted ms-2">{{ lang('Chat Flows') }}</span>
                </h4>
            </div>
        <div>
            <a class="btn btn-primary float-end" href="{{ url('/admin/livechat-flow/null') }}"><i class="fe fe-plus"></i>
                {{ lang('Create from scratch') }}</a>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-12">
        </div>
    </div>
    <div class="row chatresponses mt-sm-0 mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Saved chats Flow') }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">{{ lang('Name') }}</th>
                                <th scope="col">{{ lang('Active') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($flow)
                                <tr>
                                    @php
                                        $carbonDateTime = Carbon\Carbon::parse(
                                            $flow->active_draft ? $flow->created_at : $flow->updated_at,
                                        );
                                        $convertedDateTime = $carbonDateTime->timezone(setting('default_timezone'));
                                        $formattedDateTime = $convertedDateTime->format(
                                            setting('date_format') . ' ' . setting('time_format'),
                                        );
                                    @endphp
                                    @if ($flow->responseName)
                                        <td scope="row"><b>{{ $flow->responseName }} {{ $formattedDateTime }}</b></td>
                                    @else
                                        <td scope="row"><b>{{ lang('Responses') }} {{ $formattedDateTime }}</b></td>
                                    @endif
                                    <td class="d-flex align-items-center border-0">
                                        <div>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="custom-switch-checkbox1"
                                                    chatFlowId={{ $flow->id }}
                                                    checked={{ $flow->active == '1' ? 'true' : 'false' }}
                                                    class="custom-switch-input activeCheckBtn">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <div class=" ms-3 d-flex">
                                                <a class="btn btn-icon me-1"
                                                    href="{{ url('/admin/livechat-flow') }}/{{ $flow->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i
                                                        class="fe fe-edit text-muted"></i></a>
                                                <a class="btn btn-icon flowDeleteBtn" flow-Id={{ $flow->id }}
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove"><i
                                                        class="fe fe-trash-2 text-muted"></i> </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if ($flow->active_draft)
                                    @php
                                        $carbonDateTime = Carbon\Carbon::parse($flow->updated_at);
                                        $convertedDateTime = $carbonDateTime->timezone(setting('default_timezone'));
                                        $formattedDateTime = $convertedDateTime->format(
                                            setting('date_format') . ' ' . setting('time_format'),
                                        );
                                    @endphp
                                    <tr>
                                        @if ($flow->responseName)
                                            <td scope="row">
                                                <p>{{ $flow->responseName }} {{ lang('Draft') }} {{ $formattedDateTime }}</p>
                                            </td>
                                        @else
                                            <td scope="row">
                                                <p>{{ lang('Active Chat Draft') }} {{ $formattedDateTime }}</p>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="ms-3 d-flex">
                                                <a class="btn btn-icon draftDeleteBtn" flow-Id={{ $flow->id }}
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove"><i
                                                        class="fe fe-trash-2 text-muted"></i> </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endif

                            @if (!$flow)
                                <tr>
                                    <td colspan="2" class="text-center">
                                        {{ lang('You currently don’t have any saved chats Flow.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">{{ lang('Draft chats Flow') }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">{{ lang('Name') }}</th>
                                <th scope="col">{{ lang('Active') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $emptyConversation = true;
                            @endphp
                            @foreach ($allDraftflow as $Draftflow)
                                <tr>
                                    {{ $emptyConversation = false }}
                                    @php
                                        $carbonDateTime = Carbon\Carbon::parse($Draftflow->updated_at);
                                        $convertedDateTime = $carbonDateTime->timezone(setting('default_timezone'));
                                        $formattedDateTime = $convertedDateTime->format(
                                            setting('date_format') . ' ' . setting('time_format'),
                                        );
                                    @endphp
                                    @if ($Draftflow->responseName)
                                        <td scope="row">{{ $Draftflow->responseName }} {{ $formattedDateTime }}</td>
                                    @else
                                        <td scope="row">{{ lang('Draft Responses') }} {{ $formattedDateTime }}</td>
                                    @endif
                                    <td class="d-flex align-items-center border-0">
                                        <div>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="custom-switch-checkbox1"
                                                    chatFlowId={{ $Draftflow->id }} class="custom-switch-input activeCheckBtn">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        </div>
                                        <div>
                                            <div class=" ms-3 d-flex">
                                                <a class="btn btn-icon me-1"
                                                    href="{{ url('/admin/livechat-flow') }}/{{ $Draftflow->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i
                                                        class="fe fe-edit text-muted"></i></a>
                                                <a class="btn btn-icon flowDeleteBtn" data-bs-toggle="tooltip"
                                                    flow-Id={{ $Draftflow->id }} data-bs-placement="top"
                                                    data-bs-title="Remove"><i class="fe fe-trash-2 text-muted"></i> </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($emptyConversation)
                                <tr>
                                    <td colspan="2" class="text-center">
                                        {{ lang('You currently don’t have any saved draft chats Flow.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <!-- INTERNAL Sweet-Alert js-->
    <script src="{{ asset('build/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        document.querySelectorAll(".activeCheckBtn").forEach((ele) => {
            ele.onclick = () => {
                let SaveData = {
                    chatId: ele.getAttribute('chatFlowId'),
                    checked: ele.checked
                }
                fetch('{{ route('admin.liveChatActiveFlowSave') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        method: "POST",
                        body: JSON.stringify(SaveData)
                    })
                    .then(function(response) {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Network response was not ok');
                        }
                    })
                    .then(function(data) {
                        if (data.success) {
                            toastr.success(data.success);
                            location.reload()
                        }
                    })
                    .catch(function(error) {
                        console.error('Fetch error:', error);
                    });
            }
        })

        var SITEURL2 = "{{ url('/admin/livechat-flow/delete') }}";

        // Chat Draft Delete Alert
        if (document.querySelector(".draftDeleteBtn")) {
            document.querySelector(".draftDeleteBtn").onclick = () => {
                swal({
                    title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                    text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((res) => {
                    if (res) {
                        let ActiveDraftId = document.querySelector(".draftDeleteBtn").getAttribute("flow-Id")
                        location.href =
                            `${SITEURL2}/${ActiveDraftId}?active-draft-delete=true`
                    }
                })
            }
        }

        // Flow Delete Btn
        document.querySelectorAll(".flowDeleteBtn").forEach((ele) => {
            if (ele) {
                ele.onclick = () => {
                    swal({
                        title: `{{ lang('Are you sure you want to continue?', 'alerts') }}`,
                        text: "{{ lang('This might erase your records permanently', 'alerts') }}",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((res) => {
                        if (res) {
                            location.href =
                                `${SITEURL2}/${ele.getAttribute("flow-Id")}`
                        }
                    })
                }
            }
        })
    </script>
@endsection
