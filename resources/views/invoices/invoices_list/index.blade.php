@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Invoices"),
    "Title" => __("Invoices list")
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
                        <button type="button" class="btn btn-sm btn-info" data-kt-menu-trigger="click"
                                data-kt-menu-static="true"
                                data-kt-menu-placement='bottom-{{ ($locale == "ar")? "start" : "end" }}'>
                            {{ __("Filter") }}</button>
                        <div class="menu menu-sub menu-sub-dropdown w-500px w-md-500px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-6 text-dark fw-bold">{{ __("Filter Options") }}</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div class="row">
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7">{{ __("Branch") }}</label>
                                        <select class="form-select form-select-sm f_branch input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-7">{{ __("Client") }}</label>
                                        <select class="form-select form-select-sm f_client input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-6">
                                        <label class="form-label fs-7">{{ __("Doctor") }}</label>
                                        <select class="form-select form-select-sm f_doctor input_filter" data-control="select2"
                                                data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-6">{{ __('Start date') }}</label>
                                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr f_date_start input_filter" placeholder="DD-MM-YYYY" />
                                    </div>
                                    <div class="col-6 mb-6">
                                        <label class="form-label fs-6">{{ __('End date') }}</label>
                                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr f_date_end input_filter" placeholder="DD-MM-YYYY" />
                                    </div>
                                </div>
                                @include("layouts.partials.table_filter_buttons_actions")
                            </div>
                        </div>
                    </div>

                    @include('layouts.partials.filter_columns_button')
                    @php
                        $columns = [ '#', __('Date'), __('Branch'), __('Client'), __('Doctor'), __('Price before discount'), __('Total discount value'),
                         __('Total net'), __('Total tax value'), __('Total'), __('Total paid'), __('Total residual') ];
                        $col_start = 0;
                    @endphp
                    @include('layouts.partials.filter_columns')
                </div>

            </div>
        </div>

        <div class="card-body">
            @include('invoices.invoices_list.partials.table')
        </div>
    </div>

    <div class="modal pay_invoice_modal fade" id="payInvoiceModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-800px">
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
                <form id="pay_invoice_form">
                    @csrf
                    <input type="hidden" id="invId" name="id">
                    <div class="modal-body px-4">
                        <div class="scroll-y px-4 row" id="modal_add_scroll" data-kt-scroll="true"
                             data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_add_header"
                             data-kt-scroll-wrappers="#modal_add_scroll" data-kt-scroll-offset="400px">
                            <!--begin::Input group-->
                            <div class="col-6 mb-6">
                                <label class="form-label required fs-7">{{ __("Payment type") }}</label>
                                <select class="form-select form-select-sm payment_type" data-control="select2" id="paymentTypeData"
                                        name="payment_type" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                    <option></option>
                                    <option value="1" selected>{{ __("Cash") }}</option>
                                    <option value="2">{{ __("Transfer") }}</option>
                                    <option value="3">{{ __("Check") }}</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-6 mb-6">
                                <label class="form-label required fs-7">{{ __("Amount") }}</label>
                                <input type="text" class="form-control form-control-sm" name="amount" id="amountData" placeholder="{{ __('Amount') }}">
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-6 mb-6">
                                <label class="form-label fs-7">{{ __("Reference") }}</label>
                                <input type="text" class="form-control form-control-sm" name="reference" id="referenceData" placeholder="{{ __('Reference') }}">
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-6 mb-6">
                                <label class="form-label fs-7">{{ __("Note") }}</label>
                                <input type="text" class="form-control form-control-sm" name="note" id="noteData" placeholder="{{ __('Note') }}">
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                    <div class="modal-footer p-2 pt-0">
                        <button type="reset" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="cancel">{{ __("Discard") }}</button>
                        <button type="submit" id="payment_button" class="btn btn-primary btn-sm payment_button">
                            <span class="indicator-label">{{ __("Pay") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section("script")
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    @include('invoices.invoices_list.partials.scripts')
    @include('layouts.partials.reset_filter_button')
    @include('layouts.partials.check_all')

    <script>
        $(function () {
            let branch = $('.f_branch');
            let client = $('.f_client');
            let doctor = $('.f_doctor');

            @if (Auth::user()->hasRole('technician'))
            SelectGetData(branch, "/get_technician_branches/{{ Auth::id() }}", false);
            @else
            SelectGetData(branch, "{{ route( 'get_branches_user' ) }}", false);
            @endif

            branch.on('change', function () {
                let id = $(this).val();
                if (id) {
                    doctor.empty();
                    SelectGetData(doctor, `/get_branch_doctors/${id}`, false);
                    SelectGetData(client, `/get_branch_clients/${id}`, false);
                }
            });

            client.on('change', function () {
                let id = $(this).val();
                if (id) {
                    doctor.empty();
                    SelectGetData(doctor, "/get_client_doctors/" + id, false, "{{ $request->doctor_id ?? "" }}");
                }
            });

            SelectGetData(client, "{{ route( 'get_clients' ) }}", false);
            SelectGetData(doctor, "{{ route( 'get_doctors' ) }}", false);

            $(document).on('click', '.print_button', function(e) {
                let id = $(this).data('id');
                $.ajax({
                    url: `/invoices/issue_invoice/print/${id}`,
                    type: "GET",
                    success: function (data) {
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            removeInline: false,
                            printDelay: 500,
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr["responseJSON"])
                        $("#issue_button").removeAttr('data-kt-indicator').prop('disabled', false);
                        $('.input_filter').prop('readonly', false);
                        if (xhr["responseJSON"].error) { Swal.fire("{{ __('error') }}", '<span class="badge badge-light-danger mb-2">' + xhr["responseJSON"].error + '</span><br>', 'error'); }
                        else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                    }
                });
            });
        })
    </script>
@endsection

