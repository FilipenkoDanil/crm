<?php

use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->middleware('can:show products');
    Route::post('/', [ProductController::class, 'store'])->middleware('can:create products');
    Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:show products');
    Route::patch('/{product}', [ProductController::class, 'update'])->middleware('can:edit products');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('can:delete products');
});
