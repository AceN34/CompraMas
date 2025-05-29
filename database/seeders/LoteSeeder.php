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

        while ($lotesCreados < 5 && $intentos < 20) {
            $producto = $productos->random();
            $cantidadTotalLotes = $producto->lotes()->sum('cantidad');
            $cantidadDisponible = $producto->cantidad - $cantidadTotalLotes;

            // Asegurar que haya stock suficiente
            if ($cantidadDisponible <= 0) {
                $intentos++;
                continue;
            }

            $cantidadLote = rand(1, min(20, $cantidadDisponible)); // Asegura no pasarse del stock

            Lote::create([
                'codigo' => strtoupper(Str::random(8)),
                'producto_id' => $producto->id,
                'cantidad' => $cantidadLote,
                'fecha_caducidad' => Carbon::now()->addDays(rand(-10, 30)),
                'estado' => 'Consumible',
            ]);

            $lotesCreados++;
            $intentos++;
        }

        if ($lotesCreados < 5) {
            $this->command->warn("Solo se crearon $lotesCreados lote(s) por falta de stock suficiente en los productos.");
        }
    }
}
