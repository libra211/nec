@extends('admin.layouts.app')
@section('title', 'Election Petitions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-gavel text-danger me-2"></i>Election Petitions</h1>
    @if($can('petitions.create'))
    <a href="{{ route('admin.petitions.create') }}" class="btn" style="background:var(--nec-green);color:#fff;"><i class="fas fa-plus me-1"></i> File Petition</a>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-search" style="color:#2E8B57;font-size:13px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Search</span>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.petitions.index') }}" class="text-decoration-none" style="font-size:0.75rem;color:#64748b;margin-left:auto;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search petitioner, respondent, constituency..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-2">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Status</option>
                    @foreach(['filed','hearing','decided','dismissed','withdrawn'] as $s)<option {{ request('status')===$s?'selected':'' }} value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-filter"></i></button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.petitions.index') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;">Clear</a>
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
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Petition No.</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Petitioner</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Respondent</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Constituency</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Filing Date</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petitions as $p)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#1e293b;">{{ $p->id }}</td>
                        <td style="padding:10px 12px;"><strong style="color:#1e293b;">{{ $p->petition_number }}</strong></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $p->petitioner_name }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $p->respondent_name }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $p->constituency ?? '-' }}</td>
                        <td style="padding:10px 12px;color:#64748b;"><small>{{ $p->filing_date ? \Carbon\Carbon::parse($p->filing_date)->format('d M Y') : '-' }}</small></td>
                        <td style="padding:10px 12px;">
                            @php $colors=['filed'=>'info','hearing'=>'warning','decided'=>'success','dismissed'=>'secondary','withdrawn'=>'dark']; @endphp
                            <span class="badge bg-{{ $colors[$p->status] ?? 'secondary' }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            @if($can('petitions.update'))
                            <a href="{{ route('admin.petitions.edit', $p) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                            @endif
                            @if($can('petitions.delete'))
                            <form method="POST" action="{{ route('admin.petitions.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-gavel" style="color:#94a3b8;font-size:20px;"></i>
                            </div>
                            <p style="color:#64748b;margin-bottom:8px;font-size:0.9rem;">No petitions found</p>
                            @if($can('petitions.create'))
                            <a href="{{ route('admin.petitions.create') }}" class="btn btn-primary rounded-3 px-3" style="font-size:0.85rem;">File Your First Petition</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($petitions->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $petitions->firstItem() }} to {{ $petitions->lastItem() }} of {{ $petitions->total() }} entries</div>
        {{ $petitions->links() }}
    </div>
    @endif
</div>
@endsection
