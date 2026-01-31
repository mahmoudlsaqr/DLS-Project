@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Services categories")
])
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                @include('layouts.partials.table_search')
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end gap-2" data-kt-user-table-toolbar="base">
                    <div>
                        <button type="button" class="btn btn-sm btn-info" data-kt-menu-trigger="click" data-kt-menu-static="true" data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
                            {{ __("Filter") }}</button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-7 text-dark fw-bold">{{ __("Filter Options") }}</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="mb-10">
                                    <label class="form-label fs-7 fw-semibold">{{ __("Status") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_status input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                        <option></option>
                                        <option value="true">{{ __("Enabled") }}</option>
                                        <option value="false">{{ __("Disabled") }}</option>
                                    </select>
                                </div>
                                @include("layouts.partials.table_filter_buttons_actions")
                            </div>
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = ['check' , '#', __('Name ar'), __('Name en'), __('Category code'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_services_categories'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_services_categories'))
                        @include('layouts.partials.delete_selected')
                    @endif
                </div>

            </div>
        </div>
        <div class="card-body">
            @include('settings.data_settings.services_categories.partials.table')
        </div>
    </div>
@endsection

<!--begin::Modals actions-->
@section('modal_add_content')
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Category code") }}</label>
        <input type="text" name="category_code" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->

@endsection
@section('modal_edit_content')
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" id="name_ar_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" id="name_en_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fw-semibold fs-7 mb-2">{{ __("Category code") }}</label>
        <input type="text" name="category_code" id="category_code_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
@endsection
<!--end::Modals actions-->

@section("script")
    @include('settings.data_settings.services_categories.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {

        });

    </script>
@endsection

