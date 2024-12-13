<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('customers/check-record', [CustomerController::class, 'checkRecord'])->name('customers.checkRecord');
Route::post('customers/check-code', [CustomerController::class, 'checkCode'])->name('customers.checkCode');
Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');

Route::apiResource('customers', CustomerController::class)
    ->middleware(['check.permission'])
    ->parameter('customers', 'id')
    ->except('store');
