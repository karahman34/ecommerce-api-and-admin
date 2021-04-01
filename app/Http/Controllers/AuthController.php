<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Utils\Transformer;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get the authenthicated user data.
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        try {
            $user = Auth::guard('sanctum')->user();

            return Transformer::success('Success to get user data.', new UserResource($user));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to get user data.');
        }
    }
}
