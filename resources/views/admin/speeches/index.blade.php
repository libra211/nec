@extends('admin.layouts.app', ['title' => 'Manage Speeches'])

@php $currentStatus = $status ?? request('status', ''); @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Speeches</h2>
    <a href="{{ route('admin.speeches.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Speech</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.speeches.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.speeches.index', ['status' => 'published']) }}" class="btn btn-sm {{ $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' }}">Published <span class="badge bg-success ms-1">{{ $counts['published'] }}</span></a>
        <a href="{{ route('admin.speeches.index', ['status' => 'draft']) }}" class="btn btn-sm {{ $currentStatus === 'draft' ? 'btn-warning' : 'btn-outline-warning' }}">Drafts <span class="badge bg-warning text-dark ms-1">{{ $counts['draft'] }}</span></a>
        <a href="{{ route('admin.speeches.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-danger' : 'btn-outline-danger' }}">Trash <span class="badge bg-danger ms-1">{{ $counts['trash'] }}</span></a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="color:#2E8B57;font-size:13px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Search</span>
            @if(request('search'))
                <a href="{{ route('admin.speeches.index', $currentStatus ? ['status' => $currentStatus] : []) }}" class="text-decoration-none" style="font-size:0.75rem;color:#64748b;margin-left:auto;">Clear</a>
            @endif
        </div>
        <form action="" method="GET" class="row g-3">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            <div class="col-md-4">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search speeches..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<form action="{{ route('admin.speeches.bulk-action') }}" method="POST" id="bulkForm">@csrf
<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;width:30px;"><input type="checkbox" id="selectAll" style="accent-color:#fff;"></th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Title</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Speaker</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Event</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:center;">Views</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Date</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($speeches as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-checkbox"></td>
                    <td style="padding:10px 12px;">
                        <a href="{{ route('admin.speeches.edit', $item->id) }}" class="fw-semibold text-decoration-none" style="color:#1e293b;">{{ $item->title }}</a>
                        @if($item->meta_description)
                        <div class="small text-muted" style="color:#64748b;">{{ Str::limit(e($item->meta_description), 80) }}</div>
                        @endif
                    </td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->speaker ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->event_name ?? '—' }}</td>
                    <td style="padding:10px 12px;text-align:center;"><span class="badge bg-secondary"><i class="fas fa-eye me-1"></i>{{ number_format($item->views ?? 0) }}</span></td>
                    <td style="padding:10px 12px;">
                        @if($item->status === 'published') <span class="badge bg-success">Published</span>
                        @elseif($item->status === 'draft') <span class="badge bg-warning text-dark">Draft</span>
                        @else <span class="badge bg-danger">Trash</span> @endif
                    </td>
                    <td style="padding:10px 12px;color:#64748b;">{{ $item->speech_date ? \Carbon\Carbon::parse($item->speech_date)->format('d M Y') : '—' }}</td>
                    <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                        @if($item->status !== 'trash')
                        <a href="{{ route('admin.speeches.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.speeches.toggle-status', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ $item->status === 'published' ? '6,182,212' : '46,139,87' }},0.08);color:{{ $item->status === 'published' ? '#0891b2' : '#2E8B57' }};border:none;" title="{{ $item->status === 'published' ? 'Unpublish' : 'Publish' }}"><i class="fas fa-{{ $item->status === 'published' ? 'eye-slash' : 'eye' }}"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.speeches.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        @else
                        <a href="{{ route('admin.speeches.restore', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Restore"><i class="fas fa-undo"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.speeches.force-delete', $item->id) }}')" title="Delete"><i class="fas fa-times"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fas fa-comment-slash" style="color:#94a3b8;font-size:20px;"></i>
                        </div>
                        <p style="color:#64748b;margin-bottom:8px;font-size:0.9rem;">No speeches found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($speeches->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $speeches->firstItem() }} to {{ $speeches->lastItem() }} of {{ $speeches->total() }} entries</div>
        {{ $speeches->links() }}
    </div>
    @endif
</div>

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
