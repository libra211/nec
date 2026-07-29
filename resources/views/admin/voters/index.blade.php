@extends('admin.layouts.app', ['title' => 'Voter Management'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Voter Management</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.voters.export') }}" class="btn btn-outline-success"><i class="fas fa-file-export me-1"></i> Export CSV</a>
        <a href="{{ route('admin.voters.create') }}" class="btn" style="background:var(--nec-green);color:#fff;border:none;">
            <i class="fas fa-plus me-1"></i> Register Voter
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0" style="color:var(--nec-blue)">{{ number_format($stats['total_voters'] ?? 0) }}</h4>
                <small class="text-muted">Total Voters</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0 text-success">{{ number_format($stats['active_voters'] ?? 0) }}</h4>
                <small class="text-muted">Active</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0 text-danger">{{ number_format($stats['suspended_voters'] ?? 0) }}</h4>
                <small class="text-muted">Suspended</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0 text-primary"><i class="fas fa-mars"></i> {{ number_format($stats['male_voters'] ?? 0) }}</h4>
                <small class="text-muted">Male</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0 text-danger"><i class="fas fa-venus"></i> {{ number_format($stats['female_voters'] ?? 0) }}</h4>
                <small class="text-muted">Female</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-3">
                <h4 class="mb-0 text-warning">{{ number_format($stats['pending_transfers'] ?? 0) }}</h4>
                <small class="text-muted">Pending Transfers</small>
            </div>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.voters.index') }}" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small text-muted">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Voter ID or Name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">State</label>
                <select name="state" class="form-select">
                    <option value="">All States</option>
                    @foreach($states ?? [] as $s)
                        <option value="{{ $s }}" {{ request('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">County</label>
                <select name="county" class="form-select">
                    <option value="">All Counties</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Constituency</label>
                <select name="constituency" class="form-select">
                    <option value="">All Constituencies</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label small text-muted">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">All</option>
                    <option value="M" {{ request('gender') === 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ request('gender') === 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-secondary w-100"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Bulk Actions Form --}}
<form id="voterBulkForm" method="POST" action="{{ route('admin.voters.bulk-action') }}">
    @csrf
    <input type="hidden" name="action" id="voterBulkAction">
    <input type="hidden" name="ids" id="voterBulkIds">
</form>

{{-- Voters Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="votersTable">
                <thead class="table-light">
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAllVoters"></th>
                        <th>#</th>
                        <th>Voter ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>State</th>
                        <th>County</th>
                        <th>Constituency</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th style="width:auto;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($voters)
                        @foreach($voters as $voter)
                        <tr>
                            <td><input type="checkbox" name="voter_ids[]" value="{{ $voter->id }}" class="bulk-check-voter"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ e($voter->voter_id) }}</code></td>
                            <td>{{ e($voter->full_name ?? $voter->first_name . ' ' . $voter->last_name) }}</td>
                            <td>
                                @if(strtolower($voter->gender) === 'male')
                                    <i class="fas fa-mars text-primary me-1"></i> Male
                                @else
                                    <i class="fas fa-venus text-danger me-1"></i> Female
                                @endif
                            </td>
                            <td>{{ e($voter->state) }}</td>
                            <td>{{ e($voter->county) }}</td>
                            <td>{{ e($voter->constituency->name ?? $voter->constituency ?? 'N/A') }}</td>
                            <td>
                                @php
                                    $statusColors = ['active' => 'success', 'suspended' => 'danger', 'pending' => 'warning', 'inactive' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$voter->status] ?? 'secondary' }}">{{ ucfirst($voter->status ?? 'Active') }}</span>
                            </td>
                            <td>{{ $voter->created_at->format('d M Y') }}</td>
                            <td style="white-space:nowrap;width:auto;">
                                    <a href="{{ route('admin.voters.show', $voter->id) }}" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.voters.edit', $voter->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-{{ ($voter->status ?? 'active') === 'active' ? 'warning' : 'success' }}" title="{{ ($voter->status ?? 'active') === 'active' ? 'Suspend' : 'Activate' }}" onclick="toggleVoterStatus('{{ route('admin.voters.status', $voter->id) }}', '{{ ($voter->status ?? 'active') === 'active' ? 'suspended' : 'active' }}')"><i class="fas fa-{{ ($voter->status ?? 'active') === 'active' ? 'ban' : 'check' }}"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="confirmDelete('{{ route('admin.voters.destroy', $voter->id) }}')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkVoterAction('delete')"><i class="fas fa-trash me-1"></i> Delete Selected</button>
            </div>
            <div>
                @isset($voters)
                    {{ $voters->withQueryString()->links() }}
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    $('#votersTable').DataTable({ responsive: true, order: [[2, 'asc']] });

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
