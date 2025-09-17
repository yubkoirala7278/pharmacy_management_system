@extends('admin.layouts.master')

@section('content')
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header border-0 rounded-top-3 py-3" style="background-color: #5156BE;">
                <h3 class="card-title mb-0 fw-semibold text-white">Edit User</h3>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold" style="color: #5156BE;">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" required placeholder="Enter Name...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold" style="color: #5156BE;">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required placeholder="Enter Email...">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold" style="color: #5156BE;">Password (Leave blank
                            to keep unchanged)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Enter New Password...">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold" style="color: #5156BE;">Confirm
                            Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Confirm New Password...">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="color: #5156BE;">Roles</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0 rounded-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="card-title mb-0 fw-semibold" style="color: #5156BE;">Available Roles</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @foreach ($roles as $role)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="roles[]"
                                                    value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                    {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-medium"
                            style="background-color: #5156BE; border-color: #5156BE;">Update</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary fw-medium"
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
