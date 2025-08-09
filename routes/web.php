<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('zoho')->group(function () {
    Route::get('/authorize', App\Http\Controllers\Zoho\AuthorizeController::class);
    Route::get('/callback', App\Http\Controllers\Zoho\CallbackController::class);
});