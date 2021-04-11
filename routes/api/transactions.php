<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::name('transactions.')->prefix('transactions')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');

        Route::post('/', [TransactionController::class, 'store'])->name('store');
    });
});
