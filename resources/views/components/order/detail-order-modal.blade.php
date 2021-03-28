<div id="detail-order-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <span class="mr-1">Order Details</span>
          <span
            class="alert alert-{{ $order->status === 'pending' ? 'warning' : 'success' }} px-2 py-0 font-weight-bold alert-order-status">{{ $order->status }}</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{-- Top --}}
        <div class="row mb-2">
          {{-- Left --}}
          <div class="col-12 col-md-6 d-flex flex-column">
            <div class="d-flex flex-wrap">
              <span class="font-weight-bold mr-1">Name:</span>
              <span>{{ $buyer->name }}</span>
            </div>

            <div class="d-flex flex-wrap">
              <span class="font-weight-bold mr-1">Email:</span>
              <span>{{ $buyer->email }}</span>
            </div>

            <div class="d-flex flex-wrap">
              <span class="font-weight-bold mr-1">Postal Code:</span>
              <span>{{ $buyer->profile->postal_code }}</span>
            </div>

            <div class="d-flex flex-wrap">
              <span class="font-weight-bold mr-1">Telephone:</span>
              <span>{{ $buyer->profile->telephone }}</span>
            </div>

            <div class="d-flex flex-wrap">
              <span class="font-weight-bold mr-1">Address:</span>
              <span>{{ $buyer->profile->address }}</span>
            </div>
          </div>

          {{-- Right --}}
          <div class="col-12 col-md-6 d-flex flex-column align-items-end">
            <div>
              <span class="font-weight-bold">Order Id: </span>
              <span>{{ $order->id }}</span>
            </div>

            <div>
              <span class="font-weight-bold">Total Items: </span>
              <span>{{ $details->count() }}</span>
            </div>

            <div>
              <span class="font-weight-bold">Sub Total: </span>
              <span>{{ $transaction->totalInRupiah() }}</span>
            </div>

            <div>
              <span class="font-weight-bold">Created At: </span>
              <span>{{ $order->created_at->toDateTimeString() }}</span>
            </div>

            @if ($order->status !== 'finish')
              <button type="button" class="btn btn-success btn-lg btn-order-finish font-weight-bolder my-1"
                data-url="{{ route('orders.finish', ['order' => $order->id]) }}" style="font-size: 20px;">
                <i class="fas fa-check"></i>
                <span class="ml-1">Finish</span>
              </button>
            @endif
          </div>
        </div>

        {{-- Products Table --}}
        <table id="dt-order-details-products" class="table has-actions">
          <thead>
            <tr>
              <th>Id</th>
              <th>Image</th>
              <th>Name</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Message</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($details as $detail)
              <tr>
                <td>{{ $detail->id }}</td>
                <td>
                  <img src="{{ $detail->thumbnail->fullPathUrl() }}" alt="{{ $detail->thumbnail->fullPathUrl() }}"
                    class="img-fluid" style="max-height: 300px;">
                </td>
                <td>{{ $detail->name }}</td>
                <td>{{ $detail->priceInRupiah() }}</td>
                <td>{{ $detail->pivot->qty }}</td>
                <td>{{ $detail->pivot->message }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
