<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
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

    /**
     * Show Product details.
     *
     * @param   Product  $product
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        try {
            $product->load(['thumbnail', 'images']);

            return Transformer::success('Success to load product details data.', new ProductResource($product));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load product details data.');
        }
    }

    /**
     * Show related Product.
     *
    *  @param   Request  $request
     * @param   Product  $product
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function related(Request $request, Product $product)
    {
        try {
            $limit = $request->input('limit', 8);

            $relatedProducts = Product::with(['thumbnail', 'images'])
                                        ->where('category_id', $product->category_id)
                                        ->inRandomOrder()
                                        ->paginate($limit);

            return (new ProductsCollection($relatedProducts))
                    ->additional(
                        Transformer::skeleton(true, 'Success to load related product data.', null, true)
                    );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load related product data.');
        }
    }
    
    /**
     * Get popular products list.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function popular(Request $request)
    {
        try {
            $limit = $request->input('limit');
            $search = $request->input('search');

            $query = Product::select('products.*')
                                ->join('detail_orders', 'detail_orders.product_id', 'products.id')
                                ->when(!is_null($search), function ($query) use ($search) {
                                    $query->where('name', 'like', '%'. $search .'%');
                                })
                                ->groupBy('product_id')
                                ->orderByRaw('COUNT(product_id) DESC');

            $products = is_null($limit) ? $query->paginate() : $query->paginate($limit);

            return (new ProductsCollection($products))
                        ->additional(
                            Transformer::skeleton(true, 'Success to load popular products.', null, true)
                        );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load popular products.');
        }
    }

    /**
     * Get random products list.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function random(Request $request)
    {
        try {
            $limit = $request->input('limit');
            $search = $request->input('search');

            $query = Product::select('products.*')
                                ->when(!is_null($search), function ($query) use ($search) {
                                    $query->where('name', 'like', '%'. $search .'%');
                                })
                                ->inRandomOrder();

            $products = is_null($limit) ? $query->paginate() : $query->paginate($limit);

            return (new ProductsCollection($products))
                        ->additional(
                            Transformer::skeleton(true, 'Success to load random products.', null, true)
                        );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load random products.');
        }
    }
}
