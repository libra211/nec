@extends('admin.layouts.app', ['title' => 'Manage Subscribers'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Subscribers</h2>
    <a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary"><i class="fas fa-download me-1"></i> Export CSV</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, source..." value="{{ request('search') }}">
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
                    <th>Email</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Subscribed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $item)
                <tr>
                    <td>{{ $loop->iteration + ($subscribers->currentPage() - 1) * $subscribers->perPage() }}</td>
                    <td>{{ e($item->name) }}</td>
                    <td>{{ e($item->email) }}</td>
                    <td><span class="badge bg-info">{{ e($item->source ?? 'newsletter') }}</span></td>
                    <td>
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-' }}</td>
                    <td style="white-space:nowrap;">
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.subscribers.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No subscribers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $subscribers->withQueryString()->links() }}
    </div>
</div>
@endsection
