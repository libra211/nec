@extends('admin.layouts.app')
@section('title', 'Complaints & Reports')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Complaints & Reports</h1>
    <div class="d-flex gap-2">
        <span class="badge bg-danger fs-6">{{ $counts['new'] }} New</span>
        <span class="badge bg-warning text-dark fs-6">{{ $counts['in_progress'] }} In Progress</span>
    </div>
</div>

<div class="row g-3 mb-4">
    @php $statusColors = ['new'=>'danger','open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary','escalated'=>'dark']; @endphp
    @foreach($statusColors as $s => $color)
        @php $isActive = request('status') === $s; @endphp
        <div class="col">
            @if($s === 'new')
                <a href="{{ route('admin.complaints.index') }}" class="text-decoration-none">
            @else
                <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}" class="text-decoration-none">
            @endif
                <div class="card {{ $isActive ? 'border-'.$color.' border-2' : '' }} shadow-sm text-center py-3">
                    <div class="fw-bold">{{ $counts[$s] ?? 0 }}</div>
                    <small class="text-muted text-capitalize">{{ str_replace('_', ' ', $s) }}</small>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name, subject..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['new','open','in_progress','resolved','closed','escalated'] as $s)
                        <option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach(['registration','voter_card','polling_station','results','observer','staff','other'] as $c)
                        <option {{ request('category')===$c?'selected':'' }} value="{{ $c }}">{{ ucwords(str_replace('_',' ',$c)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select">
                    <option value="">All Priority</option>
                    @foreach(['low','medium','high','urgent'] as $p)
                        <option {{ request('priority')===$p?'selected':'' }} value="{{ $p }}">{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Name</th><th>Category</th><th>Subject</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @php
                        $priorityColors = ['low'=>'success','medium'=>'warning','high'=>'danger','urgent'=>'dark'];
                        $complaintStatusColors = ['new'=>'danger','open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary','escalated'=>'dark'];
                    @endphp
                    @forelse($complaints as $c)
                        <tr>
                            <td><strong>{{ $c->id }}</strong></td>
                            <td>{{ $c->full_name }}<br><small class="text-muted">{{ $c->phone ?? $c->email ?? '' }}</small></td>
                            <td><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_',' ',$c->category)) }}</span></td>
                            <td>{{ Str::limit($c->subject, 40) }}</td>
                            <td>
                                <span class="badge bg-{{ $priorityColors[$c->priority] ?? 'secondary' }}">{{ ucfirst($c->priority) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $complaintStatusColors[$c->status] ?? 'secondary' }}">{{ ucwords(str_replace('_',' ',$c->status)) }}</span>
                            </td>
                            <td><small>{{ $c->created_at ? $c->created_at->format('d M Y') : '' }}</small></td>
                            <td style="white-space:nowrap;">
                                <a href="{{ route('admin.complaints.show', $c) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No complaints found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $complaints->links() }}
    </div>
</div>
@endsection
