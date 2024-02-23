<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JwtMiddleware
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
        try {
            $token = JWTAuth::parseToken()->authenticate();
            
            // Debugging: Verificar si el token se está parseando correctamente
            if (!$token) {
                return response()->json(['error' => 'No se pudo parsear el token'], 401);
            }

            // Debugging: Verificar si la autenticación tuvo éxito
            if (!$user = auth()->user()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            } else {
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }

        // Continuar con la solicitud si la autenticación es exitosa
        return $next($request);
    }
}
