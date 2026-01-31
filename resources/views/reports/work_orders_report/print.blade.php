@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $locale = app()->getLocale();
    $User = auth()->user();
@endphp
<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <link rel="shortcut icon" href="{{ asset("assets/media/logos/favicon.ico") }}" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="{{ url("https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700") }}" />
    <!--end::Fonts(mandatory for all pages)-->

    <title>{{ __("Print work order report") }}</title>
    <link href="{{ asset("assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset("assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

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
            border: 0.5px solid #0a0a0a !important;
            margin: .5rem 0 !important;
        }
        table.print-content th,
        table.print-content td {
            vertical-align: top;
            border: 0.5px solid #0a0a0a !important;
            font-size: 10px !important;
        }
        table.print-content th {
            text-align: center !important;
            font-size: 10px !important;
            padding: .2rem !important;
            font-weight: 600;
        }
        table.print-content td {
            font-size: 11px !important;
            font-weight: bold !important;
            text-align: center !important;
            padding: 0 3px 0 3px;
        }
        .header-image {
            margin-bottom: .5rem !important;
        }
        .footer-image {
            position: fixed;
            bottom: 0;
            z-index: -9;
        }
        .bg-secondary {
            background-color: #d2d2d2;
        }
        @if(count($workOrders) > 46)
            @media print {
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            tr:nth-child(45n) {
                page-break-after: always;
            }
        }
        @endif
    </style>

</head>
<body>
    <table class="p-0 m-0 main_table">
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
                    <div class="row fs-8 m-0 p-0 fw-bold">
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Branch") }}</div>
                            <div class="fw-semibold fs-7">{{ $branch ?? "" }}</div>
                        </div>
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Doctor") }}</div>
                            <div class="fw-semibold fs-7">{{ $doctor ?? "" }}</div>
                        </div>
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Client") }}</div>
                            <div class="fw-semibold fs-7">{{ $client ?? "" }}</div>
                        </div>
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Start date") }}</div>
                            <div class="fw-semibold fs-7">{{ $date_from ?? "" }}</div>
                        </div>
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("End date") }}</div>
                            <div class="fw-semibold fs-7">{{ $date_to ?? "" }}</div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="print-content align-middle text-start">
                        <thead>
                        <tr class="bg-secondary">
                            <th class="w-50px">{{ __("Number") }}</th>
                            <th class="w-200px">{{ __("Date") }}</th>
                            <th class="w-200px">{{ __("Doctor") }}</th>
                            <th class="w-200px">{{ __("Service") }}</th>
                            <th class="w-75px">{{ __("Color code") }}</th>
                            <th class="w-50px">{{ __("Quantity") }}</th>
                            @if(auth()->user()->can('show prices_work_orders_management'))
                                <th class="w-50px">{{ __("Total net") }}</th>
                                <th class="w-50px">{{ __("Tax") }}</th>
                                <th class="w-50px">{{ __("Total") }}</th>
                            @endif
                            <th class="w-50px">{{ __("File number") }}</th>
                            <th class="w-200px">{{ __("Patient name") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($workOrders) and count($workOrders) > 0)
                            @foreach($workOrders as $item)
                                <tr class="{{ $item["id"] % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                                    <td>{{ $item->work_order_number }}</td>
                                    <td>{{ date("d-m-Y", strtotime($item->work_order_date)) }}</td>
                                    <td>{{ $locale == "ar" ? $item->doctors->name_ar : $item->doctors->name_en }}</td>
                                    <td>{{ $locale == "ar" ? $item->services->name_ar : $item->services->name_en }}</td>
                                    <td>{{ $item->colors->color_code }}</td>
                                    <td>{{ $item->service_count }}</td>
                                    @if(auth()->user()->can('show prices_work_orders_management'))
                                        <td>{{ $item->service_total_net }}</td>
                                        <td>{{ $item->service_tax }}</td>
                                        <td>{{ $item->service_total }}</td>
                                    @endif
                                    <td>{{ $item->file_number }}</td>
                                    <td>{{ $item->patient_name }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="16" class="p-6">{{ __("There is no data available in the table") }}</td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <td colspan="5">{{ __("Totals") }}</td>
                                <td>{{ $totals['service_count'] }}</td>
                                @if(auth()->user()->can('show prices_work_orders_management'))
                                <td>{{ $totals['service_total_net'] }}</td>
                                <td>{{ $totals['service_tax_value'] }}</td>
                                <td>{{ $totals['service_total'] }}</td>
                                <td colspan="3"></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td class="m-0 p-0">
                    <img class="footer-image" src="{{ asset("assets/media/invoice/footer.png") }}" width="100%" alt="footer">
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
