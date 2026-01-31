<div class="btn-group dropstart">
    <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical fs-3"></i>
    </a>
    <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-100px p-2" data-kt-menu="true">

        {!! $slot !!}

        @isset($deleteName)
            <x-delete-button :id="$id" :delete-name="$deleteName" />
        @endisset
    </ul>
</div>
