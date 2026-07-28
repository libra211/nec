@extends('admin.layouts.app', ['title' => 'Manage Announcements'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Announcements</h2>
    <button class="btn btn-nec-green" data-bs-toggle="modal" data-bs-target="#announcementModal" id="addAnnouncement"><i class="fas fa-plus me-1"></i> Add Announcement</button>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="announcementsTable">
            <thead>
                <tr><th>#</th><th>Title</th><th>Content</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @isset($announcements)
                    @foreach($announcements as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ e($item->title) }}</td>
                        <td>{{ Str::limit(e($item->content), 80) }}</td>
                        <td>
                            @if($item->priority === 'high')
                                <span class="badge bg-danger">High</span>
                            @elseif($item->priority === 'medium')
                                <span class="badge bg-warning">Medium</span>
                            @else
                                <span class="badge bg-info">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($item->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="{{ $item->id }}" data-title="{{ e($item->title) }}" data-content="{{ e($item->content) }}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ route('admin.announcements.destroy', $item->id) }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="announcementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="announcementForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" id="annTitle" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control" rows="4" id="annContent" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-nec-green">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
    $(document).ready(function () {
        $('#announcementsTable').DataTable({ responsive: true });
    });
</script>
@endsection
