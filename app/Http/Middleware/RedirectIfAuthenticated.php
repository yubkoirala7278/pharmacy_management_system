<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request)  $next
     * @param  string[]  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Default to 'web' guard if no guards provided
        $guards = empty($guards) ? ['web'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect master/admin users to dashboard
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}
