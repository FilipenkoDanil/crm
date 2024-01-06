<?php

use App\Http\Controllers\Api\v1\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('/suppliers')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->middleware('can:show suppliers');
    Route::post('/', [SupplierController::class, 'store'])->middleware('can:create suppliers');
    Route::get('/{supplier}', [SupplierController::class, 'show'])->middleware('can:show suppliers');
    Route::patch('/{supplier}', [SupplierController::class, 'update'])->middleware('can:edit suppliers');
    Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->middleware('can:delete suppliers');
});
