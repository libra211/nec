@extends('admin.layouts.app', ['title' => 'Batch ' . $batch->batch_number])

@section('content')
@php $approved = $batch->applications->count(); @endphp
<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h3 mb-0"><i class="fas fa-layer-group text-primary me-2"></i>{{ $batch->batch_number }}</h1>
    <a href="{{ route('admin.observers.batches') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batches</a>
</div>
<p class="text-muted small mb-3">{{ $batch->label }} &middot; Created {{ $batch->created_at->format('M j, Y g:i A') }}</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($batch->notes)
    <div class="alert alert-light border"><i class="fas fa-note-sticky me-2 text-muted"></i>{{ $batch->notes }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-primary mb-1">{{ $approved }}</h3>
                <p class="text-muted mb-0 small">Applications</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-success mb-1">{{ $batch->applications->where('accreditation_number', '!=', null)->count() }}</h3>
                <p class="text-muted mb-0 small">Accredited</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-info mb-1">{{ $batch->applications->where('accreditation_number', null)->count() }}</h3>
                <p class="text-muted mb-0 small">Awaiting Generation</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center h-100">
            <div class="card-body py-3">
                <h3 class="text-danger mb-1">{{ $batch->applications->where('revoked_at', '!=', null)->count() }}</h3>
                <p class="text-muted mb-0 small">Revoked</p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mb-3">
    @if($can('observers.review'))
    <form method="POST" action="{{ route('admin.observers.batches.generate', $batch->id) }}" onsubmit="return confirm('Generate accreditation numbers for all non-revoked observers in this batch?');">
        @csrf
        <button class="btn btn-success"><i class="fas fa-magic me-1"></i> Generate Accreditations</button>
    </form>
    <form method="POST" action="{{ route('admin.observers.badge-print') }}">
        @csrf
        <input type="hidden" name="ids" value="{{ $batch->applications->where('accreditation_number', '!=', null)->where('revoked_at', null)->pluck('id')->implode(',') }}">
        <button class="btn btn-outline-success"><i class="fas fa-print me-1"></i> Print All Badges</button>
    </form>
    @endif
</div>

@if($pending->count() > 0 && $can('observers.review'))
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-user-plus me-2 text-muted"></i>Add Approved Observers</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.observers.batches.assign', $batch->id) }}" class="row g-3 align-items-center">
            @csrf
            <div class="col-md-10">
                <select name="application_ids[]" class="form-select" multiple required size="3">
                    @foreach($pending as $p)
                        <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->application_reference }})</option>
                    @endforeach
                </select>
                <div class="form-text">Hold Ctrl/Cmd to select multiple.</div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Add</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-users me-2 text-muted"></i>Batch Members</h5></div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ref</th>
                    <th>Observer</th>
                    <th>Category</th>
                    <th>Accreditation No.</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batch->applications as $app)
                <tr>
                    <td>{{ $app->application_reference }}</td>
                    <td>
                        <div class="fw-semibold">{{ $app->full_name }}</div>
                        <div class="small text-muted">{{ $app->email }}</div>
                    </td>
                    <td><span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst($app->form_type) }}</span></td>
                    <td>
                        @if($app->accreditation_number)
                            <span class="text-uppercase fw-semibold" style="color:#166534;">{{ $app->accreditation_number }}</span>
                        @else
                            <span class="text-muted">Pending generation</span>
                        @endif
                    </td>
                    <td>
                        @if($app->revoked_at)
                            <span class="badge bg-danger">Revoked</span>
                        @elseif($app->accreditation_number)
                            <span class="badge bg-success">Accredited</span>
                        @else
                            <span class="badge bg-primary">Approved</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.observers.applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        @if($app->accreditation_number && !$app->revoked_at)
                            <a href="{{ route('admin.observers.applications.badge', $app->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-id-card"></i></a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No observers in this batch yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection