<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.login', ['tenant' => $request->route('tenant')]);
        }

        return $next($request);
    }
}