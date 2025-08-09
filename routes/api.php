<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', App\Http\Controllers\Auth\RegisterController::class);
    Route::post('/login', App\Http\Controllers\Auth\LoginController::class);
    Route::post('/forgot-password', App\Http\Controllers\Auth\ForgotPasswordController::class);
    Route::post('/reset-password', App\Http\Controllers\Auth\ResetPasswordController::class);
    Route::get('/user', App\Http\Controllers\Auth\MeController::class)->middleware('auth:sanctum');
    Route::post('/logout', App\Http\Controllers\Auth\LogoutController::class)->middleware('auth:sanctum');
});

Route::group(['prefix' => 'contacts', 'middleware' => 'auth:sanctum'], function () {
    Route::get('', App\Http\Controllers\Contacts\IndexController::class);
    Route::get('/{id}', App\Http\Controllers\Contacts\ShowController::class);
    Route::put('/{id}', App\Http\Controllers\Contacts\UpdateController::class);
    Route::post('/', App\Http\Controllers\Contacts\StoreController::class);
    Route::delete('/{id}', App\Http\Controllers\Contacts\DestroyController::class);
});