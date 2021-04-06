<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->pivot->id,
            'qty' => $this->pivot->qty,
            'message' => $this->pivot->message,
            'created_at' => $this->pivot->created_at,
            'updated_at' => $this->pivot->updated_at,
            'product' => new ProductResource($this),
        ];
    }
}
