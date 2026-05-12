<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendCode']);
Route::post('/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get('/public/productos', [ProductoController::class, 'publicIndex']);
Route::get('/public/productos/{id}', [ProductoController::class, 'publicShow']);

use App\Http\Controllers\CarritoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::post('/productos', [ProductoController::class, 'store']);
    Route::get('/productos/{id}', [ProductoController::class, 'show']);
    Route::put('/productos/{id}', [ProductoController::class, 'update']);
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);

    // Rutas del carrito
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::post('/carrito', [CarritoController::class, 'store']);
    Route::put('/carrito/{idCarrito}', [CarritoController::class, 'update']);
    Route::delete('/carrito/{idCarrito}', [CarritoController::class, 'destroy']);
    Route::post('/carrito/checkout', [CarritoController::class, 'checkout']);

    // Rutas de reseñas
    Route::get('/resenas/compras', [App\Http\Controllers\ResenaController::class, 'getComprasPendientes']);
    Route::post('/resenas', [App\Http\Controllers\ResenaController::class, 'store']);

    // Rutas de pedidos (cliente)
    Route::get('/pedidos', [App\Http\Controllers\PedidoController::class, 'index']);

    // Rutas de pedidos (vendedor)
    Route::get('/vendedor/entregas', [App\Http\Controllers\PedidoController::class, 'indexVendedor']);
    Route::put('/vendedor/entregas/{id}/entregar', [App\Http\Controllers\PedidoController::class, 'marcarEntregado']);
});
