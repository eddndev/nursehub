<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Valida que el usuario autenticado tenga uno de los roles permitidos.
     * Uso: Route::middleware('role:admin,coordinador')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Roles permitidos (admin, coordinador, etc.)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            abort(401, 'No autenticado');
        }

        // Si no se especificaron roles, permitir acceso a cualquier usuario autenticado
        if (empty($roles)) {
            return $next($request);
        }

        // Convertir strings a UserRole enums
        $allowedRoles = array_map(fn($role) => UserRole::from($role), $roles);

        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array($request->user()->role, $allowedRoles)) {
            abort(403, 'No tienes permisos para acceder a esta página');
        }

        return $next($request);
    }
}
