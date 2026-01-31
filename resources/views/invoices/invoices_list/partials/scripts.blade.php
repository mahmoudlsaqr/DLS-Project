<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('invoices.invoices_list.data') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.branch = $('.f_branch').val()
                    e.doctor = $('.f_doctor').val()
                    e.client = $('.f_client').val()
                    e.dateStart = $('.f_date_start').val()
                    e.dateEnd = $('.f_date_end').val()
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'invoice_date', name: 'invoice_date'},
                {data: 'branch', name: 'branch'},
                {data: 'client', name: 'client'},
                {data: 'doctor', name: 'doctor'},
                {data: 'total_price', name: 'total_price'},
                {data: 'total_discount_value', name: 'total_discount_value'},
                {data: 'total_total_net', name: 'total_total_net'},
                {data: 'total_tax_value', name: 'total_tax_value'},
                {data: 'total_total', name: 'total_total'},
                {data: 'total_paid', name: 'total_paid'},
                {data: 'total_residual', name: 'total_residual'},
                @if(auth()->user()->can('view details_invoices') or auth()->user()->can('print_invoices') or auth()->user()->can('pay_invoices'))
                {data: 'action', name: 'action', orderable: false, searchable: false},
                @endif
            ],
            drawCallback: function () {
                let api = this.api();
                // Helper function to get the column total
                let intVal = function (i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // Calculate totals for each column
                let total_price = api.column(5, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_discount = api.column(6, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_net = api.column(7, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_tax = api.column(8, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_total = api.column(9, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_paid = api.column(10, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let total_residual = api.column(11, {page: 'current'}).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                // Update the footer with the calculated totals
                $(api.column(5).footer()).html(total_price.toFixed(2));
                $(api.column(6).footer()).html(total_discount.toFixed(2));
                $(api.column(7).footer()).html(total_net.toFixed(2));
                $(api.column(8).footer()).html(total_tax.toFixed(2));
                $(api.column(9).footer()).html(total_total.toFixed(2));
                $(api.column(10).footer()).html(total_paid.toFixed(2));
                $(api.column(11).footer()).html(total_residual.toFixed(2));
            }
        });

        $('.f_search').on('keyup', function () {
            Table.draw();
        });
        $('.f_branch').on('change', function () {
            Table.draw();
        });
        $('.f_doctor').on('change', function () {
            Table.draw();
        });
        $('.f_client').on('change', function () {
            Table.draw();
        });
        $('.f_date_start').on('change', function () {
            Table.draw();
        });
        $('.f_date_end').on('change', function () {
            Table.draw();
        });

        $(document).on('click', '.pay_button', function () {
            let id = $(this).data('id');
            $('#invId').val(id);
            $('#payInvoiceModal').modal('show');
        });

        $(document).on('click', '.payment_button', function(e) {
            e.preventDefault();
            let button = $(this);
            button.attr('data-kt-indicator', 'on').prop('disabled', true);
            let postData = new FormData($("#pay_invoice_form")[0]);
            $.ajax({
                url: "{{ route("invoices.invoices_list.pay") }}",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Table.draw();
                    if (response.success) {
                        $('#pay_invoice_form').trigger('reset');
                        $('#payInvoiceModal').modal('hide');
                        Swal.fire("{{ __('success') }}", response.success, 'success');
                    } else if(response.error) {
                        Swal.fire("{{ __('error') }}", response.error, 'error');
                    }
                    button.removeAttr('data-kt-indicator').prop('disabled', false);
                }, error: function(xhr) {
                    Table.draw();
                    button.removeAttr('data-kt-indicator').prop('disabled', false);
                    if (xhr["responseJSON"].error) { Swal.fire("{{ __('error') }}", xhr["responseJSON"].error, 'error'); }
                    else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                }
            });
        });

{{--        @include('layouts.server_side.function_delete')--}}
        @include('layouts.server_side.delete_check_all')
        @include('layouts.partials.filter_columns_script')
    });
</script>
