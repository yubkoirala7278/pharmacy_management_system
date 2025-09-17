<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\Admin\RoleService;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->roleService->getAllRoles($request);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {
                    $badges = '';
                    foreach ($row->permissions as $permission) {
                        $badges .= '<span class="badge bg-primary me-1">' . $permission->name . '</span>';
                    }
                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.roles.show', $row->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                    $btn .= ' <a href="' . route('admin.roles.edit', $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';

                    if ($row->name !== 'admin') {
                        $btn .= ' <button class="btn btn-danger btn-sm delete-role" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>';
                    }

                    return $btn;
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        $permissions = $this->roleService->getAllPermissionsGrouped();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleService->createRole($request->all());

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating role');
        }
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Super Admin role cannot be edited.');
        }

        $permissions = $this->roleService->getAllPermissionsGrouped();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be updated.');
        }

        try {
            $this->roleService->updateRole($role, $request->validated());

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating role!');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role);
            return response()->json(['success' => 'Role deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting role: ' . $e->getMessage()], 422);
        }
    }
}
