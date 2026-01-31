<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/services/store";
        let RouteEdit = '/settings/data_settings/services/edit/';
        let RouteUpdate = '/settings/data_settings/services/update/';
        let RouteStatusUpdate = '/settings/data_settings/services/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/services/update_status_all/';
        let RouteDestroy = '/settings/data_settings/services/destroy/';
        let RouteDestroyAll = '/settings/data_settings/services/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.services.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.category = $('.f_category').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'service', name: 'service'},
                {data: 'category', name: 'category'},
                {data: 'service_code', name: 'service_code'},
                {data: 'service_cost', name: 'service_cost'},
                {data: 'service_price', name: 'service_price'},
                {data: 'tax_value', name: 'tax_value'},
                {data: 'service_price_with_tax', name: 'service_price_with_tax'},
                {data: 'note', name: 'note'},
                @if(auth()->user()->can('update status_services'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('update_services') or auth()->user()->can('remove_services'))
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
        $('.f_category').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');
            let category = $('#category_id_edit');
            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#name_ar_edit').val(data["name_ar"]);
                $('#name_en_edit').val(data["name_en"]);
                $('#service_price_edit').val(data["service_price"]);
                $('#service_cost_edit').val(data["service_cost"]);
                $('#tax_percent_edit').val(data["tax_percent"]);
                $('#note_edit').val(data["note"]);
                category.on('change', function () {
                    const id = category.val();
                    if (id) {
                        $.ajax({
                            url: `/create_service_code/${id}`,
                            type: "GET",
                            success: function(code) {
                                if (+id !== +data["category_id"]) {
                                    $('#service_code_edit').val(code);
                                } else {
                                    $('#service_code_edit').val(data["service_code"]);
                                }
                            },
                            error: function(error) {
                                console.log(error["responseJSON"])
                                alert(`Error: ${error}`);
                            }
                        });
                    }
                });

                SelectGetData(category, "{{ route( 'get_categories' ) }}", false, data["category_id"]);
                SelectGetData($('#branches_edit'), "{{ route( 'get_branches' ) }}", true, data["branches"]);
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
