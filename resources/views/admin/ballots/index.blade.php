@extends('admin.layouts.app')
@section('title', 'Ballot Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-box-open text-info me-2"></i>Ballot Management</h1>
    <a href="{{ route('admin.ballots.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Ballot</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Search election, constituency..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select"><option value="">All Status</option>
                    @foreach(['planned','printing','delivered','deployed','archived'] as $s)<option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="election_type" class="form-select"><option value="">All Types</option>
                    @foreach(['presidential','parliamentary','state_governor','county_commissioner','payam_administrator','other'] as $t)<option {{ request('election_type')===$t?'selected':'' }} value="{{ $t }}">{{ ucwords(str_replace('_',' ',$t)) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.ballots.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Election</th><th>Type</th><th>Constituency</th><th>Printed</th><th>Printer</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($ballots as $b)
                        <tr>
                            <td>{{ $b->id }}</td>
                            <td><strong>{{ $b->election_name }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_',' ',$b->election_type)) }}</span></td>
                            <td>{{ $b->constituency ?? '-' }}</td>
                            <td>{{ number_format($b->total_printed ?? 0) }}</td>
                            <td>{{ $b->printer ?? '-' }}</td>
                            <td>
                                @php $colors=['planned'=>'secondary','printing'=>'warning','delivered'=>'info','deployed'=>'success','archived'=>'dark']; @endphp
                                <span class="badge bg-{{ $colors[$b->status] ?? 'secondary' }}">{{ ucfirst($b->status) }}</span>
                            </td>
                            <td style="white-space:nowrap;">
                                <a href="{{ route('admin.ballots.edit', $b) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.ballots.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No ballot records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $ballots->links() }}
    </div>
</div>
@endsection
