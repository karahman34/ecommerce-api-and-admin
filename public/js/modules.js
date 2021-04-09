// Listen order details modal event.
$(document).on('api-modal.loaded', function (e, modalSelector) {
  if (modalSelector === '#detail-order-modal') {
    $('#dt-order-details-products').DataTable()
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
    
    const $modal = $('#detail-order-modal')
    const $alertStatus = $modal.find('.alert-order-status')

    $alertStatus.removeClass('alert-warning')
    $alertStatus.addClass('alert-success')
    $alertStatus.html('delivered')

    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Order status changed.',
    })

    $(document).trigger('order.finish')
  })
  .fail(() => Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Failed to update order status.',
  }))
})