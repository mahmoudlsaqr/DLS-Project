<div class="card-body tab-pane fade {{ str_contains( $route_name, 'settings.system_settings.general_settings.tab_2') ? 'active show' : '' }}"
     id="{{ url('tab_2') }}" role="tabpanel">
    <form class="form" method="POST" action="{{ route('settings.system_settings.general_settings.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row">
            <div class="form-check form-switch col-md-6 col-lg-4 col-xl-3 mb-4">
                <input type="hidden" name="whatsapp_check" value="0">
                <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="whatsapp_check" id="whatsapp_check"
                       value="1"  {{ settings("whatsapp_check") || old("whatsapp_check", 0) === 1 ? 'checked' : '' }} />
                <label class="fs-7 fw-semibold form-label" for="whatsapp_check">
                    {{ __('WhatsApp') }}
                </label>
            </div>
            <div class="form-check form-switch col-md-6 col-lg-4 col-xl-3 mb-4">
                <input type="hidden" name="sms_check" value="0">
                <input class="form-check-input h-20px w-20px p-0" type="checkbox" name="sms_check" id="sms_check"
                       value="1"  {{ settings("sms_check") || old("sms_check", 0) === 1 ? 'checked' : '' }} />
                <label class="fs-7 fw-semibold form-label" for="sms_check">
                    {{ __('SMS') }}
                </label>
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
