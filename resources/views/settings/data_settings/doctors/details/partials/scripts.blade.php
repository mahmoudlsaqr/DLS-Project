<script type="text/javascript">
    const locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteUpdateDetails = "{{ route("settings.data_settings.doctors.details.update", [$id]) }}";
        let RouteStatusUpdate = '/settings/data_settings/doctors/details/update_status/';
        let RouteUpdateStatusCheckAll = '/settings/data_settings/doctors/details/update_status/update_status_all/';
        let RouteDestroy = '/settings/data_settings/doctors/details/destroy/';
        let RouteDestroyAll = '/settings/data_settings/doctors/details/destroy/destroy_all/';

        let Table = $('#data-table').DataTable({
            @include("layouts.server_side.table_properties")
            ajax: {
                url: "{{ route('settings.data_settings.doctors.details.index', [ $id ]) }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.service = $('.f_service').val()
                    e.category = $('.f_category').val()
                }
            },
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'service_id', name: 'service_id'},
                {data: 'service', name: 'service'},
                {data: 'service_code', name: 'service_code'},
                {data: 'service_price', name: 'service_price'},
                {data: 'service_discount', name: 'service_discount'},
                {data: 'price', name: 'price'},
                {data: 'category', name: 'category'},
                @if(auth()->user()->can('update status details_doctors'))
                {data: 'status', name: 'status', orderable: false, searchable: false},
                @endif
                @if(auth()->user()->can('remove details_doctors'))
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
        $('.f_service').on('change', function () {
            Table.draw();
        });
        $('.f_category').on('change', function () {
            Table.draw();
        });

        @include('layouts.server_side.function_edit_details')
        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.server_side.function_status')
        @include('layouts.server_side.update_status_check_all')
        @include('layouts.partials.filter_columns_script')

        let discount = $(".service_discount");
        let discountValue = $(".service_discount_value");
        let service = $('.service');
        let price = $(".service_price")

        $('.div_service_category').hide(0)
        $('.div_service').hide(0)
        $('.div_price').hide(0)
        $('.div_discount_value').hide(0)
        $('.service_search').on('change', function () {
            let value = $(this).val();
            if (+value === 1) {
                $('.div_service_category').hide(100)
                $('.div_price').hide(100)
                $('.div_service').hide(100)
                $('.div_discount_value').hide(100)
            } else if (+value === 2) {
                $('.div_service_category').show(100)
                $('.div_price').hide(100)
                $('.div_service').hide(100)
                $('.div_discount_value').hide(100)
            } else {
                $('.div_service_category').hide(100)
                $('.div_price').show(100)
                $('.div_service').show(100)
                $('.div_discount_value').show(100)
            }

            price.val(null)
            discount.val(null)
            discountValue.val(null)
            service.val(null).trigger("change")
        }); // End Get "Categories Services"

        service.on("change", () => {
            let serviceId = $('.service').val();
            let doctorId = "{{ $id }}";
            if (serviceId) {
                $.ajax({
                    url: `/get_doctor_service_details/${serviceId}`,
                    type: "GET",
                    data: {
                        serviceId : serviceId,
                        doctorId : doctorId,
                    },
                    success: function(data) {
                        price.val(data["service_price"])
                        if (Number(data["service_discount"]) === 0) {
                            discount.val(null)
                        } else {
                            discount.val(data["service_discount"])
                        }
                        discountValue.val((+discount.val() * +price.val() / 100).toFixed(2).toString())

                        discount.on("keyup", () => {
                            let disVal = +discount.val() * +price.val() / 100;
                            discountValue.val(disVal.toFixed(2).toString())
                        }) // End - keyup services discount percent.

                        discountValue.on("keyup", () => {
                            let dis = +discountValue.val() / +price.val() * 100;
                            discount.val(dis.toFixed(2).toString())
                            if (+discountValue.val() > +price.val()) {
                                alert(`Count should be less than ${+price.val()}`);
                                discount.val(data["service_discount"])
                                discountValue.val((+discount.val() * +price.val() / 100).toFixed(2).toString())
                            }
                        }) // End - keyup services discount value.

                        price.on("change", () => {
                            discountValue.val((+discount.val() * +price.val() / 100).toFixed(2).toString())
                        }) // End - change services price value.

                    },
                    error: function(error) {
                        console.log(error["responseJSON"])
                        alert(`Error: ${error}`);
                    }
                });
            }
        }) // End - Get Service When Change Service.

        let servicesSyncButton = $("#services_sync_button");
        let servicesResetButton = $("#services_reset_button");

        function actionBouttons(button, url, SwalTitle) {
            button.click(function(e) {
                $(this).attr('data-kt-indicator', 'on');
                button.prop('disabled', true);
                e.preventDefault();
                Swal.fire({
                    title: SwalTitle,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, confirm that.')}}",
                    cancelButtonText: "{{ __("No!") }}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function(data) {
                                if (data.success || data.error) {
                                    if (data.success) {
                                        Table.draw();
                                        button.removeAttr('data-kt-indicator').prop('disabled', false);
                                        Swal.fire("{{ __('success') }}", data.success, 'success');
                                    } else { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                                } else { console.log(data) }
                            },
                            error: function(xhr) {
                                button.prop('disabled', false).removeAttr('data-kt-indicator');
                                if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                                else { Swal.fire("{{ __('error') }}", '{{ __("Something went wrong, please try again later") }}', 'error'); }
                            }
                        });
                    } else {
                        button.prop('disabled', false).removeAttr('data-kt-indicator');
                    }
                });

            });
        }
        actionBouttons(servicesSyncButton, `{{ route("settings.data_settings.doctors.details.sync_services", [$id]) }}`, "{{ __("Are you sure the services are synchronized?") }}")
        actionBouttons(servicesResetButton, `{{ route("settings.data_settings.doctors.details.reset_services", [$id]) }}`, "{{ __("Are you sure you want to remove the doctor's discounts?") }}")

        SelectGetData($('.f_category, .service_category'), "{{ route( 'get_services_categories_from_doctors_details' ) }}", false);
        SelectGetData($('.f_service, .service'), "{{ route( 'get_services_from_doctors_details' ) }}", false);

    });
</script>
