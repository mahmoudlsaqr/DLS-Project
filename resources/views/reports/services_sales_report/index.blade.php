@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;

    $locale = app()->getLocale();
    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Reports"),
    "Title" => __("Services sales report")
])
@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pb-0">
            <form id="form_search" method="POST" action="{{ route("reports.services_sales_report.report") }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('Branch') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter branch_id" name="branch_id" id="branch_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($branches as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('Client') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter client_id" name="client_id" id="client_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($clients as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('Doctor') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter doctor_id" name="doctor_id" id="doctor_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($doctors as $item)
                                <option value="{{ $item["id"] }}">{{ $locale === "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('Start date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['start_date'] ?? "" }}" name="start_date" id="start_date" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('End date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['end_date'] ?? "" }}" name="end_date" id="end_date" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-7">{{ __('Service Type') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter service_type" name="service_type" id="service_type"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            <option value="1">{{ __("Category") }}</option>
                            <option value="2">{{ __("Service") }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 categories_div">
                        <label class="required fs-7 mb-2">{{ __("Category") }}</label>
                        <select class="form-select form-select-sm fw-bold category_id" data-kt-select2="true" name="category_id" id="category_id"
                                data-placeholder="{{ __("Select option") }}" data-allow-clear="true" data-hide-search="false">
                            <option></option>
                            @foreach($categories as $item)
                                <option value="{{ $item["id"] }}">{{ $locale === "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3 mb-4 services_div">
                        <label class="form-label required fs-7">{{ __("Service") }}</label>
                        <select class="form-select form-select-sm service_id" data-control="select2"
                                name="service_id" id="service_id" data-close-on-select="true" data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                            <option></option>
                            @foreach($services as $item)
                                <option value="{{ $item["id"] }}">{{ $locale === "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-4 col-xl-3 mb-4 mt-lg-8">
                        <button type="submit" id="search_btn" class="btn btn-sm btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Search") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        @if(auth()->user()->can('print_work_orders_report'))
                            <button type="submit" id="print_button" class="btn btn-sm btn-success" data-kt-users-modal-action="submit">
                                <span class="indicator-label">{{ __("Print") }}</span>
                                <span class="indicator-progress">{{ __("Please wait...") }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <!--begin::Table-->
            @include('reports.services_sales_report.partials.table')
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection

@section('script')
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    <script type="text/javascript">
        $(function () {

            let branch = $('#branch_id');
            let doctor = $('#doctor_id');
            let client = $('#client_id');
            let category = $('#category_id');
            let service = $('#service_id');
            let categoriesDiv = $('.categories_div');
            let servicesDiv = $('.services_div');
            let serviceType = $('#service_type');
            categoriesDiv.hide();
            servicesDiv.hide();

            @if (isset($request))
                @if ((int)$request["service_type"] === 1)
                    serviceType.val(1).trigger("change");
                    categoriesDiv.show(200);
                @elseif ((int)$request["service_type"] === 2)
                    serviceType.val(2).trigger("change");
                    servicesDiv.show(200);
                @endif
            @endif

            SelectGetData(branch, "{{ route( 'get_branches_user' ) }}", false, "{{ $request->branch_id ?? "" }}");
            SelectGetData(client, "{{ route( 'get_clients' ) }}", false, "{{ $request->client_id ?? "" }}");
            SelectGetData(doctor, "{{ route( 'get_doctors' ) }}", false, "{{ $request->doctor_id ?? "" }}");
            SelectGetData(category, "{{ route( 'get_categories' ) }}", false, "{{ $request->category_id ?? "" }}");
{{--            SelectGetData(phase, "{{ route( 'get_phases' ) }}", false, "{{ $request->phase_id ?? "" }}");--}}

            SelectGetData(service, `/get_services`, false, "{{ $request->service_id ?? "" }}");

            branch.on('change', function () {
                let id = $(this).val();
                if (id) {
                    client.empty();
                    SelectGetData(client, "/get_branch_clients/" + id, false, "{{ $request->client_id ?? "" }}");
                }
            });

            client.on('change', function () {
                let id = $(this).val();
                if (id) {
                    doctor.empty();
                    SelectGetData(doctor, "/get_client_doctors/" + id, false, "{{ $request->doctor_id ?? "" }}");
                }
            });

            $("#search_btn").click(function(e) {
                let input = $('.input_filter');
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                input.prop('readonly', true);
                e.preventDefault();
                $("#form_search").submit();
                $("#search_btn").removeAttr('data-kt-indicator').prop('disabled', false);
                input.prop('readonly', false);
            });

            $(document).on('change', '#service_type', function(e) {
                let val = $(this).val();
                if (Number(val) === 1) {
                    categoriesDiv.show(200);
                    servicesDiv.hide(200);
                } else {
                    categoriesDiv.hide(200);
                    servicesDiv.show(200);
                }
            });

            $(document).on('click', '#print_button', function(e) {
                e.preventDefault();
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                $('.input_filter').prop('readonly', true);
                e.preventDefault();
                let Form = new FormData($("#form_search")[0]);
                $.ajax({
                    url: "{{ route("reports.services_sales_report.print") }}",
                    type: "POST",
                    data: Form,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $(data).printThis({
                            debug: false,
                            importCSS: true,
                            importStyle: true,
                            removeInline: false,
                            printDelay: 500,
                        });
                        $("#print_button").removeAttr('data-kt-indicator').prop('disabled', false);
                        $('.input_filter').prop('readonly', false);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON)
                        $("#print_button").removeAttr('data-kt-indicator').prop('disabled', false);
                        $('.input_filter').prop('readonly', false);
                        if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                        else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                    }
                });
            });

        })
    </script>
@endsection

