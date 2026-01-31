@php
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    $locale = app() ->getLocale();
    $User = Auth::user();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Cases"),
    "Title" => __("Case details")
])
@section('toolbar_buttons')
    @include("layouts.partials.back_button", ["link" => route("cases.cases_list.index"), "label" => __("Cases list")])
    @if (auth()->user()->can('accept_cases'))
        @if (((int)$Case->status == 1 and $User->hasRole('supervising'))
        or ((int)$Case->status == 2 and $User->hasRole('physician')))
            <a data-id="{{ $Case["id"] }}" class="btn btn-sm px-3 btn-primary accept_button">{{ __("Accept") }}</a>
        @endif
    @endif

    @if (auth()->user()->can('reject_cases'))
        @if ($User->hasRole('supervising'))
            @if (!in_array((int) $Case->status, [2, 4, 5]))
                <a data-id="{{ $Case["id"] }}" class="btn btn-sm px-3 btn-danger reject_button">{{ __("Reject") }}</a>
            @endif
        @elseif ($User->hasRole('physician'))
            @if (!in_array((int) $Case->status, [1, 3, 4, 5]))
                <a data-id="{{ $Case["id"] }}" class="btn btn-sm px-3 btn-danger reject_button">{{ __("Reject") }}</a>
            @endif
        @endif
    @endif

