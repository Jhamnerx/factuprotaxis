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
    public function showLoginForm(Request $request)
    {
        // Si ya está autenticado, redirigir al dashboard correspondiente
        if (session('taxis_authenticated')) {
            $user = session('taxis_user');
            if ($user && isset($user['type'])) {
                if ($user['type'] === 'propietario') {
                    return redirect()->route('taxis.propietario.dashboard');
                } elseif ($user['type'] === 'conductor') {
                    return redirect()->route('taxis.conductor.dashboard');
                }
            }
        }

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

        if ($userType === 'propietario') {
            $user = Propietarios::where('email', $credentials['email'])->first();
        } elseif ($userType === 'conductor') {
            $user = Conductor::where('email', $credentials['email'])->first();
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Verificar que el usuario esté habilitado
            if (!$user->enabled) {
                return back()->withErrors([
                    'email' => 'Su cuenta está deshabilitada.'
                ]);
            }

            // Guardar información del usuario en sesión
            session([
                'taxis_authenticated' => true,
                'taxis_user' => array_merge($user->toArray(), ['type' => $userType])
            ]);

            // Redirigir según el tipo de usuario
            if ($userType === 'propietario') {
                return redirect()->route('taxis.propietario.dashboard');
            } else {
                return redirect()->route('taxis.conductor.dashboard');
            }
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
        // Limpiar sesión de taxis
        session()->forget(['taxis_authenticated', 'taxis_user']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('taxis.login');
    }

    /**
     * Mostrar el dashboard según el tipo de usuario
     */
    public function dashboard()
    {
        if (!session('taxis_authenticated')) {
            return redirect()->route('taxis.login');
        }

        $user = session('taxis_user');

        if ($user['type'] === 'propietario') {
            return redirect()->route('taxis.propietario.dashboard');
        } elseif ($user['type'] === 'conductor') {
            return redirect()->route('taxis.conductor.dashboard');
        }

        return redirect()->route('taxis.login');
    }

    /**
     * Obtener el usuario autenticado
     */
    public function user()
    {
        if (session('taxis_authenticated')) {
            return response()->json([
                'user' => session('taxis_user'),
                'authenticated' => true
            ]);
        }

        return response()->json(['user' => null, 'authenticated' => false], 401);
    }
}
