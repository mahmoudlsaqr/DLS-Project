$("#button_modal_submit").click(function(e) {
    $(this).attr('data-kt-indicator', 'on');
    $("#button_modal_submit").prop('disabled', true);
    e.preventDefault();
    var postData = new FormData($("#form_modal_submit")[0]);
    $.ajax({
        url: "{{ route("work_orders.work_orders_management.update_phase") }}",
        type: "POST",
        data: postData,
        processData: false,
        contentType: false,
        success: function(data) {
            $("#button_modal_submit").removeAttr('data-kt-indicator').prop('disabled', false);
            Table.draw();
            if(data.success) {
                $('#form_modal_submit').trigger('reset');
                $('#form_modal_submit .form-select').val(null).trigger('change');
                $('#modal_submit').modal('hide');
                Swal.fire("{{ __('success') }}", data.success, 'success');
            } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
        },
        error: function(xhr) {
            $("#button_modal_submit").removeAttr('data-kt-indicator').prop('disabled', false);
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
