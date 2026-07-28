@extends('layouts.app', ['title' => 'My Profile - NEC South Sudan', 'active_page' => 'voter'])

@section('extra_head')
<style>
.profile-header-card {
    background: linear-gradient(135deg, #065f46, #0d9488); border-radius: 20px 20px 0 0;
    padding: 32px 36px 24px; color: #fff; position: relative; overflow: hidden;
}
.profile-header-card::before {
    content: ''; position: absolute; top: -60px; right: -60px; width: 250px; height: 250px;
    border-radius: 50%; background: rgba(255,255,255,0.06);
}
.profile-header-card::after {
    content: ''; position: absolute; bottom: -80px; left: -40px; width: 300px; height: 300px;
    border-radius: 50%; background: rgba(255,255,255,0.04);
}
.profile-body-card {
    background: #fff; border: 1px solid #eef0f2; border-top: 0; border-radius: 0 0 20px 20px;
    padding: 28px 32px;
}
.section-title {
    font-size: 12px; font-weight: 700; color: var(--nec-green); text-transform: uppercase;
    letter-spacing: 1.5px; padding-bottom: 8px; border-bottom: 2px solid rgba(46,139,87,0.12);
    margin-bottom: 20px;
}
.field-row {
    display: flex; align-items: center; gap: 12px; padding: 10px 14px;
    background: #fafbfc; border-radius: 10px; border: 1px solid #f0f2f5; margin-bottom: 10px;
}
.field-row .field-icon {
    width: 36px; height: 36px; border-radius: 8px; display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;
}
.field-row .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #8c8f94; letter-spacing: 0.3px; }
.field-row .field-value { font-size: 14px; font-weight: 600; color: #1d2327; }
.form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
.form-control, .form-select {
    border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; font-size: 14px; transition: all 0.2s;
}
.form-control:focus, .form-select:focus { border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1); }
.form-control:disabled, .form-control[readonly] { background: #f8fafc; color: #475569; }
.btn-nec {
    background: var(--nec-green); border-color: var(--nec-green); color: #fff;
    font-weight: 700; border-radius: 10px; padding: 10px 20px; font-size: 14px; transition: all 0.2s;
}
.btn-nec:hover { background: var(--nec-green-dark); border-color: var(--nec-green-dark); color: #fff; }
.portal-nav .nav-link {
    font-size: 13px; font-weight: 600; color: #64748b; padding: 8px 14px;
    border-radius: 8px; transition: all 0.2s;
}
.portal-nav .nav-link:hover { color: var(--nec-green); background: rgba(46,139,87,0.06); }
.portal-nav .nav-link.active { color: var(--nec-green); background: rgba(46,139,87,0.1); }
@media print {
    body * { visibility: hidden; }
    #profileContent, #profileContent * { visibility: visible; }
    #profileContent { position: fixed; top: 0; left: 0; width: 100%; }
    .no-print { display: none !important; }
}
</style>
@endsection

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);padding:24px 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('voter.portal.dashboard') }}" style="color:rgba(255,255,255,0.6);text-decoration:none;">Dashboard</a></li>
                        <li class="breadcrumb-item active" style="color:#fff;" aria-current="page">My Profile</li>
                    </ol>
                </nav>
                <h4 class="text-white fw-bold mb-0">My Profile</h4>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4">
    <div class="container" id="profileContent">
        @php
            $voter = Auth::guard('voter')->user();
            $initials = '';
            if ($voter && $voter->full_name) {
                $initials = implode('', array_map(fn($n) => mb_substr($n, 0, 1), explode(' ', $voter->full_name)));
            }
            $age = $voter && $voter->dob ? (new \DateTime())->diff(new \DateTime($voter->dob))->y : '';
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-10">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3 no-print" style="border-radius:10px;font-size:13px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Profile Header --}}
                <div class="profile-header-card">
                    <div style="position:relative;z-index:1;">
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div style="width:72px;height:72px;border-radius:16px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;flex-shrink:0;backdrop-filter:blur(4px);">
                                {{ $initials }}
                            </div>
                            <div style="flex:1;min-width:200px;">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                    <h3 style="font-weight:800;margin:0;font-size:22px;">{{ $voter->full_name ?? 'N/A' }}</h3>
                                    <span class="badge" style="background:#16a34a;color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;">
                                        <i class="fas fa-check-circle me-1"></i> Active
                                    </span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px;">
                                    <span style="font-size:13px;font-family:monospace;background:rgba(0,0,0,0.15);padding:3px 12px;border-radius:6px;letter-spacing:0.5px;">
                                        <i class="fas fa-fingerprint me-1" style="font-size:11px;"></i>{{ $voter->voter_id ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background:rgba(0,0,0,0.12);padding:10px 0;margin-top:16px;border-radius:8px;padding:10px 20px;display:flex;gap:16px;flex-wrap:wrap;position:relative;z-index:1;">
                        <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-calendar me-1" style="color:#facc15;"></i> DOB: {{ $voter->dob ?? 'N/A' }}</span>
                        <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-phone me-1" style="color:#6ee7b7;"></i> {{ $voter->phone ?? 'N/A' }}</span>
                        <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-envelope me-1" style="color:#93c5fd;"></i> {{ $voter->email ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="profile-body-card mb-4">

                    <div class="row g-4">
                        {{-- Left: Personal Info --}}
                        <div class="col-lg-6">
                            <div class="section-title"><i class="fas fa-user me-2"></i> Personal Information</div>
                            <form method="POST" action="{{ route('voter.portal.profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="field-row">
                                    <div class="field-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-user"></i></div>
                                    <div style="flex:1;">
                                        <div class="field-label">Full Name</div>
                                        <input type="text" class="form-control form-control-sm" value="{{ $voter->full_name ?? '' }}" readonly style="background:transparent;border:none;padding:0;font-size:14px;font-weight:600;color:#1d2327;">
                                    </div>
                                </div>

                                <div class="field-row">
                                    <div class="field-icon" style="background:#fefce8;color:#ca8a04;"><i class="fas fa-calendar"></i></div>
                                    <div style="flex:1;">
                                        <div class="field-label">Date of Birth</div>
                                        <input type="text" class="form-control form-control-sm" value="{{ $voter->dob ?? '' }}" readonly style="background:transparent;border:none;padding:0;font-size:14px;font-weight:600;color:#1d2327;">
                                    </div>
                                </div>

                                <div class="field-row">
                                    <div class="field-icon" style="background:#fdf2f8;color:#db2777;"><i class="fas fa-venus-mars"></i></div>
                                    <div style="flex:1;">
                                        <div class="field-label">Gender</div>
                                        <input type="text" class="form-control form-control-sm" value="{{ ($voter->gender ?? '') === 'M' ? 'Male' : (($voter->gender ?? '') === 'F' ? 'Female' : ($voter->gender ?? '')) }}" readonly style="background:transparent;border:none;padding:0;font-size:14px;font-weight:600;color:#1d2327;">
                                    </div>
                                </div>

                                <div class="field-row">
                                    <div class="field-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="fas fa-id-card"></i></div>
                                    <div style="flex:1;">
                                        <div class="field-label">National ID</div>
                                        <input type="text" class="form-control form-control-sm" value="{{ isset($voter->national_id) && $voter->national_id ? substr($voter->national_id, 0, 3) . '****' . substr($voter->national_id, -2) : '—' }}" readonly style="background:transparent;border:none;padding:0;font-size:14px;font-weight:600;color:#1d2327;">
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" value="{{ $voter->phone ?? '' }}" placeholder="Your phone number">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ $voter->email ?? '' }}" placeholder="Your email">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <button type="submit" class="btn btn-nec">
                                    <i class="fas fa-save me-1"></i> Update Profile
                                </button>
                            </form>
                        </div>

                        {{-- Right: Registration Info --}}
                        <div class="col-lg-6">
                            <div class="section-title"><i class="fas fa-map-marked-alt me-2"></i> Registration Details</div>

                            <div class="field-row">
                                <div class="field-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-flag"></i></div>
                                <div style="flex:1;"><div class="field-label">State</div><div class="field-value">{{ $voter->state ?? 'N/A' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#fefce8;color:#ca8a04;"><i class="fas fa-building"></i></div>
                                <div style="flex:1;"><div class="field-label">County</div><div class="field-value">{{ $voter->county ?? 'N/A' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#f0f7ff;color:#2563eb;"><i class="fas fa-landmark"></i></div>
                                <div style="flex:1;"><div class="field-label">Constituency</div><div class="field-value">{{ $voter->constituency ?? 'N/A' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#fdf2f8;color:#db2777;"><i class="fas fa-home"></i></div>
                                <div style="flex:1;"><div class="field-label">Payam</div><div class="field-value">{{ $voter->payam ?? 'N/A' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#ecfeff;color:#0891b2;"><i class="fas fa-school"></i></div>
                                <div style="flex:1;"><div class="field-label">Polling Station</div><div class="field-value">{{ $voter->polling_station ?? 'Not Assigned' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="fas fa-clipboard-list"></i></div>
                                <div style="flex:1;"><div class="field-label">Registration Center</div><div class="field-value">{{ $voter->registration_center ?? 'N/A' }}</div></div>
                            </div>
                            <div class="field-row">
                                <div class="field-icon" style="background:#fff7ed;color:#ea580c;"><i class="fas fa-clock"></i></div>
                                <div style="flex:1;"><div class="field-label">Registration Date</div><div class="field-value">{{ $voter->created_at ? date('d M Y', strtotime($voter->created_at)) : 'N/A' }}</div></div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('voter.portal.id-card') }}" class="btn btn-nec" style="font-size:13px;">
                                    <i class="fas fa-id-card me-1"></i> Download ID Card
                                </a>
                                <a href="{{ route('voter.portal.transfer') }}" class="btn btn-outline-success" style="font-size:13px;border-radius:10px;font-weight:600;">
                                    <i class="fas fa-exchange-alt me-1"></i> Request Transfer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account Settings --}}
                <div class="card border-0 shadow-sm no-print" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="section-title"><i class="fas fa-cog me-2"></i> Account Settings</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="#" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#f8fafc;border:1px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--nec-green)'" onmouseout="this.style.borderColor='#e2e8f0'">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#f0f7ff;color:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-key"></i></div>
                                    <div><div style="font-size:14px;font-weight:700;color:#1d2327;">Change Password</div><div style="font-size:12px;color:#8c8f94;">Update your password</div></div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#f8fafc;border:1px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--nec-green)'" onmouseout="this.style.borderColor='#e2e8f0'">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#fefce8;color:#ca8a04;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-lock"></i></div>
                                    <div><div style="font-size:14px;font-weight:700;color:#1d2327;">Change PIN</div><div style="font-size:12px;color:#8c8f94;">Update your quick-login PIN</div></div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background:#f8fafc;border:1px solid #e2e8f0;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--nec-green)'" onmouseout="this.style.borderColor='#e2e8f0'">
                                    <div style="width:40px;height:40px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-shield-alt"></i></div>
                                    <div><div style="font-size:14px;font-weight:700;color:#1d2327;">Enable 2FA</div><div style="font-size:12px;color:#8c8f94;">Two-factor authentication</div></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
