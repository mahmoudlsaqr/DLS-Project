<script type="text/javascript">
    let locale = '{{ app() ->getLocale() }}';
    $(function () {
        let RouteDestroy = '/cases/cases_list/destroy/';
        let RouteDestroyAll = "/cases/cases_list/destroy_all";
        let RouteAcceptStatus = "/cases/cases_list/accept_status/";

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
                url: "{{ route('cases.cases_list.index') }}",
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
                {data: 'case_number', name: 'case_number'},
                {data: 'physician', name: 'physician'},
                {data: 'supervising', name: 'supervising'},
                {data: 'patient_name', name: 'patient_name'},
                {data: 'note',name: 'note',
                        render: function(data) {
                            if (data !== null && data !== undefined) {
                                return data.length > 50 ? data.substring(0, 50) + '...' : data;
                            } else { return ''; }
                        }
                },
                {data: 'status', name: 'status'},
                {data: 'created_by_at', name: 'created_by_at', orderable: false, searchable: false},
                {data: 'updated_by_at', name: 'updated_by_at', orderable: false, searchable: false},
                @if(auth()->user()->can('attachments_cases') or auth()->user()->can('reject_cases')
                or auth()->user()->can('accept_cases') or auth()->user()->can('create_receipt_vouchers') or auth()->user()->can('print_receipt_vouchers')
                or auth()->user()->can('update_cases') or auth()->user()->can('remove_cases') )
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

        $(document).on('click', '.accept_button', function() {
            let Id = $(this).data('id');
            $.ajax({
                url: RouteAcceptStatus + Id,
                type: "GET",
                success: function(data) {
                    Table.draw();
                    if(data.success) { Swal.fire("{{ __('success') }}", data.success, 'success'); }
                    else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    checkbox.prop('checked', !status);
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else { Swal.fire("{{ __('error') }}", '{{ __("An error occurred while updating the status.") }}', 'error'); }
                }
            });
        });

        $(document).on('click', '#receipt_voucher', function () {
            let id = $(this).data('id');
            $('#case_id').val(id).trigger("change");
            $('#modal_voucher').modal('show');
        });

        $("#button_form_add").click(function(e) {
            $(this).attr('data-kt-indicator', 'on');
            $("#button_form_add").prop('disabled', true);
            e.preventDefault();
            var postData = new FormData($("#modal_voucher_form_add")[0]);
            $.ajax({
                url: "{{ route("cases.cases_list.store_receipt_voucher") }}",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#modal_voucher_form_add').trigger('reset');
                    $('#modal_voucher_form_add .form-select').val(null).trigger('change');
                    $('#modal_voucher').modal('hide');
                    $("#button_form_add").removeAttr('data-kt-indicator').prop('disabled', false);
                    if(data.success) {
                        $.ajax({
                            url: "cases/cases_list/receipt_voucher/" + data.case_id,
                            type: "GET",
                            success: function (response) {
                                $(response).printThis({
                                    debug: false,
                                    importCSS: true,
                                    importStyle: true,
                                    removeInline: false,
                                    printDelay: 500,
                                });
                            },
                            error: function(xhr) {
                                console.log(xhr.responseJSON)
                                $('.check_all').prop('checked', false);
                                if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                                else { Swal.fire("{{ __('error') }}", '{{ __("Error :)") }}', 'error'); }
                            }
                        });
                        Table.draw();
                        {{--Swal.fire("{{ __('success') }}", data.success, 'success');--}}
                    }
                    else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    $("#button_form_add").removeAttr('data-kt-indicator').prop('disabled', false);
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

        $(document).on('click', '.reject_button', function () {
            let id = $(this).data('id');
            $('#CaseId').val(id).trigger("change");
            $('#modal_reject').modal('show');
        });

        $("#button_form_reject").click(function(e) {
            $(this).attr('data-kt-indicator', 'on');
            $("#button_form_reject").prop('disabled', true);
            e.preventDefault();
            var postData = new FormData($("#modal_reject_form_add")[0]);
            $.ajax({
                url: "{{ route("cases.cases_list.reject_status") }}",
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

