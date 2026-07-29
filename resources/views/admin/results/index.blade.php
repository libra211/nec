@extends('admin.layouts.app', ['title' => 'Manage Results'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-poll text-primary me-2"></i>Election Results</h2>
        <p class="text-muted mb-0 small">Manage election results and vote counts</p>
    </div>
    <a href="{{ route('admin.results.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Result</a>
</div>

<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-poll"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Results</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-vote-yea"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total_votes']) }}</div><div class="stat-label">Total Votes Cast</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim purple">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total_registered'] ?? 0) }}</div><div class="stat-label">Registered Voters</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim gold">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['avg_turnout'] ? number_format($stats['avg_turnout'], 1) . '%' : '—' }}</div><div class="stat-label">Avg Turnout</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--nec-green)"></i>Results</h6>
        <form method="GET" class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width:200px;">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search..." value="{{ request('search') }}" style="font-size:13px;">
            </div>
            <select name="status" class="form-select form-select-sm" style="width:auto;font-size:13px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['active','inactive','trash'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-semibold" style="width:40px;">#</th>
                        <th class="small fw-semibold">Election</th>
                        <th class="small fw-semibold">Type</th>
                        <th class="small fw-semibold">Constituency</th>
                        <th class="small fw-semibold text-end">Registered</th>
                        <th class="small fw-semibold text-end">Votes</th>
                        <th class="small fw-semibold text-end">Turnout</th>
                        <th class="small fw-semibold text-center">Status</th>
                        <th class="small fw-semibold text-end" style="width:auto;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $item)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration + ($results->currentPage() - 1) * $results->perPage() }}</td>
                        <td><span class="fw-semibold">{{ $item->election_name }}</span></td>
                        <td><span class="badge bg-info" style="font-size:0.65rem;">{{ $item->election_type }}</span></td>
                        <td class="small">{{ $item->constituency->name ?? '—' }}</td>
                        <td class="text-end small">{{ number_format($item->registered_voters ?? 0) }}</td>
                        <td class="text-end small">{{ number_format($item->total_votes ?? 0) }}</td>
                        <td class="text-end small">{{ $item->turnout ? number_format($item->turnout, 1) . '%' : '—' }}</td>
                        <td class="text-center">
                            @if($item->status === 'active')
                                <span class="badge bg-success" style="font-size:0.65rem;">Active</span>
                            @elseif($item->status === 'inactive')
                                <span class="badge bg-warning text-dark" style="font-size:0.65rem;">Inactive</span>
                            @else
                                <span class="badge bg-danger" style="font-size:0.65rem;">Trash</span>
                            @endif
                        </td>
                        <td class="text-end" style="white-space:nowrap;">
                            <a href="{{ route('admin.results.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.results.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.results.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-poll text-muted" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="text-muted mt-2 mb-0">No results found</p>
                            <a href="{{ route('admin.results.create') }}" class="btn btn-sm btn-primary mt-2">Add Your First Result</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($results->hasPages())
    <div class="card-footer bg-white border-top">{{ $results->withQueryString()->links() }}</div>
    @endif
</div>
@endsection