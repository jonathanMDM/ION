<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and must change password
        if (auth()->check() && auth()->user()->must_change_password) {
            // Exclude the force-password-change routes and logout
            $excludedRoutes = [
                'force-password-change',
                'force-password-change.update',
                'logout',
            ];

            if (!in_array($request->route()->getName(), $excludedRoutes)) {
                return redirect()->route('force-password-change');
            }
        }

        return $next($request);
    }
}
