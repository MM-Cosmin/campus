<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModulePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $module  // Este parámetro ya no se usará.
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module)
    {
        // Permite todas las solicitudes sin ninguna verificación.
        return $next($request);
    }
}
