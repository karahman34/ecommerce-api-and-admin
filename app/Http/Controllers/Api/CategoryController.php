<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesCollection;
use App\Http\Resources\CategoryProductsCollection;
use App\Models\Category;
use App\Models\Product;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Get categories data.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit');
            $search = $request->input('search');
            $random = $request->input('random');

            $query = Category::when(!is_null($search), function ($query) use ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            })
            ->when(!is_null($random) && $random == '1', function ($query) {
                $query->inRandomOrder();
            })
            ->when(is_null($random), function ($query) {
                $query->addSelect([
                    'total_products' => Product::selectRaw('COUNT(*)')
                                                ->whereColumn('category_id', 'categories.id')
                ])
                ->orderByDesc('total_products');
            });

            $categories = is_null($limit)
                            ? $query->paginate()
                            : $query->paginate($limit);

            return (new CategoryProductsCollection($categories))
                    ->additional(
                        Transformer::skeleton(true, 'Success to load categories data.', null, true)
                    );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load categories data.');
        }
    }
    
    /**
     * Get popular categories.
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

            $products_category = DB::table('detail_orders')
                                        ->select('products.category_id')
                                        ->join('products', 'products.id', 'detail_orders.product_id');

            $query = Category::select('categories.*')
                                    ->joinSub($products_category, 'products_category', function ($join) {
                                        $join->on('categories.id', '=', 'products_category.category_id');
                                    })
                                    ->when(!is_null($search), function ($query) use ($search) {
                                        $query->where('categories.name', 'like', '%'. $search .'%');
                                    })
                                    ->groupBy('products_category.category_id')
                                    ->orderByRaw('COUNT(products_category.category_id) DESC');

            $categories = is_null($limit)
                            ? $query->paginate()
                            : $query->paginate($limit);

            return (new CategoriesCollection($categories))
                    ->additional(Transformer::skeleton(true, 'Success to get popular categories data.', null, true));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load popular categories data.');
        }
    }
}
