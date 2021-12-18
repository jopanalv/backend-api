<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

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

// Public route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/list', [ProductController::class, 'index']);
Route::get('/search/{name}', [ProductController::class, 'search']);
Route::get('/product/{slug}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/product/add', [ProductController::class, 'store']);
    Route::post('/product/edit/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']);

    Route::post('/cart/add', [CartController::class, 'store']);
    Route::get('/cart/list', [CartController::class, 'index']);
    Route::get('/cart/{user_id}', [CartController::class, 'show']);
    Route::delete('/cart/delete/{id}', [CartController::class, 'destroy']);

    Route::get('/checkout/list', [CheckoutController::class, 'index']);
    Route::get('/checkout/{id}', [CheckoutController::class, 'show']);
    Route::get('/checkout/status/success', [CheckoutController::class, 'getSuccess']);
    Route::get('/checkout/status/waiting', [CheckoutController::class, 'getWaiting']);
    Route::get('/checkout/status/process', [CheckoutController::class, 'getProcess']);
    Route::get('/checkout/status/failed', [CheckoutController::class, 'getFailed']);
    Route::post('/checkout/add', [CheckoutController::class, 'store']);
    Route::post('/checkout/confirm/{id}', [CheckoutController::class, 'confirm']);
    Route::post('/checkout/cancel/{id}', [CheckoutController::class, 'cancel']);
    Route::delete('/checkout/delete/{id}', [CheckoutController::class, 'destroy']);

    Route::get('/user/list', [AuthController::class, 'listUser']);
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::delete('/user/delete/{id}', [AuthController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
