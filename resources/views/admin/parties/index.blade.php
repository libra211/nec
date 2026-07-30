@extends('admin.layouts.app', ['title' => 'Manage Parties'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Political Parties Management</h2>
        <p class="text-muted mb-0 small">Manage registered political parties and their details</p>
    </div>
    <a href="{{ route('admin.parties.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Add Party</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-slim primary">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-flag"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['total'] ?? $parties->total() }}</div><div class="stat-label">Total Parties</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim green">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['active'] ?? 0 }}</div><div class="stat-label">Active</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim teal">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['with_candidates'] ?? 0 }}</div><div class="stat-label">With Candidates</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-slim gold">
            <div class="stat-row">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-body"><div class="stat-value">{{ $stats['new_this_year'] ?? 0 }}</div><div class="stat-label">Founded This Year</div></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4" style="background:#f8fafc;border:1px solid #e9edf2;">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="d-inline-flex align-items-center justify-content-center" style="width:28px;height:28px;border-radius:8px;background:rgba(46,139,87,0.1);color:#2E8B57;">
                <i class="fas fa-filter" style="font-size:12px;"></i>
            </span>
            <span style="font-size:0.85rem;font-weight:600;color:#1e293b;">Filters & Search</span>
            @if(request('search'))
            <a href="{{ route('admin.parties.index') }}" class="ms-auto" style="font-size:0.75rem;color:#64748b;text-decoration:none;">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            @endif
        </div>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-10">
                <label style="font-size:0.7rem;font-weight:500;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;"><i class="fas fa-search text-muted" style="font-size:12px;"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search parties..." value="{{ request('search') }}" style="border-radius:0 8px 8px 0;font-size:13px;">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2" style="padding-top:22px;">
                    <button type="submit" class="btn btn-success w-100" style="border-radius:8px;"><i class="fas fa-search me-1"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color:var(--nec-green)"></i>Registered Parties</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="partiesTable">
                <thead>
                    <tr>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;width:40px;">#</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;width:50px;"></th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Party</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Abbreviation</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;">Leader</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">Candidates</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:center;">Status</th>
                        <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parties as $party)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#64748b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;">
                            <div style="width:36px;height:36px;border-radius:8px;overflow:hidden;border:1px solid #eee;display:flex;align-items:center;justify-content:center;background:#fafafa;">
                                <img src="{{ asset($party->logo ?? 'assets/images/party-default.png') }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td style="padding:10px 12px;">
                            <div class="d-flex align-items-center gap-2">
                                @if($party->color)
                                <span style="width:12px;height:12px;border-radius:50%;background:{{ $party->color }};border:1px solid #ddd;display:inline-block;"></span>
                                @endif
                                <span class="fw-semibold" style="color:#1e293b;">{{ $party->name }}</span>
                            </div>
                        </td>
                        <td style="padding:10px 12px;"><span class="badge bg-light text-dark border">{{ $party->acronym ?? 'N/A' }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $party->leader ?? '—' }}</td>
                        <td style="padding:10px 12px;text-align:center;">
                            @php $candCount = $party->candidates()->count(); @endphp
                            <span class="badge bg-{{ $candCount > 0 ? 'info' : 'secondary' }}">{{ $candCount }}</span>
                        </td>
                        <td style="padding:10px 12px;text-align:center;">
                            <div class="form-check form-switch mb-0 d-inline-block">
                                <input class="form-check-input status-toggle" type="checkbox" role="switch" data-id="{{ $party->id }}" {{ $party->status ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.parties.show', $party->id) }}" class="btn btn-sm rounded-3" title="View" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.parties.edit', $party->id) }}" class="btn btn-sm rounded-3" title="Edit" style="padding:3px 8px;background:rgba(59,130,246,0.08);color:#3b82f6;border:none;"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm rounded-3" title="Delete" style="padding:3px 8px;background:rgba(239,68,68,0.08);color:#ef4444;border:none;" onclick="confirmDelete('{{ route('admin.parties.destroy', $party->id) }}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div style="width:52px;height:52px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i class="fas fa-flag text-muted" style="font-size:1.3rem;opacity:0.5;"></i>
                            </div>
                            <p class="text-muted mb-1" style="font-size:0.85rem;">No political parties found</p>
                            <p class="text-muted mb-3" style="font-size:0.7rem;">Try adjusting your search or filter criteria</p>
                            <a href="{{ route('admin.parties.create') }}" class="btn btn-sm btn-success rounded-3 px-3"><i class="fas fa-plus me-1"></i>Add Your First Party</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($parties->hasPages())
    <div class="card-footer bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-2 px-4 py-3">
        <div style="font-size:0.75rem;color:#64748b;">
            Showing {{ $parties->firstItem() }}–{{ $parties->lastItem() }} of {{ $parties->total() }} parties
        </div>
        <div>{{ $parties->links() }}</div>
    </div>
    @endif
</div>
@endsection

@section('extra_scripts')
<script>
$(document).ready(function () {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#partiesTable').DataTable({
            responsive: true,
            paging: false,
            info: false,
            sorting: false,
            searching: false
        });
    }

    $('.status-toggle').on('change', function () {
        var cb = $(this);
        var id = cb.data('id');
        var newStatus = cb.is(':checked') ? 1 : 0;
        var label = newStatus ? 'ON' : 'OFF';

        if (!confirm('Are you sure you want to turn this party ' + label + '?')) {
            cb.prop('checked', !cb.is(':checked'));
            return;
        }

        $.ajax({
            url: '{{ url("admin/parties") }}/' + id + '/toggle-status',
            method: 'PUT',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                cb.closest('td').find('.status-badge')
                    .removeClass('bg-success bg-warning')
                    .addClass(res.status ? 'bg-success' : 'bg-warning')
                    .text(res.label);
            },
            error: function () {
                cb.prop('checked', !cb.is(':checked'));
                alert('Failed to update status. Please try again.');
            }
        });
    });
});
</script>
@endsection
