<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th class="w-25px">
            <div class="form-check form-switch d-flex justify-content-center align-items-center">
                <input type="checkbox" class="form-check-input h-20px w-20px p-0 check_all"/>
            </div>
        </th>
        <th>#</th>
        <th>{{ __("Service") }}</th>
        <th>{{ __("Category") }}</th>
        <th>{{ __("Service code") }}</th>
        <th>{{ __("Service cost") }}</th>
        <th>{{ __("Service price") }}</th>
        <th>{{ __("Tax") }}</th>
        <th>{{ __("Price + Tax") }}</th>
        <th>{{ __("Note") }}</th>
        @if(auth()->user()->can('update status_services'))
        <th class="w-150px">{{ __("Status") }}</th>
        @endif
        @if(auth()->user()->can('update_services') or auth()->user()->can('remove_services'))
        <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
