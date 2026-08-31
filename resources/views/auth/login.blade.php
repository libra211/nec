<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\NecHelper::setting_get('site_name', 'NEC South Sudan') }} - Login</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="{{ \App\Helpers\NecHelper::setting_get('favicon', asset('assets/images/logos/neclogo.jpeg')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --nec-primary: #FC6011; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Urbanist', sans-serif;
            background: linear-gradient(160deg, #f4f6fb 0%, #fff5f0 50%, #f4f6fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrapper {
            display: flex;
            max-width: 920px;
            width: 100%;
            min-height: 580px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.08), 0 8px 24px rgba(0,0,0,.04);
            overflow: hidden;
            position: relative;
        }
        .login-brand {
            width: 42%;
            background: #2C2220;
            padding: 24px 32px 30px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            text-align: center;
            background-image: radial-gradient(circle at 20% 30%, rgba(252,96,17,.08) 0%, transparent 70%);
        }
        .login-brand::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            background: rgba(252,96,17,.06);
            border-radius: 50%;
        }
        .brand-icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--nec-primary), #FF8F40);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 32px rgba(252,96,17,.3);
            position: relative;
            overflow: hidden;
            padding: 0;
        }
        .brand-icon-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            position: relative;
            z-index: 1;
        }
        .brand-icon-circle::before,
        .brand-icon-circle::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(252,96,17,.15);
            animation: pulse-ring 2s ease-in-out infinite;
        }
        .brand-icon-circle::before { width: 120%; height: 120%; }
        .brand-icon-circle::after { width: 140%; height: 140%; animation-delay: .5s; }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .brand-title { font-weight: 800; color: #fff; font-size: 1.15rem; line-height: 1.3; margin-bottom: 4px; }
        .brand-sub {
            color: rgba(255,255,255,.5);
            font-weight: 400;
            font-size: .85rem;
            max-width: 260px;
            line-height: 1.5;
            margin: 0 auto 24px;
        }
        .brand-features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-start;
            max-width: 280px;
            margin-right: auto;
        }
        .brand-feat {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 20px;
            font-weight: 500;
            font-size: .78rem;
            color: rgba(255,255,255,.6);
        }
        .login-form-wrap {
            width: 58%;
            padding: 24px 48px 30px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .login-form-inner { max-width: 380px; width: 100%; margin: 0 auto; }
        .login-header { margin-bottom: 24px; }
        .login-header h4 { font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .login-header p { color: #6b7280; font-size: .88rem; margin: 0; }
        .login-mode-toggle {
            display: flex;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 3px;
            margin-bottom: 22px;
        }
        .mode-btn {
            flex: 1;
            padding: 8px 10px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            font-size: .82rem;
            color: #64748b;
            transition: all .2s;
            cursor: pointer;
            font-family: 'Urbanist', sans-serif;
        }
        .mode-btn.active {
            background: #fff;
            color: #1f2937;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .field-group { margin-bottom: 18px; }
        .field-group .form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
        }
        .field-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            font-size: .6rem;
        }
        .field-icon.email { background: #dbeafe; color: #3b82f6; }
        .field-icon.phone { background: #fef3c7; color: #d97706; }
        .field-icon.key { background: #fce7f3; color: #ec4899; }
        .field-icon.lock { background: #f3e8ff; color: #9333ea; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .9rem;
            z-index: 3;
        }
        .input-wrap .form-control {
            height: 50px;
            padding-left: 44px;
            padding-right: 14px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-size: .9rem;
            font-weight: 500;
            transition: all .2s;
            background: #f8fafc;
            font-family: 'Urbanist', sans-serif;
        }
        .input-wrap .form-control:focus {
            border-color: var(--nec-primary);
            box-shadow: 0 0 0 3px rgba(252,96,17,.08);
            background: #fff;
        }
        .input-wrap .form-control.is-invalid { border-color: #ef4444; }
        .input-wrap .form-control:disabled { opacity: .6; background: #f1f5f9; }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            z-index: 3;
        }
        .btn-jibu {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding: .75rem 2rem;
            font-weight: 700;
            border-radius: 50px;
            border: none;
            background: linear-gradient(135deg, #FC6011, #FF8F40);
            color: #fff;
            font-size: .95rem;
            transition: all .3s;
            box-shadow: 0 4px 15px rgba(252,96,17,.25);
            cursor: pointer;
            font-family: 'Urbanist', sans-serif;
        }
        .btn-jibu:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(252,96,17,.35);
        }
        .btn-jibu:disabled { opacity: .6; cursor: not-allowed; transform: none; }
        .btn-shimmer {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,transparent,rgba(255,255,255,.15),transparent);
            animation: shimmer 2.5s infinite;
        }
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 200%; }
        }
        .forgot-link {
            color: var(--nec-primary);
            font-weight: 600;
            font-size: .83rem;
            text-decoration: none;
            transition: opacity .2s;
        }
        .forgot-link:hover { opacity: .75; text-decoration: underline; }
        .otp-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .otp-input {
            width: 48px;
            height: 54px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all .2s;
            outline: none;
            background: #f8fafc;
            font-family: 'Urbanist', sans-serif;
        }
        .otp-input:focus {
            border-color: var(--nec-primary);
            box-shadow: 0 0 0 3px rgba(252,96,17,.08);
            background: #fff;
        }
        .otp-input.filled {
            border-color: var(--nec-primary);
            background: #fff5f0;
        }
        .otp-timer { color: #94a3b8; font-size: .85rem; font-weight: 500; }
        .otp-resend { color: var(--nec-primary); font-weight: 600; cursor: pointer; text-decoration: none; font-size: .85rem; }
        .otp-resend:hover { opacity: .75; }
        .otp-resend.disabled { color: #94a3b8; cursor: not-allowed; pointer-events: none; }
        .demo-badge {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .82rem;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }
        .demo-badge code { background: #fef3c7; padding: 1px 6px; border-radius: 4px; font-weight: 700; }
        .alert-custom {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: .8rem;
            font-weight: 500;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: nowrap;
            padding-top: 10px;
        }
        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .75rem;
            font-weight: 600;
            color: #4A4B4D;
            padding: 5px 12px;
            border-radius: 20px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .trust-badge i { font-size: .7rem; }
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            background: rgba(252,96,17,.1);
            color: var(--nec-primary);
            border: 1px solid rgba(252,96,17,.2);
        }
        .form-check-input:checked {
            background-color: var(--nec-primary);
            border-color: var(--nec-primary);
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeIn .35s ease; }
        .back-to-site {
            position: fixed;
            top: 20px;
            left: 24px;
            z-index: 1000;
        }
        .back-to-site a {
            display: inline-flex;
            align-items: center;
            padding: 8px 18px;
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(8px);
            border-radius: 10px;
            color: #2C2220;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
            transition: all .2s;
        }
        .back-to-site a:hover {
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,.12);
            transform: translateY(-1px);
        }
        .back-to-site i { color: var(--nec-primary); }
        @media (max-width: 767.98px) {
            .login-wrapper { flex-direction: column; max-width: 420px; }
            .login-brand { width: 100%; padding: 30px 25px; min-height: auto; }
            .login-form-wrap { width: 100%; padding: 25px; }
            .brand-features { display: none; }
            .brand-icon-circle { width: 80px; height: 80px; }
            .brand-icon-circle img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
            .brand-title { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div class="back-to-site">
        <a href="{{ url('/') }}"><i class="fas fa-arrow-left me-1"></i> Back to Website</a>
    </div>
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="brand-icon-circle">
                <img src="{{ \App\Helpers\NecHelper::setting_get('logo', asset('assets/images/logos/neclogo.jpeg')) }}" alt="NEC South Sudan">
            </div>
            <div class="brand-title">South Sudan<br>National Elections Commission</div>
            <p class="brand-sub">{{ \App\Helpers\NecHelper::setting_get('site_tagline', 'Ensuring free, fair, and credible elections for the people of South Sudan') }}</p>
            <div class="brand-features">
                <div class="brand-feat"><i class="fas fa-check-circle" style="color:var(--nec-primary);"></i> Free &amp; Fair Elections</div>
                <div class="brand-feat"><i class="fas fa-shield-alt" style="color:var(--nec-primary);"></i> Transparency &amp; Integrity</div>
                <div class="brand-feat"><i class="fas fa-gavel" style="color:var(--nec-primary);"></i> Electoral Justice</div>
                <div class="brand-feat"><i class="fas fa-users" style="color:var(--nec-primary);"></i> Citizen Empowerment</div>
            </div>
        </div>
        <div class="login-form-wrap">
            <div class="login-form-inner">
                <div class="login-header">
                    <h4>{{ $needsOtp ?? false ? 'Verify Identity' : 'Sign In' }}</h4>
                    <p>{{ $needsOtp ?? false ? 'Enter the verification code sent to your email' : 'Access your account to continue' }}</p>
                </div>

                @if(session('error') || isset($error))
                <div class="alert alert-danger alert-custom" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i> {{ session('error') ?? $error ?? '' }}
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-custom" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
                    <i class="fas fa-check-circle" style="flex-shrink:0;"></i> {{ session('success') }}
                </div>
                @endif

                @if($otpSent ?? false)
                <div class="alert alert-success alert-custom" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
                    <i class="fas fa-check-circle" style="flex-shrink:0;"></i> Code sent to {{ $loginIdentifier }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <input type="hidden" name="mode" id="modeField" value="{{ $mode ?? 'email' }}">

                    <!-- ============ STEP 1: CREDENTIALS ============ -->
                    <div id="credentialsSection" style="{{ ($needsOtp ?? false) ? 'display:none;' : '' }}">
                        <!-- Mode Toggle -->
                        <div class="login-mode-toggle">
                            <button type="button" class="mode-btn {{ ($mode ?? 'email') === 'email' ? 'active' : '' }}" data-mode="email" onclick="switchMode('email')">
                                <i class="fas fa-envelope me-1"></i> Email
                            </button>
                            <button type="button" class="mode-btn {{ ($mode ?? 'email') === 'phone' ? 'active' : '' }}" data-mode="phone" onclick="switchMode('phone')">
                                <i class="fas fa-phone me-1"></i> Phone
                            </button>
                        </div>

                        <!-- Email Field -->
                        <div class="field-group" id="emailField" style="{{ ($mode ?? 'email') === 'phone' ? 'display:none;' : '' }}">
                            <div class="input-wrap">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email or Voter ID" value="{{ old('email', $email ?? '') }}" {{ ($mode ?? 'email') === 'phone' ? 'disabled' : '' }} autofocus>
                            </div>
                            @error('email')<div style="color:#ef4444;font-size:.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="field-group" id="phoneField" style="{{ ($mode ?? 'email') !== 'phone' ? 'display:none;' : '' }}">
                            <label class="form-label">
                                <span class="field-icon phone"><i class="fas fa-phone"></i></span>
                                <span class="field-label-text">Phone Number</span>
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+211 XX XXX XXXX" value="{{ old('phone') }}" {{ ($mode ?? 'email') !== 'phone' ? 'disabled' : '' }}>
                            </div>
                            @error('phone')<div style="color:#ef4444;font-size:.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <!-- Password -->
                        <div class="field-group">
                            <label class="form-label">
                                <span class="field-icon lock"><i class="fas fa-lock"></i></span>
                                <span class="field-label-text">Password</span>
                            </label>
                            <div class="input-wrap">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')<div style="color:#ef4444;font-size:.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" style="cursor:pointer;">
                                <label class="form-check-label small" for="remember" style="color:#4A4B4D;font-weight:500;cursor:pointer;">Remember me</label>
                            </div>
                            <a href="{{ route('forgot-password') }}" class="forgot-link">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn-jibu submit-btn" id="loginBtn">
                            <span class="btn-shimmer"></span>
                            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Sign In</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-2"></i>Signing in...</span>
                        </button>
                    </div>

                    <!-- ============ STEP 2: OTP (admin only) ============ -->
                    <div id="otpSection" style="{{ ($needsOtp ?? false) ? '' : 'display:none;' }}">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="admin-badge"><i class="fas fa-shield-halved"></i> Admin Verification</span>
                        </div>

                        @if(($otpSent ?? false) || ($needsOtp ?? false))
                        <div class="demo-badge">
                            <i class="fas fa-info-circle" style="flex-shrink:0;"></i>
                            Demo: Use code <code>000000</code>
                        </div>
                        @endif

                        <div class="field-group">
                            <label class="form-label">
                                <span class="field-icon key"><i class="fas fa-key"></i></span>
                                <span class="field-label-text">Verification Code</span>
                            </label>
                            <div class="otp-inputs" id="otpContainer">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="0" autofocus>
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="1">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="2">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="3">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="4">
                                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="5">
                            </div>
                            <input type="hidden" name="otp" id="otpHidden" value="">
                            @error('otp')<div style="color:#ef4444;font-size:.8rem;text-align:center;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn-jibu submit-btn" id="verifyBtn">
                            <span class="btn-shimmer"></span>
                            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Verify &amp; Sign In</span>
                            <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-2"></i>Verifying...</span>
                        </button>

                        <div class="text-center mt-3">
                            <span class="otp-timer" id="resendTimer">Resend code in <strong id="countdown">60</strong>s</span>
                            <a href="javascript:void(0)" class="otp-resend disabled" id="resendBtn" style="display:none;" onclick="resendOtp()">
                                <i class="fas fa-redo me-1"></i> Resend Code
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function switchMode(mode) {
        document.getElementById('modeField').value = mode;
        document.querySelectorAll('.mode-btn').forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });
        document.getElementById('emailField').style.display = mode === 'email' ? '' : 'none';
        document.getElementById('phoneField').style.display = mode === 'phone' ? '' : 'none';
        document.getElementById('emailField').querySelector('input').disabled = mode !== 'email';
        document.getElementById('phoneField').querySelector('input').disabled = mode !== 'phone';
        var target = mode === 'email' ? document.getElementById('emailField').querySelector('input') : document.getElementById('phoneField').querySelector('input');
        if (target) target.focus();
    }

    function togglePassword(btn) {
        var input = btn.closest('.input-wrap').querySelector('input');
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    function resendOtp() {
        var btn = document.getElementById('resendBtn');
        btn.classList.add('disabled');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
        var form = document.querySelector('form');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'resend';
        input.value = '1';
        form.appendChild(input);
        // Clear otp so controller knows this isn't a verification attempt
        var otpHidden = document.getElementById('otpHidden');
        if (otpHidden) otpHidden.value = '';
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Submit button loading state and OTP hidden field sync
        document.querySelectorAll('.submit-btn').forEach(function(btn) {
            btn.closest('form').addEventListener('submit', function() {
                updateHiddenOtp();
                btn.disabled = true;
                var text = btn.querySelector('.btn-text');
                var loader = btn.querySelector('.btn-loader');
                if (text) text.classList.add('d-none');
                if (loader) loader.classList.remove('d-none');
            });
        });

        // OTP input handling
        var otpInputs = document.querySelectorAll('.otp-input');
        var otpHidden = document.getElementById('otpHidden');
        var loginForm = document.querySelector('form');

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
                        var verifyBtn = document.getElementById('verifyBtn');
                        if (verifyBtn) { verifyBtn.click(); } else if (loginForm) { loginForm.submit(); }
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
        }

        function updateHiddenOtp() {
            var code = '';
            var inputs = document.querySelectorAll('.otp-input');
            inputs.forEach(function(inp) { code += inp.value; });
            var hidden = document.getElementById('otpHidden');
            if (hidden) hidden.value = code;
        }

        // Countdown timer
        var countdown = document.getElementById('countdown');
        var resendTimer = document.getElementById('resendTimer');
        var resendBtn = document.getElementById('resendBtn');
        if (countdown && resendTimer && resendBtn) {
            var seconds = parseInt(countdown.textContent) || 60;
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
</body>
</html>