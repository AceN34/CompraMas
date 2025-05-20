<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable {
    use Notifiable;

    protected $table = 'cliente'; // Asegúrate de que esté en singular

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
