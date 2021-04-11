<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionHistoriesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function (Transaction $transaction) {
            return [
                'id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'total' => $transaction->total,
                'status' => $transaction->order->status,
                'created_at' => $transaction->created_at
            ];
        });
    }
}
