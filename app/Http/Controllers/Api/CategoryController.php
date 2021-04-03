<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesCollection;
use App\Models\Category;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
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

            $popularProducts = DB::table('products')
                                    ->select('products.category_id')
                                    ->join('detail_orders', 'detail_orders.product_id', 'products.id')
                                    ->groupBy('product_id')
                                    ->orderByRaw('COUNT(product_id) DESC');

            $query = Category::select('categories.*')
                                    ->joinSub($popularProducts, 'popular_products', function ($join) {
                                        $join->on('categories.id', '=', 'popular_products.category_id');
                                    })
                                    ->when(!is_null($search), function ($query) use ($search) {
                                        $query->where('name', 'like', '%'. $search .'%');
                                    })
                                    ->groupBy('popular_products.category_id')
                                    ->orderByRaw('COUNT(popular_products.category_id) DESC');

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
