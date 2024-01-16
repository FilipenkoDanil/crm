<?php

use App\Http\Controllers\Api\v1\ProductWarehouseController;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;
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

Route::get('/get-permissions', function () {
    return auth()->check()?auth()->user()->jsPermissions():0;
});
Route::post('/payment', [PaymentController::class, 'service']);

Route::middleware('auth:sanctum')->group(function () {
    require_once 'categories.php';
    require_once 'warehouses.php';
    require_once 'products.php';
    require_once 'clients.php';
    require_once 'suppliers.php';
    require_once 'purchases.php';
    require_once 'sales.php';
    require_once 'trash.php';
    require_once 'charts.php';

    Route::get('/payments', function () {
        return Payment::all();
    });

    Route::patch('/products-warehouses', [ProductWarehouseController::class, 'update'])->middleware('can:edit productWarehouses');
});
