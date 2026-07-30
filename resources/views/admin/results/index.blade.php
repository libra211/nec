@extends('admin.layouts.app', ['title' => 'Manage Results'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-poll text-primary me-2"></i>Election Results</h2>
        <p class="text-muted mb-0 small">Manage election results and vote counts across all constituencies</p>
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

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;">
                <i class="fas fa-filter" style="font-size:12px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('status') || request('election_type'))
            <a href="{{ route('admin.results.index') }}" class="ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search election..." value="{{ request('search') }}" style="border-radius:0 8px 8px 0;font-size:13px;">
                </div>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Type</label>
                <select name="election_type" class="form-select" style="border-radius:8px;font-size:13px;">
                    <option value="">All Types</option>
                    @foreach(['Presidential','Parliamentary','State Assembly','Gubernatorial','Local Government','By-election','Referendum'] as $t)
                    <option value="{{ $t }}" {{ request('election_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;font-size:13px;">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--nec-green)"></i>Results</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;width:40px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Election</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Type</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Constituency</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Registered</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Votes</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;min-width:120px;">Turnout</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;width:auto;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $item)
                    @php
                        $turnoutPct = $item->turnout ?: ($item->registered_voters > 0 ? round(($item->total_votes / $item->registered_voters) * 100, 1) : 0);
                        $barColor = $turnoutPct >= 60 ? 'var(--nec-green)' : ($turnoutPct >= 30 ? '#f59e0b' : '#ef4444');
                        $typeColors = [
                            'Presidential' => 'danger',
                            'Parliamentary' => 'primary',
                            'State Assembly' => 'info',
                            'Gubernatorial' => 'success',
                            'Local Government' => 'warning',
                            'By-election' => 'secondary',
                            'Referendum' => 'dark',
                        ];
                    @endphp
                    <tr style="border-bottom:1px solid #f1f3f5;transition:background 0.15s;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration + ($results->currentPage() - 1) * $results->perPage() }}</td>
                        <td style="padding:10px 12px;">
                            <span class="fw-semibold" style="color:#1e293b;font-size:0.85rem;">{{ $item->election_name }}</span>
                            @if($item->electionEvent)
                            <div style="color:#64748b;font-size:0.6rem;line-height:1.2;">{{ $item->electionEvent->event_type ?? '' }}</div>
                            @endif
                        </td>
                        <td style="padding:10px 12px;">
                            <span class="badge bg-{{ $typeColors[$item->election_type] ?? 'secondary' }} border-0" style="font-size:0.6rem;font-weight:500;padding:2px 8px;border-radius:4px;">
                                {{ $item->election_type }}
                            </span>
                        </td>
                        <td style="padding:10px 12px;color:#475569;">{{ $item->constituency->name ?? '—' }}</td>
                        <td style="padding:10px 12px;color:#475569;text-align:right;font-weight:500;">{{ number_format($item->registered_voters ?? 0) }}</td>
                        <td style="padding:10px 12px;color:#475569;text-align:right;font-weight:500;">{{ number_format($item->total_votes ?? 0) }}</td>
                        <td style="padding:10px 12px;text-align:right;min-width:120px;">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <span style="color:{{ $barColor }};font-size:0.75rem;font-weight:600;">{{ $turnoutPct ? number_format($turnoutPct, 1) . '%' : '—' }}</span>
                                @if($turnoutPct > 0)
                                <div style="flex:1;max-width:70px;height:4px;background:#e5e7eb;border-radius:2px;overflow:hidden;">
                                    <div style="width:{{ min($turnoutPct, 100) }}%;height:100%;background:{{ $barColor }};border-radius:2px;transition:width 0.3s;"></div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td style="padding:10px 12px;text-align:center;">
                            @if($item->status === 'active')
                                <span class="badge bg-success border-0" style="font-size:0.6rem;font-weight:500;padding:2px 8px;border-radius:4px;"><i class="fas fa-check-circle me-1" style="font-size:0.5rem;"></i>Active</span>
                            @elseif($item->status === 'inactive')
                                <span class="badge bg-warning text-dark border-0" style="font-size:0.6rem;font-weight:500;padding:2px 8px;border-radius:4px;"><i class="fas fa-pause-circle me-1" style="font-size:0.5rem;"></i>Inactive</span>
                            @else
                                <span class="badge bg-danger border-0" style="font-size:0.6rem;font-weight:500;padding:2px 8px;border-radius:4px;"><i class="fas fa-trash-alt me-1" style="font-size:0.5rem;"></i>Trash</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.results.show', $item->id) }}" class="btn btn-sm rounded-3" title="View" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.results.edit', $item->id) }}" class="btn btn-sm rounded-3" title="Edit" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm rounded-3" title="Delete" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.results.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-poll text-muted" style="font-size:1.3rem;opacity:0.5;"></i>
                            </div>
                            <p class="text-muted mb-1" style="font-size:0.85rem;">No results found</p>
                            <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('admin.results.create') }}" class="btn btn-sm btn-success rounded-3 px-3"><i class="fas fa-plus me-1"></i>Add Your First Result</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($results->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">
            Showing {{ $results->firstItem() }}–{{ $results->lastItem() }} of {{ $results->total() }} results
        </div>
        <div>{{ $results->withQueryString()->links() }}</div>
    </div>
    @endif
</div>
@endsection
