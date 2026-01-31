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
            size: A4;
            margin: 2em;
        }
        table {
            width: 100%;
            font-family: "Sakkal Majalla", serif !important;
        }
        table tr td .align-right {
            text-align: right!important;
        }
        table tr td .align-right {
            text-align: right!important;
        }
    </style>

</head>
<!--end::Head-->
<!--begin::Body-->
<body>
    <div class="d-flex flex-column justify-content-center align-items-center w-100">
        <table class="table table-bordered border-gray-300 fs-8 gy-5 p-6">
            <thead>
            <tr class="text-gray-800 fw-bold fs-8 text-uppercase">
                <th>{{ __("Payment number") }}</th>
                <th>{{ __("Invoice number") }}</th>
                <th>{{ __("Amount") }}</th>
                <th>{{ __("Type") }}</th>
                <th>{{ __("Branch") }}</th>
                <th>{{ __("Doctor") }}</th>
                <th class="w-150px">{{ __('Updated at') }}</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($workOrders) and count($workOrders) > 0)
                @foreach($workOrders as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->invoicesMain->id }}</td>
                        <td>{{ $item->amount ?? "0.00" }}</td>
                        <td>
                            <span class="badge badge-light-dark">
                                {{ [ 1 => __("Cash"), 2 => __("Transfer"), 3 => __("Check"), ] [$item["payment_type"]] ?? __("Unknown") }}
                            </span>
                        </td>
                        @php
                            $branch = \App\Models\Branch::find($item->invoicesMain->branch_id);
                            $branchName = getLocaleName($branch, "name_ar", "name_en");
                            $doctor = \App\Models\Doctors::find($item->invoicesMain->doctor_id);
                            $doctorName = getLocaleName($doctor, "name_ar", "name_en");
                        @endphp
                        <td>{{ $branchName ?? __("Unknown") }}</td>
                        <td>{{ $doctorName ?? __("Unknown") }}</td>
                        @if ($item["user_id"] != null)
                            <td>
                                <div class="fw-bold text-gray-800">
                                    @php
                                        $date = $item["updated_at"] ->format('d-m-Y');
                                        $time = $item["updated_at"] ->format('h:i');
                                        $NewDateTime = date('A', strtotime($item["updated_at"]));
                                        $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                    @endphp
                                    <span>{{ $date }}</span><br>
                                    <span>{{ $time }}</span>
                                    <span>{{ $DateTimeType }}</span>
                                </div>
                            </td>
                        @else
                            <td>{{ __("Unknown") }}</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="16" class="p-6">{{ __("There is no data available in the table") }}</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-start align-items-center gap-12 my-6">
            <span class="fs-7 fw-bold">
                {{ __("Total amount") }}:
                <span>{{ $sumAmount ?? "0.00" }}</span>
            </span>
            <span class="fs-7 fw-bold">
                {{ __("Total cash") }}:
                <span>{{ $sumCash ?? "0.00" }}</span>
            </span>
            <span class="fs-7 fw-bold">
                {{ __("Total transfer") }}:
                <span>{{ $sumTransfer ?? "0.00" }}</span>
            </span>
            <span class="fs-7 fw-bold">
                {{ __("Total check") }}:
                <span>{{ $sumCheck ?? "0.00" }}</span>
            </span>
        </div>
    </div>
</body>
<!--end::Body-->
</html>
