$(document).on('click', '.delete-confirm', function(e) {
    let Id = $(this).data('id');
    Swal.fire({
        title: "{{ (isset($title))? $title : __('Are you sure to delete') }} " + e.currentTarget.getAttribute('data-name') + " {{__('?')}}",
        text: "{{__('You can not undo this')}}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{__('Yes, delete it')}}",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: RouteDestroy + Id,
                data: { "_token": "{{ csrf_token() }}" },
                success: function(response) {
                    Table.draw();
                    if(response.success) { Swal.fire("{{ __('Deleted!') }}", response.success, 'success'); }
                    else if(response.error) { Swal.fire("{{ __('error') }}", response.error, 'error'); }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON)
                    $('.check_all').prop('checked', false);
                    if (xhr.responseJSON.error) { Swal.fire("{{ __('error') }}", xhr.responseJSON.error, 'error'); }
                    else { Swal.fire("{{ __('error') }}", '{{ __("Selected records not deleted.") }}', 'error'); }
                }
            });
        }
    });
});
