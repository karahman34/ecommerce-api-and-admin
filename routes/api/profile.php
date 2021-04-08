<?php

use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->name('profile.')->middleware(['auth:sanctum'])->group(function () {
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
});
