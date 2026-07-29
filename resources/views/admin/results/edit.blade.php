@extends('admin.layouts.app')
@section('title', 'Edit Result')
@section('extra_css')
<style>
:root { --card-radius: 16px; --input-radius: 10px; --transition: all 0.25s cubic-bezier(0.4,0,0.2,1); }
.form-control, .form-select { border-radius: var(--input-radius); padding: 0.65rem 1rem; font-size: 0.9rem; border: 1.5px solid #e2e8f0; transition: var(--transition); }
.form-control:focus, .form-select:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.12); }
.animate-in { animation: fadeUp 0.35s ease both; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in-d1 { animation-delay: 0.05s; }
.animate-in-d2 { animation-delay: 0.1s; }
.animate-in-d3 { animation-delay: 0.15s; }
.form-label { font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; color: #334155; }
.sticky-bar { position: sticky; bottom: 0; z-index: 1020; background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); border-top: 1px solid #e2e8f0; padding: 0.9rem 0; margin-top: 2rem; }
</style>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-edit text-primary me-2"></i>Edit Election Result</h2>
        <p class="text-muted mb-0 small">Update election result details</p>
    </div>
    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.5rem 1.2rem;"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-in" style="border-radius:12px;">
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <strong>Please fix the errors below</strong>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<form action="{{ route('admin.results.update', $result->id) }}" method="POST" id="resultForm">
@csrf
@method('PUT')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d1" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-info-circle" style="color:var(--nec-green);font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Result Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Election Name <span class="text-danger">*</span></label>
                        <input type="text" name="election_name" class="form-control @error('election_name') is-invalid @enderror" value="{{ old('election_name', $result->election_name) }}" required maxlength="255">
                        @error('election_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['active','inactive','trash'] as $s)
                            <option value="{{ $s }}" {{ old('status', $result->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Election Type <span class="text-danger">*</span></label>
                        <select name="election_type" class="form-select @error('election_type') is-invalid @enderror" required>
                            <option value="">-- Select Type --</option>
                            @foreach(['Presidential','Parliamentary','State Assembly','Gubernatorial','Local Government','By-election','Referendum'] as $t)
                            <option value="{{ $t }}" {{ old('election_type', $result->election_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('election_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Constituency</label>
                        <select name="constituency_id" class="form-select @error('constituency_id') is-invalid @enderror">
                            <option value="">-- Select Constituency --</option>
                            @foreach($constituencies as $c)
                            <option value="{{ $c->id }}" {{ old('constituency_id', $result->constituency_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('constituency_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-chart-bar" style="color:#2563eb;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Vote Statistics</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">Registered Voters</label>
                        <input type="number" name="registered_voters" class="form-control @error('registered_voters') is-invalid @enderror" value="{{ old('registered_voters', $result->registered_voters) }}" min="0">
                        @error('registered_voters') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Votes Cast</label>
                        <input type="number" name="total_votes" class="form-control @error('total_votes') is-invalid @enderror" value="{{ old('total_votes', $result->total_votes) }}" min="0">
                        @error('total_votes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Turnout (%)</label>
                        <input type="number" name="turnout" class="form-control @error('turnout') is-invalid @enderror" value="{{ old('turnout', $result->turnout) }}" min="0" max="100" step="0.01">
                        @error('turnout') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm animate-in animate-in-d2" style="border-radius:var(--card-radius);">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2" style="border-radius:var(--card-radius) var(--card-radius) 0 0;padding:1rem 1.5rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas fa-calendar" style="color:#10b981;font-size:0.85rem;"></i></div>
                <h6 class="mb-0 fw-bold">Timeline</h6>
            </div>
            <div class="card-body p-4">
                <div class="info-label" style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#94a3b8;margin-bottom:0.25rem;">Created</div>
                <div class="info-value mb-3" style="font-size:0.95rem;font-weight:500;color:#1e293b;">{{ $result->created_at ? $result->created_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
                <div class="info-label" style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#94a3b8;margin-bottom:0.25rem;">Last Updated</div>
                <div class="info-value" style="font-size:0.95rem;font-weight:500;color:#1e293b;">{{ $result->updated_at ? $result->updated_at->format('M d, Y \\a\\t g:i A') : '—' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="sticky-bar">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted" id="unsavedIndicator"><i class="fas fa-info-circle text-muted me-1"></i> Update result details as needed</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary" style="border-radius:10px;padding:0.55rem 1.5rem;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="border-radius:10px;padding:0.55rem 1.5rem;font-weight:600;" id="saveBtn"><i class="fas fa-save me-1"></i> Update Result</button>
        </div>
    </div>
</div>
</form>
@endsection

@section('extra_scripts')
<script>
function calcTurnout() {
    var votes = parseInt(document.querySelector('[name=total_votes]').value) || 0;
    var reg = parseInt(document.querySelector('[name=registered_voters]').value) || 0;
    var turnoutField = document.querySelector('[name=turnout]');
    if (votes > 0 && reg > 0) {
        turnoutField.value = ((votes / reg) * 100).toFixed(2);
    } else {
        turnoutField.value = '';
    }
}
document.querySelector('[name=total_votes]').addEventListener('input', calcTurnout);
document.querySelector('[name=registered_voters]').addEventListener('input', calcTurnout);
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); document.getElementById('saveBtn').click(); }
});
</script>
@endsection