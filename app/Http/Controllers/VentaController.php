<?php

namespace App\Http\Controllers;

use App\Models\VentaProducto;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VentaController extends Controller {
    public function mostrarFormulario() {
        $cliente = Auth::guard('cliente')->user();

        if (!$cliente) { // Si no estas logueado
            return redirect()->route('cliente.login')->with('error', 'Primero debes iniciar sesión.');
        }

        $carrito = Carrito::with('producto')->where('cliente_id', $cliente->id)->get();

        if ($carrito->isEmpty()) { // Si no tienes productos en el carrito
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = $carrito->sum(fn($item) => $item->producto->precio * $item->cantidad);

        // Obtener los datos de envío de la sesión si existen (se modifican), sino serán null
        $envio = session('envio', null);

        return inertia('Cliente/DetallesEnvio', [
            'envio' => $envio,
            'carrito' => $carrito,
            'subtotal' => $subtotal,
        ]);
    }

    public function confirmar(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigoPostal' => 'required|numeric|digits:5',
            'telefono' => 'required|numeric|digits:9',
            'comentarios' => 'nullable|string|max:500',
        ], [
            'required' => 'Este campo es obligatorio.',
            'max' => 'Máximo :max caracteres.',
            'numeric' => 'El :attribute debe ser un número.',
            'digits' => 'El :attribute debe tener :digits dígitos.',
        ]);

        session([
            'envio' => $request->only(['nombre', 'direccion', 'ciudad', 'codigoPostal', 'telefono', 'comentarios']),
        ]);

        // Simulación: redirigir a una pasarela de pago ficticia
        return redirect()->route('venta.pago')->with('success', 'Datos validados, puede proceder al pago.');
    }

    public function pago() {
        $cliente = Auth::guard('cliente')->user();
        $carrito = $cliente->carrito()->with('producto')->get();

        if ($carrito->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $datosEnvio = session('envio');
        if (!$datosEnvio) {
            return redirect()->route('venta.formulario')->with('error', 'Debes completar los datos de envío.');
        }

        $subtotal = $carrito->sum(fn($item) => $item->producto->precio * $item->cantidad);

        // Si ya hay una venta pendiente en sesión
        if (session()->has('venta_id')) {
            $venta = Venta::with('productos')->find(session('venta_id'));

            // Si hay diferencias en el carrito actual y los productos en la venta se actualiza
            $ventaProductos = $venta->productos->mapWithKeys(function ($producto) {
                return [$producto->id => $producto->pivot->cantidad];
            });

            $productosCarrito = $carrito->mapWithKeys(function ($item) {
                return [$item->producto_id => $item->cantidad];
            });

            if ($ventaProductos != $productosCarrito) {
                // Se actualizan los productos en venta_producto
                $venta->productos()->detach();

                foreach ($carrito as $item) {
                    $venta->productos()->attach($item->producto_id, [
                        'cantidad' => $item->cantidad,
                        'precio_unitario' => $item->producto->precio,
                        'total' => $item->producto->precio * $item->cantidad,
                    ]);
                }

                $venta->total = $subtotal;
                $venta->save();
            }
        } else {
            // Se crea la venta si no existe
            $venta = new Venta();
            $venta->cliente_id = $cliente->id;
            $venta->nombre = $datosEnvio['nombre'];
            $venta->direccion = $datosEnvio['direccion'];
            $venta->ciudad = $datosEnvio['ciudad'];
            $venta->codigo_postal = $datosEnvio['codigoPostal'];
            $venta->telefono = $datosEnvio['telefono'];
            $venta->comentarios = $datosEnvio['comentarios'] ?? null;
            $venta->total = $subtotal;
            $venta->estado = 'Pendiente';
            $venta->save();

            // Se asocian los productos
            foreach ($carrito as $item) {
                $venta->productos()->attach($item->producto_id, [
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->producto->precio,
                    'total' => $item->producto->precio * $item->cantidad,
                ]);
            }

            session()->put('venta_id', $venta->id);
        }

        return Inertia::render('Cliente/Pago', [
            'carrito' => $carrito,
            'subtotal' => $subtotal,
            'envio' => $datosEnvio,
        ]);
    }

    public function procesarPago(Request $request)
    {
        $orderId = $request->query('orderId');
        $paypal = new PayPalService();
        $respuesta  = $paypal->capturarOrden($orderId);

        if ($respuesta->result->status !== 'COMPLETED') {
            return redirect()->route('carrito.index')->with('error', 'Pago no válido, vuelve a intentarlo de nuevo.');
        }

        $cliente = Auth::guard('cliente')->user();
        $carrito = $cliente->carrito()->with('producto')->get();
        $datosEnvio = session('envio');

        if (!$datosEnvio || $carrito->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Faltan datos para completar la venta.');
        }

        DB::beginTransaction();

        try {
            $total = $carrito->sum(function ($item) {
                return $item->cantidad * $item->producto->precio;
            });

            // Crear venta
            $venta = new Venta();
            $venta->cliente_id = $cliente->id;
            $venta->nombre = $datosEnvio['nombre'];
            $venta->direccion = $datosEnvio['direccion'];
            $venta->ciudad = $datosEnvio['ciudad'];
            $venta->codigo_postal = $datosEnvio['codigoPostal'];
            $venta->telefono = $datosEnvio['telefono'];
            $venta->comentarios = $datosEnvio['comentarios'] ?? null;
            $venta->total = $total;
            $venta->estado = 'Pagado';
            $venta->save();

            // Guardar productos y actualizar cantidades
            foreach ($carrito as $item) {
                $producto = $item->producto;
                $cantidadVendida = $item->cantidad;

                // Verificar stock total en producto
                if ($producto->cantidad < $cantidadVendida) {
                    throw new \Exception("Stock insuficiente para el producto {$producto->nombre}.");
                }

                // Descontar stock total del producto
                $producto->cantidad -= $cantidadVendida;
                $producto->save();

                // Descontar de lotes consumibles
                $cantidadRestante = $cantidadVendida;

                $lotesConsumibles = $producto->lotes()
                    ->where('estado', 'consumible')
                    ->whereDate('fecha_caducidad', '>=', now())
                    ->orderBy('fecha_caducidad', 'asc')
                    ->get();

                foreach ($lotesConsumibles as $lote) {
                    // Si ya no queda cantidad por quitar de la venta, se sale
                    if ($cantidadRestante <= 0) break;
                    //Se resta la cantidad que hay en los lotes, hasta que haya 0 o ya no se necesiten mas
                    if ($lote->cantidad >= $cantidadRestante) {
                        $lote->cantidad -= $cantidadRestante;
                        $cantidadRestante = 0;
                    } else {
                        $cantidadRestante -= $lote->cantidad;
                        $lote->cantidad = 0;
                    }
                    // Si ya no quedan disponibles en el lote se borra el lote
                    if ($lote->cantidad <= 0) {
                        $lote->delete();
                    } else {
                        $lote->save();
                    }
                }

                // Guardar la venta-producto
                VentaProducto::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidadVendida,
                    'precio_unitario' => $producto->precio,
                    'total' => $cantidadVendida * $producto->precio,
                ]);
            }

            // Vaciar carrito
            $cliente->carrito()->delete();

            DB::commit();

            session()->forget('envio');
            session()->forget('venta_id');
            session()->put('compra_realizada', true);

            return redirect()->route('venta.gracias')->with('success', '¡Pago procesado correctamente! Gracias por tu compra.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar la venta: ' . $e->getMessage());
            return redirect()->route('carrito.index')->with('error', 'Error al guardar la venta: ' . $e->getMessage());
        }
    }


    public function cancelado() {
        return redirect()->route('carrito.index')->with('error', 'El pago fue cancelado.');
    }

    public function error() {
        return redirect()->route('carrito.index')->with('error', 'Ocurrió un error durante el proceso de pago.');
    }

    public function gracias() {
        if (!session()->pull('compra_realizada')) {
            return redirect()->route('carrito.index')->with('error', 'No tienes acceso a esa página.');
        }

        return Inertia::render('Cliente/VentaGracias');
    }
}
