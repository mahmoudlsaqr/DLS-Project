<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th class="min-w-100px">{{ __("Date") }}</th>
        <th>{{ __("Branch") }}</th>
        <th>{{ __("Client") }}</th>
        <th>{{ __("Doctor") }}</th>
        <th>{{ __("Price before discount") }}</th>
        <th>{{ __("Total discount") }}</th>
        <th>{{ __("Total net") }}</th>
        <th>{{ __("Total tax") }}</th>
        <th>{{ __("Total") }}</th>
        <th>{{ __("Total paid") }}</th>
        <th>{{ __("Total residual") }}</th>
        @if(auth()->user()->can('view details_invoices') or auth()->user()->can('print_invoices') or auth()->user()->can('pay_invoices'))
        <th class="min-w-50px">{{ __("Actions") }}</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr id="{{ $row->id }}">
            <td>{{ $row->id }}</td>
            <td>{{ date('d-m-Y', strtotime($row->invoice_date)) }}</td>
            <td>{{ getLocaleName($row->branches, 'name_ar', 'name_en') }}</td>
            <td>{{ getLocaleName($row->clients, 'name_ar', 'name_en') }}</td>
            <td>{{ getLocaleName($row->doctors, 'name_ar', 'name_en') }}</td>
            <td>{{ $row->total_price }}</td>
            <td>{{ $row->total_discount_value }}</td>
            <td>{{ $row->total_total_net }}</td>
            <td>{{ $row->total_tax_value }}</td>
            <td>{{ $row->total_total }}</td>
            <td>{{ $row->total_paid }}</td>
            <td>{{ $row->total_residual }}</td>
            @if(auth()->user()->can('view details_invoices') or auth()->user()->can('print_invoices') or auth()->user()->can('pay_invoices'))
            <td>
                <div class="btn-group dropstart">
                    <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-3"></i>
                    </a>
                    <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-2" data-kt-menu="true">
                        @can('view details_invoices')
                            <div class="menu-item px-2">
                                <a class="menu-link px-3" href="{{ route('invoices.invoices_list.show', $row->id) }}" data-id="{{ $row->id }}">
                                    {{ __('View details') }}
                                </a>
                            </div>
                        @endcan

                        @can('print_invoices')
                            <div class="menu-item px-2">
                                <a data-id="{{ $row->id }}" class="menu-link px-3 print_button">
                                    {{ __('Print') }}
                                </a>
                            </div>
                        @endcan

                        @can('pay_invoices')
                            @if((float) $row->total_paid < (float) $row->total_total)
                                <div class="menu-item px-2">
                                    <a class="menu-link pay_button" data-id="{{ $row->id }}">
                                        {{ __('Pay') }}
                                    </a>
                                </div>
                            @endif
                        @endcan
                    </ul>
                </div>
            </td>
            @endif
        </tr>
    @endforeach

    </tbody>
    <tfoot>
    <tr class="bg-secondary">
        <th colspan="5" class="fw-bold fs-5">{{ __("Totals") }}</th>
        <th id="total_price"></th>
        <th id="total_discount"></th>
        <th id="total_net"></th>
        <th id="total_tax"></th>
        <th id="total_total"></th>
        <th id="total_paid"></th>
        <th id="total_residual"></th>
        <th></th>
    </tr>
    </tfoot>
</table>
