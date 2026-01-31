document.querySelectorAll('a.toggle-vis').forEach((el) => {
    el.addEventListener('click', function(e) {
        let columnIdx = e.target.getAttribute('data-column');
        let column = Table.column(columnIdx);
        column.visible(!column.visible());
        $(this).find("i").toggleClass('d-none');
    });
});
