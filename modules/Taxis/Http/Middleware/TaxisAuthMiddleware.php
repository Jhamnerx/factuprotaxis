<?php

namespace Modules\Taxis\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\Conductor;

class TaxisAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado en el sistema de taxis
        if (!session('taxis_authenticated')) {
            return $this->redirectToLogin($request, 'Debes iniciar sesión para acceder a esta área.');
        }

        // Verificar que la sesión tiene datos válidos del usuario
        $user = session('taxis_user');
        if (!$user || !isset($user['type']) || !isset($user['id'])) {
            // Limpiar sesión inválida
            session()->forget(['taxis_authenticated', 'taxis_user']);
            return $this->redirectToLogin($request, 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        }

        // Verificar si el usuario aún existe en la base de datos
        $userExists = $this->verifyUserExists($user);
        if (!$userExists) {
            // El usuario fue eliminado de la base de datos, cerrar sesión
            session()->forget(['taxis_authenticated', 'taxis_user']);
            session()->invalidate();
            session()->regenerateToken();

            return $this->redirectToLogin($request, 'Tu cuenta ha sido eliminada. La sesión se ha cerrado automáticamente.');
        }

        // Verificar si el usuario está habilitado
        $userEnabled = $this->verifyUserEnabled($user);
        if (!$userEnabled) {
            // El usuario fue deshabilitado, cerrar sesión
            session()->forget(['taxis_authenticated', 'taxis_user']);
            session()->invalidate();
            session()->regenerateToken();

            return $this->redirectToLogin($request, 'Tu cuenta ha sido deshabilitada. La sesión se ha cerrado automáticamente.');
        }

        return $next($request);
    }

    /**
     * Verificar si el usuario aún existe en la base de datos
     */
    private function verifyUserExists($user)
    {
        try {
            if ($user['type'] === 'propietario') {
                return Propietarios::where('id', $user['id'])->exists();
            } elseif ($user['type'] === 'conductor') {
                return Conductor::where('id', $user['id'])->exists();
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar si el usuario está habilitado
     */
    private function verifyUserEnabled($user)
    {
        try {
            if ($user['type'] === 'propietario') {
                $propietario = Propietarios::where('id', $user['id'])->first();
                return $propietario && $propietario->enabled;
            } elseif ($user['type'] === 'conductor') {
                $conductor = Conductor::where('id', $user['id'])->first();
                return $conductor && $conductor->enabled;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Redirigir al login con mensaje apropiado
     */
    private function redirectToLogin($request, $message)
    {
        // Si es una petición AJAX, devolver respuesta JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => 'No autenticado',
                'message' => $message,
                'redirect' => route('taxis.login')
            ], 401);
        }

        // Para peticiones normales, redirigir al login
        return redirect()->route('taxis.login')
            ->with('error', $message);
    }
}
