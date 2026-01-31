<script type="text/javascript">
    let locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteDestroy = '/cases/orders/destroy/';
        let RouteDestroyAll = "/cases/orders/destroy_all";
        // let RouteAcceptOrder = "/cases/orders/approve_status/";

        let Table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 50,
            order: [[ 1, "desc" ]], //or asc - desc
            language: { url: '/assets/plugins/custom/datatables/' + locale + '.json' },
            lengthMenu: [
                [10, 20, 30, 50, 100, 300, 500],
                [10, 20, 30, 50, 100, 300, 500]
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "{{ route('cases.orders.index') }}",
                type: 'GET',
                data: function (e) {
                    e.search = $('.f_search').val()
                    e.status = $('.f_status').val()
                    e.physician = $('.f_physician').val()
                }
            },
            columns: [
                {data: "checkbox", name: 'checkbox', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'physician', name: 'physician'},
                {data: 'designer', name: 'designer'},
                {data: 'patient_name', name: 'patient_name'},
                {data: 'required_in_case',name: 'required_in_case',
                        render: function(data) {
                            if (data !== null && data !== undefined) {
                                return data.length > 50 ? data.substring(0, 50) + '...' : data;
                            } else { return ''; }
                        }
                },
                {data: 'status', name: 'status'},
                {data: 'created_by_at', name: 'created_by_at', orderable: false, searchable: false},
                {data: 'updated_by_at', name: 'updated_by_at', orderable: false, searchable: false},
                @if(auth()->user()->can('accept_orders') or auth()->user()->can('approve_orders') or auth()->user()->can('reject_orders')
                or auth()->user()->can('update_orders') or auth()->user()->can('remove_orders'))
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
        $('.f_physician').on('change', function () {
            Table.draw();
        });

        {{--$(document).on('click', '.approve_button', function () {--}}
        {{--    let ID = $(this).data('id');--}}
        {{--    $.ajax({--}}
        {{--        url: RouteAcceptOrder + ID,--}}
        {{--        type: "GET",--}}
        {{--        success: function(data) {--}}
        {{--            Table.draw();--}}
        {{--            if(data.success) {--}}
        {{--                Swal.fire("{{ __('success') }}", data.success, 'success');--}}
        {{--                location.href = "/cases/orders/create_case/" + ID;--}}
        {{--            } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }--}}
        {{--        },--}}
        {{--        error: function(xhr) {--}}
        {{--            console.log(xhr.responseJSON)--}}
        {{--            checkbox.prop('checked', !status);--}}
        {{--            if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }--}}
        {{--            else { Swal.fire("{{ __('error') }}", '{{ __("An error occurred while updating the status.") }}', 'error'); }--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

        $(document).on('click', '.accept_button', function () {
            let id = $(this).data('id');
            $('#OrderIdAccept').val(id).trigger("change");
            $('#modal_accept').modal('show');
        });

        $("#button_form_accept").click(function(e) {
            $(this).attr('data-kt-indicator', 'on');
            $("#button_form_accept").prop('disabled', true);
            e.preventDefault();
            var postData = new FormData($("#modal_accept_form_add")[0]);
            $.ajax({
                url: "{{ route("cases.orders.accept_status") }}",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#modal_accept_form_add').trigger('reset');
                    $('#modal_accept_form_add .form-select').val(null).trigger('change');
                    $('#modal_accept').modal('hide');
                    $("#button_form_accept").removeAttr('data-kt-indicator').prop('disabled', false);
                    if(data.success) {
                        Table.draw();
                        Swal.fire("{{ __('success') }}", data.success, 'success');
                    } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    $("#button_form_accept").removeAttr('data-kt-indicator').prop('disabled', false);
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else if (xhr.responseJSON) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function (key, value) { errorMessage += '<span class="badge badge-light-danger mb-2">' + value + '</span><br>'; });
                        Swal.fire("{{ __('error') }}", errorMessage, 'error');
                    } else { Swal.fire("{{ __('error') }}", '{{ __("Something went wrong, please try again later") }}', 'error'); }
                }
            });
        });

        $(document).on('click', '.reject_button', function () {
            let id = $(this).data('id');
            $('#OrderIdReject').val(id).trigger("change");
            $('#modal_reject').modal('show');
        });

        $("#button_form_reject").click(function(e) {
            $(this).attr('data-kt-indicator', 'on');
            $("#button_form_reject").prop('disabled', true);
            e.preventDefault();
            var postData = new FormData($("#modal_reject_form_add")[0]);
            $.ajax({
                url: "{{ route("cases.orders.reject_status") }}",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#modal_reject_form_add').trigger('reset');
                    $('#modal_reject_form_add .form-select').val(null).trigger('change');
                    $('#modal_reject').modal('hide');
                    $("#button_form_reject").removeAttr('data-kt-indicator').prop('disabled', false);
                    if(data.success) {
                        Table.draw();
                        Swal.fire("{{ __('success') }}", data.success, 'success');
                    } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    $("#button_form_reject").removeAttr('data-kt-indicator').prop('disabled', false);
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else if (xhr.responseJSON) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function (key, value) { errorMessage += '<span class="badge badge-light-danger mb-2">' + value + '</span><br>'; });
                        Swal.fire("{{ __('error') }}", errorMessage, 'error');
                    } else { Swal.fire("{{ __('error') }}", '{{ __("Something went wrong, please try again later") }}', 'error'); }
                }
            });
        });

        @include('layouts.server_side.function_delete')
        @include('layouts.server_side.delete_check_all')
        @include('layouts.partials.filter_columns_script')
    });
</script>

