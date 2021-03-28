<div class="card">
  <div class="card-header">
    <h4><i class="fas fa-shopping-cart mr-1"></i> New Orders</h4>
  </div>

  <div class="card-body">
    <table id="dt-new-orders" class="table has-actions">
      <thead>
        <tr>
          <td>Id</td>
          <td>Buyer</td>
          <td>Products</td>
          <td>Created At</td>
          <td>Actions</td>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('script')
  <script>
    const dataTableSelector = '#dt-new-orders'

    // Initialize datatable.
    $(dataTableSelector).DataTable({
      serverSide: true,
      responsive: true,
      ajax: "/new-orders",
      lengthMenu: [7, 10, 25, 50, 100],
      columns: [{
          data: 'id'
        },
        {
          data: 'user.name',
        },
        {
          data: 'product_names',
        },
        {
          data: 'created_at',
          render: data => moment(data).calendar()
        },
        {
          data: 'actions',
          searchable: false,
          orderable: false,
        },
      ],
    })

    // Listen on finish order event.
    $(document).on('order.finish', () => reloadDataTable(dataTableSelector, false))

  </script>
@endpush
