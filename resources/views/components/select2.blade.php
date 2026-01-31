<select class="form-select form-select-sm {{ $name . ' ' . $class }}"
        data-control="select2"
        data-allow-clear="true"
        data-dropdown-parent="{{ $parent }}"
        data-placeholder="{{ !empty($placeholder) ? $placeholder : __("Please select") }}"
        name="{{ $name }}"
        id="{{ $id }}"
    {{ ($disabled ?? false) ? 'disabled' : '' }}
    {{ ($required ?? false) ? 'required' : '' }}
>
    <option></option>
    @if(!empty($options))
        @foreach ($options as $key => $value)
            @if(is_array($value))
                <option value="{{ $key }}" {{ (string) $selected === (string) $key ? 'selected' : '' }}>
                    {{ $value[$valueName] ?? '' }}
                </option>
            @else
                <option value="{{ $key }}" {{ (string) $selected === (string) $key ? 'selected' : '' }}>
                    {{ __($value) }}
                </option>
            @endif
        @endforeach
    @endif
</select>
<span data-name="{{ $name }}"></span>
