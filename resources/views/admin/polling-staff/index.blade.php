@extends('admin.layouts.app')
@section('title', 'Polling Staff')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-hard-hat text-primary me-2"></i>Polling Staff</h1>
    @if($can('polling-staff.create'))
    <a href="{{ route('admin.polling-staff.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Staff</a>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('status') || request()->filled('role') || request()->filled('state'))
                <a href="{{ route('admin.polling-staff.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, email..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    <option {{ request('status')==='active'?'selected':'' }} value="active">Active</option>
                    <option {{ request('status')==='inactive'?'selected':'' }} value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Role</label>
                <select name="role" class="form-select" style="border-radius:8px;">
                    <option value="">All Roles</option>
                    @foreach(['Presiding Officer','Deputy Presiding Officer','Poll Clerk','Queue Controller','Counter','Other'] as $r)
                        <option {{ request('role')===$r?'selected':'' }} value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">State</label>
                <select name="state" class="form-select" style="border-radius:8px;">
                    <option value="">All States</option>
                    @foreach($states as $st)<option {{ request('state')===$st->name?'selected':'' }} value="{{ $st->name }}">{{ $st->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter me-1"></i> Apply</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.polling-staff.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
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
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Role</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">State</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Phone</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Trained</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#475569;">{{ $s->id }}</td>
                        <td style="padding:10px 12px;color:#1e293b;"><strong>{{ $s->full_name }}</strong><br><small style="color:#64748b;">{{ $s->email }}</small></td>
                        <td style="padding:10px 12px;color:#475569;"><span class="badge bg-light text-dark border">{{ $s->role }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $s->state ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $s->phone ?? '-' }}</td>
                        <td style="padding:10px 12px;">{!! $s->trained ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-muted"></i>' !!}</td>
                        <td style="padding:10px 12px;"><span class="badge bg-{{ $s->status==='active'?'success':'secondary' }}">{{ ucfirst($s->status) }}</span></td>
                        <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                            <form method="POST" action="{{ route('admin.polling-staff.status', $s) }}" class="d-inline">@csrf @method('PATCH')
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:{{ $s->status==='active' ? 'rgba(239,68,68,0.08)' : 'rgba(46,139,87,0.08)' }};color:{{ $s->status==='active' ? '#ef4444' : '#2E8B57' }};border:none;" title="{{ $s->status==='active'?'Deactivate':'Activate' }}"><i class="fas fa-toggle-{{ $s->status==='active'?'on':'off' }}"></i></button>
                            </form>
                            @if($can('polling-staff.update'))
                            <a href="{{ route('admin.polling-staff.edit', $s) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            @if($can('polling-staff.delete'))
                            <form method="POST" action="{{ route('admin.polling-staff.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="text-center py-5">
                                <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                    <i class="fas fa-inbox" style="font-size:1.25rem;color:#94a3b8;"></i>
                                </div>
                                <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No staff found</p>
                                <a href="{{ route('admin.polling-staff.index') }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @if($staff->total() > 0)
            <span style="font-size:0.75rem;color:#64748b;">Showing {{ $staff->firstItem() }} to {{ $staff->lastItem() }} of {{ $staff->total() }} results</span>
        @endif
        {{ $staff->links() }}
    </div>
</div>
@endsection