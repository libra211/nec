@extends('admin.layouts.app')
@section('title', 'Transfer Request Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0"><i class="fas fa-exchange-alt text-primary me-2"></i>Transfer Request Details</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="{{ route('admin.transfers.index') }}">Transfers</a></li><li class="breadcrumb-item active">Details</li></ol></nav>
    </div>
    <a href="{{ route('admin.transfers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Transfer #{{ $transfer->id }}</h5>
                @php
                    $badge = match($transfer->status) { 'approved' => 'bg-success', 'rejected' => 'bg-danger', default => 'bg-warning text-dark' };
                @endphp
                <span class="badge {{ $badge }} fs-6">{{ ucfirst($transfer->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">Full Name</strong><p>{{ $transfer->full_name }}</p></div>
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">Voter ID</strong><p><code>{{ $transfer->voter_identifier }}</code></p></div>
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">National ID</strong><p>{{ $transfer->national_id ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">Phone</strong><p>{{ $transfer->phone ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">From Constituency</strong><p class="text-danger">{{ $transfer->from_constituency ?? 'N/A' }}</p></div>
                    <div class="col-md-6"><strong class="text-muted d-block mb-1">To Constituency</strong><p class="text-success fw-bold">{{ $transfer->to_constituency ?? 'N/A' }}</p></div>
                    <div class="col-12"><strong class="text-muted d-block mb-1">Reason</strong><p>{{ $transfer->reason ?? 'No reason provided' }}</p></div>
                    @if($transfer->admin_notes)
                        <div class="col-12"><strong class="text-muted d-block mb-1">Admin Notes</strong><p>{{ $transfer->admin_notes }}</p></div>
                    @endif
                    <div class="col-md-4"><strong class="text-muted d-block mb-1">Submitted</strong><p>{{ $transfer->created_at?->format('d M Y, H:i') ?? 'N/A' }}</p></div>
                    <div class="col-md-4"><strong class="text-muted d-block mb-1">Reviewed By</strong><p>{{ $transfer->reviewed_by ?? 'Pending' }}</p></div>
                    <div class="col-md-4"><strong class="text-muted d-block mb-1">Processed Date</strong><p>{{ $transfer->processed_date?->format('d M Y, H:i') ?? 'Pending' }}</p></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold">Actions</h6></div>
            <div class="card-body d-grid gap-2">
                @if($transfer->status === 'pending')
                    <form method="POST" action="{{ route('admin.transfers.approve', $transfer) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this transfer?')"><i class="fas fa-check me-1"></i>Approve Transfer</button>
                    </form>
                    <form method="POST" action="{{ route('admin.transfers.reject', $transfer) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this transfer?')"><i class="fas fa-times me-1"></i>Reject Transfer</button>
                    </form>
                @else
                    <div class="alert alert-info mb-0"><i class="fas fa-info-circle me-1"></i>This transfer has been {{ $transfer->status }}.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
