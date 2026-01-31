<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th class="w-25px">
            <div class="form-check form-switch d-flex justify-content-center align-items-center">
                <input type="checkbox" class="form-check-input h-20px w-20px p-0 check_all"/>
            </div>
        </th>
        <th class="w-50px">#</th>
        <th class="w-100px">{{ __("Number") }}</th>
        <th class="w-100px">{{ __("Date") }}</th>
        <th>{{ __("Client") }}</th>
        <th>{{ __("Doctor") }}</th>
        <th>{{ __("Patient") }}</th>
        <th>{{ __("File number") }}</th>
        <th>{{ __("Service") }}</th>
        <th>{{ __("Color") }}</th>
        @if(auth()->user()->can('show prices_work_orders_management'))
            <th>{{ __("Quantity") }}</th>
            <th>{{ __("Total") }}</th>
        @endif
        <th>{{ __("Branch") }}</th>
        <th>{{ __("Phase") }}</th>
        <th class="w-150px">{{ __("Created by") }}</th>
        @if(auth()->user()->can('update status_work_orders_management'))
            <th class="w-150px">{{ __("Status") }}</th>
        @endif
        @canany(['update_work_orders_management', 'remove_work_orders_management', 'log phases details_work_orders_management', 'update phase_work_orders_management', 'print statement_work_orders_management', 'print_work_orders_management', 'view details_work_orders_management'])
            <th class="w-100px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach ($workOrders as $index => $row)
        <tr id="{{ $row->id }}">
            <td>
                <div class="form-check form-switch d-flex justify-content-center align-items-center">
                    <input class="form-check-input h-20px w-20px p-0 child_check"
                           data-id="{{ $row->id }}" type="checkbox" data-kt-check="true" />
                </div>
            </td>
            <td>{{ $row->id }}</td>
            <td>{{ $row->work_order_number }}</td>
            <td>{{ $row->work_order_date ? date('d-m-Y', strtotime($row->work_order_date)) : '' }}</td>
            <td>{{ getLocaleName($row->clients, "name_ar", "name_en") }}</td>
            <td>{{ getLocaleName(optional($row->doctors), "name_ar", "name_en") }}</td>
            <td>{{ $row->patient_name }}</td>
            <td>{{ $row->file_number }}</td>
            <td>{{ getLocaleName($row->services, "name_ar", "name_en") }}</td>
            <td>{{ optional($row->colors)->color_code ?? '' }}</td>
            @if(auth()->user()->can('show prices_work_orders_management'))
            <td>{{ $row->service_count }}</td>
            <td>{{ $row->service_total }}</td>
            @endif
            <td>{{ getLocaleName($row->branches, "name_ar", "name_en") }}</td>
            <td>{{ getLocaleName($row->phases, "name_ar", "name_en") }}</td>
            <td>
                @php
                    $strtotime = date('d-m-Y', strtotime($row->created_at));
                    $date = date('d-m-Y', strtotime($row->created_at));
                    $time = date('h:i', strtotime($row->created_at));
                    $DateTimeType = ((date('A', strtotime($row->created_at)) == "AM") ? __('AM') : __('PM'));
                 @endphp
                <span class="text-primary">{{ $row->createdBy->name }}</span><br>
                <span>{{ $date }} <span class="text-gray-600 ms-1">{{ $time.$DateTimeType }}</span></span>
            </td>
            @if(auth()->user()->can('update status_work_orders_management'))
                <td>
                    <div class="d-flex justify-content-start align-items-center">
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input h-20px w-20px p-0"
                                   {{ $row->status === 'true' ? 'checked' : '' }}
                                   type="checkbox" name="status" value="{{ $row->status }}"
                                   data-id="{{ $row->id }}" id="userStatus{{ $row->id }}" />
                        </div>
                        <span class="badge {{ $row->status === 'true' ? 'badge-light-primary' : 'badge-light-danger' }}">
                        {{ $row->status === 'true' ? __('Active') : __('Inactive') }}
                    </span>
                    </div>
                </td>
            @endif
            @canany(['update_work_orders_management', 'remove_work_orders_management', 'log phases details_work_orders_management', 'update phase_work_orders_management', 'print statement_work_orders_management', 'print_work_orders_management', 'view details_work_orders_management'])
            <td>
                <div class="btn-group dropstart">
                    <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-3"></i>
                    </a>
                    <div class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-2" data-kt-menu="true">
                        @can('log phases details_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3 view_details" data-id="{{ $row->id }}">{{ __("Log phases") }}</a>
                            </div>
                        @endcan
                        @can('update phase_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3 update_phase" data-id="{{ $row->id }}">{{ __("Update phase") }}</a>
                            </div>
                        @endcan
                        @can('print statement_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3 print_statement_button" data-id="{{ $row->id }}">{{ __("Print statement") }}</a>
                            </div>
                        @endcan
                        @can('print_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3 print_button" data-id="{{ $row->id }}">{{ __("Print barcode") }}</a>
                            </div>
                        @endcan
                        @can('view details_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3" href="{{ route('work_orders.work_orders_management.show', $row->id) }}" data-id="{{ $row->id }}">{{ __("View details") }}</a>
                            </div>
                        @endcan
                        @can('update_work_orders_management')
                            <div class="menu-item px-2">
                                <a href="{{ route('work_orders.work_orders_management.edit', $row->id) }}" class="menu-link px-3">{{ __("Edit") }}</a>
                            </div>
                        @endcan
                        @can('remove_work_orders_management')
                            <div class="menu-item px-2">
                                <a class="menu-link delete-confirm" data-id="{{ $row->id }}" data-name="{{ getLocaleName($row, 'name_ar', 'name_en') }}">{{ __("Delete") }}</a>
                            </div>
                        @endcan
                    </div>
                </div>
            </td>
            @endif
        </tr>
    @endforeach
</table>
