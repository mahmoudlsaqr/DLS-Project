@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Invoices"),
    "Title" => __("Show invoice")
])
@section("toolbar_buttons")
    @if(auth()->user()->can('print_invoices'))
    <button type="button" class="btn btn-sm fs-8 btn-success print_button" data-id="{{ $invoice["id"] }}">{{ __("Print invoice") }}</button>
    @endif
    @include("layouts.partials.back_button", ["link" => route("invoices.issue_invoice.index"), "label" => __("Invoices list")])
@endsection
@section('content')
    <div class="card">
        <div class="card-body">

            <h4 class="title mb-4">{{ __("Invoice details") }}</h4>
            <div class="row justify-content-between fs-7 fw-bold w-100">
                <div class="row col-12">
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Invoice number") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $invoice["id"] }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Invoice date") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ date("d-m-Y", strtotime($invoice["invoice_date"])) }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Branch") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ getLocaleName($invoice->branches, 'name_ar', 'name_en') }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Doctor") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ getLocaleName($invoice->doctors, 'name_ar', 'name_en') }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Client name") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ getLocaleName($invoice->clients, 'name_ar', 'name_en') }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Client tax number") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $invoice->clients->tax_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="separator separator-dashed separator-content border-gray-400 my-6"> <h4 class="mb-4 w-200px text-gray-700 fs-6">{{ __("Invoice details") }} </h4></div>
        <div class="card-body pt-0">
            <div class="row">
                <table class="table align-middle table-bordered table-row-dashed fs-7 gy-5 p-0">
                    <thead>
                    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                        <th class="p-3 w-50px">{{ __("Number") }}</th>
                        <th class="w-200px">{{ __("File number") }}</th>
                        <th class="w-200px">{{ __("Patient name") }}</th>
                        <th class="w-200px">{{ __("Service") }}</th>
                        <th class="w-50px">{{ __("Color") }}</th>
                        <th class="w-50px">{{ __("Quantity") }}</th>
                        <th class="w-75px">{{ __("Price") }} </th>
                        <th class="w-50px">{{ __("Discount") }}</th>
                        <th class="w-75px">{{ __("Total net") }}</th>
                        <th class="w-50px">{{ __("Tax") }}</th>
                        <th class="w-75px">{{ __("Total") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($invoiceDetails))
                        @foreach($invoiceDetails as $item)
                            <tr>
                                <td class="p-3 w-50px"> {{ $item["id"] }} </td>
                                <td class="p-3 w-200px"> {{ $item["file_number"] }} </td>
                                <td class="p-3 w-200px"> {{ $item["patient_name"] }} </td>
                                <td class="w-200px">{{ getLocaleName($item->services, 'name_ar', 'name_en') }}</td>
                                <td class="w-50px"> {{ $item->colors->color_code ?? "" }} </td>
                                <td class="w-50px"> {{ $item["service_count"] ?? "" }} </td>
                                <td class="w-75px"> {{ $item["service_price"] ?? "" }} </td>
                                <td class="w-75px"> {{ $item["service_discount"] ?? "" }}% </td>
                                <td class="w-75px"> {{ $item["service_total_net"] ?? "" }} </td>
                                <td class="w-50px"> {{ $item["service_tax"] ?? "" }}% </td>
                                <td class="w-75px"> {{ $item["service_total"] ?? "" }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" class="fs-8 p-4">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="d-flex justify-content-between align-items-center my-4">
                <div class="col-8">
                    <table class="table align-middle fs-8 fw-bold gy-0 gx-0 p-0">
                        <thead class="thead">
                        <tr>
                            <th class="mw-200px"></th>
                            <th class="mw-50px"></th>
                            <th class="mw-25px"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Total taxable") }}</td>
                            <td>{{ $invoice["total_price"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Discount value") }}</td>
                            <td>{{ $invoice["total_discount_value"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Total net") }}</td>
                            <td>{{ $invoice["total_total_net"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Tax value") }}</td>
                            <td>{{ $invoice["total_tax_value"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Total") }}</td>
                            <td>{{ $invoice["total_total"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Total paid") }}</td>
                            <td>{{ $invoice["total_paid"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        <tr class="border-bottom border-1 border-gray-600">
                            <td>{{ __("Total residual") }}</td>
                            <td>{{ $invoice["total_residual"] }}</td>
                            <td>{{ __("S.R") }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center text-end">
                    {{ QrCode::size(180)->generate($invoice["qr_code"]) }}
                </div>
            </div>
        </div>

        <div class="separator separator-dashed separator-content border-gray-400 my-6"> <h4 class="mb-4 w-200px text-gray-700 fs-6">{{ __("Invoice payments details") }} </h4></div>
        @if(isset($invoicePayment))
        <div class="card-body pt-0">
            <div class="row">
                <table class="table align-middle table-bordered table-row-dashed fs-7 gy-5 p-0">
                    <thead>
                    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                        <th class="py-2 w-125px">{{ __("Number") }}</th>
                        <th class="py-2 w-125px">{{ __("Type") }}</th>
                        <th class="py-2 w-125px">{{ __("Amount") }}</th>
                        <th class="py-2 w-150px">{{ __("Date") }}</th>
                        <th class="py-2 w-150px">{{ __("User") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($invoicePayment) > 0)
                        @foreach($invoicePayment as $item)
                            <tr>
                                <td>{{ $item["id"] }}</td>
                                <td>
                                    <span class="badge badge-light-dark">
                                        {{ [ 1 => __("Cash"), 2 => __("Transfer"), 3 => __("Check"), ] [$item["payment_type"]] ?? __("Unknown") }}
                                    </span>
                                </td>
                                <td>{{ $item["amount"] ?? "0.00" }}</td>
                                <td>{{ $item["created_at"] ->format("d-m-Y") ." | ". $item["created_at"] ->format("h:m:s") }}</td>
                                <td>{{ $item->users->name ?? __("Unknown") }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="fs-8 p-4">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection

@section("script")
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    <script>
        $(function () {
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
