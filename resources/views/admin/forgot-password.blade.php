<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NEC South Sudan - Forgot Password</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="{{ \App\Helpers\NecHelper::setting_get('favicon', asset('assets/images/logos/neclogo.jpeg')) }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ \App\Helpers\NecHelper::setting_get('favicon', asset('assets/images/logos/neclogo.jpeg')) }}">
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
            width: 38%;
            background: #2C2220;
            padding: 40px 28px;
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
        .brand-title {
            font-weight: 800;
            color: #fff;
            font-size: 1.15rem;
            line-height: 1.3;
            margin-bottom: 4px;
        }
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
            width: 62%;
            padding: 40px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        .login-form-inner { max-width: 380px; width: 100%; margin: 0 auto; }
        .login-header { margin-bottom: 24px; }
        .login-header h4 { font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .login-header p { color: #6b7280; font-size: .88rem; margin: 0; }
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
        .input-wrap .form-control:disabled { opacity: .6; background: #f1f5f9; }
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
        .alert-custom {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-link {
            color: #94a3b8;
            font-size: .83rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .back-link:hover { color: var(--nec-primary); }
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            font-size: .8rem;
            font-weight: 500;
            margin: 20px 0;
        }
        .divider::before,
        .divider::after {
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
    <div class="login-wrapper">
        <div class="login-brand">
            <div class="brand-icon-circle">
                <img src="{{ \App\Helpers\NecHelper::setting_get('logo', asset('assets/images/logos/neclogo.jpeg')) }}" alt="NEC South Sudan">
            </div>
            <div class="brand-title">South Sudan<br>National Elections Commission</div>
            <p class="brand-sub">Ensuring free, fair, and credible elections for the people of South Sudan</p>
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
                    <h4>Reset Password</h4>
                    <p>Enter your email to receive a reset code</p>
                </div>

                @if(session('error') || isset($error))
                <div class="alert alert-danger alert-custom" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') ?? $error ?? '' }}
                </div>
                @endif

                @if($otpSent ?? false)
                <div class="alert alert-success alert-custom" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;">
                    <i class="fas fa-check-circle"></i> Reset code sent to {{ $email }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.forgot-password') }}">
                    @csrf
                    <div class="field-group" style="margin-bottom:18px;">
                        <label class="form-label" style="display:flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;color:#1f2937;margin-bottom:6px;">
                            <span class="field-icon" style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:6px;font-size:.6rem;background:#dbeafe;color:#3b82f6;"><i class="fas fa-envelope"></i></span>
                            Email Address
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email', $email ?? '') }}" {{ isset($otpSent) ? 'disabled' : '' }} required autofocus>
                        </div>
                        @error('email')<div style="color:#ef4444;font-size:.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn-jibu">
                        <span class="btn-shimmer"></span>
                        <span class="btn-text">{{ isset($otpSent) ? 'Resend Reset Code' : 'Send Reset Code' }}</span>
                    </button>
                </form>

                <div style="text-align:center;margin-top:16px;">
                    <a href="{{ route('admin.login') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>