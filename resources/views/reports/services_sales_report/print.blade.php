@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use App\Models\User;

    $locale = app()->getLocale();
    $User = auth()->user();
@endphp

<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<!--begin::Head-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
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
        @if(count($result) > 41)
            @media print {
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            tr:nth-child(40n) {
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
                <div class="row fs-8 m-0 p-0 fw-bold">
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("Branch") }}:</div>
                        <div class="fw-semibold fs-7">{{ $branch ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("Doctor") }}:</div>
                        <div class="fw-semibold fs-7">{{ $doctor ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("Client") }}:</div>
                        <div class="fw-semibold fs-7">{{ $client ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("Start date") }}:</div>
                        <div class="fw-semibold fs-7">{{ $date_from ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("End date") }}:</div>
                        <div class="fw-semibold fs-7">{{ $date_to ?? "" }}</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row fs-8 m-0 p-0 fw-bold">
                    <div class="col-2">
                        <div class="fw-bold text-gray-800 fs-6">{{ __("Service type") }}:</div>
                        <div class="fw-semibold fs-7">{{ $service_type ?? "" }}</div>
                    </div>
                    @if($service_type_bool)
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Category") }}:</div>
                            <div class="fw-semibold fs-7">{{ $category ?? "" }}</div>
                        </div>
                    @else
                        <div class="col-2">
                            <div class="fw-bold text-gray-800 fs-6">{{ __("Service") }}:</div>
                            <div class="fw-semibold fs-7">{{ $service ?? "" }}</div>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table class="print-content align-middle text-start border border-gray-300">
                    <thead>
                    <tr class="bg-secondary">
                        <th>#</th>
                        <th>{{ __("Service") }}</th>
                        <th>{{ __("Quantity") }}</th>
                        <th>{{ __("Price") }}</th>
                        <th>{{ __("Tax") }}</th>
                        <th>{{ __("Total") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($result) and count($result) > 0)
                        @foreach($result as $index => $item)
                            <tr class="{{ ($index+1) % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                                <td>{{ $index+1 }}</td>
                                <td>{{ $item["service"] }}</td>
                                <td>{{ $item["count"] }}</td>
                                <td>{{ $item["total_price"] }}</td>
                                <td>{{ $item["tax"] }}</td>
                                <td>{{ $item["total_including_tax"] }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="p-6">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr class="bg-secondary text-uppercase">
                        @if(!empty($grand_total) and count($grand_total) > 0)
                            <th colspan="2" class="fw-bold fs-5">{{ __("Totals") }}</th>
                            <th>{{ $grand_total["total_count"] }}</th>
                            <th>{{ $grand_total["total_price"] }}</th>
                            <th>{{ $grand_total["total_tax"] }}</th>
                            <th>{{ $grand_total["total_including_tax"] }}</th>
                        @else
                            <th colspan="2" class="fw-bold fs-5">{{ __("Totals") }}</th>
                            <th>0</th>
                            <th>0.00</th>
                            <th>0.00</th>
                            <th>0.00</th>
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
