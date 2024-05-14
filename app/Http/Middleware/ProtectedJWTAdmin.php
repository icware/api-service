<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProtectedJWTAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $payload = JWTAuth::parseToken()->getPayload();
            $data = $payload->get('data');
            $user = $data->get('user');

            // Verifique se o usuário está ativo
            if (!$user->active) {
                return response()->json(['error' => 'Usuário inativo'], 401);
            }

            // Verifique se o usuário é super, se sim, passe
            if (!$user->super) {
                return response()->json(['error' => 'Usuário não é administrador'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Sessão expirada'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Usuário não válido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        return $next($request);
    }
}