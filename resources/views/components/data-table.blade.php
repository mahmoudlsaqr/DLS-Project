<div class="table-responsive mt-6">
    <table id="{{ $id }}" class="table table-1 align-middle border table-hover gs-4 {{ $class }}">
        <thead class="bg-light h-50px align-middle border fs-6 text-uppercase {{ $classHeader }}">
            <tr>
                @if($checkbox)
                    <th class="w-50px">
                        <x-checkbox-table class="check-all" />
                    </th>
                @endif
                @foreach($columns as $index => $column)
                    <th class="{{ $column['width'] ?? "" }}">{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="border fs-7">
            {{ $slot }}
        </tbody>

        @if ($footer)
        <tfoot class="bg-light h-50px align-middle border fs-6 text-uppercase">
            {{ $footer }}
        </tfoot>
        @endif
    </table>
</div>
