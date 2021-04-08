<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utils\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Update user profile.
     *
     * @param   ProfileUpdateRequest  $profileUpdateRequest
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $profileUpdateRequest)
    {
        try {
            $user = Auth::user();
            
            $basicPayload = $profileUpdateRequest->only(['name', 'email']);
            $profilePayload = $profileUpdateRequest->only(['telephone', 'address']);

            $this->updateUserData($user, $basicPayload);
            $this->updateUserProfile($user, $profilePayload);

            return Transformer::success('Success to update user profile.', new UserResource($user));
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update user profile.');
        }
    }

    /**
     * Update user model.
     *
     * @param   User   $user
     * @param   array  $payload
     *
     * @return  bool
     */
    private function updateUserData(User $user, array $payload)
    {
        return $user->update($payload);
    }

    /**
     * Update user profile data.
     *
     * @param   User   $user
     * @param   array  $payload
     *
     * @return  bool
     */
    private function updateUserProfile(User $user, array $payload)
    {
        return $user->profile()->update($payload);
    }
}
