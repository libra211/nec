@extends('admin.layouts.app')
@section('title', 'Downloads')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-download text-success me-2"></i>Downloads Management</h1>
    <a href="{{ route('admin.downloads.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Download</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6"><input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}"></div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.downloads.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Category</th><th>Type</th><th>Size</th><th>Downloads</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($downloads as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td><strong>{{ $d->title }}</strong>@if($d->description)<br><small class="text-muted">{{ Str::limit($d->description, 50) }}</small>@endif</td>
                            <td><span class="badge bg-light text-dark border">{{ $d->category ?? 'General' }}</span></td>
                            <td><small class="text-muted">{{ $d->file_type ?? '-' }}</small></td>
                            <td><small>{{ $d->file_size ? round(intval($d->file_size) / 1024, 1) . ' KB' : '-' }}</small></td>
                            <td><span class="badge bg-info">{{ $d->download_count ?? 0 }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.downloads.edit', $d) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <form method="POST" action="{{ route('admin.downloads.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No downloads found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $downloads->links() }}
    </div>
</div>
@endsection
