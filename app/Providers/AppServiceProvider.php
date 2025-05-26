<?php

namespace App\Providers;

use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                ];
            },
        ]);
        Inertia::share([
            'totalCarrito' => function () {
                $cliente = Auth::guard('cliente')->user();
                if (!$cliente) return 0;

                return Carrito::where('cliente_id', $cliente->id)
                    ->with('producto')
                    ->get()
                    ->sum(fn($item) => $item->producto->precio * $item->cantidad);
            },
            'carrito' => function () {
                $cliente = Auth::guard('cliente')->user();
                if (!$cliente) return [];

                return Carrito::where('cliente_id', $cliente->id)
                    ->with('producto')
                    ->get();
            },
        ]);
    }
}
