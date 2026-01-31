$("#submit_cancel_button_check_all").click(function(e) {
    let id = [];
    $('.child_check:checked').each(function() {
        id.push($(this).data('id'));
    });
    $(this).attr('data-kt-indicator', 'on');
    $("#submit_cancel_button_check_all").prop('disabled', true);
    let ReasonID = $("#ReasonID").val();
    let ReasonNote = $("#ReasonNote").val();
    if (id.length > 0) {
        $.ajax({
            url: RouteCancelAll,
            method: 'GET',
            data: { id: id, reason_id: ReasonID, reason_note: ReasonNote },
            success: function(data) {
                $('.check_all').prop('checked', false);
                $('.cancel_check_all').addClass('d-none');
                $('.btn_create').removeClass('d-none');

                $('#cancel_form_check_all').trigger('reset');
                $('#cancel_form_check_all .form-select').val(null).trigger('change');
                $('#cancel_modal_check_all').modal('hide');
                $("#submit_cancel_button_check_all").removeAttr('data-kt-indicator').prop('disabled', false);

                Table.draw();
                if(data.success) { Swal.fire("{{ __('Canceled!') }}", data.success, 'success'); }
                else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
            },
            error: function(xhr) {
                console.log(xhr.responseJSON)
                $("#submit_cancel_button_check_all").removeAttr('data-kt-indicator').prop('disabled', false);
                if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                else if (xhr.responseJSON) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';
                $.each(errors, function (key, value) { errorMessage += '<span class="badge badge-light-danger mb-2">' + value + '</span><br>'; });
                Swal.fire("{{ __('error') }}", errorMessage, 'error');
                } else { Swal.fire("{{ __('error') }}", '{{ __("Something went wrong, please try again later") }}', 'error'); }

                $('#cancel_form_check_all').trigger('reset');
                $('#cancel_form_check_all .form-select').val(null).trigger('change');
                $('#cancel_modal_check_all').modal('hide');
                $("#submit_cancel_button_check_all").removeAttr('data-kt-indicator');
                $("#submit_cancel_button_check_all").prop('disabled', false);
            }
        });
    } else {
        $('.cancel_check_all').addClass('d-none');
        $('.btn_create').removeClass('d-none');
        Swal.fire("{{ __('error') }}", '{{__("Please select at least one checkbox.")}}', 'error');
    }
});
