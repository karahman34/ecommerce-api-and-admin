<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::name('dashboard.')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'getView'])->name('index');
    Route::get('/popular-products', [DashboardController::class, 'getPopularProducts'])->name('popular_products');
    Route::get('/monthly-sales', [DashboardController::class, 'getMonthlySales'])->name('monthly_sales');
    Route::get('/new-orders', [DashboardController::class, 'getNewOrders'])->name('new_orders');
});
