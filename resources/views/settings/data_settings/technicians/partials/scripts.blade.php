<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/technicians/store";
        let RouteEdit = '/settings/data_settings/technicians/edit/';
        let RouteUpdate = '/settings/data_settings/technicians/update/';
        let RouteStatusUpdate = '/settings/data_settings/technicians/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/technicians/update_status_all/';
        let RouteDestroy = '/settings/data_settings/technicians/destroy/';
        let RouteDestroyAll = '/settings/data_settings/technicians/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.technicians.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.section = $('.f_section').val()
                    e.nationality = $('.f_nationality').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'section', name: 'section'},
                {data: 'nationality', name: 'nationality'},
                {data: 'email', name: 'email'},
                {data: 'mobile', name: 'mobile'},
                {data: 'monthly_target', name: 'monthly_target'},
                {data: 'created_by', name: 'created_by'},
                {data: 'updated_by', name: 'updated_by'},
                @if(auth()->user()->can('update status_technicians'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_technicians') or auth()->user()->can('remove_technicians'))
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
        $('.f_section').on('change', function () {
            Table.draw();
        });
        $('.f_nationality').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');
            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#userId').val(data["user_id"]);
                $('#name_ar_edit').val(data["name_ar"]);
                $('#name_en_edit').val(data["name_en"]);
                $('#email_edit').val(data["email"]);
                $('#country_code_input_edit').val(data["country_code"]).trigger("change");
                $('#mobile_edit').val(data["mobile"]);
                $('#monthly_target_edit').val(data["monthly_target"]);
                $('#note_edit').val(data["note"]);
                SelectGetData($('#section_id_edit'), "{{ route( 'get_sections' ) }}", false, data["section_id"]);
                SelectGetData($('#nationality_id_edit'), "{{ route( 'get_nationalities' ) }}", false, data["nationality_id"]);
                SelectGetData($('#branches_edit'), "{{ route( 'get_branches' ) }}", true, data["branches"]);
                SelectGetData($('#phases_edit'), "{{ route( 'get_phases' ) }}", true, data["phases"]);
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

        SelectGetData($('.branches'), "{{ route( 'get_branches' ) }}", true);
        SelectGetData($('.phases'), "{{ route( 'get_phases' ) }}", true);
        SelectGetData($('.section_id'), "{{ route( 'get_sections' ) }}", false);
        SelectGetData($('.f_section'), "{{ route( 'get_sections_from_technicians' ) }}", false);
        SelectGetData($('.nationality_id'), "{{ route( 'get_nationalities' ) }}", false);
        SelectGetData($('.f_nationality'), "{{ route( 'get_nationalities_from_technicians' ) }}", false);

    });
</script>
