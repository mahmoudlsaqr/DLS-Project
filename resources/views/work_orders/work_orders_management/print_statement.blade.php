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

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="{{ url("https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700") }}" />
    <!--end::Fonts(mandatory for all pages)-->

    <title>{{ __("Print statement work order") }}</title>
    @include('layouts.main_partials.head')

    <link href="{{ asset("assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset("assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <style>
        @page {
            size: A4;
            margin: 0;

        }
        table {
            width: 100%;
            font-family: "Sakkal Majalla", serif !important;
        }
        .card {
            direction: {{ $locale === "ar" ? "rtl" : "ltr" }};
            text-align: {{ $locale === "ar" ? "right" : "left" }};
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body>
    <div class="card border-0 p-4">
        <img class="mb-2" src="{{ asset("uploads/logo/logo.png") }}" width="120px" alt="logo">
        <div class="row fw-bold w-100 fs-9">
            <h4 class="fw-bold fs-6 mb-4">{{ __("Work order details") }}</h4>
            <div class="row justify-content-between mb-4">
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Work order number") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder["work_order_number"] }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Work order date") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ date('d-m-Y', strtotime($workOrder["work_order_date"])) }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Phase") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $locale == "ar" ? $workOrder->phases->name_ar : $workOrder->phases->name_en }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Branch") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $locale == "ar" ? $workOrder->branches->name_ar : $workOrder->branches->name_en }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Doctor") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $locale == "ar" ? $workOrder->doctors->name_ar : $workOrder->doctors->name_en }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Client") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $locale == "ar" ? $workOrder->clients->name_ar : $workOrder->clients->name_en }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Color") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->colors->color_code }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Service") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $locale == "ar" ? $workOrder->services->name_ar : $workOrder->services->name_en }}</span>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Quantity") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_count }}</span>
                </div>
                @if(auth()->user()->can('show prices_work_orders_management'))
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Price") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_price }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Discount") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_discount }}%</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Total net") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_total_net }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Tax") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_tax }}</span>
                </div>
                <div class="col-3 mb-3">
                    <span class="fw-bold text-gray-800">{{ __("Total") }}: </span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->service_total }}</span>
                </div>
                @endif
            </div>
            <div class="row mb-4">
                <div class="col-3">
                    <span class="fw-bold text-gray-800">{{ __("File number") }}</span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->file_number }}</span>
                </div>
                <div class="col-3">
                    <span class="fw-bold text-gray-800">{{ __("Patient name") }}</span>
                    <span class="fw-semibold text-gray-600">{{ $workOrder->patient_name }}</span>
                </div>
                <div class="col-3">
                    <div class="fw-bold text-gray-800">{{ __("Statement") }}</div>
                    <div class="fw-semibold text-gray-600">{{ $workOrder->statement }}</div>
                </div>
                @if ($workOrder["created_by"] != null)
                    <div class="col-12">
                        <div class="fw-bold text-gray-800">{{ __("Created by") }}</div>
                        <div class="fw-semibold text-gray-600">
                            @php
                                $user = User::where('id', '=', $workOrder["created_by"]) ->value('name');
                                $date = $workOrder["created_at"] ->format('d-m-Y');
                                $time = $workOrder["created_at"] ->format('h:i');
                                $NewDateTime = date('A', strtotime($workOrder["created_at"]));
                                $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                            @endphp
                            <span>{{ $user }}</span><br>
                            <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                        </div>
                    </div>
                @endif
                @if ($workOrder["updated_by"] != null)
                    <div class="mb-2">
                        <div class="fw-bold text-gray-800">{{ __("Updated by") }}</div>
                        <div class="fw-semibold text-gray-600">
                            @php
                                $user = User::where('id', '=', $workOrder["updated_by"]) ->value('name');
                                $date = $workOrder["updated_at"] ->format('d-m-Y');
                                $time = $workOrder["updated_at"] ->format('h:i');
                                $NewDateTime = date('A', strtotime($workOrder["updated_at"]));
                                $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                            @endphp
                            <span>{{ $user }}</span><br>
                            <span>{{ $date ." ". $time ." ". $DateTimeType }}</span><br>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (auth()->user()->can('log phases details_work_orders_management'))
            <h4 class="fw-bold fs-6 mb-4">{{ __("Log phases details") }}</h4>
            <div class="row">
                <table class="table table-bordered border border-gray-500 align-center text-left w-100 mt-4 fs-9 align-middle">
                    <thead class="bg-secondary">
                    <tr class="text-gray-800 fw-bold text-uppercase">
                        <th class="px-6">#</th>
                        <th>{{ __("Phase") }}</th>
                        <th class="w-150px">{{ __('Updated at') }}</th>
                        <th>{{ __("Updated at") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($logWorkOrder) and count($logWorkOrder) > 0)
                        @foreach($logWorkOrder as $item)
                            <tr>
                                <td>{{ $item["id"] }}</td>
                                <td>{{ $locale == "ar" ? $item->phases->name_ar : $item->phases->name_en }}</td>
                                <td>{{ $item->users->name ?? "" }}</td>
                                <td>
                                    @if ($item["log_date"] != null and $item["log_time"] != null)
                                        <div class="fw-bold text-gray-800">
                                            @php
                                                $date = date('d-m-Y', strtotime($item["log_date"]));
                                                $time = date('h:i', strtotime($item["log_time"]));
                                                $NewDateTime = date('A', strtotime($item["log_time"]));
                                                $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                            @endphp
                                            <span>{{ $date }}</span> |
                                            <span>{{ $time }}</span>
                                            <span>{{ $DateTimeType }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="p-4 fs-9">{{ __("There is no data available in the table") }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>
<!--end::Body-->
</html>
