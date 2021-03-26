const formModalSelector = '#product-form-modal'
const dataTableSelector = 'table#dt-products'

// Initialize datatable.
$(dataTableSelector).DataTable({
  serverSide: true,
  responsive: true,
  ajax: "/products",
  columns: [{
      data: 'id'
    },
    {
      data: 'thumbnail',
      searchable: false,
      orderable: false,
      render: function(data) {
        return `<img class="img-fluid" src="${data}" alt="product-thumbnail" />`
      }
    },
    {
      data: 'name'
    },
    {
      data: 'price'
    },
    {
      data: 'stock'
    },
    {
      data: 'actions',
      searchable: false,
      orderable: false,
    },
  ],
})

// Listen for modal open.
$(document).on('api-modal.loaded', function (e, selector) {
  const $modalTarget = $(selector)
  const $intendedModal = $(formModalSelector)

  if ($modalTarget.is($intendedModal)) {
    // Initialize select2.
    $(`${formModalSelector} select`).select2({
      placeholder: 'Choose a category',
      ajax: {
        url: `/categories/search?q=`,
        dataType: 'json',
        data: function ({ term, page }) {
          return {
            q: term,
            page: page || 1
          }
        },
        processResults: function ({ data, links }) {
          return {
            results: data.map(category => ({
              id: category.id,
              text: category.name,
            })),
            pagination: {
              more: links.next ? true : false
            },
          }
        }
      }
    })
  }
})

// Listen image input change.
$(document).on('change', `${formModalSelector} input#images`, function (e) {
  const images = e.target.files
  const $input = $(this)
  const $parent = $input.parent()
  
  // Remove previous preview images.
  $(formModalSelector).find('.preview-image[from-input]').remove()

  // Add preview images.
  for (let i = 0; i < images.length; i++) {
    const image = images[i];
    const objectUrl = URL.createObjectURL(image)

    $(`<div from-input class="preview-image my-1"><img src="${objectUrl}" class="img-fluid"></div>`).insertBefore($parent)
  }
})

// Delete Product Images.
$(document).on('click', `${formModalSelector} .preview-image .actions > a[delete]`, function (e) {
  e.preventDefault()

  const $btn = $(this)

  Swal.fire({
    icon: 'warning',
    title: `Are you sure want to delete the image ?`,
    text: "You will not be able to recover this image!",
    reverseButtons: true,
    showCancelButton: true,
    confirmButtonColor: '#fb3838',
    confirmButtonText: 'Yes, delete it!',
    preConfirm: () => {
      Swal.showLoading()
    },
    allowOutsideClick: () => !Swal.isLoading(),
  }).then(({ isConfirmed }) => {
    if (isConfirmed) {
      const url = $btn.attr('href')

      $.post(url, { _token: CSRF_TOKEN, _method: 'DELETE' })
        .done(() => {
          Swal.fire({
            icon: 'success',
            title: 'Complete!',
            text: `Image has been deleted.`,
          })

          // Delete image from modal.
          $btn.closest('.preview-image').remove()
        })
        .fail(err => Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text:  err?.responseJSON?.message || 'Failed to delete image.',
        }))
    }
  })
})

// Listen Success edit product image.
$(document).on('form-ajax.success', function (e, res, formSelector) {
  const $formAjax = $(formSelector)
  const $ownForm = $('#product-image-edit-modal form')

  if ($formAjax.is($ownForm)) {
    // Update img src.
    const $modal = $(formModalSelector)
    $modal.find(`img[src="${$ownForm.data('current-image-path')}"]`).attr('src', res.data.url)

    // Refresh datatable.
    $(dataTableSelector).DataTable().ajax.reload(null, false)
  }
})