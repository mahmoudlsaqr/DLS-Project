<script type="text/javascript">
    let locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteStatusUpdate = "/settings/regional_settings/regions/update-status/";
        let RouteUpdateStatusCheckAll = "/settings/regional_settings/regions/update_status_all";

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.regional_settings.regions.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'code', name: 'code'},
                {data: 'region', name: 'region'},
                @if(auth()->user()->can('update status_regions'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_status').change(function () {
            Table.draw();
        });

        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')
    });
</script>
