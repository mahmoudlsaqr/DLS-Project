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
    "Title" => __("Performance evaluation report")
])
@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pb-0">
            <form id="form_search" method="POST" action="{{ route("reports.performance_evaluation_report.report") }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Start date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['start_date'] ?? "" }}" name="start_date" id="start_date" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('End date') }}</label>
                        <input type="text" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['end_date'] ?? "" }}" name="end_date" id="end_date" placeholder="DD-MM-YYYY" />
                    </div>
{{--                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">--}}
{{--                        <label class="form-label fs-6 fw-semibold">{{ __('Phases') }}</label>--}}
{{--                        <select class="form-select form-select-sm fw-bold input_filter phase_id" name="phase_id" id="phase_id"--}}
{{--                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}" data-allow-clear="true">--}}
{{--                            <option></option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mt-lg-8">
                        <button type="submit" id="search_btn" class="btn btn-sm btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Search") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        @if(auth()->user()->can('print_performance_evaluation_report'))
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
            @include('reports.performance_evaluation_report.partials.table')
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
            // $('#reset_button').click(function() {
            //     $('.input_filter').val(null).trigger('change');
            //     $('select.input_filter').val("0").trigger('change');
            // });

            let phase = $('#phase_id');
            SelectGetData(phase, "{{ route( 'get_phases' ) }}", false, "{{ $request->phase_id ?? "" }}");

            $("#search_btn").click(function(e) {
                e.preventDefault();
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                $('.input_filter').prop('readonly', true);

                setTimeout(function () {
                    $("#form_search").submit();
                    $("#search_btn").removeAttr('data-kt-indicator').prop('disabled', false);
                    $('.input_filter').prop('readonly', false);
                }, 1000)
            });

            @if(auth()->user()->can('print_performance_evaluation_report'))
            $(document).on('click', '#print_button', function(e) {
                e.preventDefault();
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                $('.input_filter').prop('readonly', true);
                e.preventDefault();
                let Form = new FormData($("#form_search")[0]);
                $.ajax({
                    url: "{{ route("reports.performance_evaluation_report.print") }}",
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
            @endif

        })
    </script>
@endsection

