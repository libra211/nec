@extends('admin.layouts.app')
@section('title', 'Observer Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-user-tie text-primary me-2"></i>Observer Details</h1>
    <a href="{{ route('admin.observers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Personal Information</h5></div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ asset($observer->photo ?? 'assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-3" width="64" height="64">
                    <div>
                        <h4 class="mb-0">{{ e($observer->title ?? '') }} {{ e($observer->other_names ?? '') }} {{ e($observer->last_name) }}</h4>
                        <span class="badge bg-{{ $observer->status === 'accredited' ? 'success' : ($observer->status === 'pending' ? 'warning' : ($observer->status === 'verified' ? 'info' : 'danger')) }}">{{ ucfirst($observer->status) }}</span>
                    </div>
                </div>
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:180px">Email</td><td>{{ e($observer->email) }}</td></tr>
                    <tr><td class="text-muted">Phone</td><td>{{ e($observer->phone ?? 'N/A') }}</td></tr>
                    <tr><td class="text-muted">Gender</td><td>{{ ucfirst(e($observer->gender ?? 'N/A')) }}</td></tr>
                    <tr><td class="text-muted">National ID</td><td>{{ e($observer->national_id ?? 'N/A') }}</td></tr>
                    <tr><td class="text-muted">Nationality</td><td>{{ e($observer->nationality ?? 'N/A') }}</td></tr>
                    <tr><td class="text-muted">Residential Address</td><td>{{ e($observer->residential_address ?? 'N/A') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0">Assignment Details</h5></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td class="text-muted">Organisation</td><td>{{ e($observer->organisation_name ?? 'N/A') }}</td></tr>
                    <tr><td class="text-muted">Observer Type</td><td><span class="badge bg-info">{{ e($observer->observer_type ?? 'N/A') }}</span></td></tr>
                    <tr><td class="text-muted">Category</td><td>{{ e($observer->category ?? 'N/A') }}</td></tr>
                    <tr><td class="text-muted">Applied</td><td>{{ $observer->created_at->format('d M Y') }}</td></tr>
                </table>

                @if($observer->admin_notes)
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>Admin Notes:</strong>
                        <p class="mb-0 mt-1">{{ e($observer->admin_notes) }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h5 class="mb-0"><i class="fas fa-sync-alt me-2"></i>Update Status</h5></div>
    <div class="card-body">
        <form action="{{ route('admin.observers.status', $observer->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending','verified','accredited','rejected'] as $s)
                            <option value="{{ $s }}" {{ $observer->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Notes</label>
                    <input type="text" name="admin_notes" class="form-control" value="{{ $observer->admin_notes }}" placeholder="Optional notes...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
