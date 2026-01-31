
@php
    $locale = app() ->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Cases"),
    "Title" => __("Receipt vouchers")
])
@section('toolbar_buttons')

@endsection
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

                        {{-- ##===> FILTER COLUMNS <===## --}}
                        @include('layouts.partials.filter_columns_button')
                        @php
                            $columns = ['check', '#', __('Case number'), __('Clinic name'), __('Amount'), __('Description'), __('Created at'), __('Updated at') ];
                            $col_start = 1;
                        @endphp
                        @include('layouts.partials.filter_columns')

                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                @include('cases.receipt_voucher.partials.table')
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
@endsection

@if (auth()->user()->can('update_receipt_vouchers'))
    @section('modal_edit_content')
        <!--begin::Input group-->
        <div class="col-lg-6 mb-3">
            <label class="form-label required fw-semibold fs-7">{{ __("Clinic name") }}</label>
            <input type="text" name="clinic_name" id="clinic_name_edit" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Clinic name") }}" />
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="col-lg-6 mb-3">
            <label class="form-label required fw-semibold fs-7">{{ __("Amount") }}</label>
            <input type="text" oninput="input_float(this)" id="amount_edit" name="amount" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="{{ __("Amount") }}" />
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="col-lg-12 mb-3">
            <label class="form-label fw-semibold fs-7">{{ __("Attachment") }}</label>
            <input class="form-control form-control-sm mb-3 mb-lg-0" type="file" name="file" accept=".pdf, image/*" />
            <span class="text-info d-block fw-bold mt-4 fs-7"> {{ __('leave it blank if you want to keep the old attachment') }} </span>
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="col-lg-12 mb-3">
            <label class="form-label required fw-semibold fs-7">{{ __("Description") }}</label>
            <textarea type="text" name="description" rows="4" id="description_edit" class="form-control fs-7" placeholder="{{ __("Write something") }}">{{ __("Transparent orthodontic case") }}</textarea>
        </div>
        <!--end::Input group-->
    @endsection
@endif

@section('script')
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    @include('cases.receipt_voucher.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

