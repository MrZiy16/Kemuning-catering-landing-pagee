<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIsAdminOrSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki peran admin atau super_admin
        if (!in_array($request->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Akses ditolak. Hanya untuk Admin dan Super Admin.');
        }

        return $next($request);
    }
}