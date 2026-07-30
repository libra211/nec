@extends('admin.layouts.app', ['title' => 'Manage Subscribers'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Subscribers</h2>
    <a href="{{ route('admin.subscribers.export') }}" class="btn btn-primary"><i class="fas fa-download me-1"></i> Export CSV</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:rgba(46,139,87,0.1);color:#2E8B57;font-size:0.75rem;"><i class="fas fa-filter"></i></span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ request()->url() }}" class="btn btn-sm ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;padding:2px 8px;">Clear</a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, email, source..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-4">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#475569;margin-bottom:4px;display:block;">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;">
                    <option value="">All Statuses</option>
                    @foreach(['active','inactive','trash'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover" style="margin-bottom:0;">
            <thead style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;">
                <tr>
                    <th style="padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Email</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Source</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Subscribed</th>
                    <th style="padding:10px 16px 10px 12px;text-align:right;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;color:#475569;">{{ $loop->iteration + ($subscribers->currentPage() - 1) * $subscribers->perPage() }}</td>
                    <td style="padding:10px 12px;color:#1e293b;">{{ e($item->name) }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ e($item->email) }}</td>
                    <td style="padding:10px 12px;"><span class="badge bg-info">{{ e($item->source ?? 'newsletter') }}</span></td>
                    <td style="padding:10px 12px;">
                        @if($item->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($item->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </td>
                    <td style="padding:10px 12px;color:#64748b;">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-' }}</td>
                    <td style="padding:10px 16px 10px 12px;text-align:right;white-space:nowrap;">
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.subscribers.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="text-center py-5">
                            <div style="display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:14px;background:#f1f5f9;margin-bottom:12px;">
                                <i class="fas fa-users" style="font-size:1.25rem;color:#94a3b8;"></i>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;margin-bottom:12px;">No subscribers found.</p>
                            <a href="{{ request()->url() }}" class="btn btn-outline-secondary rounded-3 px-3" style="font-size:0.85rem;">Clear Filters</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        @if($subscribers->total() > 0)
            <span style="font-size:0.75rem;color:#64748b;">Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }} of {{ $subscribers->total() }} results</span>
        @endif
        {{ $subscribers->withQueryString()->links() }}
    </div>
</div>
@endsection