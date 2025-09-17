<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create some permissions for master system
        $permissions = [
            'manage tenants',
            'manage master users',
            'view system logs',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm, 'guard_name' => 'web']
            );
        }

        // 2. Create super_admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        // 3. Assign all permissions to super_admin
        $superAdminRole->syncPermissions(Permission::all());

        // 4. Create super_admin user
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // change as needed
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // change password
            ]
        );

        // 5. Assign super_admin role to user
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
