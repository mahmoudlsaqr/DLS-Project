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
        @if(count($Cases) > 18)
            @media print {
                table {
                    page-break-inside: auto;
                }
                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
                tr:nth-child(18n) {
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
                        <div>{{ __("Designer") }}:</div>
                        <div>{{ $designer ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div>{{ __("Physician") }}:</div>
                        <div>{{ $physician ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div>{{ __("Supervising") }}:</div>
                        <div>{{ $supervising ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div>{{ __("Start date") }}:</div>
                        <div>{{ $date_from ?? "" }}</div>
                    </div>
                    <div class="col-2">
                        <div>{{ __("End date") }}:</div>
                        <div>{{ $date_to ?? "" }}</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table class="print-content align-middle text-start border border-gray-300">
                    <thead>
                    <tr>
                        <th class="p-6">#</th>
                        <th>{{ __('Number') }}</th>
                        <th>{{ __('Physician') }}</th>
                        <th>{{ __('Supervising') }}</th>
                        <th>{{ __('Patient name') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created at') }}</th>
                        <th>{{ __('Updated at') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($Cases) > 0)
                        @foreach($Cases as $item)
                            <tr class="{{ $item["id"] % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                                <td class="p-2">{{ $item["id"] }}</td>
                                <td>{{ $item["case_number"] }}</td>
                                <td>{{ User::where("id", $item["physician"])->value("name") }}</td>
                                <td>{{ User::where("id", $item["supervising"])->value("name") }}</td>
                                <td>{{ $item["patient_name"] }}</td>
                                <td>{{ $item["note"] }}</td>
                                <td>
                                    @if($item["status"] == 1)
                                        <span>{{ __("Waiting for supervising") }}</span>
                                    @elseif($item["status"] == 2)
                                        <span>{{ __("Waiting for physician") }}</span>
                                    @elseif($item["status"] == 3)
                                        <span>{{ __("Approved") }}</span>
                                    @elseif($item["status"] == 4)
                                        <span>{{ __("Rejected from supervision") }}</span>
                                    @else
                                        <span>{{ __("Rejected from physician") }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item["created_by"] != null)
                                        <div>
                                            @php
                                                if ($item["created_by"] != null) {
                                                    $user = User::where('id', '=', $item["created_by"]) ->value('name');
                                                    $date = $item["created_at"] ->format('d-m-Y');
                                                    $time = $item["created_at"] ->format('h:i');
                                                    $NewDateTime = date('A', strtotime($item["created_at"]));
                                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                                }
                                            @endphp
                                            {{ $user }}<br>
                                            {{ $date." | " }}
                                            {{ $time }}
                                            {{ $DateTimeType }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($item["updated_by"] != null)
                                        <div>
                                            @php
                                                if ($item["updated_by"] != null) {
                                                    $user = User::where('id', '=', $item["updated_by"]) ->value('name');
                                                    $date = $item["updated_at"] ->format('d-m-Y');
                                                    $time = $item["updated_at"] ->format('h:i');
                                                    $NewDateTime = date('A', strtotime($item["updated_at"]));
                                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                                }
                                            @endphp
                                            {{ $user }}<br>
                                            {{ $date." | " }}
                                            {{ $time }}
                                            {{ $DateTimeType }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="p-6">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
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
