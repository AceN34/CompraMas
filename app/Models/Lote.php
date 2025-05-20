<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lote';
    protected $fillable = ['codigo', 'producto_id', 'cantidad', 'fecha_caducidad', 'estado'];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
