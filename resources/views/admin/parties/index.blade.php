@extends('admin.layouts.app', ['title' => 'Manage Parties'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Political Parties Management</h2>
        <p class="text-muted mb-0 small">Manage registered political parties and their details</p>
    </div>
    <a href="{{ route('admin.parties.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Party</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-flag"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['total'] ?? $parties->total() }}</div><div class="stat-label">Total Parties</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['with_candidates'] ?? 0 }}</div><div class="stat-label">With Candidates</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim gold">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['new_this_year'] ?? 0 }}</div><div class="stat-label">Founded This Year</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--nec-green)"></i>Registered Parties</h6>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width:220px;">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search parties..." value="{{ request('search') }}" style="font-size:13px;">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.parties.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="partiesTable">
                <thead class="table-light">
                    <tr>
                        <th class="small fw-semibold" style="width:40px;">#</th>
                        <th class="small fw-semibold" style="width:50px;"></th>
                        <th class="small fw-semibold">Party</th>
                        <th class="small fw-semibold">Abbreviation</th>
                        <th class="small fw-semibold">Leader</th>
                        <th class="small fw-semibold text-center">Candidates</th>
                        <th class="small fw-semibold text-center">Status</th>
                        <th class="small fw-semibold text-end" style="width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div style="width:36px;height:36px;border-radius:8px;overflow:hidden;border:1px solid #eee;display:flex;align-items:center;justify-content:center;background:#fafafa;">
                                <img src="{{ asset($party->logo ?? 'assets/images/party-default.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($party->color)
                                <span style="width:12px;height:12px;border-radius:50%;background:{{ $party->color }};border:1px solid #ddd;display:inline-block;"></span>
                                @endif
                                <span class="fw-semibold">{{ e($party->name) }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ e($party->acronym ?? 'N/A') }}</span></td>
                        <td class="small">{{ e($party->leader ?? '—') }}</td>
                        <td class="text-center">
                            @php $candCount = $party->candidates()->count(); @endphp
                            <span class="badge bg-{{ $candCount > 0 ? 'info' : 'secondary' }}">{{ $candCount }}</span>
                        </td>
                        <td class="text-center">
                            @if($party->status === 'active')
                                <span class="badge bg-success" style="font-size:10px;">Active</span>
                            @elseif($party->status === 'inactive')
                                <span class="badge bg-warning text-dark" style="font-size:10px;">Inactive</span>
                            @else
                                <span class="badge bg-secondary" style="font-size:10px;">{{ ucfirst($party->status) }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-sm btn-outline-primary" title="Edit" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fas fa-edit" style="font-size:12px;"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="confirmDelete('{{ route('admin.parties.destroy', $party->id) }}')" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fas fa-trash" style="font-size:12px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-flag text-muted" style="font-size:2rem;opacity:0.3;"></i>
                            <p class="text-muted mt-2 mb-0">No political parties found</p>
                            <a href="{{ route('admin.parties.create') }}" class="btn btn-sm btn-nec-green mt-2">Add Your First Party</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($parties->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $parties->links() }}
    </div>
    @endif
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#partiesTable').DataTable({
            responsive: true,
            paging: false,
            info: false,
            sorting: false,
            searching: false
        });
    }
});
</script>
@endsection
