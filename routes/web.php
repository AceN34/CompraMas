<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\LoteController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Inicio');
})->name('home');

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

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
    });
    Route::prefix('gestionLotes')->group(function () {
        Route::get('/', [LoteController::class, 'index'])->name('lotes.index');
        Route::get('/nuevo', [LoteController::class, 'create'])->name('lotes.create');
        Route::post('/', [LoteController::class, 'store'])->name('lotes.store');
        Route::get('/{codigo}/edit', [LoteController::class, 'edit'])->name('lotes.edit');
        Route::put('/{codigo}', [LoteController::class, 'update'])->name('lotes.update');
        Route::delete('/{codigo}', [LoteController::class, 'destroy'])->name('lotes.destroy');
    });
    Route::get('ventas/', [LoteController::class, 'index'])->name('ventas.index');
});

Route::post('/register', [ClienteController::class, 'register'])->name('register');
Route::post('/login', [ClienteController::class, 'login'])->name('login');
Route::get('/login', [ClienteController::class, 'verLogin'])->name('login.form');
Route::post('/logout', [ClienteController::class, 'logout'])->name('logout');
Route::get('/register', [ClienteController::class, 'verRegistro'])->name('register.form');

Route::middleware(['auth:cliente'])->group(function () {
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');
    Route::get('/perfil/editar', [ClienteController::class, 'editarPerfil'])->name('cliente.editar');
    Route::post('/perfil/actualizar', [ClienteController::class, 'actualizarPerfil'])->name('cliente.actualizar');
    Route::get('/perfil/cambiar-contrasena', [ClienteController::class, 'cambiarContrasenaForm'])->name('cliente.cambiar_contrasena');
    Route::post('/perfil/cambiar-contrasena', [ClienteController::class, 'actualizarContrasena'])->name('cliente.actualizar_contrasena');
});
