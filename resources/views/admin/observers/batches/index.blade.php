@extends('admin.layouts.app', ['title' => 'Accreditation Batches'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h3 mb-0"><i class="fas fa-layer-group text-primary me-2"></i>Accreditation Batches</h1>
    <a href="{{ route('admin.observers.applications') }}" class="btn btn-outline-secondary"><i class="fas fa-list-check me-1"></i> Applications</a>
</div>
<p class="text-muted small mb-3">Group approved observer applications into batches, then generate official accreditation numbers and print badges.</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@php
    $available = \App\Models\ObserverApplication::whereNull('batch_id')->where('status', 'approved')->whereNull('revoked_at')->get();
    $batches = $batches ?? collect();
@endphp

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-muted"></i>Create Batch</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.observers.batches.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Batch Label</label>
                        <input type="text" name="label" class="form-control" required placeholder="e.g. Batch A - Domestic Oct 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include approved applications</label>
                        @forelse($available as $a)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="application_ids[]" value="{{ $a->id }}" id="app-{{ $a->id }}">
                                <label class="form-check-label small" for="app-{{ $a->id }}">
                                    {{ $a->full_name }} <span class="text-muted">({{ $a->application_reference }})</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No approved applications waiting to be batched.</p>
                        @endforelse
                    </div>
                    <button class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Create Batch</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-layer-group me-2 text-muted"></i>Batches</h5></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Batch</th>
                            <th>Label</th>
                            <th>Applications</th>
                            <th>Status</th>
                            <th>Generated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        <tr>
                            <td class="fw-semibold">{{ $batch->batch_number }}</td>
                            <td>{{ $batch->label }}</td>
                            <td><span class="badge bg-light text-dark">{{ $batch->applications_count }}</span></td>
                            <td>
                                @if($batch->status === 'generated')
                                    <span class="badge bg-success">Generated</span>
                                @elseif($batch->status === 'closed')
                                    <span class="badge bg-secondary">Closed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $batch->generated_at ? $batch->generated_at->format('M j, Y') : '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.observers.batches.show', $batch->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i> Open</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No batches created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection