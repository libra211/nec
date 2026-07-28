@extends('admin.layouts.app')
@section('title', 'Complaint #' . $complaint->id)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.complaints.index') }}" class="text-decoration-none text-muted mb-1 d-inline-block"><i class="fas fa-arrow-left me-1"></i> Back to Complaints</a>
        <h2 class="mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Complaint #{{ $complaint->id }}</h2>
    </div>
    <div class="d-flex gap-2">
        @php $sc=['new'=>'danger','open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary','escalated'=>'dark']; @endphp
        <span class="badge bg-{{ $sc[$complaint->status] ?? 'secondary' }} fs-6 text-capitalize">{{ str_replace('_', ' ', $complaint->status) }}</span>
        @php $pc=['low'=>'success','medium'=>'warning','high'=>'danger','urgent'=>'dark']; @endphp
        <span class="badge bg-{{ $pc[$complaint->priority] ?? 'secondary' }} fs-6 text-capitalize">{{ $complaint->priority }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Complaint Details</h6></div>
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $complaint->subject }}</h5>
                <div class="mb-3 p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $complaint->description }}</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Category</small>
                        <span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $complaint->category) }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Submitted</small>
                        <span>{{ $complaint->created_at?->format('d M Y \a\t g:i A') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($complaint->status === 'resolved' || $complaint->status === 'closed')
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white"><h6 class="mb-0 fw-bold"><i class="fas fa-check-circle me-1"></i> Resolution</h6></div>
                <div class="card-body">
                    <div class="mb-2">{{ $complaint->resolution ?? 'No resolution notes provided.' }}</div>
                    <small class="text-muted">Resolved by {{ $complaint->resolved_by ?? 'N/A' }} on {{ $complaint->resolved_at?->format('d M Y') ?? 'N/A' }}</small>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Submitter</h6></div>
            <div class="card-body">
                <div class="mb-2"><i class="fas fa-user me-2 text-muted"></i>{{ $complaint->full_name }}</div>
                <div class="mb-2"><i class="fas fa-phone me-2 text-muted"></i>{{ $complaint->phone ?? 'N/A' }}</div>
                <div class="mb-2"><i class="fas fa-envelope me-2 text-muted"></i>{{ $complaint->email ?? 'N/A' }}</div>
                @if($complaint->voter_identifier)
                    <div class="mb-2"><i class="fas fa-id-card me-2 text-muted"></i>Voter ID: {{ $complaint->voter_identifier }}</div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Update Status</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.complaints.status', $complaint) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['new','open','in_progress','resolved','closed','escalated'] as $s)
                                <option {{ $complaint->status === $s ? 'selected' : '' }} value="{{ $s }}">{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Resolution Notes</label>
                        <textarea name="resolution" class="form-control" rows="4" placeholder="Resolution details...">{{ $complaint->resolution }}</textarea>
                    </div>
                    <button type="submit" class="btn w-100" style="background:var(--nec-green);color:#fff;"><i class="fas fa-save me-1"></i> Update Status</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Timeline</h6></div>
            <div class="card-body">
                <div class="mb-2"><small class="text-muted">Received:</small><br>{{ $complaint->received_date?->format('d M Y') ?? $complaint->created_at?->format('d M Y') }}</div>
                @if($complaint->resolved_at)
                    <div class="mb-2"><small class="text-muted">Resolved:</small><br>{{ $complaint->resolved_at->format('d M Y') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
