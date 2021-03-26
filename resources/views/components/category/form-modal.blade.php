@php
$action = !isset($category) ? 'create' : 'update';
$route = $action === 'create' ? route('categories.store') : route('categories.update', ['category' => $category->id]);
$method = $action === 'create' ? 'POST' : 'PATCH';

$modalTitle = $action === 'create' ? 'Create Category' : 'Edit ' . $category->name;
@endphp

<div id="category-form-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
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
          data-datatable="#dt-categories" @if ($action === 'update') data-stay-paging="1" @endif>
          @csrf @method($method)

          {{-- Name --}}
          <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" class="form-control" name="name" placeholder="Name" @isset($category)
              value="{{ $category->name }}" @endisset required autofocus>
          </div>

          {{-- Actions --}}
          @include('components.modal-actions', ['action' => $action])
        </form>
      </div>
    </div>
  </div>
</div>
