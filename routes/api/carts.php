<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('carts')->name('carts.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
});
