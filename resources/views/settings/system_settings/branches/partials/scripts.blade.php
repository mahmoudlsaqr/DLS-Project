<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteDestroy = '/settings/system_settings/branches/destroy/';
        let RouteDestroyAll = "/settings/system_settings/branches/destroy_all";
        let RouteStatusUpdate = "/settings/system_settings/branches/update-status/";
        let RouteUpdateStatusCheckAll = "/settings/system_settings/branches/update_status_all";

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.system_settings.branches.index') }}",
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
                {data: 'code', name: 'code'},
                {data: 'city', name: 'city'},
                {data: 'district', name: 'district'},
                {data: 'mobile', name: 'mobile'},
                {data: 'tin', name: 'tin'},
                {data: 'com_reg', name: 'com_reg'},
                @if(auth()->user()->can('update status_branches'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_branches') or auth()->user()->can('remove_branches'))
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
