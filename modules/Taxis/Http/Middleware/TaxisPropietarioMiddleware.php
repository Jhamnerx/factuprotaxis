<?php

namespace Modules\Taxis\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TaxisPropietarioMiddleware
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
            return redirect()->route('taxis.login');
        }

        // Verificar si el usuario es un propietario
        $user = session('taxis_user');
        if (!$user || $user['type'] !== 'propietario') {
            return redirect()->route('taxis.login')
                ->with('error', 'Acceso denegado. Solo propietarios pueden acceder a esta área.');
        }

        return $next($request);
    }
}
