<?php

use App\Http\Controllers\Api\v1\SaleController;
use Illuminate\Support\Facades\Route;

Route::prefix('/sales')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->middleware('can:show sales');
    Route::post('/', [SaleController::class, 'store'])->middleware('can:create sales');
    Route::get('/{sale}', [SaleController::class, 'show'])->middleware('can:show sales');
});
