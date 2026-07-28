@extends('layouts.app', ['title' => 'Forgot Voter ID - NEC South Sudan', 'active_page' => 'voters'])

@section('hero')
<style>
.forgot-hero {
    background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
    position: relative; overflow: hidden; padding: 64px 0 48px;
}
.forgot-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.forgot-hero .hero-content { position: relative; z-index: 1; }
.forgot-hero h1 { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
.forgot-hero p { font-size: 1.05rem; color: rgba(255,255,255,0.7); max-width: 520px; margin-bottom: 0; }
.forgot-card {
    background: #fff; border-radius: 16px; box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    padding: 32px; margin-bottom: 28px;
}
.forgot-card .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
.forgot-card .form-control {
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    padding: 11px 14px; font-size: 14px; transition: all 0.2s;
}
.forgot-card .form-control:focus {
    border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1);
}
.id-found-card {
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border: 2px solid #6ee7b7; border-radius: 16px; padding: 32px; text-align: center;
}
.id-found-card .voter-id-display {
    font-size: 24px; font-weight: 800; color: #065f46; letter-spacing: 1px;
    background: #fff; display: inline-block; padding: 14px 32px;
    border-radius: 10px; margin: 12px 0; border: 2px dashed #6ee7b7;
    font-family: monospace;
}
</style>

<section class="forgot-hero">
    <div class="container hero-content">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}" class="text-white-50">Voters</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Forgot Voter ID</li>
            </ol>
        </nav>
        <h1>Forgot Voter ID?</h1>
        <p>Enter your details below to retrieve your lost or forgotten Voter ID.</p>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                @if(isset($foundId) && $foundId)
                <div class="id-found-card">
                    <div style="width:56px;height:56px;border-radius:50%;background:#10b981;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="fas fa-check text-white" style="font-size:24px;"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#065f46;">We Found Your Voter ID</h4>
                    <p style="color:#047857;margin-bottom:4px;">{{ $foundName }}</p>
                    <p style="color:#065f46;font-size:13px;margin-bottom:12px;">Please save this ID. You will need it to vote.</p>
                    <div class="voter-id-display">{{ $foundId }}</div>
                    <div style="margin-top:16px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                        <button onclick="navigator.clipboard.writeText('{{ $foundId }}')" class="btn" style="background:#065f46;color:#fff;border-radius:10px;font-weight:600;">
                            <i class="fas fa-copy me-1"></i> Copy to Clipboard
                        </button>
                        <a href="{{ route('voter.inquiry') . '?voter_id=' . urlencode($foundId) }}" class="btn" style="background:#fff;color:#065f46;border:2px solid #6ee7b7;border-radius:10px;font-weight:600;">
                            <i class="fas fa-search me-1"></i> View Details
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('voter.forgot-id') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Look up another ID</a>
                </div>

                @else

                <div class="forgot-card">
                    @if(isset($error) && $error)
                    <div class="alert alert-danger d-flex align-items-center gap-3 py-3" style="border-radius:12px;">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <div>{{ $error }}</div>
                    </div>
                    @endif

                    <div class="alert alert-info d-flex align-items-center gap-3 py-3" style="border-radius:12px;">
                        <i class="fas fa-info-circle fa-lg"></i>
                        <div style="font-size:13px;">Enter your <strong>full name</strong> and at least one other detail (phone or National ID) to retrieve your Voter ID.</div>
                    </div>

                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="forgot_id" value="1">

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control form-control-lg" placeholder="Enter your full legal name" required value="{{ old('full_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +211 912 345 678" value="{{ old('phone') }}">
                                <div class="form-text small text-muted">Enter the phone number used during registration.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">National ID</label>
                                <input type="text" name="national_id" class="form-control" placeholder="e.g. SS-123456" value="{{ old('national_id') }}">
                                <div class="form-text small text-muted">Or enter your National ID number.</div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg text-white" style="background:var(--nec-green);border-radius:10px;font-weight:600;">
                                <i class="fas fa-search me-1"></i> Retrieve My Voter ID
                            </button>
                        </div>

                        <p class="text-center text-muted small mt-3 mb-0">
                            <i class="fas fa-lock me-1"></i> Your details are used only to verify your identity.
                        </p>
                    </form>
                </div>

                <div class="text-center">
                    <p class="text-muted small">
                        <i class="fas fa-arrow-left me-1"></i> <a href="{{ route('voter.inquiry') }}" class="text-decoration-none">Back to Voter Inquiry</a>
                    </p>
                </div>

                @endif
            </div>
        </div>
    </div>
</section>
@endsection
