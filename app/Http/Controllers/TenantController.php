<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    // Show create tenant form
    public function create()
    {
        return view('tenants.create');
    }

    // Store new tenant
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string',
            'subdomain'      => 'required|string|unique:tenants,subdomain',
            'admin_name'     => 'required|string',
            'admin_email'    => 'required|email',
            'admin_password' => 'required|string|min:6',
        ]);

        // 1. Generate unique tenant DB name
        $dbName = 'tenant_' . Str::random(10);

        // 2. Save tenant metadata in master DB
        $tenant = Tenant::create([
            'name'        => $data['name'],
            'subdomain'   => $data['subdomain'],
            'database'    => $dbName,
            'admin_email' => $data['admin_email'],
        ]);

        // 3. Create tenant database dynamically
        DB::statement("CREATE DATABASE `$dbName`");

        // 4. Configure tenant DB connection dynamically
        config(['database.connections.tenant' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => $dbName,
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
        ]]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // 5. Run tenant migrations
        Artisan::call('migrate', [
            '--path' => '/database/migrations/tenant',
            '--database' => 'tenant',
            '--force' => true,
        ]);

        // 6. Seed tenant roles and permissions
        Artisan::call('db:seed', [
            '--class' => 'TenantSeeder',
            '--database' => 'tenant',
            '--force' => true,
        ]);

        // 7. Create tenant admin user in tenant DB
        $tenantAdminId = DB::connection('tenant')->table('users')->insertGetId([
            'name'       => $data['admin_name'],
            'email'      => $data['admin_email'],
            'password'   => Hash::make($data['admin_password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Assign tenant_admin role
        $roleId = DB::connection('tenant')->table('roles')
            ->where('name', 'tenant_admin')
            ->first()->id;

        DB::connection('tenant')->table('model_has_roles')->insert([
            'role_id'   => $roleId,
            'model_type' => 'App\Models\Tenant\User',
            'model_id'  => $tenantAdminId,
        ]);

        return redirect()->route('tenants.create')
            ->with('success', 'Tenant created successfully! Tenant admin can now log in.');
    }
}
