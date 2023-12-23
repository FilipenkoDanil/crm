<?php

use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ClientController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ProductWarehouseController;
use App\Http\Controllers\Api\v1\PurchaseController;
use App\Http\Controllers\Api\v1\SaleController;
use App\Http\Controllers\Api\v1\SupplierController;
use App\Http\Controllers\Api\v1\TrashController;
use App\Http\Controllers\Api\v1\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('categories', CategoryController::class);
Route::apiResource('warehouses', WarehouseController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('purchases', PurchaseController::class)->except('destroy');
Route::apiResource('sales', SaleController::class)->except(['update', 'destroy']);

Route::patch('/products-warehouses', [ProductWarehouseController::class, 'update']);


Route::prefix('trash')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [TrashController::class, 'products']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreProduct']);
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [TrashController::class, 'suppliers']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreSupplier']);
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', [TrashController::class, 'clients']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreClient']);
    });

    Route::prefix('warehouses')->group(function () {
        Route::get('/', [TrashController::class, 'warehouses']);
        Route::post('/{id}/restore', [TrashController::class, 'restoreWarehouse']);
    });
});
