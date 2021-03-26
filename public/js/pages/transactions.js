const dataTableSelector = '#dt-transactions'

// Initialize datatable.
$(dataTableSelector).DataTable({
  serverSide: true,
  responsive: true,
  ajax: "/transactions",
  columns: [{
      data: 'id'
    },
    {
      data: 'order_id'
    },
    {
      data: 'total'
    },
    {
      data: 'actions',
      searchable: false,
      orderable: false,
    },
  ],
})