processing: true,
serverSide: true,
deferRender: true,
stateSave: false,
info: true,
paging: true,
order: [
    [0, "asc"]
],
select: {
    style: 'multi',
    selector: 'td:first-child input[type="checkbox"]',
    className: 'row-selected'
},
pageLength: 50,
lengthChange: false,
lengthMenu: [
    [10, 20, 30, 50, 100, 300, 500],
    [10, 20, 30, 50, 100, 300, 500]
],
language: {
    url: '{{"/assets/plugins/custom/datatables/{$locale}.json"}}'
},

