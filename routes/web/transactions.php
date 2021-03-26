<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');

        Route::get('/export', [TransactionController::class, 'export'])->name('export');

        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
    });
});
