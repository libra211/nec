@extends('admin.layouts.app')
@section('title', 'Activity Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-history text-primary me-2"></i>Activity Logs</h1>
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
            <div class="col-md-5">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by email, action, or details..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Action</label>
                <select name="action" class="form-select" style="border-radius:8px;">
                    <option value="">All Actions</option>
                    @foreach($uniqueActions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
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
                    <tr style="border-bottom:1px solid #f1f3f5;">
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
                        <td style="padding:10px 12px;color:#475569;">{{ $log->entity_id ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ Str::limit($log->details ?? '-', 60) }}</td>
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