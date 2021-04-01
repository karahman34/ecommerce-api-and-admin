<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/export', [ProductController::class, 'export'])->name('export');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::get('/import', [ProductController::class, 'import'])->name('import');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::get('/{product}/images/{productImage}/edit', [ProductController::class, 'editProductImage'])->name('edit_product_image');

        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::post('/export', [ProductController::class, 'export']);
        Route::post('/import', [ProductController::class, 'import']);

        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::patch('/{product}/images/{productImage}', [ProductController::class, 'updateProductImage'])->name('update_product_image');

        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

        Route::delete('/{product}/images/{productImage}', [ProductController::class, 'destroyProductImage'])->name('destroy_product_image');
    });
});
