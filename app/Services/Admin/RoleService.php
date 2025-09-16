<?php

namespace App\Services\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;

class RoleService
{
    public function getAllRoles($request)
    {
        return Role::with('permissions')
            ->when($request->has('search') && !empty($request->search), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('order_by') && !empty($request->order_by), function ($query) use ($request) {
                $query->orderBy($request->order_by, $request->order_direction ?? 'asc');
            })
            ->get();
    }

    public function createRole($data)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            // Always insert into master DB with guard_name
            $role = Role::on('mysql')->create([
                'name'       => $data['name'],
                'guard_name' => 'web',
            ]);

            if (!empty($data['permissions'])) {
                // Ensure syncing happens on master DB as well
                $permissions = Permission::on('mysql')->whereIn('id', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            DB::connection('mysql')->commit();
            return $role;
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();
            throw $e;
        }
    }



    public function updateRole($role, $data)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            // Always update on master DB
            $role->update([
                'name'       => $data['name'],
                'guard_name' => 'web', // keep consistent
            ]);

            if (!empty($data['permissions'])) {
                // Sync only with permissions from master DB
                $permissions = Permission::on('mysql')
                    ->whereIn('id', $data['permissions'])
                    ->get();

                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]); // clear permissions
            }

            DB::connection('mysql')->commit();
            return $role;
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();
            throw $e;
        }
    }


    public function deleteRole($role)
    {
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            throw new Exception('Cannot delete admin role.');
        }

        // Check if any users are assigned to this role
        if ($role->users()->count() > 0) {
            throw new Exception('Cannot delete role assigned to users.');
        }

        return $role->delete();
    }

    public function getAllPermissionsGrouped()
    {
        return Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0] ?? 'general';
        });
    }
}
