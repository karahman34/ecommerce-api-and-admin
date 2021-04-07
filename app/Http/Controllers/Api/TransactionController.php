<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Utils\Transformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Make user transaction.
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        try {
            $user = Auth::user();
            $cartItems  = $user->carts;

            if ($cartItems->count() < 1) {
                return Transformer::failed('You did not have any items inside your cart.', null, 400);
            }
            
            $order = $this->createOrder($user);
            $total = $this->createDetailOrder($order, $cartItems);

            $order->transaction()->create([
                'total' => $total,
            ]);

            $this->clearUserCart($user);

            return Transformer::success('Success to make transaction.', $order, 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to make transaction.');
        }
    }

    /**
     * Create Order.
     *
     * @param   User  $user
     *
     * @return  Order
     */
    private function createOrder(User $user)
    {
        return $user->orders()->create([
            'status' => 'pending',
        ]);
    }

    /**
     * Create details order.
     *
     * @param   Order       $order
     * @param   Collection  $cartItems
     *
     * @return  int         $total
     */
    private function createDetailOrder(Order $order, Collection $cartItems)
    {
        $total = 0;
        $detailOrders = $cartItems->reduce(function ($carry, Product $product) use (&$total) {
            $total += $product->price * $product->pivot->qty;

            $carry[$product->id] = [
                'qty' => $product->pivot->qty,
                'message' => $product->pivot->message,
            ];
            
            return $carry;
        }, []);

        $order->detail_orders()->attach($detailOrders);

        return $total;
    }

    /**
     * Clear user cart items.
     *
     * @param   User  $user
     *
     * @return  int
     */
    private function clearUserCart(User $user)
    {
        return $user->carts()->detach();
    }
}
