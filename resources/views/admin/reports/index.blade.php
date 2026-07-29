@extends('admin.layouts.app')
@section('title', 'Reports Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-file-alt text-primary me-2"></i>Reports Management</h1>
    <button class="btn" style="background:var(--nec-green);color:#fff;" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fas fa-upload me-1"></i> Upload Report
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach(['annual','financial','audit','technical','policy','other'] as $c)
                        <option {{ request('category')===$c?'selected':'' }} value="{{ $c }}">{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Title</th><th>Category</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td><strong>{{ $r->id }}</strong></td>
                            <td>{{ $r->title }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($r->category ?? 'N/A') }}</span></td>
                            <td><small>{{ $r->report_date ? \Carbon\Carbon::parse($r->report_date)->format('d M Y') : 'N/A' }}</small></td>
                            <td>
                                @if($r->trashed())
                                    <span class="badge bg-secondary">Deleted</span>
                                @elseif($r->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($r->status) }}</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                @if($r->trashed())
                                    <form method="POST" action="{{ route('admin.reports.restore', $r->id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" title="Restore"><i class="fas fa-undo"></i></button>
                                    </form>
                                @else
                                    @if($r->file_path)
                                        <a href="{{ asset('storage/' . $r->file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Download"><i class="fas fa-download"></i></a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.reports.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this report?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-file fa-2x mb-2 d-block"></i>No reports found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reports->links() }}
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.reports.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Upload Report</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Title *</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Category *</label>
                        <select name="category" class="form-select" required>
                            @foreach(['annual','financial','audit','technical','policy','other'] as $c)
                                <option value="{{ $c }}">{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Report Date *</label><input type="date" name="report_date" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">File (PDF/DOC, max 10MB) *</label><input type="file" name="file" class="form-control" required accept=".pdf,.doc,.docx,.xls,.xlsx"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-upload me-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
