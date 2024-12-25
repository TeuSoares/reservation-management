<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('customers/check-record', [CustomerController::class, 'checkRecord'])->name('customers.check-record');
Route::post('customers/check-code', [CustomerController::class, 'checkCode'])->name('customers.check-code');
Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');

Route::apiResource('customers', CustomerController::class)
    ->middleware(['check.permission'])
    ->parameter('customers', 'id')
    ->except('store');
