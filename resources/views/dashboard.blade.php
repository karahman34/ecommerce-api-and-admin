@extends('layouts.default.layout')

@section('content')
  {{-- Card Statistics --}}
  @include('components.dashboard.statistics')

  {{-- Charts --}}
  <div class="row">
    {{-- Monthly Sales Record --}}
    <div class="col-12 col-md-6">
      @include('components.dashboard.charts.monthly-sales-chart')
    </div>

    {{-- Popular Products --}}
    <div class="col-12 col-md-6">
      @include('components.dashboard.charts.popular-products-chart')
    </div>
  </div>

  {{-- Second Row --}}
  <div class="row">
    {{-- New Orders Table --}}
    <div class="col-12 col-md-6">
      @include('components.dashboard.new-orders-table')
    </div>
  </div>
@endsection
