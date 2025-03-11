<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        // Route::post('logout', 'AuthController@logout')->name('');
    });
