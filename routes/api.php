<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::apiResource(
    '/customer',
    CustomerController::class,
    ['parameters' => ['customer' => 'id']]
);

Route::put('/customer/{customerId}/add-product', [CustomerController::class, 'addProduct']);
