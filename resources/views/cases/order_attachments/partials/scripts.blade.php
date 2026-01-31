<script type="text/javascript">
    let locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteAdd = "/cases/orders/attachments/store";
        let RouteDestroy = '/cases/orders/attachments/destroy/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('cases.orders.attachments.index', [ isset($order_id)? $order_id : $Order["id"] ]) }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'description', name: 'description'},
                {data: 'filesize', name: 'filesize',
                    render: function(data, type, row) {
                        if (type === 'display' && data !== null && data !== undefined) {
                            var fileSizeInMB = (data / (1024 * 1024)).toFixed(2); // Convert bytes to MB
                            return fileSizeInMB + ' MB';
                        }
                        return data; // Return data as is for other types or if null/undefined
                    }
                },
                {data: 'type', name: 'type'},
                @if(auth()->user()->can('view_attachments') or auth()->user()->can('download_attachments') or auth()->user()->can('remove_attachments'))
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });

        @include('layouts.server_side.function_add')
        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')
    });
</script>
