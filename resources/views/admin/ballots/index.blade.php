@extends('admin.layouts.app')
@section('title', 'Ballot Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-box-open text-info me-2"></i>Ballot Management</h1>
    <a href="{{ route('admin.ballots.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> Add Ballot</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="color:#2E8B57;font-size:13px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Search</span>
            @if(request('search') || request('status') || request('election_type'))
                <a href="{{ route('admin.ballots.index') }}" class="text-decoration-none" style="font-size:0.75rem;color:#64748b;margin-left:auto;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search election, constituency..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    @foreach(['planned','printing','delivered','deployed','archived'] as $s)<option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Type</label>
                <select name="election_type" class="form-select" style="border-radius:8px;">
                    <option value="">All Types</option>
                    @foreach(['presidential','parliamentary','state_governor','county_commissioner','payam_administrator','other'] as $t)<option {{ request('election_type')===$t?'selected':'' }} value="{{ $t }}">{{ ucwords(str_replace('_',' ',$t)) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter"></i></button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.ballots.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
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
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Election</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Type</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Constituency</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Printed</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Printer</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ballots as $b)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#1e293b;">{{ $b->id }}</td>
                        <td style="padding:10px 12px;"><strong style="color:#1e293b;">{{ $b->election_name }}</strong></td>
                        <td style="padding:10px 12px;"><span class="badge bg-light text-dark border">{{ ucwords(str_replace('_',' ',$b->election_type)) }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $b->constituency ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ number_format($b->total_printed ?? 0) }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $b->printer ?? '-' }}</td>
                        <td style="padding:10px 12px;">
                            @php $colors=['planned'=>'secondary','printing'=>'warning','delivered'=>'info','deployed'=>'success','archived'=>'dark']; @endphp
                            <span class="badge bg-{{ $colors[$b->status] ?? 'secondary' }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.ballots.edit', $b) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.ballots.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-inbox" style="color:#94a3b8;font-size:20px;"></i>
                            </div>
                            <p style="color:#64748b;margin-bottom:8px;font-size:0.9rem;">No ballot records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ballots->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $ballots->firstItem() }} to {{ $ballots->lastItem() }} of {{ $ballots->total() }} entries</div>
        {{ $ballots->links() }}
    </div>
    @endif
</div>
@endsection
