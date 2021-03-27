<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->name('orders.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');

        Route::patch('/{order}/finish', [OrderController::class, 'finish'])->name('finish');

        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });
});
