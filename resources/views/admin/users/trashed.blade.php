@extends('admin.layouts.app')
@section('title', 'Trashed Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-trash-alt text-danger me-2"></i>Trashed Users</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">NAME</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">EMAIL</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">ROLE</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">DELETED AT</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#1e293b;">{{ $user->name }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $user->email }}</td>
                        <td style="padding:10px 12px;color:#475569;"><span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span></td>
                        <td style="padding:10px 12px;color:#64748b;">{{ $user->deleted_at ? $user->deleted_at->format('d M Y H:i') : 'N/A' }}</td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;">
                            @if($can('users.restore'))
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Restore"><i class="fas fa-undo"></i></button>
                            </form>
                            @endif
                            @if($can('users.delete'))
                            <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('This will permanently delete the user. Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(220,38,38,0.08);color:#dc2626;border:none;" title="Force Delete"><i class="fas fa-times"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex align-items-center justify-content-center mb-3" style="width:52px;height:52px;border-radius:14px;background:rgba(239,68,68,0.08);">
                                    <i class="fas fa-trash-alt" style="color:#ef4444;font-size:1.25rem;"></i>
                                </div>
                                <p class="text-muted mb-0">No trashed users found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages() || $users->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <span style="font-size:0.75rem;color:#64748b;">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} trashed users</span>
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
