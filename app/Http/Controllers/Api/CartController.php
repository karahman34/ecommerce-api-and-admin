<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartsCollection;
use App\Models\Cart;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get user carts data.
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $carts = Auth::user()->carts;

            return (new CartsCollection($carts))
                    ->additional(
                        Transformer::skeleton(true, 'Success to load carts data.', null, true)
                    );
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to load carts data.');
        }
    }

    /**
     * Add product to the cart.
     *
     * @param   CartRequest  $cartRequest
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function store(CartRequest $cartRequest)
    {
        try {
            $user = Auth::user();
            $payload = $cartRequest->only(['product_id', 'qty', 'message']);

            if ($user->carts()->wherePivot('product_id', $payload['product_id'])->exists()) {
                return Transformer::failed('Product is already exist in your cart.', null, 400);
            }

            // Attach new cart.
            $user->carts()->attach($payload['product_id'], [
                'qty' => $payload['qty'],
                'message' => $payload['message'],
            ]);

            // Get last cart.
            $cart = $user->carts()->orderByDesc('carts.created_at')->first();

            return Transformer::success('Success to add product to the cart.', new CartResource($cart), 201);
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to add product to the cart.', $th->getMessage());
        }
    }

    /**
     * Update Cart data.
     *
     * @param   CartRequest  $cartRequest
     * @param   Cart         $cart
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function update(CartRequest $cartRequest, Cart $cart)
    {
        try {
            $cart->update(
                $cartRequest->only(['qty', 'message'])
            );

            return Transformer::success('Success to update cart.', new CartResource($cart));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update cart.');
        }
    }

    /**
     * Delete Cart data.
     *
     * @param   Cart         $cart
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function destroy(Cart $cart)
    {
        try {
            $cart->delete();

            return Transformer::success('Success to delete cart.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete cart.');
        }
    }
}
