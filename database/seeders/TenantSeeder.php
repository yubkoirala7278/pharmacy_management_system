<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Set connection to tenant DB dynamically
        $connection = config('database.default');

        // 1. Create default roles
        $roles = ['tenant_admin', 'employee', 'viewer'];

        foreach ($roles as $roleName) {
            Role::on('tenant')->create(['name' => $roleName, 'guard_name' => 'tenant']);
        }

        // 2. Create default permissions
        $permissions = [
            'manage users',
            'view reports',
            'manage products',
        ];

        foreach ($permissions as $permName) {
            Permission::on('tenant')->create(['name' => $permName, 'guard_name' => 'tenant']);
        }

        // 3. Assign permissions to roles
        $adminRole = Role::on('tenant')->where('name', 'tenant_admin')->first();
        $adminRole->givePermissionTo(Permission::on('tenant')->pluck('name')->toArray());

        $employeeRole = Role::on('tenant')->where('name', 'employee')->first();
        $employeeRole->givePermissionTo(['view reports', 'manage products']);

        $viewerRole = Role::on('tenant')->where('name', 'viewer')->first();
        $viewerRole->givePermissionTo(['view reports']);
    }
}
