<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/doctors/store";
        let RouteEdit = '/settings/data_settings/doctors/edit/';
        let RouteUpdate = '/settings/data_settings/doctors/update/';
        let RouteStatusUpdate = '/settings/data_settings/doctors/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/doctors/update_status_all/';
        let RouteDestroy = '/settings/data_settings/doctors/destroy/';
        let RouteDestroyAll = '/settings/data_settings/doctors/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.doctors.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.client = $('.f_client').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'client', name: 'client'},
                {data: 'nationality', name: 'nationality'},
                {data: 'email', name: 'email'},
                {data: 'mobile', name: 'mobile'},
                {data: 'created_by', name: 'created_by'},
                {data: 'updated_by', name: 'updated_by'},
                @if(auth()->user()->can('update status_doctors'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_doctors') or auth()->user()->can('remove_doctors') or auth()->user()->can('view details_doctors'))
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
        $('.f_client').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');
            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#name_ar_edit').val(data["name_ar"]);
                $('#name_en_edit').val(data["name_en"]);
                $('#email_edit').val(data["email"]);
                $('#mobile_edit').val(data["mobile"]);
                $('#note_edit').val(data["note"]);
                SelectGetData($('#client_id_edit'), "{{ route( 'get_clients' ) }}", false, data["client_id"]);
                SelectGetData($('#nationality_id_edit'), "{{ route( 'get_nationalities' ) }}", false, data["nationality_id"]);
                SelectGetData($('#branch_id_edit'), "{{ route( 'get_branches_user' ) }}", false, data["branch_id"]);
                $('#modal_edit').modal('show');
            });
        });

        @include('layouts.server_side.function_add', [ 'redirectRoute' => '/settings/data_settings/doctors/details/'])
        @include('layouts.server_side.function_edit')
        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

        SelectGetData($('.branch_id'), "{{ route( 'get_branches_user' ) }}", false);
        SelectGetData($('.client_id'), "{{ route( 'get_clients' ) }}", false);
        SelectGetData($('.f_client'), "{{ route( 'get_clients_from_doctors' ) }}", false);
        SelectGetData($('.nationality_id'), "{{ route( 'get_nationalities' ) }}", false);
        SelectGetData($('.f_nationality'), "{{ route( 'get_nationalities_from_doctors' ) }}", false);

    });
</script>
