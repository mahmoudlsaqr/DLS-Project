<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __('Nationality') }}</th>
        <th>{{ __('Country') }}</th>
        <th>{{ __('Alpha 2') }}</th>
        <th>{{ __('Alpha 3') }}</th>
        <th>{{ __('Numeric 3') }}</th>
        @if(auth()->user()->can('update status_nationalities'))
            <th class="w-150px">{{ __('Status') }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
