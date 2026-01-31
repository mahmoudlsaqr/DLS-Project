<div class="form-check form-switch form-check-custom">
    @if($valueHidden !== null)
        <x-form-input type="hidden" name="{{ $name }}" id="hidden-{{ $id }}" value="{{ $valueHidden }}" />
    @endif
    <input type="{{ !empty($type) ? $type : "checkbox" }}"
           name="{{ $name }}"
           id="{{ $id }}"
           class="form-check-input h-20px w-30px {{ $class }}"
           value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}>
    @if ($label)
        <label class="form-label form-check-label m-0 ms-4" for="{{ $id }}">{{ $label }}</label>
    @endif
</div>
