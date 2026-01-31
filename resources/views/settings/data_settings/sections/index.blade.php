@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Sections")
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
                        $columns = ['check' , '#', __('Name ar'), __('Name en'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_sections'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_sections'))
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
            @include('settings.data_settings.sections.partials.table')
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
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
@endsection
<!--end::Modals actions-->

@section("script")
    @include('settings.data_settings.sections.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {

        });

    </script>
@endsection

