<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', [AuthController::class, 'getMe'])->name('me');
    });
});
