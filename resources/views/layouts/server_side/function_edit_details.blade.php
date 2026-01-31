$("#button_update").click(function(e) {
    $(this).attr('data-kt-indicator', 'on');
    $("#button_update").prop('disabled', true);
    e.preventDefault();
    var postData = new FormData($("#form_update")[0]);
        $.ajax({
            url: RouteUpdateDetails,
            type: "POST",
            data: postData,
            processData: false,
            contentType: false,
            success: function(data) {
                $("#button_update").removeAttr('data-kt-indicator').prop('disabled', false);
                Table.draw();
                if (data.success) {
                    $('#form_update').trigger('reset');
                    $('#form_update .form-select').val(null).trigger('change');
                    $('#form_update .service_search').val(1).trigger('change');
                    Swal.fire("{{ __('success') }}", data.success, 'success');
                } else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
            },
        error: function(xhr) {
            $("#button_update").removeAttr('data-kt-indicator').prop('disabled', false);
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
