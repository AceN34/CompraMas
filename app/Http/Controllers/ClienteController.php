<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function register(Request $request) {

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:cliente,email',
            'password' => ['required', 'confirmed',
                Password::min(8)     // Mínimo 8 caracteres
                ->mixedCase()             // mayúsculas y minúsculas
                ->numbers()               // números
                ->symbols(),              // símbolos,
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo debe ser un texto.',
            'email.email' => 'El correo debe tener un formato válido (ej: usuario@dominio.com).',
            'email.max' => 'El correo no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed' => 'La contraseña debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
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
            $redirectUrl = $request->session()->get('url.intended', route('productos.index'));
            return redirect()->intended($redirectUrl);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::guard('cliente')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $previousUrl = $request->session()->get('url.intended', route('home'));

        return redirect()->to($previousUrl)->with('success', 'Has cerrado sesión correctamente.');
    }

    public function verLogin(Request $request)
    {
        $previousUrl = url()->previous();

        // Evita redirect a /logout o /login
        if (!$request->session()->has('url.intended') && !str_contains($previousUrl, '/login') && !str_contains($previousUrl, '/register')) {
            $request->session()->put('url.intended', $previousUrl);
        }

        return Inertia::render('Auth/Login');
    }

    public function verRegistro() {
        return Inertia::render('Auth/Register');
    }

    public function perfil()
    {
        $cliente = Auth::guard('cliente')->user();
        return Inertia::render('Cliente/Perfil', ['cliente' => $cliente]);
    }

    public function editarPerfil()
    {
        $cliente = Auth::guard('cliente')->user();
        return Inertia::render('Cliente/EditarPerfil', ['cliente' => $cliente]);
    }

    public function actualizarPerfil(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cliente,email,' . $cliente->id,
        ]);

        $cliente->update($validated);

        return redirect()->route('cliente.perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function cambiarContrasenaForm()
    {
        return Inertia::render('Cliente/CambiarContrasena');
    }

    public function actualizarContrasena(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();

        $request->validate([
            'password_actual' => 'required',
            'nueva_password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        if (!Hash::check($request->password_actual, $cliente->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $cliente->update([
            'password' => Hash::make($request->nueva_password),
        ]);

        return redirect()->route('cliente.perfil')->with('success', 'Contraseña actualizada correctamente.');
    }

}

