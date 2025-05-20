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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admins')->attempt($credentials)) {
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

