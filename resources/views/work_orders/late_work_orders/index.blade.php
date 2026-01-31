@php
    $locale = app() ->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Dashboard"),
    "Title" => __("Late work orders")
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
                        <div class="menu menu-sub menu-sub-dropdown w-600px w-md-300px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-6 text-dark fw-bold">{{ __("Filter Options") }}</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="row">
                                    <div class="col-12 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Phase") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_phase input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("User") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_user input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                @include("layouts.partials.table_filter_buttons_actions")
                            </div>
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = [
                            '#',
                            __("Work order number"),
                            __("Work order date"),
                            __("Phase"),
                            __("Client"),
                            __("Doctor"),
                            __("Patient name"),
                            __("File number"),
                            __("Branch"),
                            __("Last update"),
                        ];
                        $col_start = 0;
                    @endphp
                    @include('layouts.partials.filter_columns')
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('work_orders.late_work_orders.partials.table')
        </div>
    </div>
@endsection
@if(auth()->user()->can('update phase_work_orders_management'))
    @section("modal_submit_content")
        <input type="hidden" name="id" id="workOrderId">
        <div class="col-12">
            <label class="form-label fs-7 fw-semibold">{{ __("Phases") }}</label>
            <select class="form-select form-select-sm fw-bold phases" data-kt-select2="true" name="phase_id"
                    data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                <option></option>
            </select>
        </div>
    @endsection
@endif
@section('script')
    @include('work_orders.late_work_orders.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')
@endsection
