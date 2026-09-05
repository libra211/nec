@extends('admin.layouts.app', ['title' => 'Observer Application #OA-' . str_pad((string) $application->id, 6, '0', STR_PAD_LEFT)])

@section('content')
@php $app = $application; @endphp
<div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="h3 mb-0"><i class="fas fa-clipboard-user text-primary me-2"></i>Application {{ $app->application_reference }}</h1>
    <a href="{{ route('admin.observers.applications') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>
<p class="text-muted small mb-3">Submitted {{ $app->created_at->format('M j, Y g:i A') }} &middot; Category: <span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst($app->form_type) }}</span></p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@if($app->revoked_at)
    <div class="alert alert-danger"><i class="fas fa-ban me-2"></i>This accreditation was revoked on {{ $app->revoked_at->format('M j, Y') }}. Reason: {{ $app->revoked_reason ?: 'Not recorded.' }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        @if($app->passport_photo)
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body d-flex align-items-center gap-3">
                <img src="{{ Storage::disk('public')->url($app->passport_photo) }}" alt="Passport photo" class="rounded" style="width:96px;height:96px;object-fit:cover;">
                <div>
                    <h5 class="mb-1">{{ $app->full_name }}</h5>
                    <div class="text-muted small">
                        @if($app->form_type === 'domestic')
                            National ID: {{ $app->national_id ?: '—' }}
                        @else
                            Passport: {{ $app->passport_number ?: '—' }} &middot; {{ $app->nationality }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-id-badge me-2 text-muted"></i>Applicant Details</h5></div>
            <div class="card-body">
                <div class="row g-2 small">
                    <div class="col-sm-6"><span class="text-muted">Full Name:</span> <strong>{{ $app->full_name }}</strong></div>
                    <div class="col-sm-6"><span class="text-muted">Gender:</span> {{ ucfirst($app->gender ?: '—') }}</div>
                    <div class="col-sm-6"><span class="text-muted">Date of Birth:</span> {{ $app->dob ? $app->dob->format('M j, Y') : '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Email:</span> {{ $app->email }}</div>
                    <div class="col-sm-6"><span class="text-muted">Phone:</span> {{ $app->country_code && $app->phone && !str_starts_with($app->phone, '+') ? $app->country_code . ' ' . $app->phone : $app->phone }}</div>
                    <div class="col-sm-6">
                        <span class="text-muted">Nationality:</span>
                        @if($app->form_type === 'domestic')
                            {{ $app->nationality }} <span class="badge bg-info-subtle text-info-emphasis">South Sudanese</span>
                        @else
                            {{ $app->nationality }} <span class="badge bg-secondary">{{ $app->continent ?: '—' }}</span>
                        @endif
                    </div>
                    <div class="col-sm-6"><span class="text-muted">Residential Address:</span> {{ $app->residential_address ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Postal Address:</span> {{ $app->postal_address ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Languages:</span> {{ $app->languages ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-building me-2 text-muted"></i>Organization & Mission</h5></div>
            <div class="card-body">
                <div class="row g-2 small">
                    <div class="col-sm-6"><span class="text-muted">Organization:</span> <strong>{{ $app->organization_name ?: '—' }}</strong></div>
                    <div class="col-sm-6"><span class="text-muted">Registration No.:</span> {{ $app->organization_registration ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Org. Address:</span> {{ $app->org_address ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Sponsoring Org.:</span> {{ $app->sponsoring_org ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Observer Count:</span> {{ $app->observer_count ?: '—' }}</div>
                    <div class="col-sm-6"><span class="text-muted">Employer:</span> {{ $app->employer ?: '—' }} <small class="text-muted">({{ $app->job_title ?: '—' }} &middot; {{ $app->employment_duration ?: '—' }})</small></div>
                    <div class="col-12"><span class="text-muted">Deployment Areas:</span> {{ $app->deployment_areas ?: '—' }}</div>
                    @if($app->previous_missions)<div class="col-12"><span class="text-muted">Previous Missions:</span> {{ $app->previous_missions }}</div>@endif
                    @if($app->election_experience)<div class="col-12"><span class="text-muted">Election Experience:</span> {{ $app->election_experience }}</div>@endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-paperclip me-2 text-muted"></i>Attachments</h5></div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span><i class="fas fa-file-image me-2 text-muted"></i>Passport Photo</span>
                            @if($app->passport_photo)<a href="{{ Storage::disk('public')->url($app->passport_photo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                            @else<span class="text-muted small">—</span>@endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span><i class="fas fa-file-pdf me-2 text-muted"></i>CV / Biography</span>
                            @if($app->cv_biography)<a href="{{ Storage::disk('public')->url($app->cv_biography) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                            @else<span class="text-muted small">—</span>@endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span><i class="fas fa-file-signature me-2 text-muted"></i>Letter of Appointment</span>
                            @if($app->letter_of_appointment)<a href="{{ Storage::disk('public')->url($app->letter_of_appointment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                            @else<span class="text-muted small">—</span>@endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span><i class="fas fa-certificate me-2 text-muted"></i>Proof of Registration</span>
                            @if($app->proof_registration)<a href="{{ Storage::disk('public')->url($app->proof_registration) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                            @else<span class="text-muted small">—</span>@endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <span><i class="fas fa-file-contract me-2 text-muted"></i>Code of Conduct</span>
                            @if($app->code_of_conduct)<a href="{{ Storage::disk('public')->url($app->code_of_conduct) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                            @else<span class="text-muted small">— Not uploaded at application</span>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($app->admin_notes)
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-note-sticky me-2 text-muted"></i>Admin Notes</h5></div>
            <div class="card-body"><p class="mb-0">{{ $app->admin_notes }}</p></div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-shield-halved me-2 text-muted"></i>Accreditation Status</h5></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge fs-6 {{ $app->revoked_at ? 'bg-danger' : ($app->is_accredited ? 'bg-success' : ($app->status === 'approved' ? 'bg-primary' : ($app->status === 'rejected' ? 'bg-danger' : ($app->status === 'reviewing' ? 'bg-info' : 'bg-warning text-dark')))) }}">{{ $app->revoked_at ? 'Revoked' : ($app->is_accredited ? 'Accredited' : ucfirst($app->status)) }}</span>
                </div>
                <table class="table table-sm table-borderless mb-3">
                    <tr><td class="text-muted">Accreditation No.</td><td class="text-end text-uppercase fw-semibold">{{ $app->accreditation_number ?: '—' }}</td></tr>
                    <tr><td class="text-muted">Batch</td><td class="text-end">{{ $app->batch ? $app->batch->batch_number : '—' }}</td></tr>
                    <tr><td class="text-muted">Approved by</td><td class="text-end">{{ $app->approver ? $app->approver->name : '—' }}</td></tr>
                    <tr><td class="text-muted">Approved</td><td class="text-end">{{ $app->approved_at ? $app->approved_at->format('M j, Y') : '—' }}</td></tr>
                </table>

                @if($app->is_accredited)
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.observers.applications.badge', $app->id) }}" class="btn btn-success"><i class="fas fa-id-card me-1"></i> View Accreditation Badge</a>
                        <a href="{{ $verifyUrl }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-external-link me-1"></i> Public Verify Page</a>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.observers.applications.generate', $app->id) }}">
                        @csrf
                        <button class="btn btn-success w-100" {{ $app->revoked_at ? 'disabled' : '' }}><i class="fas fa-magic me-1"></i> Generate Accreditation</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-sync-alt me-2 text-muted"></i>Update Status</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.observers.applications.status', $app->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending','reviewing','approved','rejected'] as $s)
                                <option value="{{ $s }}" {{ $app->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Review notes for the team...">{{ $app->admin_notes }}</textarea>
                    </div>
                    <button class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Save Status</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white text-danger"><h5 class="mb-0"><i class="fas fa-ban me-2"></i>Revoke Accreditation</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.observers.applications.revoke', $app->id) }}" onsubmit="return confirm('Revoke this accreditation permanently? The verification link will no longer confirm validity.');">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Revocation Reason</label>
                        <textarea name="revoked_reason" class="form-control" rows="2" required placeholder="Required"></textarea>
                    </div>
                    <button class="btn btn-outline-danger w-100" {{ !$app->is_accredited && !$app->revoked_at ? 'disabled' : '' }}><i class="fas fa-ban me-1"></i> Revoke</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection