<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('customers/check-record', [CustomerController::class, 'checkRecord']);
Route::post('customers', [CustomerController::class, 'store']);
Route::post('customers/check-code', [CustomerController::class, 'checkCode']);

Route::apiResource('customers', CustomerController::class)
    ->middleware(['check.permission'])
    ->parameter('customers', 'id')
    ->except('store');
