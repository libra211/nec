@extends('admin.layouts.app', ['title' => 'Manage Results'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Election Results</h2>
    <a href="{{ route('admin.results.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Result</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search election name, type..." value="{{ request('search') }}">
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
                    <th>Election Name</th>
                    <th>Type</th>
                    <th>Constituency</th>
                    <th>Total Votes</th>
                    <th>Turnout</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $item)
                <tr>
                    <td>{{ $loop->iteration + ($results->currentPage() - 1) * $results->perPage() }}</td>
                    <td>{{ e($item->election_name) }}</td>
                    <td><span class="badge bg-info">{{ e($item->election_type) }}</span></td>
                    <td>{{ e($item->constituency->name ?? '-') }}</td>
                    <td>{{ number_format($item->total_votes ?? 0) }}</td>
                    <td>{{ $item->turnout ? $item->turnout . '%' : '-' }}</td>
                    <td>
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.results.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.results.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">No results found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $results->withQueryString()->links() }}
    </div>
</div>
@endsection
