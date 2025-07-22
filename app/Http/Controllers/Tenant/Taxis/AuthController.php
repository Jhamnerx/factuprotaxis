<?php

namespace App\Http\Controllers\Tenant\Taxis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\Conductor;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('taxis::auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:propietario,conductor'
        ]);

        $credentials = $request->only('email', 'password');
        $userType = $request->input('user_type');

        // Intentar autenticación según el tipo de usuario
        $user = null;
        $guard = null;

        if ($userType === 'propietario') {
            $user = Propietarios::where('email', $credentials['email'])->first();
            $guard = 'propietarios';
        } elseif ($userType === 'conductor') {
            $user = Conductor::where('email', $credentials['email'])->first();
            $guard = 'conductores';
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Verificar que el usuario esté habilitado
            if (!$user->enabled) {
                return back()->withErrors([
                    'email' => 'Su cuenta está deshabilitada.'
                ]);
            }

            // Iniciar sesión
            Auth::guard($guard)->login($user, $request->filled('remember'));

            // Redirigir al dashboard
            return redirect()->intended(route('taxis.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Cerrar sesión en ambos guards
        Auth::guard('propietarios')->logout();
        Auth::guard('conductores')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('taxis.login');
    }

    /**
     * Mostrar el dashboard según el tipo de usuario
     */
    public function dashboard()
    {
        if (Auth::guard('propietarios')->check()) {
            $user = Auth::guard('propietarios')->user();
            return view('taxis::dashboard.propietario', compact('user'));
        }

        if (Auth::guard('conductores')->check()) {
            $user = Auth::guard('conductores')->user();
            return view('taxis::dashboard.conductor', compact('user'));
        }

        return redirect()->route('taxis.login');
    }

    /**
     * Obtener el usuario autenticado
     */
    public function user()
    {
        if (Auth::guard('propietarios')->check()) {
            return response()->json([
                'user' => Auth::guard('propietarios')->user(),
                'type' => 'propietario'
            ]);
        }

        if (Auth::guard('conductores')->check()) {
            return response()->json([
                'user' => Auth::guard('conductores')->user(),
                'type' => 'conductor'
            ]);
        }

        return response()->json(['user' => null], 401);
    }
}
