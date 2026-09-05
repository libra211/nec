@extends('admin.layouts.app', ['title' => 'Polling Station Details'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight:700;"><i class="fas fa-map-marker-alt" style="color:#2E8B57;margin-right:10px;"></i> {{ $pollingStation->name }}</h2>
        <p class="text-muted mb-0 small">Polling station details, assigned staff and voter statistics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.polling-stations.index') }}" class="btn btn-outline-secondary rounded-3 px-3"><i class="fas fa-arrow-left me-1"></i> Back</a>
        @if($can('polling-stations.update'))
        <a href="{{ route('admin.polling-stations.edit', $pollingStation->id) }}" class="btn btn-primary px-3 rounded-3 shadow-sm"><i class="fas fa-edit me-1"></i> Edit</a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0" style="font-size:0.95rem;font-weight:700;"><i class="fas fa-info-circle me-2" style="color:#2E8B57;"></i>Station Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Station Code</div>
                        <div style="font-weight:600;color:#1e293b;"><code style="background:#f1f5f9;padding:3px 8px;border-radius:5px;font-size:0.85rem;">{{ $pollingStation->code ?? '—' }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Status</div>
                        @if($pollingStation->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($pollingStation->status === 'inactive')
                            <span class="badge bg-secondary">Inactive</span>
                        @else
                            <span class="badge bg-danger">Trash</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Geographic Hierarchy</div>
                        <div style="color:#334155;font-size:0.9rem;">
                            <i class="fas fa-map-marked-alt me-1" style="color:#2E8B57;"></i>{{ $pollingStation->state ?? 'N/A' }}
                            @if($pollingStation->county) · {{ $pollingStation->county }} @endif
                            @if($pollingStation->constituency) · {{ $pollingStation->constituency }} @endif
                            @if($pollingStation->payam) · {{ $pollingStation->payam }} @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Registered Voters</div>
                        <div style="font-weight:700;font-size:1.3rem;color:#1e293b;">{{ number_format($pollingStation->registered_voters ?? 0) }}</div>
                    </div>
                    @if($pollingStation->latitude && $pollingStation->longitude)
                    <div class="col-12">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Coordinates</div>
                        <div style="color:#334155;font-size:0.9rem;"><code>{{ $pollingStation->latitude }}, {{ $pollingStation->longitude }}</code></div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Created</div>
                        <div style="color:#334155;font-size:0.9rem;">{{ $pollingStation->created_at ? date('d M Y', strtotime($pollingStation->created_at)) : 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.4px;text-transform:uppercase;color:#64748b;margin-bottom:4px;">Last Updated</div>
                        <div style="color:#334155;font-size:0.9rem;">{{ $pollingStation->updated_at ? date('d M Y', strtotime($pollingStation->updated_at)) : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="font-size:0.95rem;font-weight:700;"><i class="fas fa-user-tie me-2" style="color:#2E8B57;"></i>Assigned Staff ({{ $pollingStation->pollingStaff->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="padding:9px 16px;font-size:0.72rem;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Name</th>
                            <th style="padding:9px 12px;font-size:0.72rem;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Role</th>
                            <th style="padding:9px 12px;font-size:0.72rem;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Contact</th>
                            <th style="padding:9px 12px;font-size:0.72rem;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Trained</th>
                            <th style="padding:9px 16px 9px 12px;text-align:right;font-size:0.72rem;letter-spacing:0.3px;text-transform:uppercase;color:#64748b;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pollingStation->pollingStaff as $staff)
                        <tr>
                            <td style="padding:9px 16px;font-weight:600;color:#1e293b;">{{ $staff->full_name }}</td>
                            <td style="padding:9px 12px;color:#475569;"><span class="badge" style="background:rgba(46,139,87,0.12);color:#166534;">{{ ucfirst(str_replace('_', ' ', $staff->role)) }}</span></td>
                            <td style="padding:9px 12px;color:#475569;font-size:0.85rem;">{{ $staff->phone ?? ($staff->email ?? '—') }}</td>
                            <td style="padding:9px 12px;color:#475569;">{{ $staff->trained ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' }}</td>
                            <td style="padding:9px 16px 9px 12px;text-align:right;">
                                <span class="badge bg-{{ $staff->status === 'active' ? 'success' : ($staff->status === 'suspended' ? 'danger' : 'secondary') }}">{{ ucfirst($staff->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><p class="text-muted text-center my-4 mb-0" style="font-size:0.9rem;">No polling staff assigned to this station yet.</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="stat-slim green">
                    <div class="stat-row">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-body"><div class="stat-value">{{ number_format($pollingStation->registered_voters ?? 0) }}</div><div class="stat-label">Registered Voters</div></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="stat-slim purple">
                    <div class="stat-row">
                        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                        <div class="stat-body"><div class="stat-value">{{ $pollingStation->polling_staff_count }}</div><div class="stat-label">Assigned Staff</div></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="stat-slim teal">
                    <div class="stat-row">
                        <div class="stat-icon"><i class="fas fa-address-book"></i></div>
                        <div class="stat-body"><div class="stat-value">{{ number_format($linkedVoters) }}</div><div class="stat-label">Linked Voter Records</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0" style="font-size:0.95rem;font-weight:700;"><i class="fas fa-atlas me-2" style="color:#2E8B57;"></i>Location Summary</h5>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless mb-0" style="font-size:0.85rem;">
                    <tr><td class="text-muted" style="width:110px;">State</td><td style="font-weight:600;">{{ $pollingStation->state ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">County</td><td style="font-weight:600;">{{ $pollingStation->county ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Constituency</td><td style="font-weight:600;">{{ $pollingStation->constituency ?? 'N/A' }}</td></tr>
                    <tr><td class="text-muted">Payam</td><td style="font-weight:600;">{{ $pollingStation->payam ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection