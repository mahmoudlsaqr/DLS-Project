<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th class="w-25px">
            <div class="form-check form-switch d-flex justify-content-center align-items-center">
                <input type="checkbox" class="form-check-input h-20px w-20px p-0 check_all"/>
            </div>
        </th>
        <th>#</th>
        <th>{{ __('Branch name') }}</th>
        <th>{{ __('Branch code') }}</th>
        <th>{{ __('City') }}</th>
        <th>{{ __('District') }}</th>
        <th>{{ __('Mobile') }}</th>
        <th>{{ __('Branch tin') }}</th>
        <th>{{ __('Com reg') }}</th>
        @if(auth()->user()->can('update status_branches'))
            <th class="w-150px">{{ __("Status") }}</th>
        @endif
        @if(auth()->user()->can('update_branches') or auth()->user()->can('remove_branches'))
        <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
