<div class="card-body tab-pane fade {{ str_contains( $route_name, 'settings.system_settings.general_settings.index') ? 'active show' : '' }}
{{ str_contains( $route_name, 'settings.system_settings.general_settings.tab_1') ? 'active show' : '' }}"
     id="{{ url('tab_1') }}" role="tabpanel">
    <form class="form" method="POST" action="{{ route('settings.system_settings.general_settings.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row">
            {{-- center name arabic --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label required fs-7">{{ __('Facility name arabic') }}</label>
                <input type="text" class="form-control form-control-sm" name="center_name_ar"
                       value="{{ old('center_name_ar') ?: settings('center_name_ar') }}">
                @error('center_name_ar')
                    <span class="badge badge-light-danger mt-2 text-wrap text-start lh-lg invalid_message" role="alert">{{ $message }}</span>
                @enderror
            </div>
            {{-- center name english --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label required fs-7">{{ __('Facility name english') }}</label>
                <input type="text" class="form-control form-control-sm" name="center_name_en"
                       value="{{ old('center_name_en') ?: settings('center_name_en') }}">
            </div>
            {{-- center phone --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label required fs-7">{{ __('Facility mobile') }}</label>
                <input type="text" class="form-control form-control-sm" name="center_mobile"
                       value="{{ old('center_mobile') ?: settings('center_mobile') }}" placeholder="01XXXXXXXX">
            </div>
            {{-- center mobile --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label required fs-7">{{ __('Facility phone') }}</label>
                <input type="text" class="form-control form-control-sm" name="center_phone"
                       value="{{ old('center_phone') ?: settings('center_phone') }}" placeholder="05XXXXXXXX">
            </div>

            {{-- center facebook --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Facility facebook') }}</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"> <i class="ki-outline ki-facebook fs-4"></i> </span>
                    <input type="text" class="form-control form-control-sm" name="center_facebook"
                           value="{{ old('center_facebook') ?: settings('center_facebook') }}">
                </div>
            </div>
            {{-- center twitter --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Facility twitter') }}</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"> <i class="ki-outline ki-twitter fs-4"></i> </span>
                    <input type="text" class="form-control form-control-sm" name="center_twitter"
                           value="{{ old('center_twitter') ?: settings('center_twitter') }}">
                </div>
            </div>
            {{-- center instagram --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Facility instagram') }}</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"> <i class="ki-outline ki-instagram fs-4"></i> </span>
                    <input type="text" class="form-control form-control-sm" name="center_instagram"
                           value="{{ old('center_instagram') ?: settings('center_instagram') }}">
                </div>
            </div>
            {{-- center snapchat --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Facility snapchat') }}</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1"> <i class="ki-outline ki-snapchat fs-4"></i></span>
                    <input type="text" class="form-control form-control-sm" name="center_snapchat"
                           value="{{ old('center_snapchat') ?: settings('center_snapchat') }}">
                </div>
            </div>

            {{-- center email --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Facility email') }}</label>
                <input type="email" class="form-control form-control-sm" name="center_email"
                       value="{{ old('center_email') ?: settings('center_email') }}">
            </div>
            {{-- center admin email --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Owner email') }}</label>
                <input type="email" class="form-control form-control-sm" name="center_admin_email"
                       value="{{ old('center_admin_email') ?: settings('center_admin_email') }}">
            </div>
            {{-- center admin mobile --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Owner mobile') }}</label>
                <input type="text" class="form-control form-control-sm" name="center_admin_mobile"
                       value="{{ old('center_admin_mobile') ?: settings('center_admin_mobile') }}">
            </div>
            {{-- Additional mobile 1 --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Additional mobile 1') }}</label>
                <input type="text" class="form-control form-control-sm" name="additional_mobile_1"
                       value="{{ old('additional_mobile_1') ?: settings('additional_mobile_1') }}">
            </div>
            {{-- Additional mobile 2 --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Additional mobile 2') }}</label>
                <input type="text" class="form-control form-control-sm" name="additional_mobile_2"
                       value="{{ old('additional_mobile_2') ?: settings('additional_mobile_2') }}">
            </div>
            {{-- Additional mobile 3 --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Additional mobile 3') }}</label>
                <input type="text" class="form-control form-control-sm" name="additional_mobile_3"
                       value="{{ old('additional_mobile_3') ?: settings('additional_mobile_3') }}">
            </div>
            {{-- Additional mobile 4 --}}
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <label class="form-label fs-7">{{ __('Additional mobile 4') }}</label>
                <input type="text" class="form-control form-control-sm" name="additional_mobile_4"
                       value="{{ old('additional_mobile_4') ?: settings('additional_mobile_4') }}">
            </div>
            {{-- Invoice message --}}
            <div class="col-12 mb-4">
                <label class="form-label fs-7">{{ __('Invoice message') }}</label>
                <textarea type="text" class="form-control form-control-sm" rows="4" name="invoice_message">{{ old('invoice_message') ?: settings('invoice_message') }}</textarea>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-sm btn-primary submit_button">
                    <span class="indicator-label">{{ __("Submit") }}</span>
                    <span class="indicator-progress">{{ __("Please wait...") }}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                </button>
            </div>
        </div>
    </form>
</div>
