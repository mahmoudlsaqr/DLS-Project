@php
    $inputAttributes = $attributes->merge([
        'class' => "form-control form-control-sm $class",
        'type' => $type,
        'name' => $name,
        'id' => $id,
        'value' => old($name, $value),
        'placeholder' => $placeholder,
    ]);

    if (!empty($oninput)) {
        $inputAttributes = $inputAttributes->merge(['oninput' => $oninput]);
    }

    if (!empty($required)) {
        $inputAttributes = $inputAttributes->merge(['required' => true]);
    }
@endphp

@if($group)
    <div class="input-group input-group-sm mb-5">
        <input {{ $inputAttributes }} />
        <span class="input-group-text">{{ $groupText }}</span>
    </div>
@else
    <input {{ $inputAttributes }} />
    @if ($type === 'date')
        <span data-name="{{ $name }}"></span>
    @endif
@endif
