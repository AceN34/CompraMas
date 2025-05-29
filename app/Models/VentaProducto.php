<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model {
    protected $table = 'venta_producto';
    protected $fillable = [
        'venta_id', 'producto_id', 'cantidad', 'precio_unitario', 'total'
    ];

    public function venta() {
        return $this->belongsTo(Venta::class);
    }
    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
