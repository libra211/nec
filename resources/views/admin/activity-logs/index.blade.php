@extends('admin.layouts.app')
@section('title', 'Activity Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-history text-primary me-2"></i>Activity Logs</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by email, action, or details..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    @foreach($uniqueActions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date/Time</th>
                        <th>User Email</th>
                        <th>Action</th>
                        <th>Entity Type</th>
                        <th>Entity ID</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $log->user_email ?? 'System' }}</td>
                            <td>
                                @php
                                    $badgeClass = match(true) {
                                        str_contains($log->action, 'create') => 'bg-success',
                                        str_contains($log->action, 'update') || str_contains($log->action, 'edit') => 'bg-info',
                                        str_contains($log->action, 'delete') => 'bg-danger',
                                        str_contains($log->action, 'login') => 'bg-primary',
                                        str_contains($log->action, 'export') => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($log->action) }}</span>
                            </td>
                            <td>{{ $log->entity_type ?? '-' }}</td>
                            <td>{{ $log->entity_id ?? '-' }}</td>
                            <td>{{ Str::limit($log->details ?? '-', 60) }}</td>
                            <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No activity logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
