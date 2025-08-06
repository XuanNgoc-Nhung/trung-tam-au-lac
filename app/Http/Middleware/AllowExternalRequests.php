<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowExternalRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cho phép request từ bên ngoài
        if ($request->is('api/thanh-toan-thanh-cong')) {
            // Bỏ qua CSRF verification cho route này
            return $next($request);
        }

        return $next($request);
    }
} 