<div class="{{ $class ?? '' }} update-selector d-none">
    <button
        class="btn btn-sm btn-info rotate"
        type="button"
        data-kt-menu-trigger="click"
        data-kt-menu-placement="{{ $localeIsArabic ? 'bottom-start' : 'bottom-end' }}"
        data-kt-menu-offset="0px, 10px"
        data-kt-menu-static="true">
        {{ __("Update selector") }}
        <i class="bi bi-arrow-down fs-4 rotate-180 ms-2 me-0"></i>
    </button>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold mw-200px py-2"
         data-kt-menu="true">
        <div class="menu-item px-3">
            <a class="menu-link px-3 d-flex justify-content-between fs-8 update-status-selector" data-status="1">
                {{ __("Enable") }}
            </a>
        </div>
        <div class="menu-item px-3">
            <a class="menu-link px-3 d-flex justify-content-between fs-8 update-status-selector" data-status="0">
                {{ __("Disable") }}
            </a>
        </div>
    </div>
</div>
