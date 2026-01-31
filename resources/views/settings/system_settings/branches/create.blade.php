@extends('layouts.master', [
    "breadcrumb_main" => __("Branches"),
    "Title" => __("Create Branch")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("settings.system_settings.branches.index"), "label" => __("Branches list")])
@endsection
@section('content')
    <div class="card">
        <form id="form_create" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Arabic name') }}</label>
                        <input value="{{ old('name_ar') }}" class="form-control form-control-sm" name="name_ar" id="name_ar">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('English name') }}</label>
                        <input value="{{ old('name_en') }}" class="form-control form-control-sm" name="name_en" id="name_en">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Branch code') }}</label>
                        <input value="{{ old('code') }}" class="form-control form-control-sm" name="code" id="code">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Branch tin') }}</label>
                        <input value="{{ old('tin') }}" class="form-control form-control-sm" name="tin" id="tin">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Com reg') }}</label>
                        <input oninput="input_number(this);" value="{{ old('com_reg') }}" class="form-control form-control-sm" name="com_reg" id="com_reg">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Regions') }}</label>
                        <select class="form-select form-select-sm region_id" data-control="select2" name="region_id"></select>
                        <span id="region_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Cities') }}</label>
                        <select class="form-select form-select-sm city_id" data-control="select2" name="city_id" data-placeholder="{{ __('Select option') }}" >
                            <option></option>
                        </select>
                        <span id="city_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Districts') }}</label>
                        <select class="form-select form-select-sm district_id" data-control="select2" name="district_id" data-placeholder="{{ __('Select option') }}">
                            <option></option>
                        </select>
                        <span id="district_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Phone') }}</label>
                        <input oninput="input_number(this);" value="{{ old('phone') }}" class="form-control form-control-sm" name="phone" id="phone">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Mobile') }}</label>
                        <input oninput="input_number(this);" value="{{ old('mobile') }}" class="form-control form-control-sm" name="mobile" id="mobile">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Longitude') }}</label>
                        <input oninput="input_float(this);" value="{{ old('longitude') }}" class="form-control form-control-sm" name="longitude" id="longitude">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Latitude') }}</label>
                        <input oninput="input_float(this);" value="{{ old('latitude') }}" class="form-control form-control-sm" name="latitude" id="latitude">
                    </div>
                    <div class="col-lg-12 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Address') }}</label>
                        <input value="{{ old('address') }}" class="form-control form-control-sm " name="address" id="address">
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
    @include("layouts.partials.script_form_create", [
        "Form_route" => route('settings.system_settings.branches.store'),
        "Redirect_route" => route('settings.system_settings.branches.index')
    ])


    <script type="text/javascript">
        $(function () {

            SelectGetData($('.region_id'), "{{ route( 'get_regions' ) }}", false);
            $('.region_id').on('change', function () {
                let regionId = $(this).val();
                if (regionId) {
                    $('.city_id').empty();
                    SelectGetData($('.city_id'), "/get_cities_region/" + regionId, false);
                }
            });
            $('.city_id').on('change', function () {
                let cityId = $(this).val();
                if (cityId) {
                    $('.district_id').empty();
                    SelectGetData($('.district_id'), "/get_districts_city/" + cityId, false);
                }
            });

        });
    </script>
@endsection
