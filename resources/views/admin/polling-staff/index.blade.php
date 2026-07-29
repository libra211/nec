@extends('admin.layouts.app')
@section('title', 'Polling Staff')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-hard-hat text-primary me-2"></i>Polling Staff</h1>
    <a href="{{ route('admin.polling-staff.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Staff</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Search name, email..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select"><option value="">All Status</option>
                    <option {{ request('status')==='active'?'selected':'' }} value="active">Active</option>
                    <option {{ request('status')==='inactive'?'selected':'' }} value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select"><option value="">All Roles</option>
                    @foreach(['Presiding Officer','Deputy Presiding Officer','Poll Clerk','Queue Controller','Counter','Other'] as $r)
                        <option {{ request('role')===$r?'selected':'' }} value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="state" class="form-select"><option value="">All States</option>
                    @foreach($states as $st)<option {{ request('state')===$st->name?'selected':'' }} value="{{ $st->name }}">{{ $st->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100"><i class="fas fa-filter"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.polling-staff.index') }}" class="btn btn-outline-secondary w-100">Clear</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Name</th><th>Role</th><th>State</th><th>Phone</th><th>Trained</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($staff as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td><strong>{{ $s->full_name }}</strong><br><small class="text-muted">{{ $s->email }}</small></td>
                            <td><span class="badge bg-light text-dark border">{{ $s->role }}</span></td>
                            <td>{{ $s->state ?? '-' }}</td>
                            <td>{{ $s->phone ?? '-' }}</td>
                            <td>{!! $s->trained ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-muted"></i>' !!}</td>
                            <td><span class="badge bg-{{ $s->status==='active'?'success':'secondary' }}">{{ ucfirst($s->status) }}</span></td>
                            <td style="white-space:nowrap;">
                                <form method="POST" action="{{ route('admin.polling-staff.status', $s) }}" class="d-inline">@csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-{{ $s->status==='active'?'warning':'success' }}" title="{{ $s->status==='active'?'Deactivate':'Activate' }}"><i class="fas fa-toggle-{{ $s->status==='active'?'on':'off' }}"></i></button>
                                </form>
                                <a href="{{ route('admin.polling-staff.edit', $s) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.polling-staff.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No staff found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $staff->links() }}
    </div>
</div>
@endsection
