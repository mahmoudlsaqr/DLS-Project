<div {{ $attributes->merge(['class' => 'card card-1']) }}>
    @if ($header)
    <div class="card-header bg-light">
        <div class="card-title">
            <div class="card-label text-dark {{ $cardLabel }}">
                {{ $label ?? '' }}
            </div>
        </div>
        <div class="card-toolbar gap-2">
            {{ $toolbar ?? '' }}
        </div>
    </div>
    @endif

    <div class="card-body {{ $cardBody }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
