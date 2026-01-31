<script>
    $(function() {
        let locale = '{{ app() ->getLocale() }}';
        let DTable = $('#data-table').DataTable({
            processing: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            pageLength: "{{ (settings('rows_count') !== null)? settings('rows_count') : "10" }}",
            lengthChange: true,
            searching: false,
            lengthMenu: [
                [10, 20, 30, 50, 100, 300, 500],
                [10, 20, 30, 50, 100, 300, 500]
            ],
            language: {
                url: '/assets/plugins/custom/datatables/' + locale + '.json'
            },
            // dom: 'Bfrtip',
            // buttons: [
            //     {
            //         extend: 'print',
            //         // customize: function ( win ) {
            //         //     $(win.document.body)
            //         //         .css( 'font-size', '10pt' )
            //         //         .prepend(
            //         //             '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
            //         //         );
            //         //
            //         //     $(win.document.body).find( 'table' )
            //         //         .addClass( 'compact' )
            //         //         .css( 'font-size', 'inherit' );
            //         // }
            //     }
            // ]
        });
    });
</script>
