<!--begin::Table-->
<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __("Case number") }}</th>
        <th>{{ __("Physician") }}</th>
        <th>{{ __("Clinic name") }}</th>
        <th>{{ __("Amount") }}</th>
        <th>{{ __("Description") }}</th>
        <th class="w-150px">{{ __('Created at') }}</th>
        <th class="w-150px">{{ __('Updated at') }}</th>
        @if (auth()->user()->can('update_receipt_vouchers'))
            <th>{{ __("Action") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
