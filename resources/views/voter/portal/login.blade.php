@extends('layouts.app')

@section('hero')
<section class="hero-section hero-page" style="min-height:30vh;background:linear-gradient(135deg,#0d2e4a 0%,#1a4a2e 40%,var(--nec-black) 100%);padding:30px 0 40px;display:flex;align-items:center;position:relative;overflow:hidden;">
    <div class="container position-relative text-center">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:6px 18px;border-radius:50px;color:#fff;font-size:0.82rem;font-weight:500;backdrop-filter:blur(4px);margin-bottom:12px;">
            <i class="fas fa-vote-yea" style="color:var(--nec-gold);font-size:0.75rem;"></i> Voter Portal
        </div>
        <h1 class="fw-bold" style="color:#fff;font-size:2rem;margin-bottom:6px;">Voter Account Login</h1>
        <p style="color:rgba(255,255,255,.65);font-size:0.95rem;max-width:400px;margin:0 auto;">National Elections Commission &middot; South Sudan</p>
    </div>
</section>
@endsection

@section('extra_head')
<style>
    body.login-page{background:#f0f2f5;}
    .main-content{padding:0;margin:0;background:#f0f2f5;min-height:100vh;}
    .nec-card{background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);border:1px solid #eef0f2;padding:32px 36px;}
    .form-input{border:1.5px solid #d1d5db;border-radius:8px;padding:10px 14px;font-size:0.92rem;transition:all .2s;width:100%;outline:none;background:#fff;}
    .form-input:focus{border-color:var(--nec-green);box-shadow:0 0 0 3px rgba(46,139,87,.1);}
    .form-input.is-invalid{border-color:#dc2626;}
    .btn-primary-nec{background:var(--nec-green);color:#fff;border:none;border-radius:8px;padding:11px 20px;font-size:0.95rem;font-weight:600;width:100%;transition:all .2s;cursor:pointer;}
    .btn-primary-nec:hover{background:#1f6b3d;transform:translateY(-1px);box-shadow:0 4px 12px rgba(46,139,87,.3);}
    .btn-primary-nec:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}
    .pill-tab{background:#f1f3f5;border-radius:8px;padding:3px;display:flex;}
    .pill-tab .nav-link{flex:1;text-align:center;border-radius:6px;font-size:0.87rem;padding:8px;color:#6b7280;border:none;background:transparent;font-weight:500;}
    .pill-tab .nav-link.active{background:#fff;color:var(--nec-green);box-shadow:0 1px 3px rgba(0,0,0,.08);font-weight:600;}
    .pill-tab .nav-link i{font-size:0.8rem;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}
    .fade-in{animation:fadeIn .35s ease;}
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
                            <i class="fas fa-vote-yea" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <h3 style="font-size:1.15rem;font-weight:700;color:#1f2937;margin:0 0 2px;">Voter Portal</h3>
                        <p style="color:#6b7280;font-size:0.8rem;margin:0;">Sign in to your voter account</p>
                    </div>

                    <hr style="opacity:.15;margin:18px 0;">

                    @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#166534;">
                        <i class="fas fa-check-circle me-2" style="flex-shrink:0;"></i> {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center" style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:0.85rem;color:#991b1b;">
                        <i class="fas fa-exclamation-circle me-2" style="flex-shrink:0;"></i> {{ session('error') }}
                    </div>
                    @endif

                    <!-- Tab Navigation -->
                    <ul class="nav pill-tab mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="email-tab" data-bs-toggle="pill" data-bs-target="#emailPanel" type="button" role="tab"><i class="fas fa-envelope me-1"></i> Email</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="voterid-tab" data-bs-toggle="pill" data-bs-target="#voteridPanel" type="button" role="tab"><i class="fas fa-id-card me-1"></i> Voter ID</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Email Login -->
                        <div class="tab-pane fade show active" id="emailPanel" role="tabpanel">
                            <form method="POST" action="{{ route('voter.portal.login.submit') }}">
                                @csrf
                                <input type="hidden" name="login_type" value="email">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">Email Address</label>
                                    <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                                    @error('email')<div style="color:#dc2626;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="passwordEmail" class="form-input @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                                        <button type="button" onclick="togglePw('passwordEmail',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;padding:4px;"><i class="fas fa-eye"></i></button>
                                    </div>
                                    @error('password')<div style="color:#dc2626;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn-primary-nec submit-btn">
                                    <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Sign In</span>
                                    <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Signing In...</span>
                                </button>
                            </form>
                        </div>

                        <!-- Voter ID Login -->
                        <div class="tab-pane fade" id="voteridPanel" role="tabpanel">
                            <form method="POST" action="{{ route('voter.portal.login.submit') }}">
                                @csrf
                                <input type="hidden" name="login_type" value="voter_id">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">Voter ID Number</label>
                                    <input type="text" name="voter_id" class="form-input @error('voter_id') is-invalid @enderror" placeholder="e.g. NECSST000001" value="{{ old('voter_id') }}" required style="text-transform:uppercase;" maxlength="12">
                                    @error('voter_id')<div style="color:#dc2626;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:0.85rem;color:#374151;">PIN / Password</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="passwordVoterId" class="form-input @error('password') is-invalid @enderror" placeholder="Enter PIN or password" required>
                                        <button type="button" onclick="togglePw('passwordVoterId',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;padding:4px;"><i class="fas fa-eye"></i></button>
                                    </div>
                                    @error('password')<div style="color:#dc2626;font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn-primary-nec submit-btn">
                                    <span class="btn-text"><i class="fas fa-sign-in-alt me-1"></i> Sign In</span>
                                    <span class="btn-loader d-none"><i class="fas fa-spinner fa-spin me-1"></i> Signing In...</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr style="opacity:.12;margin:18px 0 14px;">
                    <div class="text-center">
                        <p style="color:#6b7280;font-size:0.85rem;margin-bottom:10px;">Don't have an account? <a href="{{ route('voter.portal.register') }}" style="color:var(--nec-green);font-weight:600;text-decoration:none;">Register as a Voter</a></p>
                        <p style="color:#9ca3af;font-size:0.82rem;margin:0;"><a href="{{ route('voter.portal.forgot-password') }}" style="color:var(--nec-green);text-decoration:none;">Forgot password?</a></p>
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
    btn.closest('form').addEventListener('submit', function() {
        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.btn-loader').classList.remove('d-none');
        btn.style.opacity = '0.7';
    });
});
</script>
@endsection
