const formModalSelector = '#product-form-modal'
const dataTableSelector = 'table#dt-products'
const editImageModalSelector = '#product-image-edit-modal'

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
  let $previewImage = $('.preview-image')

  if (!$previewImage.length) {
    const $parentInput = $(this).parent()
    $('<div class="row preview-image"></div>').insertBefore($parentInput)
  
    $previewImage = $('.preview-image')
  }
  
  // Remove previous preview images.
  $(formModalSelector).find('.preview-image-item[from-input]').remove()

  // Add preview images.
  for (let i = 0; i < images.length; i++) {
    const image = images[i];
    const objectUrl = URL.createObjectURL(image)
    const previewImageItem = `
      <div class="col-12 col-md-3 preview-image-item" from-input>
        <img src="${objectUrl}" class="img-fluid">
      </div>
    `

    $previewImage.append(previewImageItem)
  }
})

// Delete Product Images.
$(document).on('click', `${formModalSelector} .preview-image-item .actions > a[delete]`, function (e) {
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
          // Delete image from modal.
          $btn.closest('.preview-image-item').remove()

          // Refresh datatable.
          reloadDataTable(dataTableSelector, false)

          Swal.fire({
            icon: 'success',
            title: 'Complete!',
            text: `Image has been deleted.`,
          })
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
    reloadDataTable(dataTableSelector, false)
  }
})

// Listen file change on edit image modal.
$(document).on('change', `${editImageModalSelector} input[name="image"]`, function () {
  const $input = $(this)
  
  if ($input[0].files.length) {
    $(editImageModalSelector).find('img[preview-image]').remove()

    const file = $input[0].files[0]
    const fileUrl = URL.createObjectURL(file)
    
    const img = `<img src="${fileUrl}" class="img-fluid" preview-image>`

    $(img).insertBefore($input.parent())
  }
})