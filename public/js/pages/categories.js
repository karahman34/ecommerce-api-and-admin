const dataTableSelector = '#dt-categories'

// Initialize datatable.
$(dataTableSelector).DataTable({
  serverSide: true,
  responsive: true,
  ajax: "/categories",
  columns: [{
      data: 'id'
    },
    {
      data: 'name'
    },
    {
      data: 'actions',
      searchable: false,
      orderable: false,
    },
  ],
})