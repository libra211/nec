@extends('admin.layouts.app')
@section('title', 'Staff Member Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-user text-primary me-2"></i>{{ $staff->name }}</h1>
    <div>
        <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Edit</a>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Profile</h5></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:140px;">Email</td><td>{{ $staff->email }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ $staff->phone ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Employee ID</td><td>{{ $staff->employee_id ?? 'N/A' }}</td></tr>
                    <tr>
                        <td class="text-muted">Role</td>
                        <td><span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $staff->role)) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($staff->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($staff->status == 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($staff->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Department</td><td>{{ $staff->department ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">State</td><td>{{ $staff->state ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Created</td><td>{{ $staff->created_at->format('M d, Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Quick Actions</h5></div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('admin.staff.activity', $staff) }}" class="btn btn-outline-primary"><i class="fas fa-history me-1"></i> View Activity Log</a>
                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-1"></i> Edit Profile</a>
                @if($staff->status == 'active')
                    <form method="POST" action="{{ route('admin.staff.status', $staff) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="inactive">
                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-ban me-1"></i> Deactivate</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.staff.status', $staff) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-outline-success w-100"><i class="fas fa-check me-1"></i> Activate</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
