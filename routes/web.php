<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\EstadisticasController;
use App\Http\Controllers\Admin\LoteController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProductoController::class, 'inicio'])->name('home');

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'detalles'])->name('productos.detalles');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'login'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'storeLogin']);
});

Route::middleware('auth:admins')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
    Route::get('/inicio', [ProductoController::class, 'indexAdmin'])->name('inicio');
    Route::prefix('gestionProducto')->group(function () {
        Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/nuevo', [ProductoController::class, 'create'])->name('productos.nuevo');
        Route::get('/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
        Route::get('producto/{id}', [ProductoController::class, 'show'])->name('productos.show');
    });
    Route::prefix('gestionLotes')->group(function () {
        Route::get('/', [LoteController::class, 'index'])->name('lotes.index');
        Route::get('/nuevo', [LoteController::class, 'create'])->name('lotes.create');
        Route::post('/', [LoteController::class, 'store'])->name('lotes.store');
        Route::get('/{codigo}/edit', [LoteController::class, 'edit'])->name('lotes.edit');
        Route::put('/{codigo}', [LoteController::class, 'update'])->name('lotes.update');
        Route::delete('/{codigo}', [LoteController::class, 'destroy'])->name('lotes.destroy');
    });
    Route::get('/ventas', [EstadisticasController::class, 'ventas'])->name('ventas');
});

Route::post('/register', [ClienteController::class, 'register'])->name('register');
Route::post('/login', [ClienteController::class, 'login'])->name('login');
Route::get('/login', [ClienteController::class, 'verLogin'])->name('login.form');
Route::post('/logout', [ClienteController::class, 'logout'])->name('logout');
Route::get('/register', [ClienteController::class, 'verRegistro'])->name('register.form');


Route::middleware(['auth:cliente'])->group(function () {
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');
    Route::put('/perfil/editar', [ClienteController::class, 'actualizarPerfil'])->name('cliente.editar');
    Route::put('/perfil/cambiar-contrasena', [ClienteController::class, 'cambiarContrasena'])->name('cliente.cambiarContrasena');
    Route::get('/historial-pedidos', [ClienteController::class, 'historial'])->name('cliente.historialPedidos');
    Route::get('/pedido/{id}', [ClienteController::class, 'detallePedido'])->name('cliente.detallePedido');
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::put('/carrito/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/venta/detalles', [VentaController::class, 'mostrarFormulario'])->name('venta.detalles');
    Route::post('/venta/confirmar', [VentaController::class, 'confirmar'])->name('venta.confirmar');
    Route::get('/venta/pago', [VentaController::class, 'pago'])->name('venta.pago');
    Route::get('/venta/procesar-pago', [VentaController::class, 'procesarPago'])->name('venta.procesarPago');
    Route::get('/venta/cancelar-pago', [VentaController::class, 'cancelado'])->name('venta.cancelarPago');
    Route::get('/venta/error', [VentaController::class, 'error'])->name('venta.error');
    Route::post('/venta/procesar', [VentaController::class, 'procesar'])->name('venta.procesar');
    Route::get('/venta/gracias', [VentaController::class, 'gracias'])->name('venta.gracias');
});
