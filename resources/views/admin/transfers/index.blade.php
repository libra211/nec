@extends('admin.layouts.app')
@section('title', 'Voter Transfer Requests')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-exchange-alt text-primary me-2"></i>Voter Transfer Requests</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or voter ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Voter ID</th>
                        <th>Full Name</th>
                        <th>National ID</th>
                        <th>From Constituency</th>
                        <th>To Constituency</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td><strong>{{ $transfer->voter_identifier }}</strong></td>
                            <td>{{ $transfer->full_name }}</td>
                            <td>{{ $transfer->national_id ?? 'N/A' }}</td>
                            <td>{{ $transfer->from_constituency_id }}</td>
                            <td>{{ $transfer->to_constituency_id }}</td>
                            <td>{{ Str::limit($transfer->reason ?? '', 50) }}</td>
                            <td>
                                @if($transfer->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($transfer->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($transfer->status == 'pending')
                                    <form method="POST" action="{{ route('admin.voter-transfers.approve', $transfer) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.voter-transfers.reject', $transfer) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No transfer requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $transfers->links() }}
    </div>
</div>
@endsection
