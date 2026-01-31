@php
    use App\Models\User;
    $locale = app() ->getLocale();
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __("Number") }}</th>
        <th>{{ __("Date") }}</th>
        <th>{{ __("File number") }}</th>
        <th>{{ __("Patient name") }}</th>
        <th>{{ __("Statement") }}</th>
        <th>{{ __("Service") }}</th>
        <th>{{ __("Doctor") }}</th>
        <th>{{ __("Employer") }}</th>
        <th>{{ __("Color") }}</th>
        <th class="w-50px">{{ __("Quantity") }}</th>
        @if(auth()->user()->can('show prices_work_orders_management'))
        <th>{{ __("Total net") }}</th>
        <th>{{ __("Tax") }}</th>
        <th>{{ __("Total") }}</th>
        @endif
        <th>{{ __("Phase") }}</th>
        <th class="min-w-150px">{{ __("Created at") }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($workOrders) and count($workOrders) > 0)
            @foreach($workOrders as $item)
                <tr>
                    <td>{{ $item["id"] }}</td>
                    <td>{{ $item->work_order_number }}</td>
                    <td>{{ date("d-m-Y", strtotime($item->work_order_date)) }}</td>
                    <td>{{ $item->file_number }}</td>
                    <td>{{ $item->patient_name }}</td>
                    <td>{{ $item->statement }}</td>
                    <td>{{ $locale == "ar" ? $item->services->name_ar : $item->services->name_en }}</td>
                    <td>{{ $locale == "ar" ? $item->doctors->name_ar : $item->doctors->name_en }}</td>
                    <td>{{ $locale == "ar" ? $item->clients->name_ar : $item->clients->name_en }}</td>
                    <td>{{ $item->colors->color_code }}</td>
                    <td>{{ $item->service_count }}</td>
                    @if(auth()->user()->can('show prices_work_orders_management'))
                    <td>{{ $item->service_total_net }}</td>
                    <td>{{ $item->service_tax }}</td>
                    <td>{{ $item->service_total }}</td>
                    @endif
                    <td><span class="badge badge-light-info">{{ $locale == "ar" ? $item->phases->name_ar : $item->phases->name_en }}</span></td>
                    <td>
                        @if ($item["created_at"] != null)
                            <div class="fw-bold text-gray-800">
                                @php
                                    $date = $item["created_at"] ->format('d-m-Y');
                                    $time = $item["created_at"] ->format('h:i');
                                    $NewDateTime = date('A', strtotime($item["created_at"]));
                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                @endphp
                                <span>{{ $date }}</span><br>
                                <span>{{ $time }}</span>
                                <span>{{ $DateTimeType }}</span>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="16">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
            <td colspan="10">{{ __("Totals") }}</td>
            <td>{{ $totals['service_count'] }}</td>
            @if(auth()->user()->can('show prices_work_orders_management'))
                <td>{{ $totals['service_total_net'] }}</td>
                <td>{{ $totals['service_tax_value'] }}</td>
                <td>{{ $totals['service_total'] }}</td>
                <td colspan="2"></td>
            @endif
        </tr>
    </tfoot>
</table>
