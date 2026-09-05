@extends('admin.layouts.app')
@section('title', 'Security Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-shield-alt text-danger me-2"></i>Security Logs</h1>
    @if($can('security-logs.delete'))
        <form method="POST" action="{{ route('admin.security-logs.clear') }}" onsubmit="return confirm('Clear all security logs? This cannot be undone.')">
            @csrf
            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt me-1"></i> Clear All</button>
        </form>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('event_type') || request('date_from') || request('date_to'))
            <a href="{{ route('admin.security-logs.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search email, IP, URI, details..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Event Type</label>
                <select name="event_type" class="form-select" style="border-radius:8px;">
                    <option value="">All Events</option>
                    @foreach($eventTypes as $et)<option {{ request('event_type')===$et?'selected':'' }} value="{{ $et }}">{{ $et }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To" style="border-radius:8px;">
            </div>
            <div class="col-md-1">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter"></i></button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.security-logs.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Time</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Event</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">User</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">IP</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">URI</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Details</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-bottom:1px solid #f1f3f5;">
                            <td style="padding:10px 12px;color:#64748b;"><small class="text-nowrap">{{ $log->created_at ? $log->created_at->format('d M H:i:s') : '-' }}</small></td>
                            <td style="padding:10px 12px;color:#475569;">
                                @php $evColors=['login'=>'success','logout'=>'secondary','failed_login'=>'danger','password_change'=>'warning','permission_denied'=>'danger','suspicious_request'=>'danger']; @endphp
                                <span class="badge bg-{{ $evColors[$log->event_type] ?? 'info' }}">{{ $log->event_type }}</span>
                            </td>
                            <td style="padding:10px 12px;color:#475569;"><small>{{ $log->user_email ?? '-' }}</small></td>
                            <td style="padding:10px 12px;color:#475569;"><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                            <td style="padding:10px 12px;color:#475569;"><small class="text-muted text-truncate d-inline-block" style="max-width:200px;">{{ $log->request_uri ?? '-' }}</small></td>
                            <td style="padding:10px 12px;color:#475569;"><small class="text-truncate d-inline-block" style="max-width:200px;">{{ $log->details ?? '-' }}</small></td>
                            <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                                <a href="{{ route('admin.security-logs.show', $log) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                                @if($can('security-logs.delete'))
                                <form method="POST" action="{{ route('admin.security-logs.destroy', $log) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5" style="color:#64748b;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;"><i class="fas fa-shield-alt" style="font-size:1.25rem;color:#94a3b8;"></i></div>
                            <div style="font-weight:500;margin-bottom:4px;color:#1e293b;">No security logs found</div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($logs, 'hasPages') && $logs->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} logs</div>
        <div>{{ $logs->links() }}</div>
    </div>
    @endif
</div>
@endsection