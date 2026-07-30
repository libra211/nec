@extends('admin.layouts.app', ['title' => 'Manage Observers'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Observer Management</h2>
    <div>
        <button class="btn btn-outline-success me-1"><i class="fas fa-file-export me-1"></i> Export</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-primary">{{ $stats['total'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Applications</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-success">{{ $stats['accredited'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Accredited</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Pending Review</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-danger">{{ $stats['rejected'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Rejected</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0" id="observersTable">
            <thead>
                <tr>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 8px 10px 16px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">#</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Organization</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Type</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Contact</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Observers</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($observers)
                    @foreach($observers as $obs)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#1e293b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#1e293b;font-weight:600;">{{ e($obs->org_name) }}</td>
                        <td style="padding:10px 12px;"><span class="badge bg-info">{{ e($obs->org_type) }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ e($obs->contact_person) }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $obs->observers_count ?? 0 }}</td>
                        <td style="padding:10px 12px;">
                            @if($obs->status === 'accredited')
                                <span class="badge bg-success">Accredited</span>
                            @elseif($obs->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($obs->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></button>
                            @if(isset($obs->status) && $obs->status === 'pending')
                            <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Approve"><i class="fas fa-check"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>$(document).ready(function () { $('#observersTable').DataTable({ responsive: true }); });</script>
@endsection
