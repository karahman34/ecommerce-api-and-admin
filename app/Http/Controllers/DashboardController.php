<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use App\Utils\Transformer;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    /**
     * Get the dashboard view.
     *
     * @return  mixed
     */
    public function getView()
    {
        $products_count = Product::count();
        $empty_products_count = Product::where('stock', 0)->count();
        $new_orders_count = Order::where('status', 'pending')->count();
        $monthly_sales = DetailOrder::whereMonth('created_at', date('m'))->sum('qty');

        return view('dashboard', [
            'title' => 'Dashboard',
            'products_count' => $products_count,
            'empty_products_count' => $empty_products_count,
            'new_orders_count' => $new_orders_count,
            'monthly_sales' => $monthly_sales,
        ]);
    }

    /**
     * Get popular products.
     *
     * @return  @return \Illuminate\Http\JsonResponse
     */
    public function getPopularProducts()
    {
        try {
            $products = Product::select('products.name', DB::raw('COUNT(detail_orders.product_id) AS total'))
                                ->join('detail_orders', 'products.id', 'detail_orders.product_id')
                                ->groupBy('products.id')
                                ->orderByDesc('total')
                                ->orderBy('products.name')
                                ->limit(6)
                                ->get();
            
            return Transformer::success('Success to get popular products.', $products);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to get popular products.');
        }
    }

    /**
     * Get monthly sales record.
     *
     * @return  @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlySales()
    {
        try {
            $records = DetailOrder::select(DB::raw('MONTH(created_at) AS month, SUM(qty) AS total'))
                                    ->whereYear('created_at', date('Y'))
                                    ->groupBy('month')
                                    ->orderBy('month')
                                    ->get();

            return Transformer::success('Success to get monthly sales data.', $records);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to get monthly sales data.');
        }
    }

    /**
     * Get new orders.
     *
     * @return  @return \Illuminate\Http\JsonResponse
     */
    public function getNewOrders()
    {
        $query = Order::with('detail_orders', 'user')->where('status', 'pending');

        return DataTables::of($query)
                            ->addColumn('actions', function (Order $order) {
                                return view('components.datatables.actions-button', [
                                    'item' => $order,
                                    'datatable' => '#dt-orders',
                                    'show_modal' => '#detail-order-modal',
                                    'show_url' => route('orders.show', ['order' => $order->id]),
                                ]);
                            })
                            ->addColumn('product_names', function (Order $order) {
                                return $order->detail_orders->pluck('name')->join(', ');
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }
}
