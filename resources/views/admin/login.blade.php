@extends('layouts.app')

@section('hero')
<section class="hero-section hero-page" style="min-height:30vh;background:linear-gradient(135deg,var(--nec-black) 0%,#0d2e4a 40%,#1a4a2e 100%);padding:30px 0 40px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div class="container position-relative text-center">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:6px 18px;border-radius:50px;color:#fff;font-size:0.82rem;font-weight:500;backdrop-filter:blur(4px);margin-bottom:12px;">
            <i class="fas fa-shield-halved" style="color:var(--nec-gold);font-size:0.75rem;"></i> Secure Admin Portal
        </div>
        <h1 class="fw-bold" style="color:#fff;font-size:2rem;margin-bottom:6px;">NEC Administration</h1>
        <p style="color:rgba(255,255,255,.65);font-size:0.95rem;max-width:400px;margin:0 auto;">National Elections Commission &middot; South Sudan</p>
    </div>
</section>
@endsection

@section('extra_head')
<style>
    body.login-page{background:#f0f2f5;}
    .main-content{padding:0;margin:0;background:#f0f2f5;min-height:100vh;}
    .nec-card{background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);border:1px solid #eef0f2;padding:32px 36px;}
    .otp-input{width:48px;height:54px;text-align:center;font-size:1.4rem;font-weight:700;border:2px solid #d1d5db;border-radius:8px;transition:all .2s;outline:none;background:#fff;}
    .otp-input:focus{border-color:var(--nec-green);box-shadow:0 0 0 3px rgba(46,139,87,.12);}
    .otp-input.filled{border-color:var(--nec-green);background:#f0fdf4;}
    .step-indicator{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:28px;}
    .step-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;transition:all .3s;}
    .step-dot.active{background:var(--nec-green);color:#fff;}
    .step-dot.done{background:var(--nec-green);color:#fff;}
    .step-dot.pending{background:#e5e7eb;color:#9ca3af;}
    .step-line{width:50px;height:2.5px;background:#e5e7eb;margin:0 6px;border-radius:2px;transition:background .3s;}
    .step-line.active{background:var(--nec-green);}
    .otp-timer{color:#9ca3af;font-size:0.85rem;font-weight:500;}
    .otp-resend{color:var(--nec-green);font-weight:600;cursor:pointer;text-decoration:none;font-size:0.88rem;}
    .otp-resend:hover{opacity:.75;}
    .otp-resend.disabled{color:#9ca3af;cursor:not-allowed;pointer-events:none;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}
    .fade-in{animation:fadeIn .35s ease;}
    .form-input{border:1.5px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:0.92rem;transition:all .2s;width:100%;outline:none;background:#fff;}
    .form-input:focus{border-color:var(--nec-green);box-shadow:0 0 0 3px rgba(46,139,87,.1);}
    .form-input.is-invalid{border-color:#dc2626;}
    .btn-primary-nec{background:var(--nec-green);color:#fff;border:none;border-radius:8px;padding:11px 20px;font-size:0.95rem;font-weight:600;width:100%;transition:all .2s;cursor:pointer;}
    .btn-primary-nec:hover{background:#1f6b3d;transform:translateY(-1px);box-shadow:0 4px 12px rgba(46,139,87,.3);}
    .btn-primary-nec:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}
</style>
@endsection

@section('content')
<section style="padding:30px 0 60px;background:#f0f2f5;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-5">

                <div class="nec-card">
                    <!-- Logo & Brand -->
                    <div class="text-center mb-3">
                        <div style="width:80px;height:80px;border-radius:50%;background:var(--nec-green);display:inline-flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                            <img src="{{ asset('assets/images/nec-logo-white.png') }}" alt="NEC South Sudan" height="50" style="filter:brightness(0) invert(1);">
                        </div>
                        <h3 style="font-size:1.15rem;font-weight:700;color:#1f2937;margin:0 0 2px;">National Elections Commission</h3>
                        <p style="color:#6b7280;font-size:0.8rem;margin:0;">South Sudan &middot; Admin Portal</p>
                    </div>

                    <hr style="opacity:.15;margin:18px 0;">

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step-dot {{ ($step ?? 'email') === 'email' ? 'active' : 'done' }}">
                            @if(($step ?? 'email') === 'email') 1 @else <i class="fas fa-check" style="font-size:0.7rem;"></i> @endif
                        </div>
                        <div class="step-line {{ ($step ?? 'email') === 'otp' ? 'active' : '' }}"></div>
                        <div class="step-dot {{ ($step ?? 'email') === 'otp' ? 'active' : 'pending' }}">2</div>
                    </div>

                    <!-- ==================== STEP 1: EMAIL ==================== -->
                    @if(($step ?? 'email') === 'email')
                    <div class="text-center mb-4 fade-in">
                        <div style="width:56px;height:56px;border-radius:50%;background:#f0fdf4;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;">
                            <i class="fas fa-envelope" style="font-size:1.4rem;color:var(--nec-green);"></i>
                        </div>
                        <h4 style="font-weight:700;color:#1f2937;margin-bottom:4px;">Enter Your Email</h4>
                        <p style="color:#6b7280;font-size:0.88rem;">A one-time code will be sent to your inbox</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center" style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#991b1b;">
                        <i class="fas fa-exclamation-circle me-2" style="flex-shrink:0;"></i> {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <input type="hidden" name="step" value="email">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">Email Address</label>
                            <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                            @error('email')<div style="color:#dc2626;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn-primary-nec submit-btn">
                            <span class="btn-text"><i class="fas fa-paper-plane me-1"></i> Send Verification Code</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Sending...</span>
                        </button>
                    </form>
                    @endif

                    <!-- ==================== STEP 2: OTP ==================== -->
                    @if(($step ?? 'email') === 'otp')
                    <div class="text-center mb-4 fade-in">
                        <div style="width:56px;height:56px;border-radius:50%;background:#f0fdf4;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;">
                            <i class="fas fa-key" style="font-size:1.4rem;color:var(--nec-green);"></i>
                        </div>
                        <h4 style="font-weight:700;color:#1f2937;margin-bottom:4px;">Enter Verification Code</h4>
                        <p style="color:#6b7280;font-size:0.85rem;margin-bottom:2px;">A 6-digit code was sent to</p>
                        <p style="font-weight:600;color:#1f2937;font-size:0.92rem;margin:0;">{{ $email }}</p>
                    </div>

                    @if(session('error') || isset($error))
                    <div class="alert alert-danger d-flex align-items-center" style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#991b1b;">
                        <i class="fas fa-exclamation-circle me-2" style="flex-shrink:0;"></i> {{ session('error') ?? $error ?? '' }}
                    </div>
                    @endif

                    @if($otpSent ?? false)
                    <div class="alert alert-success d-flex align-items-center" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#166534;">
                        <i class="fas fa-check-circle me-2" style="flex-shrink:0;"></i> Code sent! Check your inbox.
                    </div>
                    @endif

                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:0.82rem;color:#92400e;">
                        <i class="fas fa-info-circle me-1"></i> Demo: Use code <code style="background:#fef3c7;padding:1px 6px;border-radius:3px;font-weight:700;">000000</code>
                    </div>

                    <form method="POST" action="{{ route('admin.login.submit') }}" id="otpForm">
                        @csrf
                        <input type="hidden" name="step" value="otp">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block text-center" style="font-size:0.85rem;color:#374151;">6-Digit Code</label>
                            <div class="d-flex justify-content-center gap-2 mb-1" id="otpContainer">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="0" autofocus>
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="1">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="2">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="3">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="4">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="5">
                            </div>
                            <input type="hidden" name="otp" id="otpHidden" value="">
                            @error('otp')<div style="color:#dc2626;font-size:0.8rem;text-align:center;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn-primary-nec submit-btn" id="verifyBtn">
                            <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Verify & Sign In</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Verifying...</span>
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="otp-timer" id="resendTimer">Resend code in <strong id="countdown">60</strong>s</span>
                        <a href="{{ route('admin.login') }}" class="otp-resend disabled" id="resendBtn" style="display:none;">
                            <i class="fas fa-redo me-1"></i> Resend Code
                        </a>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ route('admin.login') }}" style="color:#9ca3af;font-size:0.83rem;text-decoration:none;">
                            <i class="fas fa-arrow-left me-1"></i> Back to email
                        </a>
                    </div>
                    @endif

                    <hr style="opacity:.12;margin:18px 0 14px;">
                    <div class="text-center">
                        <p style="color:#9ca3af;font-size:0.75rem;margin:0;">
                            <i class="fas fa-lock me-1" style="font-size:0.7rem;"></i> Secured with OTP two-factor authentication
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.submit-btn').forEach(function(btn) {
        btn.closest('form').addEventListener('submit', function() {
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');
            btn.style.opacity = '0.7';
        });
    });

    var otpInputs = document.querySelectorAll('.otp-input');
    var otpHidden = document.getElementById('otpHidden');
    var otpForm = document.getElementById('otpForm');

    if (otpInputs.length > 0) {
        otpInputs.forEach(function(input, index) {
            input.addEventListener('input', function(e) {
                var val = this.value.replace(/[^0-9]/g, '');
                this.value = val;
                if (val) {
                    this.classList.add('filled');
                    if (index < otpInputs.length - 1) otpInputs[index + 1].focus();
                } else {
                    this.classList.remove('filled');
                }
                updateHiddenOtp();
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    updateHiddenOtp();
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    updateHiddenOtp();
                    if (otpForm) otpForm.submit();
                }
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                paste.split('').forEach(function(char, i) {
                    if (otpInputs[i]) { otpInputs[i].value = char; otpInputs[i].classList.add('filled'); }
                });
                updateHiddenOtp();
                if (paste.length === 6) otpInputs[5].focus();
            });
            input.addEventListener('focus', function() { this.select(); });
        });

        function updateHiddenOtp() {
            var code = '';
            otpInputs.forEach(function(inp) { code += inp.value; });
            if (otpHidden) otpHidden.value = code;
        }

        otpInputs[0].focus();
    }

    var countdown = document.getElementById('countdown');
    var resendTimer = document.getElementById('resendTimer');
    var resendBtn = document.getElementById('resendBtn');
    if (countdown && resendTimer && resendBtn) {
        var seconds = 60;
        var timer = setInterval(function() {
            seconds--;
            countdown.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(timer);
                resendTimer.style.display = 'none';
                resendBtn.style.display = 'inline';
                resendBtn.classList.remove('disabled');
            }
        }, 1000);
    }
});
</script>
@endsection
