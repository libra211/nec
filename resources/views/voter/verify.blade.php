@extends('layouts.app', ['title' => 'Verify Registration - NEC South Sudan', 'active_page' => 'voters'])

@section('hero')
<style>
.vfy-hero {
    background: linear-gradient(135deg, #0a2a1a 0%, #1a4a2e 40%, #2d6b3f 100%);
    position: relative; overflow: hidden; padding: 64px 0 48px;
}
.vfy-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.vfy-hero .hero-content { position: relative; z-index: 1; }
.vfy-hero h1 { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
.vfy-hero p { font-size: 1.05rem; color: rgba(255,255,255,0.7); max-width: 520px; margin-bottom: 0; }

.search-card {
    background: #fff; border-radius: 16px; box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    padding: 28px 32px; margin-bottom: 28px;
}
.search-card .form-label { font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
.search-card .form-control, .search-card .form-select {
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    padding: 11px 14px; font-size: 14px; transition: all 0.2s;
}
.search-card .form-control:focus, .search-card .form-select:focus {
    border-color: var(--nec-green); box-shadow: 0 0 0 3px rgba(46,139,87,0.1);
}
.method-tab {
    display: inline-block; padding: 7px 16px; border-radius: 8px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: all 0.2s; border: 1.5px solid #e2e8f0;
    background: #fff; color: #8c8f94; text-decoration: none;
}
.method-tab:hover { border-color: var(--nec-green); color: var(--nec-green); }
.method-tab.active { background: var(--nec-green); border-color: var(--nec-green); color: #fff; }

.voter-field { margin-bottom: 0; }
.voter-field .vf-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8f94; margin-bottom: 1px; }
.voter-field .vf-value { font-size: 15px; font-weight: 600; color: #1d2327; }
</style>

<section class="vfy-hero">
    <div class="container hero-content">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('voter.index') }}" class="text-white-50">Voters</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Verify Registration</li>
            </ol>
        </nav>
        <h1>Verify Registration</h1>
        <p>Check your voter registration status using any of the search methods below.</p>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="search-card">
                    <div class="d-flex gap-2 flex-wrap mb-4">
                        <span class="method-tab active" data-method="voter_id">Voter ID</span>
                        <span class="method-tab" data-method="national_id">National ID</span>
                        <span class="method-tab" data-method="reg_number">Reg Number</span>
                        <span class="method-tab" data-method="mobile">Phone</span>
                        <span class="method-tab" data-method="name_dob">Name + DOB</span>
                    </div>

                    <form method="POST" action="{{ route('voter.verify') }}" id="verifyForm">
                        @csrf
                        <!-- Voter ID -->
                        <div class="method-field" id="field_voter_id">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label">Voter ID Number</label>
                                    <input type="text" name="voter_id" class="form-control form-control-lg" placeholder="e.g. NEC26M369246" value="{{ old('voter_id') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-lg w-100" style="background:var(--nec-green);color:#fff;font-weight:600;border-radius:10px;padding:12px;">
                                        <i class="fas fa-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- National ID -->
                        <div class="method-field" id="field_national_id" style="display:none;">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label">National ID Number</label>
                                    <input type="text" name="national_id" class="form-control form-control-lg" placeholder="e.g. SS123456789" value="{{ old('national_id') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-lg w-100" style="background:var(--nec-green);color:#fff;font-weight:600;border-radius:10px;padding:12px;">
                                        <i class="fas fa-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Reg Number -->
                        <div class="method-field" id="field_reg_number" style="display:none;">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label">Registration Number</label>
                                    <input type="text" name="reg_number" class="form-control form-control-lg" placeholder="Registration number on your slip" value="{{ old('reg_number') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-lg w-100" style="background:var(--nec-green);color:#fff;font-weight:600;border-radius:10px;padding:12px;">
                                        <i class="fas fa-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="method-field" id="field_mobile" style="display:none;">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="mobile" class="form-control form-control-lg" placeholder="e.g. +211912000123" value="{{ old('mobile') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-lg w-100" style="background:var(--nec-green);color:#fff;font-weight:600;border-radius:10px;padding:12px;">
                                        <i class="fas fa-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Name + DOB -->
                        <div class="method-field" id="field_name_dob" style="display:none;">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control form-control-lg" placeholder="Full name as registered" value="{{ old('full_name') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control form-control-lg" value="{{ old('dob') }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-lg w-100" style="background:var(--nec-green);color:#fff;font-weight:600;border-radius:10px;padding:12px;">
                                        <i class="fas fa-search me-1"></i> Verify
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden field to track active method -->
                        <input type="hidden" name="method" id="activeMethod" value="voter_id">
                    </form>
                </div>

                @if(isset($voterError) && $voterError)
                    <div class="alert alert-danger d-flex align-items-center gap-3" style="border-radius:12px;">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <div>{!! $voterError !!}</div>
                    </div>
                @elseif(isset($voterResult) && $voterResult)
                    @php
                        $age = (new \DateTime())->diff(new \DateTime($voterResult->dob))->y;
                        $initials = implode('', array_map(fn($n) => mb_substr($n, 0, 1), explode(' ', $voterResult->full_name)));
                    @endphp
                    <div style="max-width:820px;margin:0 auto;">

                    <!-- VOTER ID CREDENTIAL HEADER -->
                    <div style="background:linear-gradient(135deg,#065f46,#0d9488);border-radius:20px 20px 0 0;padding:0;color:#fff;position:relative;overflow:hidden;">
                        <div style="position:absolute;top:-60px;right:-60px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
                        <div style="position:absolute;bottom:-80px;left:-40px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,0.03);"></div>
                        <div style="position:absolute;top:40%;left:60%;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.02);"></div>
                        <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#f59e0b,#10b981,#3b82f6);"></div>

                        <div class="d-flex align-items-center gap-4 flex-wrap" style="position:relative;z-index:1;padding:36px 36px 24px;">
                            <div style="width:88px;height:88px;border-radius:16px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:800;color:#fff;flex-shrink:0;backdrop-filter:blur(4px);">
                                {{ $initials }}
                            </div>
                            <div style="flex:1;min-width:200px;">
                                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                    <h3 style="font-weight:800;margin-bottom:0;font-size:24px;letter-spacing:-0.3px;">{{ $voterResult->full_name }}</h3>
                                    <span class="badge" style="background:#f59e0b;color:#1e293b;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.3px;">
                                        <i class="fas fa-crown me-1"></i>Voter
                                    </span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px;">
                                    <span style="font-size:14px;font-family:monospace;background:rgba(0,0,0,0.15);padding:3px 12px;border-radius:6px;letter-spacing:0.5px;">
                                        <i class="fas fa-fingerprint me-1" style="font-size:11px;"></i>{{ $voterResult->voter_id }}
                                    </span>
                                    <span class="badge" style="background:#16a34a;color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;">
                                        <i class="fas fa-shield-alt me-1"></i>Verified
                                    </span>
                                    <span class="badge" style="background:rgba(255,255,255,0.15);color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;">
                                        <i class="fas fa-id-card me-1"></i>Registered
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div style="background:rgba(0,0,0,0.12);padding:12px 36px;display:flex;gap:24px;flex-wrap:wrap;position:relative;z-index:1;">
                            <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-calendar me-1" style="color:#facc15;"></i> DOB: {{ $voterResult->dob }}</span>
                            <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-phone me-1" style="color:#6ee7b7;"></i> {{ $voterResult->phone }}</span>
                            <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-envelope me-1" style="color:#93c5fd;"></i> {{ $voterResult->email }}</span>
                            <span style="font-size:12px;color:rgba(255,255,255,0.7);"><i class="fas fa-clock me-1" style="color:#c4b5fd;"></i> Since {{ date('Y', strtotime($voterResult->registered_at)) }}</span>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div style="background:#fff;border:1px solid #eef0f2;border-top:0;border-radius:0 0 20px 20px;padding:0;">

                        <!-- STAT ROW -->
                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;">
                            <div style="padding:20px 16px;text-align:center;border-right:1px solid #f0f2f5;">
                                <i class="fas fa-calendar-alt" style="font-size:20px;color:#16a34a;"></i>
                                <div style="font-size:26px;font-weight:800;color:#1d2327;margin-top:2px;line-height:1.2;">{{ $age }}</div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8c8f94;">Years Old</div>
                            </div>
                            <div style="padding:20px 16px;text-align:center;border-right:1px solid #f0f2f5;">
                                <i class="fas fa-venus-mars" style="font-size:20px;color:#be185d;"></i>
                                <div style="font-size:20px;font-weight:800;color:#1d2327;margin-top:2px;line-height:1.2;">{{ $voterResult->gender === 'M' ? 'Male' : ($voterResult->gender === 'F' ? 'Female' : $voterResult->gender) }}</div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8c8f94;">Gender</div>
                            </div>
                            <div style="padding:20px 16px;text-align:center;border-right:1px solid #f0f2f5;">
                                <i class="fas fa-flag" style="font-size:20px;color:#d97706;"></i>
                                <div style="font-size:20px;font-weight:800;color:#1d2327;margin-top:2px;line-height:1.2;">{{ $voterResult->state }}</div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8c8f94;">State</div>
                            </div>
                            <div style="padding:20px 16px;text-align:center;">
                                <i class="fas fa-id-card" style="font-size:20px;color:#3b82f6;"></i>
                                <div style="font-size:16px;font-weight:800;color:#1d2327;margin-top:2px;line-height:1.2;">{{ $voterResult->national_id ?: '—' }}</div>
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8c8f94;">National ID</div>
                            </div>
                        </div>
                        <div style="border-top:1px solid #f0f2f5;"></div>

                        <!-- locator chips -->
                        <div style="padding:18px 28px;display:flex;flex-wrap:wrap;gap:6px;border-bottom:1px solid #f0f2f5;">
                            <span style="background:#f0fdf4;color:#166534;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;"><i class="fas fa-map-marker-alt me-1" style="color:#16a34a;"></i> {{ $voterResult->state }}</span>
                            <span style="background:#fefce8;color:#854d0e;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;"><i class="fas fa-building me-1" style="color:#ca8a04;"></i> {{ $voterResult->county }}</span>
                            <span style="background:#f0f7ff;color:#1e40af;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;"><i class="fas fa-landmark me-1" style="color:#2563eb;"></i> {{ $voterResult->constituency }}</span>
                            <span style="background:#fdf2f8;color:#9d174d;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;"><i class="fas fa-home me-1" style="color:#db2777;"></i> {{ $voterResult->payam }}</span>
                        </div>

                        <!-- main content grid -->
                        <div style="padding:24px 28px 20px;">
                            <div class="row g-4">
                                <!-- Personal Info Column -->
                                <div class="col-md-6">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                        <div style="width:4px;height:18px;background:#16a34a;border-radius:2px;"></div>
                                        <h6 style="font-size:13px;font-weight:700;color:#1d2327;margin:0;"><i class="fas fa-user me-1" style="color:#16a34a;"></i> Personal Information</h6>
                                    </div>
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fafbfc;border-radius:10px;border:1px solid #f0f2f5;">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;color:#16a34a;flex-shrink:0;"><i class="fas fa-calendar" style="font-size:13px;"></i></div>
                                            <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8c8f94;letter-spacing:0.3px;">Date of Birth</div><div style="font-size:14px;font-weight:600;color:#1d2327;">{{ $voterResult->dob }} <span style="font-weight:400;color:#8c8f94;">(age {{ $age }})</span></div></div>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fafbfc;border-radius:10px;border:1px solid #f0f2f5;">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#fdf2f8;display:flex;align-items:center;justify-content:center;color:#be185d;flex-shrink:0;"><i class="fas fa-{{ $voterResult->gender === 'M' ? 'mars' : ($voterResult->gender === 'F' ? 'venus' : 'genderless') }}" style="font-size:13px;"></i></div>
                                            <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8c8f94;letter-spacing:0.3px;">Gender</div><div style="font-size:14px;font-weight:600;color:#1d2327;">{{ $voterResult->gender === 'M' ? 'Male' : ($voterResult->gender === 'F' ? 'Female' : $voterResult->gender) }}</div></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Column -->
                                <div class="col-md-6">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                        <div style="width:4px;height:18px;background:#3b82f6;border-radius:2px;"></div>
                                        <h6 style="font-size:13px;font-weight:700;color:#1d2327;margin:0;"><i class="fas fa-address-book me-1" style="color:#3b82f6;"></i> Contact Details</h6>
                                    </div>
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fafbfc;border-radius:10px;border:1px solid #f0f2f5;">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#f0f7ff;display:flex;align-items:center;justify-content:center;color:#2563eb;flex-shrink:0;"><i class="fas fa-phone" style="font-size:13px;"></i></div>
                                            <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8c8f94;letter-spacing:0.3px;">Phone</div><div style="font-size:14px;font-weight:600;color:#1d2327;">{{ $voterResult->phone }}</div></div>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fafbfc;border-radius:10px;border:1px solid #f0f2f5;">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#fefce8;display:flex;align-items:center;justify-content:center;color:#ca8a04;flex-shrink:0;"><i class="fas fa-envelope" style="font-size:13px;"></i></div>
                                            <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8c8f94;letter-spacing:0.3px;">Email</div><div style="font-size:14px;font-weight:600;color:#1d2327;word-break:break-all;">{{ $voterResult->email }}</div></div>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#fafbfc;border-radius:10px;border:1px solid #f0f2f5;">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;color:#7c3aed;flex-shrink:0;"><i class="fas fa-id-card" style="font-size:13px;"></i></div>
                                            <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;color:#8c8f94;letter-spacing:0.3px;">National ID</div><div style="font-size:14px;font-weight:600;color:#1d2327;">{{ $voterResult->national_id }}</div></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Column (full width) -->
                                <div class="col-12">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                        <div style="width:4px;height:18px;background:#d97706;border-radius:2px;"></div>
                                        <h6 style="font-size:13px;font-weight:700;color:#1d2327;margin:0;"><i class="fas fa-map-marked-alt me-1" style="color:#d97706;"></i> Location &amp; Polling</h6>
                                    </div>
                                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                        @php
                                        $locItems = [
                                            ['State', $voterResult->state, 'map-marker-alt', '#16a34a', '#f0fdf4'],
                                            ['County', $voterResult->county, 'building', '#ca8a04', '#fefce8'],
                                            ['Constituency', $voterResult->constituency, 'landmark', '#2563eb', '#f0f7ff'],
                                            ['Payam', $voterResult->payam, 'home', '#db2777', '#fdf2f8'],
                                            ['Reg. Center', $voterResult->registration_center ?? '', 'clipboard-list', '#7c3aed', '#f5f3ff'],
                                            ['Registered', date('d M Y', strtotime($voterResult->registered_at)) . ' (' . \Carbon\Carbon::parse($voterResult->registered_at)->diffForHumans() . ')', 'clock', '#0891b2', '#ecfeff'],
                                        ];
                                        @endphp
                                        @foreach($locItems as $li)
                                        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:{{ $li[4] }};border-radius:10px;border:1px solid {{ $li[4] }};">
                                            <div style="width:30px;height:30px;border-radius:8px;background:#fff;display:flex;align-items:center;justify-content:center;color:{{ $li[3] }};flex-shrink:0;"><i class="fas fa-{{ $li[2] }}" style="font-size:12px;"></i></div>
                                            <div><div style="font-size:9px;font-weight:700;text-transform:uppercase;color:{{ $li[3] }};letter-spacing:0.3px;">{{ $li[0] }}</div><div style="font-size:13px;font-weight:600;color:#1d2327;">{{ $li[1] }}</div></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- POLLING STATION SPOTLIGHT -->
                            @if(isset($voterResult->polling_station) && $voterResult->polling_station)
                            <div style="margin-top:20px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:14px;padding:20px 24px;border:2px solid #6ee7b7;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:52px;height:52px;border-radius:14px;background:#10b981;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;flex-shrink:0;box-shadow:0 4px 12px rgba(16,185,129,0.25);"><i class="fas fa-school"></i></div>
                                    <div>
                                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#047857;">Your Polling Station</div>
                                        <div style="font-size:20px;font-weight:800;color:#065f46;line-height:1.3;">{{ $voterResult->polling_station }}</div>
                                        @if(isset($voterResult->registration_center) && $voterResult->registration_center)
                                        <div style="font-size:12px;color:#047857;margin-top:2px;"><i class="fas fa-clipboard-check me-1"></i>Registered at: {{ $voterResult->registration_center }}</div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ url('voter/polling-finder?voter_id=' . urlencode($voterResult->voter_id)) }}" class="btn" style="background:#065f46;color:#fff;border-radius:10px;font-weight:600;padding:10px 20px;font-size:13px;white-space:nowrap;">
                                    <i class="fas fa-map-marked-alt me-1"></i> Find on Map
                                </a>
                            </div>
                            @endif

                            <!-- FOOTER -->
                            <div style="margin-top:18px;padding-top:14px;border-top:1px solid #f0f2f5;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;">
                                <span style="font-size:11px;color:#94a3b8;"><i class="fas fa-fingerprint me-1"></i> Verified Voter · ID: {{ $voterResult->voter_id }}</span>
                                <span style="font-size:11px;color:#94a3b8;"><i class="fas fa-sync-alt me-1"></i> Last updated: {{ date('d M Y, H:i', strtotime($voterResult->updated_at)) }}</span>
                            </div>
                        </div>
                    </div>
                    </div>

                @elseif(isset($searched) && $searched)
                    <div class="text-center py-5" style="color:#8c8f94;">
                        <i class="fas fa-search" style="font-size:40px;color:#dde0e4;margin-bottom:12px;"></i>
                        <h6 style="color:#50575e;">No Record Found</h6>
                        <p>No voter registration found. Please check your details and try again.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<script>
(function() {
    var tabs = document.querySelectorAll('.method-tab');
    var fields = {
        voter_id: document.getElementById('field_voter_id'),
        national_id: document.getElementById('field_national_id'),
        reg_number: document.getElementById('field_reg_number'),
        mobile: document.getElementById('field_mobile'),
        name_dob: document.getElementById('field_name_dob')
    };
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var method = this.getAttribute('data-method');
            tabs.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            Object.keys(fields).forEach(function(k) {
                fields[k].style.display = k === method ? 'block' : 'none';
            });
            document.getElementById('activeMethod').value = method;
        });
    });
    var activeMethod = document.getElementById('activeMethod').value;
    tabs.forEach(function(t) {
        if (t.getAttribute('data-method') === activeMethod) t.click();
    });
})();
</script>
@endsection
