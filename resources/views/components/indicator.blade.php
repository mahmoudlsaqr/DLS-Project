<button {{ $attributes->merge(['class' => 'btn btn-sm fw-bold btn-primary']) }}>
    <span class="indicator-label">
        {{ $slot }}
        @if($fresh ?? false)
            <i class="bi bi-rocket fs-4 ms-2 me-0"></i>
        @endif
    </span>
    <span class="indicator-progress">
        {{ __("Please wait...") }}
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
    </span>
</button>
