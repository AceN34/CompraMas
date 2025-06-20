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
                'cantidad' => 0,
                'imagen' => 'gublins_135gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lasaña Boloñesa La Cocinera 280gr',
                'precio' => 3.79,
                'categoria' => 'Comida Preparada',
                'cantidad' => 5,
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
                'categoria' => 'Condimentos',
                'cantidad' => 90,
                'imagen' => 'azucar_blanco_azucarera_1kg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Aceite Oliva Virgen Extra La Española 500ml',
                'precio' => 5.70,
                'categoria' => 'Aceites',
                'cantidad' => 35,
                'imagen' => 'aceite_la_espanola_500ml.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Sopa Pollo Fideos Finos Gallina Blanca 71gr',
                'precio' => 0.90,
                'categoria' => 'Caldos',
                'cantidad' => 45,
                'imagen' => 'sopa_pollo_fideos_finos_gallina_blanca_71gr.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cola Cao Energy 188ml 4UD',
                'precio' => 2.75,
                'categoria' => 'Batidos',
                'cantidad' => 35,
                'imagen' => 'Cola_Cao_Energy_188ml_4UD.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tomate Frito Orlando Pack 3UD 212gr',
                'precio' => 2.10,
                'categoria' => 'Salsas',
                'cantidad' => 76,
                'imagen' => 'tomate_frito_orlando_pack_3UD_212gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Chorizo Picante Revilla 65gr',
                'precio' => 1.10,
                'categoria' => 'Embutidos',
                'cantidad' => 51,
                'imagen' => 'chorizo_picante_revilla_65gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ketchup Heinz 567gr',
                'precio' => 2.40,
                'categoria' => 'Salsas',
                'cantidad' => 37,
                'imagen' => 'ketchup_heinz_567gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Fuze Tea 500ml',
                'precio' => 1.60,
                'categoria' => 'Bebidas',
                'cantidad' => 63,
                'imagen' => 'fuze_tea_500ml.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Jamon Cocido ElPozo 110gr',
                'precio' => 1.00,
                'categoria' => 'Embutidos',
                'cantidad' => 51,
                    'imagen' => 'jamon_cocido_elpozo_lonchas_110gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Yatekomo Pollo Gallina Blanca 60gr',
                'precio' => 1.79,
                'categoria' => 'Comida Preparada',
                'cantidad' => 43,
                'imagen' => 'yatekomo_pollo_gallina_blanca_60gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Powerade Mountain Blast 600ml',
                'precio' => 1.40,
                'categoria' => 'Bebidas',
                'cantidad' => 4,
                'imagen' => 'powerade_mountain_blast_600_ml.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Caldo Casero Pollo Gallina Blanca 1l',
                'precio' => 1.85,
                'categoria' => 'Caldo',
                'cantidad' => 13,
                'imagen' => 'caldo_casero_pollo_gallina_blanca_1l.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Macarron Plumas nº3 Gallo 450gr',
                'precio' => 0.95,
                'categoria' => 'Pasta y Arroz',
                'cantidad' => 41,
                'imagen' => 'macarron_plumas_nº3_gallo_450gr.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Botella Agua Solan Cabras 1l',
                'precio' => 1.05,
                'categoria' => 'Bebidas',
                'cantidad' => 84,
                'imagen' => 'botella_agua_solan_cabras_1l.webp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
