@extends('admin.layouts.app')
@section('title', 'Staff Activity - ' . $staff->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-history text-primary me-2"></i>Activity: {{ $staff->name }}</h1>
    <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">DATE/TIME</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">ACTION</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">ENTITY TYPE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">DETAILS</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $activity->created_at->format('M d, Y H:i') }}</td>
                        <td style="padding:10px 12px;color:#475569;"><span class="badge bg-info">{{ ucfirst($activity->action) }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $activity->entity_type ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ Str::limit($activity->details ?? '-', 60) }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;color:#475569;"><code>{{ $activity->ip_address ?? '-' }}</code></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(13,110,253,0.08);">
                                    <i class="fas fa-history" style="color:#0d6efd;font-size:1.25rem;"></i>
                                </div>
                                <p class="text-muted mb-0">No activity recorded for this staff member.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($activities->hasPages() || $activities->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} activities</span>
        {{ $activities->links() }}
    </div>
    @endif
</div>
@endsection
