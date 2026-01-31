<script>
    $(function () {
        $(document).on('click', '.check_all', function() {
            if ($(this).is(':checked')) {
                $('.delete_check_all, #update_status_check_all, .cancel_check_all').removeClass('d-none');
                $('.btn_create').addClass('d-none');
            } else {
                $('.delete_check_all, #update_status_check_all, .cancel_check_all').addClass('d-none');
                $('.btn_create').removeClass('d-none');
            }
            $('.child_check').prop('checked', $(this).prop('checked'));
            $('.child_check').change(function() {
                let allChecked = $('.child_check').not(':checked').length === 0;
                $('.check_all').prop('checked', allChecked);
            });
        });

        $(document).on('click', '.child_check', function() {
            let check = !$('.child_check:checked').length;
            if(check){
                $('.delete_check_all, #update_status_check_all, .cancel_check_all').addClass('d-none');
                $('.btn_create').removeClass('d-none');
            } else {
                $('.delete_check_all, #update_status_check_all, .cancel_check_all').removeClass('d-none');
                $('.btn_create').addClass('d-none');
            }
        });
    });
</script>
