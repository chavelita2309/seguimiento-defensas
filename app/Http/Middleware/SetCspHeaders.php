<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCspHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Definimos la política básica de seguridad
        $cspHeader = "default-src 'self';";
        $cspHeader .= "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net;";
        $cspHeader .= "img-src 'self' data:;";
        $cspHeader .= "connect-src 'self' ws: wss:;";
        $cspHeader .= "frame-ancestors 'self';";

        // Si estamos en un entorno local, agregamos las fuentes inseguras necesarias para Vite
        if (app()->environment('local')) {
            $cspHeader .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173;";
            $cspHeader .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net http://localhost:5173;";
        } else {
            // En producción, solo permitimos fuentes seguras y eliminamos las palabras clave
            $cspHeader .= "script-src 'self';";
            $cspHeader .= "style-src 'self' https://fonts.googleapis.com https://fonts.bunny.net;";
        }

        $response->header('Content-Security-Policy', $cspHeader);
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-Powered-By', null);

        return $response;
    }
}
