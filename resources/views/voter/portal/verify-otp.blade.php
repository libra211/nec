<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account - NEC Voter Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --nec-green: #2E8B57; --nec-green-dark: #1a5c38; --nec-gold: #D4AF37; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
            display: flex; align-items: center; justify-content: center; padding: 30px 20px;
        }
        .reg-wrapper { width: 100%; max-width: 480px; }
        .reg-card { background: #fff; border-radius: 20px; box-shadow: 0 8px 40px rgba(0,0,0,0.15); overflow: hidden; }
        .reg-header { background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%); padding: 28px 32px 22px; text-align: center; color: #fff; }
        .reg-header i { font-size: 2.2rem; }
        .reg-header h3 { font-weight: 800; font-size: 19px; margin: 10px 0 4px; }
        .reg-header p { font-size: 13px; opacity: 0.8; margin-bottom: 0; }
        .reg-body { padding: 28px 32px 32px; }
        .otp-input { letter-spacing: 14px; font-size: 1.8rem; font-weight: 800; text-align: center; height: 66px; color: var(--nec-green-dark); border: 2px solid #d5e9dd; border-radius: 12px; }
        .otp-input:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.12); }
        .btn-verify { background: linear-gradient(135deg, var(--nec-green), var(--nec-green-dark)); color: #fff; font-weight: 700; border: none; border-radius: 10px; padding: 12px 20px; width: 100%; }
        .btn-verify:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(46,139,87,0.35); }
        .btn-verify:disabled { opacity: 0.5; }
        .resend-btn { background: none; border: none; color: var(--nec-green); font-weight: 600; font-size: 13px; text-decoration: underline; }
        .demo-hint { background: #f0fdf4; border: 1px dashed #86efac; color: #166534; border-radius: 8px; padding: 10px 14px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="reg-wrapper">
        <div class="reg-card">
            <div class="reg-header">
                <i class="fas fa-envelope-circle-check"></i>
                <h3>Verify Email Address</h3>
                <p>A 6-digit code was sent to <strong>{{ e($identifier ?? '') }}</strong></p>
            </div>
            <div class="reg-body">
                @if(!empty($success))
                    <div class="alert alert-success py-2">{{ $success }}</div>
                @endif
                @if(!empty($error))
                    <div class="alert alert-danger py-2">{{ $error }}</div>
                @endif

                <form method="POST" action="{{ route('voter.portal.register.verify-otp') }}" id="otpForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Verification Code</label>
                        <input type="text" name="otp" id="otpInput" class="form-control otp-input" inputmode="numeric" maxlength="6" autocomplete="one-time-code" autofocus required>
                    </div>
                    <button type="submit" class="btn btn-verify" id="otpSubmit" disabled>
                        <i class="fas fa-check-circle me-2"></i>Verify &amp; Activate Account
                    </button>
                </form>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <form method="POST" action="{{ route('voter.portal.register.resend-otp') }}">
                        @csrf
                        <button type="submit" class="resend-btn"><i class="fas fa-rotate-right me-1"></i>Resend code</button>
                    </form>
                    <a href="{{ route('voter.portal.register') }}" class="text-muted small">Start over</a>
                </div>

                <div class="demo-hint mt-4">
                    <i class="fas fa-circle-info me-1"></i> For verification, use the 6-digit code sent to your email. In the demo environment the code <strong>000000</strong> also works.
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('otpInput');
            const submit = document.getElementById('otpSubmit');
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
                submit.disabled = this.value.length !== 6;
            });
        })();
    </script>
</body>
</html>