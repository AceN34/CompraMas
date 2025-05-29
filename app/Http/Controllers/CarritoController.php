<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\Producto;
use Inertia\Inertia;

class CarritoController extends Controller {
        public function index() {
            $cliente = Auth::guard('cliente')->user();

            $carrito = $cliente->carrito()->with('producto')->get();

            $subtotal = $carrito->sum(function ($item) {
                return $item->producto->precio * $item->cantidad;
            });

            return Inertia::render('Cliente/Carrito', [
                'carrito' => $carrito,
                'subtotal' => $subtotal,
            ]);
        }

    public function agregar(Request $request) {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        $cliente = auth('cliente')->user();

        // Comprueba si existe el producto en el carrito
        $carritoItem = $cliente->carrito()
            ->where('producto_id', $producto->id)
            ->first();

        if ($carritoItem) {
            // Si existe se aumenta la cantidad
            $carritoItem->cantidad += $request->cantidad;
            $carritoItem->save();
        } else {
            // Sino se añade el producto con su cantidad
            $cliente->carrito()->create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
            ]);
        }

        return redirect()->back()->with('success', 'El producto se ha añadido al carrito.');
    }

    public function actualizar(Request $request, $id) {
        $cliente = Auth::guard('cliente')->user();

        $item = $cliente->carrito()->findOrFail($id);
        $item->cantidad = $request->input('cantidad');
        $item->save();

        return redirect()->back()->with('success', 'Cantidad actualizada.');
    }

    public function eliminar($id) {
        $cliente = Auth::guard('cliente')->user();

        $item = $cliente->carrito()->findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        $clienteId = Auth::guard('cliente')->id();
        Carrito::where('cliente_id', $clienteId)->delete();

        return redirect()->back()->with('success', 'Carrito vaciado.');
    }
}
