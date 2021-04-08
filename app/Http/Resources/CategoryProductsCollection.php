<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryProductsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'total_products' => $category->total_products,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });
    }
}
