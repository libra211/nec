@extends('layouts.app', ['title' => 'Transfer Status - NEC South Sudan', 'active_page' => 'voter'])

@section('extra_head')
<style>
.section-title {
    font-size: 12px; font-weight: 700; color: var(--nec-green); text-transform: uppercase;
    letter-spacing: 1.5px; padding-bottom: 8px; border-bottom: 2px solid rgba(46,139,87,0.12);
    margin-bottom: 20px;
}
.transfer-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.transfer-table thead th {
    background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 16px;
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    color: #64748b; white-space: nowrap;
}
.transfer-table tbody td {
    padding: 14px 16px; border-bottom: 1px solid #f0f2f5; font-size: 13px; vertical-align: middle;
}
.transfer-table tbody tr:hover { background: #f8fafc; }
.status-badge {
    display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px;
    border-radius: 20px; font-size: 11px; font-weight: 700;
}
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.approved { background: #dcfce7; color: #166534; }
.status-badge.rejected { background: #fee2e2; color: #991b1b; }
.timeline-item {
    display: flex; gap: 16px; padding: 16px 0; position: relative;
}
.timeline-item:not(:last-child)::after {
    content: ''; position: absolute; left: 15px; top: 40px; bottom: -4px;
    width: 2px; background: #e2e8f0;
}
.timeline-item.done::after { background: var(--nec-green); }
.timeline-dot {
    width: 32px; height: 32px; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; font-size: 12px;
    position: relative; z-index: 1;
}
.timeline-content h6 { font-size: 13px; font-weight: 700; color: #1d2327; margin-bottom: 2px; }
.timeline-content p { font-size: 12px; color: #8c8f94; margin-bottom: 0; }
.btn-nec {
    background: var(--nec-green); border-color: var(--nec-green); color: #fff;
    font-weight: 700; border-radius: 10px; padding: 10px 20px; font-size: 14px; transition: all 0.2s;
}
.btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; }
.empty-state {
    text-align: center; padding: 48px 20px; color: #8c8f94;
}
.empty-state i { font-size: 48px; color: #dde0e4; margin-bottom: 12px; }
.empty-state h5 { color: #475569; font-weight: 700; }
</style>
@endsection

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);padding:24px 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('voter.portal.dashboard') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Dashboard</a></li>
                        <li class="breadcrumb-item active" style="color:#fff;" aria-current="page">Transfer Status</li>
                    </ol>
                </nav>
                <h4 class="text-white fw-bold mb-0">Transfer Request Status</h4>
            </div>
            <a href="{{ route('voter.portal.transfer') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:6px 14px;font-size:12px;font-weight:600;text-decoration:none;">
                <i class="fas fa-plus me-1"></i> New Request
            </a>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(isset($transfers) && count($transfers) > 0)
                    {{-- Transfer Requests Table --}}
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="transfer-table">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Reviewed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transfers as $transfer)
                                        <tr>
                                            <td>
                                                <div style="font-weight:600;color:#1d2327;">{{ $transfer->from_state ?? '' }}</div>
                                                <div style="font-size:11px;color:#8c8f94;">{{ $transfer->from_constituency ?? '' }}</div>
                                            </td>
                                            <td>
                                                <div style="font-weight:600;color:#1d2327;">{{ $transfer->new_state ?? '' }}</div>
                                                <div style="font-size:11px;color:#8c8f94;">{{ $transfer->new_constituency ?? '' }}</div>
                                            </td>
                                            <td style="max-width:160px;">
                                                <span style="font-size:12px;color:#475569;">{{ Str::limit($transfer->reason ?? '', 50) }}</span>
                                            </td>
                                            <td>
                                                @php $status = strtolower($transfer->status ?? 'pending'); @endphp
                                                <span class="status-badge {{ $status }}">
                                                    <i class="fas fa-{{ $status === 'approved' ? 'check-circle' : ($status === 'rejected' ? 'times-circle' : 'clock') }}"></i>
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td style="white-space:nowrap;font-size:12px;color:#64748b;">
                                                {{ $transfer->created_at ? date('d M Y', strtotime($transfer->created_at)) : '—' }}
                                            </td>
                                            <td style="white-space:nowrap;font-size:12px;color:#64748b;">
                                                {{ $transfer->reviewed_at ? date('d M Y', strtotime($transfer->reviewed_at)) : 'Pending' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline for each request --}}
                    @foreach($transfers as $transfer)
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0" style="font-size:14px;">
                                    <i class="fas fa-exchange-alt me-2" style="color:var(--nec-green);"></i>
                                    {{ $transfer->from_state ?? '' }} → {{ $transfer->new_state ?? '' }}
                                </h6>
                                @php $status = strtolower($transfer->status ?? 'pending'); @endphp
                                <span class="status-badge {{ $status }}">{{ ucfirst($status) }}</span>
                            </div>

                            @if($status === 'rejected' && isset($transfer->rejection_reason))
                            <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius:8px;font-size:12px;">
                                <i class="fas fa-exclamation-circle me-1"></i> <strong>Rejection Reason:</strong> {{ $transfer->rejection_reason }}
                            </div>
                            @endif

                            <div class="timeline-item done">
                                <div class="timeline-dot" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-check"></i></div>
                                <div class="timeline-content">
                                    <h6>Request Submitted</h6>
                                    <p>{{ $transfer->created_at ? date('d M Y, H:i', strtotime($transfer->created_at)) : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="timeline-item {{ in_array($status, ['approved', 'rejected']) ? 'done' : ($status === 'pending' ? '' : 'done') }}">
                                <div class="timeline-dot" style="background:{{ in_array($status, ['approved', 'rejected']) ? '#dcfce7' : '#fef3c7' }};color:{{ in_array($status, ['approved', 'rejected']) ? '#16a34a' : '#92400e' }};">
                                    <i class="fas fa-{{ in_array($status, ['approved', 'rejected']) ? 'check' : 'clock' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>Under Review</h6>
                                    <p>{{ in_array($status, ['approved', 'rejected']) ? 'Completed' : 'Your request is being reviewed by NEC' }}</p>
                                </div>
                            </div>
                            <div class="timeline-item {{ $status === 'approved' ? 'done' : '' }}">
                                <div class="timeline-dot" style="background:{{ $status === 'approved' ? '#dcfce7' : ($status === 'rejected' ? '#fee2e2' : '#f1f5f9') }};color:{{ $status === 'approved' ? '#16a34a' : ($status === 'rejected' ? '#991b1b' : '#94a3b8') }};">
                                    <i class="fas fa-{{ $status === 'approved' ? 'check' : ($status === 'rejected' ? 'times' : 'clock') }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ $status === 'approved' ? 'Transfer Approved' : ($status === 'rejected' ? 'Transfer Rejected' : 'Awaiting Decision') }}</h6>
                                    <p>{{ $transfer->reviewed_at ? date('d M Y, H:i', strtotime($transfer->reviewed_at)) : 'Pending review' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                @else
                    <div class="card border-0 shadow-sm" style="border-radius:14px;">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-exchange-alt"></i>
                                <h5>No Transfer Requests</h5>
                                <p class="mb-3">You haven't submitted any transfer requests yet.</p>
                                <a href="{{ route('voter.portal.transfer') }}" class="btn btn-nec" style="font-size:14px;">
                                    <i class="fas fa-plus me-2"></i> New Transfer Request
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('voter.portal.dashboard') }}" class="text-decoration-none small" style="color:#8c8f94;">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
