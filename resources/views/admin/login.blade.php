@extends('layouts.app')

@section('hero')
<section class="hero-section hero-page" style="min-height:40vh;background:linear-gradient(135deg,var(--nec-black) 0%,#0d2e4a 40%,#1a4a2e 100%);padding:50px 0 60px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div class="hero-bg-animation"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="text-center">
            <div class="hero-badge mb-3">
                <i class="fas fa-shield-halved"></i> Secure Access Portal
            </div>
            <h1 class="hero-title" style="font-size:2rem;">Login to NEC</h1>
            <p class="hero-subtitle" style="max-width:500px;margin:0 auto;">Access the National Elections Commission administrative dashboard</p>
        </div>
    </div>
</section>
@endsection

@section('extra_head')
<style>
    body.login-page{background:var(--nec-bg);margin:0;padding:0;}
    .main-content{padding:0;margin:0;}
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
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-lock me-1"></i> Secure Authentication</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">All sessions are encrypted and monitored for security.</p>
                        </div>
                        <div style="padding:18px;background:rgba(255,255,255,.08);border-radius:10px;border-left:3px solid var(--nec-gold);">
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-database me-1"></i> Real-time Data</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">Monitor election data, voter counts, and system health in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM -->
            <div class="col-lg-5 d-flex align-items-stretch">
                <div class="login-form-card w-100" style="background:#fff;padding:50px 35px;border-radius:0 12px 12px 0;border:1px solid #e9ecef;border-left:none;box-shadow:0 8px 30px rgba(0,0,0,.1);">
                    <div class="text-center mb-4">
                        <h3 style="font-size:1.6rem;font-weight:800;color:var(--nec-black);margin-bottom:6px;">Sign In</h3>
                        <p style="color:var(--nec-text-light);font-size:0.88rem;">Enter your credentials to access the admin portal</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px;background:#fff5f5;border:1px solid #f5c6cb;color:#721c24;font-size:0.88rem;">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-envelope" style="color:#999;"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" required autofocus style="border-left:none;font-size:0.92rem;">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-lock" style="color:#999;"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required style="border-left:none;font-size:0.92rem;border-right:none;">
                                <button class="btn" type="button" id="togglePassword" style="background:transparent;border:1px solid #dee2e6;border-left:none;"><i class="fas fa-eye" style="color:#999;"></i></button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4" style="font-size:0.88rem;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember" style="color:#666;">Remember me</label>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn" class="btn fw-bold w-100" style="background:var(--nec-green);color:#fff;padding:12px;border:none;border-radius:6px;font-size:1rem;transition:all .3s;">
                            <span id="btnText"><i class="fas fa-sign-in-alt me-1"></i> Sign In</span>
                            <span id="btnLoader" class="d-none"><i class="fas fa-spinner fa-spin me-1"></i> Signing In...</span>
                        </button>
                    </form>

                    <hr style="margin:25px 0;border-color:#eee;">

                    <div class="text-center">
                        <p style="color:#999;font-size:0.85rem;margin-bottom:0;">Need help? <a href="{{ route('contact.index') }}" style="color:var(--nec-green);font-weight:600;text-decoration:none;">Contact Support</a></p>
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
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoader.classList.remove('d-none');
        submitBtn.style.opacity = '0.7';
    });
});
</script>
@endsection
