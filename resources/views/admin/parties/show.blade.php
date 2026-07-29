@extends('admin.layouts.app')
@section('title', $party->name . ' - Party Details')
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
.party-logo-lg { width: 80px; height: 80px; border-radius: 16px; object-fit: cover; border: 2px solid #e2e8f0; }
.candidate-avatar { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
.animate-in { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-flag text-primary me-2"></i>{{ $party->name }}</h2>
        <p class="text-muted mb-0 small">Detailed information about this political party</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back to Parties</a>
        <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-primary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-edit me-1"></i> Edit Party</a>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN: Party Details --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d1">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-info-circle" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">General Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-label">Party Name</div>
                        <div class="info-value">{{ $party->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Acronym</div>
                        <div class="info-value">{{ $party->acronym ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Leader / Chairperson</div>
                        <div class="info-value">{{ $party->leader ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Year Founded</div>
                        <div class="info-value">{{ $party->founded ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Party Color</div>
                        <div class="info-value d-flex align-items-center gap-2">
                            @if($party->color)
                            <span style="width:20px;height:20px;border-radius:50%;background:{{ $party->color }};border:1px solid #ddd;display:inline-block;"></span>
                            <span>{{ $party->color }}</span>
                            @else
                            —
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge bg-{{ $party->status ? 'success' : 'warning' }}" style="font-size:0.75rem;border-radius:6px;">
                                <i class="fas fa-{{ $party->status ? 'check-circle' : 'minus-circle' }} me-1"></i>
                                {{ $party->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Created</div>
                        <div class="info-value">{{ $party->created_at ? $party->created_at->format('M d, Y') : '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ $party->updated_at ? $party->updated_at->format('M d, Y') : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Candidates --}}
        <div class="card border-0 shadow-sm detail-card animate-in animate-in-d2">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-users" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Candidates ({{ $party->candidates_count }})</h6>
            </div>
            <div class="card-body p-0">
                @if($candidates->count())
                <div class="list-group list-group-flush">
                    @foreach($candidates as $candidate)
                    <div class="list-group-item d-flex align-items-center gap-3 px-4 py-3">
                        <img src="{{ asset($candidate->photo ?? 'assets/images/default-avatar.png') }}" alt="" class="candidate-avatar">
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $candidate->name }}</div>
                            <div class="text-muted" style="font-size:0.8rem;">{{ $candidate->position ?? '—' }}</div>
                        </div>
                        <span class="badge bg-{{ $candidate->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($candidate->status) }}</span>
                    </div>
                    @endforeach
                </div>
                @if($party->candidates_count > 10)
                <div class="text-center py-3 border-top">
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">View All {{ $party->candidates_count }} Candidates</a>
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-user-slash text-muted" style="font-size:2rem;opacity:0.3;"></i>
                    <p class="text-muted mt-2 mb-0 small">No candidates associated with this party.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Logo & Documents --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm detail-card mb-4 animate-in animate-in-d2">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(255,193,7,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:#f59e0b;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Party Logo</h6>
            </div>
            <div class="card-body p-4 text-center">
                @if($party->logo)
                <img src="{{ asset('storage/' . $party->logo) }}" alt="{{ $party->name }} logo" class="party-logo-lg mb-2">
                @else
                <div style="width:80px;height:80px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto;" class="mb-2">
                    <i class="fas fa-flag text-muted" style="font-size:1.5rem;opacity:0.4;"></i>
                </div>
                @endif
                <div class="small text-muted">{{ $party->name }}</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm detail-card animate-in animate-in-d3">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-file-alt" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Registration Document</h6>
            </div>
            <div class="card-body p-4 text-center">
                @if($party->registration_document)
                <i class="fas fa-file-pdf text-danger" style="font-size:2.5rem;opacity:0.7;"></i>
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $party->registration_document) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius:8px;"><i class="fas fa-download me-1"></i> View Document</a>
                </div>
                @else
                <i class="fas fa-file-alt text-muted" style="font-size:2.5rem;opacity:0.3;"></i>
                <p class="text-muted mt-2 mb-0 small">No registration document uploaded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
