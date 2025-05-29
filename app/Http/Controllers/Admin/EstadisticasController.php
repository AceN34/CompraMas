<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\VentaProducto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstadisticasController extends Controller {
    public function ventas(Request $request) {
        $filtro = $request->input('filtro', 'dia');
        $fechaInicio = Carbon::now()->subMonth(); // Último mes

        if ($filtro === 'dia') {
            $ventas = VentaProducto::selectRaw('DATE(created_at) as fecha, producto_id, SUM(cantidad) as total_vendido')
                ->where('created_at', '>=', $fechaInicio)
                ->groupBy('fecha', 'producto_id')
                ->orderBy('fecha')
                ->get();
        } else {
            $ventas = VentaProducto::selectRaw('MONTH(created_at) as mes, YEAR(created_at) as anio, producto_id, SUM(cantidad) as total_vendido')
                ->where('created_at', '>=', $fechaInicio)
                ->groupBy('mes', 'anio', 'producto_id')
                ->orderBy('anio', 'asc')
                ->orderBy('mes', 'asc')
                ->get();
        }
        // Agrupar ventas por fecha o mes y preparar datos para el gráfico
        $datos = $ventas->groupBy(function ($venta) use ($filtro) {
            if ($filtro === 'dia') {
                return Carbon::parse($venta->fecha)->format('d-m-Y');
            } else {
                return Carbon::parse($venta->anio . '-' . $venta->mes . '-01')->format('m-Y');
            }
        });

        // Ahora, para cada grupo (fecha o mes), vamos a calcular las cantidades totales por producto
        $productosVendidos = [];

        foreach ($datos as $fechaOMes => $ventasPorFecha) {
            foreach ($ventasPorFecha as $venta) {
                $nombreProducto = $venta->producto->nombre ?? 'Producto ' . $venta->producto_id; // Por si no se carga el producto
                if (!isset($productosVendidos[$nombreProducto])) {
                    $productosVendidos[$nombreProducto] = 0;
                }
                $productosVendidos[$nombreProducto] += $venta->total_vendido;
            }
        }

        // Ordenar productos más vendidos descendente y tomar top 10
        arsort($productosVendidos);
        $productosVendidos = array_slice($productosVendidos, 0, 10, true);

        // Preparar etiquetas y cantidades para la vista
        $etiquetas = array_keys($productosVendidos);
        $cantidades = array_values($productosVendidos);

        return Inertia::render('Admin/Ventas', [
            'etiquetas' => $etiquetas,
            'cantidades' => $cantidades,
            'filtro' => $filtro
        ]);
    }
}
