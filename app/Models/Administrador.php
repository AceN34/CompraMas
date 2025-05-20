<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admins';

    protected $table = 'administrador';

    protected $fillable = [
        'nombre', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
