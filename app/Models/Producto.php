<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;

class Producto extends Model
{
    public function index()
    {
        $productos = Producto::all();
        return Inertia::render('Productos', [
            'productos' => $productos
        ]);
    }
    public function ventas() {
        return $this->belongsToMany(Venta::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
