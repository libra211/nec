@extends('admin.layouts.app')
@section('title', 'Voter Transfer Requests')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-exchange-alt text-primary me-2"></i>Voter Transfer Requests</h1>
    @if($can('voter-transfers.view'))
    <a href="{{ route('admin.voter-transfers.export', request()->query()) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-download me-1"></i>Export CSV</a>
    @endif
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 stat-slim primary h-100">
            <div class="card-body">
                <div class="stat-label text-muted">Total Requests</div>
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 stat-slim warning h-100">
            <div class="card-body">
                <div class="stat-label text-muted">Pending</div>
                <div class="stat-value">{{ number_format($stats['pending']) }}</div>
                @if($stats['pending'] > 0)
                <div class="stat-sub text-warning">Awaiting review</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 stat-slim success h-100">
            <div class="card-body">
                <div class="stat-label text-muted">Approved</div>
                <div class="stat-value">{{ number_format($stats['approved']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-3 stat-slim danger h-100">
            <div class="card-body">
                <div class="stat-label text-muted">Rejected</div>
                <div class="stat-value">{{ number_format($stats['rejected']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.voter-transfers.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name, voter ID, or constituency..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Voter ID</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Full Name</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">From</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">To</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Reason</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Date</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr style="border-bottom:1px solid #f1f3f5;cursor:pointer;" onclick="window.location='{{ route('admin.voter-transfers.show', $transfer) }}'">
                            <td style="padding:10px 12px;color:#1e293b;"><strong>{{ $transfer->voter_identifier }}</strong></td>
                            <td style="padding:10px 12px;color:#1e293b;">{{ $transfer->full_name }}</td>
                            <td style="padding:10px 12px;color:#475569;">
                                <div style="font-weight:600;color:#334155;">{{ $transfer->from_constituency ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $transfer->from_state ?? '' }}</div>
                            </td>
                            <td style="padding:10px 12px;color:#475569;">
                                <div style="font-weight:600;color:#334155;">{{ $transfer->to_constituency ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $transfer->to_state ?? '' }}</div>
                            </td>
                            <td style="padding:10px 12px;color:#475569;">{{ Str::limit($transfer->reason ?? '', 50) }}</td>
                            <td style="padding:10px 12px;color:#475569;">
                                @php
                                    $badge = match($transfer->status) { 'approved' => 'bg-success', 'rejected' => 'bg-danger', 'cancelled' => 'bg-secondary', default => 'bg-warning text-dark' };
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($transfer->status) }}</span>
                            </td>
                            <td style="padding:10px 12px;color:#64748b;">{{ $transfer->created_at?->format('M d, Y') }}</td>
                            <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;" onclick="event.stopPropagation()">
                                <a href="{{ route('admin.voter-transfers.show', $transfer) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#2563eb;border:none;" title="View details"><i class="fas fa-eye"></i></a>
                                @if($transfer->status == 'pending')
                                    @if($can('voter-transfers.approve'))
                                    <form method="POST" action="{{ route('admin.voter-transfers.approve', $transfer) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Approve"><i class="fas fa-check"></i></button>
                                    </form>
                                    @endif
                                    @if($can('voter-transfers.reject'))
                                    <form method="POST" action="{{ route('admin.voter-transfers.reject', $transfer) }}" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Reject"><i class="fas fa-times"></i></button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-5" style="color:#64748b;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;"><i class="fas fa-exchange-alt" style="font-size:1.25rem;color:#94a3b8;"></i></div>
                            <div style="font-weight:500;margin-bottom:4px;color:#1e293b;">No transfer requests found</div>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($transfers, 'hasPages') && $transfers->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of {{ $transfers->total() }} transfers</div>
        <div>{{ $transfers->links() }}</div>
    </div>
    @endif
</div>
@endsection