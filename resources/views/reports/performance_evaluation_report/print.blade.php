@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;
    use App\Models\User;

    $locale = app()->getLocale();
    $route_name = Route::currentRouteName();

    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp

<!doctype html>
<html lang="{{ $locale }}" direction="{{ $CurrentLocaleDirection }}" dir="{{ $CurrentLocaleDirection }}" style="direction: {{ $CurrentLocaleDirection }}">
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

    <title>{{ __("Print case report") }}</title>

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
        @if(count($data) > 40)
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
                        <div>{{ __("Start date") }}:</div>
                        <div>{{ !empty($request['start_date'])? getDateDMY($request['start_date']) : __("Undefined") }}</div>
                    </div>
                    <div class="col-2">
                        <div>{{ __("End date") }}:</div>
                        <div>{{ !empty($request['end_date'])? getDateDMY($request['end_date']) : __("Undefined") }}</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table class="print-content align-middle text-start border border-gray-300">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __("User") }}</th>
                        <th class="text-center" colspan="2">{{ __("Design phase") }}</th>
                        <th class="text-center" colspan="2">{{ __("Returned") }}</th>
                        <th class="text-center" colspan="2">{{ __("Net") }}</th>
                        <th class="text-center">{{ __("Monthly target") }}</th>
                        <th class="text-center">{{ __("Achievement %") }}</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>{{ __("No. work orders") }}</th>
                        <th>{{ __("No. teeth") }}</th>
                        <th>{{ __("No. work orders") }}</th>
                        <th>{{ __("No. teeth") }}</th>
                        <th>{{ __("No. work orders") }}</th>
                        <th>{{ __("No. teeth") }}</th>
                        <th>{{ __("Target") }}</th>
                        <th>{{ __("Achievement %") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $index = 1;
                        $totalWorkOrders = 0;
                        $totalTeeth = 0;
                        $totalReturnedWorkOrders = 0;
                        $totalReturnedTeeth = 0;
                        $totalNetWorkOrders = 0;
                        $totalNetTeeth = 0;
                        $totalSumDone = 0;
                    @endphp
                    @if(!empty($data) and count($data) > 0)
                        @foreach($data as $item)
                            @php
                                $achievementRate = $item["monthly_target"] > 0
                                    ? round(($item["sum_done"] / $item["monthly_target"]) * 100, 2)
                                    : 0;

                                $totalWorkOrders += $item["work_orders_count"];
                                $totalTeeth += $item["sum"];
                                $totalReturnedWorkOrders += $item["returned_work_orders_count"];
                                $totalReturnedTeeth += $item["returned_sum"];
                                $totalNetWorkOrders += $item["work_orders_count_done"];
                                $totalNetTeeth += $item["sum_done"];
                                $totalSumDone += $item["sum_done"];
                            @endphp
                            <tr class="{{ $index % 2 == 1 ? "bg-secondary" : "bg-secondary bg-opacity-10"}}">
                                <td>{{ $index }}</td>
                                <td>{{ $item["user_name"] }}</td>
                                <td>{{ $item["work_orders_count"] }}</td>
                                <td>{{ $item["sum"] }}</td>
                                <td>{{ $item["returned_work_orders_count"] }}</td>
                                <td>{{ $item["returned_sum"] }}</td>
                                <td>{{ $item["work_orders_count_done"] }}</td>
                                <td>{{ $item["sum_done"] }}</td>
                                <td>{{ $item["monthly_target"] }}</td>
                                <td>{{ $achievementRate }}%</td>
                            </tr>
                            @php $index += 1; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr class="fw-bold text-uppercase border bg-secondary">
                        <td colspan="2" class="text-center">{{ __("Totals") }}</td>
                        <td>{{ $totalWorkOrders }}</td>
                        <td>{{ $totalTeeth }}</td>
                        <td>{{ $totalReturnedWorkOrders }}</td>
                        <td>{{ $totalReturnedTeeth }}</td>
                        <td>{{ $totalNetWorkOrders }}</td>
                        <td>{{ $totalNetTeeth }}</td>
                        <td colspan="2"></td>
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
