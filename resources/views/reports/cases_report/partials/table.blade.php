@php
    use App\Models\User;
    $locale = app() ->getLocale();
@endphp
<table class="table table-hover border table-row-bordered align-middle fs-7">
    <thead>
    <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
        <th>#</th>
        <th>{{ __('Number') }}</th>
        <th>{{ __('Designer') }}</th>
        <th>{{ __('Physician') }}</th>
        <th>{{ __('Supervising') }}</th>
        <th>{{ __('Patient name') }}</th>
        <th>{{ __('Note') }}</th>
        <th class="w-150px">{{ __('Status') }}</th>
        <th class="w-150px">{{ __('Created at') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(count($Cases) > 0)
            @foreach($Cases as $item)
                <tr>
                    <td>{{ $item["id"] }}</td>
                    <td>{{ $item["case_number"] }}</td>
                    <td>{{ User::find($item["created_by"])->name ?? "" }}</td>
                    <td>{{ User::find($item["physician"])->name ?? "" }}</td>
                    <td>{{ User::find($item["supervising"])->name ?? "" }}</td>
                    <td>{{ $item["patient_name"] }}</td>
                    <td>{{ $item["note"] }}</td>
                    <td>
                        <span class="badge badge-{{ [
                            1 => 'light-info',
                            2 => 'light-success',
                            3 => 'light-primary',
                            4 => 'light-danger',
                        ] [$item["status"]] ?? 'light-danger' }}">
                            {{ [
                                1 => __("Waiting for supervising"),
                                2 => __("Waiting for physician"),
                                3 => __("Approved"),
                                4 => __("Rejected from supervision"),
                        ] [$item["status"]] ?? __("Rejected from physician") }}
                        </span>
                    </td>
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
                <td colspan="9" class="p-6">{{ __("There is no data available in the table") }}</td>
            </tr>
        @endif
    </tbody>
</table>
