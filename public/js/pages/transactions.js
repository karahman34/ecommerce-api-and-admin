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
      data: 'created_at',
      render: data => moment(data).format('L - LT')
    },
    {
      data: 'actions',
      searchable: false,
      orderable: false,
    },
  ],
})