<textarea
    name="{{ $name }}"
    id="{{ $id }}"
    class="form-control form-control-sm {{ $class }}"
    placeholder="{{ $placeholder }}"
    rows="{{ $rows }}"
    {{ $required ? 'required' : '' }}>{{ $value }}
</textarea>
