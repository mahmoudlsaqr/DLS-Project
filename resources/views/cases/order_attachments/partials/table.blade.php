
<!--begin::Table-->
<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __('File description') }}</th>
        <th>{{ __('File size') }}</th>
        <th>{{ __('Type') }}</th>
        @if(auth()->user()->can('view_attachments') or auth()->user()->can('download_attachments') or auth()->user()->can('remove_attachments'))
            <th>{{ __('Actions') }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
