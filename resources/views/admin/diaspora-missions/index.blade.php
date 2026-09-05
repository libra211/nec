@extends('admin.layouts.app', ['title' => 'Manage Diaspora Missions'])

@php $currentStatus = $status ?? request('status', ''); @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-plane-departure me-2"></i>Diaspora Missions</h2>
    @if($can('diaspora-missions.create'))
    <a href="{{ route('admin.diaspora-missions.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Mission</a>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.diaspora-missions.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.diaspora-missions.index', ['status' => 'active']) }}" class="btn btn-sm {{ $currentStatus === 'active' ? 'btn-success' : 'btn-outline-success' }}">Active <span class="badge bg-success ms-1">{{ $counts['active'] }}</span></a>
        <a href="{{ route('admin.diaspora-missions.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $currentStatus === 'inactive' ? 'btn-danger' : 'btn-outline-danger' }}">Inactive <span class="badge bg-danger ms-1">{{ $counts['inactive'] }}</span></a>
        <a href="{{ route('admin.diaspora-missions.index', ['status' => 'trash']) }}" class="btn btn-sm {{ $currentStatus === 'trash' ? 'btn-dark' : 'btn-outline-secondary' }}">Trash <span class="badge bg-secondary ms-1">{{ $counts['trash'] }}</span></a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            <div class="col-md-5">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, city or code..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-4">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Country</label>
                <select name="country_id" class="form-select" style="border-radius:8px;">
                    <option value="">All Countries</option>
                    @foreach($countries as $c)
                        <option value="{{ $c->id }}" {{ ($countryId ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Name</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Country</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">City</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Code</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Contact</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missions as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;">
                        @if($can('diaspora-missions.update'))
                        <a href="{{ route('admin.diaspora-missions.edit', $item->id) }}" class="fw-semibold text-decoration-none" style="color:#1e293b;">{{ $item->name }}</a>
                        @else
                        <span class="fw-semibold" style="color:#1e293b;">{{ $item->name }}</span>
                        @endif
                        @if($item->address)
                        <div class="small text-muted" style="color:#64748b;">{{ Str::limit($item->address, 80) }}</div>
                        @endif
                    </td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->country->name ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->city ?? '—' }}</td>
                    <td style="padding:10px 12px;"><span class="text-uppercase fw-semibold">{{ $item->code ?? '—' }}</span></td>
                    <td style="padding:10px 12px;color:#475569;">
                        @if($item->phone)<div><i class="fas fa-phone me-1"></i>{{ $item->phone }}</div>@endif
                        @if($item->email)<div><i class="fas fa-envelope me-1"></i>{{ $item->email }}</div>@endif
                        @if(!$item->phone && !$item->email) — @endif
                    </td>
                    <td style="padding:10px 12px;">
                        @if($item->status === 'active') <span class="badge bg-success">Active</span>
                        @else <span class="badge bg-danger">Inactive</span> @endif
                    </td>
                    <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                        @if($item->trashed())
                            @if($can('diaspora-missions.update'))
                            <a href="{{ route('admin.diaspora-missions.restore', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Restore"><i class="fas fa-undo"></i></a>
                            @endif
                        @else
                        @if($can('diaspora-missions.update'))
                        <a href="{{ route('admin.diaspora-missions.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        @endif
                        <a href="{{ route('admin.diaspora-missions.toggle-status', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ $item->status === 'active' ? '6,182,212' : '46,139,87' }},0.08);color:{{ $item->status === 'active' ? '#0891b2' : '#2E8B57' }};border:none;" title="{{ $item->status === 'active' ? 'Deactivate' : 'Activate' }}"><i class="fas fa-{{ $item->status === 'active' ? 'eye-slash' : 'eye' }}"></i></a>
                        @if($can('diaspora-missions.delete'))
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.diaspora-missions.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fas fa-plane-departure" style="color:#94a3b8;font-size:20px;"></i>
                        </div>
                        <p style="color:#64748b;margin-bottom:8px;">No diaspora missions found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($missions->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $missions->firstItem() }} to {{ $missions->lastItem() }} of {{ $missions->total() }} entries</div>
        {{ $missions->links() }}
    </div>
    @endif
</div>
@endsection