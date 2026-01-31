<div class="form-check form-check-sm form-check-custom justify-content-center m-0">
    <input class="form-check-input {{ $class ?? '' }}"
           type="checkbox"
           name="{{ $name ?? '' }}"
           id="{{ $id ?? '' }}"
           data-id="{{ $dataId ?? '' }}"
        {{ ($checked ?? false) ? 'checked' : '' }}
        {{ ($disabled ?? false) ? 'disabled' : '' }} />
</div>
