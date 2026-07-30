@extends('admin.layouts.app')
@section('title', 'Complaints & Reports')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Complaints & Reports</h1>
    <div class="d-flex gap-2">
        <span class="badge bg-danger fs-6">{{ $counts['new'] }} New</span>
        <span class="badge bg-warning text-dark fs-6">{{ $counts['in_progress'] }} In Progress</span>
    </div>
</div>

<div class="row g-3 mb-4">
    @php $statusColors = ['new'=>'danger','open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary','escalated'=>'dark']; @endphp
    @foreach($statusColors as $s => $color)
        @php $isActive = request('status') === $s; @endphp
        <div class="col">
            @if($s === 'new')
                <a href="{{ route('admin.complaints.index') }}" class="text-decoration-none">
            @else
                <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}" class="text-decoration-none">
            @endif
                <div class="card {{ $isActive ? 'border-'.$color.' border-2' : '' }} shadow-sm text-center py-3">
                    <div class="fw-bold">{{ $counts[$s] ?? 0 }}</div>
                    <small class="text-muted text-capitalize">{{ str_replace('_', ' ', $s) }}</small>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="color:#2E8B57;font-size:13px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Search</span>
            @if(request('search') || request('status') || request('category') || request('priority'))
                <a href="{{ route('admin.complaints.index') }}" class="text-decoration-none" style="font-size:0.75rem;color:#64748b;margin-left:auto;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, subject..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    @foreach(['new','open','in_progress','resolved','closed','escalated'] as $s)
                        <option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Category</label>
                <select name="category" class="form-select" style="border-radius:8px;">
                    <option value="">All Categories</option>
                    @foreach(['registration','voter_card','polling_station','results','observer','staff','other'] as $c)
                        <option {{ request('category')===$c?'selected':'' }} value="{{ $c }}">{{ ucwords(str_replace('_',' ',$c)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Priority</label>
                <select name="priority" class="form-select" style="border-radius:8px;">
                    <option value="">All Priority</option>
                    @foreach(['low','medium','high','urgent'] as $p)
                        <option {{ request('priority')===$p?'selected':'' }} value="{{ $p }}">{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter"></i></button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Category</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Subject</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Priority</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Date</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $priorityColors = ['low'=>'success','medium'=>'warning','high'=>'danger','urgent'=>'dark'];
                    $complaintStatusColors = ['new'=>'danger','open'=>'info','in_progress'=>'warning','resolved'=>'success','closed'=>'secondary','escalated'=>'dark'];
                @endphp
                @forelse($complaints as $c)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;"><strong style="color:#1e293b;">{{ $c->id }}</strong></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $c->full_name }}<br><small class="text-muted">{{ $c->phone ?? $c->email ?? '' }}</small></td>
                        <td style="padding:10px 12px;"><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_',' ',$c->category)) }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ Str::limit($c->subject, 40) }}</td>
                        <td style="padding:10px 12px;">
                            <span class="badge bg-{{ $priorityColors[$c->priority] ?? 'secondary' }}">{{ ucfirst($c->priority) }}</span>
                        </td>
                        <td style="padding:10px 12px;">
                            <span class="badge bg-{{ $complaintStatusColors[$c->status] ?? 'secondary' }}">{{ ucwords(str_replace('_',' ',$c->status)) }}</span>
                        </td>
                        <td style="padding:10px 12px;color:#64748b;"><small>{{ $c->created_at ? $c->created_at->format('d M Y') : '' }}</small></td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.complaints.show', $c) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-inbox" style="color:#94a3b8;font-size:20px;"></i>
                            </div>
                            <p style="color:#64748b;margin-bottom:8px;font-size:0.9rem;">No complaints found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($complaints->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }} of {{ $complaints->total() }} entries</div>
        {{ $complaints->links() }}
    </div>
    @endif
</div>
@endsection
