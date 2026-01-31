@extends('layouts.landing', [
    "Title" => __("Search for work order")
])
@section('toolbar_buttons')
    <a href="{{ route("landing.work_orders.search_page") }}" class="btn btn-sm btn-success">{{ __("Search for work order") }}</a>
    @if (Auth::check())
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Dashboard") }}</a>
    @else
        <a href="{{ route("dashboard") }}" class="btn btn-sm btn-success">{{ __("Sign in") }}</a>
    @endif
@endsection
@section('content')
    <form id="form-doctors-requests" class="mt-6" method="POST" action="#" enctype="multipart/form-data">
        @csrf

        <x-card>
            <x-slot name="label">{{ __('Create request') }}</x-slot>
            <x-slot name="toolbar"></x-slot>

            <h5 class="my-4">{{ __("Request data") }}:</h5>

            <div class="row g-4">
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Doctor name')" for="doctor-name" :required="true"></x-form-label>
                    <x-form-input type="text" name="doctor_name" class="doctor-name" id="doctor-name" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Patient name')" for="patient-name" :required="true"></x-form-label>
                    <x-form-input type="text" name="patient_name" class="patient-name" id="patient-name" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('File number')" for="file-number" :required="true"></x-form-label>
                    <x-form-input type="text" name="file_number" class="file-number" id="file-number" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Type of case')" for="type-case" :required="true"></x-form-label>
                    <x-form-input type="text" name="type_case" class="type-case" id="type-case" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Number of teeth')" for="number-teeth" :required="true"></x-form-label>
                    <x-form-input type="text" name="number_teeth" class="number-teeth" id="number-teeth" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Color code')" for="color-code-select" :required="true"></x-form-label>
                    <x-select2 name="color_code" id="color-code-select" class="color-code-select" />
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-form-label :label="__('Duration of delivery')" for="duration-delivery" :required="true"></x-form-label>
                    <x-form-input type="text" name="duration_delivery" class="duration-delivery" id="duration-delivery" />
                </div>
            </div>

            <h5 class="my-4">{{ __("Request options") }}:</h5>

            <div class="row g-4 mt-4">
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="checkbox"
                        name="crowns"
                        id="crowns_checkbox"
                        value="1"
                        label="Crown's"
                    />
                </div>
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="checkbox"
                        name="bridges"
                        id="bridges_checkbox"
                        value="1"
                        label="Bridge's"
                    />
                </div>
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="checkbox"
                        name="rpd"
                        id="rpd_checkbox"
                        value="1"
                        label="R.P.D"
                    />
                </div>
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="checkbox"
                        name="rcd"
                        id="rcd_checkbox"
                        value="1"
                        label="R.C.D"
                    />
                </div>
            </div>

            <h5 class="my-4">{{ __("Request status") }}:</h5>

            <div class="row g-4 mt-4">
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="radio"
                        name="request_status"
                        id="request-status-new"
                        value="1"
                        :label="__('New')"
                        :checked="true"
                    />
                </div>
                <div class="col-sm-12 col-md-6 col-xl-2 mb-4">
                    <x-checkbox
                        type="radio"
                        name="request_status"
                        id="request-status-remark"
                        value="2"
                        :label="__('Remark')"
                    />
                </div>
            </div>

            <h5 class="my-4">{{ __("Additional data") }}:</h5>

            <div class="row g-4 mt-4">
                <div class="col-12">
                    <x-form-label :label="__('Notes')" for="notes"></x-form-label>
                    <x-form-textarea name="notes" id="notes" class="notes" ></x-form-textarea>
                </div>
                <div class="col-12 mb-4">
                    <x-form-label :label="__('Files')" for="attachments"></x-form-label>
                    <x-form-input type="file" accept=".ply,.stl,.obj" name="attachments[]" class="attachments" id="attachments" multiple />
                </div>
            </div>

            <x-slot name="footer">
                <x-indicator type="submit" class="submit-doctors-requests me-6"
                             data-form-url="{{ route('landing.doctors-requests.store') }}"
                             data-redirect-url="{{ route('landing.doctors-requests.create') }}">{{ __('Save data') }}
                </x-indicator>
            </x-slot>

        </x-card>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            const submitForm = $(".submit-doctors-requests")
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

                handleFormStoreSubmission("form-doctors-requests", "submit-doctors-requests", routes, messageTitles);
            });

            const color = $(".color-code-select")

            lazySelect2Ajax(color, "{{ route( 'ajax.get_colors' ) }}", false);

        });
    </script>
@endsection


