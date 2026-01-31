<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteDestroy = '/work_orders/work_orders_management/destroy/';
        let RouteDestroyAll = "/work_orders/work_orders_management/destroy_all";
        let RouteStatusUpdate = "/work_orders/work_orders_management/update_status/";
        let RouteUpdateStatusCheckAll = "/work_orders/work_orders_management/update_status_all";

        let columns = [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'work_order_number', name: 'work_order_number' },
            { data: 'work_order_date', name: 'work_order_date' },
            { data: 'client', name: 'client' },
            { data: 'doctor', name: 'doctor' },
            { data: 'patient_name', name: 'patient_name' },
            { data: 'file_number', name: 'file_number' },
            { data: 'service', name: 'service' },
            { data: 'color', name: 'color' },
            @if(auth()->user()->can('show prices_work_orders_management'))
                { data: 'service_count', name: 'service_count' },
                { data: 'service_total', name: 'service_total' },
            @endif
            { data: 'branch', name: 'branch' },
            { data: 'phase', name: 'phase' },
            { data: 'created_by', name: 'created_by' },
            @if(auth()->user()->can('update status_work_orders_management'))
                { data: 'status', name: 'status', orderable: false, searchable: false },
            @endif
            @canany(['update_work_orders_management', 'remove_work_orders_management', 'log phases details_work_orders_management', 'update phase_work_orders_management', 'print statement_work_orders_management', 'print_work_orders_management', 'view details_work_orders_management'])
                { data: 'action', name: 'action', orderable: false, searchable: false },
            @endif
        ];

        let Table = dataTable({
            url: "{{ route('work_orders.work_orders_management.data') }}",
            type: 'GET',
            data: function (e) {
                e.search = $('.f_search').val()
                e.status = $('.f_status').val()
                e.branch = $('.f_branch').val()
                e.doctor = $('.f_doctor').val()
                e.client = $('.f_client').val()
                e.service = $('.f_service').val()
                e.color = $('.f_color').val()
                e.phase = $('.f_phase').val()
                e.dateStart = $('.f_date_start').val()
                e.dateEnd = $('.f_date_end').val()
            }
        }, columns);

        const filters = [
            '.f_status',
            '.f_branch',
            '.f_doctor',
            '.f_client',
            '.f_service',
            '.f_color',
            '.f_phase',
            // '.f_date_start',
            '.f_date_end'
        ];

        $(filters.join(', ')).each(function () {
            $(this).on("change", function () {
                Table.draw();
            });
        });

        $('.f_search').on("input", () => Table.draw());

        $(document).on('click', '.print_button', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                url: `/work_orders/work_orders_management/print/${id}`,
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

        $(document).on('click', '.print_statement_button', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                url: `/work_orders/work_orders_management/print_statement/${id}`,
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

        $(document).on('click', '.view_details', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/work_orders/work_orders_management/get_log_phases/${id}`,
                type: 'GET',
                success: function(data) {
                    let content = '';

                    if(data.length > 0) {
                        content += `
                            <table class="table table-hover border table-row-bordered align-middle fs-7">
                                <thead>
                                <tr class="fw-bold text-uppercase border border-1 bg-dark bg-opacity-20">
                                    <th>#</th>
                                    <th>{{ __("Phase") }}</th>
                                    <th>{{ __("Date") }}</th>
                                    <th>{{ __("Time") }}</th>
                                    <th>{{ __("User") }}</th>
                                </tr>
                            </thead>
                            <tbody>`;
                            $.each(data, function(index, log) {
                                content += `<tr class="${(log["id"] % 2 === 1) ? "table-Light" : "bg-secondary"}">`;
                                content += `<td>${log["id"]}</td>`;
                                content += `<td>${log["phase"]}</td>`;
                                content += `<td>${log["date"]}</td>`;
                                content += `<td>${log["time"]}</td>`;
                                content += `<td>${log["user"]}</td>`;
                                content += `</tr>`;
                            });
                        content += `
                            </tbody>
                        </table>
                        `;
                    } else {
                        content = '<p>No log stages available for this work order.</p>';
                    }
                    $('#log-details-content').html(content);
                    $('#logDetailsModal').modal('show');
                },
                error: function(xhr) {
                    $('#log-details-content').html('An error occurred while fetching the log details.');
                    $('#logDetailsModal').modal('show');
                }
            });
        });

        $(document).on('click', '.update_stage', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let phase_id = checkbox.data('phase');
            $.ajax({
                url: `/work_orders/work_orders_management/update_phase/${id}`,
                type: "GET",
                data: {
                    'phase_id': phase_id
                },
                success: function(data) {
                    Table.draw();
                    if(data.success) { Swal.fire("{{ __('success') }}", data.success, 'success'); }
                    else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else { Swal.fire("{{ __('error') }}", '{{ __("An error occurred while updating the stage.") }}', 'error'); }
                }
            });
        });

        $(document).on('click', '.update_phase', function () {
            let id = $(this).data('id');
            $('#workOrderId').val(id);
            $('#modal_submit').modal('show');
        });

        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.function_modal_submit')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

    });
</script>
