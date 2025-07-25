<?php

namespace Modules\Taxis\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
            // Si es una petición AJAX, devolver respuesta JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'No autenticado',
                    'message' => 'Debes iniciar sesión para acceder a esta área.',
                    'redirect' => route('taxis.login')
                ], 401);
            }

            // Para peticiones normales, redirigir al login
            return redirect()->route('taxis.login')
                ->with('error', 'Debes iniciar sesión para acceder a esta área.');
        }

        // Verificar que la sesión tiene datos válidos del usuario
        $user = session('taxis_user');
        if (!$user || !isset($user['type']) || !isset($user['id'])) {
            // Limpiar sesión inválida
            session()->forget(['taxis_authenticated', 'taxis_user']);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Sesión inválida',
                    'message' => 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                    'redirect' => route('taxis.login')
                ], 401);
            }

            return redirect()->route('taxis.login')
                ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        }

        return $next($request);
    }
}
