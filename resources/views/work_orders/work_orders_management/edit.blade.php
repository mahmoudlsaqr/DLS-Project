@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Clients;
    $locale = app()->getLocale();

@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Work orders management"),
    "Title" => __("Edit work order")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("work_orders.work_orders_management.index"), "label" => __("Work orders list")])
@endsection
@section('content')
    <div class="card">
        <form id="form_update" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="row">
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __('Work order number') }}</label>
                            <input class="form-control form-control-sm" name="work_order_number" @if($madeInvoiceForIt === 1) disabled @endif id="work_order_number" value="{{ $workOrder["work_order_number"] }}">
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __('Work order date') }}</label>
                            <input type="text" class="form-control form-control-sm work_order_date fw-bold @if(!auth()->user()->can('change date_work_orders_management')) bg-secondary @else fc-flatpickr @endif"
                                   name="work_order_date" placeholder="DD-MM-YYYY" value="{{ $workOrder["work_order_date"] }}"
                                   @if(!auth()->user()->can('change date_work_orders_management')) readonly @endif />
                            <span id="work_order_date"></span>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __("Branch") }}</label>
                            <select class="form-select form-select-sm branch_id" data-control="select2" @if($madeInvoiceForIt === 1) disabled @endif
                                    name="branch_id" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                @foreach($branches as $item)
                                    <option value="{{ $item->id }}">{{ $locale == "ar"? $item->name_ar : $item->name_en }}</option>
                                @endforeach
                            </select>
                            <span id="branch_id"></span>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __("Doctor") }}</label>
                            <select class="form-select form-select-sm doctor_id" data-control="select2" @if($madeInvoiceForIt === 1) disabled @endif
                                    name="doctor_id" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                <option value="{{ $workOrder->doctor_id }}">{{ $locale == "ar"? $workOrder->doctors->name_ar : $workOrder->doctors->name_en }}</option>
                            </select>
                            <span id="doctor_id"></span>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __("Employer") }}</label>
                            <input class="form-control form-control-sm bg-secondary" name="client" @if($madeInvoiceForIt === 1) disabled @endif id="client" value="{{ $locale == "ar" ? Clients::find($workOrder["client_id"])->name_ar : Clients::find($workOrder["client_id"])->name_en }}" readonly>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __("Color code") }}</label>
                            <select class="form-select form-select-sm color_id" data-control="select2" @if($madeInvoiceForIt === 1) disabled @endif
                                    name="color_id" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                @foreach($colors as $item)
                                    <option value="{{ $item->id }}">{{ $item->color_code }}</option>
                                @endforeach
                            </select>
                            <span id="color_id"></span>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __("Service") }}</label>
                            <select class="form-select form-select-sm service_id" data-control="select2" @if($madeInvoiceForIt === 1) disabled @endif
                                    name="service_id" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                                @foreach($services as $item)
                                    <option value="{{ $item->id }}" @if($workOrder["service_id"] === $item->id) selected @endif>{{ $locale == "ar"? $item->name_ar : $item->name_en }}</option>
                                @endforeach
                            </select>
                            <span id="service_id"></span>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
                            <label class="form-label required fs-7">{{ __('Count') }}</label>
                            <input class="form-control form-control-sm input_count" oninput="input_number(this);" @if($madeInvoiceForIt === 1) disabled @endif name="service_count" id="service_count" value="{{ $workOrder["service_count"] }}">
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="row">
                        @if(auth()->user()->can('service details_work_orders_management'))
                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Price') }}</label>
                                <input class="form-control form-control-sm input_number" oninput="input_float(this);" @if($madeInvoiceForIt === 1) disabled @endif name="service_price" id="service_price" value="{{ $workOrder["service_price"] }}">
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Discount') }}</label>
                                <input class="form-control form-control-sm input_number" oninput="input_float(this); max_min_val(this, 100, 0);" @if($madeInvoiceForIt === 1) disabled @endif name="service_discount" id="service_discount" value="{{ $workOrder["service_discount"] }}">
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Discount value') }}</label>
                                <input class="form-control form-control-sm input_number" oninput="input_float(this);" @if($madeInvoiceForIt === 1) disabled @endif name="service_discount_value" id="service_discount_value" value="{{ $workOrder["service_discount_value"] }}">
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Total net') }}</label>
                                <input class="form-control form-control-sm input_number bg-secondary" oninput="input_float(this);" @if($madeInvoiceForIt === 1) disabled @endif name="service_total_net" id="service_total_net" value="{{ $workOrder["service_total_net"] }}" readonly>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Service tax') }}</label>
                                <input class="form-control form-control-sm input_number" oninput="input_float(this); max_min_val(this, 100, 0);" @if($madeInvoiceForIt === 1) disabled @endif name="service_tax" id="service_tax" value="{{ $workOrder["service_tax"] }}">
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-sm-6 col-lg-4 col-xl-2 mb-4">
                                <label class="form-label required fs-7">{{ __('Total') }}</label>
                                <input class="form-control form-control-sm input_number bg-secondary" oninput="input_float(this);" @if($madeInvoiceForIt === 1) disabled @endif name="service_total" id="service_total" value="{{ $workOrder["service_total"] }}" readonly>
                            </div>
                            <!--end::Input group-->
                        @endif
                    </div>
                    <div class="row">
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-4 mb-4">
                            <label class="form-label required fs-7">{{ __('Patient name') }}</label>
                            <input class="form-control form-control-sm" name="patient_name" id="patient_name" @if($madeInvoiceForIt === 1) disabled @endif value="{{ $workOrder["patient_name"] }}">
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-4 mb-4">
                            <label class="form-label required fs-7">{{ __('Statement') }}</label>
                            <input class="form-control form-control-sm" name="statement" id="statement" @if($madeInvoiceForIt === 1) disabled @endif value="{{ $workOrder["statement"] }}">
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-sm-6 col-lg-4 col-xl-4 mb-4">
                            <label class="form-label required fs-7">{{ __('File number') }}</label>
                            <input class="form-control form-control-sm" name="file_number" id="file_number" @if($madeInvoiceForIt === 1) disabled @endif value="{{ $workOrder["file_number"] }}">
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="col-lg-12">
                            <label class="form-label required fs-7">{{ __('Notes') }}</label>
                            <textarea class="form-control form-control-sm" name="note" @if($madeInvoiceForIt === 1) disabled @endif id="note">{{ $workOrder["note"] }}</textarea>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="mb-3">
                    <button type="submit" id="submit_btn" class="btn btn-sm btn-primary" data-kt-users-modal-action="submit">
                        <span class="indicator-label">{{ __("Submit") }}</span>
                        <span class="indicator-progress">{{ __("Please wait...") }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section("script")
    @include("layouts.partials.script_form_update", [
        "Form_route" => route('work_orders.work_orders_management.update', [$workOrder["id"]]),
        "Redirect_route" => route('work_orders.work_orders_management.index')
    ])
    <script>
        $(function () {
            let branch = $('.branch_id');
            let color = $('.color_id');
            @if(Auth::user()->hasRole('technician'))
            SelectGetData(branch, "/get_technician_branches/{{ Auth::id() }}", false, '{{ $workOrder["branch_id"] }}');
            @else
            SelectGetData(branch, "{{ route("get_branches_user") }}", false, '{{ $workOrder["branch_id"] }}');
            @endif
            SelectGetData(color, "{{ route( 'get_colors' ) }}", false, '{{ $workOrder["color_id"] }}');
        })
    </script>
    @include("work_orders.work_orders_management.partials.create_script")
@endsection
