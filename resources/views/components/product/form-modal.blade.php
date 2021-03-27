@php
$action = !isset($product) ? 'create' : 'update';
$route = $action === 'create' ? route('products.store') : route('products.update', ['product' => $product->id]);
$method = $action === 'create' ? 'POST' : 'PATCH';

$modalTitle = $action === 'create' ? 'Create Product' : 'Edit ' . $product->name;
@endphp

<div id="product-form-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $modalTitle }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- The Form --}}
        <form action="{{ $route }}" method="POST" class="need-ajax has-modal has-datatable"
          data-datatable="#dt-products" enctype="multipart/form-data" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          {{-- Name & Stock --}}
          <div class="row">
            {{-- Name --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="name">Name</label>
                <input id="name" type="text" class="form-control" name="name" placeholder="Name" @isset($product)
                  value="{{ $product->name }}" @endisset required autofocus>
              </div>
            </div>

            {{-- Stock --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="stock">Stock</label>
                <input id="stock" type="number" class="form-control" name="stock" placeholder="Stock" @isset($product)
                  value="{{ $product->stock }}" @endisset required>
              </div>
            </div>
          </div>

          {{-- Price & Category --}}
          <div class="row">
            {{-- Price --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="price">Price</label>
                <input id="price" type="number" class="form-control" name="price" placeholder="Price" @isset($product)
                  value="{{ $product->price }}" @endisset required>
              </div>
            </div>

            {{-- Category --}}
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                  @isset($product)
                    <option value="{{ $product->category->id }}" selected>{{ $product->category->name }}</option>
                  @endisset
                </select>
              </div>
            </div>
          </div>

          {{-- Preview Images --}}
          @if (isset($product))
            <div class="row preview-image">
              @foreach ($product->images as $productImage)
                <div class="col-12 col-md-3 preview-image-item">
                  {{-- Image --}}
                  <img src="{{ $productImage->fullPathUrl() }}" alt="{{ $productImage->path }}"
                    class="img-fluid d-block">

                  {{-- Actions --}}
                  <div class="d-flex actions my-1">
                    {{-- Edit --}}
                    <a href="{{ route('products.edit_product_image', ['product' => $product->id, 'productImage' => $productImage->id]) }}"
                      class="mr-2 btn-modal-trigger" data-modal="#product-image-edit-modal">
                      <i class="fas fa-edit mr-1"></i>
                      Edit
                    </a>

                    {{-- Delete --}}
                    <a href="{{ route('products.destroy_product_image', ['product' => $product->id, 'productImage' => $productImage->id]) }}"
                      delete>
                      <i class="fas fa-trash mr-1"></i>
                      Delete
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

          {{-- Image Input --}}
          <div class="form-group">
            <label for="images">Images</label>
            <input multiple type="file" name="images" id="images" accept="image/*" class="form-control-file" @if ($action === 'create') required @endif>
          </div>

          {{-- Actions --}}
          @include('components.modal-actions', ['action' => $action])
        </form>
      </div>
    </div>
  </div>
</div>
