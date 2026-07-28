@extends('admin.layouts.app', ['title' => 'Manage Speeches'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Speeches</h2>
    <a href="{{ route('admin.speeches.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Speech</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search title, speaker, event..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['published','draft','trash'] as $s)
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
                    <th>Title</th>
                    <th>Speaker</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($speeches as $item)
                <tr>
                    <td>{{ $loop->iteration + ($speeches->currentPage() - 1) * $speeches->perPage() }}</td>
                    <td>{{ e($item->title) }}</td>
                    <td>{{ e($item->speaker ?? '-') }}</td>
                    <td>{{ e($item->event_name ?? '-') }}</td>
                    <td>{{ $item->speech_date ? \Carbon\Carbon::parse($item->speech_date)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($item->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($item->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.speeches.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.speeches.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No speeches found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $speeches->withQueryString()->links() }}
    </div>
</div>
@endsection
