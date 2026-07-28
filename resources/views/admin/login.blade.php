<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — NEC South Sudan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; font-family:'Inter',system-ui,-apple-system,sans-serif; }

        /* ── Split Layout ── */
        .auth-wrapper { display:flex; min-height:100vh; }

        /* Left Branding Panel */
        .auth-brand {
            flex:0 0 45%; max-width:45%;
            background:linear-gradient(160deg,#0a1628 0%,#0f2247 40%,#1a3c8f 70%,#1d6b4f 100%);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding:3rem; position:relative; overflow:hidden;
            color:#fff;
        }
        .auth-brand::before {
            content:''; position:absolute; top:-30%; right:-20%; width:500px; height:500px;
            border-radius:50%; background:radial-gradient(circle,rgba(212,175,55,0.08) 0%,transparent 70%);
        }
        .auth-brand::after {
            content:''; position:absolute; bottom:-25%; left:-15%; width:400px; height:400px;
            border-radius:50%; background:radial-gradient(circle,rgba(46,139,87,0.1) 0%,transparent 70%);
        }
        .auth-brand > * { position:relative; z-index:2; }
        .brand-logo { width:120px; height:120px; border-radius:20px; object-fit:cover; margin-bottom:2rem;
            box-shadow:0 8px 32px rgba(0,0,0,0.3); border:3px solid rgba(255,255,255,0.1); }
        .brand-title { font-size:1.75rem; font-weight:800; text-align:center; margin-bottom:.5rem; letter-spacing:-0.5px; }
        .brand-subtitle { font-size:0.95rem; opacity:.7; text-align:center; margin-bottom:2.5rem; max-width:320px; line-height:1.6; }
        .brand-features { list-style:none; padding:0; max-width:320px; }
        .brand-features li { display:flex; align-items:flex-start; gap:12px; margin-bottom:1.25rem; font-size:.88rem; opacity:.85; line-height:1.5; }
        .brand-features li i { margin-top:3px; font-size:.8rem; color:#d4af37; flex-shrink:0; }
        .brand-divider { width:60px; height:3px; background:linear-gradient(90deg,#d4af37,#2E8B57); border-radius:2px; margin-bottom:2rem; }
        .brand-footer { margin-top:auto; padding-top:2rem; text-align:center; opacity:.4; font-size:.75rem; }

        /* Right Form Panel */
        .auth-form-panel {
            flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding:2.5rem; background:#f8f9fb; position:relative;
        }
        .auth-form-panel > * { position:relative; z-index:2; }
        .form-container { width:100%; max-width:420px; }
        .form-header { text-align:center; margin-bottom:2.5rem; }
        .form-header h2 { font-size:1.6rem; font-weight:800; color:#0a1628; margin-bottom:.35rem; }
        .form-header p { font-size:.9rem; color:#6b7280; }

        /* Form Elements */
        .form-label { font-size:.8rem; font-weight:600; color:#374151; margin-bottom:.4rem; letter-spacing:.3px; }
        .input-wrapper { position:relative; }
        .input-wrapper .input-icon {
            position:absolute; left:14px; top:50%; transform:translateY(-50%);
            color:#9ca3af; font-size:.85rem; pointer-events:none; transition:color .2s;
        }
        .input-wrapper input {
            width:100%; padding:12px 14px 12px 44px; border:1.5px solid #e5e7eb; border-radius:10px;
            font-size:.9rem; background:#fff; color:#111827; transition:all .2s; outline:none;
        }
        .input-wrapper input:focus { border-color:#1a3c8f; box-shadow:0 0 0 3px rgba(26,60,143,.1); }
        .input-wrapper input:focus + .input-icon,
        .input-wrapper input:focus ~ .input-icon { color:#1a3c8f; }
        .input-wrapper input::placeholder { color:#9ca3af; }
        .password-toggle {
            position:absolute; right:14px; top:50%; transform:translateY(-50%);
            background:none; border:none; color:#9ca3af; cursor:pointer; padding:4px; font-size:.9rem;
            transition:color .2s;
        }
        .password-toggle:hover { color:#374151; }

        /* Checkbox & Links */
        .form-check-input { width:1.1em; height:1.1em; border-radius:4px; border-color:#d1d5db; }
        .form-check-input:checked { background-color:#1a3c8f; border-color:#1a3c8f; }
        .form-check-label { font-size:.82rem; color:#4b5563; }
        .forgot-link { font-size:.82rem; color:#1a3c8f; text-decoration:none; font-weight:600; }
        .forgot-link:hover { color:#0f2247; text-decoration:underline; }

        /* Submit Button */
        .btn-signin {
            width:100%; padding:13px; border:none; border-radius:10px; font-size:.95rem; font-weight:700;
            background:linear-gradient(135deg,#1a3c8f 0%,#1d6b4f 100%); color:#fff;
            cursor:pointer; transition:all .3s; letter-spacing:.3px; position:relative; overflow:hidden;
        }
        .btn-signin:hover { transform:translateY(-1px); box-shadow:0 8px 25px rgba(26,60,143,.3); }
        .btn-signin:active { transform:translateY(0); }
        .btn-signin:disabled { opacity:.6; cursor:not-allowed; transform:none; }
        .btn-signin .spinner-border { width:1rem; height:1rem; border-width:.15em; }

        /* Alert */
        .auth-alert {
            padding:12px 16px; border-radius:10px; font-size:.82rem; margin-bottom:1.25rem;
            display:flex; align-items:center; gap:10px; border:none;
        }
        .auth-alert-danger { background:#fef2f2; color:#991b1b; }
        .auth-alert-success { background:#f0fdf4; color:#166534; }
        .auth-alert i { font-size:.9rem; flex-shrink:0; }

        /* Footer Links */
        .form-footer { text-align:center; margin-top:2rem; padding-top:1.5rem; border-top:1px solid #e5e7eb; }
        .form-footer a { font-size:.82rem; color:#6b7280; text-decoration:none; }
        .form-footer a:hover { color:#1a3c8f; text-decoration:underline; }
        .form-footer .back-link { display:inline-flex; align-items:center; gap:6px; }

        /* South Sudan Flag Bar */
        .flag-bar {
            position:absolute; bottom:0; left:0; right:0; height:4px; display:flex;
        }
        .flag-bar span { flex:1; }
        .flag-black { background:#000; }
        .flag-red { background:#ce1126; }
        .flag-green { background:#078930; }
        .flag-white { background:#fff; }

        /* Responsive */
        @media (max-width:991.98px) {
            .auth-wrapper { flex-direction:column; }
            .auth-brand {
                flex:none; max-width:100%; padding:2.5rem 2rem;
                min-height:auto;
            }
            .brand-logo { width:80px; height:80px; border-radius:14px; margin-bottom:1.5rem; }
            .brand-title { font-size:1.4rem; }
            .brand-subtitle { font-size:.85rem; margin-bottom:1.5rem; }
            .brand-features { display:none; }
            .brand-footer { display:none; }
            .auth-form-panel { padding:2rem 1.5rem; }
        }
        @media (max-width:480px) {
            .auth-brand { padding:2rem 1.5rem; }
            .auth-form-panel { padding:1.5rem 1rem; }
            .form-container { max-width:100%; }
        }

        /* Animations */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .form-container { animation:fadeInUp .5s ease-out; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .auth-brand > * { animation:fadeIn .6s ease-out; }
        .auth-brand > *:nth-child(2) { animation-delay:.1s; }
        .auth-brand > *:nth-child(3) { animation-delay:.2s; }
        .auth-brand > *:nth-child(4) { animation-delay:.3s; }
    </style>
</head>
<body>
    <div class="auth-wrapper">

        {{-- Left Panel: Branding --}}
        <div class="auth-brand">
            <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo" class="brand-logo">
            <div class="brand-divider"></div>
            <h1 class="brand-title">National Elections Commission</h1>
            <p class="brand-subtitle">Secure administration portal for managing South Sudan's electoral processes and democratic institutions.</p>

            <ul class="brand-features">
                <li>
                    <i class="fas fa-shield-halved"></i>
                    <span>Enterprise-grade security with role-based access control and audit logging</span>
                </li>
                <li>
                    <i class="fas fa-chart-line"></i>
                    <span>Real-time dashboards for voter registration, elections, and constituency management</span>
                </li>
                <li>
                    <i class="fas fa-globe-africa"></i>
                    <span>Geographic information system covering all 10 states and 80 counties</span>
                </li>
                <li>
                    <i class="fas fa-users-gear"></i>
                    <span>Multi-role access for coordinators, officers, editors, and administrators</span>
                </li>
            </ul>

            <div class="brand-footer">
                &copy; {{ date('Y') }} National Elections Commission<br>
                Republic of South Sudan
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="auth-form-panel">
            <div class="form-container">
                <div class="form-header">
                    <h2>Welcome back</h2>
                    <p>Sign in to your NEC administration account</p>
                </div>

                @if(session('error'))
                <div class="auth-alert auth-alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="auth-alert auth-alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="you@nec.gov.ss"
                                   value="{{ old('email') }}" required autofocus autocomplete="email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                   required autocomplete="current-password">
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-signin" id="submitBtn">
                        <span id="btnText">Sign In</span>
                        <span id="btnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
                    </button>
                </form>

                <div class="form-footer">
                    <a href="{{ route('home') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to NEC Website
                    </a>
                </div>
            </div>

            <div class="flag-bar">
                <span class="flag-black"></span>
                <span class="flag-red"></span>
                <span class="flag-green"></span>
                <span class="flag-white"></span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            var icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('btnText').classList.add('d-none');
            document.getElementById('btnSpinner').classList.remove('d-none');
        });
    </script>
</body>
</html>
