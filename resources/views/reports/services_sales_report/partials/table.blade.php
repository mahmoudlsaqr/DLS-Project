@php
    use App\Models\User;
    $locale = app() ->getLocale();
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __("Service") }}</th>
        <th>{{ __("Quantity") }}</th>
        <th>{{ __("Cost") }}</th>
        <th>{{ __("Price") }}</th>
        <th>{{ __("Tax") }}</th>
        <th>{{ __("Total") }}</th>
        <th>{{ __("Net") }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($result) and count($result) > 0)
            @foreach($result as $index => $item)
                <tr class="{{ ($index+1) % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item["service"] }}</td>
                    <td>{{ $item["count"] }}</td>
                    <td>{{ $item["total_cost"] }}</td>
                    <td>{{ $item["total_price"] }}</td>
                    <td>{{ $item["tax"] }}</td>
                    <td>{{ $item["total_including_tax"] }}</td>
                    <td>{{ $item["total_net"] }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="p-6">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        @if(!empty($grand_total) and count($grand_total) > 0)
            <th colspan="2" class="fw-bold fs-5">{{ __("Totals") }}</th>
            <th>{{ $grand_total["total_count"] }}</th>
            <th>{{ $grand_total["total_cost"] }}</th>
            <th>{{ $grand_total["total_price"] }}</th>
            <th>{{ $grand_total["total_tax"] }}</th>
            <th>{{ $grand_total["total_including_tax"] }}</th>
            <th>{{ $grand_total["total_net"] }}</th>
        @else
            <th colspan="2" class="fw-bold fs-5">{{ __("Totals") }}</th>
            <th>0</th>
            <th>0.00</th>
            <th>0.00</th>
            <th>0.00</th>
            <th>0.00</th>
            <th>0.00</th>
        @endif
        </tr>
    </tfoot>
</table>
