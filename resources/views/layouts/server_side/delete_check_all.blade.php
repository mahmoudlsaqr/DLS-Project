$(document).on('click', '.delete_check_all', function() {
    let id = [];
    $('.child_check:checked').each(function() { id.push($(this).data('id')); });
    if (id.length > 0) {
        Swal.fire({
            title: "{{ (isset($title))? $title : __('Are you sure to delete?') }}",
            text: "{{__('You can not undo this')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('Yes, delete it')}}",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: RouteDestroyAll,
                    method: 'GET',
                    data: { id: id },
                    success: function(data) {
                        Table.draw();
                        $('.check_all').prop('checked', false);
                        $('.child_check').prop('checked', false);
                        $('.delete_check_all, #update_status_check_all').addClass('d-none');
                        $('.btn_create').removeClass('d-none');
                        if(data.success) { Swal.fire("{{ __('Deleted!') }}", data.success, 'success'); }
                        else if(data.error) { Swal.fire("{{ __('error') }}", data.error, 'error'); }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON)
                        $('.child_check').prop('checked', false);
                        $('.check_all').prop('checked', false);
                        if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                        else { Swal.fire("{{ __('error') }}", '{{ __("Selected records not deleted.") }}', 'error'); }
                    }
                });
            }
        });
    } else {
        $('.check_all').prop('checked', false);
        $('.child_check').prop('checked', false);
        $('.delete_check_all, #update_status_check_all').addClass('d-none');
        $('.btn_create').removeClass('d-none');
        Swal.fire("{{ __('error') }}", '{{__("Please select at least one checkbox.")}}', 'error');
    }
});
