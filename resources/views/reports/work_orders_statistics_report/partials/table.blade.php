@php
    use App\Models\User;
    $locale = app() ->getLocale();
    $i = 1;
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __("Date") }}</th>
        <th>{{ __("Number") }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($workOrders) and count($workOrders) > 0)
            @foreach($workOrders as $item)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $item["work_order_date"] }}</td>
                    <td>{{ $item["total_work_orders"] }}</td>
                </tr>
                @php
                    $i += 1;
                @endphp
            @endforeach
        @else
            <tr>
                <td colspan="1" class="p-6">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
</table>
