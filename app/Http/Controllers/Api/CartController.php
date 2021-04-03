<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartsCollection;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get user carts data.
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
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
}
