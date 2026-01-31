<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteStatusUpdate = "/settings/regional_settings/cities/update-status/";
        let RouteUpdateStatusCheckAll = "/settings/regional_settings/cities/update_status_all";

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.regional_settings.cities.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.regions = $('.f_regions').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'city', name: 'city'},
                {data: 'region', name: 'region'},
                @if(auth()->user()->can('update status_cities'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_regions').change(function () {
            Table.draw();
        });
        $('.f_status').change(function () {
            Table.draw();
        });

        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

    });
</script>
