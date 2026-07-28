<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Portal Login - NEC South Sudan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --nec-green: #2E8B57; --nec-green-dark: #1a5c38; --nec-gold: #D4AF37; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; min-height: 100vh;
            background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
            display: flex; align-items: center; justify-content: center; padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-wrapper { position: relative; z-index: 1; width: 100%; max-width: 480px; }
        .login-card {
            background: #fff; border-radius: 20px; box-shadow: 0 8px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);
            padding: 32px 32px 24px; text-align: center; color: #fff;
            position: relative; overflow: hidden;
        }
        .login-header::before {
            content: ''; position: absolute; top: -40px; right: -40px;
            width: 160px; height: 160px; border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .login-header::after {
            content: ''; position: absolute; bottom: -60px; left: -30px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .login-header img { height: 64px; margin-bottom: 12px; border-radius: 8px; position: relative; z-index: 1; }
        .login-header h3 { font-weight: 800; font-size: 20px; margin-bottom: 2px; position: relative; z-index: 1; }
        .login-header p { font-size: 13px; opacity: 0.75; margin-bottom: 0; position: relative; z-index: 1; }
        .login-body { padding: 28px 32px 32px; }
        .login-tabs {
            display: flex; gap: 4px; background: #f1f5f9; border-radius: 10px;
            padding: 4px; margin-bottom: 24px;
        }
        .login-tab {
            flex: 1; padding: 10px; text-align: center; border-radius: 8px;
            font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
            border: none; background: transparent; color: #64748b;
        }
        .login-tab.active {
            background: #fff; color: var(--nec-green); box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .login-tab:hover:not(.active) { color: #334155; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
        .form-control {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px; font-size: 14px; transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1);
        }
        .btn-nec {
            background: var(--nec-green); border-color: var(--nec-green);
            color: #fff; font-weight: 700; border-radius: 10px; padding: 12px;
            font-size: 15px; transition: all 0.2s;
        }
        .btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(46,139,87,0.3); }
        .login-footer { text-align: center; margin-top: 20px; }
        .login-footer a { color: var(--nec-green); font-weight: 600; text-decoration: none; font-size: 14px; }
        .login-footer a:hover { text-decoration: underline; }
        .login-footer p { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .flag-bar { display: flex; height: 4px; }
        .flag-bar .stripe { flex: 1; }
        .stripe-black { background: #000; } .stripe-red { background: #CE1126; }
        .stripe-green { background: #078930; } .stripe-blue { background: #0F47AF; }
        .stripe-gold { background: #FCDD09; }
        .input-group-text { border: 1.5px solid #e2e8f0; border-radius: 10px; background: #f8fafc; }
        .back-link { position: absolute; top: 20px; left: 20px; z-index: 2; }
        .back-link a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.2s; }
        .back-link a:hover { color: #fff; }
        @media (max-width: 576px) {
            .login-body { padding: 20px 20px 24px; }
            .login-header { padding: 24px 20px 20px; }
        }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i> Back to NEC</a>
    </div>

    <div class="login-wrapper">
        <div class="flag-bar mb-0" style="border-radius:20px 20px 0 0;overflow:hidden;">
            <div class="stripe stripe-black"></div>
            <div class="stripe stripe-red"></div>
            <div class="stripe stripe-green"></div>
            <div class="stripe stripe-blue"></div>
            <div class="stripe stripe-gold"></div>
        </div>

        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
                <h3>Voter Portal Login</h3>
                <p>Sign in to manage your voter registration</p>
            </div>
            <div class="login-body">
                @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                @if(session('expired'))
                    <div class="alert alert-warning d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-clock"></i> {{ session('expired') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="login-tabs">
                    <button class="login-tab active" data-tab="email" type="button">
                        <i class="fas fa-envelope me-1"></i> Email
                    </button>
                    <button class="login-tab" data-tab="voter-id" type="button">
                        <i class="fas fa-id-card me-1"></i> Voter ID
                    </button>
                </div>

                <form method="POST" action="{{ route('voter.portal.login.submit') }}" id="loginForm">
                    @csrf

                    <!-- Email Login Tab -->
                    <div class="tab-content active" id="tab-email">
                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required id="emailPassword">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('emailPassword', this)" style="border-radius:0 10px 10px 0;border-color:#e2e8f0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember" style="font-size:13px;">Remember me</label>
                            </div>
                            <a href="{{ route('voter.portal.forgot-password') }}" style="font-size:13px;color:var(--nec-green);font-weight:600;text-decoration:none;">Forgot Password?</a>
                        </div>
                    </div>

                    <!-- Voter ID Login Tab -->
                    <div class="tab-content" id="tab-voter-id">
                        <div class="mb-3">
                            <label class="form-label">Voter ID Number <span class="text-danger">*</span></label>
                            <input type="text" name="voter_id" class="form-control" placeholder="e.g., 1000001" value="{{ old('voter_id') }}">
                            @error('voter_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="pin_code" class="form-control" placeholder="Enter your PIN" id="pinCode" maxlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('pinCode', this)" style="border-radius:0 10px 10px 0;border-color:#e2e8f0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('pin_code') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <input type="hidden" name="login_method" id="loginMethod" value="email">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-nec">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>
                    </div>
                </form>

                <div class="login-footer">
                    <p class="mt-3">Don't have an account?</p>
                    <a href="{{ route('voter.portal.register') }}">Register here <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.querySelectorAll('.login-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.login-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
            this.classList.add('active');
            var tabId = this.getAttribute('data-tab');
            document.getElementById('tab-' + tabId).classList.add('active');
            document.getElementById('loginMethod').value = tabId === 'email' ? 'email' : 'voter_id';
        });
    });

    function togglePassword(id, btn) {
        var input = document.getElementById(id);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>
