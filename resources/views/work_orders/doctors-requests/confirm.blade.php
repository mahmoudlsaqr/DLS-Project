@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Doctors requests"),
    "Title" => __("Confirm doctor request")
])
@section('content')
    <form id="form-requests" method="POST" action="{{ route('work_orders.work_orders_management.store') }}" enctype="multipart/form-data">
        @csrf

        {{---------------------------------------------------
        📌 Begin: Main Card
        ----------------------------------------------------}}
        <x-card>
            <x-slot name="toolbar">
                <x-button :href="route('work_orders.work_orders_management.index')">{{ __('Work orders list') }}</x-button>
            </x-slot>
            <x-slot name="label">{{ __('Confirm doctor request') }}</x-slot>

            <div class="row g-4">
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-input type="hidden" name="request_id" class="request_id" id="request_id" value="{{ $item['id'] ?? '' }}" />
                    <x-form-label :label="__('Work order number')" for="work_order_number" :required="true"></x-form-label>
                    <x-form-input type="text" name="work_order_number" class="work_order_number" id="work_order_number" oninput="notValidateArabicCharInput(this);" value="{{ $item['work_order_number'] ?? '' }}" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Work order date')" for="work_order_date" :required="true"></x-form-label>
                    <x-form-input type="text" name="work_order_date" class="work_order_date datepicker_date" id="work_order_date" value="{{ now() }}" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Branch')" for="branch_id" :required="true"></x-form-label>
                    <x-select2 name="branch_id" id="branch_id" class="branch_id" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Doctor')" for="doctor_id" :required="true"></x-form-label>
                    <x-select2 name="doctor_id" id="doctor_id" class="doctor_id" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Employer')" for="client" :required="true"></x-form-label>
                    <x-form-input type="text" name="client" class="client bg-secondary" id="client" :readonly="true" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Color code')" for="color_id" :required="true"></x-form-label>
                    <x-select2 name="color_id" id="color_id" class="color_id" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Service')" for="service_id" :required="true"></x-form-label>
                    <x-select2 name="service_id" id="service_id" class="service_id" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Count')" for="service_count" :required="true"></x-form-label>
                    <x-form-input type="text" name="service_count" class="input_count" id="service_count" oninput="input_number(this);" value="{{ $item['service_count'] ?? '1' }}" />
                </div>

                @if(auth()->user()->can('service details_work_orders_management'))
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Price')" for="service_price"></x-form-label>
                        <x-form-input type="text" name="service_price" class="input_number" id="service_price" oninput="input_float(this);" />
                    </div>
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Discount')" for="service_discount"></x-form-label>
                        <x-form-input type="text" name="service_discount" class="input_number" id="service_discount" oninput="input_float(this); max_min_val(this, 100, 0);" />
                    </div>
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Discount value')" for="service_discount_value"></x-form-label>
                        <x-form-input type="text" name="service_discount_value" class="input_number" id="service_discount_value" oninput="input_float(this);" />
                    </div>
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Total net')" for="service_total_net"></x-form-label>
                        <x-form-input type="text" name="service_total_net" class="input_number bg-secondary" id="service_total_net" oninput="input_float(this);" readonly />
                    </div>
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Service tax')" for="service_tax"></x-form-label>
                        <x-form-input type="text" name="service_tax" class="input_number bg-secondary" id="service_tax" oninput="input_float(this); max_min_val(this, 100, 0);" />
                    </div>
                    <div class="col-sm-6 col-lg-3 col-xl-2">
                        <x-form-label :label="__('Total')" for="service_total"></x-form-label>
                        <x-form-input type="text" name="service_total" class="input_number bg-secondary" id="service_total" oninput="input_float(this);" readonly />
                    </div>
                @endif

                <div class="col-sm-6 col-lg-4">
                    <x-form-label :label="__('Patient name')" for="patient_name" :required="true"></x-form-label>
                    <x-form-input type="text" name="patient_name" class="" id="patient_name" value="{{ $item['patient_name'] ?? '' }}" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('File number')" for="file_number" :required="true"></x-form-label>
                    <x-form-input type="text" name="file_number" class="" id="file_number" value="{{ $item['file_number'] ?? '' }}" />
                </div>
                <div class="col-sm-6 col-lg-3 col-xl-2">
                    <x-form-label :label="__('Statement')" for="statement" :required="true"></x-form-label>
                    <x-form-input type="text" name="statement" class="" id="statement" />
                </div>
                <div class="col-12">
                    <x-form-label :label="__('Notes')" for="note" :required="true"></x-form-label>
                    <x-form-textarea name="note" class="note" id="note" />
                </div>
            </div>

            <x-slot name="footer">
                <div class="d-flex justify-content-start align-items-center">
                    <x-indicator type="submit" class="submit-requests"
                                 data-form-url="{{ route('work_orders.work_orders_management.store') }}"
                                 data-redirect-url="{{ route('work_orders.work_orders_management.index') }}">{{ __('Save data') }}
                    </x-indicator>
                </div>
            </x-slot>
        </x-card>

        {{---------------------------------------------------
        📌 End: Main Card
        ----------------------------------------------------}}
@endsection
@section("script")

    <script type="text/javascript">
        $(document).ready(function () {

            const submitForm = $(".submit-requests")
            submitForm.click(function(e) {
                e.preventDefault();

                const routes = {
                    form: $(this).data("form-url"),
                    redirect: $(this).data("redirect-url"),
                };

                const messageTitles = {
                    success: `{{ __("success") }}`,
                    error: `{{ __("error") }}`
                };

                handleFormStoreSubmission("form-requests", "submit-requests", routes, messageTitles, true);
            });
        });

    </script>

    <script>
        $(function () {
            let branch = $('.branch_id');
            let color = $('.color_id');

            @if(Auth::user()->hasRole('technician'))
            SelectGetData(branch, "/get_technician_branches/{{ Auth::id() }}", false);
            @else
            SelectGetData(branch, "{{ route("get_branches_user") }}", false);
            @endif

            lazySelect2Ajax(color, `{{ route( 'ajax.get_colors' ) }}`, false, `{{ $item['color_id'] ?? null }}` || null);
        })
    </script>
    @include("work_orders.work_orders_management.partials.create_script")

@endsection

