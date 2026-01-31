<script>
    $("#submit_btn").click(function(e) {
        $(this).attr('data-kt-indicator', 'on');
        $("#submit_btn").prop('disabled', true);
        e.preventDefault();
        let data = new FormData($("#form_update")[0]);
        $.ajax({
            url: "{{ $Form_route }}",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $("#submit_btn").removeAttr('data-kt-indicator').prop('disabled', false);
                if (data.success) {
                    Swal.fire("{{ __('success') }}", data.success, 'success');
                    setTimeout(function() { location.href = "{{ $Redirect_route }}"; }, 1000);
                } else {
                    Swal.fire("{{ __('error') }}", data.error, 'error');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseJSON)
                $(".invalid_message").remove();
                $("#submit_btn").prop('disabled', false).removeAttr('data-kt-indicator');
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#' + key).after('<span class="badge badge-light-danger mt-2 text-wrap text-start lh-lg invalid_message">' + value + '</span>');
                });
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

</script>
