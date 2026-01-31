<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteStatusUpdate = "/settings/regional_settings/districts/update-status/";
        let RouteUpdateStatusCheckAll = "/settings/regional_settings/districts/update_status_all";

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.regional_settings.districts.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.regions = $('.f_regions').val()
                    e.cities = $('.f_cities').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'district', name: 'district'},
                {data: 'region', name: 'region'},
                {data: 'city', name: 'city'},
                @if(auth()->user()->can('update status_districts'))
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
        $('.f_cities').change(function () {
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
