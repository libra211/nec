@extends('layouts.app', ['title' => 'Application Submitted - NEC South Sudan', 'active_page' => 'observers'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,#166534 0%,#14532d 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Application Submitted</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('observers.index') }}" class="text-white-50 text-decoration-none">Observers</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Application Success</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
                    <div class="text-center p-5" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:80px;height:80px;background:#22c55e;color:#fff;font-size:2rem;">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-2" style="font-family:'Poppins',sans-serif;">Application Received!</h3>
                        <p class="text-muted mb-0">Your observer accreditation application has been successfully submitted to NEC.</p>
                    </div>
                    <div class="p-4 p-md-5">
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Application ID</div>
                                    <div class="fw-bold" style="color:#1a3c8f;font-size:1.1rem;">#OA-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Status</div>
                                    <div><span class="badge bg-warning text-dark fw-semibold px-3 py-2" style="border-radius:20px;font-size:0.82rem;">Under Review</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 mb-4" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                            <h6 class="fw-bold mb-3" style="color:#1e40af;"><i class="fas fa-user me-2"></i>Applicant Details</h6>
                            <div class="row g-2" style="font-size:0.9rem;">
                                <div class="col-sm-6"><span class="text-muted">Name:</span> <strong>{{ $application->title }} {{ $application->first_name }} {{ $application->other_names }} {{ $application->last_name }}</strong></div>
                                <div class="col-sm-6"><span class="text-muted">Email:</span> <strong>{{ $application->email }}</strong></div>
                                <div class="col-sm-6"><span class="text-muted">Phone:</span> <strong>{{ $application->phone }}</strong></div>
                                <div class="col-sm-6"><span class="text-muted">Category:</span> <strong>{{ ucfirst($application->form_type) }} Observer</strong></div>
                                @if($application->organization_name)
                                <div class="col-12"><span class="text-muted">Organization:</span> <strong>{{ $application->organization_name }}</strong></div>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 mb-4" style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;">
                            <h6 class="fw-bold mb-3" style="color:#92400e;"><i class="fas fa-circle-info me-2"></i>What Happens Next?</h6>
                            <ol class="mb-0 ps-3" style="font-size:0.88rem;color:#78350f;line-height:1.8;">
                                <li><strong>Verification:</strong> NEC will verify your identity and organizational details (2-5 business days).</li>
                                <li><strong>Review:</strong> Your application will be reviewed by the Accreditation Committee.</li>
                                <li><strong>Decision:</strong> You will receive an email notification with the accreditation decision.</li>
                                <li><strong>Issuance:</strong> If approved, your official observer credentials and badge will be prepared for collection.</li>
                            </ol>
                        </div>

                        <div class="p-3 mb-4" style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;">
                            <p class="mb-0" style="font-size:0.85rem;color:#991b1b;"><i class="fas fa-exclamation-triangle me-2"></i><strong>Important:</strong> Please save your Application ID <strong>#OA-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</strong> for future reference. You may need it to check your application status or contact NEC.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('observers.index') }}" class="btn btn-outline-secondary flex-fill" style="border-radius:10px;font-weight:600;">
                                <i class="fas fa-arrow-left me-2"></i> Back to Observers
                            </a>
                            <a href="{{ route('home') }}" class="btn flex-fill" style="background:linear-gradient(135deg,#1a3c8f,#0d2366);color:#fff;border:none;border-radius:10px;font-weight:600;">
                                <i class="fas fa-home me-2"></i> Return Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
