<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService
{
    public function getAllUsers($request)
    {
        return User::with('roles')
            ->when($request->has('search') && !empty($request->search), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('order_by') && !empty($request->order_by), function ($query) use ($request) {
                $query->orderBy($request->order_by, $request->order_direction ?? 'asc');
            })
            ->get();
    }

    public function createUser($data)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            $user = User::on('mysql')->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (!empty($data['roles'])) {
                $roles = Role::on('mysql')->whereIn('id', $data['roles'])->get();
                $user->syncRoles($roles);
            }

            DB::connection('mysql')->commit();
            return $user;
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();
            throw $e;
        }
    }

    public function updateUser($user, $data)
    {
        DB::connection('mysql')->beginTransaction();

        try {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (!empty($data['roles'])) {
                $roles = Role::on('mysql')->whereIn('id', $data['roles'])->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }

            DB::connection('mysql')->commit();
            return $user;
        } catch (Exception $e) {
            DB::connection('mysql')->rollBack();
            throw $e;
        }
    }

    public function deleteUser($user)
    {
        return $user->delete();
    }

    public function getAllRoles()
    {
        return Role::all();
    }
}
