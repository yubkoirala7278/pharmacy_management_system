@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Role Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role Name</label>
                                    <p class="form-control-static">{{ $role->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Permissions</label>
                                    <div>
                                        @foreach ($role->permissions as $permission)
                                            <span class="badge bg-primary me-1 mb-1">{{ $permission->name }}</span>
                                        @endforeach
                                        @if ($role->permissions->count() === 0)
                                            <span class="text-muted">No permissions assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Back</a>
                            @can('role-edit')
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">Edit</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
