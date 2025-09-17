@extends('admin.layouts.master')

@section('content')
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header border-0 rounded-top-3 py-3" style="background-color: #5156BE;">
                <h3 class="card-title mb-0 fw-semibold text-white">Role Details</h3>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #5156BE;">Role Name</label>
                            <p class="form-control-static fs-6">{{ $role->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #5156BE;">Permissions</label>
                            <div>
                                @foreach ($role->permissions as $permission)
                                    <span class="badge me-1 mb-1"
                                        style="background-color: #eaecf4; color: #5156BE; font-weight: 600; font-size: 0.75rem;">{{ $permission->name }}</span>
                                @endforeach
                                @if ($role->permissions->count() === 0)
                                    <span class="text-muted">No permissions assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary fw-medium"
                        style="background-color: #6e707e; border-color: #6e707e;">Back</a>
                    <a href="{{ route('admin.roles.edit', $role->slug) }}" class="btn btn-primary fw-medium"
                        style="background-color: #5156BE; border-color: #5156BE;">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .card-shadow {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
        }

        .form-label {
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }

        .form-control-static {
            font-size: 0.875rem;
            color: #333;
        }

        .card-header {
            border-bottom: none;
        }

        .btn {
            border-radius: 0.3rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
@endsection
