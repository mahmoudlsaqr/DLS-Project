<div class="menu-item">
    <a class="menu-link {{ str_starts_with($thisRoute, $match ?? $route) ? 'active' : '' }} py-3"
           href="{{ !empty($route) ? route($route) : '#' }}" >
            @if($icon)
            <span class="menu-icon">
                <i class="ki-outline {{ $icon }} fs-3"></i>
            </span>
            @else
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
            @endif
        <span class="menu-title">{{ __($text) }}</span>
    </a>
</div>
