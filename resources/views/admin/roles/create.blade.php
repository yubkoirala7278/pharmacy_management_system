@extends('admin.layouts.master')

@section('content')
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header border-0 rounded-top-3 py-3" style="background-color: #5156BE;">
                <h3 class="card-title mb-0 fw-semibold text-white">Create New Role</h3>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold" style="color: #5156BE;">Role
                            Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required placeholder="Enter Role..">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="color: #5156BE;">Permissions</label>
                        <div class="row">
                            @foreach ($permissions as $group => $groupPermissions)
                                <div class="col-md-3 mb-3">
                                    <div class="card shadow-sm border-0 rounded-3">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="card-title mb-0 fw-semibold" style="color: #5156BE;">
                                                {{ ucfirst($group) }}</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            @foreach ($groupPermissions as $permission)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-medium"
                            style="background-color: #5156BE; border-color: #5156BE;">Submit</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary fw-medium"
                            style="background-color: #6e707e; border-color: #6e707e;">Cancel</a>
                    </div>
                </form>
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

        .form-control {
            border-radius: 0.3rem;
            font-size: 0.875rem;
        }

        .form-check-input:checked {
            background-color: #5156BE;
            border-color: #5156BE;
        }

        .form-check-label {
            font-size: 0.85rem;
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
