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

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.agents.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name, phone, or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">State</label>
                <select name="state" class="form-select">
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
                    <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Title</th>
                        <th>Assigned Area</th>
                        <th>Status</th>
                        <th>Voters Registered</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td>{{ $agents->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-2" width="32" height="32">
                                <strong>{{ e($agent->first_name) }} {{ e($agent->last_name) }}</strong>
                            </div>
                        </td>
                        <td>{{ e($agent->phone ?? '-') }}</td>
                        <td>{{ e($agent->email ?? '-') }}</td>
                        <td>{{ e($agent->title ?? '-') }}</td>
                        <td>{{ e($agent->assigned_area ?? '-') }}</td>
                        <td>
                            @php
                                $statusClasses = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'suspended' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusClasses[$agent->status] ?? 'secondary' }}">{{ ucfirst($agent->status) }}</span>
                        </td>
                        <td>{{ $agent->voters_count ?? 0 }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.agents.voters', $agent) }}" class="btn btn-outline-info" title="View Voters"><i class="fas fa-users"></i></a>
                                <a href="{{ route('admin.agents.edit', $agent) }}" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-outline-{{ $agent->status === 'active' ? 'warning' : 'success' }}" title="{{ $agent->status === 'active' ? 'Suspend' : 'Activate' }}" onclick="toggleStatus('{{ route('admin.agents.status', $agent) }}', {{ $agent->status === 'active' ? 'false' : 'true' }})"><i class="fas fa-{{ $agent->status === 'active' ? 'ban' : 'check' }}"></i></button>
                                <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete('{{ route('admin.agents.destroy', $agent) }}')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-user-tag fa-2x mb-2 d-block"></i>
                            No registration agents found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $agents->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
