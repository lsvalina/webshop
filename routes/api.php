<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('login', [ApiAuthController::class, 'login']);
Route::post('logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/category/{category}', [ProductController::class, 'productsByCategory']);
    Route::get('/filter', [ProductController::class, 'filter']);
    Route::get('/{SKU}', [ProductController::class, 'show']);
});

Route::group(['prefix' => 'orders'], function () {
   Route::post('/', [OrderController::class, 'store']);
});
