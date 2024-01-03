<?php

use App\Http\Controllers\Api\v1\TrashController;
use Illuminate\Support\Facades\Route;

Route::prefix('trash')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [TrashController::class, 'products']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreProduct'])->middleware('can:restore trash');
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [TrashController::class, 'suppliers']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreSupplier'])->middleware('can:restore trash');
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', [TrashController::class, 'clients']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreClient'])->middleware('can:restore trash');
    });

    Route::prefix('warehouses')->group(function () {
        Route::get('/', [TrashController::class, 'warehouses']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreWarehouse'])->middleware('can:restore trash');
    });
})->middleware('can:show trash');
