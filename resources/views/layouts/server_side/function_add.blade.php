$("#button_form_add").click(function(e) {
    $(this).attr('data-kt-indicator', 'on');
    $("#button_form_add").prop('disabled', true);
    e.preventDefault();
    var postData = new FormData($("#modal_form_add")[0]);
    $.ajax({
        url: RouteAdd,
        type: "POST",
        data: postData,
        processData: false,
        contentType: false,
        success: function(data) {
            $("#button_form_add").removeAttr('data-kt-indicator').prop('disabled', false);
            Table.draw();
            if(data.success) {
                $('#modal_form_add').trigger('reset');
                $('#modal_form_add .form-select').val(null).trigger('change');
                $('#modal_add').modal('hide');
                @if(isset($redirectRoute))
                    if (data.id) { location.href = "{{ $redirectRoute }}" + data.id; }
                    else { location.href = "{{ $redirectRoute }}"; }
                @endif
                Swal.fire("{{ __('success') }}", data.success, 'success');
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
