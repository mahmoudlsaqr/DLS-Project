<!--begin::Table-->
<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th class="w-25px">
            <div class="form-check form-switch d-flex justify-content-center align-items-center">
                <input type="checkbox" class="form-check-input h-20px w-20px p-0 check_all"/>
            </div>
        </th>
        <th>#</th>
        <th>{{ __("Number") }}</th>
        <th>{{ __("Service") }}</th>
        <th>{{ __("Service code") }}</th>
        <th>{{ __("Service price") }}</th>
        <th>{{ __("Service discount") }}</th>
        <th>{{ __("Price") }}</th>
        <th>{{ __("Category") }}</th>
        @if(auth()->user()->can('update status details_doctors'))
        <th class="w-150px">{{ __("Status") }}</th>
        @endif
        @if(auth()->user()->can('remove details_doctors'))
        <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
