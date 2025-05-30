<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\Producto;
    use Inertia\Inertia;
    use Illuminate\Http\Request;

    class ProductoController extends Controller {

        public function inicio() {
            $productosMasVendidos = Producto::withCount('ventas')
                ->orderByDesc('ventas_count')
                ->take(4)
                ->get();

            $ultimosProductos = Producto::latest()->take(6)->get();

            return Inertia::render('Inicio', [
                'productosMasVendidos' => $productosMasVendidos,
                'ultimosProductos' => $ultimosProductos,
            ]);
        }

        public function index(Request $request) {
            $productos = Producto::query();

            // Filtra por nombre si hay búsqueda
            if ($request->filled('search')) {
                $productos->where('nombre', 'like', '%' . $request->search . '%');
            }

            $categoria = $request->input('categoria');

            if ($categoria) {
                $productos->where('categoria', $categoria);
            }

            return Inertia::render('Productos/Index', [
                'productos' => $productos->get(),
                'categoria' => $categoria,
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
            ], [
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'precio.required' => 'El precio es obligatorio y debe ser un número.',
                'categoria.required' => 'La categoría es obligatoria.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'imagen.mimes' => 'Solo se permiten imágenes en formato JPG, JPEG, PNG.',
                'imagen.max' => 'La imagen no debe pesar más de 2MB.',
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

        public function update(Request $request, $id) {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio' => 'required|numeric',
                'categoria' => 'required|string|max:255',
                'cantidad' => 'required|integer',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], [
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'precio.required' => 'El precio es obligatorio y debe ser un número.',
                'categoria.required' => 'La categoría es obligatoria.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'imagen.mimes' => 'Solo se permiten imágenes en formato JPG, JPEG, PNG o WEBP.',
                'imagen.max' => 'La imagen no debe pesar más de 2MB.',
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

        public function detalles($id)
        {
            $producto = Producto::findOrFail($id);

            return Inertia::render('Productos/ProductoDetalle', [
                'producto' => $producto
            ]);
        }
        public function show($id) {
            $producto = Producto::with('lotes')->findOrFail($id);

            return Inertia::render('Admin/ProductoVer', [
                'producto' => $producto,
            ]);
        }
    }
