@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Illuminate\Support\Facades\Route;
    use Carbon\Carbon;

    $locale = app()->getLocale();
    $User = auth()->user();
    $CurrentLocaleDirection = LaravelLocalization::getCurrentLocaleDirection();
    $route_name = Route::currentRouteName();

    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Invoices"),
    "Title" => __("Issue invoice")
])
@section('content')
    <div class="card">
        <div class="card-body pb-0">
            <form id="form_search" method="POST" action="{{ route("invoices.issue_invoice.search") }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6">{{ __('Branch') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter branch_id" name="branch_id" id="branch_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($branches as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6">{{ __('Client') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter client_id" name="client_id" id="client_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($clients as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6">{{ __('Doctor') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter doctor_id" name="doctor_id" id="doctor_id"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">
                            <option></option>
                            @foreach($doctors as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar" ? $item->name_ar : $item->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 additional_discount_div">
                        <label class="form-label fs-6">{{ __('Additional discount') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold additional_discount" oninput="input_float(this);" name="additional_discount" id="additional_discount" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 additional_percent_discount_div">
                        <label class="form-label fs-6">{{ __('Additional percent discount') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold additional_percent_discount" oninput="input_float(this);" name="additional_percent_discount" id="additional_percent_discount" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6">{{ __('Start date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['start_date'] ?? $startOfMonth }}" name="start_date" id="start_date" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6">{{ __('End date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['end_date'] ?? $endOfMonth }}" name="end_date" id="end_date" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mt-lg-8">
                        <button type="submit" id="search_btn" class="btn btn-sm btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Search") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="submit" id="issue_button" class="btn btn-sm btn-success" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Issue") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @include('invoices.issue_invoice.partials.table')
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{ url("https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js") }}"></script>
    <script type="text/javascript">
        $(function () {
            let branch = $('#branch_id');
            let doctor = $('#doctor_id');
            let client = $('#client_id');

            SelectGetData(branch, "{{ route( 'get_branches_user' ) }}", false, "{{ $request->branch_id ?? "" }}");
            SelectGetData(client, "{{ route( 'get_clients' ) }}", false, "{{ $request->client_id ?? "" }}");
            SelectGetData(doctor, "{{ route( 'get_doctors' ) }}", false, "{{ $request->doctor_id ?? "" }}");

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

            $(document).on('click', '#issue_button', function(e) {
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                $('.input_filter').prop('readonly', true);
                e.preventDefault();
                let form = new FormData($("#form_search")[0]);
                $.ajax({
                    url: "{{ route("invoices.issue_invoice.issue") }}",
                    type: "POST",
                    data: form,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire("{{ __('success') }}", response.success, 'success');
                            @if(auth()->user()->can('print_invoices'))
                                if (response['invoiceId']) {
                                    let id = response['invoiceId'];
                                    $.ajax({
                                        url: `/invoices/issue_invoice/print/${id}`,
                                        type: "GET",
                                        success: function (data) {
                                            $(data).printThis({
                                                debug: false,
                                                importCSS: true,
                                                importStyle: true,
                                                removeInline: false,
                                                printDelay: 500,
                                            });
                                        },
                                        error: function(xhr) {
                                            console.log(xhr["responseJSON"])
                                            $("#issue_button").removeAttr('data-kt-indicator').prop('disabled', false);
                                            $('.input_filter').prop('readonly', false);
                                            if (xhr["responseJSON"].error) { Swal.fire("{{ __('error') }}", '<span class="badge badge-light-danger mb-2">' + xhr["responseJSON"].error + '</span><br>', 'error'); }
                                            else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                                        }
                                    });
                                }
                            @endif
                        } else if (response.error) {
                            Swal.fire("{{ __('error') }}", response.error, 'error');
                        }

                        $("#issue_button").removeAttr('data-kt-indicator').prop('disabled', false);
                        $('.input_filter').prop('readonly', false);
                    },
                    error: function(xhr) {
                        console.log(xhr["responseJSON"])
                        $("#issue_button").removeAttr('data-kt-indicator').prop('disabled', false);
                        $('.input_filter').prop('readonly', false);
                        if (xhr["responseJSON"].error) { Swal.fire("{{ __('error') }}", '<span class="badge badge-light-danger mb-2">' + xhr["responseJSON"].error + '</span><br>', 'error'); }
                        else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                    }
                });
            });
        })

        $(".additional_discount_div").hide();
        $(".additional_percent_discount_div").hide();

        function toggleDivBasedOnElements(elements, div) {
            function checkAndToggle() {
                const allFilled = elements.every(ele => ele.val() !== null && ele.val().trim() !== "");
                if (allFilled) {
                    div.show(100);
                } else {
                    div.hide(100);
                }
            }
            elements.forEach(ele => ele.on("change", checkAndToggle));
            checkAndToggle();
        }
        toggleDivBasedOnElements([$(".branch_id"), $(".client_id"), $(".doctor_id")], $(".additional_discount_div"))
        toggleDivBasedOnElements([$(".branch_id"), $(".client_id"), $(".doctor_id")], $(".additional_percent_discount_div"))

        $(document).on('change', '.additional_discount', function(e) {
            let percentDiscountVal = $(".additional_percent_discount").val;
            if (percentDiscountVal) {
                $(".additional_percent_discount").val(null);
            }
        });

        $(document).on('change', '.additional_percent_discount', function(e) {
            let percentDiscountVal = $(".additional_discount").val;
            if (percentDiscountVal) {
                $(".additional_discount").val(null);
            }
        });

    </script>
@endsection

