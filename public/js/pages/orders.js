const dataTableSelector = '#dt-orders'
const orderDetailsDataTableSelector = '#dt-detail-products'
const orderDetailsModalSelector = '#detail-order-modal'

// Initialize datatable.
$(dataTableSelector).DataTable({
  serverSide: true,
  responsive: true,
  ajax: "/orders",
  columns: [{
      data: 'id'
    },
    {
      data: 'user_id'
    },
    {
      data: 'status',
      render: data => {
        const alertType = data === 'pending' ? 'warning' : 'success'

        return `<span class="alert alert-${alertType} py-1 px-2 font-weight-bold">${data}</span>`
      }
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

// Initialize order details datatable.
function initializeOrderDetailsDataTables() {
  $(orderDetailsDataTableSelector).DataTable()
}

// Listen order details modal event.
$(document).on('api-modal.loaded', function (e, modalSelector) {
  if (modalSelector === orderDetailsModalSelector) {
    initializeOrderDetailsDataTables()
  }
})

// Listen on finish order event.
$(document).on('order.finish', () => reloadDataTable(dataTableSelector, false))