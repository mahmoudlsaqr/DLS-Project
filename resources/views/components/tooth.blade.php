@props([
    'tooth',
    'type' => 'upper-left',
    'patient' => null,
    'svgFirst' => false,
])

@php


    $isUpper = str_starts_with($type, 'upper');
    $isLower = str_starts_with($type, 'lower');

    $toothClass = $isLower ? 'lower_tooth' : 'upper_tooth';
    $img = data_get($tooth, 'img');
    $height = data_get($tooth, 'height');
    $name = data_get($tooth, 'name');
    $id = data_get($tooth, 'id');
    $imagePath = asset("assets/app/media/dental_images/{$toothClass}/{$id}/{$img}");

    // نحدد اسم ملف الـ SVG حسب نوع الفك
    $svgView = match ($type) {
        'upper-right' => 'components.tooth-svg-upper-right',
        'lower-right' => 'components.tooth-svg-lower-right',
        'upper-left' => 'components.tooth-svg-upper-left',
        'lower-left' => 'components.tooth-svg-lower-left',
        default => 'components.tooth-svg', // fallback
    };
       $canals = $patient ? getToothCanals($id, $patient->id) : null;
@endphp

<li tooth="{{ $name }}" class="tooth-item {{ $toothClass }} {{ $type }}">
    <div class="tooth-wrapper">
        @if($isUpper)
            <div class="tooth-label-row">
                <span class="badge tooth-badge">{{ $id }}</span>
            </div>
            <div class="tooth-label-row">
                <span class="badge tooth-canals">{{ $canals ?? '' }}</span>
            </div>
        @endif

        @if ($svgFirst)
            @include($svgView, ['id' => $id, 'patient' => $patient])
        @endif

        <img
            class="t_image"
            alt="{{ $name }}"
            src="{{ $imagePath }}"
            height="{{ $height }}"
            id="to{{ $id }}"
            data-tooth="{{ $name }}"
            data-tooth-id="{{ $id }}"
            data-patient-id="{{ $patient->id }}"
            onclick="toggleSelectTooth(this)"
        >

        @unless($svgFirst)
            @include($svgView, ['id' => $id, 'patient' => $patient])
        @endunless

        @if($isLower)
            <div class="tooth-label-row">
                <span class="badge tooth-canals">{{ $canals ?? '' }}</span>
            </div>
            <div class="tooth-label-row">
                <span class="badge tooth-badge">{{ $id }}</span>
            </div>
        @endif
    </div>
</li>



