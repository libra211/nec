@extends('admin.layouts.app')
@section('title', 'Activity Log Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Activity Log Details</h1>
    <a href="{{ route('admin.activity-logs.index', request()->query()) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0" style="font-size:0.95rem;font-weight:700;"><i class="fas fa-bars me-2" style="color:#2E8B57;"></i>Log Entry</h5>
            </div>
            <div class="card-body">
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
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">{{ ucfirst($log->action) }}</span>
                    <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                </div>

                @if($log->details)
                <div class="alert" style="background:#f8fafc;border:1px solid #e9edf2;border-radius:10px;">
                    <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:6px;">Details</div>
                    <div style="color:#1e293b;">{{ $log->details }}</div>
                </div>
                @endif

                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted" style="width:180px;font-size:0.8rem;">User Email</td>
                        <td style="font-weight:600;">{{ $log->user_email ?? '<span class="text-muted">System</span>' }}</td></tr>
                    <tr><td class="text-muted" style="width:180px;font-size:0.8rem;">Entity Type</td>
                        <td>{{ $log->entity_type ? ucfirst(str_replace('_', ' ', $log->entity_type)) : 'N/A' }}</td></tr>
                    <tr><td class="text-muted" style="width:180px;font-size:0.8rem;">Entity ID</td>
                        <td>{{ $log->entity_id ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted" style="width:180px;font-size:0.8rem;">IP Address</td>
                        <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:5px;">{{ $log->ip_address ?? 'N/A' }}</code></td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0" style="font-size:0.95rem;font-weight:700;"><i class="fas fa-link me-2" style="color:#2E8B57;"></i>Related Activity</h5>
            </div>
            <div class="card-body">
                @php
                    $related = \App\Models\ActivityLog::where('id', '!=', $log->id)
                        ->where(function ($q) use ($log) {
                            if ($log->entity_id) {
                                $q->orWhere('entity_id', $log->entity_id);
                            }
                            if ($log->user_email) {
                                $q->orWhere('user_email', $log->user_email);
                            }
                        })
                        ->latest('created_at')->limit(5)->get();
                @endphp
                @forelse($related as $rel)
                    <a href="{{ route('admin.activity-logs.show', $rel->id) }}" class="d-flex align-items-start gap-2 text-decoration-none mb-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid #eef2f6;">
                        <i class="fas fa-history mt-1" style="color:#2E8B57;font-size:0.85rem;"></i>
                        <div>
                            <div style="font-size:0.8rem;font-weight:600;color:#1e293b;">{{ ucfirst($rel->action) }}</div>
                            <div style="font-size:0.72rem;color:#64748b;">{{ $rel->created_at->format('M d, Y H:i') }}{{ $rel->user_email ? ' · ' . $rel->user_email : '' }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-muted text-center mb-0" style="font-size:0.85rem;">No related activity found.</p>
                @endforelse
                @if($can('activity-logs.delete'))
                <hr class="my-3">
                <form method="POST" action="{{ route('admin.activity-logs.destroy', $log->id) }}" onsubmit="return confirm('Delete this log entry?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-3"><i class="fas fa-trash-alt me-1"></i> Delete Entry</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection