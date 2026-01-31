<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/users/store";
        let RouteEdit = '/users/edit/';
        let RouteUpdate = '/users/update/';
        let RouteStatusUpdate = '/users/update_status/';
        let RouteUpdateStatusCheckAll = '/users/update_status_all/';
        let RouteDestroy = '/users/destroy/';
        let RouteDestroyAll = '/users/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('users.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.roles = $('.f_roles').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'user', name: 'user'},
                {data: 'mobile', name: 'mobile'},
                {data: 'email', name: 'email'},
                @if(auth()->user()->can('update status_users'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_users') or auth()->user()->can('remove_users'))
                {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_roles').on('change', function () {
            Table.draw();
        });
        $('.f_status').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');

            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#name_edit').val(data["name"]);
                $('#username_edit').val(data["username"]);
                $('#email_edit').val(data["email"]);
                $('#country_code_input_edit').val(data["country_code"]).trigger("change");
                $('#mobile_edit').val(data["mobile"]);
                $('#role_edit').val(data["role"]).trigger("change");
                console.log(data["branches"])
                SelectGetData($('#branches_edit'), "{{ route( 'get_branches' ) }}", true, data["branches"]);
                if(data["role"] == "physician") {
                    $('.supervising').show();
                    $('#supervising_edit').val(data["supervising"]).trigger("change");
                } else {
                    $('.supervising').hide();
                    $('#supervising_edit').val(null).trigger("change");
                }
                if(Number(data["receiving_orders"]) === 1) {
                    $('#edit_receiving_orders').prop("checked", true);
                } else {
                    $('#edit_receiving_orders').prop("checked", false);
                }
                if (data["role"] == "supervising") {
                    $('.skipping').show();
                    if(Number(data["skipping"]) === 1) {
                        $('#edit_skipping').prop("checked", true);
                    } else {
                        $('#edit_skipping').prop("checked", false);
                    }
                }
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

    });
</script>
