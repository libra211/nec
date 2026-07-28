@extends('admin.layouts.app', ['title' => 'Manage News'])

@php
    $currentStatus = $status ?? request('status', '');
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-newspaper me-2"></i>News Management</h2>
    <div>
        <a href="{{ route('admin.news.create') }}" class="btn btn-nec-green"><i class="fas fa-plus me-1"></i> Add Article</a>
    </div>
</div>

{{-- Status Filter Tabs (WordPress-style) --}}
<div class="card mb-3">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'published']) }}" class="btn btn-sm {{ $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published <span class="badge bg-success ms-1">{{ $counts['published'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $currentStatus === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">Drafts <span class="badge bg-warning text-dark ms-1">{{ $counts['draft'] }}</span></a>
        <a href="{{ route('admin.news.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-danger' : 'btn-outline-danger' }}">Trash <span class="badge bg-danger ms-1">{{ $counts['trash'] }}</span></a>
        <span class="text-muted mx-2">|</span>

        {{-- Category Filter --}}
        <select class="form-select form-select-sm d-inline-block w-auto" id="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>

        {{-- Search --}}
        <form action="" method="GET" class="d-inline-flex align-items-center gap-1 ms-auto" id="searchForm">
            @if($currentStatus)
                <input type="hidden" name="status" value="{{ $currentStatus }}">
            @endif
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search articles..." value="{{ request('search') }}" style="width:200px;">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.news.index', ['status' => $currentStatus ?: null]) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>
</div>

{{-- Bulk Actions --}}
<form action="{{ route('admin.news.bulk-action') }}" method="POST" id="bulkForm">
    @csrf
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="newsTable">
                <thead class="table-light">
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th class="text-center">Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-checkbox"></td>
                        <td>
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="fw-semibold text-decoration-none">{{ e($item->title) }}</a>
                            @if($item->featured_image)
                                <i class="fas fa-image text-muted ms-1 small" title="Has featured image"></i>
                            @endif
                            @if($item->meta_description)
                                <i class="fas fa-tag text-muted ms-1 small" title="Has SEO meta"></i>
                            @endif
                            <div class="text-muted small mt-1">
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
                        <td><span class="badge bg-info">{{ e($item->category ?? 'General') }}</span></td>
                        <td>{{ e($item->author ?? 'Admin') }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary" title="Total views">
                                <i class="fas fa-eye me-1"></i>{{ number_format($item->views ?? 0) }}
                            </span>
                        </td>
                        <td>
                            @if($item->status === 'published')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Published</span>
                            @elseif($item->status === 'draft')
                                <span class="badge bg-warning text-dark"><i class="fas fa-pen me-1"></i>Draft</span>
                            @elseif($item->status === 'trash')
                                <span class="badge bg-danger"><i class="fas fa-trash me-1"></i>Trash</span>
                            @endif
                        </td>
                        <td class="small">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            @if($item->published_at)
                                <br><span class="text-muted">Pub: {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status !== 'trash')
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.news.toggle-status', $item->id) }}" class="btn btn-outline-{{ $item->status === 'published' ? 'warning' : 'success' }}" title="{{ $item->status === 'published' ? 'Move to Draft' : 'Publish' }}">
                                    <i class="fas fa-{{ $item->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                </a>
                                <button class="btn btn-outline-danger" onclick="confirmTrash('{{ route('admin.news.destroy', $item->id) }}')" title="Trash"><i class="fas fa-trash"></i></button>
                            </div>
                            @else
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.news.restore', $item->id) }}" class="btn btn-outline-success" title="Restore"><i class="fas fa-undo"></i></a>
                                <button class="btn btn-outline-danger" onclick="confirmForceDelete('{{ route('admin.news.force-delete', $item->id) }}')" title="Delete Permanently"><i class="fas fa-times"></i></button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No news articles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($news->hasPages())
        <div class="card-footer">
            {{ $news->links() }}
        </div>
        @endif
    </div>

    {{-- Bulk Action Controls --}}
    <div class="mt-2 d-flex align-items-center gap-2" id="bulkActions">
        <span class="text-muted small" id="selectedCount">0 selected</span>
        <select name="bulk_action" class="form-select form-select-sm d-inline-block w-auto">
            <option value="">Bulk Actions</option>
            <option value="publish">Publish</option>
            <option value="draft">Move to Draft</option>
            <option value="trash">Move to Trash</option>
            @if($currentStatus === 'trash')
                <option value="restore">Restore</option>
                <option value="delete">Delete Permanently</option>
            @endif
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary">Apply</button>
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
            $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Trashed!', 'Article moved to trash.', 'success').then(function () { location.reload(); }); } });
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
            $.ajax({ url: url, type: 'DELETE', success: function () { Swal.fire('Deleted!', 'Article permanently deleted.', 'success').then(function () { location.reload(); }); } });
        }
    });
}
</script>
@endsection
