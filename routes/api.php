<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', DashboardController::class);

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::get('/users/{user}', 'show');
    Route::put('/users/{user}', 'update');
    Route::delete('/users/{user}', 'destroy');
});

Route::controller(CategoryController::class)->group(function () {
	Route::get('/categories', 'index');
	Route::post('/categories', 'store');
	Route::get('/categories/{category}', 'show');
	Route::put('/categories/{category}', 'update');
	Route::delete('/categories/{category}', 'destroy');
});

Route::controller(ProductController::class)->group(function () {
	Route::get('/products', 'index');
	Route::post('/products', 'store');
	Route::get('/products/{product}', 'show');
	Route::put('/products/{product}', 'update');
	Route::delete('/products/{product}', 'destroy');
});
