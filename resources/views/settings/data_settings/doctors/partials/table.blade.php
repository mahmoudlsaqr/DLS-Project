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
        <th>{{ __("Doctors") }}</th>
        <th>{{ __("Employer") }}</th>
        <th>{{ __("Nationality") }}</th>
        <th>{{ __("Email") }}</th>
        <th>{{ __("Mobile") }}</th>
        <th class="w-150px">{{ __('Created at') }}</th>
        <th class="w-150px">{{ __('Updated at') }}</th>
        @if(auth()->user()->can('update status_doctors'))
        <th class="w-150px">{{ __("Status") }}</th>
        @endif
        @if(auth()->user()->can('update_doctors') or auth()->user()->can('remove_doctors') or auth()->user()->can('view details_doctors'))
        <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody></tbody>
</table>
<!--end::Table-->
