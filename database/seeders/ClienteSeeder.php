<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;

class ClienteSeeder extends Seeder {
    public function run()
    {
        Cliente::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan@email.com',
            'password' => Hash::make('juan$123'),
        ]);

        Cliente::create([
            'nombre' => 'María López',
            'email' => 'maria@email.com',
            'password' => Hash::make('maria$123'),
        ]);

        Cliente::create([
            'nombre' => 'Carlos Ruiz',
            'email' => 'carlos@email.com',
            'password' => Hash::make('carlos$123'),
        ]);
    }
}
