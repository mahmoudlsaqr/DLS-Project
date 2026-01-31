@php
    $locale = app() ->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Orders"),
    "Title" => __("Orders list")
])
@section('content')
    <div class="card">
        <!--begin::Card-->
        <div class="card">
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
                        <!--begin::Filter-->
                        <div>
                            <button type="button" class="btn h-35px btn-sm btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-static="true" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
                                {{ __("Filter") }}</button>
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <div class="px-7 py-5">
                                    <div class="fs-7 text-dark fw-bold">{{ __("Filter Options") }}</div>
                                </div>
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                    <!--begin::Input-->
                                    <div class="mb-10">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Status") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_status input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false">
                                            <option></option>
                                            <option value="1">{{ __("Pending review") }}</option>
                                            <option value="2">{{ __("Sent to designer") }}</option>
                                            <option value="3">{{ __("Approved") }}</option>
                                            <option value="4">{{ __("Rejected") }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Input-->
                                    <div class="mb-10">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Physician") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_physician input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false">
                                            <option></option>
                                            @foreach($users as $item)
                                                @if($item["role"] == "physician")
                                                    <option value="{{ $item["id"] }}">{{ $item["name"] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Actions-->
                                    @include("layouts.partials.table_filter_buttons_actions")
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                        </div>
                        <!--end::Filter-->

                        {{--    ##===> FILTER COLUMNS <===##--}}
                        @include('layouts.partials.filter_columns_button')
                        @php
                            $columns = ['check' , '#', __('Physician'), __('Designer'), __('Patient name'), __('Required in case'), __('Status'), __('Created at'), __('Updated at') ];
                            $col_start = 1;
                        @endphp
                        @include('layouts.partials.filter_columns')

                        @if(auth()->user()->can('create_orders'))
                            @include('layouts.partials.add_button_href', ["href_btn" => route("cases.orders.create")])
                        @endif

                        @if(auth()->user()->can('remove_orders'))
                            @include('layouts.partials.delete_selected')
                        @endif
                    </div>
                    <!--end::Toolbar-->

                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                @include('cases.orders.partials.table')
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>

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
                                            data-dropdown-parent="#modal_accept">
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

@section('script')
    @include('cases.orders.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

