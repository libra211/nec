@extends('admin.layouts.app', ['title' => 'Manage Videos'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Videos</h2>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Video</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;">
                <i class="fas fa-filter" style="font-size:12px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.videos.index') }}" class="ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search title, description..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Statuses</option>
                    @foreach(['published','draft','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-grow-1" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;"><i class="fas fa-times"></i></a>
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
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">URL</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Views</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Created</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($videos as $item)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration + ($videos->currentPage() - 1) * $videos->perPage() }}</td>
                        <td style="padding:10px 12px;color:#1e293b;">{{ e($item->title) }}</td>
                        <td style="padding:10px 12px;"><a href="{{ $item->url }}" target="_blank" class="text-truncate d-inline-block" style="max-width:200px;color:#475569;">{{ e($item->url) }}</a></td>
                        <td style="padding:10px 12px;color:#475569;">{{ number_format($item->views ?? 0) }}</td>
                        <td style="padding:10px 12px;">
                            @if($item->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @elseif($item->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @else
                                <span class="badge bg-danger">Trash</span>
                            @endif
                        </td>
                        <td style="padding:10px 12px;color:#64748b;">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.videos.edit', $item->id) }}" class="btn btn-sm rounded-3" title="Edit" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm rounded-3" title="Delete" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.videos.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-video text-muted" style="font-size:1.3rem;opacity:0.5;"></i>
                            </div>
                            <p class="text-muted mb-1" style="font-size:0.85rem;">No videos found</p>
                            <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('admin.videos.create') }}" class="btn btn-sm btn-success rounded-3 px-3"><i class="fas fa-plus me-1"></i>Add Video</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">
            Showing {{ $videos->firstItem() }}–{{ $videos->lastItem() }} of {{ $videos->total() }} videos
        </div>
        <div>{{ $videos->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
