<script type="text/javascript">
    $(function () {
        let branch = $('.branch_id');
        let doctor = $('.doctor_id');
        let client = $('#client');
        let service = $('.service_id');
        let workOrderDate = $('.work_order_date');
        let inputCount = $('.input_count');
        let inputsNumber = $('.input_number');

        function handleWorkOrderDateChange() {
            let id = $(this).val();
            if (id) {
                const resetFields = () => {
                    branch.val(null).trigger("change");
                    doctor.val(null).trigger("change");
                    client.val(null).trigger("change");
                    service.val(null).trigger("change");
                    inputCount.val(null);
                    inputsNumber.val(null);
                };
                resetFields();
            }
        }

        function handleBranchChange() {
            let id = $(this).val();
            if (id) {
                doctor.val(null).trigger("change");
                client.val(null).trigger("change");
                SelectGetData(doctor, `/get_branch_doctors/${id}`, false);
            }
        }

        function handleDoctorChange() {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: `/get_client_doctor/${id}`,
                    type: "GET",
                    success: function(data) {
                        client.val(null).trigger("change");
                        client.val(data['client']);
                    },
                    error: function(xhr) {
                        let errorMessage = xhr["responseJSON"]?.error || '{{ __("Error :)") }}';
                        Swal.fire("{{ __('error') }}", errorMessage, 'error');
                    }
                });
                service.val(null).trigger("change");
                inputCount.val(null);
                inputsNumber.val(null);
                SelectGetData(service, `/get_doctor_services/${id}`, false);
            }
        }

        @if(isset($madeInvoiceForIt))
            @if($madeInvoiceForIt === 0)
                workOrderDate.on('change', handleWorkOrderDateChange);
                branch.on('change', handleBranchChange);
                doctor.on('change', handleDoctorChange);
            @endif
        @else
            workOrderDate.on('change', handleWorkOrderDateChange);
            branch.on('change', handleBranchChange);
            doctor.on('change', handleDoctorChange);
        @endif

    });
</script>

@if(auth()->user()->can('service details_work_orders_management'))
    @include("work_orders.work_orders_management.partials.service_details_script")
@endif
