@extends('admin.layouts.app', ['title' => 'Manage Candidates'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-users text-primary me-2"></i>Candidates Management</h2>
        <p class="text-muted mb-0 small">Manage election candidates and their party affiliations</p>
    </div>
    <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Candidate</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Candidates</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-flag"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['parties'] }}</div><div class="stat-label">Parties Represented</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim gold">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $candidates->total() }}</div><div class="stat-label">Total (Filtered)</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--nec-green)"></i>Candidates</h6>
        <form method="GET" class="d-flex gap-2 align-items-end">
            <div class="input-group input-group-sm" style="width:200px;">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search..." value="{{ request('search') }}" style="font-size:13px;">
            </div>
            <select name="party_id" class="form-select form-select-sm" style="width:auto;font-size:13px;" onchange="this.form.submit()">
                <option value="">All Parties</option>
                @foreach($parties as $party)
                <option value="{{ $party->id }}" {{ request('party_id') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width:auto;font-size:13px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['active','inactive','trash'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @if(request('search') || request('party_id') || request('status'))
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-semibold" style="width:40px;">#</th>
                        <th class="small fw-semibold" style="width:50px;"></th>
                        <th class="small fw-semibold">Name</th>
                        <th class="small fw-semibold">Party</th>
                        <th class="small fw-semibold">Position</th>
                        <th class="small fw-semibold">Constituency</th>
                        <th class="small fw-semibold text-center">Status</th>
                        <th class="small fw-semibold text-end" style="width:auto;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $item)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                        <td>
                            <div style="width:36px;height:36px;border-radius:10px;overflow:hidden;border:1px solid #eee;display:flex;align-items:center;justify-content:center;background:#fafafa;">
                                <img src="{{ asset($item->photo ? 'storage/' . $item->photo : 'assets/images/default-avatar.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->name }}</span>
                        </td>
                        <td>
                            @if($item->politicalParty)
                            <span class="badge" style="background:{{ $item->politicalParty->color ?? '#6c757d' }}20;color:{{ $item->politicalParty->color ?? '#6c757d' }};border:1px solid {{ $item->politicalParty->color ?? '#6c757d' }}30;font-size:0.7rem;">
                                @if($item->politicalParty->color)
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $item->politicalParty->color }};display:inline-block;margin-right:3px;"></span>
                                @endif
                                {{ $item->politicalParty->acronym ?? $item->politicalParty->name }}
                            </span>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="small">{{ $item->position }}</td>
                        <td class="small">{{ $item->constituency ?? '—' }}</td>
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
                            <a href="{{ route('admin.candidates.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.candidates.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.candidates.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-user-slash text-muted" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="text-muted mt-2 mb-0">No candidates found</p>
                            <a href="{{ route('admin.candidates.create') }}" class="btn btn-sm btn-primary mt-2">Add Your First Candidate</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($candidates->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $candidates->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection