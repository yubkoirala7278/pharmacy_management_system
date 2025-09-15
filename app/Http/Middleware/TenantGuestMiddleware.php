<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantGuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.dashboard', ['tenant' => $request->route('tenant')]);
        }

        return $next($request);
    }
}
