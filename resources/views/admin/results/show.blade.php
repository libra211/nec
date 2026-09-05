@extends('admin.layouts.app')
@section('title', $result->election_name . ' - Result Details')
@section('extra_css')
<style>
:root { --card-radius: 16px; --transition: all 0.25s cubic-bezier(0.4,0,0.2,1); }
.detail-card { border-radius: var(--card-radius); transition: var(--transition); }
.detail-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important; }
.info-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #94a3b8; margin-bottom: 0.25rem; }
.info-value { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
.animate-in { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
.stat-box { text-align:center; padding:1rem; border-radius:12px; background:#f8fafc; }
.stat-box .value { font-size:1.5rem; font-weight:700; }
.stat-box .label { font-size:0.7rem; color:#94a3b8; text-transform:uppercase; letter-spacing:0.03em; }
.cand-row { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 0; border-bottom:1px solid #f1f5f9; }
.cand-row:last-child { border-bottom:0; }
.cand-bar { height:6px; border-radius:3px; flex-shrink:0; }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-poll text-primary me-2"></i>{{ $result->election_name }}</h2>
        <p class="text-muted mb-0 small">Election result details and breakdown</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back</a>
        @if($can('results.update'))
        <a href="{{ route('admin.results.edit', $result->id) }}" class="btn btn-primary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-edit me-1"></i> Edit</a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d1">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-info-circle" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Election Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-label">Election Name</div>
                        <div class="info-value">{{ $result->election_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Election Type</div>
                        <div class="info-value"><span class="badge bg-info">{{ $result->election_type }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Constituency</div>
                        <div class="info-value">{{ $result->constituency->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge bg-{{ $result->status === 'active' ? 'success' : ($result->status === 'inactive' ? 'warning' : 'danger') }}">
                                <i class="fas fa-{{ $result->status === 'active' ? 'check-circle' : ($result->status === 'inactive' ? 'minus-circle' : 'trash') }} me-1"></i>
                                {{ ucfirst($result->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-4"><div class="stat-box"><div class="value">{{ number_format($result->registered_voters ?? 0) }}</div><div class="label">Registered Voters</div></div></div>
                            <div class="col-4"><div class="stat-box"><div class="value">{{ number_format($result->total_votes ?? 0) }}</div><div class="label">Total Votes</div></div></div>
                            <div class="col-4"><div class="stat-box"><div class="value">{{ $result->turnout ? number_format($result->turnout, 1) . '%' : '—' }}</div><div class="label">Turnout</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm detail-card animate-in animate-in-d2">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-trophy" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Candidate Results ({{ $result->candidateResults->count() }})</h6>
            </div>
            <div class="card-body p-4">
                @if($result->candidateResults->count())
                    @php $maxVotes = $result->candidateResults->max('votes') ?: 1; @endphp
                    @foreach($result->candidateResults as $cr)
                    <div class="cand-row">
                        <div style="width:28px;height:28px;border-radius:8px;background:{{ $cr->party_color ?? '#e2e8f0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.7rem;font-weight:700;color:{{ $cr->party_color ? '#fff' : '#94a3b8' }};">{{ $cr->party_name ? substr($cr->party_name, 0, 2) : '?' }}</span>
                        </div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small">{{ $cr->candidate_name }}</span>
                                <span class="fw-bold small">{{ number_format($cr->votes) }} ({{ $cr->percentage ? number_format($cr->percentage, 1) . '%' : '—' }})</span>
                            </div>
                            <div style="background:#f1f5f9;border-radius:3px;height:6px;margin-top:4px;overflow:hidden;">
                                <div class="cand-bar" style="width:{{ ($cr->votes / $maxVotes) * 100 }}%;background:{{ $cr->party_color ?? '#3b82f6' }};"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar text-muted" style="font-size:2rem;opacity:0.3;"></i>
                    <p class="text-muted mt-2 mb-0 small">No candidate results recorded for this election.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d2">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-calendar" style="color:#10b981;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Timeline</h6>
            </div>
            <div class="card-body p-4">
                <div class="info-label">Created</div>
                <div class="info-value mb-3">{{ $result->created_at ? $result->created_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $result->updated_at ? $result->updated_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
                @if($result->electionEvent)
                <hr>
                <div class="info-label">Election Event</div>
                <div class="info-value">{{ $result->electionEvent->name ?? '—' }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection