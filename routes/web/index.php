<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

Route::get('/reset-password', [ClientController::class, 'resetPassword'])->name('password.reset');

Route::name('dashboard.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'getView'])->name('index');
    Route::get('/popular-products', [DashboardController::class, 'getPopularProducts'])->name('popular_products');
    Route::get('/monthly-sales', [DashboardController::class, 'getMonthlySales'])->name('monthly_sales');
    Route::get('/new-orders', [DashboardController::class, 'getNewOrders'])->name('new_orders');
});

Route::middleware(['guest:admin'])->group(function () {
    Route::get('login', [AuthController::class, 'loginView'])->name('login');
    Route::post('login-admin', [AuthController::class, 'authenthicate'])->name('login_admin');
});
