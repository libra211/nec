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
        <div class="card border-0 shadow-sm rounded-3 text-center">
            <div class="card-body py-3">
                <h3 class="text-primary mb-1">{{ $stats['total'] ?? 0 }}</h3>
                <p class="text-muted mb-0 small">Total Observers</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center">
            <div class="card-body py-3">
                <h3 class="text-success mb-1">{{ $stats['accredited'] ?? 0 }}</h3>
                <p class="text-muted mb-0 small">Accredited</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center">
            <div class="card-body py-3">
                <h3 class="text-warning mb-1">{{ $stats['verified'] ?? 0 }}</h3>
                <p class="text-muted mb-0 small">Verified</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 text-center">
            <div class="card-body py-3">
                <h3 class="text-danger mb-1">{{ $stats['pending'] ?? 0 }}</h3>
                <p class="text-muted mb-0 small">Pending Review</p>
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
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Observer</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Email</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Category</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Organization</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;">Status</th>
                    <th style="background:#2E8B57;color:#fff;font-weight:600;border-bottom:2px solid #1f6b3f;padding:10px 16px 10px 12px;font-size:0.75rem;letter-spacing:0.3px;text-transform:uppercase;text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @isset($observers)
                    @foreach($observers as $obs)
                    <tr style="border-bottom:1px solid #f1f3f5;">
                        <td style="padding:10px 8px 10px 16px;color:#1e293b;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 12px;color:#1e293b;font-weight:600;">{{ $obs->title }} {{ $obs->last_name }}, {{ $obs->other_names }}</td>
                        <td style="padding:10px 12px;color:#475569;">{{ $obs->email }}</td>
                        <td style="padding:10px 12px;"><span class="badge bg-info">{{ ucfirst($obs->category) }}</span></td>
                        <td style="padding:10px 12px;color:#475569;">{{ $obs->organisation_name ? e($obs->organisation_name) : '—' }}</td>
                        <td style="padding:10px 12px;">
                            @if($obs->status === 'accredited')
                                <span class="badge bg-success">Accredited</span>
                            @elseif($obs->status === 'verified')
                                <span class="badge bg-info">Verified</span>
                            @elseif($obs->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($obs->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($obs->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px 10px 12px;white-space:nowrap;text-align:right;">
                            <a href="{{ route('admin.observers.show', $obs->id) }}" class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(6,182,212,0.08);color:#0891b2;border:none;" title="View"><i class="fas fa-eye"></i></a>
                            @if($obs->status === 'pending' && $can('observers.review'))
                            <form action="{{ route('admin.observers.status', $obs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Accredit this observer?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accredited">
                                <button class="btn btn-sm rounded-3" style="padding:3px 8px;background:rgba(46,139,87,0.08);color:#2E8B57;border:none;" title="Accredit"><i class="fas fa-check"></i></button>
                            </form>
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
