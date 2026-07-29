@extends('admin.layouts.app', ['title' => 'Manage Constituencies'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Constituencies</h2>
    <a href="{{ route('admin.constituencies.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Constituency</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, code, state..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="state" class="form-select">
                    <option value="">All States</option>
                    @foreach($states as $st)
                        <option value="{{ $st }}" {{ request('state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                    <th>Code</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>County</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($constituencies as $item)
                <tr>
                    <td>{{ $loop->iteration + ($constituencies->currentPage() - 1) * $constituencies->perPage() }}</td>
                    <td><code>{{ e($item->code) }}</code></td>
                    <td>{{ e($item->name) }}</td>
                    <td>{{ e($item->state ?? '-') }}</td>
                    <td>{{ e($item->county ?? '-') }}</td>
                    <td>
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.constituencies.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.constituencies.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No constituencies found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $constituencies->withQueryString()->links() }}
    </div>
</div>
@endsection
