<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\VentaProducto;
use App\Models\Producto;
use App\Models\Cliente;
use Faker\Factory as Faker;

class VentaSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();

        // Obtener algunos clientes y productos para asociar
        $clientes = Cliente::all();
        $productos = Producto::all();

        // Crear 10 ventas
        for ($i = 1; $i <= 10; $i++) {
            $cliente = $clientes->random();

            $venta = Venta::create([
                'cliente_id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'direccion' => 'Calle Ejemplo ' . rand(1, 100),
                'ciudad' => 'Ciudad Ejemplo',
                'codigo_postal' => '12345',
                'telefono' => '600123456',
                'comentarios' => 'Comentario de prueba ' . $i,
                'total' => 0, // Lo actualizaremos luego
                'estado' => 'Pendiente',
            ]);

            // Generar una fecha aleatoria entre el 1 de enero y el 31 de diciembre del año actual
            $fechaVenta = $faker->dateTimeThisYear();

            // Actualizar los timestamps con la fecha generada
            $venta->update([
                'created_at' => $fechaVenta,
                'updated_at' => $fechaVenta,
            ]);

            // Crear entre 1 y 5 productos vendidos para esta venta
            $productosEnVenta = $productos->random(rand(1, 5));
            $totalVenta = 0;

            foreach ($productosEnVenta as $producto) {
                $cantidad = rand(1, 3);
                $precioUnitario = $producto->precio;
                $subtotal = $precioUnitario * $cantidad;

                VentaProducto::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'total' => $subtotal,
                ]);

                $totalVenta += $subtotal;
            }

            // Actualizar total venta
            $venta->total = $totalVenta;
            $venta->save();
        }
    }
}
