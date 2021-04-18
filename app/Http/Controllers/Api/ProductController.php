<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsCollection;
use App\Models\Product;
use App\Services\ProductService;
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
            $products = ProductService::index($request);

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
                                        ->where('name', '!=', $product->name)
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
}
