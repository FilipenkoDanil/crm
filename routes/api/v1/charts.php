<?php

use App\Http\Controllers\Api\v1\ChartController;
use Illuminate\Support\Facades\Route;

Route::prefix('/chart')->group(function () {
    Route::get('/today', [ChartController::class, 'today']);
    Route::get('/week', [ChartController::class, 'week']);
});
