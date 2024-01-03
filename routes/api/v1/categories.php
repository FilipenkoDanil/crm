<?php

use App\Http\Controllers\Api\v1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->middleware('can:show categories');
    Route::post('/', [CategoryController::class, 'store'])->middleware('can:create categories');
    Route::get('/{category}', [CategoryController::class, 'show'])->middleware('can:show categories');
    Route::patch('/{category}', [CategoryController::class, 'update'])->middleware('can:edit categories');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('can:delete categories');
});
