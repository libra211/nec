@extends('admin.layouts.app', ['title' => 'Manage Education Materials'])

@php $currentStatus = $status ?? request('status', ''); @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Education Materials</h2>
    <a href="{{ route('admin.education.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Material</a>
</div>

<div class="card mb-3">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.education.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.education.index', ['status' => 'published']) }}" class="btn btn-sm {{ $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published <span class="badge bg-success ms-1">{{ $counts['published'] }}</span></a>
        <a href="{{ route('admin.education.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $currentStatus === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">Drafts <span class="badge bg-warning text-dark ms-1">{{ $counts['draft'] }}</span></a>
        <a href="{{ route('admin.education.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-danger' : 'btn-outline-danger' }}">Trash <span class="badge bg-danger ms-1">{{ $counts['trash'] }}</span></a>
        <form action="" method="GET" class="d-inline-flex align-items-center gap-1 ms-auto">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search materials..." value="{{ request('search') }}" style="width:200px;">
            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<form action="{{ route('admin.education.bulk-action') }}" method="POST" id="bulkForm">@csrf
<div class="card"><div class="card-body p-0">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th width="30"><input type="checkbox" id="selectAll"></th>
            <th>Title</th>
            <th>Type</th>
            <th>Language</th>
            <th>Audience</th>
            <th class="text-center">Views</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($materials as $item)
        <tr>
            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-checkbox"></td>
            <td>
                <a href="{{ route('admin.education.edit', $item->id) }}" class="fw-semibold text-decoration-none">{{ e($item->title) }}</a>
                @if($item->meta_description)
                <div class="small text-muted">{{ Str::limit(e($item->meta_description), 80) }}</div>
                @endif
            </td>
            <td><span class="badge bg-info">{{ e(ucfirst(str_replace('_', ' ', $item->content_type ?? 'document'))) }}</span></td>
            <td>{{ e($item->language ?? 'English') }}</td>
            <td class="small">{{ e($item->target_audience ?? 'general') }}</td>
            <td class="text-center"><span class="badge bg-secondary"><i class="fas fa-eye me-1"></i>{{ number_format($item->views ?? 0) }}</span></td>
            <td>
                @if($item->status === 'published') <span class="badge bg-success">Published</span>
                @elseif($item->status === 'draft') <span class="badge bg-warning text-dark">Draft</span>
                @else <span class="badge bg-danger">Trash</span> @endif
            </td>
            <td class="text-center" style="white-space:nowrap;">
                @if($item->status !== 'trash')
                <a href="{{ route('admin.education.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                <a href="{{ route('admin.education.toggle-status', $item->id) }}" class="btn btn-sm btn-outline-{{ $item->status === 'published' ? 'warning' : 'success' }}" title="{{ $item->status === 'published' ? 'Unpublish' : 'Publish' }}"><i class="fas fa-{{ $item->status === 'published' ? 'eye-slash' : 'eye' }}"></i></a>
                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.education.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                @else
                <a href="{{ route('admin.education.restore', $item->id) }}" class="btn btn-sm btn-outline-success" title="Restore"><i class="fas fa-undo"></i></a>
                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.education.force-delete', $item->id) }}')" title="Delete"><i class="fas fa-times"></i></button>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-4 text-muted">No education materials found.</td></tr>
        @endforelse
    </tbody>
</table>
</div>@if($materials->hasPages())<div class="card-footer">{{ $materials->links() }}</div>@endif</div>

<div class="mt-2 d-flex align-items-center gap-2">
    <span class="text-muted small" id="selectedCount">0 selected</span>
    <select name="bulk_action" class="form-select form-select-sm w-auto">
        <option value="">Bulk Actions</option>
        <option value="publish">Publish</option>
        <option value="draft">Move to Draft</option>
        <option value="trash">Move to Trash</option>
        @if($currentStatus === 'trash')<option value="restore">Restore</option><option value="delete">Delete Permanently</option>@endif
    </select>
    <button type="submit" class="btn btn-sm btn-outline-secondary">Apply</button>
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
