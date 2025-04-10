<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\V1\ResponseFormatter;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class authJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Cek apakah token valid
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ResponseFormatter::error([], 'Token is invalid', 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ResponseFormatter::error([], 'Token is expired', 401);
            } else {
                return ResponseFormatter::error([], 'Authorization Token not found', 401);
            }
        }

        return $next($request);
    }
}
