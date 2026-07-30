@extends('admin.layouts.app', ['title' => 'Manage Constituencies'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Constituencies</h2>
    <a href="{{ route('admin.constituencies.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Constituency</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;"><i class="fas fa-filter" style="font-size:0.75rem;"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search') || request('state') || request('status'))
            <a href="{{ route('admin.constituencies.index') }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#6b7280;text-decoration:none;"><i class="fas fa-times me-1"></i>Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, code, state..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">State</label>
                <select name="state" class="form-select" style="border-radius:8px;">
                    <option value="">All States</option>
                    @foreach($states as $st)
                        <option value="{{ $st }}" {{ request('state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#6b7280;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">#</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Code</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Name</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">State</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">County</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($constituencies as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 12px;color:#475569;">{{ $loop->iteration + ($constituencies->currentPage() - 1) * $constituencies->perPage() }}</td>
                    <td style="padding:10px 12px;color:#475569;"><code>{{ e($item->code) }}</code></td>
                    <td style="padding:10px 12px;color:#1e293b;">{{ e($item->name) }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->state ?? '-') }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->county ?? '-') }}</td>
                    <td style="padding:10px 12px;color:#475569;">
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.constituencies.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.constituencies.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5" style="color:#64748b;">
                    <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;"><i class="fas fa-map-marker-alt" style="font-size:1.25rem;color:#94a3b8;"></i></div>
                    <div style="font-weight:500;margin-bottom:4px;color:#1e293b;">No constituencies found</div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($constituencies, 'hasPages') && $constituencies->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $constituencies->firstItem() }} to {{ $constituencies->lastItem() }} of {{ $constituencies->total() }} constituencies</div>
        <div>{{ $constituencies->withQueryString()->links() }}</div>
    </div>
    @endif
</div>
@endsection