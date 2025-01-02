<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['check.origin'],
], function () {
    Route::group(['prefix' => 'customers'], function () {
        Route::post('check-record', [CustomerController::class, 'checkRecord'])->name('customers.check-record');
        Route::post('check-code', [CustomerController::class, 'checkCode'])->name('customers.check-code');
        Route::post('', [CustomerController::class, 'store'])->name('customers.store');

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('', [CustomerController::class, 'index'])->name('customers.index');
            Route::delete('{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        });

        Route::group(['middleware' => ['check.permission']], function () {
            Route::get('{id}', [CustomerController::class, 'show'])->name('customers.show');
            Route::put('{id}', [CustomerController::class, 'update'])->name('customers.update');
        });
    });

    Route::post('reservations', [ReservationController::class, 'store'])->middleware('check.permission')->name('reservations.store');
    Route::apiResource('reservations', ReservationController::class)
        ->middleware('auth:sanctum')
        ->parameters(['reservations' => 'id'])
        ->except('store');
});
