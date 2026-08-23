<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UmkmMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isUmkm()) {
            abort(403, 'Akses ditolak. Hanya UMKM yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