@endsection
@section('content')
    <div class="card">
        <!--begin::Card-->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Case number") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $Case["case_number"] }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient name") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $Case["patient_name"] }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient gender") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">
                            @if($Case["gender"] == 0) {{ __("Female") }} @else {{ __("Male") }} @endif
                        </div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient age") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $Case["age"] }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Physician") }}</div>
                        <div class="fw-semibold text-gray-600 fs-6">{{ User::where("id", $Case["physician"])->value("name") }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Supervising") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ User::where("id", $Case["supervising"])->value("name") }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Status") }}</div>
                        <div class="fw-bold text-gray-800 fs-8 mt-2">
                            @if($Case["status"] == 1)
                                <span class="badge badge-light-info">{{ __("Waiting for supervising") }}</span>
                            @elseif($Case["status"] == 2)
                                <span class="badge badge-light-success">{{ __("Waiting for physician") }}</span>
                            @elseif($Case["status"] == 3)
                                <span class="badge badge-light-primary">{{ __("Approved") }}</span>
                            @elseif($Case["status"] == 4)
                                <span class="badge badge-light-danger">{{ __("Rejected from supervision") }}</span>
                            @else
                                <span class="badge badge-light-danger">{{ __("Rejected from physician") }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($Case["created_by"] != null)
                        <div class="col-lg-2 mb-3">
                            <div class="fw-bold text-gray-800 fs-7">{{ __("Created by") }}</div>
                            <div class="fw-semibold text-gray-600 fs-7">
                                @php
                                    $user = User::where('id', '=', $Case["created_by"]) ->value('name');
                                    $date = $Case["created_at"] ->format('d-m-Y');
                                    $time = $Case["created_at"] ->format('h:i');
                                    $NewDateTime = date('A', strtotime($Case["created_at"]));
                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                @endphp
                                <span>{{ $user }}</span><br>
                                <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                            </div>
                        </div>
                    @endif
                    @if ($Case["updated_by"] != null)
                        <div class="col-lg-2 mb-3">
                            <div class="fw-bold text-gray-800 fs-7">{{ __("Updated by") }}</div>
                            <div class="fw-semibold text-gray-600 fs-7">
                                @php
                                    $user = User::where('id', '=', $Case["updated_by"]) ->value('name');
                                    $date = $Case["updated_at"] ->format('d-m-Y');
                                    $time = $Case["updated_at"] ->format('h:i');
                                    $NewDateTime = date('A', strtotime($Case["updated_at"]));
                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                @endphp
                                <span>{{ $user }}</span><br>
                                <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-3 border border-gray-200 rounded p-4 mt-6">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Note") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7" style="white-space: pre-wrap">{{ $Case["note"] }}</div>
                </div>
            </div>

            <div class="separator separator-dashed separator-content border-gray-400 my-10"> <h4 class="mb-4 text-gray-600 fs-6">{{ __("Notes") }} </h4></div>

            <div class="card-body">
                @forelse($CaseNotes as $item)
                    <!--begin::Accordion-->
                    <div class="accordion" id="kt_accordion_{{ $item["id"] }}">
                        <div class="accordion-item my-2">
                            <h2 class="accordion-header" id="kt_accordion_{{ $item["id"] }}_header_{{ $item["id"] }}">
                                <button class="accordion-button fs-4 fw-semibold collapsed p-4" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#kt_accordion_{{ $item["id"] }}_body_{{ $item["id"] }}" aria-expanded="true"
                                        aria-controls="kt_accordion_{{ $item["id"] }}_body_{{ $item["id"] }}">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-35px overflow-hidden me-3">
                                            <div class="symbol-label">
                                                <img src="{{ asset("/uploads/avatars/".$item->User["avatar"]) }}"
                                                     alt="{{ $item->User["avatar"] . $item->User["id"] }}" class="w-100" />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column justify-content-start align-items-start">
                                            <span class="text-gray-800 fs-7">{{ $item->User["name"] }}<span class="badge badge-light-primary fs-9 mx-2">{{ $item->User->roles->value("name") }}</span></span>
                                            <span class="text-gray-600 fs-8 mt-2">
                                                @php
                                                    if ($item["created_at"] != null) {
                                                        $date = $item["created_at"] ->format('d-m-Y');
                                                        $time = $item["created_at"] ->format('h:i');
                                                        $NewDateTime = date('A', strtotime($item["created_at"]));
                                                        $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                                    }
                                                @endphp
                                                {{ $date ." ". $time ." ". $DateTimeType }}
                                            </span>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="kt_accordion_{{ $item["id"] }}_body_{{ $item["id"] }}" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_{{ $item["id"] }}_header_{{ $item["id"] }}" data-bs-parent="#kt_accordion_{{ $item["id"] }}">
                                <div class="accordion-body">
                                    <div class="mx-8">
                                        <div class="fw-semibold text-gray-800 fs-7">{{ $item["notes"] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Accordion-->
                @empty
                    <div class="text-center text-muted py-3">
                        {{ __("Sorry, no notes to display") }}
                    </div>
                @endforelse
            </div>

            <div class="separator separator-dashed separator-content border-gray-400 my-10"> <h4 class="mb-4 text-gray-600 fs-6">{{ __("Attachments") }} </h4></div>
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    @include('layouts.partials.table_search')
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                        {{--    ##===> FILTER COLUMNS <===##--}}
                        @include('layouts.partials.filter_columns_button')
                        @php
                            $columns = ['check' , '#', __('File'), __('Type') ];
                            $col_start = 1;
                        @endphp
                        @include('layouts.partials.filter_columns')

                        @if(auth()->user()->can('add_attachments'))
                            @include('layouts.partials.add_button_toggle')
                        @endif
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body">
                <div class="row ">
                    <!--begin::Table-->
                    @include('cases.case_attachments.partials.table')
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>

    @if(auth()->user()->can('view_attachments'))
        @if(count($CaseAttachment) > 0)
            @foreach($CaseAttachment as $item)
                <div class="modal bg-body fade" tabindex="-1" id="modal_video_{{ $item["id"] }}_view">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content shadow-none">
                            <!--begin::Modal header-->
                            <div class="modal-header d-flex justify-content-between align-items-center">
                                <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("View video") }}</h2>
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2 modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal" aria-label="cancel">
                                    <i class="ki-outline ki-cross fs-1"></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <video class="vide_{{ $item["id"] }}" height="100%" width="100%" controls>
                                    <source class="video_source" src="{{ "uploads/case_attachments/{$item->Cases->case_number}/{$item['file']}" }}" type="video/mp4">
                                </video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light modal_{{ $item["id"] }}_cancel" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="modal bg-body fade" tabindex="-1" id="modal_image_view">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content shadow-none">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("View image") }}</h2>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->

                    <div class="modal-body d-flex justify-content-center align-items-center w-100">
                        <div class="mw-50">
                            <img class="image_source w-100 p-4" src="" alt="image_view"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->can('reject_cases'))
        <!--begin::Modal - Add task-->
        <div class="modal fade" id="modal_reject" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Reject Case") }}</h2>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body px-5 my-7">
                        <!--begin::Form-->
                        <form id="modal_reject_form_add" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!--begin::Scroll-->
                            <div class="scroll-y px-5 px-lg-10 row" id="modal_add_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_add_header"
                                 data-kt-scroll-wrappers="#modal_add_scroll" data-kt-scroll-offset="400px">
                                <!--begin::Input group-->
                                <input type="hidden" name="id" id="CaseId"/>
                                <!--begin::Input group-->
                                <div class="col-lg-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Note") }}</label>
                                    <textarea type="text" name="notes" rows="4" class="form-control mb-3 mb-lg-0" placeholder="{{ __("Write something") }}"></textarea>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Actions-->
                            <div class="text-center pt-10">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="cancel">{{ __("Discard") }}</button>
                                <button type="submit" id="button_form_reject" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">{{ __("Submit") }}</span>
                                    <span class="indicator-progress">{{ __("Please wait...") }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Add task-->
    @endif

@endsection

@if(auth()->user()->can('add_attachments'))
    <!--begin::Modals actions-->
    @section('modal_add_content')
        <input type="hidden" name="case_id" value="{{ $case_id }}" />
        <div id="attachments_repeater">
            <div class="table-responsive">
                <table class="table gs-2 mb-0 fw-semibold">
                    <thead>
                    <tr class="fs-7 fw-bold text-start">
                        <th class="form-label required fw-semibold">{{ __('Attachments') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Description') }}</th>
                        <th class="form-label required fw-semibold">{{ __('Type') }}</th>
                    </tr>
                    </thead>

                    <tbody data-repeater-list="attachments_repeater">
                    <tr data-repeater-item="" data-kt-element="item">
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="file" name="file"
                                   accept=".pdf, .stl, image/*, video/*">
                        </td>
                        <td class="px-1">
                            <input class="form-control form-control-sm fs-7" type="text" name="description">
                        </td>
                        <td class="px-1">
                            <select class="form-select form-select-sm fs-7" data-kt-repeater="select2"
                                    name="type" data-dropdown-parent="#modal_add">
                                <option></option>
                                <option value="1">{{ __("Image") }}</option>
                                <option value="2">{{ __("Video") }}</option>
                                <option value="3">{{ __("Pdf") }}</option>
                                <option value="4">{{ __("Stl") }}</option>
                            </select>
                        </td>
                        <td class="px-1">
                            <a data-repeater-delete="" class="btn h-35px btn-sm btn-icon btn-flex btn-light-danger">
                                <i class="ki-solid ki-trash fs-5"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-3">
                <button type="button" href="javascript:;" data-repeater-create class="btn btn-sm btn-light-success fs-7" id="AddButton">
                    <i class="ki-duotone ki-plus fw-bold fs-5"></i>
                    {{ __("Add row") }}
                </button>
            </div>
        </div>
    @endsection
    <!--end::Modals actions-->
@endif

@section('script')
    @include('cases.case_attachments.partials.scripts')
    <script src="{{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}"></script>
    <script>
        $(function () {
            let RouteAcceptStatus = "/cases/cases_list/accept_status/";

            @if(auth()->user()->can('view_attachments'))
                @if(count($CaseAttachment) > 0)
                    @php $i = 0; @endphp
                    @foreach($CaseAttachment as $item)
                        $(document).on('click', '.modal_{{ $item["id"] }}_cancel', function() {
                                var video = $('video');
                                video.get({{ $i }}).pause();
                                video.get({{ $i }}).stop();
                        });
                        @php ++$i @endphp
                    @endforeach
                @endif

                $(document).on('click', '.image_view_btn', function () {
                            let Src = $(this).data('src');
                            $('.image_source').attr('src', Src);
                            $('#modal_image_view').modal('show');
                        });
            @endif

            @if(auth()->user()->can('accept_cases'))
            $(document).on('click', '.accept_button', function() {
                let Id = $(this).data('id');
                $.ajax({
                    url: RouteAcceptStatus + Id,
                    type: "GET",
                    success: function(data) {
                        if(data.success) {
                            Swal.fire("{{ __('success') }}", data.success, 'success');
                            location.reload();
                        } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON)
                        checkbox.prop('checked', !status);
                        if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                        else { Swal.fire("{{ __('error') }}", '{{ __("An error occurred while updating the status.") }}', 'error'); }
                    }
                });
            });
            @endif

            @if(auth()->user()->can('reject_cases'))
            $(document).on('click', '.reject_button', function () {
                let id = $(this).data('id');
                $('#CaseId').val(id).trigger("change");
                $('#modal_reject').modal('show');
            });

            $("#button_form_reject").click(function(e) {
                $(this).attr('data-kt-indicator', 'on');
                $("#button_form_reject").prop('disabled', true);
                e.preventDefault();
                var postData = new FormData($("#modal_reject_form_add")[0]);
                $.ajax({
                    url: "{{ route("cases.cases_list.reject_status") }}",
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        $('#modal_reject_form_add').trigger('reset');
                        $('#modal_reject_form_add .form-select').val(null).trigger('change');
                        $('#modal_reject').modal('hide');
                        $("#button_form_reject").removeAttr('data-kt-indicator').prop('disabled', false);

                        if(data.success) {
                            Swal.fire("{{ __('success') }}", data.success, 'success');
                            location.reload();
                        } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON)
                        $("#button_form_reject").removeAttr('data-kt-indicator').prop('disabled', false);
                        if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                        else if (xhr.responseJSON) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            $.each(errors, function (key, value) { errorMessage += '<span class="badge badge-light-danger mb-2">' + value + '</span><br>'; });
                            Swal.fire("{{ __('error') }}", errorMessage, 'error');
                        } else { Swal.fire("{{ __('error') }}", '{{ __("Something went wrong, please try again later") }}', 'error'); }
                    }
                });
            });
            @endif

            $('#attachments_repeater').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();

                    // Re-init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(){
                    // Init select2
                    $('[data-kt-repeater="select2"]').select2({
                        placeholder: '{{ __("Select option") }}'
                    });
                }
            })
        })
    </script>
@endsection

