<div class="card">
  <div class="card-header">
    <h4><i class="fas fa-box mr-1"></i> Popular Products</h4>
  </div>
  <div class="card-body pt-0">
    <canvas id="popular-products-chart" height="150"></canvas>
  </div>
</div>

@push('script')
  <script>
    function initializePopularProductsChart(products) {
      const ctx = document.getElementById('popular-products-chart').getContext('2d');
      const popularProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: products.map(product => product.name),
          datasets: [{
            label: '# of Sales',
            data: products.map(product => product.total),
            backgroundColor: [
              'rgba(255, 99, 132)',
              'rgba(54, 162, 235)',
              'rgba(255, 206, 86)',
              'rgba(75, 192, 192)',
              'rgba(153, 102, 255)',
              'rgba(255, 159, 64)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });
    }

    function fetchPopularProducts() {
      $.get('/popular-products')
        .done(res => initializePopularProductsChart(res.data))
    }

    fetchPopularProducts()

  </script>
@endpush
