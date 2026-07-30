@extends('admin.layouts.app')
@section('title', 'Downloads')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-download text-success me-2"></i>Downloads Management</h1>
    <a href="{{ route('admin.downloads.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Download</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;">
                <i class="fas fa-filter" style="font-size:12px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search'))
            <a href="{{ route('admin.downloads.index') }}" class="ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2" style="padding-top:22px;">
                    <button type="submit" class="btn btn-success" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.downloads.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Title</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Category</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Type</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Size</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Downloads</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downloads as $d)
                        <tr style="border-bottom:1px solid #f1f3f5;">
                            <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $d->id }}</td>
                            <td style="padding:10px 12px;">
                                <strong style="color:#1e293b;">{{ $d->title }}</strong>
                                @if($d->description)<br><small style="color:#64748b;">{{ Str::limit($d->description, 50) }}</small>@endif
                            </td>
                            <td style="padding:10px 12px;"><span class="badge bg-light text-dark border">{{ $d->category ?? 'General' }}</span></td>
                            <td style="padding:10px 12px;color:#475569;">{{ $d->file_type ?? '-' }}</td>
                            <td style="padding:10px 12px;color:#475569;">{{ $d->file_size ? round(intval($d->file_size) / 1024, 1) . ' KB' : '-' }}</td>
                            <td style="padding:10px 12px;"><span class="badge bg-info">{{ $d->download_count ?? 0 }}</span></td>
                            <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                                <a href="{{ route('admin.downloads.edit', $d) }}" class="btn btn-sm rounded-3" title="Edit" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.downloads.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm rounded-3" title="Delete" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                    <i class="fas fa-download text-muted" style="font-size:1.3rem;opacity:0.5;"></i>
                                </div>
                                <p class="text-muted mb-1" style="font-size:0.85rem;">No downloads found</p>
                                <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                                <a href="{{ route('admin.downloads.create') }}" class="btn btn-sm btn-success rounded-3 px-3"><i class="fas fa-plus me-1"></i>Add Download</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">
            Showing {{ $downloads->firstItem() }}–{{ $downloads->lastItem() }} of {{ $downloads->total() }} downloads
        </div>
        <div>{{ $downloads->links() }}</div>
    </div>
</div>
@endsection
