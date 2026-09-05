@extends('layouts.app', ['title' => 'Accreditation Verification - NEC South Sudan', 'active_page' => 'observers'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Accreditation Verification</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('observers.index') }}" class="text-white-50">Observers</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Verify Accreditation</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-shield-halved text-white-50" style="font-size:3.5rem;opacity:0.5;"></i>
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

            @if(! $application)
                <div class="card border-0 shadow-sm text-center p-5" style="border-radius:14px;">
                    <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:#fee2e2;color:#dc2626;font-size:2rem;">
                        <i class="fas fa-xmark"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Record Not Found</h4>
                    <p class="text-muted mb-4">No observer accreditation matches this verification code. The code may be mistyped or the record may not exist.</p>
                    <p class="small text-muted mb-0">If you believe this is an error, contact the National Election Commission at <a href="mailto:info@nec.gov.ss" class="link-primary text-decoration-none">info@nec.gov.ss</a>.</p>
                </div>
            @elseif($application->revoked_at)
                <div class="card border-0 shadow-sm text-center p-5" style="border-radius:14px;border:1px solid #fecaca;">
                    <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:#fee2e2;color:#dc2626;font-size:2rem;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h4 class="fw-bold mb-2" style="color:#b91c1c;">Accreditation Revoked</h4>
                    <p class="text-muted mb-1"><strong>{{ $application->accreditation_number ?: 'Unknown serial' }}</strong></p>
                    <p class="text-muted mb-3">This accreditation has been revoked and is no longer valid. Please contact NEC for further information.</p>
                    <div class="mx-auto p-3" style="background:#fef2f2;border-radius:10px;max-width:520px;">
                        <p class="mb-1 small text-danger fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i>Revoked</p>
                        <p class="mb-0 small text-muted">Reason: {{ $application->revoked_reason ?: 'Not specified.' }}</p>
                    </div>
                </div>
            @elseif(! $application->is_accredited)
                <div class="card border-0 shadow-sm text-center p-5" style="border-radius:14px;">
                    <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:#fef3c7;color:#d97706;font-size:2rem;">
                        <i class="fas fa-clock-rotate-left"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Accreditation Under Review</h4>
                    <p class="text-muted mb-0">This application reference exists, but accreditation has not yet been issued. Please check back after NEC has completed its review.</p>
                </div>
            @else
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:14px;border:2px solid #86efac;">
                    <div class="text-center p-5" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                        <div class="mx-auto mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:#16a34a;color:#fff;font-size:2rem;box-shadow:0 6px 16px rgba(22,163,74,0.35);">
                            <i class="fas fa-circle-check"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color:#14532d;">Valid Accreditation</h4>
                        <p class="text-muted mb-0">This credential is officially issued by the National Election Commission.</p>
                    </div>
                    <div class="p-4 p-md-5">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Holder Name</div>
                                    <div class="fw-bold" style="color:#0f172a;">{{ $application->full_name }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 h-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Accreditation Number</div>
                                    <div class="fw-bold text-uppercase" style="color:#166534;letter-spacing:0.5px;">{{ $application->accreditation_number }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Category</div>
                                    <div class="fw-bold" style="color:#0f172a;">{{ ucfirst($application->form_type) }} Observer</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Issued</div>
                                    <div class="fw-bold" style="color:#0f172a;">{{ $application->approved_at ? $application->approved_at->format('F j, Y') : '—' }}</div>
                                </div>
                            </div>
                            @if($application->organization_name)
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="small text-muted mb-1">Organization</div>
                                    <div class="fw-bold" style="color:#0f172a;">{{ $application->organization_name }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="p-3 mt-4" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;">
                            <p class="mb-0 small" style="color:#1e40af;"><i class="fas fa-circle-info me-2"></i>Verification checks confirm that this accreditation is genuine, active, and not photocopied, amended, or revoked.</p>
                        </div>
                    </div>
                </div>
            @endif

            </div>
        </div>
    </div>
</section>
@endsection