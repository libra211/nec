@extends('admin.layouts.app', ['title' => 'Voters - ' . $agent->first_name . ' ' . $agent->last_name])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Voters Registered by {{ e($agent->first_name) }} {{ e($agent->last_name) }}</h2>
    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Agents
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-muted small mb-1">Agent Name</div>
                <div class="fw-bold">{{ e($agent->first_name) }} {{ e($agent->last_name) }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Title</div>
                <div class="fw-bold">{{ e($agent->title ?? '-') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Assigned Area</div>
                <div class="fw-bold">{{ e($agent->assigned_area ?? '-') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Total Voters Registered</div>
                <div class="fs-4 fw-bold" style="color:var(--nec-green);">{{ $voters->total() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">VOTER ID</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">FULL NAME</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">GENDER</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">PHONE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">STATE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">CONSTITUENCY</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">REGISTERED AT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voters as $voter)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $voters->firstItem() + $loop->index }}</td>
                        <td style="padding:10px 12px;color:#475569;"><code>{{ e($voter->voter_id) }}</code></td>
                        <td style="padding:10px 12px;color:#1e293b;">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-2" width="28" height="28">
                                {{ e($voter->full_name) }}
                            </div>
                        </td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($voter->gender ?? '-') }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($voter->phone ?? '-') }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($voter->state ?? '-') }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($voter->constituency ?? '-') }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;color:#64748b;">{{ $voter->registered_at ? $voter->registered_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(46,139,87,0.08);">
                                    <i class="fas fa-users" style="color:#2E8B57;font-size:1.25rem;"></i>
                                </div>
                                <p class="text-muted mb-0">No voters registered by this agent yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($voters->hasPages() || $voters->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $voters->firstItem() }} to {{ $voters->lastItem() }} of {{ $voters->total() }} voters</span>
        {{ $voters->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
