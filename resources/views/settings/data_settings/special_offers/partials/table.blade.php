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
        <th>{{ __("Offer name") }}</th>
        <th>{{ __("Client") }}</th>
        <th>{{ __("Discount") }}</th>
        <th>{{ __("Start date") }}</th>
        <th>{{ __("End date") }}</th>
        @if(auth()->user()->can('update_special_offers') or auth()->user()->can('remove_special_offers'))
        <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
