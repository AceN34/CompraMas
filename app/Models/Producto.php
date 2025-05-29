<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class Producto extends Model
{
    protected $fillable = ['nombre', 'precio', 'categoria', 'cantidad', 'imagen'];
    public function ventas() {
        return $this->belongsToMany(Venta::class, 'venta_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
    public function lotes() {
        return $this->hasMany(Lote::class);
    }
}
