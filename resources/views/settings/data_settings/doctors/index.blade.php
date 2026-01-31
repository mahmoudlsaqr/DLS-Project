@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Data settings"),
    "Title" => __("Doctors")
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
                                    <label class="form-label required fs-7">{{ __("Employer") }}</label>
                                    <select class="form-select form-select-sm fw-bold f_client input_filter" data-kt-select2="true"
                                            data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false">
                                        <option></option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-4">
                                    <label class="form-label required fs-7">{{ __("Nationality") }}</label>
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
                        $columns = ['check' , '#', __('Doctors'), __('Employer'), __('Nationality'), __('Email'), __('Mobile'), __("Created by"), __("Updated by"), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_doctors'))
                        @include('layouts.partials.add_button_toggle')
                    @endif
                    @if(auth()->user()->can('remove_doctors'))
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
            @include('settings.data_settings.doctors.partials.table')
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
        <label class="form-label required fs-7">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("English name") }}</label>
        <input type="text" name="name_en" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Employer") }}</label>
        <select class="form-select form-select-sm fw-bold client_id" data-kt-select2="true" name="client_id" id="client_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Nationality") }}</label>
        <select class="form-select form-select-sm fw-bold nationality_id" data-kt-select2="true" name="nationality_id" id="nationality_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_add">
            <option></option>
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Email") }}</label>
        <input type="email" name="email" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" class="form-control form-control-sm" placeholder="0500000000" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label required fs-7">{{ __("Branch") }}</label>
        <select class="form-select form-select-sm branch_id" data-control="select2"
                name="branch_id" data-close-on-select="true" data-dropdown-parent="#modal_add"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label fs-7">{{ __("Notes") }}</label>
        <textarea type="text" name="note" class="form-control form-control-sm"></textarea>
    </div>
@endsection
@section('modal_edit_content')
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Arabic name") }}</label>
        <input type="text" name="name_ar" id="name_ar_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("English name") }}</label>
        <input type="text" name="name_en" id="name_en_edit" class="form-control form-control-sm" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Employer") }}</label>
        <select class="form-select form-select-sm fw-bold client_id" data-kt-select2="true" name="client_id" id="client_id_edit"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-hide-search="false" data-dropdown-parent="#modal_edit">
            <option></option>
            @foreach($clients as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Nationality") }}</label>
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
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Email") }}</label>
        <input type="email" name="email" id="email_edit" class="form-control form-control-sm" placeholder="example@domain.com" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-6 mb-4">
        <label class="form-label required fs-7">{{ __("Mobile") }}</label>
        <input oninput="input_number(this)" type="text" name="mobile" id="mobile_edit" class="form-control form-control-sm" placeholder="0500000000" />
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label required fs-7">{{ __("Branch") }}</label>
        <select class="form-select form-select-sm branch_id" data-control="select2"
                name="branch_id" id="branch_id_edit" data-close-on-select="true" data-dropdown-parent="#modal_edit"
                data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
            @foreach($branches as $item)
                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
            @endforeach
        </select>
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="col-lg-12 mb-4">
        <label class="form-label fs-7">{{ __("Notes") }}</label>
        <textarea type="text" name="note" id="note_edit" class="form-control form-control-sm"></textarea>
    </div>
    <!--end::Input group-->
@endsection
<!--end::Modals actions-->

@section("script")
    @include('settings.data_settings.doctors.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

