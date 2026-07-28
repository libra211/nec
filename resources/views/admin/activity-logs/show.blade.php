@extends('admin.layouts.app')
@section('title', 'Activity Log Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Activity Log Details</h1>
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><td class="text-muted" style="width:180px;">Date/Time</td><td>{{ $log->created_at->format('M d, Y H:i:s') }}</td></tr>
            <tr><td class="text-muted">User Email</td><td>{{ $log->user_email ?? 'System' }}</td></tr>
            <tr><td class="text-muted">Action</td><td><span class="badge bg-primary">{{ ucfirst($log->action) }}</span></td></tr>
            <tr><td class="text-muted">Entity Type</td><td>{{ $log->entity_type ?? 'N/A' }}</td></tr>
            <tr><td class="text-muted">Entity ID</td><td>{{ $log->entity_id ?? 'N/A' }}</td></tr>
            <tr><td class="text-muted">Details</td><td>{{ $log->details ?? 'N/A' }}</td></tr>
            <tr><td class="text-muted">IP Address</td><td><code>{{ $log->ip_address ?? 'N/A' }}</code></td></tr>
        </table>
    </div>
</div>
@endsection
