<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Services\Admin\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->userService->getAllUsers($request);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    $badges = '';
                    foreach ($row->roles as $role) {
                        $badges .= '<span class="badge bg-primary me-1">' . $role->name . '</span>';
                    }
                    return $badges ?: '<span class="text-muted">No roles</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.users.show', $row->slug) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
                    $btn .= ' <a href="' . route('admin.users.edit', $row->slug) . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <button class="btn btn-danger btn-sm delete-user" data-slug="' . $row->slug . '"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = $this->userService->getAllRoles();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (Auth::user()->id == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Super Admin user cannot be edited.');
        }

        $roles = $this->userService->getAllRoles();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (Auth::user()->id == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Super Admin user cannot be updated.');
        }

        try {
            $this->userService->updateUser($user, $request->validated());

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if (Auth::user()->id == $user->id) {
            return response()->json(['error' => 'You cannot delete yourself!'], 422);
        }
        try {
            $this->userService->deleteUser($user);
            return response()->json(['success' => 'User deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting user: ' . $e->getMessage()], 422);
        }
    }
}
