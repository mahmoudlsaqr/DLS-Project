@php
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    $locale = app() ->getLocale();
    $User = Auth::user();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Orders"),
    "Title" => __("Order details")
])
@section('toolbar_buttons')
    @include("layouts.partials.back_button", ["link" => route("cases.orders.index"), "label" => __("Orders list")])
    @if (auth()->user()->can('accept_orders'))
        @if ((int)$User["receiving_orders"] == 1 and (int)$Order->status == 1)
            <a data-id="{{ $Order["id"] }}" class="btn btn-sm px-3 btn-primary accept_button">{{ __("Accept") }}</a>
        @endif
    @endif

    @if (auth()->user()->can('approve_orders'))
        @if (((int)$Order->status == 2 and Auth::id() == $Order["designer"]))
            <a data-id="{{ $Order["id"] }}" class="btn btn-sm px-3 btn-success approve_button">{{ __("Approve") }}</a>
        @endif
    @endif

    @if (auth()->user()->can('reject_orders'))
        @if ((int)$User["receiving_orders"] == 1 && in_array((int)$Order->status, [1, 2]))
            <a data-id="{{ $Order["id"] }}" class="btn btn-sm px-3 btn-danger reject_button">{{ __("Reject") }}</a>
        @endif
    @endif
@endsection
@section('content')
    <div class="card">
        <div class="card">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient name") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $Order["patient_name"] }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient gender") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">
                        @if($Order["gender"] == 0) {{ __("Female") }} @else {{ __("Male") }} @endif
                        </div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient age") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $Order["age"] }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Physician") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ User::where("id", $Order["created_by"])->value("name") }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Designer") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ User::where("id", $Order["designer"])->value("name") }}</div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Status") }}</div>
                        <div class="fw-bold text-gray-800 fs-8 mt-2">
                            @if($Order["status"] == 1)
                                <span class="badge badge-light-info">{{ __("Pending review") }}</span>
                            @elseif($Order["status"] == 2)
                                <span class="badge badge-light-success">{{ __("Sent to designer") }}</span>
                            @elseif($Order["status"] == 3)
                                <span class="badge badge-light-primary">{{ __("Approved") }}</span>
                            @else
                                <span class="badge badge-light-danger">{{ __("Rejected") }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($Order["created_by"] != null)
                        <div class="col-lg-2 mb-3">
                            <div class="fw-bold text-gray-800 fs-7">{{ __("Created by") }}</div>
                            <div class="fw-semibold text-gray-600 fs-7">
                                @php
                                    if ($Order["created_by"] != null) {
                                        $user = User::where('id', '=', $Order["created_by"]) ->value('name');
                                        $date = $Order["created_at"] ->format('d-m-Y');
                                        $time = $Order["created_at"] ->format('h:i');
                                        $NewDateTime = date('A', strtotime($Order["created_at"]));
                                        $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                    }
                                @endphp
                                <span>{{ $user }}</span><br>
                                <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                            </div>
                        </div>
                    @endif
                    @if ($Order["updated_by"] != null)
                        <div class="col-lg-2 mb-3">
                            <div class="fw-bold text-gray-800 fs-7">{{ __("Updated by") }}</div>
                            <div class="fw-semibold text-gray-600 fs-7">
                                @php
                                    if ($Order["updated_by"] != null) {
                                        $user = User::where('id', '=', $Order["updated_by"]) ->value('name');
                                        $date = $Order["updated_at"] ->format('d-m-Y');
                                        $time = $Order["updated_at"] ->format('h:i');
                                        $NewDateTime = date('A', strtotime($Order["updated_at"]));
                                        $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                    }
                                @endphp
                                <span>{{ $user }}</span><br>
                                <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2 mt-6">
                    <div class="col-lg-4 mb-3 border border-gray-200 rounded p-4">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Required in case") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7" style="white-space: pre-wrap">{{ $Order["required_in_case"] }}</div>
                    </div>
                    <div class="col-lg-4 mb-3 border border-gray-200 rounded p-4">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Treatment plan") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7" style="white-space: pre-wrap">{{ $Order["treatment_plan"] }}</div>
                    </div>
                    <div class="col-lg-4 mb-3 border border-gray-200 rounded p-4">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Patient expectations") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7" style="white-space: pre-wrap">{{ $Order["patient_expectations"] }}</div>
                    </div>
                </div>
            </div>

            <div class="separator separator-dashed separator-content border-gray-400 my-10"> <h4 class="mb-4 text-gray-600 fs-6">{{ __("Notes") }} </h4></div>

            <div class="card-body py-4">
                @forelse($OrderNotes as $item)
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
                    @include('cases.order_attachments.partials.table')
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>

    @if(auth()->user()->can('view_attachments'))
        @if(count($OrderAttachment) > 0)
            @foreach($OrderAttachment as $item)
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
                                    <source class="video_source" src="{{ "uploads/order_attachments/{$item["order_id"]}/{$item['file']}" }}" type="video/mp4">
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
    @if(auth()->user()->can('reject_orders'))
        <!--begin::Modal - Add task-->
        <div class="modal fade" id="modal_reject" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Reject Order") }}</h2>
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
                                <input type="hidden" name="id" id="OrderIdReject"/>
                                <!--begin::Input group-->
                                <div class="col-lg-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Note") }}</label>
                                    <textarea type="text" name="notes" rows="4" class="form-control mb-3 mb-lg-0" placeholder="{{ __("Write something") }}"></textarea>
                                </div>
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

    @if(auth()->user()->can('accept_orders'))
        <!--begin::Modal - Add task-->
        <div class="modal fade" id="modal_accept" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Accept Order") }}</h2>
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
                        <form id="modal_accept_form_add" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <!--begin::Scroll-->
                            <div class="scroll-y px-5 px-lg-10 row" id="modal_add_scroll" data-kt-scroll="true"
                                 data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_add_header"
                                 data-kt-scroll-wrappers="#modal_add_scroll" data-kt-scroll-offset="400px">
                                <!--begin::Input group-->
                                <input type="hidden" name="id" id="OrderIdAccept"/>
                                <!--begin::Input group-->
                                <!--begin::Input group-->
                                <div class="col-lg-12 mb-7">
                                    <label class="form-label required fs-7 fw-semibold">{{ __("Designer") }}</label>
                                    <select class="form-select form-select-sm" data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                                            data-allow-clear="false" data-kt-user-table-filter="designer" data-hide-search="false" name="designer"
                                            data-dropdown-parent="#modal_accept_form_add">
                                        <option></option>
                                        @foreach($users as $item)
                                            @if($item["role"] == "designer")
                                                <option value="{{ $item["id"] }}">{{ $item["name"] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <div class="col-lg-12 mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __("Note") }}</label>
                                    <textarea type="text" name="notes" rows="4" class="form-control mb-3 mb-lg-0" placeholder="{{ __("Write something") }}"></textarea>
                                </div>
                            </div>
                            <!--begin::Actions-->
                            <div class="text-center pt-10">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="cancel">{{ __("Discard") }}</button>
                                <button type="submit" id="button_form_accept" class="btn btn-primary" data-kt-users-modal-action="submit">
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
        <input type="hidden" name="order_id" value="{{ $Order["id"] }}" />
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
    @include('cases.order_attachments.partials.scripts')
    <script src="{{asset("assets/plugins/custom/formrepeater/formrepeater.bundle.js")}}"></script>
    <script>
        $(function () {
            let RouteAcceptOrder = "/cases/orders/approve_status/";

            @if(auth()->user()->can('view_attachments'))
                @if(count($OrderAttachment) > 0)
                    @php $i = 0; @endphp
                    @foreach($OrderAttachment as $item)
                        $(document).on('click', '.modal_{{ $item["id"] }}_cancel', function() {
                            let video = $('video');
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

            @if(auth()->user()->can('approve_orders'))
                $(document).on('click', '.approve_button', function () {
                    let ID = $(this).data('id');
                    $.ajax({
                        url: RouteAcceptOrder + ID,
                        type: "GET",
                        success: function(data) {
                            if(data.success) {
                                Swal.fire("{{ __('success') }}", data.success, 'success');
                                location.href = "/cases/orders/create_case/" + ID;
                            } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                        },
                        error: function(xhr) {
                            checkbox.prop('checked', !status);
                            if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                            else { Swal.fire("{{ __('error') }}", '{{ __("An error occurred while updating the status.") }}', 'error'); }
                        }
                    });
                });
            @endif

            @if(auth()->user()->can('accept_orders'))
                $(document).on('click', '.accept_button', function () {
                    let id = $(this).data('id');
                    $('#OrderIdAccept').val(id).trigger("change");
                    $('#modal_accept').modal('show');
                });

                $("#button_form_accept").click(function(e) {
                    $(this).attr('data-kt-indicator', 'on');
                    $("#button_form_accept").prop('disabled', true);
                    e.preventDefault();
                    let postData = new FormData($("#modal_accept_form_add")[0]);
                    $.ajax({
                        url: "{{ route("cases.orders.accept_status") }}",
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $('#modal_accept_form_add').trigger('reset');
                            $('#modal_accept_form_add .form-select').val(null).trigger('change');
                            $('#modal_accept').modal('hide');
                            $("#button_form_accept").removeAttr('data-kt-indicator').prop('disabled', false);
                            if(data.success) {
                                location.reload();
                                Swal.fire("{{ __('success') }}", data.success, 'success');
                            } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseJSON)
                            $("#button_form_accept").removeAttr('data-kt-indicator').prop('disabled', false);
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

            @if(auth()->user()->can('reject_orders'))
                $(document).on('click', '.reject_button', function () {
                    let id = $(this).data('id');
                    $('#OrderIdReject').val(id).trigger("change");
                    $('#modal_reject').modal('show');
                });

                $("#button_form_reject").click(function(e) {
                    $(this).attr('data-kt-indicator', 'on');
                    $("#button_form_reject").prop('disabled', true);
                    e.preventDefault();
                    let postData = new FormData($("#modal_reject_form_add")[0]);
                    $.ajax({
                        url: "{{ route("cases.orders.reject_status") }}",
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
                                location.reload();
                                Swal.fire("{{ __('success') }}", data.success, 'success');
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

