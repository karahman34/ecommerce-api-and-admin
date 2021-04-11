<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'total' => $this->total,
            'status' => $this->order->status,
            'created_at' => $this->created_at,
            'send_to' => [
                'name' => $this->name,
                'telephone' => $this->telephone,
                'address' => $this->address,
            ],
            'order_details' => new CartsCollection($this->order->detail_orders)
        ];
    }
}
