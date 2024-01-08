<?php

use App\Http\Controllers\Auth\OAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/auth/{provider}', [OAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [OAuthController::class, 'providerCallback']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

Auth::routes();
