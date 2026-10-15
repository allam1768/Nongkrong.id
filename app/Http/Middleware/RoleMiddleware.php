<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and ensure the authenticated user has one of the required roles.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('portal');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Akses tidak diizinkan untuk peran ini.');
        }

        return $next($request);
    }
}
