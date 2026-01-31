@php
    $index = 1;
    $totalWorkOrders = 0;
    $totalTeeth = 0;
    $totalReturnedWorkOrders = 0;
    $totalReturnedTeeth = 0;
    $totalNetWorkOrders = 0;
    $totalNetTeeth = 0;
    $totalSumDone = 0;
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __("User") }}</th>
        <th class="text-center" colspan="2">{{ __("Design phase") }}</th>
        <th class="text-center" colspan="2">{{ __("Returned") }}</th>
        <th class="text-center" colspan="2">{{ __("Net") }}</th>
        <th class="text-center">{{ __("Monthly target") }}</th>
        <th class="text-center">{{ __("Achievement %") }}</th>
    </tr>
    <tr class="fw-bold text-uppercase border bg-dark bg-opacity-15">
        <th></th>
        <th></th>
        <th>{{ __("No. work orders") }}</th>
        <th>{{ __("No. teeth") }}</th>
        <th>{{ __("No. work orders") }}</th>
        <th>{{ __("No. teeth") }}</th>
        <th>{{ __("No. work orders") }}</th>
        <th>{{ __("No. teeth") }}</th>
        <th>{{ __("Target") }}</th>
        <th>{{ __("Achievement %") }}</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($data) and count($data) > 0)
        @foreach($data as $item)
            @php
                $achievementRate = $item["monthly_target"] > 0
                    ? round(($item["sum_done"] / $item["monthly_target"]) * 100, 2)
                    : 0;

                $totalWorkOrders += $item["work_orders_count"];
                $totalTeeth += $item["sum"];
                $totalReturnedWorkOrders += $item["returned_work_orders_count"];
                $totalReturnedTeeth += $item["returned_sum"];
                $totalNetWorkOrders += $item["work_orders_count_done"];
                $totalNetTeeth += $item["sum_done"];
                $totalSumDone += $item["sum_done"];
            @endphp
            <tr>
                <td>{{ $index }}</td>
                <td>{{ $item["user_name"] }}</td>
                <td>{{ $item["work_orders_count"] }}</td>
                <td>{{ $item["sum"] }}</td>
                <td>{{ $item["returned_work_orders_count"] }}</td>
                <td>{{ $item["returned_sum"] }}</td>
                <td>{{ $item["work_orders_count_done"] }}</td>
                <td>{{ $item["sum_done"] }}</td>
                <td>{{ $item["monthly_target"] }}</td>
                <td>{{ $achievementRate }}%</td>
            </tr>
            @php $index += 1; @endphp
        @endforeach
    @else
        <tr>
            <td colspan="10">{{ __("There is no data available in the table") }}</td>
        </tr>
    @endif
    </tbody>
    <tfoot>
    <tr class="fw-bold text-uppercase border bg-dark bg-opacity-20">
        <td colspan="2" class="text-center">{{ __("Totals") }}</td>
        <td>{{ $totalWorkOrders }}</td>
        <td>{{ $totalTeeth }}</td>
        <td>{{ $totalReturnedWorkOrders }}</td>
        <td>{{ $totalReturnedTeeth }}</td>
        <td>{{ $totalNetWorkOrders }}</td>
        <td>{{ $totalNetTeeth }}</td>
        <td colspan="2"></td>
    </tr>
    </tfoot>
</table>
