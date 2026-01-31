@php

    use App\Models\Cases;
    $CountCases = Cases::count();
    $CountCasesSupervising = Cases::where("status", 1)->count();
    $CountCasesPhysician = Cases::where("status", 2)->count();
    $CountCasesAccepted = Cases::where("status", 3)->count();
    $CountCasesRejected= Cases::where("status", 4)->orwhere("status", 5)->count();

    if ($CountCasesSupervising != 0) {
        $CountCasesSupervisingPercent = ($CountCasesSupervising / $CountCases) * 100;
    } else { $CountCasesPhysicianPercent = 0; }

    if ($CountCasesPhysician != 0) {
        $CountCasesPhysicianPercent = ($CountCasesPhysician / $CountCases) * 100;
    } else { $CountCasesPhysicianPercent = 0; }

    if ($CountCasesAccepted != 0) {
        $CountCasesAcceptedPercent = ($CountCasesAccepted / $CountCases) * 100;
    } else { $CountCasesAcceptedPercent = 0; }

    if ($CountCasesRejected != 0) {
        $CountCasesRejectedPercent = ($CountCasesRejected / $CountCases) * 100;
    } else { $CountCasesRejectedPercent = 0; }

@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Home"),
    "Title" => __("Dashboard")
])
@section("toolbar_buttons")
    @if(auth()->user()->can('view_delivering_work_orders'))
        @include("layouts.partials.back_button", ["link" => route("work_orders.work_orders_management.delivering_work_orders.index"), "label" => __("Delivering work orders")])
    @endif
@endsection
@section('content')

    @if(auth()->user()->can('menu_cases') or auth()->user()->can('view_cases'))
        @if(isset($StatusesCasesLabel) and count($CountsCases)>0 and count($PercentsCases)>0)
        <div class="card">
            <div class="card-header py-4">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">{{ __("Cases statistics") }}</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Total Cases: {{ $CountCases ?? "" }}</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                    @include("layouts.partials.back_button", ["link" => route("cases.cases_list.index"), "label" => __("Cases list")])
                </div>
                <!--end::Toolbar-->
            </div>
            <div class="card-body py-4">
                <div class="row">
                    @foreach($StatusesCasesLabel as $status => $label)
                        <!--begin::Col-->
                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3 mb-md-5 mb-xl-10">
                            <!--begin::Card widget 16-->
                            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-100 mb-5 mb-xl-10
                            {{ $status === 'WS'? 'bg-info' : ($status === 'WP'? 'bg-success' : ($status === 'A'? 'bg-primary' : ($status === 'RS'? 'bg-danger' : ($status === 'RP'? 'bg-danger' : 'bg-dark')))) }}">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Amount-->
                                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $CountsCases[$status] ?? 0 }}</span>
                                        <!--end::Amount-->
                                        <!--begin::Subtitle-->
                                        <span class="text-white pt-1 fw-semibold fs-6">{{ __($label) }}</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex align-items-end pt-0">
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                            <span></span>
                                            <span>{{ number_format($PercentsCases[$status] ?? 0, 2) }}%</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 {{ ($status === 'WS'? 'bg-light-danger' : ($status === 'WP'?
                                        'bg-light-success' : ($status === 'A'? 'bg-light-primary' : ($status === 'RS'? 'bg-light-warning' : 'bg-light-danger')))) }} rounded">
                                        <div class="bg-warning rounded h-8px" role="progressbar" style="width: {{ number_format($PercentsCases[$status] ?? 0, 2) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--end::Progress-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 16-->
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endif

    @if(auth()->user()->can('menu_orders') or auth()->user()->can('view_orders'))
        @if(isset($StatusesOrdersLabel) and count($CountsOrders)>0 and count($PercentsOrders)>0)
        <div class="card mt-7">
            <div class="card-header py-4">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">{{ __("Orders statistics") }}</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Total Orders: {{ $CountOrders ?? "" }}</span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                    @include("layouts.partials.back_button", ["link" => route("cases.orders.index"), "label" => __("Orders list")])
                </div>
                <!--end::Toolbar-->
            </div>
            <div class="card-body py-4">
                <div class="row">
                    @foreach($StatusesOrdersLabel as $status => $label)
                        <!--begin::Col-->
                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3 mb-md-5 mb-xl-10">
                            <!--begin::Card widget 16-->
                            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-100 mb-5 mb-xl-10
                            {{ $status === 'PR'? 'bg-info' : ($status === 'SD'? 'bg-success' : ($status === 'A'? 'bg-primary' : ($status === 'R'? 'bg-danger' : 'bg-dark'))) }}">
                                <!--begin::Header-->
                                <div class="card-header pt-5">
                                    <!--begin::Title-->
                                    <div class="card-title d-flex flex-column">
                                        <!--begin::Amount-->
                                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $CountsOrders[$status] }}</span>
                                        <!--end::Amount-->
                                        <!--begin::Subtitle-->
                                        <span class="text-white pt-1 fw-semibold fs-6">{{ __($label) }}</span>
                                        <!--end::Subtitle-->
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex align-items-end pt-0">
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                            <span></span>
                                            <span>{{ number_format($PercentsOrders[$status], 2) }}%</span>
                                        </div>
                                        <div class="h-8px mx-3 w-100 {{ ($status === 'PR'? 'bg-light-danger' : ($status === 'SD'?
                                        'bg-light-success' : ($status === 'A'? 'bg-light-primary' : ($status === 'R'? 'bg-light-warning' : 'bg-light-danger')))) }} rounded">
                                            <div class="bg-warning rounded h-8px" role="progressbar" style="width: {{ number_format($PercentsOrders[$status], 2) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <!--end::Progress-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card widget 16-->
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endif

@endsection
