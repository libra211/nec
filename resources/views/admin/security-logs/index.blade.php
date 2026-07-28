@extends('admin.layouts.app')
@section('title', 'Security Logs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-shield-alt text-danger me-2"></i>Security Logs</h1>
    @if(in_array($adminRole ?? '', ['super_admin', 'admin']))
        <form method="POST" action="{{ route('admin.security-logs.clear') }}" onsubmit="return confirm('Clear all security logs? This cannot be undone.')">
            @csrf
            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt me-1"></i> Clear All</button>
        </form>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Search email, IP, URI, details..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="event_type" class="form-select"><option value="">All Events</option>
                    @foreach($eventTypes as $et)<option {{ request('event_type')===$et?'selected':'' }} value="{{ $et }}">{{ $et }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To"></div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.security-logs.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle table-sm">
                <thead class="table-dark">
                    <tr><th>Time</th><th>Event</th><th>User</th><th>IP</th><th>URI</th><th>Details</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td><small class="text-nowrap">{{ $log->created_at ? $log->created_at->format('d M H:i:s') : '-' }}</small></td>
                            <td>
                                @php $evColors=['login'=>'success','logout'=>'secondary','failed_login'=>'danger','password_change'=>'warning','permission_denied'=>'danger','suspicious_request'=>'danger']; @endphp
                                <span class="badge bg-{{ $evColors[$log->event_type] ?? 'info' }}">{{ $log->event_type }}</span>
                            </td>
                            <td><small>{{ $log->user_email ?? '-' }}</small></td>
                            <td><small class="text-muted">{{ $log->ip_address ?? '-' }}</small></td>
                            <td><small class="text-muted text-truncate d-inline-block" style="max-width:200px;">{{ $log->request_uri ?? '-' }}</small></td>
                            <td><small class="text-truncate d-inline-block" style="max-width:200px;">{{ $log->details ?? '-' }}</small></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.security-logs.show', $log) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <form method="POST" action="{{ route('admin.security-logs.destroy', $log) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-shield-alt fa-2x mb-2 d-block"></i>No security logs found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
