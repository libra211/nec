@extends('admin.layouts.app', ['title' => 'Observer Applications'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h3 mb-0"><i class="fas fa-list-check text-primary me-2"></i>Observer Applications</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.observers.batches') }}" class="btn btn-outline-secondary"><i class="fas fa-layer-group me-1"></i> Accreditation Batches</a>
        <a href="{{ route('admin.observers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-user-tie me-1"></i> Registered Observers</a>
    </div>
</div>
<p class="text-muted small mb-3">Public observer accreditation requests submitted via the observer portal.</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-primary mb-1">{{ $stats['total'] }}</h3>
                <p class="text-muted mb-0 small">Total Applications</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-info mb-1">{{ $stats['domestic'] }}</h3>
                <p class="text-muted mb-0 small">Domestic</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-success mb-1">{{ $stats['accredited'] }}</h3>
                <p class="text-muted mb-0 small">Accredited</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-warning mb-1">{{ $stats['pending'] }}</h3>
                <p class="text-muted mb-0 small">Pending Review</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name, email, accreditation no...">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Category</label>
                <select name="form_type" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="domestic" {{ request('form_type') === 'domestic' ? 'selected' : '' }}>Domestic</option>
                    <option value="international" {{ request('form_type') === 'international' ? 'selected' : '' }}>International</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['pending','reviewing','approved','rejected'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Batch</label>
                <select name="batch" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="none" {{ request('batch') === 'none' ? 'selected' : '' }}>Not assigned</option>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}" {{ (string) request('batch') === (string) $b->id ? 'selected' : '' }}>{{ $b->batch_number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Revoked</label>
                <select name="revoked" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('revoked') === '1' ? 'selected' : '' }}>Revoked</option>
                    <option value="0" {{ request('revoked') === '0' ? 'selected' : '' }}>Active</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary btn-sm w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.observers.applications') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-rotate-left me-1"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2 text-muted"></i>Applications <span class="badge bg-light text-dark">{{ $applications->total() }}</span></h5>
            @if($can('observers.review'))
            <form method="POST" action="{{ route('admin.observers.badge-print') }}" id="bulkPrintForm">
                @csrf
                <input type="hidden" name="ids" id="bulkPrintIds" value="">
                <button type="submit" class="btn btn-success btn-sm" id="bulkPrintBtn" disabled><i class="fas fa-id-card me-1"></i> Print Badges (0)</button>
            </form>
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:36px;"><input type="checkbox" class="form-check-input" id="checkAll"></th>
                    <th>Ref</th>
                    <th>Applicant</th>
                    <th>Category</th>
                    <th>Organization</th>
                    <th>Status</th>
                    <th>Batch</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td><input type="checkbox" class="form-check-input app-check" value="{{ $app->id }}" data-accred="{{ $app->is_accredited ? 1 : 0 }}"></td>
                    <td>{{ $app->application_reference }}</td>
                    <td>
                        <div class="fw-semibold">{{ $app->full_name }}</div>
                        <div class="small text-muted">{{ $app->email }}</div>
                    </td>
                    <td><span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst($app->form_type) }}</span></td>
                    <td class="text-muted">{{ $app->organization_name ?: '—' }}</td>
                    <td>
                        @if($app->revoked_at)
                            <span class="badge bg-danger">Revoked</span>
                        @elseif($app->is_accredited)
                            <span class="badge bg-success">Accredited</span>
                        @elseif($app->status === 'approved')
                            <span class="badge bg-primary">Approved</span>
                        @elseif($app->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($app->status === 'reviewing')
                            <span class="badge bg-info">Reviewing</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($app->batch)
                            <span class="badge bg-secondary">{{ $app->batch->batch_number }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.observers.applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                        @if($app->is_accredited)
                            <a href="{{ route('admin.observers.applications.badge', $app->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-id-card"></i> Badge</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5 text-muted">No applications found matching your filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checks = document.querySelectorAll('.app-check');
    const all = document.getElementById('checkAll');
    const btn = document.getElementById('bulkPrintBtn');
    const ids = document.getElementById('bulkPrintIds');

    function refresh() {
        const selected = Array.from(checks).filter(c => c.checked);
        ids.value = selected.map(c => c.value).join(',');
        btn.disabled = selected.length === 0;
        btn.textContent = 'Print Badges (' + selected.length + ')';
        if (selected.some(c => c.dataset.accred !== '1')) {
            btn.title = 'Only accredited observers can be printed';
        } else {
            btn.title = '';
        }
    }

    checks.forEach(c => {
        c.addEventListener('change', function () {
            refresh();
            if (all) all.checked = checks.length > 0 && Array.from(checks).every(x => x.checked);
        });
    });

    if (all) {
        all.addEventListener('change', function () {
            checks.forEach(c => { c.checked = all.checked; });
            refresh();
        });
    }

    btn.addEventListener('click', function (e) {
        const selected = Array.from(checks).filter(c => c.checked);
        if (selected.some(c => c.dataset.accred !== '1')) {
            e.preventDefault();
            alert('Only already-accredited observers can be printed. Generate accreditations first.');
            return;
        }
    });
});
</script>
@endsection