<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * Products index service.
     *
     * @param   Request  $request
     *
     * @return  Collection
     */
    public static function index(Request $request)
    {
        $q = $request->input('q');
        $limit = $request->input('limit');
        $filter = $request->input('filter');
        $category = $request->input('category');

        $query = Product::with(['category', 'thumbnail', 'images']);
        
        switch ($filter) {
            case 'new':
                $query->orderByDesc('products.created_at');
                break;

            case 'random':
                $query->inRandomOrder();
                break;
            
            case 'popular':
                $query = Product::select('products.*')
                                ->with(['category', 'thumbnail', 'images'])
                                ->join('detail_orders', 'detail_orders.product_id', 'products.id')
                                ->groupBy('products.id')
                                ->orderByRaw('COUNT(product_id) DESC');
                break;
        }

        // Apply filtering.
        $query->when(!is_null($q), function ($query) use ($q) {
            $query->where('products.name', 'like', '%' . $q . '%');
        })
        ->when(!is_null($category), function ($query) use ($category) {
            $query->select('products.*')
                    ->join('categories', 'categories.id', 'products.category_id')
                    ->where('categories.name', $category);
        });

        $products = !is_null($limit)
                            ? $query->paginate($limit)
                            : $query->paginate();

        return $products;
    }
}
