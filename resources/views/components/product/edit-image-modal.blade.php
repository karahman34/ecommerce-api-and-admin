<div id="product-image-edit-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered shadow-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" class="need-ajax has-modal has-datatable"
          data-current-image-path="{{ $productImage->fullPathUrl() }}"
          action="{{ route('products.update_product_image', ['product' => $product->id, 'productImage' => $productImage->id]) }}">
          @method('PATCH') @csrf

          <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control-file">
          </div>

          {{-- Actions --}}
          @include('components.modal-actions', ['action' => 'update'])
        </form>
      </div>
    </div>
  </div>
</div>
