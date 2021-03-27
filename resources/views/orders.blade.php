@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header with-button">
      {{-- Title --}}
      <h4><i class="fas fa-box mr-1"></i> {{ $title }}</h4>

      {{-- Actions --}}
      <div class="d-flex align-items-center">
        {{-- Export --}}
        @include('components.button.export-btn', ['action' => route('categories.export')])
      </div>
    </div>

    <div class="card-body">
      <table id="dt-orders" class="table has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>User Id</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('js/pages/orders.js') }}"></script>
@endpush
