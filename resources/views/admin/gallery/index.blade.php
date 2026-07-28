@extends('admin.layouts.app', ['title' => 'Manage Gallery'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gallery</h2>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Image</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search title, album..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="album" class="form-select">
                    <option value="">All Albums</option>
                    @foreach($albums as $al)
                        <option value="{{ $al }}" {{ request('album') === $al ? 'selected' : '' }}>{{ $al }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                    <th>Album</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galleries as $item)
                <tr>
                    <td>{{ $loop->iteration + ($galleries->currentPage() - 1) * $galleries->perPage() }}</td>
                    <td>{{ e($item->title) }}</td>
                    <td><span class="badge bg-info">{{ e($item->album ?? 'General') }}</span></td>
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
                    <td>
                        <a href="{{ route('admin.gallery.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.gallery.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted">No gallery images found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $galleries->withQueryString()->links() }}
    </div>
</div>
@endsection
