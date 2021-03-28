<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard.index') }}">{{ env('APP_NAME') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('dashboard.index') }}">{{ env('APP_NAME') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>

      {{-- Dropdown Example --}}
      {{-- <li class="nav-item dropdown active">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        <ul class="dropdown-menu">
          <li class="active"><a class="nav-link" href="index-0.html">General Dashboard</a></li>
          <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
        </ul>
      </li> --}}

      {{-- Dashboard --}}
      <li @if (request()->is('dashboard.index')) class="active" @endif><a class="nav-link" href="{{ route('dashboard.index') }}"><i class="fas fa-fire"></i>
          <span>Dashboard</span></a>
      </li>

      {{-- Products --}}
      <li @if (request()->is('products.index')) class="active" @endif><a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-box"></i>
          <span>Products</span></a>
      </li>

      {{-- Categories --}}
      <li @if (request()->is('categories.index')) class="active" @endif><a class="nav-link" href="{{ route('categories.index') }}"><i class="fas fa-tags"></i>
          <span>Categories</span></a>
      </li>

      {{-- Orders --}}
      <li @if (request()->is('orders.index')) class="active" @endif><a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-shopping-cart"></i>
          <span>Orders</span></a>
      </li>

      {{-- Transactions --}}
      <li @if (request()->is('transactions.index')) class="active" @endif><a class="nav-link" href="{{ route('transactions.index') }}"><i
            class="fas fa-clipboard-list"></i>
          <span>Transactions</span></a>
      </li>
    </ul>
  </aside>
</div>
