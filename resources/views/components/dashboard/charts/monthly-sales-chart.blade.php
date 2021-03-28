<div class="card">
  <div class="card-header">
    <h4><i class="fas fa-chart-bar mr-1"></i> Monthly Sales</h4>
  </div>
  <div class="card-body pt-0">
    <canvas id="monthly-sales-chart" height="150"></canvas>
  </div>
</div>

@push('script')
  <script>
    const monthNames = {
      1: "January",
      2: "February",
      3: "March",
      4: "April",
      5: "May",
      6: "June",
      7: "July",
      8: "August",
      9: "September",
      10: "Octomber",
      11: "November",
      12: "Desember",
    }

    function initializeMonthlySalesChart(records) {
      const ctx = document.getElementById('monthly-sales-chart').getContext('2d');
      const monthlySalesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: records.map(record => monthNames[record.month]),
          datasets: [{
            label: '# of Sales',
            data: records.map(record => record.total),
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

    function fetchMonthlySalesRecord() {
      $.get('/monthly-sales')
        .done(res => initializeMonthlySalesChart(res.data))
    }

    fetchMonthlySalesRecord()

  </script>
@endpush
