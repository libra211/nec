@extends('admin.layouts.app', ['title' => 'Voter Profile'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Voter Profile</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.voters.index') }}">Voters</a></li>
                <li class="breadcrumb-item active">{{ $voter->voter_id }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if($voter->isDeceased())
            <form action="{{ route('admin.voters.revive', $voter->id) }}" method="POST" onsubmit="return confirm('Clear the death record and return this voter to active?');">
                @csrf
                <button class="btn btn-outline-success"><i class="fas fa-user-check me-1"></i> Revive Voter</button>
            </form>
        @else
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#deceasedModal"><i class="fas fa-heartbeat me-1"></i> Record Death</button>
        @endif
        @if($can('voters.update'))
        <a href="{{ route('admin.voters.edit', $voter->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit me-1"></i> Edit</a>
        @endif
        <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center">
            @php
                $initials = '';
                if ($voter->full_name) {
                    $parts = explode(' ', $voter->full_name);
                    $initials = mb_substr($parts[0], 0, 1);
                    if (count($parts) > 1) $initials .= mb_substr(end($parts), 0, 1);
                }
            @endphp
            @if($voter->photo)
                <img src="{{ asset($voter->photo) }}" alt="Voter photo" class="rounded-circle me-4" style="width:80px;height:80px;object-fit:cover;border:3px solid #bbf7d0;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center me-4" style="width:80px;height:80px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:3px solid #bbf7d0;">
                    <span style="font-size:28px;font-weight:800;color:#166534;">{{ strtoupper($initials) }}</span>
                </div>
            @endif
            <div>
                <h3 class="mb-1">{{ $voter->full_name }}</h3>
                <div class="d-flex align-items-center gap-3">
                    <code class="fs-6">{{ $voter->voter_id }}</code>
                    @php $statusColors = ['active' => 'success', 'suspended' => 'danger', 'inactive' => 'secondary', 'deceased' => 'dark']; @endphp
                    <span class="badge bg-{{ $statusColors[$voter->status] ?? 'success' }} fs-6">{{ ucfirst($voter->status ?? 'Active') }}</span>
                    @if($voter->registration_type === 'agent')
                        <span class="badge" style="background:#1e40af;font-size:12px"><i class="bi bi-people me-1"></i> Agent-Assisted</span>
                    @else
                        <span class="badge" style="background:#166534;font-size:12px"><i class="bi bi-person me-1"></i> Self-Registered</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($voter->isDeceased())
    <div class="card border-0 shadow-sm mb-4" style="border-left:5px solid #343a40;">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-start">
                <div style="display:flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:50%;background:#f2f4f6;flex-shrink:0;"><i class="fas fa-heart-crack" style="color:#343a40;font-size:20px;"></i></div>
                <div class="w-100">
                    <h5 class="mb-2">Vital Record</h5>
                    <div class="row small">
                        <div class="col-md-3"><span class="text-muted">Date of Death</span><br><strong>{{ $voter->deceased_date ? $voter->deceased_date->format('d M Y') : 'N/A' }}</strong></div>
                        <div class="col-md-3"><span class="text-muted">Recorded By</span><br><strong>{{ $voter->deceased_by ?: 'N/A' }}</strong></div>
                        <div class="col-md-3"><span class="text-muted">Recorded At</span><br><strong>{{ $voter->deceased_at ? $voter->deceased_at->format('d M Y, h:i A') : 'N/A' }}</strong></div>
                        <div class="col-md-3"><span class="text-muted">Certificate Ref</span><br><strong>{{ $voter->death_certificate_ref ?: 'N/A' }}</strong></div>
                    </div>
                    <p class="small text-muted mb-0 mt-2"><i class="fas fa-info-circle me-1"></i>This voter is excluded from the electoral roll and cannot vote.</p>
                </div>
            </div>
        </div>
    </div>
@endif

@if(!$voter->isDeceased())
<div class="modal fade" id="deceasedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.voters.deceased', $voter->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Record Death</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Recording a death removes <strong>{{ $voter->full_name }}</strong> from the electoral roll and marks them ineligible to vote.</p>
                <div class="mb-3">
                    <label class="form-label">Date of Death <span class="text-danger">*</span></label>
                    <input type="date" name="deceased_date" class="form-control" required max="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Death Certificate Reference</label>
                    <input type="text" name="death_certificate_ref" class="form-control" placeholder="Optional" maxlength="100">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark"><i class="fas fa-heartbeat me-1"></i> Record Death</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:var(--nec-green)"><i class="fas fa-user me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:180px">Full Name</th><td>{{ $voter->full_name }}</td></tr>
                    <tr><th class="text-muted">Date of Birth</th><td>{{ $voter->dob ? date('d M Y', strtotime($voter->dob)) : 'N/A' }} <span class="text-muted small">({{ $voter->age() }} yrs)</span></td></tr>
                    <tr><th class="text-muted">Gender</th><td>{{ ($voter->gender ?? '') === 'M' ? 'Male' : (($voter->gender ?? '') === 'F' ? 'Female' : ($voter->gender ?? 'N/A')) }}</td></tr>
                    <tr><th class="text-muted">National ID</th><td>{{ $voter->national_id ? substr($voter->national_id, 0, 3) . '****' . substr($voter->national_id, -2) : 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Reg Number</th><td>{{ $voter->reg_number ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Nationality</th><td>{{ $voter->nationality ?? ($voter->country?->name ?? 'South Sudanese') }}</td></tr>
                    <tr><th class="text-muted">Preferred Language</th><td>{{ $voter->preferred_language ?? 'English' }}</td></tr>
                    <tr><th class="text-muted">Phone</th><td>{{ $voter->phone ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $voter->email ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:var(--nec-blue)"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:180px">State</th><td>{{ $voter->state }}</td></tr>
                    <tr><th class="text-muted">County</th><td>{{ $voter->county ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Constituency</th><td>{{ $voter->constituency ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Payam</th><td>{{ $voter->payam ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Boma</th><td>{{ $voter->boma ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Polling Station</th><td>{{ $voter->polling_station ?? 'N/A' }} @if($voter->pollingStation?->code)<span class="badge" style="background:rgba(46,139,87,0.12);color:#166534;">{{ $voter->pollingStation->code }}</span>@endif</td></tr>
                    <tr><th class="text-muted">Registration Center</th><td>{{ $voter->registration_center ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:var(--nec-gold)"><i class="fas fa-info-circle me-2"></i>Registration Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:180px">Registration Type</th><td>{{ $voter->registration_type === 'agent' ? 'Agent-Assisted' : 'Self-Registration' }}</td></tr>
                    <tr><th class="text-muted">Registered By</th><td>{{ $voter->registered_by_code ?? ($voter->registration_type === 'agent' ? 'NEC Registration Team' : 'NEC Online Portal') }}</td></tr>
                    <tr><th class="text-muted">Officer Name</th><td>{{ $voter->registered_by_name ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Title/Role</th><td>{{ $voter->registered_by_title ?? ($voter->registration_type === 'agent' ? 'Registration Officer' : 'Online Portal') }}</td></tr>
                    <tr><th class="text-muted">Location</th><td>{{ $voter->registered_by_location ?? ($voter->registration_type === 'agent' ? 'NEC Field Office' : 'NEC Portal') }}</td></tr>
                    <tr><th class="text-muted">Registration Date</th><td>{{ $voter->registered_at ? date('d M Y, h:i A', strtotime($voter->registered_at)) : 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:var(--nec-green)"><i class="fas fa-check-circle me-2"></i>Eligibility &amp; Roll Status</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:180px">Eligible to Vote</th><td>
                        @if($voter->isDeceased())
                            <span class="badge bg-dark">Deceased — excluded</span>
                        @elseif($voter->isEligibleToVote())
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Eligible</span>
                        @else
                            <span class="badge bg-warning text-dark">Not yet eligible</span>
                        @endif
                    </td></tr>
                    <tr><th class="text-muted">Age at Next Election</th><td>{{ $voter->ageAtElection() }} years</td></tr>
                    <tr><th class="text-muted">Eligibility Date</th><td>{{ $voter->eligibility_date?->format('d M Y') ?? ($voter->eligibilityDate()?->format('d M Y') ?? 'N/A') }}</td></tr>
                    <tr><th class="text-muted">Pre-Registered</th><td>{{ $voter->isPreRegistered() ? '<span class="badge bg-info">Yes</span>' : 'No' }}</td></tr>
                    <tr><th class="text-muted">Electoral Status</th><td><span class="badge bg-{{ $statusColors[$voter->status] ?? 'success' }}">{{ ucfirst($voter->status ?? 'active') }}</span></td></tr>
                    <tr><th class="text-muted">Record Created</th><td>{{ $voter->created_at ? date('d M Y, h:i A', strtotime($voter->created_at)) : 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Last Updated</th><td>{{ $voter->updated_at ? date('d M Y, h:i A', strtotime($voter->updated_at)) : 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:#7c3aed"><i class="fas fa-user-lock me-2"></i>Voter Portal Account</h5>
            </div>
            <div class="card-body">
                @if($voter->account)
                    <table class="table table-borderless mb-0">
                        <tr><th class="text-muted" style="width:180px">Account Email</th><td>{{ $voter->account->email }}</td></tr>
                        <tr><th class="text-muted">Account Status</th><td>
                            @php $accColors = ['active' => 'success', 'inactive' => 'secondary', 'locked' => 'danger']; @endphp
                            <span class="badge bg-{{ $accColors[$voter->account->status] ?? 'secondary' }}">{{ ucfirst($voter->account->status ?? 'inactive') }}</span>
                        </td></tr>
                        <tr><th class="text-muted">Email Verified</th><td>{{ $voter->account->email_verified_at ? date('d M Y', strtotime($voter->account->email_verified_at)) : '<span class="text-danger">No</span>' }}</td></tr>
                        <tr><th class="text-muted">Last Login</th><td>{{ $voter->account->last_login ? date('d M Y, h:i A', strtotime($voter->account->last_login)) : 'Never' }}</td></tr>
                    </table>
                @else
                    <p class="text-muted text-center mb-0">No portal account created yet.</p>
                @endif
            </div>
        </div>
    </div>

    @if($voter->is_diaspora || $voter->country_id || $voter->country_name || $voter->city)
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:#0369a1"><i class="fas fa-globe-africa me-2"></i>Diaspora / International Registration</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:180px">Type</th><td><span class="badge bg-info">Diaspora</span></td></tr>
                    <tr><th class="text-muted">Country</th><td>{{ $voter->country_name ?? $voter->country?->name ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">City</th><td>{{ $voter->city ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Address</th><td>{{ $voter->address ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Postal Code</th><td>{{ $voter->postal_code ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Passport No.</th><td>{{ $voter->passport_number ? substr($voter->passport_number, 0, 2) . '****' . substr($voter->passport_number, -2) : 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Document Type</th><td>{{ $voter->document_type ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Mission</th><td>{{ $voter->diasporaMission?->name ?? ($voter->diaspora_mission_id ? 'Assigned' : 'N/A') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0" style="color:var(--nec-gold)"><i class="fas fa-exchange-alt me-2"></i>Transfer History</h5>
            </div>
            <div class="card-body">
                @if(isset($voter->voterTransfers) && $voter->voterTransfers->count())
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr><th>From</th><th>To</th><th>Status</th><th>Requested</th><th>Reviewed</th></tr>
                        </thead>
                        <tbody>
                            @foreach($voter->voterTransfers as $transfer)
                            <tr>
                                <td>{{ $transfer->from_constituency ?? '-' }}, {{ $transfer->from_state ?? '-' }}</td>
                                <td>{{ $transfer->to_constituency ?? '-' }}, {{ $transfer->to_state ?? '-' }}</td>
                                <td>
                                    @php $tColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary']; @endphp
                                    <span class="badge bg-{{ $tColors[$transfer->status] ?? 'secondary' }}">{{ ucfirst($transfer->status) }}</span>
                                </td>
                                <td>{{ date('d M Y', strtotime($transfer->created_at)) }}</td>
                                <td>{{ $transfer->reviewed_at ? date('d M Y', strtotime($transfer->reviewed_at)) . ($transfer->reviewed_by ? ' · ' . $transfer->reviewed_by : '') : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted text-center mb-0">No transfer history</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
