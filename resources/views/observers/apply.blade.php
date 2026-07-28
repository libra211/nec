@extends('layouts.app', ['title' => 'Observer Application - NEC South Sudan', 'active_page' => 'observers'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,#1a3c8f 0%,#0d2366 50%,#091a4d 100%);position:relative;overflow:hidden;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1440 320%22><path fill=%22%23ffffff%22 fill-opacity=%220.04%22 d=%22M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,250.7C960,235,1056,181,1152,165.3C1248,149,1344,171,1392,181.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z%22></svg>') no-repeat bottom center; background-size:cover;"></div>
    <div class="container position-relative">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:52px;height:52px;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);">
                        <i class="fas fa-binoculars text-white" style="font-size:1.3rem;"></i>
                    </div>
                    <span class="badge bg-white text-primary fw-semibold px-3 py-2" style="border-radius:20px;font-size:0.78rem;letter-spacing:0.5px;">OFFICIAL FORM</span>
                </div>
                <h1 class="text-white fw-bold mb-2" style="font-family:'Poppins',sans-serif;font-size:2.2rem;">Election Observer Application</h1>
                <p class="text-white-50 mb-0" style="font-size:1.05rem;max-width:600px;">Apply for official accreditation to observe elections and referenda in the Republic of South Sudan.</p>
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('observers.index') }}" class="text-white-50 text-decoration-none">Observers</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Apply</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-flex align-items-center gap-3">
                    <div class="text-end d-none d-lg-block">
                        <div class="text-white-50 small">Need help?</div>
                        <a href="mailto:observers@nec.gov.ss" class="text-white fw-semibold text-decoration-none">observers@nec.gov.ss</a>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:80px;height:80px;border:2px solid rgba(255,255,255,0.2);">
                        <i class="fas fa-shield-halved text-white" style="font-size:2.2rem;opacity:0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<style>
    .wizard-progress { display:flex; align-items:center; justify-content:center; gap:0; padding:0; margin:0 auto 2rem; max-width:700px; position:relative; }
    .wizard-progress::before { content:''; position:absolute; top:20px; left:40px; right:40px; height:3px; background:#e2e8f0; z-index:0; }
    .wizard-progress .step-indicator { position:relative; z-index:1; display:flex; flex-direction:column; align-items:center; gap:6px; flex:1; }
    .wizard-progress .step-circle { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; background:#e2e8f0; color:#94a3b8; transition:all 0.4s cubic-bezier(0.4,0,0.2,1); border:3px solid #e2e8f0; cursor:default; }
    .wizard-progress .step-indicator.active .step-circle { background:#1a3c8f; color:#fff; border-color:#1a3c8f; box-shadow:0 0 0 4px rgba(26,60,143,0.15); transform:scale(1.1); }
    .wizard-progress .step-indicator.completed .step-circle { background:#22c55e; color:#fff; border-color:#22c55e; }
    .wizard-progress .step-label { font-size:0.7rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; transition:color 0.3s; white-space:nowrap; }
    .wizard-progress .step-indicator.active .step-label,
    .wizard-progress .step-indicator.completed .step-label { color:#1a3c8f; }

    .wizard-step { display:none; animation:stepIn 0.4s ease; }
    .wizard-step.active { display:block; }
    @keyframes stepIn { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

    .step-card { background:#fff; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.03); overflow:hidden; }
    .step-card-header { padding:1.5rem 2rem 1rem; border-bottom:1px solid #f1f5f9; }
    .step-card-header h4 { font-weight:700; color:#1a3c8f; margin:0; font-size:1.15rem; }
    .step-card-header p { color:#64748b; margin:0.25rem 0 0; font-size:0.88rem; }
    .step-card-body { padding:1.5rem 2rem 2rem; }

    .form-floating-custom { position:relative; }
    .form-floating-custom label { position:absolute; top:50%; left:12px; transform:translateY(-50%); color:#94a3b8; font-size:0.88rem; pointer-events:none; transition:all 0.2s; background:transparent; padding:0 4px; z-index:2; }
    .form-floating-custom .form-control:focus ~ label,
    .form-floating-custom .form-control:not(:placeholder-shown) ~ label,
    .form-floating-custom .form-select:valid ~ label { top:-8px; font-size:0.72rem; color:#1a3c8f; background:#fff; font-weight:600; }

    .nec-input { border:1.5px solid #e2e8f0; border-radius:10px; padding:0.65rem 0.85rem; font-size:0.92rem; transition:all 0.2s; background:#f8fafc; }
    .nec-input:focus { border-color:#1a3c8f; box-shadow:0 0 0 3px rgba(26,60,143,0.08); background:#fff; }
    .nec-input.is-invalid { border-color:#ef4444; }
    .nec-input.is-valid { border-color:#22c55e; }

    .form-label-custom { font-size:0.82rem; font-weight:600; color:#334155; margin-bottom:0.35rem; display:flex; align-items:center; gap:0.35rem; }
    .form-label-custom .required { color:#ef4444; }

    .file-upload-zone { border:2px dashed #d1d5db; border-radius:12px; padding:1.5rem; text-align:center; cursor:pointer; transition:all 0.3s; background:#f8fafc; position:relative; }
    .file-upload-zone:hover { border-color:#1a3c8f; background:rgba(26,60,143,0.02); }
    .file-upload-zone.dragover { border-color:#1a3c8f; background:rgba(26,60,143,0.05); }
    .file-upload-zone.has-file { border-color:#22c55e; background:rgba(34,197,94,0.03); border-style:solid; }
    .file-upload-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; }
    .file-upload-zone .upload-icon { font-size:1.8rem; color:#94a3b8; margin-bottom:0.5rem; }
    .file-upload-zone .upload-text { font-size:0.85rem; color:#64748b; }
    .file-upload-zone .upload-hint { font-size:0.72rem; color:#94a3b8; margin-top:0.25rem; }
    .file-upload-zone .file-name { font-size:0.82rem; color:#22c55e; font-weight:600; margin-top:0.5rem; }
    .file-upload-zone .file-remove { color:#ef4444; cursor:pointer; font-size:0.75rem; text-decoration:underline; margin-top:0.25rem; display:inline-block; }

    .photo-preview { width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid #e2e8f0; margin:0 auto 0.75rem; display:none; }
    .photo-preview.show { display:block; }

    .review-section { background:#f8fafc; border-radius:12px; padding:1.25rem; margin-bottom:1rem; border:1px solid #e2e8f0; }
    .review-section h6 { font-weight:700; color:#1a3c8f; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.75rem; padding-bottom:0.5rem; border-bottom:1px solid #e2e8f0; }
    .review-row { display:flex; padding:0.3rem 0; font-size:0.88rem; }
    .review-row .review-label { color:#64748b; min-width:160px; font-weight:500; }
    .review-row .review-value { color:#1e293b; font-weight:600; }

    .btn-nec-primary { background:linear-gradient(135deg,#1a3c8f,#0d2366); color:#fff; border:none; border-radius:10px; padding:0.65rem 1.5rem; font-weight:600; font-size:0.92rem; transition:all 0.3s; }
    .btn-nec-primary:hover { background:linear-gradient(135deg,#0d2366,#091a4d); color:#fff; transform:translateY(-1px); box-shadow:0 4px 12px rgba(26,60,143,0.25); }
    .btn-nec-primary:disabled { opacity:0.5; transform:none; box-shadow:none; cursor:not-allowed; }

    .btn-nec-outline { background:transparent; color:#1a3c8f; border:2px solid #1a3c8f; border-radius:10px; padding:0.6rem 1.5rem; font-weight:600; font-size:0.92rem; transition:all 0.3s; }
    .btn-nec-outline:hover { background:rgba(26,60,143,0.05); color:#0d2366; }

    .char-count { font-size:0.7rem; color:#94a3b8; text-align:right; margin-top:0.15rem; }

    .info-banner { background:linear-gradient(135deg,rgba(26,60,143,0.04),rgba(26,60,143,0.01)); border:1px solid rgba(26,60,143,0.1); border-radius:10px; padding:0.85rem 1rem; margin-bottom:1.5rem; display:flex; align-items:flex-start; gap:0.65rem; }
    .info-banner i { color:#1a3c8f; font-size:0.9rem; margin-top:0.1rem; flex-shrink:0; }
    .info-banner p { margin:0; font-size:0.82rem; color:#475569; line-height:1.5; }

    @media(max-width:768px) {
        .wizard-progress .step-label { display:none; }
        .wizard-progress .step-circle { width:34px; height:34px; font-size:0.78rem; }
        .step-card-body { padding:1.25rem 1rem 1.5rem; }
        .step-card-header { padding:1.25rem 1rem 0.75rem; }
        .review-row { flex-direction:column; gap:0.15rem; }
        .review-row .review-label { min-width:auto; }
    }
</style>

<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4" style="border-radius:12px;border:none;background:#dcfce7;color:#166534;">
            <i class="fas fa-check-circle me-2" style="font-size:1.2rem;"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius:12px;border:none;background:#fef2f2;color:#991b1b;">
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please correct the following errors:</strong>
            </div>
            <ul class="mb-0 mt-1 ps-3" style="font-size:0.85rem;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="wizard-progress" id="wizardProgress">
            <div class="step-indicator active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Personal</div>
            </div>
            <div class="step-indicator" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Contact</div>
            </div>
            <div class="step-indicator" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Professional</div>
            </div>
            <div class="step-indicator" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Observer</div>
            </div>
            <div class="step-indicator" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Documents</div>
            </div>
            <div class="step-indicator" data-step="6">
                <div class="step-circle">6</div>
                <div class="step-label">Review</div>
            </div>
        </div>

        <form method="POST" action="{{ route('observers.apply.submit') }}" enctype="multipart/form-data" id="observerForm" novalidate>
            @csrf

            {{-- STEP 1: Personal Information --}}
            <div class="wizard-step active" data-step="1">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-user-circle me-2"></i>Personal Information</h4>
                        <p>Provide your basic personal details as they appear on your official identification.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="info-banner">
                            <i class="fas fa-info-circle"></i>
                            <p>All fields marked with <span class="text-danger fw-bold">*</span> are required. Ensure your name matches your national ID or passport exactly.</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label-custom">Title</label>
                                <select name="title" class="form-select nec-input @error('title') is-invalid @enderror">
                                    <option value="">Select</option>
                                    <option value="Mr" {{ old('title') === 'Mr' ? 'selected' : '' }}>Mr</option>
                                    <option value="Mrs" {{ old('title') === 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                    <option value="Ms" {{ old('title') === 'Ms' ? 'selected' : '' }}>Ms</option>
                                    <option value="Dr" {{ old('title') === 'Dr' ? 'selected' : '' }}>Dr</option>
                                    <option value="Prof" {{ old('title') === 'Prof' ? 'selected' : '' }}>Prof</option>
                                    <option value="Hon" {{ old('title') === 'Hon' ? 'selected' : '' }}>Hon</option>
                                    <option value="Other" {{ old('title') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-custom">First Name <span class="required">*</span></label>
                                <input type="text" name="first_name" class="form-control nec-input @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="e.g. John" maxlength="100" required>
                                @error('first_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Last Name <span class="required">*</span></label>
                                <input type="text" name="last_name" class="form-control nec-input @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="e.g. Doe" maxlength="100" required>
                                @error('last_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Other Names</label>
                                <input type="text" name="other_names" class="form-control nec-input @error('other_names') is-invalid @enderror" value="{{ old('other_names') }}" placeholder="e.g. Michael" maxlength="150">
                                @error('other_names')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Gender <span class="required">*</span></label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check flex-fill" style="background:{{ old('gender') === 'male' ? 'rgba(26,60,143,0.06)' : '#f8fafc' }};border:1.5px solid {{ old('gender') === 'male' ? '#1a3c8f' : '#e2e8f0' }};border-radius:10px;padding:0.65rem 0.85rem;transition:all 0.2s;cursor:pointer;" onclick="selectGender(this,'male')">
                                        <input class="form-check-input d-none" type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }} required>
                                        <i class="fas fa-mars text-primary me-2"></i> <span class="fw-semibold" style="font-size:0.9rem;">Male</span>
                                    </div>
                                    <div class="form-check flex-fill" style="background:{{ old('gender') === 'female' ? 'rgba(26,60,143,0.06)' : '#f8fafc' }};border:1.5px solid {{ old('gender') === 'female' ? '#1a3c8f' : '#e2e8f0' }};border-radius:10px;padding:0.65rem 0.85rem;transition:all 0.2s;cursor:pointer;" onclick="selectGender(this,'female')">
                                        <input class="form-check-input d-none" type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }}>
                                        <i class="fas fa-venus text-danger me-2"></i> <span class="fw-semibold" style="font-size:0.9rem;">Female</span>
                                    </div>
                                </div>
                                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Date of Birth <span class="required">*</span></label>
                                <input type="date" name="dob" class="form-control nec-input @error('dob') is-invalid @enderror" value="{{ old('dob') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                                @error('dob')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Nationality <span class="required">*</span></label>
                                <input type="text" name="nationality" class="form-control nec-input @error('nationality') is-invalid @enderror" value="{{ old('nationality', 'South Sudanese') }}" maxlength="100" required>
                                @error('nationality')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">National ID / Passport No. <span class="required">*</span></label>
                                <input type="text" name="national_id" class="form-control nec-input @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}" placeholder="e.g. SS-12345678" maxlength="100" required>
                                @error('national_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-nec-primary" onclick="nextStep(1)">Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 2: Contact & Address --}}
            <div class="wizard-step" data-step="2">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-address-card me-2"></i>Contact & Address</h4>
                        <p>How can we reach you? Provide your current contact details and emergency contact.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Email Address <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text nec-input" style="border-right:none;background:#f1f5f9;"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control nec-input @error('email') is-invalid @enderror" style="border-left:none;" value="{{ old('email') }}" placeholder="you@example.com" maxlength="255" required>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Phone Number <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text nec-input" style="border-right:none;background:#f1f5f9;"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="tel" name="phone" class="form-control nec-input @error('phone') is-invalid @enderror" style="border-left:none;" value="{{ old('phone', '+211') }}" placeholder="+211XXXXXXXXX" maxlength="20" required>
                                </div>
                                <small class="text-muted">Include country code (e.g. +211 for South Sudan)</small>
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Residential Address</label>
                                <textarea name="residential_address" class="form-control nec-input @error('residential_address') is-invalid @enderror" rows="2" maxlength="500" placeholder="Street address, area, city">{{ old('residential_address') }}</textarea>
                                <div class="char-count"><span id="addrCount">0</span>/500</div>
                                @error('residential_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Postal Address</label>
                                <input type="text" name="postal_address" class="form-control nec-input @error('postal_address') is-invalid @enderror" value="{{ old('postal_address') }}" placeholder="P.O. Box, City" maxlength="255">
                                @error('postal_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12"><hr class="my-2"><h6 class="fw-bold text-muted small text-uppercase" style="letter-spacing:0.5px;">Emergency Contact</h6></div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control nec-input @error('emergency_contact_name') is-invalid @enderror" value="{{ old('emergency_contact_name') }}" placeholder="Full name" maxlength="200">
                                @error('emergency_contact_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Contact Phone</label>
                                <input type="tel" name="emergency_contact_phone" class="form-control nec-input @error('emergency_contact_phone') is-invalid @enderror" value="{{ old('emergency_contact_phone') }}" placeholder="+211XXXXXXXXX" maxlength="20">
                                @error('emergency_contact_phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-nec-outline" onclick="prevStep(2)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                    <button type="button" class="btn btn-nec-primary" onclick="nextStep(2)">Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 3: Professional Details --}}
            <div class="wizard-step" data-step="3">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-briefcase me-2"></i>Professional Details</h4>
                        <p>Tell us about your professional background and language abilities.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Employer / Organization</label>
                                <input type="text" name="employer" class="form-control nec-input @error('employer') is-invalid @enderror" value="{{ old('employer') }}" placeholder="Current employer or 'Self-employed'" maxlength="255">
                                @error('employer')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Job Title / Position</label>
                                <input type="text" name="job_title" class="form-control nec-input @error('job_title') is-invalid @enderror" value="{{ old('job_title') }}" placeholder="e.g. Programme Officer" maxlength="255">
                                @error('job_title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Years of Experience</label>
                                <input type="text" name="employment_duration" class="form-control nec-input @error('employment_duration') is-invalid @enderror" value="{{ old('employment_duration') }}" placeholder="e.g. 5 years" maxlength="100">
                                @error('employment_duration')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Languages Spoken</label>
                                <input type="text" name="languages" class="form-control nec-input @error('languages') is-invalid @enderror" value="{{ old('languages') }}" placeholder="e.g. English, Arabic, Dinka" maxlength="500">
                                <small class="text-muted">Separate multiple languages with commas</small>
                                @error('languages')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-nec-outline" onclick="prevStep(3)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                    <button type="button" class="btn btn-nec-primary" onclick="nextStep(3)">Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 4: Observer Details --}}
            <div class="wizard-step" data-step="4">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-binoculars me-2"></i>Observer Details</h4>
                        <p>Select your observer type and provide organizational information if applicable.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom">Observer Type <span class="required">*</span></label>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="observer-type-card p-3 text-center rounded-3" style="border:2px solid {{ old('observer_type') === 'domestic' ? '#1a3c8f' : '#e2e8f0' }};background:{{ old('observer_type') === 'domestic' ? 'rgba(26,60,143,0.04)' : '#f8fafc' }};cursor:pointer;transition:all 0.2s;" onclick="selectObserverType(this,'domestic')">
                                            <input type="radio" name="observer_type" value="domestic" class="d-none" {{ old('observer_type') === 'domestic' ? 'checked' : '' }} required>
                                            <i class="fas fa-flag text-primary mb-2" style="font-size:1.5rem;"></i>
                                            <div class="fw-bold" style="font-size:0.88rem;">Domestic</div>
                                            <div class="text-muted" style="font-size:0.72rem;">Local CSO / Media / Party</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="observer-type-card p-3 text-center rounded-3" style="border:2px solid {{ old('observer_type') === 'international' ? '#1a3c8f' : '#e2e8f0' }};background:{{ old('observer_type') === 'international' ? 'rgba(26,60,143,0.04)' : '#f8fafc' }};cursor:pointer;transition:all 0.2s;" onclick="selectObserverType(this,'international')">
                                            <input type="radio" name="observer_type" value="international" class="d-none" {{ old('observer_type') === 'international' ? 'checked' : '' }}>
                                            <i class="fas fa-globe text-success mb-2" style="font-size:1.5rem;"></i>
                                            <div class="fw-bold" style="font-size:0.88rem;">International</div>
                                            <div class="text-muted" style="font-size:0.72rem;">Foreign Org / Embassy</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="observer-type-card p-3 text-center rounded-3" style="border:2px solid {{ old('observer_type') === 'regional' ? '#1a3c8f' : '#e2e8f0' }};background:{{ old('observer_type') === 'regional' ? 'rgba(26,60,143,0.04)' : '#f8fafc' }};cursor:pointer;transition:all 0.2s;" onclick="selectObserverType(this,'regional')">
                                            <input type="radio" name="observer_type" value="regional" class="d-none" {{ old('observer_type') === 'regional' ? 'checked' : '' }}>
                                            <i class="fas fa-earth-africa text-warning mb-2" style="font-size:1.5rem;"></i>
                                            <div class="fw-bold" style="font-size:0.88rem;">Regional</div>
                                            <div class="text-muted" style="font-size:0.72rem;">AU / IGAD / Regional Body</div>
                                        </div>
                                    </div>
                                </div>
                                @error('observer_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Number of Observers</label>
                                <input type="number" name="observer_count" class="form-control nec-input @error('observer_count') is-invalid @enderror" value="{{ old('observer_count', 1) }}" min="1" max="100">
                                @error('observer_count')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12" id="orgFields">
                                <h6 class="fw-bold text-muted small text-uppercase mb-3" style="letter-spacing:0.5px;">Organization Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Organization Name</label>
                                        <input type="text" name="organization_name" class="form-control nec-input @error('organization_name') is-invalid @enderror" value="{{ old('organization_name') }}" maxlength="255">
                                        @error('organization_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Registration Number</label>
                                        <input type="text" name="organization_registration" class="form-control nec-input @error('organization_registration') is-invalid @enderror" value="{{ old('organization_registration') }}" maxlength="100">
                                        @error('organization_registration')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label-custom">Organization Address</label>
                                        <input type="text" name="org_address" class="form-control nec-input @error('org_address') is-invalid @enderror" value="{{ old('org_address') }}" maxlength="500" placeholder="Full address of the organization">
                                        @error('org_address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Sponsoring Organization</label>
                                        <input type="text" name="sponsoring_org" class="form-control nec-input @error('sponsoring_org') is-invalid @enderror" value="{{ old('sponsoring_org') }}" maxlength="255" placeholder="If different from above">
                                        @error('sponsoring_org')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <h6 class="fw-bold text-muted small text-uppercase mb-3" style="letter-spacing:0.5px;">Deployment & Experience</h6>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Preferred Deployment Areas</label>
                                <input type="text" name="deployment_areas" class="form-control nec-input @error('deployment_areas') is-invalid @enderror" value="{{ old('deployment_areas') }}" maxlength="500" placeholder="e.g. Juba, Wau, Bor (comma-separated)">
                                <small class="text-muted">States or counties where you prefer to be deployed</small>
                                @error('deployment_areas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Previous Observation Missions</label>
                                <textarea name="previous_missions" class="form-control nec-input @error('previous_missions') is-invalid @enderror" rows="3" maxlength="1000" placeholder="List any previous election observation missions you have participated in...">{{ old('previous_missions') }}</textarea>
                                <div class="char-count"><span id="prevCount">0</span>/1000</div>
                                @error('previous_missions')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Election Experience</label>
                                <textarea name="election_experience" class="form-control nec-input @error('election_experience') is-invalid @enderror" rows="3" maxlength="1000" placeholder="Describe your experience with elections, democracy, governance...">{{ old('election_experience') }}</textarea>
                                <div class="char-count"><span id="expCount">0</span>/1000</div>
                                @error('election_experience')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-nec-outline" onclick="prevStep(4)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                    <button type="button" class="btn btn-nec-primary" onclick="nextStep(4)">Continue <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 5: Documents --}}
            <div class="wizard-step" data-step="5">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-file-arrow-up me-2"></i>Document Uploads</h4>
                        <p>Upload supporting documents. All files are optional but recommended for faster processing.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="info-banner">
                            <i class="fas fa-shield-halved"></i>
                            <p>Documents are encrypted and stored securely. Accepted formats: <strong>JPG, PNG, PDF, DOC, DOCX</strong>. Maximum file size: <strong>2MB</strong> for images, <strong>5MB</strong> for documents.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label-custom">Passport / ID Photo <span class="text-muted fw-normal">(Recommended)</span></label>
                                <div class="file-upload-zone" id="photoZone">
                                    <input type="file" name="passport_photo" accept=".jpg,.jpeg,.png" onchange="handleFileSelect(this, 'photoZone', 'photoPreview')">
                                    <img class="photo-preview" id="photoPreview">
                                    <div class="upload-icon"><i class="fas fa-camera"></i></div>
                                    <div class="upload-text fw-semibold">Click or drag to upload photo</div>
                                    <div class="upload-hint">JPG or PNG, max 2MB</div>
                                </div>
                                @error('passport_photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">CV / Biography <span class="text-muted fw-normal">(Recommended)</span></label>
                                <div class="file-upload-zone" id="cvZone">
                                    <input type="file" name="cv_biography" accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 'cvZone', null)">
                                    <div class="upload-icon"><i class="fas fa-file-lines"></i></div>
                                    <div class="upload-text fw-semibold">Click or drag to upload CV</div>
                                    <div class="upload-hint">PDF, DOC or DOCX, max 5MB</div>
                                </div>
                                @error('cv_biography')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Letter of Appointment <span class="text-muted fw-normal">(If applicable)</span></label>
                                <div class="file-upload-zone" id="letterZone">
                                    <input type="file" name="letter_of_appointment" accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 'letterZone', null)">
                                    <div class="upload-icon"><i class="fas fa-envelope-open-text"></i></div>
                                    <div class="upload-text fw-semibold">Click or drag to upload letter</div>
                                    <div class="upload-hint">PDF, DOC or DOCX, max 5MB</div>
                                </div>
                                @error('letter_of_appointment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Proof of Registration <span class="text-muted fw-normal">(If applicable)</span></label>
                                <div class="file-upload-zone" id="proofZone">
                                    <input type="file" name="proof_registration" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelect(this, 'proofZone', null)">
                                    <div class="upload-icon"><i class="fas fa-stamp"></i></div>
                                    <div class="upload-text fw-semibold">Click or drag to upload proof</div>
                                    <div class="upload-hint">PDF, JPG or PNG, max 2MB</div>
                                </div>
                                @error('proof_registration')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-nec-outline" onclick="prevStep(5)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                    <button type="button" class="btn btn-nec-primary" onclick="nextStep(5)">Review Application <i class="fas fa-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 6: Review & Submit --}}
            <div class="wizard-step" data-step="6">
                <div class="step-card">
                    <div class="step-card-header">
                        <h4><i class="fas fa-clipboard-check me-2"></i>Review & Submit</h4>
                        <p>Please review your application carefully before submitting. You can go back to any step to make changes.</p>
                    </div>
                    <div class="step-card-body">
                        <div class="review-section" id="reviewPersonal">
                            <h6><i class="fas fa-user me-2"></i>Personal Information</h6>
                            <div class="review-row"><span class="review-label">Name:</span><span class="review-value" id="rvName">-</span></div>
                            <div class="review-row"><span class="review-label">Gender:</span><span class="review-value" id="rvGender">-</span></div>
                            <div class="review-row"><span class="review-label">Date of Birth:</span><span class="review-value" id="rvDob">-</span></div>
                            <div class="review-row"><span class="review-label">Nationality:</span><span class="review-value" id="rvNationality">-</span></div>
                            <div class="review-row"><span class="review-label">National ID:</span><span class="review-value" id="rvNatId">-</span></div>
                        </div>

                        <div class="review-section" id="reviewContact">
                            <h6><i class="fas fa-address-card me-2"></i>Contact Details</h6>
                            <div class="review-row"><span class="review-label">Email:</span><span class="review-value" id="rvEmail">-</span></div>
                            <div class="review-row"><span class="review-label">Phone:</span><span class="review-value" id="rvPhone">-</span></div>
                            <div class="review-row"><span class="review-label">Address:</span><span class="review-value" id="rvAddress">-</span></div>
                            <div class="review-row"><span class="review-label">Emergency:</span><span class="review-value" id="rvEmergency">-</span></div>
                        </div>

                        <div class="review-section" id="reviewProfessional">
                            <h6><i class="fas fa-briefcase me-2"></i>Professional Details</h6>
                            <div class="review-row"><span class="review-label">Employer:</span><span class="review-value" id="rvEmployer">-</span></div>
                            <div class="review-row"><span class="review-label">Job Title:</span><span class="review-value" id="rvJobTitle">-</span></div>
                            <div class="review-row"><span class="review-label">Experience:</span><span class="review-value" id="rvExp">-</span></div>
                            <div class="review-row"><span class="review-label">Languages:</span><span class="review-value" id="rvLang">-</span></div>
                        </div>

                        <div class="review-section" id="reviewObserver">
                            <h6><i class="fas fa-binoculars me-2"></i>Observer Details</h6>
                            <div class="review-row"><span class="review-label">Type:</span><span class="review-value" id="rvType">-</span></div>
                            <div class="review-row"><span class="review-label">Organization:</span><span class="review-value" id="rvOrg">-</span></div>
                            <div class="review-row"><span class="review-label">Observers:</span><span class="review-value" id="rvCount">-</span></div>
                            <div class="review-row"><span class="review-label">Deployment:</span><span class="review-value" id="rvDeploy">-</span></div>
                        </div>

                        <div class="review-section" id="reviewDocs">
                            <h6><i class="fas fa-file me-2"></i>Uploaded Documents</h6>
                            <div class="review-row"><span class="review-label">Photo:</span><span class="review-value" id="rvPhoto">Not uploaded</span></div>
                            <div class="review-row"><span class="review-label">CV:</span><span class="review-value" id="rvCv">Not uploaded</span></div>
                            <div class="review-row"><span class="review-label">Letter:</span><span class="review-value" id="rvLetter">Not uploaded</span></div>
                            <div class="review-row"><span class="review-label">Proof:</span><span class="review-value" id="rvProof">Not uploaded</span></div>
                        </div>

                        <div class="mt-4 p-4" style="background:#fffbeb;border:2px solid #fbbf24;border-radius:12px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agreeCode" name="agree_code" style="width:1.3em;height:1.3em;accent-color:#1a3c8f;" {{ old('agree_code') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="agreeCode" style="font-size:0.92rem;">
                                    I solemnly affirm that the information provided is true and accurate to the best of my knowledge. I agree to abide by the <a href="#" data-bs-toggle="modal" data-bs-target="#codeModal" class="text-primary text-decoration-underline">NEC Code of Conduct for Election Observers</a> and understand that accreditation may be revoked for violations. <span class="text-danger">*</span>
                                </label>
                            </div>
                            @error('agree_code')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-nec-outline" onclick="prevStep(6)"><i class="fas fa-arrow-left me-2"></i> Back</button>
                    <button type="submit" class="btn btn-nec-primary px-4" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Submit Application
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Code of Conduct Modal --}}
<div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a3c8f,#0d2366);border-radius:16px 16px 0 0;">
                <h5 class="modal-title text-white fw-bold" id="codeModalLabel"><i class="fas fa-scroll me-2"></i>NEC Code of Conduct for Election Observers</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-size:0.9rem;line-height:1.7;color:#334155;">
                <p>Election observers accredited by the National Elections Commission of South Sudan (NEC) are expected to adhere to the following principles:</p>
                <ol>
                    <li><strong>Impartiality:</strong> Observers must maintain strict neutrality and impartiality throughout the electoral process.</li>
                    <li><strong>Independence:</strong> Observers shall not interfere with the electoral process, voters, or election officials.</li>
                    <li><strong>Professionalism:</strong> Observers must conduct themselves in a professional and courteous manner at all times.</li>
                    <li><strong>Objectivity:</strong> Reports and statements must be factual, objective, and based solely on direct observation.</li>
                    <li><strong>Non-partisanship:</strong> Observers must not engage in, support, or promote any political party or candidate.</li>
                    <li><strong>Confidentiality:</strong> Observer must respect the secrecy of the ballot and voter privacy.</li>
                    <li><strong>Compliance:</strong> Observers must comply with all laws, regulations, and guidelines issued by NEC.</li>
                    <li><strong>Reporting:</strong> Observers must submit timely and accurate observation reports to NEC.</li>
                    <li><strong>Identification:</strong> Observers must wear their official accreditation credentials at all times during observation.</li>
                    <li><strong>Non-violence:</strong> Observers must not engage in or encourage any form of violence or intimidation.</li>
                </ol>
                <p class="mb-0">Violation of this Code of Conduct may result in the revocation of accreditation and potential legal consequences.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background:#1a3c8f;border:none;border-radius:8px;">I Understand</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    let currentStep = 1;
    const totalSteps = 6;

    const stepFields = {
        1: ['first_name','last_name','gender','dob','nationality','national_id'],
        2: ['email','phone'],
        3: [],
        4: ['observer_type'],
        5: [],
        6: ['agree_code']
    };

    const stepLabels = {
        1: 'Personal Information',
        2: 'Contact Details',
        3: 'Professional Details',
        4: 'Observer Details',
        5: 'Documents',
        6: 'Review & Submit'
    };

    window.nextStep = function(from) {
        if (from < totalSteps) {
            if (!validateStep(from)) return;
            showStep(from + 1);
            if (from + 1 === 6) populateReview();
        }
    };

    window.prevStep = function(from) {
        if (from > 1) showStep(from - 1);
    };

    function showStep(n) {
        document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
        document.querySelector(`.wizard-step[data-step="${n}"]`).classList.add('active');

        document.querySelectorAll('.wizard-progress .step-indicator').forEach(ind => {
            const s = parseInt(ind.dataset.step);
            ind.classList.remove('active','completed');
            if (s < n) ind.classList.add('completed');
            else if (s === n) ind.classList.add('active');
        });

        currentStep = n;
        window.scrollTo({top:document.querySelector('.wizard-progress').offsetTop - 80, behavior:'smooth'});
    }

    function validateStep(n) {
        const fields = stepFields[n] || [];
        let valid = true;
        let firstInvalid = null;

        fields.forEach(name => {
            const el = document.querySelector(`[name="${name}"]`);
            if (!el) return;

            if (name === 'agree_code') {
                if (!el.checked) {
                    valid = false;
                    el.closest('.form-check, .mt-4').style.outline = '2px solid #ef4444';
                    el.closest('.form-check, .mt-4').style.outlineOffset = '2px';
                    el.closest('.form-check, .mt-4').style.borderRadius = '12px';
                    if (!firstInvalid) firstInvalid = el;
                } else {
                    el.closest('.form-check, .mt-4').style.outline = '';
                }
                return;
            }

            if (el.tagName === 'SELECT') {
                if (!el.value) {
                    valid = false;
                    el.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = el;
                } else {
                    el.classList.remove('is-invalid');
                    el.classList.add('is-valid');
                }
            } else {
                if (!el.value.trim()) {
                    valid = false;
                    el.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = el;
                } else {
                    el.classList.remove('is-invalid');
                    el.classList.add('is-valid');
                }
            }
        });

        if (!valid && firstInvalid) {
            firstInvalid.focus();
            showToast(`Please complete ${stepLabels[n]} before continuing.`, 'warning');
        }
        return valid;
    }

    function showToast(msg, type) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({icon:type, title:'Attention', text:msg, confirmButtonColor:'#1a3c8f', timer:3000, timerProgressBar:true});
        } else {
            alert(msg);
        }
    }

    function populateReview() {
        const v = n => document.querySelector(`[name="${n}"]`)?.value || '';
        const title = v('title');
        const fName = v('first_name');
        const oNames = v('other_names');
        const lName = v('last_name');
        const fullName = [title, fName, oNames, lName].filter(Boolean).join(' ');
        document.getElementById('rvName').textContent = fullName || '-';
        document.getElementById('rvGender').textContent = v('gender') === 'male' ? 'Male' : v('gender') === 'female' ? 'Female' : '-';
        const dob = v('dob');
        document.getElementById('rvDob').textContent = dob ? new Date(dob).toLocaleDateString('en-GB',{day:'2-digit',month:'long',year:'numeric'}) : '-';
        document.getElementById('rvNationality').textContent = v('nationality') || '-';
        document.getElementById('rvNatId').textContent = v('national_id') || '-';
        document.getElementById('rvEmail').textContent = v('email') || '-';
        document.getElementById('rvPhone').textContent = v('phone') || '-';
        document.getElementById('rvAddress').textContent = v('residential_address') || v('postal_address') || '-';
        const ec = [v('emergency_contact_name'), v('emergency_contact_phone')].filter(Boolean).join(' / ');
        document.getElementById('rvEmergency').textContent = ec || '-';
        document.getElementById('rvEmployer').textContent = v('employer') || '-';
        document.getElementById('rvJobTitle').textContent = v('job_title') || '-';
        document.getElementById('rvExp').textContent = v('employment_duration') || '-';
        document.getElementById('rvLang').textContent = v('languages') || '-';

        const typeMap = {domestic:'Domestic Observer',international:'International Observer',regional:'Regional Observer'};
        document.getElementById('rvType').textContent = typeMap[v('observer_type')] || '-';
        document.getElementById('rvOrg').textContent = v('organization_name') || '-';
        document.getElementById('rvCount').textContent = v('observer_count') || '1';
        document.getElementById('rvDeploy').textContent = v('deployment_areas') || '-';

        document.querySelectorAll('.file-upload-zone').forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            const name = input?.name;
            const rvMap = {passport_photo:'rvPhoto',cv_biography:'rvCv',letter_of_appointment:'rvLetter',proof_registration:'rvProof'};
            if (rvMap[name]) {
                document.getElementById(rvMap[name]).textContent = input.files.length ? input.files[0].name : 'Not uploaded';
                document.getElementById(rvMap[name]).style.color = input.files.length ? '#22c55e' : '#94a3b8';
            }
        });
    }

    /* Gender radio card selection */
    window.selectGender = function(el, val) {
        el.querySelector('input').checked = true;
        document.querySelectorAll('[name="gender"]').forEach(r => {
            const card = r.closest('.form-check');
            if (card) {
                card.style.background = r.checked ? 'rgba(26,60,143,0.06)' : '#f8fafc';
                card.style.borderColor = r.checked ? '#1a3c8f' : '#e2e8f0';
            }
        });
    };

    /* Observer type card selection */
    window.selectObserverType = function(el, val) {
        el.querySelector('input').checked = true;
        document.querySelectorAll('.observer-type-card').forEach(card => {
            const input = card.querySelector('input');
            card.style.borderColor = input.checked ? '#1a3c8f' : '#e2e8f0';
            card.style.background = input.checked ? 'rgba(26,60,143,0.04)' : '#f8fafc';
        });
    };

    /* File upload handling */
    window.handleFileSelect = function(input, zoneId, previewId) {
        const zone = document.getElementById(zoneId);
        if (input.files.length) {
            zone.classList.add('has-file');
            const existing = zone.querySelector('.file-name');
            if (existing) existing.remove();
            const removeBtn = zone.querySelector('.file-remove');
            if (removeBtn) removeBtn.remove();

            const fileName = document.createElement('div');
            fileName.className = 'file-name';
            fileName.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + input.files[0].name;

            const removeLink = document.createElement('span');
            removeLink.className = 'file-remove';
            removeLink.textContent = 'Remove';
            removeLink.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                input.value = '';
                zone.classList.remove('has-file');
                fileName.remove();
                removeLink.remove();
                if (previewId) document.getElementById(previewId).classList.remove('show');
            };

            zone.appendChild(fileName);
            zone.appendChild(removeLink);

            if (previewId && input.files[0].type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(previewId);
                    img.src = e.target.result;
                    img.classList.add('show');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    };

    /* Drag & drop visual */
    document.querySelectorAll('.file-upload-zone').forEach(zone => {
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
        zone.addEventListener('drop', () => zone.classList.remove('dragover'));
    });

    /* Character counters */
    document.querySelectorAll('textarea[maxlength]').forEach(ta => {
        const counter = ta.parentElement.querySelector('.char-count span');
        if (counter) {
            ta.addEventListener('input', () => counter.textContent = ta.value.length);
            counter.textContent = ta.value.length;
        }
    });

    /* Live validation feedback */
    document.querySelectorAll('.nec-input[required], .nec-input[name="email"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.type === 'email' && this.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    /* Submit handler */
    document.getElementById('observerForm').addEventListener('submit', function(e) {
        if (!validateStep(6)) {
            e.preventDefault();
            return;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
    });

    /* Init phone field */
    const phoneInput = document.querySelector('[name="phone"]');
    if (phoneInput && !phoneInput.value) phoneInput.value = '+211';
})();
</script>
@endpush
