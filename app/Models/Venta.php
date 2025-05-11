<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function productos() {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
