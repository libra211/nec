@extends('admin.layouts.app', ['title' => 'Staff Management'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Staff Management</h2>
@if($can('staff.create'))
    <a href="{{ route('admin.staff.create') }}" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
        <i class="fas fa-plus me-1"></i> Add Staff Member
    </a>
    @endif
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
<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('role') || request()->filled('state') || request()->filled('status'))
                <a href="{{ route('admin.staff.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.staff.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, email, or employee ID..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Role</label>
                <select name="role" class="form-select" style="border-radius:8px;">
                    <option value="">All Roles</option>
                    @foreach(['registration_officer','polling_officer','data_entry','state_coordinator','constituency_officer'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">State</label>
                <select name="state" class="form-select" style="border-radius:8px;">
                    <option value="">All States</option>
                    @foreach($states ?? [] as $s)
                        <option value="{{ $s }}" {{ request('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Staff Table --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover" id="staffTable" style="margin-bottom:0;">
            <thead style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;">
                <tr>
                    <th style="padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Email</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Phone</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Role</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Employee ID</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Department</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">State</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Last Active</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($staff)
                    @forelse($staff as $member)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#475569;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#1e293b;"><strong>{{ $member->name }}</strong></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $member->email }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $member->phone ?? '-' }}</td>
                        <td style="padding:10px 12px;">
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
                        <td style="padding:10px 12px;color:#475569;"><code>{{ $member->employee_id ?? '-' }}</code></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $member->department ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $member->state ?? '-' }}</td>
                        <td style="padding:10px 12px;">
                            @if($member->is_active ?? true)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td style="padding:10px 12px;color:#64748b;">{{ $member->last_login_at ? $member->last_login_at->diffForHumans() : 'Never' }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                            <a href="{{ route('admin.staff.show', $member->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                            @if($can('staff.update'))
                            <a href="{{ route('admin.staff.edit', $member->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:{{ ($member->is_active ?? true) ? 'rgba(239,68,68,0.08)' : 'rgba(46,139,87,0.08)' }};color:{{ ($member->is_active ?? true) ? '#ef4444' : '#2E8B57' }};border:none;" title="Toggle Status" onclick="toggleStaffStatus('{{ route('admin.staff.status', $member->id) }}', {{ ($member->is_active ?? true) ? 'false' : 'true' }})"><i class="fas fa-{{ ($member->is_active ?? true) ? 'ban' : 'check' }}"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">
                            <div class="text-center py-5">
                                <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                    <i class="fas fa-users" style="font-size:1.25rem;color:#94a3b8;"></i>
                                </div>
                                <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No staff found</p>
                                <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                @endisset
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @isset($staff)
            @if($staff->total() > 0)
                <span style="font-size:0.75rem;color:#64748b;">Showing {{ $staff->firstItem() }} to {{ $staff->lastItem() }} of {{ $staff->total() }} results</span>
            @endif
            {{ $staff->withQueryString()->links() }}
        @endisset
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
