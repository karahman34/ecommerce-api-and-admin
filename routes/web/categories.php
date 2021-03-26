<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->name('categories.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/search', [CategoryController::class, 'search'])->name('search');
    });
});
