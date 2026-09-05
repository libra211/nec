@extends('admin.layouts.app', ['title' => 'Permissions'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;">
            <i class="fas fa-key me-2" style="color: var(--nec-blue)"></i>Role Permissions
        </h2>
        <p class="text-muted mb-0">Manage what each role can access in the admin panel</p>
    </div>
    @if($can('permissions.manage'))
    <a href="{{ route('admin.permissions.sync') }}" class="btn btn-outline-primary" onclick="return confirm('This will scan all routes and create new permissions. Continue?')">
        <i class="fas fa-sync-alt me-1"></i>Sync from Routes
    </a>
    @endif
</div>

<ul class="nav nav-tabs mb-4" id="roleTabs" role="tablist">
    @foreach($roles as $i => $role)
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                data-bs-target="#tab-{{ $role['slug'] }}" type="button" role="tab">
            <span class="badge bg-{{ $role['color'] }} me-1">{{ $role['name'] }}</span>
        </button>
    </li>
    @endforeach
</ul>

<form method="POST" id="permissionForm">
    @csrf
    @method('PUT')
    <div class="tab-content" id="roleTabContent">
        @foreach($roles as $i => $role)
        <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tab-{{ $role['slug'] }}" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $role['color'] }} me-2">{{ $role['name'] }}</span>
                        Permissions
                    </h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-success check-all" data-role="{{ $role['slug'] }}">
                            <i class="fas fa-check-double me-1"></i>Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger uncheck-all" data-role="{{ $role['slug'] }}">
                            <i class="fas fa-times me-1"></i>Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($permissions as $module => $modulePerms)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="border rounded p-3">
                                <h6 class="text-uppercase text-muted mb-3" style="font-size:0.75rem; letter-spacing:1px;">
                                    <i class="fas fa-puzzle-piece me-1"></i>{{ ucfirst(str_replace('-', ' ', $module)) }}
                                </h6>
                                @foreach($modulePerms as $perm)
                                <div class="form-check mb-1">
                                    <input class="form-check-input perm-check-{{ $role['slug'] }}"
                                           type="checkbox"
                                           name="permissions[{{ $role['slug'] }}][]"
                                           value="{{ $perm->id }}"
                                           id="perm-{{ $role['slug'] }}-{{ $perm->id }}"
                                           {{ in_array($perm->slug, $rolePermissions[$role['slug']] ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm-{{ $role['slug'] }}-{{ $perm->id }}" style="font-size:0.88rem;">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" data-role="{{ $role['slug'] }}">
                        <i class="fas fa-save me-1"></i>Save {{ $role['name'] }} Permissions
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</form>
@endsection

@section('extra_scripts')
<script>
document.querySelectorAll('.check-all').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.perm-check-' + this.dataset.role).forEach(function(cb) { cb.checked = true; });
    });
});
document.querySelectorAll('.uncheck-all').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.perm-check-' + this.dataset.role).forEach(function(cb) { cb.checked = false; });
    });
});

document.querySelectorAll('.card-footer button[type="submit"]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var role = this.dataset.role;
        var form = document.getElementById('permissionForm');
        var action = '{{ url("admin/permissions") }}/' + role;
        form.action = action;
        form.method = 'POST';

        var existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) existingMethod.remove();
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        var existingRole = form.querySelector('input[name="active_role"]');
        if (existingRole) existingRole.remove();
        var roleInput = document.createElement('input');
        roleInput.type = 'hidden';
        roleInput.name = 'active_role';
        roleInput.value = role;
        form.appendChild(roleInput);

        document.querySelectorAll('.perm-check-' + role).forEach(function(cb) {
            cb.name = 'permissions[]';
        });
        document.querySelectorAll('[name^="permissions["]').forEach(function(el) {
            if (el.name !== 'permissions[]') el.disabled = true;
        });

        form.submit();
    });
});
</script>
@endsection
