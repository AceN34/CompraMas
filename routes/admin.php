<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;

    Route::middleware('guest:admins')->group(function () {
        Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
        Route::post('/admin/login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth:admins')->group(function () {
        Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');

        // Ejemplo de ruta protegida:
        Route::get('/admin/dashboard', function () {
            return inertia('Admin/Dashboard');
        })->name('admin.dashboard');
    });
?>
