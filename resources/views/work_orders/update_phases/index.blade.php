@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders"),
    "Title" => __("Update work orders phases")
])
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h4 class="">{{ __("Search for work order") }}</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route("work_orders.work_orders_management.update_work_orders_phases.search") }}" method="GET">
                @csrf
                <div class="row row-cols-4">
                    <div class="col">
                        <input class="form-control form-select-sm w-100" type="text" name="number" value="{{ $number ?? "" }}" id="searchInput" placeholder="{{ __('Enter work order number') }}" />
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-sm btn-primary submit_button" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Search") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@if(isset($workOrder))
    <div class="card my-6">
        <div class="card-header">
            <div class="card-title">
                <h4 class="">{{ __("Work order information") }}</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route("work_orders.work_orders_management.update_work_orders_phases.update_phase_after_search") }}" method="POST">
                @csrf
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
                    @if(auth()->user()->can('show prices_work_orders_management'))
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Price") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_price }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                        <div class="fw-bold text-gray-800 fs-7">{{ __("Discount") }}</div>
                        <div class="fw-semibold text-gray-600 fs-7">{{ $workOrder->service_discount }}%</div>
                    </div>
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
                    @endif
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
                <div class="card my-6">
                    <div class="card-body">
                        <div class="row row-cols-3">
                            <input type="hidden" name="number" value="{{ $number }}">
                            <input type="hidden" name="id" value="{{ $workOrder["id"] }}">
                            @foreach($workingPhases as $item)
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input h-20px w-20px p-0" type="radio" name="phase_id" id="phase_{{ $item["id"] }}" value="{{ $item["id"] }}" />
                                        <label class="fw-semibold fs-7 mb-2" for="phase_{{ $item["id"] }}">
                                            {{ getLocaleName($item, "name_ar", "name_en") }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-primary submit_button" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Update") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@section("script")
@endsection

