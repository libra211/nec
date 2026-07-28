@extends('layouts.app')

@section('hero')
<section class="hero-section hero-page" style="min-height:40vh;background:linear-gradient(135deg,var(--nec-black) 0%,#1a4a2e 40%,#0d2e4a 100%);padding:50px 0 60px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div class="hero-bg-animation"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="text-center">
            <div class="hero-badge mb-3">
                <i class="fas fa-vote-yea"></i> Voter Portal
            </div>
            <h1 class="hero-title" style="font-size:2rem;">Voter Login</h1>
            <p class="hero-subtitle" style="max-width:500px;margin:0 auto;">Access your NEC voter account to check registration status and manage your profile</p>
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
                            <div style="background:rgba(255,255,255,.15);border-radius:12px;padding:12px;"><i class="fas fa-vote-yea" style="font-size:2rem;color:var(--nec-gold);"></i></div>
                            <div><span style="display:block;font-size:1.4rem;font-weight:900;color:var(--nec-gold);line-height:1;">NEC</span><span style="font-size:0.62rem;text-transform:uppercase;letter-spacing:2.5px;color:rgba(255,255,255,.5);">Voter Portal</span></div>
                        </div>
                        <h2 style="font-size:1.8rem;font-weight:800;margin-bottom:20px;line-height:1.3;">Your Voice Matters</h2>
                        <p style="color:rgba(255,255,255,.7);font-size:0.95rem;line-height:1.7;margin-bottom:30px;">Log in to verify your voter registration, find your polling station, view election information, and manage your profile.</p>
                    </div>
                    <div>
                        <div style="padding:18px;background:rgba(255,255,255,.08);border-radius:10px;border-left:3px solid var(--nec-gold);margin-bottom:18px;">
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-id-card me-1"></i> Check Registration</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">Verify your voter registration status and polling station details.</p>
                        </div>
                        <div style="padding:18px;background:rgba(255,255,255,.08);border-radius:10px;border-left:3px solid var(--nec-gold);">
                            <p style="color:#fff;font-weight:600;font-size:0.95rem;margin:0 0 4px;"><i class="fas fa-calendar-check me-1"></i> Election Updates</p>
                            <p style="color:rgba(255,255,255,.65);font-size:0.82rem;margin:0;">Stay informed about upcoming elections and important deadlines.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM -->
            <div class="col-lg-5 d-flex align-items-stretch">
                <div class="login-form-card w-100" style="background:#fff;padding:50px 35px;border-radius:0 12px 12px 0;border:1px solid #e9ecef;border-left:none;box-shadow:0 8px 30px rgba(0,0,0,.1);">
                    <div class="text-center mb-4">
                        <h3 style="font-size:1.6rem;font-weight:800;color:var(--nec-black);margin-bottom:6px;">Welcome Back</h3>
                        <p style="color:var(--nec-text-light);font-size:0.88rem;">Sign in to your voter account</p>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:0.88rem;">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px;font-size:0.88rem;">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav nav-pills mb-4 p-1" style="background:#f1f3f5;border-radius:8px;" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link active w-100" id="email-tab" data-bs-toggle="pill" data-bs-target="#emailPanel" type="button" role="tab" style="border-radius:6px;font-size:0.88rem;padding:8px;"><i class="fas fa-envelope me-1"></i> Email</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="voterid-tab" data-bs-toggle="pill" data-bs-target="#voteridPanel" type="button" role="tab" style="border-radius:6px;font-size:0.88rem;padding:8px;"><i class="fas fa-id-card me-1"></i> Voter ID</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Email Login -->
                        <div class="tab-pane fade show active" id="emailPanel" role="tabpanel">
                            <form id="loginEmailForm" method="POST" action="{{ route('voter.portal.login.post') }}">
                                @csrf
                                <input type="hidden" name="login_type" value="email">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-envelope" style="color:#999;font-size:0.88rem;"></i></span>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" value="{{ old('email') }}" required style="border-left:none;font-size:0.92rem;">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-lock" style="color:#999;font-size:0.88rem;"></i></span>
                                        <input type="password" name="password" id="passwordEmail" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required style="border-left:none;font-size:0.92rem;border-right:none;">
                                        <button class="btn" type="button" onclick="togglePw('passwordEmail',this)" style="background:transparent;border:1px solid #dee2e6;border-left:none;"><i class="fas fa-eye" style="color:#999;"></i></button>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn fw-bold w-100 submit-btn" style="background:var(--nec-green);color:#fff;padding:12px;border:none;border-radius:6px;font-size:1rem;">
                                    <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Sign In</span>
                                    <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Signing In...</span>
                                </button>
                            </form>
                        </div>

                        <!-- Voter ID Login -->
                        <div class="tab-pane fade" id="voteridPanel" role="tabpanel">
                            <form id="loginVoterIdForm" method="POST" action="{{ route('voter.portal.login.post') }}">
                                @csrf
                                <input type="hidden" name="login_type" value="voter_id">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">Voter ID Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-id-card" style="color:#999;font-size:0.88rem;"></i></span>
                                        <input type="text" name="voter_id" class="form-control @error('voter_id') is-invalid @enderror" placeholder="e.g. NECSST000001" value="{{ old('voter_id') }}" required style="border-left:none;font-size:0.92rem;text-transform:uppercase;" maxlength="12">
                                        @error('voter_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.88rem;color:#444;">PIN / Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="border-right:none;background:transparent;"><i class="fas fa-lock" style="color:#999;font-size:0.88rem;"></i></span>
                                        <input type="password" name="password" id="passwordVoterId" class="form-control @error('password') is-invalid @enderror" placeholder="Enter PIN or password" required style="border-left:none;font-size:0.92rem;border-right:none;">
                                        <button class="btn" type="button" onclick="togglePw('passwordVoterId',this)" style="background:transparent;border:1px solid #dee2e6;border-left:none;"><i class="fas fa-eye" style="color:#999;"></i></button>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn fw-bold w-100 submit-btn" style="background:var(--nec-green);color:#fff;padding:12px;border:none;border-radius:6px;font-size:1rem;">
                                    <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Sign In</span>
                                    <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Signing In...</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr style="margin:25px 0;border-color:#eee;">

                    <div class="text-center">
                        <p style="color:#999;font-size:0.85rem;margin-bottom:10px;">Don't have an account? <a href="{{ route('voter.portal.register') }}" style="color:var(--nec-green);font-weight:600;text-decoration:none;">Register as a Voter</a></p>
                        <p style="color:#999;font-size:0.82rem;margin-bottom:0;"><a href="{{ route('voter.portal.forgot-password') }}" style="color:var(--nec-green);text-decoration:none;">Forgot password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
function togglePw(id, btn) {
    const el = document.getElementById(id);
    const type = el.getAttribute('type') === 'password' ? 'text' : 'password';
    el.setAttribute('type', type);
    btn.querySelector('i').classList.toggle('fa-eye');
    btn.querySelector('i').classList.toggle('fa-eye-slash');
}

document.querySelectorAll('.submit-btn').forEach(btn => {
    btn.closest('form').addEventListener('submit', function(e) {
        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.btn-loader').classList.remove('d-none');
        btn.style.opacity = '0.7';
    });
});
</script>
@endsection
