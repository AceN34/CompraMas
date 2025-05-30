<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoteSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();
        $lotesCreados = 0;
        $intentos = 0;

        // Los intentos para evitar que se quede en bucle infinito si no hay mas stock para asignar
        while ($lotesCreados < 12 && $intentos < 40) {
            $producto = $productos->random();
            $cantidadTotalLotes = $producto->lotes()->sum('cantidad');
            $cantidadDisponible = $producto->cantidad - $cantidadTotalLotes;

            // Asegurar que haya stock suficiente
            if ($cantidadDisponible <= 0) {
                $intentos++;
                continue;
            }

            $cantidadLote = rand(1, min(20, $cantidadDisponible)); // Asegura no pasarse del stock
            $fechaCaducidad = Carbon::now()->addDays(rand(-10, 30));

            Lote::create([
                'codigo' => strtoupper(Str::random(8)),
                'producto_id' => $producto->id,
                'cantidad' => $cantidadLote,
                'fecha_caducidad' => Carbon::now()->addDays(rand(-10, 30)),
                'estado' => $fechaCaducidad->isPast() ? 'Caducado' : 'Consumible',
            ]);

            $lotesCreados++;
            $intentos++;
        }
    }
}
