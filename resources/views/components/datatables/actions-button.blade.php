@isset($show_url)
  <a href="{{ $show_url }}" class="btn btn-primary btn-modal-trigger" data-modal="{{ $show_modal }}"><i
      class="fas fa-eye"></i></a>
@endisset

@isset($edit_url)
  <a href="{{ $edit_url }}" class="btn btn-warning btn-modal-trigger" data-modal="{{ $modal }}"><i
      class="fas fa-edit"></i></a>
@endisset

@isset($delete_url)
  <a href="{{ $delete_url }}" class="btn btn-danger delete-prompt-trigger has-datatable"
    data-datatable="{{ $datatable }}" data-item-name="{{ $item_title }}"><i class="fas fa-trash"></i></a>
@endisset
