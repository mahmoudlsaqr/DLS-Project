<div class="d-flex align-items-center justify-content-start">
    <div class="form-check form-switch">
        <input class="form-check-input h-20px w-30px toggle-status {{ $class ?? '' }}"
               type="checkbox"
               name="{{ $name ?? '' }}"
               value="{{ $value ?? '' }}"
               data-id="{{ $dataId ?? '' }}"
               id="{{ $id ?? '' }}"
            {{ ($checked ?? false) ? 'checked' : '' }} />
    </div>
    <span class="badge badge-light-{{ ($checked ?? false) ? "success" : "danger" }} mb-1">{{ ($checked ?? false) ? __("Active") : __("InActive") }}</span>
</div>
