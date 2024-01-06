<?php

use App\Http\Controllers\Api\v1\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/warehouses')->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->middleware('can:show warehouses');
    Route::post('/', [WarehouseController::class, 'store'])->middleware('can:create warehouses');
    Route::get('/{warehouse}', [WarehouseController::class, 'show'])->middleware('can:show warehouses');
    Route::patch('/{warehouse}', [WarehouseController::class, 'update'])->middleware('can:edit warehouses');
    Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])->middleware('can:delete warehouses');
});
