@extends('admin.layouts.app', ['title' => 'Manage Polling Stations'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Polling Stations</h2>
    <a href="{{ route('admin.polling-stations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Station</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name, code, constituency..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                    <th>Code</th>
                    <th>Constituency</th>
                    <th>State</th>
                    <th>Voters</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pollingStations as $item)
                <tr>
                    <td>{{ $loop->iteration + ($pollingStations->currentPage() - 1) * $pollingStations->perPage() }}</td>
                    <td>{{ e($item->name) }}</td>
                    <td><code>{{ e($item->code ?? '-') }}</code></td>
                    <td>{{ e($item->constituency ?? '-') }}</td>
                    <td>{{ e($item->state ?? '-') }}</td>
                    <td>{{ number_format($item->registered_voters ?? 0) }}</td>
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
                        <a href="{{ route('admin.polling-stations.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.polling-stations.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">No polling stations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $pollingStations->withQueryString()->links() }}
    </div>
</div>
@endsection
