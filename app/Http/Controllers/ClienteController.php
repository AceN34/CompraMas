<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ClienteController extends Controller {
    public function register(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:cliente,email',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo debe ser un texto.',
            'email.email' => 'El correo debe tener un formato válido.',
            'email.max' => 'El correo no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed' => 'Debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'Debe incluir al menos un número.',
            'password.symbols' => 'Debe incluir al menos un símbolo.',
        ]);

        Cliente::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('status', 'Cuenta creada correctamente.');
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe tener un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::guard('cliente')->attempt($validated, $request->boolean('remember'))) {
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

    public function verLogin(Request $request) {
        $previousUrl = url()->previous();

        if (
            !$request->session()->has('url.intended') &&
            !str_contains($previousUrl, '/login') &&
            !str_contains($previousUrl, '/register')
        ) {
            $request->session()->put('url.intended', $previousUrl);
        }

        return Inertia::render('Auth/Login');
    }

    public function verRegistro() {
        return Inertia::render('Auth/Register');
    }

    public function perfil() {
        $cliente = Auth::guard('cliente')->user();

        return Inertia::render('Cliente/Perfil', [
            'cliente' => $cliente,
        ]);
    }

    public function actualizarPerfil(Request $request) {
        $cliente = Auth::guard('cliente')->user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:cliente,email,' . $cliente->id,
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe tener un formato válido.',
            'email.max' => 'El correo no puede tener más de 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
        ]);

        $cliente->update($validated);

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function cambiarContrasena(Request $request) {
        $cliente = Auth::guard('cliente')->user();

        $validated = $request->validate([
            'password_actual' => 'required',
            'password' => [
                'required', 'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'password_actual.required' => 'Debes ingresar tu contraseña actual.',

            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'Debe tener al menos 8 caracteres.',
            'password.mixed' => 'Debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'Debe incluir al menos un número.',
            'password.symbols' => 'Debe incluir al menos un símbolo.',
        ]);

        if (!Hash::check($validated['password_actual'], $cliente->password)) {
            return back()->withErrors([
                'password_actual' => 'La contraseña actual es incorrecta.',
            ]);
        }

        if (Hash::check($validated['password'], $cliente->password)) {
            return back()->withErrors([
                'password' => 'La nueva contraseña no puede ser igual a la actual.',
            ]);
        }

        $cliente->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
