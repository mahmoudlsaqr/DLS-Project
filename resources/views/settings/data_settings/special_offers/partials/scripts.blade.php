<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/settings/data_settings/special_offers/store";
        let RouteEdit = '/settings/data_settings/special_offers/edit/';
        let RouteUpdate = '/settings/data_settings/special_offers/update/';
        let RouteDestroy = '/settings/data_settings/special_offers/destroy/';
        let RouteDestroyAll = '/settings/data_settings/special_offers/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.special_offers.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'offer_name', name: 'offer_name'},
                {data: 'doctor', name: 'doctor'},
                {data: 'discount', name: 'discount'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                @if(auth()->user()->can('update_special_offers') or auth()->user()->can('remove_special_offers'))
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
                $('#offer_name_edit').val(data["offer_name"]);
                $('#discount_edit').val(data["discount"]);
                SelectGetData($('#doctor_id_edit'), "{{ route( 'get_doctors' ) }}", false, data["doctor_id"]);
                $('.start_date_edit').val(data["start_date"]);
                $('.end_date_edit').val(data["end_date"]);
                $('#modal_edit').modal('show');
            });

        });

        @include('layouts.server_side.function_add')
        @include('layouts.server_side.function_edit')
        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.partials.filter_columns_script')


        SelectGetData($('.doctor_id'), "{{ route( 'get_doctors' ) }}", false);

    });
</script>
