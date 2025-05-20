<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:cliente,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Cliente::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('status', 'Cuenta creada correctamente.');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('cliente')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('productos.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {

        Auth::guard('cliente')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $previousUrl = url()->previous();

        // Evita redirect a /logout o /login
        if (str_contains($previousUrl, '/logout') || str_contains($previousUrl, '/login')) {
            $previousUrl = route('productos.index'); //ruta por defecto
        }

        return redirect()->to($previousUrl)->with('success', 'Has cerrado sesión correctamente.');
    }

    public function verLogin(Request $request)
    {
        $prev = url()->previous();

        // Evita guardar la URL si es la misma página de login o register
        if (!$request->session()->has('url.intended') && !str_contains($prev, '/login') && !str_contains($prev, '/register')) {
            $request->session()->put('url.intended', $prev);
        }

        return Inertia::render('Auth/Login');
    }

    public function verRegistro()
    {
        return Inertia::render('Auth/Register');
    }
}

