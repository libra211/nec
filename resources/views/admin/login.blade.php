<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — NEC South Sudan</title>
    <meta name="description" content="Sign in to the NEC South Sudan administration portal.">
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        :root { --nec-green:#00914c; --nec-green-dark:#006b38; --nec-black:#0a1628; --nec-gold:#d4af37; }
        body { font-family:'Inter',sans-serif; background:#f0f2f5; min-height:100vh; display:flex; flex-direction:column; }

        /* Login Hero */
        .login-hero {
            background:linear-gradient(135deg,var(--nec-black) 0%,#1a3c8f 50%,var(--nec-green) 100%);
            padding:3rem 0 4rem; text-align:center; color:#fff; position:relative; overflow:hidden;
        }
        .login-hero::before { content:''; position:absolute; top:-40%; right:-10%; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle,rgba(212,175,55,0.07) 0%,transparent 70%); }
        .login-hero::after { content:''; position:absolute; bottom:-30%; left:-10%; width:400px; height:400px; border-radius:50%; background:radial-gradient(circle,rgba(255,255,255,0.04) 0%,transparent 70%); }
        .login-hero > * { position:relative; z-index:2; }
        .login-hero img { width:90px; height:90px; border-radius:16px; object-fit:cover; margin-bottom:1rem; box-shadow:0 8px 32px rgba(0,0,0,0.3); border:3px solid rgba(255,255,255,0.15); }
        .login-hero h1 { font-size:1.8rem; font-weight:800; margin-bottom:.3rem; }
        .login-hero p { opacity:.7; font-size:.95rem; margin-bottom:0; }

        /* Login Card */
        .login-card-wrap { margin-top:-50px; position:relative; z-index:10; padding-bottom:3rem; }
        .login-card {
            background:#fff; border-radius:20px; overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,0.1); max-width:480px; margin:0 auto;
        }
        .login-card-body { padding:2.5rem; }

        /* Tabs */
        .login-tabs {
            display:flex; gap:4px; background:#f1f5f9; border-radius:12px; padding:4px; margin-bottom:2rem;
        }
        .login-tab-btn {
            flex:1; padding:11px 8px; border:none; border-radius:10px; font-size:.82rem; font-weight:600;
            cursor:pointer; transition:all .25s; background:transparent; color:#64748b; display:flex; align-items:center; justify-content:center; gap:6px;
        }
        .login-tab-btn.active { background:#fff; color:var(--nec-green); box-shadow:0 2px 8px rgba(0,0,0,0.06); }
        .login-tab-btn:hover:not(.active) { color:#334155; }
        .tab-pane-login { display:none; }
        .tab-pane-login.active { display:block; animation:fadeIn .3s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* Form */
        .form-group { margin-bottom:1.25rem; }
        .form-label-custom {
            display:block; font-size:.78rem; font-weight:600; color:#374151; margin-bottom:6px;
            letter-spacing:.3px; text-transform:uppercase;
        }
        .input-icon-wrap { position:relative; }
        .input-icon-wrap .ico {
            position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.85rem;
            pointer-events:none; transition:color .2s;
        }
        .input-icon-wrap input, .input-icon-wrap select {
            width:100%; padding:12px 44px 12px 44px; border:1.5px solid #e5e7eb; border-radius:12px;
            font-size:.9rem; background:#fff; color:#111827; transition:all .25s; outline:none;
            font-family:'Inter',sans-serif;
        }
        .input-icon-wrap input:focus, .input-icon-wrap select:focus {
            border-color:var(--nec-green); box-shadow:0 0 0 4px rgba(0,145,76,.08);
        }
        .input-icon-wrap input:focus ~ .ico, .input-icon-wrap input:focus + .ico { color:var(--nec-green); }
        .input-icon-wrap input::placeholder { color:#b0b8c4; }
        .pw-toggle {
            position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none;
            color:#9ca3af; cursor:pointer; padding:4px; font-size:.9rem; transition:color .2s; z-index:2;
        }
        .pw-toggle:hover { color:#374151; }

        /* Validation States */
        .input-icon-wrap input.is-invalid { border-color:#dc3545; }
        .input-icon-wrap input.is-valid { border-color:#198754; }
        .invalid-feedback { font-size:.75rem; color:#dc3545; margin-top:4px; display:none; }
        .input-icon-wrap input.is-invalid ~ .invalid-feedback { display:block; }
        .valid-check { position:absolute; right:44px; top:50%; transform:translateY(-50%); color:#198754; font-size:.8rem; display:none; }
        .input-icon-wrap input.is-valid ~ .valid-check { display:block; }

        /* Checkbox */
        .form-check-custom { display:flex; align-items:center; gap:8px; }
        .form-check-custom .form-check-input { width:1.15em; height:1.15em; border-radius:4px; border-color:#d1d5db; margin-top:0; }
        .form-check-custom .form-check-input:checked { background-color:var(--nec-green); border-color:var(--nec-green); }
        .form-check-custom label { font-size:.82rem; color:#4b5563; margin-bottom:0; cursor:pointer; }

        /* Links */
        .link-green { color:var(--nec-green); font-weight:600; text-decoration:none; font-size:.82rem; transition:color .2s; }
        .link-green:hover { color:var(--nec-green-dark); text-decoration:underline; }

        /* Submit */
        .btn-login-submit {
            width:100%; padding:14px; border:none; border-radius:12px; font-size:.95rem; font-weight:700;
            background:linear-gradient(135deg,var(--nec-green) 0%,var(--nec-green-dark) 100%);
            color:#fff; cursor:pointer; transition:all .3s; letter-spacing:.3px; position:relative; overflow:hidden;
            font-family:'Inter',sans-serif;
        }
        .btn-login-submit:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(0,145,76,.3); }
        .btn-login-submit:active { transform:translateY(0); }
        .btn-login-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; box-shadow:none; }
        .btn-login-submit .spinner-border { width:1rem; height:1rem; border-width:.15em; }

        /* Alert */
        .auth-alert {
            padding:12px 16px; border-radius:12px; font-size:.82rem; margin-bottom:1.25rem;
            display:flex; align-items:center; gap:10px; border:none; font-weight:500;
        }
        .auth-alert-danger { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
        .auth-alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
        .auth-alert i { font-size:.9rem; flex-shrink:0; }

        /* Footer */
        .login-footer { text-align:center; padding:1.5rem 2.5rem 2rem; border-top:1px solid #f1f5f9; }
        .login-footer p { font-size:.85rem; color:#6b7280; margin-bottom:6px; }
        .login-footer a { font-size:.85rem; }

        /* Responsive */
        @media (max-width:576px) {
            .login-hero { padding:2rem 1rem 3rem; }
            .login-hero img { width:70px; height:70px; }
            .login-hero h1 { font-size:1.4rem; }
            .login-card-body { padding:1.75rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ═══════ HEADER ═══════ --}}

    {{-- Top Bar --}}
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-left">
                        <span><i class="fas fa-envelope"></i> info@nec.gov.ss</span>
                        <span class="ms-3"><i class="fas fa-phone"></i> +211 (0) 912 345 678</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-bar-right">
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-x-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flag Bar --}}
    <div class="top-flag-bar">
        <div class="stripe stripe-black"></div>
        <div class="stripe stripe-red"></div>
        <div class="stripe stripe-green"></div>
        <div class="stripe stripe-blue"></div>
        <div class="stripe stripe-gold"></div>
    </div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-xl nec-navbar sticky-top" id="necNavbar">
        <div class="container">
            <a class="navbar-brand py-2" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" style="width:100px;height:80px;border-radius:3px;">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#necMainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="necMainNav">
                <ul class="navbar-nav me-auto align-items-xl-center gap-xl-1 mb-2 mb-xl-0">
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('about.index') }}">About NEC</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('elections.index') }}">Elections</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('constituencies.index') }}">Constituencies</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('parties.index') }}">Parties</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3" href="{{ route('contact.index') }}">Contact</a></li>
                </ul>
                <ul class="navbar-nav align-items-xl-center">
                    <li class="nav-item">
                        <a href="{{ route('voter.portal.login') }}" class="nav-btn-login"><i class="fas fa-user me-1"></i> Voter Portal</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.login') }}" class="nav-btn-login" style="background:var(--nec-green);"><i class="fas fa-lock me-1"></i> Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ═══════ LOGIN HERO ═══════ --}}
    <section class="login-hero">
        <div class="container">
            <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC Logo">
            <h1>National Elections Commission</h1>
            <p>Sign in to access the administration portal</p>
        </div>
    </section>

    {{-- ═══════ LOGIN CARD ═══════ --}}
    <section class="login-card-wrap">
        <div class="container">
            <div class="login-card">
                <div class="login-card-body">

                    {{-- Alerts --}}
                    @if(session('error'))
                    <div class="auth-alert auth-alert-danger" id="alertBox">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="auth-alert auth-alert-success" id="alertBox">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    {{-- Tabs --}}
                    <div class="login-tabs">
                        <button class="login-tab-btn active" data-tab="admin" type="button">
                            <i class="fas fa-user-shield"></i> Admin
                        </button>
                        <button class="login-tab-btn" data-tab="voter" type="button">
                            <i class="fas fa-vote-yea"></i> Voter Portal
                        </button>
                    </div>

                    {{-- ─── Admin Login ─── --}}
                    <div class="tab-pane-login active" id="pane-admin">
                        <form action="{{ route('admin.login.submit') }}" method="POST" id="adminForm" novalidate>
                            @csrf
                            <div class="form-group">
                                <label class="form-label-custom" for="adminEmail">Email Address</label>
                                <div class="input-icon-wrap">
                                    <input type="email" id="adminEmail" name="email" placeholder="you@nec.gov.ss"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <i class="fas fa-envelope ico"></i>
                                    <i class="fas fa-check-circle valid-check"></i>
                                    <div class="invalid-feedback">Please enter a valid email address</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom" for="adminPassword">Password</label>
                                <div class="input-icon-wrap">
                                    <input type="password" id="adminPassword" name="password" placeholder="Enter your password"
                                           required autocomplete="current-password" minlength="4">
                                    <i class="fas fa-lock ico"></i>
                                    <button type="button" class="pw-toggle" onclick="togglePw('adminPassword','toggleAdmin')" tabindex="-1">
                                        <i class="fas fa-eye" id="toggleAdmin"></i>
                                    </button>
                                    <div class="invalid-feedback">Password is required</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check-custom">
                                    <input type="checkbox" class="form-check-input" id="adminRemember" name="remember">
                                    <label for="adminRemember">Remember me</label>
                                </div>
                                <a href="#" class="link-green">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn-login-submit" id="adminSubmit">
                                <span id="adminBtnText"><i class="fas fa-sign-in-alt me-2"></i>Sign In to Admin</span>
                                <span id="adminBtnSpin" class="d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
                            </button>
                        </form>
                    </div>

                    {{-- ─── Voter Login ─── --}}
                    <div class="tab-pane-login" id="pane-voter">
                        <form action="{{ route('voter.portal.login.submit') }}" method="POST" id="voterForm" novalidate>
                            @csrf

                            <div class="form-group">
                                <label class="form-label-custom" for="voterLogin">Email or Voter ID</label>
                                <div class="input-icon-wrap">
                                    <input type="text" id="voterLogin" name="login" placeholder="you@email.com or voter ID"
                                           value="{{ old('login') }}" required autocomplete="username">
                                    <i class="fas fa-user ico"></i>
                                    <i class="fas fa-check-circle valid-check"></i>
                                    <div class="invalid-feedback">Please enter your email or voter ID</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label-custom" for="voterPassword">Password</label>
                                <div class="input-icon-wrap">
                                    <input type="password" id="voterPassword" name="password" placeholder="Enter your password"
                                           required autocomplete="current-password" minlength="4">
                                    <i class="fas fa-lock ico"></i>
                                    <button type="button" class="pw-toggle" onclick="togglePw('voterPassword','toggleVoter')" tabindex="-1">
                                        <i class="fas fa-eye" id="toggleVoter"></i>
                                    </button>
                                    <div class="invalid-feedback">Password is required</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check-custom">
                                    <input type="checkbox" class="form-check-input" id="voterRemember" name="remember">
                                    <label for="voterRemember">Remember me</label>
                                </div>
                                <a href="{{ route('voter.portal.forgot-password') }}" class="link-green">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn-login-submit" id="voterSubmit">
                                <span id="voterBtnText"><i class="fas fa-sign-in-alt me-2"></i>Sign In to Voter Portal</span>
                                <span id="voterBtnSpin" class="d-none"><span class="spinner-border spinner-border-sm me-2"></span>Signing in...</span>
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <p style="font-size:.85rem;color:#6b7280;margin-bottom:0;">
                                Don't have an account?
                                <a href="{{ route('voter.portal.register') }}" class="link-green">Register here <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i></a>
                            </p>
                        </div>
                    </div>

                </div>

                <div class="login-footer">
                    <p class="mb-1"><i class="fas fa-arrow-left me-1"></i> <a href="{{ route('home') }}" class="link-green">Back to NEC Website</a></p>
                    <p style="font-size:.75rem;color:#9ca3af;">&copy; {{ date('Y') }} National Elections Commission — Republic of South Sudan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════ FOOTER ═══════ --}}
    <footer class="nec-footer" style="margin-top:auto;">
        <div class="footer-main">
            <div class="container">
                <div class="row g-4 align-items-stretch">
                    <div class="col-md-6 col-lg-4 d-flex">
                        <div class="footer-widget w-100">
                            <div class="footer-brand mb-3" style="display:flex;align-items:center;gap:14px;">
                                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" width="70" height="70" style="object-fit:contain;border-radius:5px;">
                                <div>
                                    <span style="display:block;font-size:1.4rem;font-weight:900;color:var(--nec-gold);line-height:1;">NEC</span>
                                    <span style="display:block;width:24px;height:2px;background:var(--nec-gold);margin:5px 0;border-radius:1px;"></span>
                                    <span style="font-size:0.6rem;text-transform:uppercase;letter-spacing:3px;color:rgba(255,255,255,.45);">Republic of South Sudan</span>
                                </div>
                            </div>
                            <p class="text-white-50 mb-3" style="line-height:1.8;font-size:.85rem;">The National Elections Commission is the independent constitutional body responsible for organizing and supervising elections in South Sudan.</p>
                            <div class="footer-contact mb-3">
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-2" style="color:var(--nec-gold);"></i>Juba, South Sudan</p>
                                <p class="mb-1"><i class="fas fa-phone me-2" style="color:var(--nec-gold);"></i>+211 (0) 912 345 678</p>
                                <p class="mb-1"><i class="fas fa-envelope me-2" style="color:var(--nec-gold);"></i>info@nec.gov.ss</p>
                            </div>
                            <div class="footer-social">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-x-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:.88rem;">Quick Links</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('about.index') }}">About NEC</a></li>
                                <li><a href="{{ route('elections.calendar') }}">Election Calendar</a></li>
                                <li><a href="{{ route('parties.index') }}">Political Parties</a></li>
                                <li><a href="{{ route('candidates.index') }}">Candidates</a></li>
                                <li><a href="{{ route('constituencies.index') }}">Constituencies</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:.88rem;">For Voters</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('voter.register') }}">Voter Registration</a></li>
                                <li><a href="{{ route('voter.verify') }}">Verify Registration</a></li>
                                <li><a href="{{ route('voter.polling-finder') }}">Find Polling Station</a></li>
                                <li><a href="{{ route('voter.education') }}">Voter Education</a></li>
                                <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:.88rem;">Support</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                                <li><a href="{{ route('help.index') }}">Help Center</a></li>
                                <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('legal.terms-of-use') }}">Terms of Use</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright mb-0">&copy; {{ date('Y') }} National Elections Commission — South Sudan. All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="footer-bottom-links mb-0">
                            <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('legal.terms-of-use') }}">Terms of Service</a></li>
                            <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    /* ── Tab Switching ── */
    document.querySelectorAll('.login-tab-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.login-tab-btn').forEach(function(b){b.classList.remove('active')});
            document.querySelectorAll('.tab-pane-login').forEach(function(p){p.classList.remove('active')});
            this.classList.add('active');
            document.getElementById('pane-'+this.dataset.tab).classList.add('active');
        });
    });

    /* ── Password Toggle ── */
    function togglePw(inputId, iconId){
        var inp=document.getElementById(inputId), ico=document.getElementById(iconId);
        if(inp.type==='password'){inp.type='text';ico.classList.replace('fa-eye','fa-eye-slash');}
        else{inp.type='password';ico.classList.replace('fa-eye-slash','fa-eye');}
    }

    /* ── Real-time Validation ── */
    function validateField(input){
        var val=input.value.trim();
        if(input.required && !val){input.classList.add('is-invalid');input.classList.remove('is-valid');return false;}
        if(input.type==='email' && val){
            var ok=/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
            if(!ok){input.classList.add('is-invalid');input.classList.remove('is-valid');return false;}
        }
        if(input.minLength && val.length<input.minLength){input.classList.add('is-invalid');input.classList.remove('is-valid');return false;}
        input.classList.remove('is-invalid');input.classList.add('is-valid');return true;
    }

    document.querySelectorAll('.input-icon-wrap input[required]').forEach(function(inp){
        inp.addEventListener('blur',function(){validateField(this);});
        inp.addEventListener('input',function(){
            if(this.classList.contains('is-invalid')) validateField(this);
        });
    });

    /* ── Admin Form Submit ── */
    document.getElementById('adminForm').addEventListener('submit',function(e){
        var email=document.getElementById('adminEmail');
        var pass=document.getElementById('adminPassword');
        var valid=true;
        if(!validateField(email)) valid=false;
        if(!validateField(pass)) valid=false;
        if(!valid){e.preventDefault();return;}
        document.getElementById('adminSubmit').disabled=true;
        document.getElementById('adminBtnText').classList.add('d-none');
        document.getElementById('adminBtnSpin').classList.remove('d-none');
    });

    /* ── Voter Form Submit ── */
    document.getElementById('voterForm').addEventListener('submit',function(e){
        var login=document.getElementById('voterLogin');
        var pass=document.getElementById('voterPassword');
        var valid=true;
        if(!validateField(login)) valid=false;
        if(!validateField(pass)) valid=false;
        if(!valid){e.preventDefault();return;}
        document.getElementById('voterSubmit').disabled=true;
        document.getElementById('voterBtnText').classList.add('d-none');
        document.getElementById('voterBtnSpin').classList.remove('d-none');
    });

    /* ── Auto-dismiss Alert ── */
    var alertEl=document.getElementById('alertBox');
    if(alertEl){setTimeout(function(){alertEl.style.transition='opacity .5s';alertEl.style.opacity='0';setTimeout(function(){alertEl.remove()},500);},6000);}
    </script>
</body>
</html>
