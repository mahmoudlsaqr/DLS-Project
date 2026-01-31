<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/working_phases/store";
        let RouteEdit = '/settings/data_settings/working_phases/edit/';
        let RouteUpdate = '/settings/data_settings/working_phases/update/';
        let RouteStatusUpdate = '/settings/data_settings/working_phases/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/working_phases/update_status_all/';
        let RouteDestroy = '/settings/data_settings/working_phases/destroy/';
        let RouteDestroyAll = '/settings/data_settings/working_phases/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.working_phases.index') }}",
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
                {data: 'completion_percent', name: 'completion_percent'},
                {data: 'number_of_times_of_delay', name: 'number_of_times_of_delay'},
                @if(auth()->user()->can('update status_working_phases'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_working_phases') or auth()->user()->can('remove_working_phases'))
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
                $('#name_ar_edit').val(data["name_ar"]);
                $('#name_en_edit').val(data["name_en"]);
                $('#completion_percent_edit').val(data["completion_percent"]);
                $('#number_of_times_of_delay_edit').val(data["number_of_times_of_delay"]);
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
