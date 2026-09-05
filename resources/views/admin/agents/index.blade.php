@extends('admin.layouts.app', ['title' => 'Registration Agents'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Registration Agents</h2>
    <a href="{{ route('admin.agents.create') }}" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
        <i class="fas fa-plus me-1"></i> Add Agent
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(46,139,87,0.1);color:var(--nec-green);">
                        <i class="fas fa-user-tag fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Agents</div>
                        <div class="fs-4 fw-bold">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(46,139,87,0.1);color:var(--nec-green);">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Active</div>
                        <div class="fs-4 fw-bold text-success">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(139,0,0,0.1);color:var(--nec-red);">
                        <i class="fas fa-ban fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Suspended</div>
                        <div class="fs-4 fw-bold text-danger">{{ $stats['suspended'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(26,60,143,0.1);color:var(--nec-blue);">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Voters Registered</div>
                        <div class="fs-4 fw-bold" style="color:var(--nec-blue);">{{ $stats['total_voters'] ?? 0 }}</div>
                    </div>
                </div>
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
            @if(request('search') || request('status') || request('state'))
            <a href="{{ route('admin.agents.index') }}" class="ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.agents.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, phone, or email..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">State</label>
                <select name="state" class="form-select" style="border-radius:8px;">
                    <option value="">All States</option>
                    @php
                        $allStates = [];
                        foreach(config('nec.regions') as $region) {
                            $allStates = array_merge($allStates, $region['states'], $region['admin_areas'] ?? []);
                        }
                    @endphp
                    @foreach($allStates as $state)
                        <option value="{{ $state }}" {{ request('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Code</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Name</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Phone</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Email</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Title</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Assigned Area</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Voters</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $agents->firstItem() + $loop->index }}</td>
                        <td style="padding:10px 12px;">
                            <span class="badge rounded-pill" style="background:rgba(46,139,87,0.1);color:#2E8B57;font-weight:700;font-size:0.78rem;letter-spacing:0.3px;">{{ $agent->agent_code }}</span>
                        </td>
                        <td style="padding:10px 12px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-2" width="32" height="32">
                                <strong style="color:#1e293b;">{{ $agent->first_name }} {{ $agent->last_name }}</strong>
                            </div>
                        </td>
                        <td style="padding:10px 12px;color:#475569;">{{ $agent->phone ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $agent->email ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $agent->title ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $agent->assigned_state }}{{ $agent->assigned_constituency ? ', ' . $agent->assigned_constituency : '' }}</td>
                        <td style="padding:10px 12px;">
                            @php
                                $statusClasses = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'suspended' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusClasses[$agent->status] ?? 'secondary' }}">{{ ucfirst($agent->status) }}</span>
                        </td>
                        <td style="padding:10px 12px;color:#475569;">{{ $agent->voters_count ?? 0 }}</td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.agents.voters', $agent) }}" class="btn btn-sm rounded-3" title="View Voters" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;"><i class="fas fa-users"></i></a>
                            <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-sm rounded-3" title="Edit" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;"><i class="fas fa-edit"></i></a>
                            @php
                                $toggleBg = $agent->status === 'active' ? 'rgba(245,158,11,0.08)' : 'rgba(34,197,94,0.08)';
                                $toggleColor = $agent->status === 'active' ? '#f59e0b' : '#22c55e';
                            @endphp
                            <button type="button" class="btn btn-sm rounded-3" title="{{ $agent->status === 'active' ? 'Suspend' : 'Activate' }}" style="padding:3px 8px;background:{{ $toggleBg }};color:{{ $toggleColor }};border:none;" onclick="toggleStatus('{{ route('admin.agents.status', $agent) }}', {{ $agent->status === 'active' ? 'false' : 'true' }})"><i class="fas fa-{{ $agent->status === 'active' ? 'ban' : 'check' }}"></i></button>
                            <button type="button" class="btn btn-sm rounded-3" title="Delete" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.agents.destroy', $agent) }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-user-tag text-muted" style="font-size:1.3rem;opacity:0.5;"></i>
                            </div>
                            <p class="text-muted mb-1" style="font-size:0.85rem;">No registration agents found</p>
                            <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('admin.agents.create') }}" class="btn btn-sm btn-success rounded-3 px-3"><i class="fas fa-plus me-1"></i>Add Agent</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">
            Showing {{ $agents->firstItem() }}–{{ $agents->lastItem() }} of {{ $agents->total() }} agents
        </div>
        <div>{{ $agents->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
