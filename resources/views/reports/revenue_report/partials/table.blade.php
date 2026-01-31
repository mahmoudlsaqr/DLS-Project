@php
    use App\Models\User;
    $locale = app() ->getLocale();
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>{{ __("Payment number") }}</th>
        <th>{{ __("Invoice number") }}</th>
        <th>{{ __("Amount") }}</th>
        <th>{{ __("Type") }}</th>
        <th>{{ __("Branch") }}</th>
        <th>{{ __("Doctor") }}</th>
        <th class="w-150px">{{ __('Updated at') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(!empty($workOrders) and count($workOrders) > 0)
            @foreach($workOrders as $item)
                <tr class="{{ $item["id"] % 2 == 1 ? "table-Light" : "bg-secondary"}}">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->invoicesMain->id }}</td>
                    <td>{{ $item->amount ?? "0.00" }}</td>
                    <td>
                        <span class="badge badge-light-dark">
                            {{ [ 1 => __("Cash"), 2 => __("Transfer"), 3 => __("Check"), ] [$item["payment_type"]] ?? __("Unknown") }}
                        </span>
                    </td>
                    @php
                        $branch = \App\Models\Branch::find($item->invoicesMain->branch_id);
                        $branchName = getLocaleName($branch, "name_ar", "name_en");
                        $doctor = \App\Models\Doctors::find($item->invoicesMain->doctor_id);
                        $doctorName = getLocaleName($doctor, "name_ar", "name_en");
                    @endphp
                    <td>{{ $branchName ?? __("Unknown") }}</td>
                    <td>{{ $doctorName ?? __("Unknown") }}</td>
                    @if ($item["user_id"] != null)
                        <td>
                            <div class="fw-bold text-gray-800">
                                @php
                                    $date = $item["updated_at"] ->format('d-m-Y');
                                    $time = $item["updated_at"] ->format('h:i');
                                    $NewDateTime = date('A', strtotime($item["updated_at"]));
                                    $DateTimeType = (($NewDateTime == "AM") ? __('AM') : __('PM'));
                                @endphp
                                <span>{{ $date }}</span><br>
                                <span>{{ $time }}</span>
                                <span>{{ $DateTimeType }}</span>
                            </div>
                        </td>
                    @else
                        <td>{{ __("Unknown") }}</td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="16" class="p-6">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
</table>

<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-10 fs-7">
        <th>{{ __("Total amount") }}</th>
        <th>{{ __("Total cash") }}</th>
        <th>{{ __("Total transfer") }}</th>
        <th>{{ __("Total check") }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $sumAmount ?? "0.00" }}</td>
        <td>{{ $sumCash ?? "0.00" }}</td>
        <td>{{ $sumTransfer ?? "0.00" }}</td>
        <td>{{ $sumCheck ?? "0.00" }}</td>
    </tr>
    </tbody>
</table>
