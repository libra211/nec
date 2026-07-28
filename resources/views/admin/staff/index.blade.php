@extends('admin.layouts.app', ['title' => 'Staff Management'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Staff Management</h2>
    <a href="{{ route('admin.staff.create') }}" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
        <i class="fas fa-plus me-1"></i> Add Staff
    </a>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 style="color:var(--nec-blue)">{{ $stats['total'] ?? 0 }}</h3>
                <small class="text-muted">Total Staff</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $stats['active'] ?? 0 }}</h3>
                <small class="text-muted">Active</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $stats['registration_officers'] ?? 0 }}</h3>
                <small class="text-muted">Registration Officers</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="text-info">{{ $stats['polling_officers'] ?? 0 }}</h3>
                <small class="text-muted">Polling Officers</small>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, email, or employee ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach(['registration_officer','polling_officer','data_entry','state_coordinator','constituency_officer'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">State</label>
                <select name="state" class="form-select">
                    <option value="">All States</option>
                    @foreach($states ?? [] as $s)
                        <option value="{{ $s }}" {{ request('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Staff Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="staffTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Employee ID</th>
                        <th>Department</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th width="130">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($staff)
                        @foreach($staff as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ e($member->name) }}</strong></td>
                            <td>{{ e($member->email) }}</td>
                            <td>{{ e($member->phone ?? '-') }}</td>
                            <td>
                                @php
                                    $roleBadges = [
                                        'state_coordinator' => 'success',
                                        'constituency_officer' => 'info',
                                        'registration_officer' => 'warning',
                                        'polling_officer' => 'secondary',
                                        'data_entry' => 'light'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $roleBadges[$member->role] ?? 'secondary' }}">{{ ucwords(str_replace('_', ' ', $member->role)) }}</span>
                            </td>
                            <td><code>{{ e($member->employee_id ?? '-') }}</code></td>
                            <td>{{ e($member->department ?? '-') }}</td>
                            <td>{{ e($member->state ?? '-') }}</td>
                            <td>
                                @if($member->is_active ?? true)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $member->last_login_at ? $member->last_login_at->diffForHumans() : 'Never' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.staff.show', $member->id) }}" class="btn btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.staff.edit', $member->id) }}" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-{{ ($member->is_active ?? true) ? 'warning' : 'success' }}" title="Toggle Status" onclick="toggleStaffStatus('{{ route('admin.staff.status', $member->id) }}', {{ ($member->is_active ?? true) ? 'false' : 'true' }})"><i class="fas fa-{{ ($member->is_active ?? true) ? 'ban' : 'check' }}"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            @isset($staff)
                {{ $staff->withQueryString()->links() }}
            @endisset
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    $('#staffTable').DataTable({ responsive: true, order: [[1, 'asc']] });

    window.toggleStaffStatus = function (url, activate) {
        Swal.fire({
            title: activate ? 'Activate staff member?' : 'Deactivate staff member?',
            icon: 'question', showCancelButton: true,
            confirmButtonColor: '#2E8B57', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirm'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'PATCH', data: { is_active: activate ? 1 : 0 },
                    success: function () { Swal.fire('Done!', 'Status updated.', 'success').then(function () { location.reload(); }); }
                });
            }
        });
    };
});
</script>
@endsection
