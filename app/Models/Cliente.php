<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable {
    use Notifiable;

    protected $table = 'cliente';

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function carrito() {
        return $this->hasMany(Carrito::class);
    }

    public function ventas() {
        return $this->hasMany(Venta::class);
    }
}
