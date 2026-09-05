@extends('admin.layouts.app')
@section('title', 'Security Log Details')
@section('content')
<a href="{{ route('admin.security-logs.index') }}" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Logs</a>
<h2 class="mb-4"><i class="fas fa-shield-alt text-danger me-2"></i>Security Log #{{ $log->id }}</h2>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Event Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Event Type</small>
                        @php $evColors=['login'=>'success','logout'=>'secondary','failed_login'=>'danger','password_change'=>'warning','permission_denied'=>'danger','suspicious_request'=>'danger']; @endphp
                        <span class="badge bg-{{ $evColors[$log->event_type] ?? 'info' }} fs-6">{{ $log->event_type }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Timestamp</small>
                        <span>{{ $log->created_at ? $log->created_at->format('d M Y \a\t g:i:s A') : 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">User Email</small>
                        <span>{{ $log->user_email ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">IP Address</small>
                        <span><code>{{ $log->ip_address ?? 'N/A' }}</code></span>
                    </div>
                    <div class="col-md-12">
                        <small class="text-muted d-block">Request URI</small>
                        <span><code>{{ $log->request_uri ?? 'N/A' }}</code></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Details</small>
                        <div class="p-3 bg-light rounded" style="white-space:pre-wrap;">{{ $log->details ?? 'No details' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Request Info</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">User Agent</small>
                    <small class="text-break">{{ $log->user_agent ?? 'N/A' }}</small>
                </div>
                @if($log->request_data)
                <div>
                    <small class="text-muted d-block">Request Data</small>
                    <pre class="bg-light p-2 rounded mb-0" style="font-size:0.75rem; max-height:200px; overflow:auto;">{{ $log->request_data }}</pre>
                </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Actions</h6></div>
            <div class="card-body">
                @if($can('security-logs.delete'))
                <form method="POST" action="{{ route('admin.security-logs.destroy', $log) }}" onsubmit="return confirm('Delete this log entry?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100"><i class="fas fa-trash me-1"></i> Delete Log Entry</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
