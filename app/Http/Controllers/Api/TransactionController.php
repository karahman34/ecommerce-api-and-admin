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

            if (!$this->profileComplete($user)) {
                return Transformer::failed('You must complete your profile first to continue.', null, 422);
            }
            
            $cartItems = $user->carts;
            if ($cartItems->count() < 1) {
                return Transformer::failed('You did not have any items inside your cart.', null, 400);
            }
            
            $order = $this->createOrder($user);
            $total = $this->createDetailOrder($order, $cartItems);

            $order->transaction()->create([
                'total' => $total,
                'name' => $user->name,
                'address' => $user->profile->address,
                'telephone' => $user->profile->telephone,
            ]);

            $this->clearUserCart($user);

            return Transformer::success('Success to make transaction.', $order, 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to make transaction.');
        }
    }

    /**
     * Check weather the user profile is complete.
     *
     * @param   User  $user
     *
     * @return  bool
     */
    private function profileComplete(User $user)
    {
        $profile = $user->profile;

        if (is_null($profile->telephone) || is_null($profile->address)) {
            return false;
        }

        return true;
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
