<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'settings'], function () {
  Route::get('/', App\Http\Controllers\Setting\IndexController::class);
  Route::post('/', App\Http\Controllers\Setting\StoreController::class);
});

Route::group(['prefix' => 'zoho'], function () {
  Route::post('/generate', App\Http\Controllers\Zoho\GenerateController::class);
  Route::post('/', App\Http\Controllers\Zoho\StoreController::class);
});
