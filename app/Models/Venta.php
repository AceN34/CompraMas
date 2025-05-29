<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model {
    protected $fillable = [
        'cliente_id', 'nombre', 'direccion', 'ciudad',
        'codigo_postal', 'telefono', 'comentarios',
        'total', 'estado'
    ];
    public function productos() {
        return $this->belongsToMany(Producto::class, 'venta_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function ventaProductos() {
        return $this->hasMany(VentaProducto::class);
    }
}
