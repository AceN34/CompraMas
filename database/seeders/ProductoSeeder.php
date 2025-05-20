<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'nombre' => 'Bolsa Patatas Sabrosa 2kg',
                'precio' => 3.99,
                'categoria' => 'Verduras',
                'cantidad' => 50,
                'imagen' => 'patatas_sabrosa_2kg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Coca Cola 500ml',
                'precio' => 1.50,
                'categoria' => 'Bebidas',
                'cantidad' => 100,
                'imagen' => 'coca_cola_500ml.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lay\'s Vinagreta 150gr',
                'precio' => 1.70,
                'categoria' => 'Snacks',
                'cantidad' => 80,
                'imagen' => 'lays_vinagreta_150gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gublins 135gr',
                'precio' => 1.50,
                'categoria' => 'Snacks',
                'cantidad' => 75,
                'imagen' => 'gublins_135gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lasaña Boloñesa La Cocinera 280gr',
                'precio' => 3.79,
                'categoria' => 'Comida Preparada',
                'cantidad' => 40,
                'imagen' => 'lasana_cocinera_280gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Pasta Tallarines Gallo 250gr',
                'precio' => 1.00,
                'categoria' => 'Pasta y Arroz',
                'cantidad' => 60,
                'imagen' => 'tallarines_gallo_250gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Azúcar Blanco Azucarera 1kg',
                'precio' => 1.05,
                'categoria' => 'Lácteos',
                'cantidad' => 90,
                'imagen' => 'azucar_blanco_azucarera_1kg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Aceite Oliva Virgen Extra La Española 500ml',
                'precio' => 5.70,
                'categoria' => 'Lácteos',
                'cantidad' => 35,
                'imagen' => 'aceite_la_espanola_500ml.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
