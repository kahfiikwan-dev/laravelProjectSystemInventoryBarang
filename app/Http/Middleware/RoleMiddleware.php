<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // If no roles specified, just ensure authenticated
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles list
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses (' . implode('/', $roles) . ') untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
