<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YallaControl;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(YallaControl::class)->prefix('yalla')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('store', 'store')->name('store');
});

