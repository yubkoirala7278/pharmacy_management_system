
<div class="container">
    <h2>Create New Tenant</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('tenants.store') }}" method="POST">
        @csrf

        <!-- Tenant Information -->
        <h4>Tenant Information</h4>
        <div class="mb-3">
            <label>Tenant Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tenant Subdomain</label>
            <input type="text" name="subdomain" class="form-control" placeholder="e.g., tenant1" required>
        </div>

        <hr>

        <!-- Tenant Admin Information -->
        <h4>Tenant Admin Information</h4>
        <div class="mb-3">
            <label>Admin Name</label>
            <input type="text" name="admin_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Admin Email</label>
            <input type="email" name="admin_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Admin Password</label>
            <input type="password" name="admin_password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Create Tenant</button>
    </form>
</div>
