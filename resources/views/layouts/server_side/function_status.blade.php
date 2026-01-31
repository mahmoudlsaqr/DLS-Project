$(document).on('click', 'input[name="status"]', function() {
    let checkbox = $(this);
    let Id = $(this).data('id');
    let status = $(this).prop('checked');
    $.ajax({
    url: RouteStatusUpdate + Id,
    type: "GET",
    data: { 'status': status },
    success: function(data) {
        checkbox.prop('checked', data.status);
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
