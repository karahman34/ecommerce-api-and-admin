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

// Listen finish order button.
$(document).on('click', '.btn-order-finish', function () {
  const $btn = $(this)
  const $btnSpinner = new ButtonSpinner($btn)
  const url = $btn.data('url')

  $btnSpinner.show()
  
  $.post(url, {
    _method: 'PATCH',
    _token: CSRF_TOKEN,
  })
  .done(function () {
    $btn.remove()
    
    const $modal = $(orderDetailsModalSelector)
    const $alertStatus = $modal.find('.alert-order-status')

    $alertStatus.removeClass('alert-warning')
    $alertStatus.addClass('alert-success')
    $alertStatus.html('finish')

    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Order status changed.',
    })

    reloadDataTable(dataTableSelector, false)
  })
})