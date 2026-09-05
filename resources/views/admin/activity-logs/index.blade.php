@extends('admin.layouts.app')
@section('title', 'Activity Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-history text-primary me-2"></i>Activity Logs</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
           class="btn btn-outline-success rounded-3 px-3" title="Export filtered logs to CSV"><i class="fas fa-download me-1"></i> Export</a>
        @if($can('activity-logs.clear'))
        <form method="POST" action="{{ route('admin.activity-logs.clear') }}" onsubmit="return confirm('Clear ALL activity logs? This cannot be undone.')">
            @csrf
            <button type="submit" class="btn btn-outline-danger rounded-3 px-3" title="Delete every activity log entry"><i class="fas fa-trash-alt me-1"></i> Clear All</button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-list-alt"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label">Total Entries</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['today']) }}</div><div class="stat-label">Today</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['week']) }}</div><div class="stat-label">This Week</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim gray">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-user-friends"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['unique_users']) }}</div><div class="stat-label">Active Users</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('action'))
                <a href="{{ request()->url() }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by email, action, details, entity or IP..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Action</label>
                <select name="action" class="form-select" style="border-radius:8px;">
                    <option value="">All Actions</option>
                    @foreach($uniqueActions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Entity Type</label>
                <select name="entity_type" class="form-select" style="border-radius:8px;">
                    <option value="">All Types</option>
                    @foreach($uniqueEntities as $entity)
                        <option value="{{ $entity }}" {{ request('entity_type') == $entity ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $entity)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Entity ID</label>
                <input type="text" name="entity_id" class="form-control" placeholder="e.g. 122" value="{{ request('entity_id') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2 d-flex gap-2" style="padding-top:4px;">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}" style="border-radius:8px;">
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover" style="margin-bottom:0;">
            <thead style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;">
                <tr>
                    <th style="padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Date/Time</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">User Email</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Action</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Entity Type</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Entity ID</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Details</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr style="border-bottom:1px solid #f1f3f5;cursor:pointer;" onclick="window.location='{{ route('admin.activity-logs.show', $log->id) }}'">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $log->user_email ?? 'System' }}</td>
                        <td style="padding:10px 12px;">
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
                        <td style="padding:10px 12px;color:#475569;">{{ $log->entity_type ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;"><a href="{{ route('admin.activity-logs.index', array_merge(request()->query(), ['entity_type' => $log->entity_type, 'entity_id' => $log->entity_id])) }}" class="text-decoration-none">{{ $log->entity_id ?? '-' }}</a></td>
                        <td style="padding:10px 12px;color:#475569;">{{ Str::limit($log->details ?? '-', 80) }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;color:#475569;"><code>{{ $log->ip_address ?? '-' }}</code></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="text-center py-5">
                                <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                    <i class="fas fa-history" style="font-size:1.25rem;color:#94a3b8;"></i>
                                </div>
                                <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No activity logs found.</p>
                                <a href="{{ request()->url() }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @if($logs->total() > 0)
            <span style="font-size:0.75rem;color:#64748b;">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results</span>
        @endif
        {{ $logs->links() }}
    </div>
</div>
@endsection