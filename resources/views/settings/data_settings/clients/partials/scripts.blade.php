<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteDestroy = '/settings/data_settings/clients/destroy/';
        let RouteDestroyAll = "/settings/data_settings/clients/destroy_all";
        let RouteStatusUpdate = "/settings/data_settings/clients/update-status/";
        let RouteUpdateStatusCheckAll = "/settings/data_settings/clients/update_status_all";

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.clients.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'city', name: 'city'},
                {data: 'district', name: 'district'},
                {data: 'branch', name: 'branch'},
                {data: 'mobile', name: 'mobile'},
                {data: 'created_by', name: 'created_by'},
                {data: 'updated_by', name: 'updated_by'},
                @if(auth()->user()->can('update status_clients'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_clients') or auth()->user()->can('remove_clients'))
                {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_status').on('change', function () {
            Table.draw();
        });

        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

    });
</script>
