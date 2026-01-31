$("#submit_cancel_button").click(function(e) {
    $(this).attr('data-kt-indicator', 'on');
    $("#submit_cancel_button").prop('disabled', true);
    e.preventDefault();
    var FormD = new FormData($("#cancel_form")[0]);
    $.ajax({
        url: RouteCancel,
        type: "POST",
        data: FormD,
        processData: false,
        contentType: false,
        success: function(data) {
            if(data.success) {
                $('#cancel_form').trigger('reset');
                $('#cancel_form .form-select').val(null).trigger('change');
                $('#cancel_modal').modal('hide');
                $("#submit_cancel_button").removeAttr('data-kt-indicator');
                $("#submit_cancel_button").prop('disabled', false);
                Swal.fire("{{ __('success') }}", data.success, 'success');
                Table.draw();
            }
        },
        error: function(xhr) {
            console.log(xhr.responseJSON)
            $("#submit_cancel_button").removeAttr('data-kt-indicator').prop('disabled', false);
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
