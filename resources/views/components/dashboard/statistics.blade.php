<div class="row">
  {{-- Products --}}
  <div class="col-6 col-md-3">
    @include('components.card-statistic', [
    'header' => 'Products',
    'value' => $products_count,
    'background' => 'bg-primary',
    'icon' => 'fa-box',
    ])
  </div>

  {{-- Empty Products --}}
  <div class="col-6 col-md-3">
    @include('components.card-statistic', [
    'header' => 'Empty Products',
    'value' => $empty_products_count,
    'background' => 'bg-danger',
    'icon' => 'fa-box',
    ])
  </div>

  {{-- New Orders --}}
  <div class="col-6 col-md-3">
    @include('components.card-statistic', [
    'header' => 'New Orders',
    'value' => $new_orders_count,
    'background' => 'bg-warning',
    'icon' => 'fa-shopping-cart',
    ])
  </div>

  {{-- Monthly Sales --}}
  <div class="col-6 col-md-3">
    @include('components.card-statistic', [
    'header' => 'Monthly Sales',
    'value' => $monthly_sales,
    'background' => 'bg-success',
    'icon' => 'fa-chart-bar',
    ])
  </div>
</div>
