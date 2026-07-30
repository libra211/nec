@extends('admin.layouts.app', ['title' => 'Manage Gallery'])

@php $currentStatus = $status ?? request('status', ''); @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-images me-2"></i>Gallery</h2>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Image</a>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.gallery.index', ['status' => 'published']) }}" class="btn btn-sm {{ $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published <span class="badge bg-success ms-1">{{ $counts['published'] }}</span></a>
        <a href="{{ route('admin.gallery.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $currentStatus === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">Drafts <span class="badge bg-warning text-dark ms-1">{{ $counts['draft'] }}</span></a>
        <a href="{{ route('admin.gallery.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-danger' : 'btn-outline-danger' }}">Trash <span class="badge bg-danger ms-1">{{ $counts['trash'] }}</span></a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('album'))
            <a href="{{ route('admin.gallery.index', ['status' => $currentStatus ?: null]) }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form action="" method="GET" class="row g-2 align-items-end">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search gallery..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Album</label>
                <select name="album" class="form-select form-select-sm" style="border-radius:8px;">
                    <option value="">All Albums</option>
                    @foreach($albums as $al)
                        <option value="{{ $al }}" {{ request('album') === $al ? 'selected' : '' }}>{{ $al }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<form action="{{ route('admin.gallery.bulk-action') }}" method="POST" id="bulkForm">@csrf
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="30" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;"><input type="checkbox" id="selectAll"></th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Image</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Title</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Album</th>
                    <th class="text-center" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Views</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Created</th>
                    <th class="text-center" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galleries as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-checkbox"></td>
                    <td style="padding:10px 12px;">
                        @if($item->image_path)
                        <img src="{{ $item->image_path }}" alt="{{ $item->title }}" style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
                        @else
                        <span class="text-muted small">No image</span>
                        @endif
                    </td>
                    <td style="padding:10px 12px;color:#1e293b;">
                        <a href="{{ route('admin.gallery.edit', $item->id) }}" class="fw-semibold text-decoration-none" style="color:#1e293b;">{{ e($item->title) }}</a>
                        @if($item->description)
                        <div class="small text-muted" style="color:#64748b;">{{ Str::limit(e($item->description), 60) }}</div>
                        @endif
                    </td>
                    <td style="padding:10px 12px;color:#475569;"><span class="badge bg-info">{{ e($item->album ?? 'General') }}</span></td>
                    <td class="text-center" style="padding:10px 12px;color:#475569;"><span class="badge bg-secondary"><i class="fas fa-eye me-1"></i>{{ number_format($item->views ?? 0) }}</span></td>
                    <td style="padding:10px 12px;color:#475569;">
                        @if($item->status === 'published') <span class="badge bg-success">Published</span>
                        @elseif($item->status === 'draft') <span class="badge bg-warning text-dark">Draft</span>
                        @else <span class="badge bg-danger">Trash</span> @endif
                    </td>
                    <td style="padding:10px 12px;color:#64748b;">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                    <td class="text-center" style="padding:10px 16px 10px 12px;white-space:nowrap;">
                        @if($item->status !== 'trash')
                        <a href="{{ route('admin.gallery.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.gallery.toggle-status', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ $item->status === 'published' ? '234,179,8' : '46,139,87' }},0.08);color:{{ $item->status === 'published' ? '#ca8a04' : '#2E8B57' }};border:none;" title="{{ $item->status === 'published' ? 'Unpublish' : 'Publish' }}"><i class="fas fa-{{ $item->status === 'published' ? 'eye-slash' : 'eye' }}"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.gallery.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        @else
                        <a href="{{ route('admin.gallery.restore', $item->id) }}" class="btn btn-sm" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;border-radius:8px;" title="Restore"><i class="fas fa-undo"></i></a>
                        <button class="btn btn-sm" style="padding:3px 8px;background:rgba(220,38,38,0.08);color:#dc2626;border:none;border-radius:8px;" onclick="confirmDelete('{{ route('admin.gallery.force-delete', $item->id) }}')" title="Delete"><i class="fas fa-times"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5" style="color:#64748b;">
                    <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;"><i class="fas fa-images" style="font-size:1.25rem;color:#94a3b8;"></i></div>
                    <div style="font-weight:500;margin-bottom:4px;color:#1e293b;">No gallery images found</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($galleries->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $galleries->firstItem() }} to {{ $galleries->lastItem() }} of {{ $galleries->total() }} images</div>
        <div>{{ $galleries->links() }}</div>
    </div>
    @endif
</div>

<div class="mt-2 d-flex align-items-center gap-2">
    <span style="font-size:0.75rem;color:#64748b;" id="selectedCount">0 selected</span>
    <select name="bulk_action" class="form-select form-select-sm w-auto rounded-3" style="font-size:0.75rem;padding:4px 12px;">
        <option value="">Bulk Actions</option>
        <option value="publish">Publish</option>
        <option value="draft">Move to Draft</option>
        <option value="trash">Move to Trash</option>
        @if($currentStatus === 'trash')<option value="restore">Restore</option><option value="delete">Delete Permanently</option>@endif
    </select>
    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.75rem;padding:4px 12px;">Apply</button>
</div>
</form>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    $('#selectAll').on('change', function() { $('.row-checkbox').prop('checked', this.checked); updateSelectedCount(); });
    $(document).on('change', '.row-checkbox', updateSelectedCount);
    function updateSelectedCount() { $('#selectedCount').text($('.row-checkbox:checked').length + ' selected'); }
});
</script>
@endsection