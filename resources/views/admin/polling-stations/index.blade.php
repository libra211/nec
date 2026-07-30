@extends('admin.layouts.app', ['title' => 'Manage Polling Stations'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-map-marker-alt" style="color:#2E8B57;margin-right:10px;"></i> Polling Stations</h2>
        <p class="text-muted mb-0 small">Manage all polling stations across South Sudan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.polling-stations.create') }}" class="btn btn-primary px-3 rounded-3 shadow-sm">
            <i class="fas fa-plus me-1"></i> Add Station
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label">Total Stations</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['active']) }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim gray">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-pause-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['inactive']) }}</div><div class="stat-label">Inactive</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total_voters']) }}</div><div class="stat-label">Registered Voters</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('status') || request()->filled('state'))
                <a href="{{ request()->url() }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, code, constituency..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">State</label>
                <select name="state" class="form-select" style="border-radius:8px;">
                    <option value="">All States</option>
                    @foreach($states as $sid => $sname)
                        <option value="{{ $sname }}" {{ request('state') === $sname ? 'selected' : '' }}>{{ $sname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover" style="margin-bottom:0;">
            <thead style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;">
                <tr>
                    <th style="padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Code</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">State</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">County</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Constituency</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Voters</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pollingStations as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;color:#475569;">{{ $loop->iteration + ($pollingStations->currentPage() - 1) * $pollingStations->perPage() }}</td>
                    <td style="padding:10px 12px;color:#1e293b;font-weight:600;">{{ e($item->name) }}</td>
                    <td style="padding:10px 12px;color:#475569;"><code style="font-size:0.8rem;background:#f1f5f9;padding:2px 6px;border-radius:4px;color:#0F2042;">{{ e($item->code ?? '-') }}</code></td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->state ?? '-') }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->county ?? '-') }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->constituency ?? '-') }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ number_format($item->registered_voters ?? 0) }}</td>
                    <td style="padding:10px 12px;">
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.polling-stations.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.polling-stations.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="text-center py-5">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                <i class="fas fa-map-marker-alt" style="font-size:1.25rem;color:#94a3b8;"></i>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No polling stations found.</p>
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @if($pollingStations->total() > 0)
            <span style="font-size:0.75rem;color:#64748b;">Showing {{ $pollingStations->firstItem() }} to {{ $pollingStations->lastItem() }} of {{ $pollingStations->total() }} results</span>
        @endif
        {{ $pollingStations->withQueryString()->links() }}
    </div>
</div>
@endsection
