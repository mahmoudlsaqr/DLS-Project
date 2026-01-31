<div data-kt-menu-trigger="{default:'click', lg: 'hover'}"
     data-kt-menu-placement="{{ $locale === 'ar' ? 'left-start' : 'right-start' }}"
     class="menu-item menu-lg-down-accordion">

    <span class="menu-link {{ $isActive() ? 'active' : '' }} py-3">
        <span class="menu-icon">
            @if($icon)
                <i class="ki-outline {{ $icon }} fs-3"></i>
            @endif
        </span>
        <span class="menu-title">{{ $title }}</span>
        <span class="menu-arrow"></span>
    </span>

    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg px-lg-2 py-lg-4 w-lg-225px">
        {{ $slot }}
    </div>
</div>
