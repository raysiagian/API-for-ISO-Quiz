<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Contoh logika: Cek apakah user aktif
        if ($request->user() && $request->user()->is_Active != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Akun Anda tidak aktif.',
            ], 403);
        }

        // Lanjutkan ke controller
        return $next($request);
    }
}