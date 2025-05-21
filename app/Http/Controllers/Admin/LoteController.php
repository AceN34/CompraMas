<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoteController extends Controller
{
    public function index() {
        $lotes = Lote::with('producto')->get();

        // Comprueba si el lote esta caducado o no, y lo actualiza en la bbdd
        foreach ($lotes as $lote) {
            if (Carbon::parse($lote->fecha_caducidad)->isPast() && $lote->estado !== 'Caducado') {
                $lote->estado = 'Caducado';
                $lote->save();
            }
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
            'fecha_caducidad' => 'required|date|after:today',
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
            'fecha_caducidad.after' => 'La fecha debe ser posterior a hoy.',
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        // Verifica si hay suficiente cantidad disponible
        $cantidadDisponible = $producto->cantidad - $producto->lotes()->sum('cantidad');

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
        return Inertia::render('Admin/EditarLote', [
            'lote' => Lote::with('producto')->findOrFail($codigo),
            'productos' => Producto::all(),
        ]);
    }

    public function update(Request $request, $codigo) {
        $lote = Lote::findOrFail($codigo);

        $validated = $request->validate([
            'codigo' => 'required|string|unique:lotes,codigo,' . $codigo,
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'nullable|date',
        ]);

        $lote->update($validated);
        return redirect()->route('admin.lotes.index');
    }

    public function destroy($codigo) {
        Lote::destroy($codigo);
        return back();
    }

}
