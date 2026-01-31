@php
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
        <th>{{ __("Color code") }}</th>
        <th>{{ __("Quantity") }}</th>
        <th>{{ __("Price") }}</th>
        <th>{{ __("Discount") }}</th>
        <th>{{ __("Discount value") }}</th>
        <th>{{ __("Total net") }}</th>
        <th>{{ __("Tax") }}</th>
        <th>{{ __("Tax value") }}</th>
        <th>{{ __("Total") }}</th>
        <th>{{ __("Phase") }}</th>
        <th class="w-150px">{{ __("Created at") }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($workOrders) and count($workOrders) > 0)
            @foreach($workOrders as $index => $item)
                <tr class="{{ $item["id"] % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                    <td style="padding: 0 2em !important;">{{ $index + 1 }}</td>
                    <td>{{ $item->work_order_number }}</td>
                    <td>{{ date("d-m-Y", strtotime($item->work_order_date)) }}</td>
                    <td>{{ $item->file_number }}</td>
                    <td>{{ $item->patient_name }}</td>
                    <td>{{ $item->statement }}</td>
                    <td>{{ $locale == "ar" ? $item->services->name_ar : $item->services->name_en }}</td>
                    <td>{{ $item->colors->color_code }}</td>
                    <td>{{ $item->service_count }}</td>
                    <td>{{ $item->service_price }}</td>
                    <td>{{ $item->service_discount }}%</td>
                    <td>{{ $item->service_discount_value }}</td>
                    <td>{{ $item->service_total_net }}</td>
                    <td>{{ $item->service_tax }}%</td>
                    <td>{{ $item->service_tax_value }}</td>
                    <td>{{ $item->service_total }}</td>
                    <td><span class="badge badge-light-info">{{ $locale == "ar" ? $item->phases->name_ar : $item->phases->name_en }}</span></td>
                    <td>
                        @if ($item["created_at"] != null)
                            <div class="mb-6">
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
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="16" class="p-6">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
    @if(!empty($workOrders) and count($workOrders) > 0)
        <tfoot>
            <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                    @if(isset($total) and !empty($total))
                        <td colspan="8" class="fw-bold fs-5">{{ __("Totals") }}</td>
                        <td>{{ $total['sum_count'] ?? '0' }}</td>
                        <td>{{ $total['sum_price'] ?? '0.00' }}</td>
                        <td></td>
                        <td>{{ $total['sum_discount_value'] ?? '0.00' }}</td>
                        <td>{{ $total['sum_net'] ?? '0.00' }}</td>
                        <td></td>
                        <td>{{ $total['sum_tax_value'] ?? '0.00' }}</td>
                        <td>{{ $total['sum_total'] ?? '0.00' }}</td>
                        <td colspan="2"></td>
                    @endif
            </tr>
        </tfoot>
    @endif
</table>
