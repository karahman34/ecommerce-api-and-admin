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

        {{-- Import --}}
        @include('components.button.import-btn', ['action' => route('categories.import')])

        {{-- Create --}}
        @include('components.button.create-btn', [
        'action' => route('categories.create'),
        'modal' => '#category-form-modal'
        ])
      </div>
    </div>

    <div class="card-body">
      <table id="dt-categories" class="table has-actions">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('js/pages/categories.js') }}"></script>
@endpush
