@extends('layouts.default.layout')

@section('content')
  <div class="card">
    <div class="card-header with-button">
      {{-- Title --}}
      <h4><i class="fas fa-box mr-1"></i> {{ $title }}</h4>

      {{-- Actions --}}
      <div class="d-flex align-items-center">
        {{-- Export --}}
        @include('components.button.export-btn', ['action' => route('transactions.export')])
      </div>
    </div>

    <div class="card-body">
      <table id="dt-transactions" class="table has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>Order Id</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('js/pages/transactions.js') }}"></script>
@endpush
