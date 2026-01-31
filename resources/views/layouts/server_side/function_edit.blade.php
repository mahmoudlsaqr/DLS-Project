$(document).on('click', '#button_form_edit', function(e) {
    $(this).attr('data-kt-indicator', 'on');
    $(this).prop('disabled', true);
    e.preventDefault();
    var postData = new FormData($("#modal_form_edit")[0]);
    $.ajax({
        url: RouteUpdate + $('#EditId').val(),
        type: 'POST',
        data: postData,
        processData: false,
        contentType: false,
        success: function(data) {
            $("#button_form_edit").removeAttr('data-kt-indicator').prop('disabled', false);
            Table.draw();
            if(data.success) {
                $('#modal_form_edit').trigger('reset');
                $('#modal_form_edit .form-select').val(null).trigger('change');
                $('#modal_edit').modal('hide');
                Swal.fire("{{ __('success') }}", data.success, 'success');
            }
            else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
        },
        error: function(xhr) {
            console.log(xhr.responseJSON)
            $("#button_form_edit").removeAttr('data-kt-indicator').prop('disabled', false);
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
