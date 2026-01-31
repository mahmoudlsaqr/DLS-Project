@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.landing', [
    "Title" => __("Search for work order")
])
@section('toolbar_buttons')
    @if (Auth::check())
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Dashboard") }}</a>
    @else
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Sign in") }}</a>
    @endif
@endsection
@section('content')

    <div class="card my-8 p-0">
        <div class="card-header p-4">
            <div class="d-flex justify-content-center align-items-center w-100">
                <input class="form-control form-select-sm w-100" type="text" name="search" id="searchInput" placeholder="{{ __('Enter work order number') }}" />
            </div>
        </div>
    </div>
    <div class="card my-8 p-0">
        <div class="card-body p-0 py-4">
            <table id="data-table" class="table table-row-bordered align-middle fs-7 gy-4 p-0">
                <thead>
                <tr class="text-start text-gray-800 fw-bold text-uppercase">
                    <th class="w-25px">#</th>
                    <th class="w-100px">{{ __("Work order number") }}</th>
                    <th class="w-100px">{{ __("File number") }}</th>
                    <th class="w-250px">{{ __("Patient name") }}</th>
                    <th class="w-250px">{{ __("Doctor") }}</th>
                    <th class="w-200px">{{ __("Completion percent") }}</th>
                    <th class="w-200px">{{ __("Date") }}</th>
                    <th class="w-200px">{{ __("Time") }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="card my-8 p-0">
        <div class="card-body p-0 py-4">
            <table id="data-table_log" class="table table-row-bordered table-striped align-middle fs-7 gy-4 p-0">
                <thead>
                <tr class="text-start text-gray-800 fw-bold text-uppercase">
                    <th class="w-25px">#</th>
                    <th class="w-200px">{{ __("Phase") }}</th>
                    <th class="w-200px">{{ __("Date") }}</th>
                    <th class="w-200px">{{ __("Time") }}</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            const searchInput = $("#searchInput");
            const dataTable = $('#data-table').DataTable({
                language: { url: `/assets/plugins/custom/datatables/{{ $locale }}.json` },
                pageLength: 50,
                lengthMenu: [5, 10, 20, 30, 50, 100, 300, 500],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'work_order_number', name: 'work_order_number' },
                    { data: 'file_number', name: 'file_number' },
                    { data: 'patient_name', name: 'patient_name' },
                    { data: 'doctor', name: 'doctor' },
                    { data: 'completion_percent', name: 'completion_percent' },
                    { data: 'date', name: 'date' },
                    { data: 'time', name: 'time' },
                ]
            });

            const dataTableLog = $('#data-table_log').DataTable({
                language: { url: `/assets/plugins/custom/datatables/{{ $locale }}.json` },
                pageLength: 50,
                lengthMenu: [5, 10, 20, 30, 50, 100, 300, 500],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'phase', name: 'phase' },
                    { data: 'date', name: 'date' },
                    { data: 'time', name: 'time' },
                ]
            });

            searchInput.on('change', function () {
                const number = $(this).val();
                if (number) {
                    dataTable.ajax.url(`/landing/work_orders/search/${number}`).load();
                    dataTableLog.ajax.url(`/landing/work_orders/search_log_phases/${number}`).load();
                } else {
                    dataTable.draw();
                    dataTableLog.draw();
                }
            });
        });
    </script>
@endsection


