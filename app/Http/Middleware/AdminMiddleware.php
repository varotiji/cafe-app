<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan apakah rolenya admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Kalau bukan admin, lempar balik ke POS dengan pesan error
        return redirect()->route('pos')->with('error', 'Akses Dibatasi! Hanya Admin yang boleh masuk ke halaman tersebut.');
    }
}
