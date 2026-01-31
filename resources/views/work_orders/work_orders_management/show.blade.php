@php
    use App\Models\Clients;
    $locale = app()->getLocale();

@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders management"),
    "Title" => __("Show work order")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("work_orders.work_orders_management.index"), "label" => __("Delivering work orders")])
@endsection
@section('content')
    <div class="card">
        <div class="card-body">

{{--            <h4 class="title mb-4">{{ __("Work order details") }}</h4>--}}
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Work order number") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder["work_order_number"] }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Work order date") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ date('d-m-Y', strtotime($workOrder["work_order_date"])) }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Phase") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $locale == "ar" ? $workOrder->phases->name_ar : $workOrder->phases->name_en }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Branch") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $locale == "ar" ? $workOrder->branches->name_ar : $workOrder->branches->name_en }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Doctor") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $locale == "ar" ? $workOrder->doctors->name_ar : $workOrder->doctors->name_en }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Client") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $locale == "ar" ? $workOrder->clients->name_ar : $workOrder->clients->name_en }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Color") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->colors->color_code }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Service") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $locale == "ar" ? $workOrder->services->name_ar : $workOrder->services->name_en }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Quantity") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_count }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Price") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_price }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Discount") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_discount }}%</div>
                </div>
{{--                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">--}}
{{--                    <div class="fw-bold text-gray-800 fs-7">{{ __("Discount value") }}</div>--}}
{{--                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_discount_value }}</div>--}}
{{--                </div>--}}
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Total net") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_total_net }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Tax") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_tax }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Total") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_total }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("File number") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->file_number }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Patient name") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->patient_name }}</div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                    <div class="fw-bold text-gray-800 fs-7">{{ __("Statement") }}</div>
                    <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->statement }}</div>
                </div>
                @if ($workOrder["created_by"] != null)
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Created by") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">
                            @php
                                $user = \App\Models\User::where('id', '=', $workOrder["created_by"]) ->value('name');
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
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Updated by") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">
                            @php
                                $user = \App\Models\User::where('id', '=', $workOrder["updated_by"]) ->value('name');
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
            <div class="separator separator-dashed separator-content border-gray-400 my-6"> <h4 class="mb-4 w-200px text-gray-700 fs-6">{{ __("Log phases details") }} </h4></div>

            <div class="card-body pt-0">
                <div class="row">
                    <table class="table align-middle table-bordered table-row-dashed fs-7 gy-5 p-0">
                        <thead>
                        <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                            <th class="px-6">#</th>
                            <th>{{ __("Phase") }}</th>
                            <th class="w-150px">{{ __('Updated at') }}</th>
                            <th>{{ __("Updated at") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($logWorkOrder) and count($logWorkOrder) > 0)
                            @foreach($logWorkOrder as $item)
                                <tr class="{{ $item["id"] % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                                    <td style="padding: 0 2em !important;">{{ $item["id"] }}</td>
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
                                                <span>{{ $date }}</span><br>
                                                <span>{{ $time }}</span>
                                                <span>{{ $DateTimeType }}</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="16" class="p-6">{{ __("There is no data available in the table") }}</td>
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

@endsection
