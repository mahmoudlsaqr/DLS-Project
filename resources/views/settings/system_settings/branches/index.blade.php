@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("System settings"),
    "Title" => __("Branches")
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
                                <div class="fs-6 text-dark fw-bold">{{ __("Filter Options") }}</div>
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
                        $columns = ['check' , '#', __('Branch name'), __('Branch code'), __('Region'), __('City'), __('Branch tin'), __('Com reg'), __('Branch target'), __('Created at'), __('Updated at'), __('Status') ];
                        $col_start = 1;
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_branches'))
                    @include('layouts.partials.add_button_href', ["href_btn"=>route("settings.system_settings.branches.create")])
                    @endif

                    @if(auth()->user()->can('remove_branches'))
                    @include('layouts.partials.delete_selected')
                    @endif

                </div>
            </div>

        </div>

        <div class="card-body">
            @include('settings.system_settings.branches.partials.table')
        </div>
    </div>
@endsection
@section("script")
    @include('settings.system_settings.branches.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection

