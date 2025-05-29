<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    public function login()
    {
        return inertia('Admin/Login');
    }

    public function storeLogin(Request $request)
    {
        $validaciones = $request->validate([
            'email' => 'required|email|exists:administrador,email',
            'password' => 'required',
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El correo debe tener un formato válido.',
            'email.exists' => 'No se encontró un usuario con este correo electrónico.',
            'password.required' => 'El campo de la contraseña es obligatorio.',
        ]);

        if (Auth::guard('admins')->attempt($validaciones)) {
            $request->session()->regenerate();
            return redirect()->route('admin.inicio');
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admins')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

