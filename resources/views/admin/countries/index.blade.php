@extends('admin.layouts.app', ['title' => 'Manage Countries'])

@php $currentStatus = $status ?? request('status', ''); @endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="fas fa-earth-africa me-2"></i>Countries</h2>
    <a href="{{ route('admin.countries.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Country</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
    <div class="card-body py-2 px-3 d-flex align-items-center flex-wrap gap-2">
        <a href="{{ route('admin.countries.index') }}" class="btn btn-sm {{ !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' }}">All <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span></a>
        <a href="{{ route('admin.countries.index', ['status' => 'active']) }}" class="btn btn-sm {{ $currentStatus === 'active' ? 'btn-success' : 'btn-outline-success' }}">Active <span class="badge bg-success ms-1">{{ $counts['active'] }}</span></a>
        <a href="{{ route('admin.countries.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $currentStatus === 'inactive' ? 'btn-danger' : 'btn-outline-danger' }}">Inactive <span class="badge bg-danger ms-1">{{ $counts['inactive'] }}</span></a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            @if($currentStatus) <input type="hidden" name="status" value="{{ $currentStatus }}"> @endif
            @if($continent ?? null) <input type="hidden" name="continent" value="{{ $continent }}"> @endif
            <div class="col-md-5">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search name, code, ISO3 or nationality..." value="{{ request('search') }}" style="border-radius:8px;">
            </div>
            <div class="col-md-3">
                <label class="d-block mb-1" style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Continent</label>
                <select name="continent" class="form-select" style="border-radius:8px;">
                    <option value="">All Continents</option>
                    @foreach($continents as $c)
                        <option value="{{ $c }}" {{ ($continent ?? '') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
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
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Code</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">ISO3</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Nationality</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Continent</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Calling Code</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($countries as $item)
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 8px 10px 16px;">
                        <a href="{{ route('admin.countries.edit', $item->id) }}" class="fw-semibold text-decoration-none" style="color:#1e293b;">{{ $item->name }}</a>
                        @if($item->status === 'inactive') <span class="badge bg-danger ms-1">Inactive</span> @endif
                    </td>
                    <td style="padding:10px 12px;"><span class="text-uppercase fw-semibold">{{ $item->code }}</span></td>
                    <td style="padding:10px 12px;"><span class="text-uppercase">{{ $item->iso3 ?? '—' }}</span></td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->nationality ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->continent ?? '—' }}</td>
                    <td style="padding:10px 12px;color:#475569;">{{ $item->calling_code ? '+' . $item->calling_code : '—' }}</td>
                    <td style="padding:10px 12px;">
                        @if($item->status === 'active') <span class="badge bg-success">Active</span>
                        @else <span class="badge bg-danger">Inactive</span> @endif
                    </td>
                    <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                        <a href="{{ route('admin.countries.edit', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('admin.countries.toggle-status', $item->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba({{ $item->status === 'active' ? '6,182,212' : '46,139,87' }},0.08);color:{{ $item->status === 'active' ? '#0891b2' : '#2E8B57' }};border:none;" title="{{ $item->status === 'active' ? 'Deactivate' : 'Activate' }}"><i class="fas fa-{{ $item->status === 'active' ? 'eye-slash' : 'eye' }}"></i></a>
                        <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.countries.destroy', $item->id) }}')" title="Delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fas fa-earth-africa" style="color:#94a3b8;font-size:20px;"></i>
                        </div>
                        <p style="color:#64748b;margin-bottom:8px;">No countries found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($countries->total() > 0)
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">Showing {{ $countries->firstItem() }} to {{ $countries->lastItem() }} of {{ $countries->total() }} entries</div>
        {{ $countries->links() }}
    </div>
    @endif
</div>
@endsection