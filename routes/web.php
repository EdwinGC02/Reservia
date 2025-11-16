<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('', function () {
    return Inertia::render('Login'); // your React component
});
Route::get('dashboards/sales', action: function () {
    return Inertia::render('dashboards/sales'); // your React component
});
Route::get('productos', [ProductController::class, 'index']);
Route::post('productos', [ProductController::class, 'store']);
Route::put('productos/{id}', [ProductController::class, 'update']);
Route::delete('productos/{id}', [ProductController::class, 'destroy']);
Route::get('listarProductos', [ProductController::class, 'listarProductos']);

