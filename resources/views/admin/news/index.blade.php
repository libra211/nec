@extends('admin.layouts.app', ['title' => 'Manage News'])

@php
    $currentStatus = $status ?? request('status', '');
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-newspaper me-2"></i>News Management</h2>
    <div>
        @if($can('news.create'))
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Article</a>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'published']) }}" class="btn btn-sm {{ $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published <span class="badge bg-success ms-1">{{ $counts['published'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $currentStatus === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">Drafts <span class="badge bg-warning text-dark ms-1">{{ $counts['draft'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-danger' : 'btn-outline-danger' }}">Trash <span class="badge bg-danger ms-1">{{ $counts['trash'] }}</span></a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('category'))
            <a href="{{ route('admin.news.index', ['status' => $currentStatus ?: null]) }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Category</label>
                <select class="form-select" style="border-radius:8px;" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <form action="" method="GET" class="d-flex align-items-center gap-1" id="searchForm">
                    @if($currentStatus)
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search articles..." value="{{ request('search') }}" style="border-radius:8px;">
                    <button type="submit" class="btn btn-success" style="border-radius:8px;"><i class="fas fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.news.index', ['status' => $currentStatus ?: null]) }}" class="btn btn-sm btn-outline-danger" style="border-radius:8px;"><i class="fas fa-times"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Actions --}}
<form action="{{ route('admin.news.bulk-action') }}" method="POST" id="bulkForm">
    @csrf
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="newsTable">
                <thead>
                    <tr>
                        <th width="30" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;"><input type="checkbox" id="selectAll"></th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Title</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Category</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Author</th>
                        <th class="text-center" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Views</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Date</th>
                        <th class="text-center" style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-checkbox"></td>
                        <td style="padding:10px 12px;color:#1e293b;">
                            @if($can('news.update'))
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="fw-semibold text-decoration-none" style="color:#1e293b;">{{ $item->title }}</a>
                            @else
                            <span class="fw-semibold" style="color:#1e293b;">{{ $item->title }}</span>
                            @endif
                            @if($item->featured_image)
                                <i class="fas fa-image text-muted ms-1 small" title="Has featured image"></i>
                            @endif
                            @if($item->meta_description)
                                <i class="fas fa-tag text-muted ms-1 small" title="Has SEO meta"></i>
                            @endif
                            <div class="text-muted small mt-1" style="color:#64748b;">
                                <span>ID: {{ $item->id }}</span>
                                @if($item->slug)
                                    <span class="mx-1">|</span>
                                    <span>Slug: {{ $item->slug }}</span>
                                @endif
                                @if($item->tags)
                                    <span class="mx-1">|</span>
                                    <span>Tags: {{ $item->tags }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:10px 12px;color:#475569;"><span class="badge bg-info">{{ $item->category ?? 'General' }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $item->author ?? 'Admin' }}</td>
                        <td class="text-center" style="padding:10px 12px;color:#475569;">
                            <span class="badge bg-secondary" title="Total views">
                                <i class="fas fa-eye me-1"></i>{{ number_format($item->views ?? 0) }}
                            </span>
                        </td>
                        <td style="padding:10px 12px;color:#475569;">
                            @if($item->status === 'published')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Published</span>
                            @elseif($item->status === 'draft')
                                <span class="badge bg-warning text-dark"><i class="fas fa-pen me-1"></i>Draft</span>
                            @elseif($item->status === 'trash')
                                <span class="badge bg-danger"><i class="fas fa-trash me-1"></i>Trash</span>
                            @endif
                        </td>
                        <td style="padding:10px 12px;color:#64748b;">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            @if($item->published_at)
                                <br><span class="text-muted" style="color:#64748b;">Pub: {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="text-center" style="padding:10px 16px 10px 12px;white-space:nowrap;">
                            @if($item->status !== 'trash')
                            @if($can('news.update'))
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            <a href="{{ route('admin.news.toggle-status', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ $item->status === 'published' ? '234,179,8' : '46,139,87' }},0.08);color:{{ $item->status === 'published' ? '#ca8a04' : '#2E8B57' }};border:none;" title="{{ $item->status === 'published' ? 'Move to Draft' : 'Publish' }}"><i class="fas fa-{{ $item->status === 'published' ? 'eye-slash' : 'eye' }}"></i></a>
                            @if($can('news.delete'))
                            <button type="button" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmTrash('{{ route('admin.news.destroy', $item->id) }}')" title="Trash"><i class="fas fa-trash"></i></button>
                            @endif
                            @else
                            @if($can('news.update'))
                            <a href="{{ route('admin.news.restore', $item->id) }}" class="btn btn-sm" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;border-radius:8px;" title="Restore"><i class="fas fa-undo"></i></a>
                            @endif
                            @if($can('news.delete'))
                            <button type="button" class="btn btn-sm" style="padding:3px 8px;background:rgba(220,38,38,0.08);color:#dc2626;border:none;border-radius:8px;" onclick="confirmForceDelete('{{ route('admin.news.force-delete', $item->id) }}')" title="Delete Permanently"><i class="fas fa-times"></i></button>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5" style="color:#64748b;">
                        <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;"><i class="fas fa-newspaper" style="font-size:1.25rem;color:#94a3b8;"></i></div>
                        <div style="font-weight:500;margin-bottom:4px;color:#1e293b;">No news articles found</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($news->hasPages())
        <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
            <div style="font-size:0.75rem;color:#64748b;">Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }} articles</div>
            <div>{{ $news->links() }}</div>
        </div>
        @endif
    </div>

    {{-- Bulk Action Controls --}}
    <div class="mt-2 d-flex align-items-center gap-2" id="bulkActions">
        <span style="font-size:0.75rem;color:#64748b;" id="selectedCount">0 selected</span>
        <select name="bulk_action" class="form-select form-select-sm d-inline-block w-auto rounded-3" style="font-size:0.75rem;padding:4px 12px;">
            <option value="">Bulk Actions</option>
            <option value="publish">Publish</option>
            <option value="draft">Move to Draft</option>
            <option value="trash">Move to Trash</option>
            @if($currentStatus === 'trash')
                <option value="restore">Restore</option>
                <option value="delete">Delete Permanently</option>
            @endif
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.75rem;padding:4px 12px;">Apply</button>
    </div>
</form>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    // Select all
    $('#selectAll').on('change', function () {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });
    $(document).on('change', '.row-checkbox', updateSelectedCount);

    function updateSelectedCount() {
        var count = $('.row-checkbox:checked').length;
        $('#selectedCount').text(count + ' selected');
    }

    // Category filter redirect
    $('#categoryFilter').on('change', function () {
        var url = new URL(window.location.href);
        if (this.value) {
            url.searchParams.set('category', this.value);
        } else {
            url.searchParams.delete('category');
        }
        window.location.href = url.toString();
    });
});

function confirmTrash(url) {
    Swal.fire({
        title: 'Move to trash?',
        text: 'This article can be restored later.',
        icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, trash it'
    }).then(function (result) {
        if (result.isConfirmed) {
            $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Trashed!', 'Article moved to trash.', 'success').then(function () { location.reload(); }); }, error: function (xhr) { Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to trash article.', 'error'); } });
        }
    });
}

function confirmForceDelete(url) {
    Swal.fire({
        title: 'Permanently delete?',
        text: 'This cannot be undone!',
        icon: 'error',
        showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete permanently'
    }).then(function (result) {
        if (result.isConfirmed) {
            $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Deleted!', 'Article permanently deleted.', 'success').then(function () { location.reload(); }); }, error: function (xhr) { Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete article.', 'error'); } });
        }
    });
}
</script>
@endsection