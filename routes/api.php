<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::get('/token', [TokenController::class, 'getToken']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource(
        '/customer',
        CustomerController::class,
        ['parameters' => ['customer' => 'id']]
    );

    Route::put('/customer/{customerId}/add-product', [CustomerController::class, 'addProduct']);

    Route::put('/customer/{customerId}/remove-product', [CustomerController::class, 'removeProduct']);

    Route::get('/product/{productId}/customers', [ProductController::class, 'getCustomers']);
});
