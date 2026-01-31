<div class="form-floating mb-7">
    <input
        type="{{ $type }}"
        class="form-control {{ $class }}"
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name) }}" />
    <label for="{{ $id }}">{{ $label }}</label>
</div>
