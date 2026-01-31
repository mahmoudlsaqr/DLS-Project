@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Special offers")
])
@section('content')
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
                        <button type="button" class="btn btn-sm btn-info" data-kt-menu-trigger="click" data-kt-menu-static="true" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
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
                                <div class="mb-10">
                                    <label class="form-label fs-7">{{ __("Status") }}</label>
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
                        $columns = ['check' , '#', __('Offer name'), __('Client'), __('Discount'), __('Start date'), __('End date') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_special_offers'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_special_offers'))
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
            @include('settings.data_settings.special_offers.partials.table')
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection

<!--begin::Modals actions-->
@section('modal_add_content')
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label required fs-7">{{ __("Offer name") }}</label>
        <input type="text" name="offer_name" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Discount") }}</label>
        <input type="text" oninput="input_float(this); max_min_val(this, 100, 1)" name="discount" id="discount" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Doctor") }}</label>
        <select class="form-select form-select-sm fw-bold doctor_id" data-kt-select2="true" name="doctor_id" id="doctor_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Start date") }}</label>
        <input type="text" class="form-control form-control-sm fc-flatpickr start_date" name="start_date" placeholder="DD-MM-YYYY"/>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("End date") }}</label>
        <input type="text" class="form-control form-control-sm fc-flatpickr end_date" name="end_date" placeholder="DD-MM-YYYY"/>
    </div>
    <!--end::Input group-->
@endsection
@section('modal_edit_content')
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label required fs-7">{{ __("Offer name") }}</label>
        <input type="text" name="offer_name" id="offer_name_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Discount") }}</label>
        <input type="text" oninput="input_float(this); max_min_val(this, 100, 1)" name="discount" id="discount_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Doctor") }}</label>
        <select class="form-select form-select-sm doctor_id" data-kt-select2="true" name="doctor_id" id="doctor_id_edit"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($doctors as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Start date") }}</label>
        <input type="text" class="form-control form-control-sm fc-flatpickr start_date_edit" name="start_date" placeholder="DD-MM-YYYY"/>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("End date") }}</label>
        <input type="text" class="form-control form-control-sm fc-flatpickr end_date_edit" name="end_date" placeholder="DD-MM-YYYY"/>
    </div>
    <!--end::Input group-->
@endsection
<!--end::Modals actions-->

@section("script")
    @include('settings.data_settings.special_offers.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {

        });

    </script>
@endsection

