<!--begin::Table-->
<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __('District') }}</th>
        <th>{{ __('Region') }}</th>
        <th>{{ __('City') }}</th>
        @if(auth()->user()->can('update status_districts'))
            <th class="w-150px">{{ __('Status') }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
