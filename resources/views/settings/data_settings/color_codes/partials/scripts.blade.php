<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/color_codes/store";
        let RouteEdit = '/settings/data_settings/color_codes/edit/';
        let RouteUpdate = '/settings/data_settings/color_codes/update/';
        let RouteStatusUpdate = '/settings/data_settings/color_codes/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/color_codes/update_status_all/';
        let RouteDestroy = '/settings/data_settings/color_codes/destroy/';
        let RouteDestroyAll = '/settings/data_settings/color_codes/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.color_codes.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'color_code', name: 'color_code'},
                @if(auth()->user()->can('update status_color_codes'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_color_codes') or auth()->user()->can('remove_color_codes'))
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

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');

            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#color_code_edit').val(data["color_code"]);
                $('#modal_edit').modal('show');
            });

        });

        @include('layouts.server_side.function_add')
        @include('layouts.server_side.function_edit')
        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

    });
</script>
