<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsCollection;
use App\Models\Product;
use App\Utils\Transformer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get products data.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'thumbnail', 'images'])
                                ->when($request->has('search'), function ($query) use ($request) {
                                    $query->where('name', 'like', '%' . $request->input('q') . '%');
                                });

            $filter = $request->input('filter');
            if (!is_null($filter)) {
                $allowedFilters = ['new'];
                                    
                if (!in_array($filter, $allowedFilters)) {
                    return Transformer::failed('Filter not allowed.', null, 400);
                }

                switch ($filter) {
                    case 'new':
                        $query->orderByDesc('products.created_at');
                        break;
                }
            }

            $products = $request->has('limit') ? $query->paginate($request->input('limit')) : $query->paginate();

            return (new ProductsCollection($products))
                    ->additional(
                        Transformer::skeleton(true, 'Success to load products data.', null, true)
                    );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load products data.');
        }
    }
}
