<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        // Route::post('logout', 'AuthController@logout')->name('');
    });

Route::controller(FlightController::class)
    ->middleware('auth:api')
    ->prefix('flights')
    ->name('flights.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::get('/{flight_id}', 'detail')->name('detail');
        Route::post('/{flight_id}/approve', 'approve')->name('approve');
        Route::post('/{flight_id}/cancel', 'cancel')->name('cancel');
    });
