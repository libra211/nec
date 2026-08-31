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

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="color:#2E8B57;font-size:13px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Search</span>
            @if(request('search') || request('party_id') || request('status'))
                <a href="{{ route('admin.candidates.index') }}" class="text-decoration-none" style="font-size:0.75rem;color:#64748b;margin-left:auto;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <div class="input-group" style="border-radius:8px;overflow:hidden;">
                    <span class="input-group-text bg-white" style="border:1px solid #dee2e6;border-right:none;"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search candidates..." value="{{ request('search') }}" style="border:1px solid #dee2e6;border-left:none;font-size:13px;">
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Party</label>
                <select name="party_id" class="form-select" style="border-radius:8px;font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Parties</option>
                    @foreach($parties as $party)
                    <option value="{{ $party->id }}" {{ request('party_id') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;width:40px;">#</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;width:50px;"></th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Party</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Position</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Constituency</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:center;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $item)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                        <td style="padding:10px 12px;">
                            <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;border:1px solid #eee;display:flex;align-items:center;justify-content:center;background:#fafafa;">
                                <img src="{{ asset($item->photo ? 'storage/' . $item->photo : 'assets/images/default-avatar.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td style="padding:10px 12px;"><span style="font-weight:600;color:#1e293b;">{{ $item->name }}</span></td>
                        <td style="padding:10px 12px;">
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
                        <td style="padding:10px 12px;color:#475569;">{{ $item->position }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $item->constituency ?? '—' }}</td>
                        <td style="padding:10px 12px;text-align:center;">
                            @if($item->status === 'active')
                                <span class="badge bg-success" style="font-size:0.65rem;">Active</span>
                            @elseif($item->status === 'inactive')
                                <span class="badge bg-warning text-dark" style="font-size:0.65rem;">Inactive</span>
                            @else
                                <span class="badge bg-danger" style="font-size:0.65rem;">Trash</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.candidates.show', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.candidates.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.candidates.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-user-slash" style="color:#94a3b8;font-size:20px;"></i>
                            </div>
                            <p style="color:#64748b;margin-bottom:8px;font-size:0.9rem;">No candidates found</p>
                            <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary rounded-3 px-3" style="font-size:0.85rem;">Add Your First Candidate</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($candidates->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $candidates->firstItem() }} to {{ $candidates->lastItem() }} of {{ $candidates->total() }} entries</div>
        {{ $candidates->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
