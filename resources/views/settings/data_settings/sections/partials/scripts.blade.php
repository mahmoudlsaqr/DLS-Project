<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/sections/store";
        let RouteEdit = '/settings/data_settings/sections/edit/';
        let RouteUpdate = '/settings/data_settings/sections/update/';
        let RouteStatusUpdate = '/settings/data_settings/sections/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/sections/update_status_all/';
        let RouteDestroy = '/settings/data_settings/sections/destroy/';
        let RouteDestroyAll = '/settings/data_settings/sections/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.sections.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'name_ar', name: 'name_ar'},
                {data: 'name_en', name: 'name_en'},
                @if(auth()->user()->can('update status_sections'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_sections') or auth()->user()->can('remove_sections'))
                {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ]
        });
        Table.draw();

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
                $('#name_ar_edit').val(data["name_ar"]);
                $('#name_en_edit').val(data["name_en"]);
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
