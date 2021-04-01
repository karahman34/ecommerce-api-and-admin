<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->name('categories.')->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/export', [CategoryController::class, 'export'])->name('export');
        Route::get('/import', [CategoryController::class, 'import'])->name('import');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::get('/search', [CategoryController::class, 'search'])->name('search');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');

        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::post('/import', [CategoryController::class, 'import']);

        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');

        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});
