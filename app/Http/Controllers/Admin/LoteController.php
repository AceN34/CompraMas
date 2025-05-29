<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoteController extends Controller {
    public function index() {
        $lotes = Lote::with('producto')->get();
        $lotesPorCaducar = [];

        foreach ($lotes as $lote) {
            $fechaCaducidad = Carbon::parse($lote->fecha_caducidad);

            // Si está caducado, cambiar estado y descontar cantidad del producto
            if ($fechaCaducidad->isPast() && $lote->estado !== 'Caducado') {
                $lote->estado = 'Caducado';
                $lote->save();

                // Descontar la cantidad del lote al producto
                $producto = $lote->producto;
                if ($producto) {
                    // Evitar cantidad negativa
                    $producto->cantidad = max(0, $producto->cantidad - $lote->cantidad);
                    $producto->save();
                }
            }

            // Si caduca en los próximos 7 días y no está caducado
            if ($fechaCaducidad->isBetween(now(), now()->addDays(7)) && $lote->estado !== 'Caducado') {
                $lotesPorCaducar[] = $lote->codigo . ' (' . $lote->producto->nombre . ')';
            }
        }

        if (!empty($lotesPorCaducar)) {
            session()->flash('warning', "¡Atención! Los siguientes lotes caducan pronto: \n" . implode(",\n", $lotesPorCaducar));
        }

        return Inertia::render('Admin/Lotes', [
            'lotes' => $lotes

        ]);
    }


    public function create() {
        $productos = Producto::with('lotes')->get()->map(function ($producto) {
            $cantidadLotes = $producto->lotes->sum('cantidad');
            $producto->disponible = $producto->cantidad - $cantidadLotes;
            return $producto;
        });

        return Inertia::render('Admin/NuevoLote', [
            'productos' => $productos,
        ]);
    }


    public function store(Request $request) {
        $request->validate([
            'codigo' => 'required|string|unique:lote,codigo',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'required|date|after:today|before:2100-01-01|date_format:Y-m-d',
        ], [
            'codigo.required' => 'Debes ingresar un código para el lote.',
            'codigo.unique' => 'Este código ya está en uso.',
            'codigo.max' => 'El código no debe exceder los 50 caracteres.',
            'producto_id.required' => 'Debes seleccionar un producto.',
            'producto_id.exists' => 'El producto no existe.',
            'cantidad.required' => 'Debes ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'fecha_caducidad.required' => 'Debes ingresar una fecha de caducidad.',
            'fecha_caducidad.date' => 'La fecha no es válida.',
            'fecha_caducidad.after' => 'La fecha no puede ser anterior a hoy.',
            'fecha_caducidad.date_format' => 'La fecha debe tener el formato YYYY-MM-DD.',
            'fecha_caducidad.before' => 'La fecha es demasiado lejana.',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verifica si hay suficiente cantidad disponible
        $cantidadAsignada = $producto->lotes()
            ->where('estado', 'Consumible')
            ->sum('cantidad');
        $cantidadDisponible = $producto->cantidad - $cantidadAsignada;

        if ($request->cantidad > $cantidadDisponible) {
            return back()->withErrors([
                'cantidad' => 'Cantidad excede la disponible en stock.'
            ])->withInput();
        }

        Lote::create([
            'codigo' => $request->codigo,
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'fecha_caducidad' => $request->fecha_caducidad,
            'estado' => 'Consumible'
        ]);

        return redirect()->route('admin.lotes.index')->with('success', 'Lote guardado correctamente.');
    }


    public function edit($codigo) {
        $lote = Lote::with('producto')->where('codigo', $codigo)->firstOrFail();

        $productos = Producto::with('lotes')->get();

        return Inertia::render('Admin/EditarLote', [
            'lote' => $lote,
            'productos' => $productos,
        ]);
    }


    public function update(Request $request, $codigo) {
        $lote = Lote::where('codigo', $codigo)->firstOrFail();

        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'required|date|before:2100-01-01|date_format:Y-m-d',
        ], [
            'cantidad.required' => 'Debes ingresar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'fecha_caducidad.required' => 'Debes ingresar una fecha de caducidad.',
            'fecha_caducidad.date' => 'La fecha no es válida.',
            'fecha_caducidad.date_format' => 'La fecha debe tener el formato DD/MM/YYYY.',
            'fecha_caducidad.before' => 'La fecha es demasiado lejana.',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Calcula la cantidad total de los lotes, sin incluir el que se esta modificando
        $cantidadAsignada = $producto->lotes()
            ->where('estado', 'Consumible')
            ->where('codigo', '!=', $lote->codigo)
            ->sum('cantidad');
        $cantidadDisponible = $producto->cantidad - $cantidadAsignada;

        if ($request->cantidad > $cantidadDisponible) {
            return back()->withErrors([
                'cantidad' => 'Cantidad excede la disponible en stock.'
            ])->withInput();
        }

        $estadoAnterior = $lote->estado;
        $nuevoEstado = Carbon::parse($request->fecha_caducidad)->isPast() ? 'Caducado' : 'Consumible';

        $lote->update([
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'fecha_caducidad' => $request->fecha_caducidad,
            'estado' => $nuevoEstado,
        ]);

        // Si el estado cambia de Consumible a Caducado, descontar cantidad del producto y viceversa
        if ($estadoAnterior !== $nuevoEstado) {
            if ($estadoAnterior === 'Consumible' && $nuevoEstado === 'Caducado') {
                // Se resta la cantidad del producto
                $producto->cantidad = max(0, $producto->cantidad - $request->cantidad);
            } else if ($estadoAnterior === 'Caducado' && $nuevoEstado === 'Consumible') {
                // Se añade la cantidad al producto
                $producto->cantidad = $producto->cantidad + $request->cantidad;
            }
            $producto->save();
        }

        return redirect()->route('admin.lotes.index')->with('success', 'Lote actualizado correctamente.');
    }


    public function destroy($codigo) {
        Lote::destroy($codigo);
        return back();
    }

}
