@php
    use Illuminate\Support\Facades\Auth;

    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders"),
    "Title" => __("Work orders management")
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
                        <div class="menu menu-sub menu-sub-dropdown w-600px w-md-600px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-6 text-dark fw-bold">{{ __("Filter Options") }}</div>
                            </div>
                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->
                            <!--begin::Content-->
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Branch") }}</label>
                                        <select class="form-select form-select-sm f_branch input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Client") }}</label>
                                        <select class="form-select form-select-sm f_client input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Doctor") }}</label>
                                        <select class="form-select form-select-sm f_doctor input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input Status-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Status") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_status input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                            <option></option>
                                            <option value="true">{{ __("Enabled") }}</option>
                                            <option value="false">{{ __("Disabled") }}</option>
                                        </select>
                                    </div>
                                    <!--end::Input Status-->
                                </div>
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Color") }}</label>
                                        <select class="form-select form-select-sm f_color input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input Status-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Phase") }}</label>
                                        <select class="form-select form-select-sm fw-bold f_phase input_filter" data-kt-select2="true"
                                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
                                            <option></option>
                                        </select>
                                    </div>
                                    <!--end::Input Status-->
                                </div>
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-12 mb-6">
                                        <label class="form-label fs-7 fw-semibold">{{ __("Service") }}</label>
                                        <select class="form-select form-select-sm f_service input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="row">
                                    <!--begin::Input Status-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-6 fw-semibold">{{ __('Start date') }}</label>
                                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr f_date_start input_filter" placeholder="DD-MM-YYYY" />
                                    </div>
                                    <!--end::Input Status-->
                                    <!--begin::Input Status-->
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-6 fw-semibold">{{ __('End date') }}</label>
                                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr f_date_end input_filter" placeholder="DD-MM-YYYY" />
                                    </div>
                                    <!--end::Input Status-->
                                </div>
                                <!--begin::Actions-->
                                @include("layouts.partials.table_filter_buttons_actions")
                                <!--end::Actions-->
                            </div>
                            <!--end::Content-->
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = ['check', '#', __('Number'), __('Date'), __('Client'), __('Doctor'), __('Patient name'), __('File number'), __('Service'),
                         __('Color'), __('Quantity'), __('Total'), __('Branch'), __('Phase'), __('Created at'), __('Status') ];
                        $col_start = 1;
//                        if (!auth()->user()->can('show prices_work_orders_management')) {
//                            $columns = array_pop($columns, __('Total'));
//                        }
                    @endphp
                    @include('layouts.partials.filter_columns')

                    @if(auth()->user()->can('create_work_orders_management'))
                        @include('layouts.partials.add_button_href', ["href_btn"=>route("work_orders.work_orders_management.create")])
                    @endif

                    @if(auth()->user()->can('remove_work_orders_management'))
                        @include('layouts.partials.delete_selected')
                    @endif

                </div>
            </div>
        </div>
        <div class="card-body">
            @include('work_orders.work_orders_management.partials.table')
        </div>
    </div>

    <div class="modal fade" id="logDetailsModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-700px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2 class="modal-title fs-4" id="exampleModalLabel">{{ __("Stages log details") }}</h2>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-danger ms-2" data-bs-dismiss="modal" aria-label="cancel">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <div class="modal-body px-4">
                    <p id="log-details-content">{{ __("Loading...") }}</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("modal_submit_content")
    <input type="hidden" name="id" id="workOrderId">
    <!--begin::Input Status-->
    <div class="col-12">
        <label class="form-label fs-7 fw-semibold">{{ __("Phases") }}</label>
        <select class="form-select form-select-sm fw-bold phases" data-kt-select2="true" name="phase_id"
                data-placeholder="{{ __("Select option") }}" data-allow-clear="false" data-kt-user-table-filter="role" data-hide-search="false">
            <option></option>
        </select>
    </div>
    <!--end::Input Status-->
@endsection

@section("script")
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    @include('work_orders.work_orders_management.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {
            let branch = $('.branch_id, .f_branch');
            let doctor = $('.doctor_id, .f_doctor');
            let service = $('.service_id, .f_service');
            let client = $('.f_client');
            let phase = $('.f_phase');
            let phases = $('.phases');
            let color = $('.color_id, .f_color');

            @if (Auth::user()->hasRole('technician'))
                SelectGetData(branch, "/get_technician_branches/{{ Auth::id() }}", false);
                SelectGetData(phases, "/get_technician_phases/{{ Auth::id() }}", false);
            @else
                SelectGetData(branch, "{{ route("get_branches_user") }}", false);
                SelectGetData(phases, "{{ route( 'get_phases_without_entry') }}", false);
            @endif

            branch.on('change', function () {
                let id = $(this).val();
                if (id) {
                    doctor.empty();
                    SelectGetData(doctor, `/get_branch_doctors/${id}`, false);
                    SelectGetData(client, `/get_branch_clients/${id}`, false);
                }
            });

            doctor.on('change', function () {
                let id = $(this).val();
                if (id) {
                    SelectGetData(service, `/get_doctor_services/${id}`, false);
                }
            });

            SelectGetData(client, "{{ route( 'get_clients' ) }}", false);
            SelectGetData(color, "{{ route( 'get_colors' ) }}", false);
            SelectGetData(phase, "{{ route( 'get_phases' ) }}", false);
        })
    </script>
@endsection

