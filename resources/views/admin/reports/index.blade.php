@extends('admin.layouts.app')
@section('title', 'Reports Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-file-alt text-primary me-2"></i>Reports Management</h1>
    <button class="btn" style="background:var(--nec-green);color:#fff;" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fas fa-upload me-1"></i> Upload Report
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('category'))
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Category</label>
                <select name="category" class="form-select" style="border-radius:8px;">
                    <option value="">All Categories</option>
                    @foreach(['annual','financial','audit','technical','policy','other'] as $c)
                        <option {{ request('category')===$c?'selected':'' }} value="{{ $c }}">{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter me-1"></i> Apply</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="margin-bottom:0;">
            <thead style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;">
                <tr>
                    <th style="padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Title</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Category</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Date</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $r)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#475569;"><strong>{{ $r->id }}</strong></td>
                        <td style="padding:10px 12px;color:#1e293b;">{{ $r->title }}</td>
                        <td style="padding:10px 12px;color:#475569;"><span class="badge bg-light text-dark border">{{ ucfirst($r->category ?? 'N/A') }}</span></td>
                        <td style="padding:10px 12px;color:#64748b;"><small>{{ $r->report_date ? \Carbon\Carbon::parse($r->report_date)->format('d M Y') : 'N/A' }}</small></td>
                        <td style="padding:10px 12px;">
                            @if($r->trashed())
                                <span class="badge bg-secondary">Deleted</span>
                            @elseif($r->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($r->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                            @if($r->trashed())
                                <form method="POST" action="{{ route('admin.reports.restore', $r->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Restore"><i class="fas fa-undo"></i></button>
                                </form>
                            @else
                                @if($r->file_path)
                                    <a href="{{ asset('storage/' . $r->file_path) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" target="_blank" title="Download"><i class="fas fa-download"></i></a>
                                @endif
                                <form method="POST" action="{{ route('admin.reports.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this report?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-5">
                                <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                    <i class="fas fa-file" style="font-size:1.25rem;color:#94a3b8;"></i>
                                </div>
                                <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No reports found</p>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @if($reports->total() > 0)
            <span style="font-size:0.75rem;color:#64748b;">Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} results</span>
        @endif
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