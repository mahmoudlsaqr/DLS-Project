<div class="d-flex justify-content-{{ $search? 'between' : 'end' }} align-items-center flex-wrap {{ $class ?? '' }}">
    @if($search)
    <x-search-input />
    @endif
    <div class="d-flex justify-content-end align-items-center gap-2 {{ $class ?? '' }}">
        @if($filters)
            <x-button class="btn-primary button-filters" data-bs-toggle="modal" data-bs-target="#modal_filter">
                {{ __('Filter') }}
                <i class="ki-duotone ki-filter fs-4 ms-2 me-0"><span class="path1"></span><span class="path2"></span></i>
            </x-button>
        @endif
            <button class="btn btn-sm btn-primary rotate"
                    type="button"
                    data-kt-menu-trigger="click"
                    data-kt-menu-placement="{{ $localeIsArabic ? 'bottom-start' : 'bottom-end' }}"
                    data-kt-menu-offset="0px, 10px"
                    data-kt-menu-static="true">
                {{ __("Filter columns") }}
                <i class="bi bi-arrow-down fs-4 rotate-180 ms-2 me-0"></i>
            </button>
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold mw-200px py-2"
                 data-kt-menu="true">
                <div class="menu-item px-3">
                    @foreach($columns as $index => $column)
                        @if(count($columns)-($sub ?? 0) > $index)
                        <a class="menu-link px-3 toggle-vis d-flex justify-content-between fs-8"
                           data-column="{{ $checkbox? $index+$start : $index }}">
                            {{ $column['label'] }}
                            <i class="bi bi-check-square text-gray-800 check_col"></i>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
    </div>
</div>
