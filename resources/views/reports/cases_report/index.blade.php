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
    "Title" => __("Cases report")
])
@section('content')
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pb-0">
            <form id="form_search" method="POST" action="{{ route("reports.cases_report.report") }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Designer') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter" name="designer"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                                data-allow-clear="false">
                            <option></option>
                            <option value="0" @if(isset($request['designer'])) @if("0" == $request['designer']) selected @endif @endif>{{ __("All") }}</option>
                            @foreach($Users as $item)
                                @if($item->roles->value("name") == "designer")
                                    <option value="{{ $item["id"] }}" @if(isset($request['designer'])) @if($item["id"] == $request['designer']) selected @endif @endif>{{ $item["name"] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Physician') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter" name="physician"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                                data-allow-clear="false">
                            <option></option>
                            <option value="0" @if(isset($request['physician'])) @if("0" == $request['physician']) selected @endif @endif>{{ __("All") }}</option>
                            @foreach($Users as $item)
                                @if($item->roles->value("name") == "physician")
                                    <option value="{{ $item["id"] }}" @if(isset($request['physician'])) @if($item["id"] == $request['physician']) selected @endif @endif>{{ $item["name"] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Supervising') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter" name="supervising"
                                data-kt-select2="true" data-placeholder="{{ __("Select option") }}"
                                data-allow-clear="false">
                            <option></option>
                            <option value="0" @if(isset($request['supervising'])) @if("0" == $request['supervising']) selected @endif @endif>{{ __("All") }}</option>
                            @foreach($Users as $item)
                                @if($item->roles->value("name") == "supervising")
                                    <option value="{{ $item["id"] }}" @if(isset($request['supervising'])) @if($item["id"] == $request['supervising']) selected @endif @endif>{{ $item["name"] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Status') }}</label>
                        <select class="form-select form-select-sm fw-bold input_filter" data-kt-select2="true" name="status"
                                data-placeholder="{{ __("Select option") }}" data-allow-clear="false">
                            <option></option>
                            <option value="0" @if(isset($request['status'])) @if("0" == $request['status']) selected @endif @endif>{{ __("All") }}</option>
                            <option value="1" @if(isset($request['status'])) @if(1 == $request['status']) selected @endif @endif>{{ __("Waiting for supervising") }}</option>
                            <option value="2" @if(isset($request['status'])) @if(2 == $request['status']) selected @endif @endif>{{ __("Waiting for physician") }}</option>
                            <option value="3" @if(isset($request['status'])) @if(3 == $request['status']) selected @endif @endif>{{ __("Accredited") }}</option>
                            <option value="4" @if(isset($request['status'])) @if(4 == $request['status']) selected @endif @endif>{{ __("Rejected from supervision") }}</option>
                            <option value="5" @if(isset($request['status'])) @if(5 == $request['status']) selected @endif @endif>{{ __("Rejected from physician") }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('Start date') }}</label>
                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['start_date'] ?? '' }}" name="start_date" id="date_from" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                        <label class="form-label fs-6 fw-semibold">{{ __('End date') }}</label>
                        <input type="date" class="form-control form-control-sm fw-bold fc-flatpickr input_filter" value="{{ $request['end_date'] ?? '' }}" name="end_date" id="date_to" placeholder="DD-MM-YYYY" />
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mt-lg-8">
                        <button type="submit" id="search_btn" class="btn btn-sm btn-primary" data-kt-users-modal-action="submit">
                            <span class="indicator-label">{{ __("Search") }}</span>
                            <span class="indicator-progress">{{ __("Please wait...") }}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        @if(auth()->user()->can('print_cases_report'))
                            <button type="submit" id="print_button" class="btn btn-sm btn-success" data-kt-users-modal-action="submit">
                                <span class="indicator-label">{{ __("Print") }}</span>
                                <span class="indicator-progress">{{ __("Please wait...") }}
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        @endif
{{--                            <button type="reset" id="reset_button" class="btn btn-sm btn-danger">{{ __("Reset") }}</button>--}}
                    </div>
                </div>
            </form>
        </div>
        <!--end::Card body-->

        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Table-->
            @include('reports.cases_report.partials.table')
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

            $("#search_btn").click(function(e) {
                $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                $('.input_filter').prop('readonly', true);
                e.preventDefault();
                $("#form_search").submit();
                $("#search_btn").removeAttr('data-kt-indicator').prop('disabled', false);
                $('.input_filter').prop('readonly', false);
            });

            @if(auth()->user()->can('print_cases_report'))
                $(document).on('click', '#print_button', function(e) {
                    e.preventDefault();
                    $(this).attr('data-kt-indicator', 'on').prop('disabled', true);
                    $('.input_filter').prop('readonly', true);
                    e.preventDefault();
                    let Form = new FormData($("#form_search")[0]);
                    $.ajax({
                        url: "{{ route("reports.cases_report.print") }}",
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

