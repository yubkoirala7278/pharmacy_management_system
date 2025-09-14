<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $subdomain = $request->route('tenant');

        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();

        // Dynamically set tenant DB connection
        config([
            'database.connections.tenant.host'     => env('DB_HOST', '127.0.0.1'),
            'database.connections.tenant.port'     => env('DB_PORT', '3306'),
            'database.connections.tenant.database' => $tenant->database,
            'database.connections.tenant.username' => env('DB_USERNAME', 'root'),
            'database.connections.tenant.password' => env('DB_PASSWORD', ''),
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        return $next($request);
    }
}
