@extends('layouts.app')

@section('title', 'Voter Registration - National Elections Commission')

@push('styles')
<style>
    :root {
        --reg-green: #2E8B57;
        --reg-green-light: #e8f5ee;
        --reg-green-dark: #1a5c3a;
        --reg-gold: #D4AF37;
        --reg-blue: #1a3c8f;
        --reg-red: #c0392b;
        --reg-gray-50: #f8f9fc;
        --reg-gray-100: #f1f3f7;
        --reg-gray-200: #e2e6ee;
        --reg-gray-300: #cdd3de;
        --reg-gray-400: #9aa3b4;
        --reg-gray-600: #5a6478;
        --reg-gray-800: #2d3748;
        --reg-shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
        --reg-shadow-md: 0 4px 16px rgba(0,0,0,0.08);
        --reg-shadow-lg: 0 8px 32px rgba(0,0,0,0.1);
        --reg-shadow-xl: 0 12px 48px rgba(0,0,0,0.12);
        --reg-radius: 14px;
        --reg-radius-sm: 10px;
        --reg-radius-xs: 7px;
        --reg-transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
    }

    /* ─── HERO ───────────────────────────────────────────────── */
    .reg-hero {
        background: linear-gradient(135deg, var(--reg-green-dark) 0%, var(--reg-green) 50%, #3da86a 100%);
        color: #fff; padding: 2.5rem 0 3.5rem; text-align: center;
        position: relative; overflow: hidden;
    }
    .reg-hero::before {
        content: ''; position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .reg-hero h1 { font-family: 'Poppins', sans-serif; font-size: 1.85rem; font-weight: 800; position: relative; }
    .reg-hero p { font-size: 1rem; opacity: 0.9; position: relative; }
    .reg-hero .hero-badges { display: flex; justify-content: center; gap: 0.75rem; margin-top: 1rem; flex-wrap: wrap; position: relative; }
    .reg-hero .hero-badge {
        background: rgba(255,255,255,0.15); backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.2); border-radius: 50px;
        padding: 0.35rem 0.9rem; font-size: 0.78rem; font-weight: 500;
        display: inline-flex; align-items: center; gap: 0.35rem;
    }

    /* ─── MAIN CARD ──────────────────────────────────────────── */
    .reg-card {
        background: #fff; border-radius: 20px; box-shadow: var(--reg-shadow-lg);
        max-width: 960px; margin: -2.5rem auto 3rem; position: relative; z-index: 2;
        overflow: hidden; border: 1px solid rgba(0,0,0,0.04);
    }
    .reg-card-inner { padding: 2rem 2.5rem 2.5rem; }

    /* ─── STEPPER ────────────────────────────────────────────── */
    .stepper { display: flex; align-items: flex-start; justify-content: center; padding: 1.5rem 0 0; }
    .stepper-step { display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; }
    .stepper-circle {
        width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; background: var(--reg-gray-100); color: var(--reg-gray-400);
        border: 2.5px solid var(--reg-gray-200); transition: var(--reg-transition); position: relative;
    }
    .stepper-circle .check-icon { display: none; }
    .stepper-step.active .stepper-circle {
        background: var(--reg-green); color: #fff; border-color: var(--reg-green);
        box-shadow: 0 0 0 5px rgba(46,139,87,0.15);
    }
    .stepper-step.done .stepper-circle {
        background: #27ae60; color: #fff; border-color: #27ae60;
    }
    .stepper-step.done .stepper-circle .step-num { display: none; }
    .stepper-step.done .stepper-circle .check-icon { display: block; }
    .stepper-label {
        margin-top: 0.5rem; font-size: 0.72rem; font-weight: 600; color: var(--reg-gray-400);
        text-transform: uppercase; letter-spacing: 0.5px; text-align: center; transition: var(--reg-transition);
    }
    .stepper-step.active .stepper-label { color: var(--reg-green); }
    .stepper-step.done .stepper-label { color: #27ae60; }
    .stepper-line {
        flex: 1; height: 3px; background: var(--reg-gray-200); margin: 20px -8px 0;
        border-radius: 2px; transition: background 0.4s; min-width: 50px; max-width: 90px; position: relative; z-index: 1;
    }
    .stepper-line.done { background: #27ae60; }

    /* ─── PROGRESS BAR ───────────────────────────────────────── */
    .progress-wrap { padding: 0.5rem 2.5rem; }
    .progress-track { height: 5px; background: var(--reg-gray-100); border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--reg-green), #27ae60); border-radius: 3px; transition: width 0.5s cubic-bezier(0.4,0,0.2,1); }
    .progress-text { text-align: right; font-size: 0.72rem; font-weight: 600; color: var(--reg-green); margin-top: 0.3rem; }

    /* ─── FORM SECTIONS ──────────────────────────────────────── */
    .form-section { display: none; animation: sectionIn 0.45s cubic-bezier(0.4,0,0.2,1); }
    .form-section.active { display: block; }
    @keyframes sectionIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    .section-header {
        display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem;
        padding-bottom: 1rem; border-bottom: 2px solid var(--reg-gray-100);
    }
    .section-icon {
        width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .section-icon.green { background: var(--reg-green-light); color: var(--reg-green); }
    .section-icon.blue { background: #e8eef8; color: var(--reg-blue); }
    .section-icon.gold { background: #fdf6e3; color: #b8941f; }
    .section-icon.teal { background: #e0f2f1; color: #00796b; }
    .section-title-text h3 { font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--reg-gray-800); margin: 0; }
    .section-title-text p { font-size: 0.82rem; color: var(--reg-gray-400); margin: 0.15rem 0 0; }

    /* ─── FORM FIELDS ────────────────────────────────────────── */
    .field-group { margin-bottom: 1.15rem; }
    .field-label {
        display: flex; align-items: center; gap: 0.35rem;
        font-weight: 600; font-size: 0.85rem; color: var(--reg-gray-800); margin-bottom: 0.4rem;
    }
    .field-label .req { color: var(--reg-red); font-weight: 700; }
    .field-label .optional { color: var(--reg-gray-400); font-weight: 400; font-size: 0.78rem; }
    .field-input-wrap { position: relative; }
    .field-input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--reg-gray-400); font-size: 0.95rem; z-index: 5; pointer-events: none;
    }
    .field-input-wrap .form-control,
    .field-input-wrap .form-select {
        padding-left: 2.5rem; border-radius: var(--reg-radius-sm); border: 1.5px solid var(--reg-gray-200);
        padding: 0.7rem 1rem 0.7rem 2.6rem; font-size: 0.92rem; transition: var(--reg-transition);
        background: var(--reg-gray-50); height: 46px;
    }
    .field-input-wrap .form-control:focus,
    .field-input-wrap .form-select:focus {
        border-color: var(--reg-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1); background: #fff;
    }
    .field-input-wrap .form-select { padding-right: 2.5rem; }
    .field-input-wrap .status-icon {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem;
    }
    .field-hint { font-size: 0.76rem; color: var(--reg-gray-400); margin-top: 0.3rem; display: flex; align-items: center; gap: 0.3rem; }
    .field-hint i { font-size: 0.7rem; }

    /* ─── REG TYPE CARDS ─────────────────────────────────────── */
    .reg-type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .reg-type-card {
        border: 2px solid var(--reg-gray-200); border-radius: var(--reg-radius); padding: 1.5rem 1.25rem;
        text-align: center; cursor: pointer; transition: var(--reg-transition); position: relative;
        background: var(--reg-gray-50);
    }
    .reg-type-card:hover { border-color: var(--reg-green); background: #fff; transform: translateY(-2px); box-shadow: var(--reg-shadow-md); }
    .reg-type-card.selected {
        border-color: var(--reg-green); background: #fff;
        box-shadow: 0 0 0 3px rgba(46,139,87,0.12), var(--reg-shadow-md);
    }
    .reg-type-card.selected::after {
        content: '\f26a'; font-family: 'bootstrap-icons'; position: absolute; top: 10px; right: 12px;
        color: var(--reg-green); font-size: 1.1rem;
    }
    .reg-type-icon {
        width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 0.75rem; font-size: 1.5rem; transition: var(--reg-transition);
    }
    .reg-type-card.selected .reg-type-icon { transform: scale(1.08); }
    .reg-type-card[data-type="self"] .reg-type-icon { background: #e8f5ee; }
    .reg-type-card[data-type="agent"] .reg-type-icon { background: #e8eef8; }
    .reg-type-title { font-weight: 700; font-size: 0.95rem; color: var(--reg-gray-800); margin-bottom: 0.25rem; }
    .reg-type-desc { font-size: 0.82rem; color: var(--reg-gray-400); }

    .agent-panel {
        display: none; margin-top: 1rem; padding: 1.25rem; background: var(--reg-gray-50);
        border-radius: var(--reg-radius-sm); border: 1px solid var(--reg-gray-200);
    }
    .agent-panel.show { display: block; animation: sectionIn 0.3s; }
    .agent-panel .field-input-wrap .form-control,
    .agent-panel .field-input-wrap .form-select { background: #fff; }

    /* ─── DIASPORA PANEL ─────────────────────────────────────── */
    .diaspora-panel {
        display: none; margin-bottom: 1.4rem; padding: 1.4rem 1.4rem 1rem;
        background: linear-gradient(180deg, #f3f7ff, #f8fbff);
        border-radius: var(--reg-radius); border: 1.5px dashed var(--reg-blue);
        box-shadow: var(--reg-shadow-sm);
    }
    .diaspora-panel.show { display: block; animation: sectionIn 0.3s; }
    .diaspora-panel .field-input-wrap .form-control,
    .diaspora-panel .field-input-wrap .form-select { background: #fff; }

    /* ─── FILE UPLOAD PREVIEW ────────────────────────────────── */
    .upload-preview {
        margin-top: 0.45rem; display: none;
    }
    .upload-preview.show { display: block; animation: sectionIn 0.3s; }
    .upload-preview img {
        max-width: 160px; max-height: 120px; border-radius: var(--reg-radius-sm);
        border: 1px solid var(--reg-gray-300); box-shadow: var(--reg-shadow-sm);
    }
    .upload-preview .up-file {
        display: inline-flex; align-items: center; gap: 0.45rem;
        font-size: 0.8rem; color: var(--reg-gray-600); background: #fff;
        border: 1px solid var(--reg-gray-200); border-radius: var(--reg-radius-xs);
        padding: 0.35rem 0.7rem;
    }
    .upload-preview .up-file i { color: var(--reg-green); }
    .name-caps { text-transform: capitalize; }

    /* ─── DOB ELIGIBILITY ────────────────────────────────────── */
    .dob-result {
        margin-top: 0.5rem; padding: 0.75rem 1rem; border-radius: var(--reg-radius-sm);
        font-size: 0.85rem; display: none; animation: sectionIn 0.3s; line-height: 1.45;
    }
    .dob-result.show { display: flex; gap: 0.75rem; align-items: flex-start; }
    .dob-result.eligible { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
    .dob-result.not-eligible { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
    .dob-result .dob-icon { font-size: 1.6rem; flex-shrink: 0; line-height: 1; margin-top: 0.1rem; }
    .dob-result .dob-text strong { font-size: 0.95rem; }
    .dob-result .dob-sub { font-size: 0.78rem; opacity: 0.8; margin-top: 0.15rem; }

    /* ─── LOCATION CHAIN ─────────────────────────────────────── */
    .location-chain {
        display: flex; flex-direction: column; gap: 0;
        position: relative; padding-left: 20px;
    }
    .location-chain::before {
        content: ''; position: absolute; left: 8px; top: 24px; bottom: 24px; width: 2px;
        background: linear-gradient(180deg, var(--reg-green) 0%, var(--reg-gray-200) 100%);
    }
    .chain-item { position: relative; padding-left: 20px; padding-bottom: 0.5rem; }
    .chain-dot {
        position: absolute; left: -20px; top: 12px; width: 18px; height: 18px; border-radius: 50%;
        background: var(--reg-gray-100); border: 2.5px solid var(--reg-gray-300);
        display: flex; align-items: center; justify-content: center; z-index: 2;
        transition: var(--reg-transition);
    }
    .chain-dot i { font-size: 0.6rem; color: transparent; }
    .chain-item.loaded .chain-dot { background: var(--reg-green); border-color: var(--reg-green); }
    .chain-item.loaded .chain-dot i { color: #fff; }
    .chain-item.loading .chain-dot {
        border-color: var(--reg-green); border-top-color: transparent;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ─── REVIEW CARDS ───────────────────────────────────────── */
    .review-section { background: var(--reg-gray-50); border-radius: var(--reg-radius-sm); padding: 1.25rem; margin-bottom: 1rem; border: 1px solid var(--reg-gray-200); }
    .review-section-title {
        font-weight: 700; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;
        color: var(--reg-green); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;
    }
    .review-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--reg-gray-100); }
    .review-row:last-child { border-bottom: none; }
    .review-label { font-size: 0.82rem; color: var(--reg-gray-400); }
    .review-value { font-size: 0.88rem; font-weight: 600; color: var(--reg-gray-800); text-align: right; }
    .review-value.missing { color: var(--reg-red); font-weight: 500; font-style: italic; }

    /* ─── BUTTONS ────────────────────────────────────────────── */
    .btn-reg {
        padding: 0.65rem 1.5rem; border-radius: var(--reg-radius-sm); font-weight: 600;
        font-size: 0.9rem; border: none; cursor: pointer; transition: var(--reg-transition);
        display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .btn-reg:active { transform: scale(0.97); }
    .btn-reg-next {
        background: var(--reg-green); color: #fff;
        box-shadow: 0 2px 8px rgba(46,139,87,0.25);
    }
    .btn-reg-next:hover { background: var(--reg-green-dark); box-shadow: 0 4px 12px rgba(46,139,87,0.35); transform: translateY(-1px); }
    .btn-reg-prev { background: var(--reg-gray-100); color: var(--reg-gray-600); }
    .btn-reg-prev:hover { background: var(--reg-gray-200); }
    .btn-reg-submit {
        background: linear-gradient(135deg, var(--reg-green), var(--reg-green-dark));
        color: #fff; padding: 0.75rem 2rem; font-size: 1rem; letter-spacing: 0.3px;
        box-shadow: 0 4px 16px rgba(46,139,87,0.3);
    }
    .btn-reg-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(46,139,87,0.4); }
    .btn-reg-submit:disabled { opacity: 0.5; transform: none; cursor: not-allowed; box-shadow: none; }
    .btn-nav-group { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid var(--reg-gray-100); }

    /* ─── CONFIRM CHECKBOX ───────────────────────────────────── */
    .confirm-box {
        display: flex; gap: 0.75rem; padding: 1rem 1.25rem; background: #ecfdf5;
        border-radius: var(--reg-radius-sm); border: 1.5px solid #a7f3d0;
    }
    .confirm-box input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--reg-green); margin-top: 0.1rem; flex-shrink: 0; }
    .confirm-box label { font-size: 0.88rem; font-weight: 500; color: var(--reg-gray-800); line-height: 1.5; }

    /* ─── AUTO-SAVE TOAST ────────────────────────────────────── */
    .save-toast {
        position: fixed; bottom: 24px; right: 24px; background: var(--reg-gray-800); color: #fff;
        padding: 0.6rem 1.1rem; border-radius: var(--reg-radius-sm); font-size: 0.82rem;
        display: none; z-index: 9999; box-shadow: var(--reg-shadow-lg);
        align-items: center; gap: 0.4rem;
    }
    .save-toast.show { display: flex; animation: toastIn 0.35s cubic-bezier(0.4,0,0.2,1); }
    .save-toast i { color: #34d399; }
    @keyframes toastIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    /* ─── DUPLICATE ALERT ────────────────────────────────────── */
    .dup-alert {
        margin-top: 0.4rem; padding: 0.55rem 0.8rem; border-radius: var(--reg-radius-xs);
        font-size: 0.8rem; display: none; align-items: center; gap: 0.4rem;
        animation: sectionIn 0.25s;
    }
    .dup-alert.warn { background: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
    .dup-alert.show { display: flex; }

    /* ─── VALIDATION STATES ──────────────────────────────────── */
    .field-input-wrap .form-control.is-invalid,
    .field-input-wrap .form-select.is-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
    .field-input-wrap .invalid-feedback { font-size: 0.78rem; color: #dc2626; margin-top: 0.3rem; display: none; }
    .field-input-wrap .form-control.is-invalid ~ .invalid-feedback,
    .field-input-wrap .form-select.is-invalid ~ .invalid-feedback { display: block; }

    /* ─── RESPONSIVE ─────────────────────────────────────────── */
    @media (max-width: 768px) {
        .reg-card-inner { padding: 1.25rem 1rem 1.5rem; }
        .reg-hero h1 { font-size: 1.4rem; }
        .stepper-circle { width: 36px; height: 36px; font-size: 0.75rem; }
        .stepper-label { font-size: 0.65rem; }
        .stepper-line { min-width: 28px; }
        .reg-type-grid { grid-template-columns: 1fr; }
        .progress-wrap { padding: 0.5rem 1rem; }
    }
</style>
@endpush

@section('content')
<div class="reg-hero">
    <div class="container">
        <h1><i class="fas fa-user-plus me-2"></i>Voter Registration</h1>
        <p class="mb-0">Register to vote in South Sudan's {{ config('nec.election_year') }} National Elections</p>
        <div class="hero-badges">
            <span class="hero-badge"><i class="fas fa-shield-halved"></i> Secure &amp; Confidential</span>
            <span class="hero-badge"><i class="fas fa-clock"></i> ~3 Minutes</span>
            <span class="hero-badge"><i class="fas fa-cloud-arrow-up"></i> Auto-Saves</span>
            <span class="hero-badge"><i class="fas fa-lock"></i> PII Encrypted</span>
        </div>
    </div>
</div>

<div class="container">
    <div class="reg-card">
        {{-- STEPPER --}}
        <div class="stepper" id="stepper">
            <div class="stepper-step active" id="stp1">
                <div class="stepper-circle"><span class="step-num">1</span><i class="fas fa-check check-icon"></i></div>
                <div class="stepper-label">Type</div>
            </div>
            <div class="stepper-line" id="line1"></div>
            <div class="stepper-step" id="stp2">
                <div class="stepper-circle"><span class="step-num">2</span><i class="fas fa-check check-icon"></i></div>
                <div class="stepper-label">Personal Info</div>
            </div>
            <div class="stepper-line" id="line2"></div>
            <div class="stepper-step" id="stp3">
                <div class="stepper-circle"><span class="step-num">3</span><i class="fas fa-check check-icon"></i></div>
                <div class="stepper-label">Location</div>
            </div>
            <div class="stepper-line" id="line3"></div>
            <div class="stepper-step" id="stp4">
                <div class="stepper-circle"><span class="step-num">4</span><i class="fas fa-check check-icon"></i></div>
                <div class="stepper-label">Review</div>
            </div>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="progress-wrap">
            <div class="progress-track"><div class="progress-fill" id="progressFill" style="width:0%"></div></div>
            <div class="progress-text" id="progressText">Step 1 of 4</div>
        </div>

        <div class="reg-card-inner">
            <form id="registrationForm" method="POST" action="{{ route('voter.register.submit') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="registration_type" id="registrationTypeInput" value="self">
                <input type="hidden" name="location_type" id="locationTypeInput" value="ss">
                <input type="hidden" name="preferred_language" value="English">

                {{-- ═══════════════ STEP 1: Registration Type ═══════════════ --}}
                <div class="form-section active" id="section1">
                    <div class="section-header">
                        <div class="section-icon green"><i class="fas fa-users"></i></div>
                        <div class="section-title-text">
                            <h3>How are you registering?</h3>
                            <p>Choose whether you are registering yourself or through an authorized agent</p>
                        </div>
                    </div>

                    <div class="reg-type-grid">
                        <div class="reg-type-card selected" data-type="self" onclick="selectRegType('self')">
                            <div class="reg-type-icon"><i class="fas fa-user" style="color:var(--reg-green)"></i></div>
                            <div class="reg-type-title">Self Registration</div>
                            <div class="reg-type-desc">I am registering myself in person</div>
                        </div>
                        <div class="reg-type-card" data-type="agent" onclick="selectRegType('agent')">
                            <div class="reg-type-icon"><i class="fas fa-clipboard-user" style="color:var(--reg-blue)"></i></div>
                            <div class="reg-type-title">Assisted Registration</div>
                            <div class="reg-type-desc">An authorized NEC agent is helping me register</div>
                        </div>
                    </div>

                    <div class="agent-panel" id="agentPanel">
                        <div class="field-group">
                            <label class="field-label"><span class="req">*</span> Registration Agent (code)</label>
                            <div class="field-input-wrap">
                                <i class="fas fa-user-tie field-input-icon"></i>
                                <select name="agent_id" id="agent_id" class="form-select">
                                    <option value="">-- Choose an agent code --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->agent_code }} — {{ $agent->assigned_state ?? $agent->state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-hint" style="margin-top:6px;color:#64748b;font-size:0.78rem;">Select the code of the NEC agent who is assisting you. You will be asked to verify your details with the agent's device.</div>
                        </div>
                    </div>

                    <div class="btn-nav-group">
                        <div></div>
                        <button type="button" class="btn-reg btn-reg-next" onclick="goToStep(2)">Continue <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ═══════════════ STEP 2: Personal Information ═══════════════ --}}
                <div class="form-section" id="section2">
                    <div class="section-header">
                        <div class="section-icon blue"><i class="fas fa-id-card"></i></div>
                        <div class="section-title-text">
                            <h3>Personal Information</h3>
                            <p>Enter your legal name and identification details</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="field-group">
                                <label class="field-label">Full Legal Name <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-user field-input-icon"></i>
                                    <input type="text" name="full_name" id="full_name" class="form-control name-caps" placeholder="e.g. James John Garang" required maxlength="255" oninput="capitalizeName(this);autoSaveField()">
                                </div>
                                <div class="field-hint"><i class="fas fa-info-circle"></i> Auto-capitalizes as you type</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-group">
                                <label class="field-label">Gender <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-venus-mars field-input-icon"></i>
                                    <select name="gender" id="gender" class="form-select" required onchange="autoSaveField()">
                                        <option value="">Select...</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-group">
                                <label class="field-label">Date of Birth <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-calendar-days field-input-icon"></i>
                                    <input type="date" name="dob" id="dob" class="form-control" required max="{{ date('Y-m-d', mktime(0,0,0,12,31,config('nec.election_year') - config('nec.minimum_registration_age'))) }}" lang="en-GB" onchange="onDobChange();autoSaveField()">
                                </div>
                                <div class="dob-result" id="dobInfo"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-group">
                                <label class="field-label">National ID Number <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-id-badge field-input-icon"></i>
                                    <input type="text" name="national_id" id="national_id" class="form-control" placeholder="e.g. SSN-12345" required maxlength="50" oninput="checkDuplicate('national_id', this.value)">
                                    <span class="status-icon" id="national_id_status"></span>
                                </div>
                                <div class="dup-alert warn" id="national_id_alert"><i class="fas fa-exclamation-triangle"></i> <span id="national_id_msg"></span></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="field-group">
                                <label class="field-label">Phone Number <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-phone field-input-icon"></i>
                                    <input type="tel" name="phone" id="phone" class="form-control" placeholder="+211 9XX XXX XXX" required maxlength="13" oninput="formatPhone(this);checkDuplicate('phone', this.value)">
                                    <span class="status-icon" id="phone_status"></span>
                                </div>
                                <div class="dup-alert warn" id="phone_alert"><i class="fas fa-exclamation-triangle"></i> <span id="phone_msg"></span></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="field-group">
                                <label class="field-label">Email Address <span class="optional">(optional — recommended for faster verification)</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-envelope field-input-icon"></i>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="your.email@example.com" maxlength="255" oninput="checkDuplicate('email', this.value)">
                                    <span class="status-icon" id="email_status"></span>
                                </div>
                                <div class="dup-alert warn" id="email_alert"><i class="fas fa-exclamation-triangle"></i> <span id="email_msg"></span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label">Your Photo <span class="optional">(optional, jpg/png/webp up to 5MB)</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-image field-input-icon"></i>
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*" onchange="previewUpload(this, 'photoPreview')">
                                </div>
                                <div class="upload-preview" id="photoPreview"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group">
                                <label class="field-label">National ID / Passport Scan <span class="optional">(optional, jpg/png/webp/pdf up to 5MB)</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-file-image field-input-icon"></i>
                                    <input type="file" name="id_document" id="id_document" class="form-control" accept="image/*,application/pdf" onchange="previewUpload(this, 'docPreview', true)">
                                </div>
                                <div class="upload-preview" id="docPreview"></div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-nav-group">
                        <button type="button" class="btn-reg btn-reg-prev" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn-reg btn-reg-next" onclick="goToStep(3)">Continue <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ═══════════════ STEP 3: Location ═══════════════ --}}
                <div class="form-section" id="section3">
                    <div class="section-header">
                        <div class="section-icon gold"><i class="fas fa-map-location-dot"></i></div>
                        <div class="section-title-text">
                            <h3>Your Location</h3>
                            <p>Where are you currently located? Choose your registration area below.</p>
                        </div>
                    </div>

                    <div class="reg-type-grid mt-2 mb-3" id="locationTypeGrid">
                        <div class="reg-type-card selected" data-loctype="ss" onclick="selectLocationType('ss')">
                            <div class="reg-type-icon"><i class="fas fa-map-location-dot" style="color:var(--reg-green)"></i></div>
                            <div class="reg-type-title">Inside South Sudan</div>
                            <div class="reg-type-desc">Register at your state, county or polling station</div>
                        </div>
                        <div class="reg-type-card" data-loctype="diaspora" onclick="selectLocationType('diaspora')">
                            <div class="reg-type-icon"><i class="fas fa-earth-africa" style="color:var(--reg-blue)"></i></div>
                            <div class="reg-type-title">Outside (Diaspora)</div>
                            <div class="reg-type-desc">South Sudanese living abroad register at a mission</div>
                        </div>
                    </div>

                    {{-- Diaspora fields --}}
                    <div class="diaspora-panel" id="diasporaPanel" style="display:none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label"><span class="req">*</span> Country of Residence</label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-earth-americas field-input-icon"></i>
                                        <select name="country_id" id="country_select" class="form-select" onchange="loadDiasporaMissions()">
                                            <option value="">Select country...</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" data-nationality="{{ $country->nationality }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label">Nationality <span class="optional">(optional)</span></label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-passport field-input-icon"></i>
                                        <input type="text" name="nationality" id="nationality" class="form-control name-caps" placeholder="e.g. South Sudanese" maxlength="120">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label"><span class="req">*</span> Passport Number</label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-passport field-input-icon"></i>
                                        <input type="text" name="passport_number" id="passport_number" class="form-control" placeholder="e.g. SS-123456" maxlength="60" oninput="checkDuplicate('passport_number', this.value)">
                                        <span class="status-icon" id="passport_number_status"></span>
                                    </div>
                                    <div class="dup-alert warn" id="passport_number_alert"><i class="fas fa-exclamation-triangle"></i> <span id="passport_number_msg"></span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-group">
                                    <label class="field-label"><span class="req">*</span> City / Town</label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-city field-input-icon"></i>
                                        <input type="text" name="city" id="city" class="form-control name-caps" placeholder="e.g. Nairobi" maxlength="120">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="field-group">
                                    <label class="field-label"><span class="req">*</span> Residential Address</label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-house-chimney field-input-icon"></i>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Street, estate/building, district" maxlength="255">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="field-group">
                                    <label class="field-label">Postal Code <span class="optional">(optional)</span></label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-envelope field-input-icon"></i>
                                        <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="e.g. 00100" maxlength="30">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="field-group">
                                    <label class="field-label"><span class="req">*</span> Registration Venue (Mission / Consulate)</label>
                                    <div class="field-input-wrap">
                                        <i class="fas fa-building-columns field-input-icon"></i>
                                        <select name="diaspora_mission_id" id="diaspora_mission_select" class="form-select">
                                            <option value="">Select country first...</option>
                                        </select>
                                        <div class="field-hint"><i class="fas fa-info-circle"></i> This will be your polling location for the elections.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- South Sudan location chain --}}
                    <div class="location-chain" id="ssLocationPanel">
                        <div class="chain-item" id="chain_region">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">Region <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-globe-africa field-input-icon"></i>
                                    <select id="region_select" class="form-select" onchange="loadStates()" required>
                                        <option value="">Select Region...</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_state">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">State <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-map-marked-alt field-input-icon"></i>
                                    <select name="state" id="state_select" class="form-select" onchange="loadCounties()" required disabled>
                                        <option value="">Select State...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_county">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">County <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-building field-input-icon"></i>
                                    <select name="county" id="county_select" class="form-select" onchange="loadConstituencies()" required disabled>
                                        <option value="">Select County...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_constituency">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">Constituency <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-landmark field-input-icon"></i>
                                    <select name="constituency" id="constituency_select" class="form-select" required disabled>
                                        <option value="">Select Constituency...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_payam">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">Payam <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-location-dot field-input-icon"></i>
                                    <select name="payam" id="payam_select" class="form-select" onchange="loadBomas()" required disabled>
                                        <option value="">Select Payam...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_boma">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">Boma <span class="optional">(optional)</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-house-chimney field-input-icon"></i>
                                    <select name="boma" id="boma_select" class="form-select" disabled>
                                        <option value="">Select Boma...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="chain-item" id="chain_station">
                            <div class="chain-dot"><i class="fas fa-check"></i></div>
                            <div class="field-group">
                                <label class="field-label">Polling Station <span class="req">*</span></label>
                                <div class="field-input-wrap">
                                    <i class="fas fa-booth-curtain field-input-icon"></i>
                                    <select name="polling_station" id="polling_station_select" class="form-select" required disabled>
                                        <option value="">Select Polling Station...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-group mt-3" style="padding-left:20px;">
                        <label class="field-label">Registration Center <span class="optional">(optional)</span></label>
                        <div class="field-input-wrap">
                            <i class="fas fa-building-columns field-input-icon"></i>
                            <input type="text" name="registration_center" id="registration_center" class="form-control" placeholder="If different from polling station">
                        </div>
                    </div>
                    </div>

                    <div class="btn-nav-group">
                        <button type="button" class="btn-reg btn-reg-prev" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn-reg btn-reg-next" onclick="goToStep(4)">Continue <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>

                {{-- ═══════════════ STEP 4: Review & Submit ═══════════════ --}}
                <div class="form-section" id="section4">
                    <div class="section-header">
                        <div class="section-icon teal"><i class="fas fa-clipboard-check"></i></div>
                        <div class="section-title-text">
                            <h3>Review Your Information</h3>
                            <p>Please verify all details before submitting your registration</p>
                        </div>
                    </div>

                    <div id="reviewSummary"></div>

                    <div class="confirm-box mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmCheck" onchange="document.getElementById('submitBtn').disabled = !this.checked">
                        <label class="form-check-label" for="confirmCheck">
                            I confirm that the information provided is true, complete, and accurate to the best of my knowledge. I understand that providing false information is an offence under the Elections Act.
                        </label>
                    </div>

                    <div class="btn-nav-group">
                        <button type="button" class="btn-reg btn-reg-prev" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn-reg btn-reg-submit" id="submitBtn" disabled>
                            <i class="fas fa-paper-plane"></i> Submit Registration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="save-toast" id="saveToast"><i class="fas fa-cloud-arrow-up"></i> Draft saved</div>
@endsection

@push('scripts')
<script>
let currentStep = 1;
let autoSaveTimer = null;
let duplicateTimers = {};

const API_BASE = '{{ url("/api/geo") }}';
const ELECTION_YEAR = {{ config('nec.election_year') }};
const VOTING_AGE = {{ config('nec.voting_age') }};
const MIN_REG_AGE = {{ config('nec.minimum_registration_age') }};

/* ─── STEP NAVIGATION ───────────────────────────────────────── */

function goToStep(step) {
    if (step > currentStep + 1) return;

    if (step === 2 && currentStep === 1) {
        var type = document.getElementById('registrationTypeInput').value;
        if (type === 'agent' && !document.getElementById('agent_id').value) {
            showFieldError('agent_id', 'Please select a registration agent.');
            return;
        }
    }
    if (step === 3 && currentStep === 2 && !validateStep2()) return;
    if (step === 4) { if (!validateStep3()) return; buildReview(); }

    document.querySelectorAll('.form-section').forEach(function(s) { s.classList.remove('active'); });
    document.getElementById('section' + step).classList.add('active');

    for (var i = 1; i <= 4; i++) {
        var el = document.getElementById('stp' + i);
        el.classList.remove('active', 'done');
        if (i < step) el.classList.add('done');
        else if (i === step) el.classList.add('active');
    }
    for (var i = 1; i <= 3; i++) {
        document.getElementById('line' + i).classList.toggle('done', i < step);
    }

    var pct = ((step - 1) / 3) * 100;
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressText').textContent = 'Step ' + step + ' of 4';
    currentStep = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ─── REG TYPE ──────────────────────────────────────────────── */

function selectRegType(type) {
    document.querySelectorAll('.reg-type-card').forEach(function(c) { c.classList.remove('selected'); });
    document.querySelector('.reg-type-card[data-type="' + type + '"]').classList.add('selected');
    document.getElementById('registrationTypeInput').value = type;
    document.getElementById('agentPanel').classList.toggle('show', type === 'agent');
    if (type === 'self') clearFieldError('agent_id');
}

/* ─── LOCATION TYPE (SS / DIASPORA) ─────────────────────────── */

function selectLocationType(type) {
    document.querySelectorAll('#locationTypeGrid .reg-type-card').forEach(function(c){ c.classList.remove('selected'); });
    document.querySelector('#locationTypeGrid .reg-type-card[data-loctype="' + type + '"]').classList.add('selected');
    document.getElementById('locationTypeInput').value = type;
    var diasporaPanel = document.getElementById('diasporaPanel');
    var ssPanel = document.getElementById('ssLocationPanel');
    var diasporaIds = ['country_select','passport_number','city','address','diaspora_mission_select'];
    var ssIds = ['state_select','county_select','constituency_select','payam_select','polling_station_select'];
    if (type === 'diaspora') {
        diasporaPanel.classList.add('show'); ssPanel.style.display = 'none';
        diasporaIds.forEach(function(id){ var el=document.getElementById(id); if(el) el.setAttribute('required',''); });
        ssIds.forEach(function(id){ var el=document.getElementById(id); if(el) el.removeAttribute('required'); });
    } else {
        diasporaPanel.classList.remove('show'); ssPanel.style.display = '';
        diasporaIds.forEach(function(id){ var el=document.getElementById(id); if(el) el.removeAttribute('required'); });
        ssIds.forEach(function(id){ var el=document.getElementById(id); if(el) el.setAttribute('required',''); });
    }
    if (currentStep === 4) buildReview();
}

function loadDiasporaMissions() {
    var countryId = document.getElementById('country_select').value;
    var sel = document.getElementById('diaspora_mission_select');
    var natEl = document.getElementById('nationality');
    var opt = document.getElementById('country_select').selectedOptions[0];
    if (natEl && opt && opt.dataset.nationality) natEl.value = opt.dataset.nationality;
    sel.innerHTML = '<option value="">Loading...</option>';
    if (!countryId) { sel.innerHTML = '<option value="">Select country first...</option>'; return; }
    fetch(API_BASE + '/diaspora-missions?country_id=' + countryId)
    .then(function(r){ return r.json(); })
    .then(function(data){
        var arr = Array.isArray(data) ? data : (data.missions || []);
        sel.innerHTML = '<option value="">Select mission / consulate...</option>';
        arr.forEach(function(m){
            sel.innerHTML += '<option value="'+m.id+'">'+m.name + (m.city ? ' — '+m.city : '') + '</option>';
        });
    })
    .catch(function(){ sel.innerHTML = '<option value="">Select mission / consulate...</option>'; });
}

function previewUpload(input, targetId, isDoc) {
    var box = document.getElementById(targetId);
    if (!box) return;
    if (!input.files || !input.files[0]) { box.classList.remove('show'); box.innerHTML = ''; return; }
    var file = input.files[0];
    if (file.size > 5 * 1024 * 1024) {
        showFieldError(input.id, 'File must be 5MB or less.');
        input.value = '';
        box.classList.remove('show'); box.innerHTML = '';
        return;
    }
    clearFieldError(input.id);
    box.classList.add('show');
    if (file.type === 'application/pdf' || isDoc) {
        box.innerHTML = '<div class="up-file"><i class="fas fa-' + (file.type === 'application/pdf' ? 'file-pdf' : 'file') + '"></i> ' + file.name + ' <small>(' + Math.round(file.size/1024) + ' KB)</small></div>';
    } else {
        var reader = new FileReader();
        reader.onload = function(e){ box.innerHTML = '<img src="'+e.target.result+'" alt="Photo preview">'; };
        reader.readAsDataURL(file);
    }
}

/* ─── DOB ELIGIBILITY ───────────────────────────────────────── */

function onDobChange() {
    var dobVal = document.getElementById('dob').value;
    var box = document.getElementById('dobInfo');
    if (!dobVal) { box.className = 'dob-result'; box.innerHTML = ''; return; }

    var birth = new Date(dobVal + 'T00:00:00');
    var now = new Date();
    var electionCutoff = new Date(ELECTION_YEAR, 11, 31);
    var ageAtElection = Math.floor((electionCutoff - birth) / 31557600000);
    var ageToday = Math.floor((now - birth) / 31557600000);
    var formatted = birth.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
    var eligibleDate = new Date(birth);
    eligibleDate.setFullYear(birth.getFullYear() + VOTING_AGE);
    var eligibleText = eligibleDate.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });

    if (ageAtElection < MIN_REG_AGE) {
        box.className = 'dob-result not-eligible show';
        box.innerHTML = '<div class="dob-icon"><i class="fas fa-circle-xmark"></i></div><div class="dob-text">'
            + '<strong>Registration open from ' + MIN_REG_AGE + ' (' + (ELECTION_YEAR - MIN_REG_AGE) + ')</strong><br>'
            + 'Born ' + formatted + ' — you would be only <strong>' + ageAtElection + '</strong> on election day (31 Dec ' + ELECTION_YEAR + ').'
            + '<div class="dob-sub">The minimum age to register for this cycle is ' + MIN_REG_AGE + '.</div></div>';
        return;
    }

    if (ageAtElection >= VOTING_AGE) {
        box.className = 'dob-result eligible show';
        box.innerHTML = '<div class="dob-icon"><i class="fas fa-circle-check"></i></div><div class="dob-text">'
            + '<strong>You are eligible to vote!</strong><br>'
            + 'Born ' + formatted + ' — you will be <strong>' + ageAtElection + '</strong> years old on election day (31 Dec ' + ELECTION_YEAR + ').'
            + '<div class="dob-sub">You meet the age requirement. Currently ' + ageToday + ' years old.</div></div>';
        return;
    }

    if (ageToday < VOTING_AGE) {
        var yrsTo18 = VOTING_AGE - ageToday;
        box.className = 'dob-result not-eligible show';
        box.innerHTML = '<div class="dob-icon"><i class="fas fa-hourglass-half"></i></div><div class="dob-text">'
            + '<strong>Pre-registration accepted!</strong><br>'
            + 'Born ' + formatted + ' — you will be <strong>' + ageAtElection + '</strong> on election day and eligible to vote once 18.'
            + '<div class="dob-sub">Eligibility date: <strong>' + eligibleText + '</strong> (' + yrsTo18 + ' year' + (yrsTo18 !== 1 ? 's' : '') + ' to go).</div></div>';
    } else {
        box.className = 'dob-result eligible show';
        box.innerHTML = '<div class="dob-icon"><i class="fas fa-circle-check"></i></div><div class="dob-text">'
            + '<strong>You are eligible to vote!</strong><br>'
            + 'Born ' + formatted + ' — ' + ageAtElection + ' years old on election day.</div></div>';
    }
}

/* ─── PHONE FORMAT ──────────────────────────────────────────── */

function formatPhone(el) {
    var v = el.value.replace(/[^\d+]/g, '');
    if (v.startsWith('+211')) el.value = v.substring(0, 13);
    else if (v.startsWith('09')) el.value = v.substring(0, 10);
    else el.value = v.substring(0, 13);
}

/* ─── NAME CAPITALIZE ───────────────────────────────────────── */

function capitalizeName(el) {
    var val = el.value;
    var result = val.replace(/\b\w/g, function(c) { return c.toUpperCase(); });
    if (result !== val) {
        var pos = el.selectionStart;
        el.value = result;
        el.setSelectionRange(pos, pos);
    }
}

/* ─── VALIDATION ────────────────────────────────────────────── */

function showFieldError(id, msg) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('is-invalid');
    var wrap = el.closest('.field-input-wrap') || el.parentNode;
    var fb = wrap.querySelector('.invalid-feedback');
    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; wrap.appendChild(fb); }
    fb.textContent = msg;
}

function clearFieldError(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('is-invalid');
    var wrap = el.closest('.field-input-wrap') || el.parentNode;
    var fb = wrap.querySelector('.invalid-feedback');
    if (fb) fb.remove();
}

function validateStep2() {
    var valid = true;
    var rules = [
        { id: 'full_name', tests: [
            [function(v) { return v.trim().length >= 2; }, 'Name must be at least 2 characters.'],
            [function(v) { return /^[a-zA-Z\s.\-']+$/.test(v.trim()); }, 'Name can only contain letters, spaces, hyphens.'],
        ]},
        { id: 'gender', tests: [[function(v) { return v !== ''; }, 'Please select your gender.']] },
        { id: 'dob', tests: [
            [function(v) { return v !== ''; }, 'Date of birth is required.'],
            [function(v) {
                if (!v) return false;
                var cutoff = new Date(ELECTION_YEAR, 11, 31);
                return Math.floor((cutoff - new Date(v)) / 31557600000) >= MIN_REG_AGE;
            }, 'You must be at least ' + MIN_REG_AGE + ' by 31 Dec ' + ELECTION_YEAR + ' to register.'],
        ]},
        { id: 'national_id', tests: [
            [function(v) { return v.trim().length >= 4; }, 'National ID must be at least 4 characters.'],
            [function(v) { return /^[A-Za-z0-9\-]+$/.test(v.trim()); }, 'Only letters, numbers, and hyphens allowed.'],
        ]},
        { id: 'phone', tests: [
            [function(v) { return v.trim().length >= 9; }, 'Phone number must be at least 9 digits.'],
            [function(v) {
                var d = v.replace(/[\s\-()]/g, '');
                return (/^(\+211\d{9}$|09\d{8}$|\+\d{8,15}$|\d{8,15}$)/).test(d);
            }, 'Enter a valid number, e.g. +211 912 345 678 or +254 712 345 678.'],
        ]},
    ];

    rules.forEach(function(r) {
        var el = document.getElementById(r.id);
        if (!el) return;
        clearFieldError(r.id);
        for (var i = 0; i < r.tests.length; i++) {
            if (!r.tests[i][0](el.value)) { showFieldError(r.id, r.tests[i][1]); valid = false; break; }
        }
    });

    var email = document.getElementById('email');
    if (email && email.value.trim()) {
        clearFieldError('email');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) { showFieldError('email', 'Enter a valid email.'); valid = false; }
    }
    if (!valid) { var f = document.querySelector('#section2 .is-invalid'); if (f) f.focus(); }
    return valid;
}

function validateStep3() {
    var valid = true;
    var locType = document.getElementById('locationTypeInput').value;
    if (locType === 'diaspora') {
        ['country_select','passport_number','city','address','diaspora_mission_select'].forEach(function(id) {
            var el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('is-invalid');
            if (!el.value.trim()) { el.classList.add('is-invalid'); valid = false; }
        });
        var natEl = document.getElementById('nationality');
        if (natEl && !natEl.value.trim()) { natEl.classList.add('is-invalid'); valid = false; } else if (natEl) { natEl.classList.remove('is-invalid'); }
    } else {
        ['state_select','county_select','constituency_select','payam_select','polling_station_select'].forEach(function(id) {
            var el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('is-invalid');
            if (!el.value) { el.classList.add('is-invalid'); valid = false; }
        });
    }
    if (!valid) { var f = document.querySelector('#section3 .is-invalid'); if (f) f.focus(); }
    return valid;
}

/* ─── CASCADING DROPDOWNS ───────────────────────────────────── */

function markChain(id, state) {
    var el = document.getElementById(id);
    el.classList.remove('loaded', 'loading');
    if (state) el.classList.add(state);
}

function loadStates() {
    var regionId = document.getElementById('region_select').value;
    var sel = document.getElementById('state_select');
    sel.innerHTML = '<option value="">Loading...</option>'; sel.disabled = true;
    resetCascadeFrom('state'); markChain('chain_region', 'loaded'); markChain('chain_state', 'loading');
    if (!regionId) { sel.innerHTML = '<option value="">Select State...</option>'; markChain('chain_state', ''); return; }
    fetch(API_BASE + '/states?region_id=' + regionId).then(function(r){return r.json();}).then(function(data){
        sel.innerHTML = '<option value="">Select State...</option>';
        data.forEach(function(s){ sel.innerHTML += '<option value="'+s.name+'" data-id="'+s.id+'">'+s.name+'</option>'; });
        sel.disabled = false; markChain('chain_state', 'loaded');
    });
}

function loadCounties() {
    var stateName = document.getElementById('state_select').value;
    var sel = document.getElementById('county_select');
    sel.innerHTML = '<option value="">Loading...</option>'; sel.disabled = true;
    resetCascadeFrom('county'); markChain('chain_county', 'loading');
    if (!stateName) { sel.innerHTML = '<option value="">Select County...</option>'; markChain('chain_county', ''); return; }
    var stateId = document.getElementById('state_select').selectedOptions[0]?.getAttribute('data-id');
    if (stateId) { fetchCounties(stateId, sel); }
    else { fetch(API_BASE+'/states').then(function(r){return r.json();}).then(function(states){
        var s = states.find(function(x){return x.name===stateName;});
        if (s) return fetchCounties(s.id, sel);
        sel.innerHTML = '<option value="">Select County...</option>'; markChain('chain_county', '');
    }); }
}

function fetchCounties(stateId, sel) {
    return fetch(API_BASE+'/counties?state_id='+stateId).then(function(r){return r.json();}).then(function(data){
        sel.innerHTML = '<option value="">Select County...</option>';
        var arr = Array.isArray(data)?data:Object.values(data);
        arr.forEach(function(c){
            var name = typeof c==='object'?c.name:c; var id = typeof c==='object'?c.id:'';
            sel.innerHTML += '<option value="'+name+'" data-id="'+id+'">'+name+'</option>';
        });
        sel.disabled = false; markChain('chain_county', 'loaded');
    }).catch(function(){ sel.innerHTML='<option value="">Select County...</option>'; markChain('chain_county',''); });
}

function loadConstituencies() {
    var state = document.getElementById('state_select').value;
    var county = document.getElementById('county_select').value;
    var sel = document.getElementById('constituency_select');
    sel.innerHTML = '<option value="">Loading...</option>'; sel.disabled = true;
    resetCascadeFrom('constituency'); markChain('chain_constituency', 'loading');
    if (!state||!county) { sel.innerHTML='<option value="">Select Constituency...</option>'; markChain('chain_constituency',''); return; }
    fetch(API_BASE+'/constituencies?state='+encodeURIComponent(state)+'&county='+encodeURIComponent(county))
    .then(function(r){return r.json();}).then(function(data){
        sel.innerHTML = '<option value="">Select Constituency...</option>';
        var arr = Array.isArray(data)?data:Object.values(data);
        arr.forEach(function(c){ var name=typeof c==='object'?c.name:c; sel.innerHTML+='<option value="'+name+'">'+name+'</option>'; });
        sel.disabled=false; markChain('chain_constituency','loaded');
    }).catch(function(){ sel.innerHTML='<option value="">Select Constituency...</option>'; });
}

function loadPayams() {
    var countySelect = document.getElementById('county_select');
    var countyName = countySelect.value;
    var sel = document.getElementById('payam_select');
    sel.innerHTML = '<option value="">Loading...</option>'; sel.disabled = true;
    resetCascadeFrom('payam'); markChain('chain_payam', 'loading');
    if (!countyName) { sel.innerHTML='<option value="">Select Payam...</option>'; markChain('chain_payam',''); return; }
    var countyId = countySelect.selectedOptions[0]?.getAttribute('data-id');
    if (countyId) { fetchPayams(countyId, sel); }
    else {
        var stateId = document.getElementById('state_select').selectedOptions[0]?.getAttribute('data-id');
        if (stateId) {
            fetch(API_BASE+'/counties?state_id='+stateId).then(function(r){return r.json();}).then(function(counties){
                var arr=Array.isArray(counties)?counties:Object.values(counties);
                var c=arr.find(function(x){return(x.name||x)===countyName;});
                if(c&&c.id)return fetchPayams(c.id,sel);
                sel.innerHTML='<option value="">Select Payam...</option>'; markChain('chain_payam','');
            }).catch(function(){ markChain('chain_payam',''); });
        }
    }
}

function fetchPayams(countyId, sel) {
    return fetch(API_BASE+'/payams?county_id='+countyId).then(function(r){return r.json();}).then(function(data){
        sel.innerHTML='<option value="">Select Payam...</option>';
        Object.entries(data).forEach(function(e){ sel.innerHTML+='<option value="'+e[1]+'" data-id="'+e[0]+'">'+e[1]+'</option>'; });
        sel.disabled=false; markChain('chain_payam','loaded');
    }).catch(function(){ sel.innerHTML='<option value="">Select Payam...</option>'; });
}

function loadBomas() {
    var payamSelect = document.getElementById('payam_select');
    var sel = document.getElementById('boma_select');
    sel.innerHTML='<option value="">Loading...</option>'; sel.disabled=true;
    markChain('chain_boma','loading');
    var payamId = payamSelect.selectedOptions[0]?.getAttribute('data-id');
    if (payamId) {
        fetch(API_BASE+'/bomas?payam_id='+payamId).then(function(r){return r.json();}).then(function(data){
            sel.innerHTML='<option value="">Select Boma...</option>';
            var arr=Array.isArray(data)?data:Object.values(data);
            arr.forEach(function(b){ var name=typeof b==='object'?b.name:b; sel.innerHTML+='<option value="'+name+'">'+name+'</option>'; });
            sel.disabled=false; markChain('chain_boma','loaded');
        }).catch(function(){ sel.innerHTML='<option value="">Select Boma...</option>'; markChain('chain_boma',''); });
    } else {
        sel.innerHTML='<option value="">Select Boma...</option>'; markChain('chain_boma','');
    }
}

function loadPollingStations() {
    var state = document.getElementById('state_select').value;
    var county = document.getElementById('county_select').value;
    var sel = document.getElementById('polling_station_select');
    sel.innerHTML='<option value="">Loading...</option>'; sel.disabled=true;
    markChain('chain_station','loading');
    fetch(API_BASE+'/polling-stations?state='+encodeURIComponent(state)+'&county='+encodeURIComponent(county))
    .then(function(r){return r.json();}).then(function(data){
        sel.innerHTML='<option value="">Select Polling Station...</option>';
        Object.entries(data).forEach(function(e){ sel.innerHTML+='<option value="'+e[1]+'">'+e[1]+'</option>'; });
        sel.disabled=false; markChain('chain_station','loaded');
    }).catch(function(){ sel.innerHTML='<option value="">Select Polling Station...</option>'; markChain('chain_station',''); });
}

function resetCascadeFrom(level) {
    var chain = {
        'state': ['county_select','constituency_select','payam_select','boma_select','polling_station_select'],
        'county': ['constituency_select','payam_select','boma_select','polling_station_select'],
        'constituency': ['payam_select','boma_select','polling_station_select'],
        'payam': ['boma_select','polling_station_select'],
    };
    (chain[level]||[]).forEach(function(id){
        var el=document.getElementById(id);
        if(el){ el.innerHTML='<option value="">'+el.options[0].text+'</option>'; el.disabled=true; el.classList.remove('is-invalid'); }
    });
}

/* ─── EVENT LISTENERS ───────────────────────────────────────── */

document.getElementById('state_select').addEventListener('change', function(){ resetCascadeFrom('state'); loadCounties(); loadPayams(); loadPollingStations(); });
document.getElementById('county_select').addEventListener('change', function(){ loadConstituencies(); loadPayams(); loadPollingStations(); });
document.getElementById('constituency_select').addEventListener('change', function(){ loadPayams(); loadPollingStations(); });
document.getElementById('payam_select').addEventListener('change', function(){ loadBomas(); });

/* ─── DUPLICATE CHECK ───────────────────────────────────────── */

function checkDuplicate(field, value) {
    if (duplicateTimers[field]) clearTimeout(duplicateTimers[field]);
    var statusEl = document.getElementById(field+'_status');
    var alertEl = document.getElementById(field+'_alert');
    if (!value || value.length < 3) { if(statusEl) statusEl.innerHTML=''; if(alertEl) alertEl.classList.remove('show'); return; }
    if(statusEl) statusEl.innerHTML='<i class="fas fa-spinner fa-spin" style="color:var(--reg-gray-400)"></i>';
    duplicateTimers[field] = setTimeout(function(){
        fetch('{{ url("/api/voter/check-duplicate") }}',{
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify({field:field,value:value})
        }).then(function(r){return r.json();}).then(function(data){
            if(data.exists){
                if(statusEl) statusEl.innerHTML='<i class="fas fa-circle-xmark" style="color:#ef4444"></i>';
                document.getElementById(field+'_msg').textContent=data.message;
                if(alertEl) alertEl.classList.add('show');
                document.getElementById(field).classList.add('is-invalid');
            } else {
                if(statusEl) statusEl.innerHTML='<i class="fas fa-circle-check" style="color:#10b981"></i>';
                if(alertEl) alertEl.classList.remove('show');
                document.getElementById(field).classList.remove('is-invalid');
            }
        });
    }, 500);
}

/* ─── AUTO-SAVE ─────────────────────────────────────────────── */

function autoSaveField() {
    if (autoSaveTimer) clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function(){
        var data = {};
        ['full_name','gender','dob','national_id','phone','email'].forEach(function(f){
            var el=document.getElementById(f); if(el) data[f]=el.value;
        });
        ['state_select','county_select','constituency_select','payam_select','boma_select','polling_station_select'].forEach(function(f){
            var el=document.getElementById(f); if(el) data[f.replace('_select','')]=el.value;
        });
        data.location_type = document.getElementById('locationTypeInput').value;
        if (data.location_type === 'diaspora') {
            ['country_id','nationality','passport_number','city','address','postal_code','diaspora_mission_id'].forEach(function(f){
                var el=document.getElementById(f); if(el) data[f]=el.value;
            });
        }
        data.registration_type = document.getElementById('registrationTypeInput').value;
        data.agent_id = document.getElementById('agent_id')?.value || '';
        fetch('{{ url("/api/voter/auto-save") }}',{
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify(data)
        }).then(function(){
            var t=document.getElementById('saveToast');
            t.classList.add('show');
            setTimeout(function(){t.classList.remove('show');},2000);
        });
    }, 2000);
}

/* ─── REVIEW BUILDER ────────────────────────────────────────── */

function buildReview() {
    var html = '';

    // Personal info section
    html += '<div class="review-section"><div class="review-section-title"><i class="fas fa-user"></i> Personal Information</div>';
    var name = document.getElementById('full_name').value || '';
    var gender = document.getElementById('gender').value;
    var genderText = gender === 'M' ? 'Male' : gender === 'F' ? 'Female' : '';
    var dob = document.getElementById('dob').value;
    var dobDisplay = '-';
    if (dob) {
        var b = new Date(dob+'T00:00:00');
        dobDisplay = b.toLocaleDateString('en-GB',{day:'numeric',month:'long',year:'numeric'});
        var age = Math.floor((new Date(ELECTION_YEAR,11,31)-b)/31557600000);
        dobDisplay += ' <span style="background:#ecfdf5;color:#065f46;padding:2px 8px;border-radius:20px;font-size:0.75rem;font-weight:600;margin-left:6px;">'+age+' yrs</span>';
    }
    var natId = document.getElementById('national_id').value || '';
    var phone = document.getElementById('phone').value || '';
    var email = document.getElementById('email').value || '';

    html += reviewRow('Full Name', name || null);
    html += reviewRow('Gender', genderText || null);
    html += reviewRow('Date of Birth', dobDisplay);
    html += reviewRow('National ID', natId || null);
    html += reviewRow('Phone', phone || null);
    html += reviewRow('Email', email || '<em style="color:var(--reg-gray-400)">Not provided</em>');
    html += '</div>';

    // Location section
    html += '<div class="review-section"><div class="review-section-title"><i class="fas fa-map-marker-alt"></i> Location</div>';
    var locType = document.getElementById('locationTypeInput').value;
    if (locType === 'diaspora') {
        var countrySel = document.getElementById('country_select');
        var missionSel = document.getElementById('diaspora_mission_select');
        var missionText = null;
        if (missionSel && missionSel.selectedIndex >= 0 && missionSel.value) missionText = missionSel.selectedOptions[0].text;
        html += reviewRow('Residency', '<span style="background:#e8eef8;color:#1a3c8f;padding:2px 8px;border-radius:20px;font-size:0.75rem;font-weight:600;">Diaspora</span>');
        html += reviewRow('Country of Residence', countrySel && countrySel.value ? countrySel.selectedOptions[0].text : null);
        html += reviewRow('Nationality', document.getElementById('nationality').value || null);
        html += reviewRow('Passport Number', document.getElementById('passport_number').value || null);
        html += reviewRow('City / Town', document.getElementById('city').value || null);
        html += reviewRow('Address', document.getElementById('address').value || null);
        html += reviewRow('Postal Code', document.getElementById('postal_code').value || '<em style="color:var(--reg-gray-400)">Not provided</em>');
        html += reviewRow('Registration Venue (Mission)', missionText || null);
    } else {
        var stateEl = document.getElementById('state_select');
        var countyEl = document.getElementById('county_select');
        var consEl = document.getElementById('constituency_select');
        var payamEl = document.getElementById('payam_select');
        var bomaEl = document.getElementById('boma_select');
        var stationEl = document.getElementById('polling_station_select');

        html += reviewRow('Residency', '<span style="background:#ecfdf5;color:#065f46;padding:2px 8px;border-radius:20px;font-size:0.75rem;font-weight:600;">Inside South Sudan</span>');
        html += reviewRow('State', stateEl.selectedOptions[0]?.text !== 'Select State...' ? stateEl.selectedOptions[0]?.text : null);
        html += reviewRow('County', countyEl.selectedOptions[0]?.text !== 'Select County...' ? countyEl.selectedOptions[0]?.text : null);
        html += reviewRow('Constituency', consEl.selectedOptions[0]?.text !== 'Select Constituency...' ? consEl.selectedOptions[0]?.text : null);
        html += reviewRow('Payam', payamEl.selectedOptions[0]?.text !== 'Select Payam...' ? payamEl.selectedOptions[0]?.text : null);
        html += reviewRow('Boma', bomaEl.selectedOptions[0]?.text !== 'Select Boma...' ? bomaEl.selectedOptions[0]?.text || '<em style="color:var(--reg-gray-400)">None</em>' : '<em style="color:var(--reg-gray-400)">None</em>');
        html += reviewRow('Polling Station', stationEl.selectedOptions[0]?.text !== 'Select Polling Station...' ? stationEl.selectedOptions[0]?.text : null);
    }
    html += '</div>';

    // Registration type section
    html += '<div class="review-section"><div class="review-section-title"><i class="fas fa-clipboard-list"></i> Registration</div>';
    var regType = document.getElementById('registrationTypeInput').value;
    var agentSel = document.getElementById('agent_id');
    var agentText = agentSel && agentSel.selectedIndex > 0 ? agentSel.selectedOptions[0].text : null;
    html += reviewRow('Type', regType === 'self' ? 'Self Registration' : 'Agent-Assisted');
    if (regType === 'agent') html += reviewRow('Agent', agentText || null);
    html += '</div>';

    document.getElementById('reviewSummary').innerHTML = html;
}

function reviewRow(label, value) {
    if (!value) value = '<span class="missing">Not selected</span>';
    return '<div class="review-row"><span class="review-label">'+label+'</span><span class="review-value">'+value+'</span></div>';
}
</script>
@endpush
