<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200px py-2"
     data-kt-menu="true">
    <div class="menu-item px-3">
        @for($i = $col_start; $i < count($columns); ++$i)
            <a class="menu-link px-3 toggle-vis d-flex justify-content-between fs-8"
               data-column="{{ $i }}">{{ $columns[$i] }}
                <i class="ki-outline ki-check-square text-success check_col"></i>
            </a>
        @endfor
    </div>
</div>
