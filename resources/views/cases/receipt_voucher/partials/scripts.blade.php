<script type="text/javascript">
    let locale = '{{ app() ->getLocale() }}';

    let RouteEdit = '/cases/receipt_vouchers/edit/';
    let RouteUpdate = '/cases/receipt_vouchers/update/';

    $(function () {
        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('cases.receipt_vouchers.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.physician = $('.f_physician').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'case_number', name: 'case_number', orderable: false, searchable: false},
                {data: 'physician', name: 'physician'},
                {data: 'clinic_name', name: 'clinic_name'},
                {data: 'amount', name: 'amount'},
                {data: 'description', name: 'description'},
                {data: 'created_by_at', name: 'created_by_at', orderable: false, searchable: false},
                {data: 'updated_by_at', name: 'updated_by_at', orderable: false, searchable: false},
                @if (auth()->user()->can('update_receipt_vouchers') or auth()->user()->can('print_receipt_vouchers'))
                {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ]
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_physician').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.edit_btn', function () {
            let id = $(this).data('id');

            $.get(RouteEdit + id, function (data) {
                $('#EditId').val(data["id"]);
                $('#clinic_name_edit').val(data["clinic_name"]);
                $('#amount_edit').val(data["amount"]);
                $('#description_edit').val(data["description"]);
                $('#modal_edit').modal('show');
            });
        });

        $(document).on('click', '#print_receipt', function(e) {
            let Id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                url: "cases/cases_list/receipt_voucher/" + Id,
                type: "GET",
                success: function (response) {
                    $(response).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        removeInline: false,
                        printDelay: 500,
                    });
                    Table.draw();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    $('.check_all').prop('checked', false);
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                }
            });
        });

        @include('layouts.server_side.function_add')
        @include('layouts.server_side.function_edit')
        @include('layouts.partials.filter_columns_script')
    });
</script>

<script>

</script>
