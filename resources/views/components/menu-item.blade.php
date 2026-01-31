<li class="menu-item">
    <a {{ isset($route) ? 'href=' . $route : '' }} class="menu-link {{ $class ?? '' }}" data-id="{{ $dataId ?? '' }}">
        {{ $slot }}
    </a>
</li>
