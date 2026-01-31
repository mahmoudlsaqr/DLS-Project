<table id="data-table" class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
            <th>#</th>
            <th>{{ __("Number") }}</th>
            <th class="w-100px">{{ __("Date") }}</th>
            <th>{{ __("Phase") }}</th>
            <th>{{ __("Client") }}</th>
            <th>{{ __("Doctor") }}</th>
            <th>{{ __("Patient name") }}</th>
            <th>{{ __("File number") }}</th>
            <th class="w-200px">{{ __("Last update") }}</th>
            <th class="w-200px">{{ __("Branch") }}</th>
            @if(auth()->user()->can('update phase_work_orders_management'))
            <th class="w-100px">{{ __("Actions") }}</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $index => $row)
        <tr id="{{ $row["work_order_id"] }}">
            <td>{{ $row["work_order_id"] }}</td>
            <td>{{ $row["work_order_number"] }}</td>
            <td>{{ $row["work_order_date"] }}</td>
            <td>{{ $row["phase_name"] }}</td>
            <td>{{ $row["client"] }}</td>
            <td>{{ $row["doctor"] }}</td>
            <td>{{ $row["patient_name"] }}</td>
            <td>{{ $row["file_number"] }}</td>
            <td>{{ $row["branch"] }}</td>
            <td>{!! $row["last_update"] !!}</td>
            @if(auth()->user()->can('update_work_orders_management'))
                <td>{!! $row["actions"] !!}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
