$(document).on('change', '.update_status_check_all', function() {
    let status = $(this).val();
    let id = [];
    $('.child_check:checked').each(function() { id.push($(this).data('id')); });
    if (id.length > 0) {
        $.ajax({
            url: RouteUpdateStatusCheckAll,
            method: 'GET',
            data: { id: id, status: status },
            success: function(data) {
                $('.check_all').prop('checked', false);
                $('.option').prop('selected', false);
                $('.delete_check_all, #update_status_check_all').addClass('d-none');
                $('.btn_create').removeClass('d-none');
                Table.draw();
                if(data.success) { Swal.fire("{{ __('Updated!') }}", data.success, 'success'); }
                else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
            },
            error: function(xhr) {
                console.log(xhr.responseJSON)
                $('.option').prop('selected', false);
                $('.check_all').prop('checked', false);
                if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                else { Swal.fire("{{ __('error') }}", '{{ __("Selected records not updated.") }}', 'error'); }
            },
        });
    } else {
        $('.option').prop('selected', false);
        $('.check_all').prop('checked', false);
        $('.delete_check_all, #update_status_check_all').addClass('d-none');
        $('.btn_create').removeClass('d-none');
        Swal.fire("{{ __('error') }}", '{{__("Please select at least one checkbox.")}}', 'error');
    }
});
