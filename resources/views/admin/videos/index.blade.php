@extends('admin.layouts.app', ['title' => 'Manage Videos'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Videos</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Video</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search title, description..." value="{{ request('search') }}">
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
                    <th>URL</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($videos as $item)
                <tr>
                    <td>{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                    <td>{{ e($item->title) }}</td>
                    <td><a href="{{ $item->url }}" target="_blank" class="text-truncate d-inline-block" style="max-width:200px;">{{ e($item->url) }}</a></td>
                    <td>{{ number_format($item->views ?? 0) }}</td>
                    <td>
                        @if($item->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif($item->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.videos.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.videos.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No videos found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $videos->withQueryString()->links() }}
    </div>
</div>
@endsection
