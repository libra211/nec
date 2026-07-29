@extends('admin.layouts.app')
@section('title', $candidate->name . ' - Candidate Details')
@section('extra_css')
<style>
:root {
  --card-radius: 16px;
  --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.detail-card { border-radius: var(--card-radius); transition: var(--transition); }
.detail-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important; }
.info-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #94a3b8; margin-bottom: 0.25rem; }
.info-value { font-size: 0.95rem; font-weight: 500; color: #1e293b; }
.candidate-photo-lg { width: 100px; height: 100px; border-radius: 20px; object-fit: cover; border: 3px solid #e2e8f0; }
.animate-in { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
.party-badge { display:inline-flex; align-items:center; gap:0.4rem; padding:0.3rem 0.8rem; border-radius:8px; font-size:0.8rem; font-weight:500; }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-user-tie text-primary me-2"></i>{{ $candidate->name }}</h2>
        <p class="text-muted mb-0 small">Detailed information about this candidate</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.candidates.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back to Candidates</a>
        <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-primary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-edit me-1"></i> Edit Candidate</a>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d1">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-info-circle" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Personal Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $candidate->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Position</div>
                        <div class="info-value">{{ $candidate->position }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Political Party</div>
                        <div class="info-value">
                            @if($candidate->politicalParty)
                            <span class="party-badge" style="background:{{ $candidate->politicalParty->color ?? '#6c757d' }}15;color:{{ $candidate->politicalParty->color ?? '#6c757d' }};border:1px solid {{ $candidate->politicalParty->color ?? '#6c757d' }}30;">
                                @if($candidate->politicalParty->color)
                                <span style="width:10px;height:10px;border-radius:50%;background:{{ $candidate->politicalParty->color }};display:inline-block;"></span>
                                @endif
                                {{ $candidate->politicalParty->name }}
                            </span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Constituency</div>
                        <div class="info-value">{{ $candidate->constituency ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">State</div>
                        <div class="info-value">{{ $candidate->state ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge bg-{{ $candidate->status === 'active' ? 'success' : ($candidate->status === 'inactive' ? 'warning' : 'danger') }}" style="font-size:0.75rem;border-radius:6px;">
                                <i class="fas fa-{{ $candidate->status === 'active' ? 'check-circle' : ($candidate->status === 'inactive' ? 'minus-circle' : 'trash') }} me-1"></i>
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Bio</div>
                        <div class="info-value">{{ $candidate->bio ?? 'No bio provided.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d2">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-camera" style="color:#f59e0b;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Photo</h6>
            </div>
            <div class="card-body p-4 text-center">
                @if($candidate->photo)
                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}" class="candidate-photo-lg mb-2">
                @else
                <div style="width:100px;height:100px;border-radius:20px;background:linear-gradient(135deg,#e2e8f0,#cbd5e1);display:flex;align-items:center;justify-content:center;margin:0 auto;" class="mb-2">
                    <i class="fas fa-user text-muted" style="font-size:2.5rem;opacity:0.4;"></i>
                </div>
                @endif
                <div class="small text-muted">{{ $candidate->name }}</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm detail-card animate-in animate-in-d3">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-calendar" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Timeline</h6>
            </div>
            <div class="card-body p-4">
                <div class="info-label">Created</div>
                <div class="info-value mb-3">{{ $candidate->created_at ? $candidate->created_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
                <div class="info-label">Last Updated</div>
                <div class="info-value">{{ $candidate->updated_at ? $candidate->updated_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection