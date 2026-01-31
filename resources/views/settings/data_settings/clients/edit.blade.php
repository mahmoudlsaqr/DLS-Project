@php
    $locale = app()->getLocale();
@endphp
@extends('layouts.master', [
    "breadcrumb_main" => __("Clients"),
    "Title" => __("Edit client")
])
@section("toolbar_buttons")
    @include("layouts.partials.back_button", ["link" => route("settings.data_settings.clients.index"), "label" => __("Clients list")])
@endsection
@section('content')
    <div class="card">
        <form id="form_update" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="row">
                    <input value="{{ $client["id"] }}" type="hidden" name="id">
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Arabic name') }}</label>
                        <input type="text" value="{{ old('name_ar')? : $client["name_ar"] }}" class="form-control form-control-sm" name="name_ar" id="name_ar">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('English name') }}</label>
                        <input type="text" value="{{ old('name_en')? : $client["name_en"] }}" class="form-control form-control-sm" name="name_en" id="name_en">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Tax number') }}</label>
                        <input value="{{ old('tax_number')? : $client["tax_number"] }}" class="form-control form-control-sm" name="tax_number" id="tax_number">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Discount') }}</label>
                        <input oninput="input_float(this); max_min_val(this, 100, 0)" value="{{ $client["discount"] ?? "" }}" class="form-control form-control-sm" name="discount" id="discount">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Commercial register') }}</label>
                        <input value="{{ old('commercial_register')? : $client["commercial_register"] }}" class="form-control form-control-sm" name="commercial_register" id="commercial_register">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Regions') }}</label>
                        <select class="form-select form-select-sm region_id" data-control="select2" name="region_id">
                            @foreach ($regions as $item)
                                <option value="{{ $item["id"] }}"> {{ ($locale == 'ar') ? $item["name_ar"] : $item["name_en"] }} </option>
                            @endforeach
                        </select>
                        <span id="region_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Cities') }}</label>
                        <select class="form-select form-select-sm city_id" data-control="select2" name="city_id" data-placeholder="{{ __('Select option') }}" >
                            @foreach ($cities as $item)
                                @if($item["region_id"] === $client["region_id"])
                                    <option value="{{ $item["id"] }}" @if($item["id"] == $client["city_id"]) selected @endif> {{ ($locale == 'ar') ? $item["name_ar"] : $item["name_en"] }} </option>
                                @endif
                            @endforeach
                        </select>
                        <span id="city_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Districts') }}</label>
                        <select class="form-select form-select-sm district_id" data-control="select2" name="district_id" data-placeholder="{{ __('Select option') }}">
                            @foreach ($districts as $item)
                                @if($item["city_id"] === $client["city_id"])
                                    <option value="{{ $item["id"] }}" @if($item["id"] === $client["district_id"]) selected @endif> {{ ($locale == 'ar') ? $item["name_ar"] : $item["name_en"] }} </option>
                                @endif
                            @endforeach
                        </select>
                        <span id="district_id"></span>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7">{{ __("Branch") }}</label>
                        <select class="form-select form-select-sm branch_id" data-control="select2"
                                name="branch_id" data-close-on-select="true"
                                data-placeholder="{{ __('Select option') }}" data-allow-clear="true">
                            @foreach($branches as $item)
                                <option value="{{ $item["id"] }}">{{ $locale == "ar"? $item["name_ar"] : $item["name_en"] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Phone') }}</label>
                        <input type="text" oninput="input_number(this);" value="{{ old('phone')? : $client["phone"] }}" class="form-control form-control-sm" name="phone" id="phone">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label required fs-7 fw-semibold">{{ __('Mobile') }}</label>
                        <input type="text" oninput="input_number(this);" value="{{ old('mobile')? : $client["mobile"] }}" class="form-control form-control-sm" name="mobile" id="mobile">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Longitude') }}</label>
                        <input type="text" oninput="input_float(this);" value="{{ old('longitude')? : $client["longitude"] }}" class="form-control form-control-sm" name="longitude" id="longitude">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Latitude') }}</label>
                        <input type="text" oninput="input_float(this);" value="{{ old('latitude')? : $client["latitude"] }}" class="form-control form-control-sm" name="latitude" id="latitude">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Commissioner') }}</label>
                        <input value="{{ old('commissioner')? : $client["commissioner"]  }}" class="form-control form-control-sm " name="commissioner" id="commissioner">
                    </div>
                    <div class="col-lg-3 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Commissioner number') }}</label>
                        <input oninput="input_number(this);" value="{{ old('commissioner_number')? : $client["commissioner_number"]  }}" class="form-control form-control-sm" name="commissioner_number" id="commissioner_number">
                    </div>
                    <div class="col-lg-9 mb-4">
                        <label class="form-label fs-7 fw-semibold">{{ __('Address') }}</label>
                        <input type="text" value="{{ old('address')? : $client["address"] }}" class="form-control form-control-sm " name="address" id="address">
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
        "Form_route" => route('settings.data_settings.clients.update', [$client["id"]]),
        "Redirect_route" => route('settings.data_settings.clients.index')
    ])

    <script type="text/javascript">
        $(function () {
            let region = $('.region_id');
            let city = $('.city_id');
            let district = $('.district_id');
            SelectGetData($('.branch_id'), "{{ route( 'get_branches' ) }}", false, "{{ $client["branch_id"] ?? "" }}");
{{--            SelectGetData(region, "{{ route( 'get_regions' ) }}", false);--}}
            region.on('change', function () {
                let regionId = $(this).val();
                if (regionId) {
                    city.empty();
                    SelectGetData(city, "/get_cities_region/" + regionId, false);
                }
            });
            city.on('change', function () {
                let cityId = $(this).val();
                if (cityId) {
                    district.empty();
                    SelectGetData(district, "/get_districts_city/" + cityId, false);
                }
            });
        });
    </script>
@endsection
