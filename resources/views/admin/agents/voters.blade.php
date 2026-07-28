@extends('admin.layouts.app', ['title' => 'Voters - ' . $agent->first_name . ' ' . $agent->last_name])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Voters Registered by {{ e($agent->first_name) }} {{ e($agent->last_name) }}</h2>
    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Agents
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-muted small mb-1">Agent Name</div>
                <div class="fw-bold">{{ e($agent->first_name) }} {{ e($agent->last_name) }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Title</div>
                <div class="fw-bold">{{ e($agent->title ?? '-') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Assigned Area</div>
                <div class="fw-bold">{{ e($agent->assigned_area ?? '-') }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Total Voters Registered</div>
                <div class="fs-4 fw-bold" style="color:var(--nec-green);">{{ $voters->total() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Voter ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>State</th>
                        <th>Constituency</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voters as $voter)
                    <tr>
                        <td>{{ $voters->firstItem() + $loop->index }}</td>
                        <td><code>{{ e($voter->voter_id) }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/default-avatar.png') }}" alt="" class="rounded-circle me-2" width="28" height="28">
                                {{ e($voter->full_name) }}
                            </div>
                        </td>
                        <td>{{ e($voter->gender ?? '-') }}</td>
                        <td>{{ e($voter->phone ?? '-') }}</td>
                        <td>{{ e($voter->state ?? '-') }}</td>
                        <td>{{ e($voter->constituency ?? '-') }}</td>
                        <td>{{ $voter->registered_at ? $voter->registered_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            No voters registered by this agent yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $voters->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
