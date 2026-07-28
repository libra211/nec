@extends('layouts.app')

@section('hero')
<section class="hero-section hero-page" style="min-height:40vh;background:linear-gradient(135deg,var(--nec-black) 0%,#0d2e4a 40%,#1a4a2e 100%);padding:50px 0 60px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div class="hero-bg-animation"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="text-center">
            <div class="hero-badge mb-3" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);padding:8px 20px;border-radius:50px;color:#fff;font-size:0.85rem;font-weight:500;backdrop-filter:blur(4px);">
                <i class="fas fa-shield-halved" style="color:var(--nec-gold);"></i> Secure Access Portal
            </div>
            <h1 class="hero-title" style="font-size:2.4rem;font-weight:800;color:#fff;margin-bottom:14px;">Login to NEC</h1>
            <p class="hero-subtitle" style="max-width:500px;margin:0 auto;color:rgba(255,255,255,.7);font-size:1.05rem;">Access the National Elections Commission administrative dashboard</p>
        </div>
    </div>
</section>
@endsection

@section('extra_head')
<style>
    body.login-page{background:var(--nec-bg);margin:0;padding:0;}
    .main-content{padding:0;margin:0;}
    .otp-input{width:52px;height:60px;text-align:center;font-size:1.5rem;font-weight:700;border:2px solid #dee2e6;border-radius:10px;transition:all .2s;outline:none;}
    .otp-input:focus{border-color:var(--nec-green);box-shadow:0 0 0 3px rgba(0,145,76,.15);}
    .otp-input.filled{border-color:var(--nec-green);background:#f0fdf4;}
    .step-indicator{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:30px;}
    .step-dot{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;transition:all .3s;}
    .step-dot.active{background:var(--nec-green);color:#fff;}
    .step-dot.done{background:var(--nec-green);color:#fff;}
    .step-dot.pending{background:#e9ecef;color:#999;}
    .step-line{width:60px;height:3px;background:#e9ecef;margin:0 8px;border-radius:2px;transition:background .3s;}
    .step-line.active{background:var(--nec-green);}
    .otp-resend{color:var(--nec-green);font-weight:600;cursor:pointer;text-decoration:none;font-size:0.88rem;transition:opacity .2s;}
    .otp-resend:hover{opacity:.7;color:var(--nec-green);}
    .otp-resend.disabled{color:#999;cursor:not-allowed;pointer-events:none;}
    .otp-timer{color:#999;font-size:0.85rem;font-weight:500;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
    .fade-in{animation:fadeIn .4s ease;}
</style>
@endsection

@section('content')
<section style="padding:50px 0 80px;">
    <div class="container">
        <div class="row g-0 justify-content-center">
            <!-- LEFT INFO -->
            <div class="col-lg-5 d-flex align-items-stretch">
                <div class="login-info-card w-100 d-flex flex-column justify-content-between" style="background:linear-gradient(135deg,var(--nec-green),#0a6b3a 50%,var(--nec-black));padding:50px 35px;color:#fff;border-radius:12px 0 0 12px;position:relative;overflow:hidden;">
                    <div>
                        <div style="display:flex;align-items:center;gap:14px;margin-bottom:35px;">
                            <div style="background:rgba(255,255,255,.15);border-radius:12px;padding:12px;"><i class="fas fa-shield-halved" style="font-size:2rem;color:var(--nec-gold);"></i></div>
                            <div><span style="display:block;font-size:1.4rem;font-weight:900;color:var(--nec-gold);line-height:1;">NEC</span><span style="font-size:0.62rem;text-transform:uppercase;letter-spacing:2.5px;color:rgba(255,255,255,.5);">Admin Portal</span></div>
                        </div>
                        <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:20px;line-height:1.3;">Manage Elections with Confidence</h2>
                        <p style="color:rgba(255,255,255,.7);font-size:0.95rem;line-height:1.7;margin-bottom:30px;">Access the administrative dashboard to manage voter registrations, election data, constituencies, and system settings.</p>
                    </div>
                    <div>
                        <div style="padding:18px;background:rgba(255,255,255,.08);border-radius:10px;border-left:3px solid var(--nec-gold);margin-bottom:18px;">
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-lock me-1"></i> Two-Factor Authentication</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">OTP verification adds an extra layer of security to your account.</p>
                        </div>
                        <div style="padding:18px;background:rgba(255,255,255,.08);border-radius:10px;border-left:3px solid var(--nec-gold);">
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-envelope-open-text me-1"></i> Email Verification</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">A one-time code is sent to your registered email address.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM -->
            <div class="col-lg-5 d-flex align-items-stretch">
                <div class="login-form-card w-100" style="background:#fff;padding:50px 35px;border-radius:0 12px 12px 0;border:1px solid #e9ecef;border-left:none;box-shadow:0 8px 30px rgba(0,0,0,.1);">

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step-dot {{ ($step ?? 'email') === 'email' ? 'active' : 'done' }}">
                            @if(($step ?? 'email') === 'email')
                                1
                            @else
                                <i class="fas fa-check" style="font-size:0.75rem;"></i>
                            @endif
                        </div>
                        <div class="step-line {{ ($step ?? 'email') === 'otp' ? 'active' : '' }}"></div>
                        <div class="step-dot {{ ($step ?? 'email') === 'otp' ? 'active' : 'pending' }}">2</div>
                    </div>

                    <!-- ==================== STEP 1: EMAIL ==================== -->
                    @if(($step ?? 'email') === 'email')
                    <div class="text-center mb-4 fade-in">
                        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-envelope" style="font-size:1.6rem;color:var(--nec-green);"></i>
                        </div>
                        <h3 style="font-size:1.5rem;font-weight:800;color:var(--nec-black);margin-bottom:6px;">Enter Your Email</h3>
                        <p style="color:var(--nec-text-light);font-size:0.88rem;">We'll send a one-time verification code to your email</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px;background:#fff5f5;border:1px solid #f5c6cb;color:#721c24;font-size:0.88rem;">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <input type="hidden" name="step" value="email">
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-envelope" style="color:#999;"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" required autofocus style="border-left:none;font-size:0.92rem;">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn fw-bold w-100 submit-btn" style="background:var(--nec-green);color:#fff;padding:12px;border:none;border-radius:6px;font-size:1rem;transition:all .3s;">
                            <span class="btn-text"><i class="fas fa-paper-plane me-1"></i> Send Verification Code</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Sending...</span>
                        </button>
                    </form>

                    @endif

                    <!-- ==================== STEP 2: OTP ==================== -->
                    @if(($step ?? 'email') === 'otp')
                    <div class="text-center mb-4 fade-in">
                        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-key" style="font-size:1.6rem;color:var(--nec-green);"></i>
                        </div>
                        <h3 style="font-size:1.5rem;font-weight:800;color:var(--nec-black);margin-bottom:6px;">Enter Verification Code</h3>
                        <p style="color:var(--nec-text-light);font-size:0.88rem;">A 6-digit code was sent to</p>
                        <p style="color:var(--nec-black);font-weight:700;font-size:0.92rem;margin:0;">{{ $email }}</p>
                    </div>

                    @if(session('error') || isset($error))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px;background:#fff5f5;border:1px solid #f5c6cb;color:#721c24;font-size:0.88rem;">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') ?? $error ?? '' }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($otpSent ?? false)
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:0.88rem;">
                        <i class="fas fa-check-circle me-1"></i> Verification code sent! Check your inbox.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Demo hint -->
                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.85rem;color:#92400e;">
                        <i class="fas fa-info-circle me-1"></i> <strong>Demo Mode:</strong> Use code <code style="background:#fef3c7;padding:2px 8px;border-radius:4px;font-weight:700;">000000</code> to log in
                    </div>

                    <form method="POST" action="{{ route('admin.login.submit') }}" id="otpForm">
                        @csrf
                        <input type="hidden" name="step" value="otp">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- OTP Input Boxes -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block text-center" style="font-size:0.88rem;color:#444;">6-Digit Code</label>
                            <div class="d-flex justify-content-center gap-2 mb-2" id="otpContainer">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="0" autofocus>
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="1">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="2">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="3">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="4">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="5">
                            </div>
                            <input type="hidden" name="otp" id="otpHidden" value="">
                            @error('otp')
                            <div class="text-danger text-center mt-1" style="font-size:0.82rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn fw-bold w-100 submit-btn" id="verifyBtn" style="background:var(--nec-green);color:#fff;padding:12px;border:none;border-radius:6px;font-size:1rem;transition:all .3s;">
                            <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Verify & Sign In</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Verifying...</span>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="otp-timer" id="resendTimer">Resend code in <strong id="countdown">60</strong>s</span>
                        <a href="{{ route('admin.login') }}" class="otp-resend disabled" id="resendBtn" style="display:none;">
                            <i class="fas fa-redo me-1"></i> Resend Code
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('admin.login') }}" style="color:#999;font-size:0.85rem;text-decoration:none;">
                            <i class="fas fa-arrow-left me-1"></i> Back to email
                        </a>
                    </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Submit button loading state
    document.querySelectorAll('.submit-btn').forEach(function(btn) {
        btn.closest('form').addEventListener('submit', function() {
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');
            btn.style.opacity = '0.7';
        });
    });

    // OTP Input handling
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
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
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
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                        otpInputs[i].classList.add('filled');
                    }
                });
                updateHiddenOtp();
                if (paste.length === 6) {
                    otpInputs[5].focus();
                }
            });

            input.addEventListener('focus', function() {
                this.select();
            });
        });

        function updateHiddenOtp() {
            var code = '';
            otpInputs.forEach(function(inp) { code += inp.value; });
            if (otpHidden) otpHidden.value = code;
        }

        // Auto-focus first input
        otpInputs[0].focus();
    }

    // Countdown timer for resend
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
