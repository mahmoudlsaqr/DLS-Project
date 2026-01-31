<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(document).ready(function () {

        let search = $('.f_search'),
            phase = $('.f_phase'),
            user = $('.f_user');

        let columns = [
            {data: 'work_order_id', name: 'work_order_id'},
            {data: 'work_order_number', name: 'work_order_number'},
            {data: 'work_order_date', name: 'work_order_date'},
            {data: 'phase_name', name: 'phase_name'},
            {data: 'client', name: 'client'},
            {data: 'doctor', name: 'doctor'},
            {data: 'patient_name', name: 'patient_name'},
            {data: 'file_number', name: 'file_number'},
            {data: 'branch', name: 'branch'},
            {data: 'last_update', name: 'last_update'},
            @if(auth()->user()->can('update phase_work_orders_management'))
            {data: 'actions', name: 'actions'},
            @endif
        ];

        let Table = dataTable({
                url: "{{ route('work_orders.late_work_orders.data') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.phase = $('.f_phase').val()
                    e.user = $('.f_user').val()
                }
            }, columns);

        search.on("input", () => Table.draw());
        phase.on("change", () => Table.draw());
        user.on("change", () => Table.draw());


        let phases = $('.phases');

        @if (Auth::user()->hasRole('technician'))
        SelectGetData(phases, "/get_technician_phases/{{ Auth::id() }}", false);
        @else
        SelectGetData(phases, "{{ route( 'get_phases_without_entry') }}", false);
        @endif
        SelectGetData(phase, "{{ route( 'get_phases' ) }}", false);
        SelectGetData(user, "{{ route( 'get_users' ) }}", false);


        $(document).on('click', '.update_phase', function () {
            let id = $(this).data('id');
            $('#workOrderId').val(id);
            $('#modal_submit').modal('show');
        });

        @include('layouts.server_side.function_modal_submit')
        @include('layouts.partials.filter_columns_script')

    });
</script>
