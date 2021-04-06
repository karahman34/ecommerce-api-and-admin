<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('carts')->name('carts.')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');

    Route::post('/', [CartController::class, 'store'])->name('store');

    Route::patch('/{cart}', [CartController::class, 'update'])->name('update');

    Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy');
});
