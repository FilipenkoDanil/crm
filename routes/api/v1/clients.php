<?php

use App\Http\Controllers\Api\v1\ClientController;
use Illuminate\Support\Facades\Route;

Route::prefix('/clients')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->middleware('can:show clients');
    Route::post('/', [ClientController::class, 'store'])->middleware('can:create clients');
    Route::get('/{client}', [ClientController::class, 'show'])->middleware('can:show clients');
    Route::patch('/{client}', [ClientController::class, 'update'])->middleware('can:edit clients');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->middleware('can:delete clients');
});
