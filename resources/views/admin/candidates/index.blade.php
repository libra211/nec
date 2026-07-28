@extends('admin.layouts.app', ['title' => 'Manage Candidates'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Candidates</h2>
    <a href="{{ route('admin.candidates.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Candidate</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search name, constituency, position..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Party</th>
                    <th>Position</th>
                    <th>Constituency</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($candidates as $item)
                <tr>
                    <td>{{ $loop->iteration + ($candidates->currentPage() - 1) * $candidates->perPage() }}</td>
                    <td>{{ e($item->name) }}</td>
                    <td><span class="badge bg-info">{{ e($item->politicalParty->name ?? 'N/A') }}</span></td>
                    <td>{{ e($item->position) }}</td>
                    <td>{{ e($item->constituency ?? '-') }}</td>
                    <td>
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.candidates.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.candidates.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No candidates found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $candidates->withQueryString()->links() }}
    </div>
</div>
@endsection
