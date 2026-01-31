@php
    use App\Models\User;
    use App\Models\WorkOrdersManagement;
    $locale = app()->getLocale();
@endphp
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-2 row-cols-xxl-3">

    <form class="d-none" id="form_return" method="POST" action="{{ route("reports.work_orders_report.report") }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <input name="phase_id" id="phaseId">
        <input name="branch_id" value="{{ $request["branch_id"] ?? "" }}">
        <input name="client_id" value="{{ $request["client_id"] ?? "" }}">
        <input name="doctor_id" value="{{ $request["doctor_id"] ?? "" }}">
        <input name="start_date" value="{{ $request["start_date"] ?? "" }}">
        <input name="end_date" value="{{ $request["end_date"] ?? "" }}">
    </form>

    @foreach($workPhases as $phase)
        @php
            $queryLog = WorkOrdersManagement::with('workOrders', 'users', 'phases');
            if (isset($request)) {
               if ($request ->branch_id) {
                    $queryLog ->where('branch_id', $request["branch_id"]);
               }
               if ($request ->client_id) {
                    $queryLog ->where('client_id', $request["client_id"]);
               }
               if ($request ->doctor_id) {
                    $queryLog ->where('doctor_id', $request["doctor_id"]);
               }
               if ($request->start_date && $request->end_date) {
                    $queryLog ->whereBetween('work_order_date', [$request["start_date"], $request["end_date"]]);
               }
            }
        @endphp

        @if(auth()->user()->can('view_work_orders_report'))
            <div data-id="{{ $phase["id"] }}" class="col mb-4 return_btn cursor-pointer">
        @else
            <div class="col mb-4">
        @endif
            <!--begin::Card-->
            <div class="card m-0">
                <!--begin::Card header-->
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h4 class="fw-bold">{{ getLocaleName($phase, "name_ar", "name_en") }}</h4>
                    </div>
                    <!--end::Card title-->
                    <div class="card-toolbar fw-bold fs-2">
                        <div class="ms-2">
                            <span class="fs-4 text-primary">{{ $queryLog->where("phase_id", $phase["id"])->count() }}</span>
                            <span class="text-gray-500 fs-6 fw-semibold ms-2">{{ __("Work order") }}</span>
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

            </div>
            <!--end::Card-->
        </div>
    @endforeach

{{--    <div class="col">--}}
{{--        <!--begin::Reward Tier-->--}}
{{--        <a href="#" class="card bg-info hoverable h-md-100">--}}
{{--            <!--begin::Body-->--}}
{{--            <div class="card-body">--}}
{{--                <i class="ki-outline ki-award text-white fs-3x ms-n1"></i>--}}
{{--                <div class="text-white fw-bold fs-2 mt-5">--}}
{{--                    Premium Member--}}
{{--                </div>--}}

{{--                <div class="fw-semibold text-white">--}}
{{--                    Tier Milestone Reached--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!--end::Body-->--}}
{{--        </a>--}}
{{--        <!--end::Reward Tier-->--}}
{{--    </div>--}}
</div>
