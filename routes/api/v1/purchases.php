<?php

use App\Http\Controllers\Api\v1\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('/purchases')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->middleware('can:show purchases');
    Route::post('/', [PurchaseController::class, 'store'])->middleware('can:create purchases');
    Route::get('/{purchase}', [PurchaseController::class, 'show'])->middleware('can:show purchases');
    Route::patch('/{purchase}', [PurchaseController::class, 'update'])->middleware('can:edit purchases');
});
