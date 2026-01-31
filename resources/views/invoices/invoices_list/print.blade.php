@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;

    $locale = app()->getLocale();
    $route_name = Route::currentRouteName();
    $centerName = ($locale === "ar")? settings('center_name_ar') : settings('center_name_en');

    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" direction="{{ $CurrentLocaleDirection }}" dir="{{ $CurrentLocaleDirection }}" style="direction: {{ $CurrentLocaleDirection }}">
<head>
    <base href="/"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content=""/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __("Print invoice") }}</title>
    @include('layouts.main_partials.head')
    <style>
        @page {
            size: A4 portrait;
            margin: 1rem !important;
            padding: 0 !important;
        }
        * {
            font-family: "Sakkal Majalla", serif !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
        }
        table {
            width: 100%;
        }
        table.main_table {
            margin: 0 !important;
            padding: 0 !important;
        }
        table.print-content {
            font-size: 12px;
            border: 1px solid #0a0a0a !important;
            border-collapse: collapse !important;
            margin: .5rem 0 !important;
        }
        table.print-content th,
        table.print-content td {
            vertical-align: top;
            border: 1px solid #B5B5C3 !important;
            font-size: 10px !important;
        }
        table.print-content th {
            background-color: #B5B5C3;
            text-align: center !important;
            font-size: 9px !important;
            padding: .2rem !important;
            font-weight: 500;
        }
        table.print-content td {
            text-align: right !important;
            padding: .5rem !important;
        }
        table.print-content tbody tr td {
            font-weight: normal !important;
            font-size: 10px !important;
        }
        .header-image {
            margin-bottom: .5rem !important;
        }
        .footer-image {
            position: fixed;
            bottom: 0;
            z-index: -9;
        }
        @if(count($invoiceDetails) > 31)
        @media print {
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            tr:nth-child(26n) {
                page-break-after: always;
            }
        }
        @endif
    </style>
</head>
<body>
<table class="main_table">
    <thead>
    <tr>
        <td>
            <img class="header-image" src="{{ asset("assets/media/invoice/header.png") }}" width="100%" alt="header">
        </td>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <div class="row justify-content-between align-items-center fs-8 fw-bold w-100 m-0 p-0">
                    <div class="col-4">
                        <p>{{ $centerName ?? "" }}</p>
                        <p>{{ getLocaleName($invoice->branches, 'name_ar', 'name_en') }}</p>
                        <p>{{ __("Tax number") }} - {{ $invoice->branches->tin ?? "" }}</p>
                        <p>{{ __("Invoice number") }} : <span>{{ $invoice['id'] }}</span> </p>
                        <p>{{ __("Invoice date") }} : <span>{{ getDateYMD($invoice['invoice_date']) }}</span></p>
                        @if(!empty($invoice['start_date_issue']) && !empty($invoice['end_date_issue']))
                        <p>
                            {{ __("Issue period") }} :
                            <span>{{ __("from") }} {{ getDateYMD($invoice['start_date_issue']) }}</span>
                            <span>{{ __("to") }} {{ getDateYMD($invoice['end_date_issue']) }}</span>
                        </p>
                        @endif
                    </div>
                    <div class="col-4 text-center">
                        {{ QrCode::size(90)->generate($invoice["qr_code"]) }}
                    </div>
                    <div class="col-4 text-start">
                        <div class="row">
                            <div class="col-6">
                                <p>{{ __("Tax invoice") }}</p>
                                <p>{{ __("Client name") }}</p>
                                <p>{{ __("Client tax number") }}</p>
                                <p>{{ __("Doctor") }}</p>

                            </div>
                            <div class="col-6">
                                <p>-</p>
                                <p>{{ getLocaleName($invoice->clients, 'name_ar', 'name_en') }}</p>
                                <p>{{ $invoice->clients->tax_number }}</p>
                                <p>{{ getLocaleName($invoice->doctors, 'name_ar', 'name_en') }}</p>

                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table id="data-table" class="print-content align-middle text-start border border-gray-300">
                    <thead>
                    <tr>
                        <th class="w-25px">{{ __("Number") }}</th>
                        <th class="w-50px">{{ __("Work order number") }}</th>
                        <th class="w-50px">{{ __("File number") }}</th>
                        <th class="w-100px">{{ __("Patient name") }}</th>
                        <th class="w-150px">{{ __("Service") }}</th>
                        <th class="w-25px">{{ __("Quantity") }}</th>
                        <th class="w-50px">{{ __("Price") }}</th>
                        <th class="w-50px">{{ __("Total net") }}</th>
                        <th class="w-50px">{{ __("Tax") }}</th>
                        <th class="w-50px">{{ __("Total") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($invoiceDetails as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->workOrder->work_order_number }}</td>
                            <td>{{ $item['file_number'] }}</td>
                            <td>{{ $item['patient_name'] }}</td>
                            <td>{{ getLocaleName($item->services, 'name_ar', 'name_en') }}</td>
                            <td>{{ $item['service_count'] ?? "" }}</td>
                            <td>{{ $item['service_price'] ?? "" }}</td>
                            <td>{{ $item['service_total_net'] ?? "" }}</td>
                            <td>{{ $item['service_tax'] ?? "" }}</td>
                            <td>{{ $item['service_total'] ?? "" }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="fs-8 p-4">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <!-- End Print Content -->
            </td>
        </tr>
        <tr>
            <td>
                <div class="footer d-flex justify-content-between align-content-start ms-4">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="col-6">
                            <table class="fw-bold fs-9">
                                <tbody>
                                @foreach([
                                    __("Total taxable") => $invoice["total_price"],
                                    __("Total net") => $invoice["total_total_net"],
                                    __("Discount value") => $invoice["total_discount_value"],
                                    __("Tax value") => $invoice["total_tax_value"],
                                    __("Total paid") => $invoice["total_paid"],
                                    __("Total residual") => $invoice["total_residual"]
                                ] as $label => $value)
                                    <tr class="border-bottom-1 border-bottom">
                                        <td class="w-250px">{{ $label }}</td>
                                        <td class="w-50px">{{ $value }}</td>
                                        <td class="w-50px">{{ __("S.R") }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row w-100 mt-4">
                    <p class="text-center fs-9 fw-bold">{{ settings("invoice_message") ?? "" }}</p>
                </div>
            </td>
        </tr>
    </tbody>

    <tfoot>
    <tr>
        <td>
            <img class="footer-image" src="{{ asset("assets/media/invoice/footer.png") }}" width="100%" alt="footer">
        </td>
    </tr>
    </tfoot>

</table>
</body>
</html>


