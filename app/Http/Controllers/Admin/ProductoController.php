<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductoController extends Controller {
    public function index(Request $request) {
        $productos = Producto::query();

        // Filtra por nombre si hay búsqueda
        if ($request->filled('search')) {
            $productos->where('nombre', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Productos/Index', [
            'productos' => $productos->get(),
            'search' => $request->search,
        ]);
    }
    public function indexAdmin() {
        $productos = Producto::all();

        return Inertia::render('Admin/InicioAdmin', [
            'productos' => $productos
        ]);
    }

    public function create() {
        $categorias = Producto::select('categoria')->distinct()->pluck('categoria');

        return Inertia::render('Admin/NuevoProducto', [
            'categorias' => $categorias,
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $filename = $request->file('imagen')->getClientOriginalName();
            $request->file('imagen')->move(public_path('images'), $filename);
            $validated['imagen'] = $filename;
        }

        Producto::create($validated);

        return redirect()->route('admin.inicio');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Producto::distinct()->pluck('categoria')->toArray();

        return Inertia::render('Admin/EditarProducto', [
            'producto' => $producto,
            'categorias' => $categorias,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto = Producto::findOrFail($id);

        if ($request->hasFile('imagen')) {
            $filename = $request->file('imagen')->getClientOriginalName();
            $request->file('imagen')->move(public_path('images'), $filename);
            $validated['imagen'] = $filename;
        }

        $producto->update($validated);

        return redirect()->route('admin.inicio');
    }

    public function destroy($id) {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('admin.inicio');
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);

        return Inertia::render('Productos/ProductoDetalle', [
            'producto' => $producto
        ]);
    }
}
