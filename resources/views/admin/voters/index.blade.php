@extends('admin.layouts.app', ['title' => 'Voter Management'])

@php
    $sortCol = request('sort', 'created_at');
    $sortDir = request('direction', 'desc');
    $currentUrl = route('admin.voters.index');
    if (!function_exists('sortUrl')) { function sortUrl($col, $currentCol, $currentDir) {
        $params = array_merge(request()->except('sort', 'direction'), ['sort' => $col, 'direction' => ($col === $currentCol && $currentDir === 'asc') ? 'desc' : 'asc']);
        return route('admin.voters.index', $params);
    } }
    if (!function_exists('sortIcon')) { function sortIcon($col, $currentCol, $currentDir) {
        if ($col !== $currentCol) return 'fa-sort';
        return $currentDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
    } }
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-users" style="color:#2E8B57;margin-right:10px;"></i>Voter Management</h2>
        <p class="text-muted mb-0 small">Manage registered voters, view demographics, and track transfers</p>
    </div>
    <div class="d-flex gap-2">
        @if($can('voters.export'))
        <a href="{{ route('admin.voters.export', request()->only(['search','status','state','county','constituency','gender'])) }}" class="btn btn-outline-success btn-sm px-3 rounded-3"><i class="fas fa-file-export me-1"></i> Export CSV</a>
        @endif
        @if($can('voters.import'))
        <button type="button" class="btn btn-outline-dark btn-sm px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#importVotersModal"><i class="fas fa-file-import me-1"></i> Import CSV</button>
        @endif
        @if($can('voters.create'))
        <a href="{{ route('admin.voters.create') }}" class="btn btn-primary btn-sm px-3 rounded-3 shadow-sm">
            <i class="fas fa-plus me-1"></i> Register Voter
        </a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['total_voters'] ?? 0) }}</div><div class="stat-label">Total Voters</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['active_voters'] ?? 0) }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim red">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-ban"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['suspended_voters'] ?? 0) }}</div><div class="stat-label">Suspended</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim dark">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-heartbeat"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['deceased_voters'] ?? 0) }}</div><div class="stat-label">Deceased</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim blue">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-mars"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['male_voters'] ?? 0) }}</div><div class="stat-label">Male</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim pink">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-venus"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['female_voters'] ?? 0) }}</div><div class="stat-label">Female</div></div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="stat-slim orange">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
                <div class="stat-body"><div class="stat-value">{{ number_format($stats['pending_transfers'] ?? 0) }}</div><div class="stat-label">Pending Transfers</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body px-4 py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.12);color:#2E8B57;"><i class="fas fa-sliders-h" style="font-size:0.7rem;"></i></span>
            <span class="fw-semibold" style="font-size:0.85rem;color:#2E8B57;">Filters &amp; Search</span>
            @if(request()->anyFilled(['search', 'state', 'county', 'constituency', 'status', 'gender']))
            <a href="{{ $currentUrl }}" class="btn btn-sm btn-outline-secondary ms-auto rounded-3 px-3"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form method="GET" action="{{ $currentUrl }}" class="row g-2 align-items-end">
            <div class="col-lg-3 col-md-4">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-search me-1"></i>SEARCH</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;"><i class="fas fa-search text-muted" style="font-size:11px;"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Voter ID, name, phone, email..." value="{{ request('search') }}" style="font-size:13px;border-radius:0 8px 8px 0;">
                    @if(request('search'))
                    <a href="{{ $currentUrl }}?{{ http_build_query(request()->except('search')) }}" class="input-group-text bg-white text-decoration-none text-muted" style="border-radius:0 8px 8px 0;"><i class="fas fa-times" style="font-size:10px;"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-map-marker-alt me-1"></i>STATE</label>
                <select name="state" class="form-select" style="font-size:13px;border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All States</option>
                    @foreach($states ?? [] as $s)
                        <option value="{{ $s }}" {{ request('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-map-pin me-1"></i>COUNTY</label>
                <select name="county" class="form-select" style="font-size:13px;border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All Counties</option>
                    @foreach($counties ?? [] as $c)
                        <option value="{{ $c }}" {{ request('county') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-city me-1"></i>CONSTITUENCY</label>
                <select name="constituency" class="form-select" style="font-size:13px;border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All Constituencies</option>
                    @foreach($constituencies ?? [] as $c)
                        <option value="{{ $c }}" {{ request('constituency') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-3">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-flag me-1"></i>STATUS</label>
                <select name="status" class="form-select" style="font-size:13px;border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="deceased" {{ request('status') === 'deceased' ? 'selected' : '' }}>Deceased</option>
                    <option value="trash" {{ request('status') === 'trash' ? 'selected' : '' }}>Trash</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-3">
                <label class="small text-muted mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;"><i class="fas fa-venus-mars me-1"></i>GENDER</label>
                <select name="gender" class="form-select" style="font-size:13px;border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="M" {{ request('gender') === 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ request('gender') === 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-3">
                <label class="small mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;color:transparent;">_</label>
                <button type="submit" class="btn btn-success w-100" style="font-size:13px;border-radius:8px;padding:6px 12px;background:#2E8B57;border-color:#2E8B57;"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <form id="voterBulkForm" method="POST" action="{{ route('admin.voters.bulk-action') }}">
            @csrf
            <input type="hidden" name="action" id="voterBulkAction">
            <input type="hidden" name="ids" id="voterBulkIds">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="position:sticky;top:0;z-index:2;">
                        <tr>
                            <th style="width:36px;background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;"><input type="checkbox" id="selectAllVoters" class="form-check-input" style="border-color:rgba(255,255,255,0.4);margin:0;"></th>
                            <th style="width:36px;background:#2E8B57;color:rgba(255,255,255,0.7);font-weight:500;border-bottom:2px solid #1f6b3f;padding:10px 4px;font-size:0.7rem;">#</th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('voter_id', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    VOTER ID <i class="fas {{ sortIcon('voter_id', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('full_name', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    FULL NAME <i class="fas {{ sortIcon('full_name', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;text-align:center;">
                                <a href="{{ sortUrl('gender', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center justify-content-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    GENDER <i class="fas {{ sortIcon('gender', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('state', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    STATE <i class="fas {{ sortIcon('state', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('county', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    COUNTY <i class="fas {{ sortIcon('county', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('constituency', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    CONSTITUENCY <i class="fas {{ sortIcon('constituency', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;text-align:center;">
                                <a href="{{ sortUrl('status', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center justify-content-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    STATUS <i class="fas {{ sortIcon('status', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="min-width:95px;background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;">
                                <a href="{{ sortUrl('created_at', $sortCol, $sortDir) }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color:#fff;font-size:0.75rem;letter-spacing:0.3px;">
                                    REGISTERED <i class="fas {{ sortIcon('created_at', $sortCol, $sortDir) }}" style="font-size:9px;"></i>
                                </a>
                            </th>
                            <th style="width:auto;background:#2E8B57;color:rgba(255,255,255,0.7);font-weight:500;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.7rem;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($voters)
                            @forelse($voters as $voter)
                            @php
                                $isMale = strtolower($voter->gender) === 'male' || $voter->gender === 'M';
                            @endphp
                            <tr style="transition:background 0.15s;border-bottom:1px solid #f1f3f5;">
                                <td style="padding:10px 8px 10px 16px;"><input type="checkbox" name="voter_ids[]" value="{{ $voter->id }}" class="bulk-check-voter form-check-input" style="margin:0;"></td>
                                <td class="text-muted" style="padding:10px 4px;font-size:0.7rem;">{{ $loop->iteration + ($voters->currentPage() - 1) * $voters->perPage() }}</td>
                                <td style="padding:10px 12px;"><code style="font-size:0.65rem;background:#f1f5f9;padding:3px 8px;border-radius:6px;border:1px solid #e2e8f0;color:#1e293b;">{{ $voter->voter_id }}</code></td>
                                <td style="padding:10px 12px;">
                                    <span class="fw-semibold" style="font-size:0.85rem;color:#1e293b;">{{ $voter->full_name ?? $voter->first_name . ' ' . $voter->last_name }}</span>
                                    @if(!empty($voter->phone))
                                    <div class="text-muted" style="font-size:0.6rem;line-height:1.4;margin-top:1px;">{{ $voter->phone }}</div>
                                    @endif
                                </td>
                                <td style="padding:10px 12px;text-align:center;">
                                    @if($isMale)
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(59,130,246,0.1);color:#3b82f6;font-size:0.75rem;font-weight:700;">M</span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(236,72,153,0.1);color:#ec4899;font-size:0.75rem;font-weight:700;">F</span>
                                    @endif
                                </td>
                                <td style="padding:10px 12px;font-size:0.8rem;color:#475569;">{{ $voter->state }}</td>
                                <td style="padding:10px 12px;font-size:0.8rem;color:#475569;">{{ $voter->county }}</td>
                                <td style="padding:10px 12px;font-size:0.8rem;color:#475569;">{{ $voter->constituency->name ?? $voter->constituency ?? 'N/A' }}</td>
                                <td style="padding:10px 12px;text-align:center;">
                                    @php
                                        $statusColors = ['active' => 'success', 'suspended' => 'danger', 'pending' => 'warning', 'inactive' => 'secondary'];
                                        $statusIcons = ['active' => 'fa-check-circle', 'suspended' => 'fa-ban', 'pending' => 'fa-clock', 'inactive' => 'fa-circle'];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$voter->status] ?? 'secondary' }} border-0" style="font-size:0.6rem;font-weight:500;padding:3px 10px;border-radius:6px;">
                                        <i class="fas {{ $statusIcons[$voter->status] ?? 'fa-circle' }} me-1" style="font-size:0.45rem;"></i>{{ ucfirst($voter->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td style="padding:10px 12px;font-size:0.75rem;color:#64748b;white-space:nowrap;">{{ $voter->created_at->format('d M Y') }}</td>
                                <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.voters.show', $voter->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye" style="font-size:0.7rem;"></i></a>
                                        @if($can('voters.update'))
                                        <a href="{{ route('admin.voters.edit', $voter->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit" style="font-size:0.7rem;"></i></a>
                                        @endif
                                        <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:{{ ($voter->status ?? 'active') === 'active' ? 'rgba(245,158,11,0.08)' : 'rgba(46,139,87,0.08)' }};color:{{ ($voter->status ?? 'active') === 'active' ? '#d97706' : '#2E8B57' }};border:none;" title="{{ ($voter->status ?? 'active') === 'active' ? 'Suspend' : 'Activate' }}" onclick="toggleVoterStatus('{{ route('admin.voters.status', $voter->id) }}', '{{ ($voter->status ?? 'active') === 'active' ? 'suspended' : 'active' }}')"><i class="fas fa-{{ ($voter->status ?? 'active') === 'active' ? 'ban' : 'check' }}" style="font-size:0.7rem;"></i></button>
                                        @if($can('voters.delete'))
                                        <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete" onclick="confirmDelete('{{ route('admin.voters.destroy', $voter->id) }}')"><i class="fas fa-trash" style="font-size:0.7rem;"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                        <i class="fas fa-users text-muted" style="font-size:1.3rem;opacity:0.4;"></i>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size:0.9rem;">No voters found</p>
                                    <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                                    @if($can('voters.create'))
                                    <a href="{{ route('admin.voters.create') }}" class="btn btn-primary btn-sm rounded-3 px-3"><i class="fas fa-plus me-1"></i>Register a Voter</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    @isset($voters)
    @if($voters->hasPages() || $voters->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted" style="font-size:0.75rem;">Showing {{ $voters->firstItem() }}–{{ $voters->lastItem() }} of {{ $voters->total() }} voters</span>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown" style="font-size:0.75rem;padding:4px 12px;">
                    <i class="fas fa-tasks me-1"></i> Bulk Actions
                </button>
                <ul class="dropdown-menu rounded-3 shadow-sm border-0" style="font-size:0.8rem;">
                    <li><button type="button" class="dropdown-item" onclick="bulkVoterAction('delete')"><i class="fas fa-trash text-danger me-2" style="font-size:0.7rem;"></i>Delete Selected</button></li>
                </ul>
            </div>
        </div>
        <div>{{ $voters->appends(request()->except('page'))->links() }}</div>
    </div>
    @endif
    @endisset
</div>

@if($can('voters.import'))
<div class="modal fade" id="importVotersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4" style="box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <form method="POST" action="{{ route('admin.voters.import') }}" enctype="multipart/form-data" id="voterImportForm">
                @csrf
                <div class="modal-header" style="background:#2E8B57;color:#fff;border-radius:calc(.75rem - 1px) calc(.75rem - 1px) 0 0;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-file-import me-2"></i> Import Voters (CSV)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info rounded-3 py-2 px-3 mb-3" style="font-size:.8rem;">
                        <i class="fas fa-info-circle me-1"></i> Upload a CSV of voters. Every row is stamped into the
                        administrative area you choose below. Rows with a duplicate National ID, phone, or Voter ID are
                        skipped automatically.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">State <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="import_state" id="import_state" required>
                                <option value="">-- Select State --</option>
                                @foreach($states as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">County <span class="text-muted">(optional)</span></label>
                            <select class="form-select form-select-sm" name="import_county" id="import_county">
                                <option value="">-- All Counties --</option>
                                @foreach($counties as $co)
                                    <option value="{{ $co }}">{{ $co }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Constituency <span class="text-muted">(optional)</span></label>
                            <select class="form-select form-select-sm" name="import_constituency" id="import_constituency">
                                <option value="">-- All Constituencies --</option>
                                @foreach($constituencies as $cst)
                                    <option value="{{ $cst }}">{{ $cst }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold small">CSV File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm" name="csv_file" accept=".csv,.txt" required>
                        <div class="form-text">Required columns: <code>full_name</code>, <code>gender</code> (M/F), <code>dob</code> (YYYY-MM-DD), <code>phone</code>. Optional: <code>voter_id</code>, <code>national_id</code>, <code>email</code>, <code>payam</code>, <code>boma</code>, <code>polling_station</code>, <code>registration_center</code>.</div>
                        <a href="{{ route('admin.voters.import-template') }}" class="btn btn-link btn-sm p-0 mt-1" style="font-size:.8rem;"><i class="fas fa-download me-1"></i> Download CSV template</a>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-4"><i class="fas fa-upload me-1"></i> Import Voters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(session('import_summary') || session('import_errors') || session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1080;">
    @if(session('success') && !session('import_summary'))
    <div class="toast show align-items-center border-0 text-bg-success mb-2" role="alert">
        <div class="d-flex"><div class="toast-body">{{ session('success') }}</div><button type="button" class="btn-close me-2 m-auto btn-close-white" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
    @if($summary = session('import_summary'))
    <div class="toast show border-0 text-bg-dark mb-2" role="alert">
        <div class="d-flex"><div class="toast-body">
            <strong>Import complete</strong><br>
            <span class="text-success">Imported: {{ $summary['imported'] }}</span> &middot;
            <span class="text-warning">Duplicates skipped: {{ $summary['duplicates'] }}</span> &middot;
            <span class="text-danger">Invalid: {{ $summary['invalid'] }}</span>
        </div><button type="button" class="btn-close me-2 m-auto btn-close-white" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
    @if($errors = session('import_errors'))
    <div class="toast show border-0 text-bg-light shadow" role="alert" style="max-width:420px;">
        <div class="d-flex"><div class="toast-body" style="max-height:240px;overflow:auto;font-size:.75rem;">
            <strong>Details ({{ count($errors) }}):</strong>
            @foreach($errors as $e)<div class="text-muted">- {{ $e }}</div>@endforeach
        </div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div>
    </div>
    @endif
</div>
@endif
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    $('#selectAllVoters').on('change', function () {
        $('.bulk-check-voter').prop('checked', this.checked);
    });

    window.toggleVoterStatus = function (url, status) {
        Swal.fire({
            title: status === 'suspended' ? 'Suspend this voter?' : 'Activate this voter?',
            icon: 'question', showCancelButton: true,
            confirmButtonColor: '#2E8B57', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirm'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({ url: url, type: 'PATCH', data: { status: status },
                    success: function () { Swal.fire('Done!', 'Status updated.', 'success').then(function () { location.reload(); }); }
                });
            }
        });
    };

    window.bulkVoterAction = function (action) {
        var checked = [];
        $('.bulk-check-voter:checked').each(function () { checked.push($(this).val()); });
        if (checked.length === 0) { Swal.fire('No selection', 'Please select at least one voter.', 'warning'); return; }
        Swal.fire({
            title: 'Delete ' + checked.length + ' voter(s)?',
            text: 'This action cannot be undone!', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!'
        }).then(function (result) {
            if (result.isConfirmed) {
                $('#voterBulkAction').val(action);
                $('#voterBulkIds').val(checked.join(','));
                $('#voterBulkForm').submit();
            }
        });
    };
});
</script>
@endsection
