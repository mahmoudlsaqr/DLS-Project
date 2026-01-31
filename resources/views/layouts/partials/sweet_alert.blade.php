<script>
    $('body').on('click', '.delete-confirm', function (e) {
        e.preventDefault();
        let form = $(this).closest('#form_del');
        Swal.fire({
            title: "{{__('Are you sure to delete')}} " + e.currentTarget.getAttribute('data-name') + " {{__('?')}}",
            text: "{{__("You won't be able to revert this")}}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText: "{{__('Cancel')}}",
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('Yes, delete it')}}",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
