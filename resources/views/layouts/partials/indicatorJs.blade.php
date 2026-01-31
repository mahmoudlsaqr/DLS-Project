<script>
    $(function () {
        $("#btn_submit_accounting").click(function(e) {
            e.preventDefault();
            setTimeout(function() {
                $("#btn_submit_accounting").attr('data-kt-indicator', 'on');
                setTimeout(function() {
                    $("#form_accounting").submit();
                }, 300);
                $("#btn_submit_accounting").prop('disabled', true);
            }, 200);
        });
    });
</script>

<script>
    $(function () {
        $("#btn_submit").click(function(e) {
            e.preventDefault();
            setTimeout(function() {
                $("#btn_submit").attr('data-kt-indicator', 'on');
                setTimeout(function() {
                    $("#form").submit();
                }, 300);
                $("#btn_submit").prop('disabled', true);
            }, 200);
        });
    });
</script>
