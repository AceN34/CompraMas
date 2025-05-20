<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::with('producto')->get();
        return Inertia::render('Admin/Lotes', ['lote' => $lotes]);
    }


    public function create()
    {
        $productos = Producto::with('lotes')->get()->map(function ($producto) {
            $cantidadLotes = $producto->lotes->sum('cantidad');
            $producto->disponible = $producto->cantidad - $cantidadLotes;
            return $producto;
        });

        return Inertia::render('Admin/NuevoLote', [
            'productos' => $productos,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'required|date',
        ]);

        // Verifica si hay suficiente cantidad disponible
        $producto = Producto::findOrFail($request->producto_id);
        $cantidadDisponible = $producto->cantidad - $producto->lotes()->sum('cantidad');

        if ($request->cantidad > $cantidadDisponible) {
            return back()->withErrors(['cantidad' => 'Cantidad excede la disponible en stock.']);
        }

        // Crear lote
        Lote::create([
            'producto_id' => $request->producto_id,
            'cantidad' => $request->cantidad,
            'fecha_caducidad' => $request->fecha_caducidad,
            'estado' => 'activo', // O el estado que definas por defecto
        ]);

        return redirect()->route('admin.lotes.index')->with('success', 'Lote guardado correctamente.');
    }


    public function edit($id)
    {
        return Inertia::render('Admin/EditarLote', [
            'lote' => Lote::with('producto')->findOrFail($id),
            'productos' => Producto::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $lote = Lote::findOrFail($id);

        $validated = $request->validate([
            'codigo' => 'required|string|unique:lotes,codigo,' . $id,
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_caducidad' => 'nullable|date',
        ]);

        $lote->update($validated);
        return redirect()->route('admin.lotes.index');
    }

    public function destroy($id)
    {
        Lote::destroy($id);
        return back();
    }

}
