@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Technicians")
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
                                <div class="mb-4">
                                    <label class="required fs-7 mb-2">{{ __("Section") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_section input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false">
                                        <option></option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-4">
                                    <label class="required fs-7 mb-2">{{ __("Nationality") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_nationality input_filter" data-kt-select2="true"
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
                        $columns = ['check' , '#', __('Technician'), __('Section'), __('Nationality'), __('Email'), __('Mobile'), __('Monthly target'), __("Created by"), __("Updated by"), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_technicians'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_technicians'))
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
            @include('settings.data_settings.technicians.partials.table')
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
        <label class="required fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Section") }}</label>
        <select class="form-select form-select-sm fw-bold section_id" data-kt-select2="true" name="section_id" id="section_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Nationality") }}</label>
        <select class="form-select form-select-sm fw-bold nationality_id" data-kt-select2="true" name="nationality_id" id="nationality_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Email") }}</label>
        <input type="email" name="email" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Country code") }}</label>
        <select name="country_code" data-control="select2" data-hide-search="false" class="form-select form-select-sm" dir="ltr"
                data-dropdown-parent="#modal_add" data-placeholder="{{ __("Select option") }}">
            <option></option>
            @include('layouts.partials.country_codes')
        </select>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" class="form-control form-control-sm" placeholder="{{ __("Mobile") }}" />
    </div>
    <!--end::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Monthly target") }}</label>
        <input oninput="input_number(this)" type="text" name="monthly_target" class="form-control form-control-sm" placeholder="{{ __("Monthly target") }}" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Branches") }}</label>
        <select class="form-select form-select-sm branches" data-control="select2"
                name="branches[]" data-close-on-select="true" data-dropdown-parent="#modal_add"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Phase") }}</label>
        <select class="form-select form-select-sm phases" data-control="select2"
                name="phases[]" data-close-on-select="true" data-dropdown-parent="#modal_add"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Notes") }}</label>
        <textarea type="text" name="note" class="form-control form-control-sm"></textarea>
    </div>
    <!--end::Input group-->
    <div class="separator separator-dashed separator-content border-gray-400 my-8"><h4 class="w-200px text-gray-700 fs-7">{{ __("Technician user info") }}</h4></div>
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("User name") }}</label>
        <input type="text" name="username" class="form-control form-control-sm" placeholder="{{ __("User name") }}" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Password") }}</label>
        <input type="password" name="password" class="form-control form-control-sm" placeholder="{{ __("Password") }}" />
    </div>
    <!--end::Input group-->

@endsection
@section('modal_edit_content')
    <input type="hidden" name="user_id" id="userId" class="form-control form-control-sm" />
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" id="name_ar_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("English name") }}</label>
        <input type="text" name="name_en" id="name_en_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Section") }}</label>
        <select class="form-select form-select-sm fw-bold section_id" data-kt-select2="true" name="section_id" id="section_id_edit"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($sections as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Nationality") }}</label>
        <select class="form-select form-select-sm fw-bold nationality_id" data-kt-select2="true" name="nationality_id" id="nationality_id_edit"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($nationalities as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["Nat_Ar"] : $item["Nat_En"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Email") }}</label>
        <input type="email" name="email" id="email_edit" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Country code") }}</label>
        <select name="country_code" data-control="select2" data-hide-search="false" id="country_code_input_edit" class="form-select form-select-sm" dir="ltr"
                data-dropdown-parent="#modal_edit" data-placeholder="{{ __("Select option") }}">
            <option></option>
            @include('layouts.partials.country_codes')
        </select>
    </div>
    <div class="col-lg-6 mb-4">
        <label class="required fs-7 mb-2">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" id="mobile_edit" class="form-control form-control-sm" placeholder="{{ __("Mobile") }}" />
    </div>
    <!--end::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="fs-7 mb-2">{{ __("Monthly target") }}</label>
        <input oninput="input_number(this)" type="text" name="monthly_target" id="monthly_target_edit" class="form-control form-control-sm" placeholder="{{ __("Monthly target") }}" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Branches") }}</label>
        <select class="form-select form-select-sm branches" data-control="select2"
                name="branches[]" id="branches_edit" data-close-on-select="true" data-dropdown-parent="#modal_edit"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
            @foreach($branches as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Phases") }}</label>
        <select class="form-select form-select-sm phases" data-control="select2"
                name="phases[]" id="phases_edit" data-close-on-select="true" data-dropdown-parent="#modal_edit"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true"
                multiple="multiple">
            @foreach($phases as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="required fs-7 mb-2">{{ __("Notes") }}</label>
        <textarea type="text" name="note" id="note_edit" class="form-control form-control-sm"></textarea>
    </div>
    <!--end::Input group-->
@endsection
<!--end::Modals actions-->

@section("script")
    @include('settings.data_settings.technicians.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

