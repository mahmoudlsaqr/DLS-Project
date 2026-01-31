@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Doctors"),
    "Title" => __("Doctors services details")
])
@section("toolbar_buttons")
    @if(auth()->user()->can('sync services_details_doctors'))
    <button type="submit" id="services_sync_button" class="btn btn-sm btn-warning" data-kt-users-modal-action="submit">
        <span class="indicator-label">{{ __("Sync") }}</span>
        <span class="indicator-progress">{{ __("Please wait...") }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
    @endif
    @if(auth()->user()->can('reset services_details_doctors'))
    <button type="submit" id="services_reset_button" class="btn btn-sm btn-danger" data-kt-users-modal-action="submit">
        <span class="indicator-label">{{ __("Reset") }}</span>
        <span class="indicator-progress">{{ __("Please wait...") }}
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
    @endif
@endsection
@section('content')
    @if(auth()->user()->can('update details_doctors'))
    <div class="card mb-6">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title">
                <h5 class="text-gray-600">{{ __("Update doctor services") }}</h5>
            </div>
            <div class="card-toolbar">
                <div class="d-flex gap-2">
                    <span class="text-gray-600 fs-6">{{ __("Doctor") }} :</span>
                    <span class="text-primary fs-6">{{ ($locale == "ar")? $doctor["name_ar"] : $doctor["name_en"] }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="form_update" autocomplete="off">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 mb-4">
                            <label class="form-label fs-7">{{ __('Services search') }}</label>
                            <select class="form-select form-select-sm service_search" data-control="select2" name="service_search" data-placeholder="{{ __("Select option") }}">
                                <option></option>
                                <option value="1" selected>{{ __("All") }}</option>
                                <option value="2">{{ __("Category") }}</option>
                                <option value="3">{{ __("Service") }}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-4 div_service_category">
                            <label class="form-label fs-7">{{ __('Category') }}</label>
                            <select class="form-select form-select-sm service_category" data-control="select2" name="service_category" data-placeholder="{{ __("Select option") }}">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-4 div_service">
                            <label class="form-label fs-7">{{ __('Service') }}</label>
                            <select class="form-select form-select-sm service" data-control="select2" name="service" data-placeholder="{{ __("Select option") }}">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-lg-2 mb-4 div_price">
                            <label class="form-label fs-7">{{ __('Price') }}</label>
                            <input type="text" oninput="input_float(this);" class="form-control form-control-sm service_price" name="service_price" />
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label class="form-label fs-7">{{ __('Discount') }}</label>
                            <input type="text" oninput="input_float(this); max_min_val(this, 100, 1)" class="form-control form-control-sm service_discount" name="service_discount" />
                        </div>
                        <div class="col-lg-2 mb-4 div_discount_value">
                            <label class="form-label fs-7">{{ __('Discount value') }}</label>
                            <input type="text" oninput="input_float(this);" class="form-control form-control-sm service_discount_value" />
                        </div>
                        <div class="col-lg-12">
                            <button id="button_update" type="submit" class="btn btn-sm btn-primary">
                                <span class="indicator-label">{{__("Save")}}</span>
                                <span class="indicator-progress">{{__("Please wait")}}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
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
                        <button type="button" class="btn btn-sm btn-info" data-kt-menu-trigger="click" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
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
                                <!--begin::Input group-->
                                <div class="mb-4">
                                    <label class="form-label fs-7">{{ __("Service") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_service input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false">
                                        <option></option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-4">
                                    <label class="form-label fs-7">{{ __("Category") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_category input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false">
                                        <option></option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-semibold">{{ __("Status") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_status input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                        <option></option>
                                        <option value="true">{{ __("Enabled") }}</option>
                                        <option value="false">{{ __("Disabled") }}</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                @include("layouts.partials.table_filter_buttons_actions")
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                    </div>
                    <!--end::Filter-->

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = ['check' , '#', __('Service number'), __('Service'), __('Service code'), __('Service price'), __('Service discount'), __('Price'), __('Category'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('remove details_doctors'))
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
            @include('settings.data_settings.doctors.details.partials.table')
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection

@section("script")
    @include('settings.data_settings.doctors.details.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

