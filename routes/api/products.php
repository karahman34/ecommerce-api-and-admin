<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/random', [ProductController::class, 'random'])->name('random');
    Route::get('/popular', [ProductController::class, 'popular'])->name('popular');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/related', [ProductController::class, 'related'])->name('related');
});
