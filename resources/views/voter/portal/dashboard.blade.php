@extends('layouts.app', ['title' => 'Voter Dashboard - NEC South Sudan', 'active_page' => 'voter'])

@section('extra_head')
<style>
.portal-welcome {
    background: linear-gradient(135deg, var(--nec-green) 0%, #0d3b1e 100%);
    border-radius: 16px; padding: 28px 32px; color: #fff; position: relative; overflow: hidden;
}
.portal-welcome::before {
    content: ''; position: absolute; top: -60px; right: -60px; width: 250px; height: 250px;
    border-radius: 50%; background: rgba(255,255,255,0.06);
}
.portal-welcome::after {
    content: ''; position: absolute; bottom: -80px; left: -40px; width: 300px; height: 300px;
    border-radius: 50%; background: rgba(255,255,255,0.04);
}
.portal-welcome .content { position: relative; z-index: 1; }
.portal-welcome h2 { font-weight: 800; font-size: 22px; margin-bottom: 4px; }
.portal-welcome p { opacity: 0.75; font-size: 14px; margin-bottom: 0; }
.portal-welcome .voter-badge {
    background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);
    padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 6px; margin-top: 8px;
    backdrop-filter: blur(4px);
}
.status-card {
    background: #fff; border-radius: 14px; border: 1px solid #f0f2f5;
    padding: 20px; transition: all 0.2s; height: 100%;
}
.status-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); transform: translateY(-2px); }
.status-card .icon-box {
    width: 44px; height: 44px; border-radius: 12px; display: flex;
    align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
}
.status-card .label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #8c8f94; }
.status-card .value { font-size: 15px; font-weight: 700; color: #1d2327; }
.action-card {
    background: #fff; border: 1.5px solid #f0f2f5; border-radius: 14px;
    padding: 20px; text-align: center; text-decoration: none; color: inherit;
    transition: all 0.2s; height: 100%;
}
.action-card:hover {
    border-color: var(--nec-green); box-shadow: 0 4px 16px rgba(46,139,87,0.1);
    transform: translateY(-2px); color: inherit; text-decoration: none;
}
.action-card .icon-circle {
    width: 52px; height: 52px; border-radius: 14px; display: flex;
    align-items: center; justify-content: center; font-size: 20px; margin: 0 auto 10px;
}
.action-card h6 { font-weight: 700; font-size: 13px; margin-bottom: 2px; color: #1d2327; }
.action-card p { font-size: 11px; color: #8c8f94; margin-bottom: 0; }
.activity-item {
    display: flex; align-items: flex-start; gap: 12px; padding: 12px 0;
    border-bottom: 1px solid #f0f2f5;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px;
}
.section-title {
    font-size: 12px; font-weight: 700; color: var(--nec-green); text-transform: uppercase;
    letter-spacing: 1.5px; margin-bottom: 16px;
}
.portal-nav .nav-link {
    font-size: 13px; font-weight: 600; color: #64748b; padding: 8px 14px;
    border-radius: 8px; transition: all 0.2s;
}
.portal-nav .nav-link:hover { color: var(--nec-green); background: rgba(46,139,87,0.06); }
.portal-nav .nav-link.active { color: var(--nec-green); background: rgba(46,139,87,0.1); }
</style>
@endsection

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);padding:24px 0;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="text-white-50" style="text-decoration:none;font-size:13px;">
                    <i class="fas fa-home"></i>
                </a>
                <span style="color:rgba(255,255,255,0.3);">/</span>
                <span style="color:#fff;font-weight:600;font-size:14px;">Voter Portal</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span style="color:rgba(255,255,255,0.7);font-size:13px;">
                    <i class="fas fa-user me-1"></i> {{ ($voter->full_name ?? 'Voter') }}
                </span>
                <form method="POST" action="{{ route('voter.portal.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:5px 12px;font-size:12px;font-weight:600;">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-4">
    <div class="container">

        {{-- Welcome Banner --}}
        <div class="portal-welcome mb-4" data-aos="fade-up">
            <div class="content">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h2>Welcome, {{ ($voter->full_name ?? 'Voter') }}!</h2>
                        <p>Manage your voter registration, download your ID card, and stay informed.</p>
                        <div class="voter-badge">
                            <i class="fas fa-fingerprint"></i>
                            Voter ID: {{ ($voter->voter_id ?? 'N/A') }}
                        </div>
                    </div>
                    <div class="text-center">
                        @php
                            $voter = $voter ?? null;
                            $initials = '';
                            if ($voter && $voter->full_name) {
                                $initials = implode('', array_map(fn($n) => mb_substr($n, 0, 1), explode(' ', $voter->full_name)));
                            }
                        @endphp
                        <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;backdrop-filter:blur(4px);">
                            {{ $initials }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Cards --}}
        <div class="section-title"><i class="fas fa-chart-bar me-2"></i> Your Status</div>
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="status-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background:#dcfce7;color:#16a34a;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="label">Registration</div>
                            <div class="value">
                                <span class="badge bg-success" style="font-size:11px;font-weight:700;">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="status-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background:#f0f7ff;color:#2563eb;">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div>
                            <div class="label">Constituency</div>
                            <div class="value">{{ $voter->constituency ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="status-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background:#fefce8;color:#ca8a04;">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <div class="label">Polling Station</div>
                            <div class="value" style="font-size:13px;">{{ $voter->polling_station ?? 'Not Assigned' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="status-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="background:#f5f3ff;color:#7c3aed;">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div>
                            <div class="label">Transfer</div>
                            <div class="value">
                                <span class="badge bg-secondary" style="font-size:11px;font-weight:700;">None</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="section-title"><i class="fas fa-bolt me-2"></i> Quick Actions</div>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="0">
                <a href="{{ route('voter.portal.profile') }}" class="action-card">
                    <div class="icon-circle" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-user"></i></div>
                    <h6>View Profile</h6>
                    <p>Personal info</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('voter.portal.id-card') }}" class="action-card">
                    <div class="icon-circle" style="background:#fefce8;color:#ca8a04;"><i class="fas fa-id-card"></i></div>
                    <h6>ID Card</h6>
                    <p>Download</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('voter.portal.transfer') }}" class="action-card">
                    <div class="icon-circle" style="background:#f0f7ff;color:#2563eb;"><i class="fas fa-exchange-alt"></i></div>
                    <h6>Transfer</h6>
                    <p>Request</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('voter.portal.transfer-status') }}" class="action-card">
                    <div class="icon-circle" style="background:#fdf2f8;color:#db2777;"><i class="fas fa-clipboard-list"></i></div>
                    <h6>Status</h6>
                    <p>Check requests</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('voter.polling-finder') }}" class="action-card">
                    <div class="icon-circle" style="background:#ecfeff;color:#0891b2;"><i class="fas fa-map-marker-alt"></i></div>
                    <h6>Find Station</h6>
                    <p>On map</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="500">
                <a href="{{ route('voter.report-issue') }}" class="action-card">
                    <div class="icon-circle" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-flag"></i></div>
                    <h6>Report</h6>
                    <p>Issue</p>
                </a>
            </div>
        </div>

        <div class="row g-4">
            {{-- Recent Activity --}}
            <div class="col-lg-7" data-aos="fade-up">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="section-title"><i class="fas fa-history me-2"></i> Recent Activity</div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:#16a34a;"></div>
                            <div class="flex-grow-1">
                                <div style="font-size:13px;font-weight:600;color:#1d2327;">Logged in to Voter Portal</div>
                                <div style="font-size:12px;color:#8c8f94;">Just now</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:#2563eb;"></div>
                            <div class="flex-grow-1">
                                <div style="font-size:13px;font-weight:600;color:#1d2327;">Registration verified</div>
                                <div style="font-size:12px;color:#8c8f94;">Your registration is active and up to date</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:#ca8a04;"></div>
                            <div class="flex-grow-1">
                                <div style="font-size:13px;font-weight:600;color:#1d2327;">Account created</div>
                                <div style="font-size:12px;color:#8c8f94;">Voter portal account successfully registered</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Announcements --}}
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="section-title"><i class="fas fa-bullhorn me-2"></i> Announcements</div>
                        <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:12px;padding:16px;margin-bottom:12px;border:1px solid #6ee7b7;">
                            <div style="font-size:13px;font-weight:700;color:#065f46;margin-bottom:4px;">
                                <i class="fas fa-calendar-alt me-1"></i> Upcoming Election
                            </div>
                            <div style="font-size:12px;color:#047857;">General Elections — 22 December 2026</div>
                        </div>
                        <div style="background:#fffbeb;border-radius:12px;padding:16px;margin-bottom:12px;border:1px solid #fde68a;">
                            <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:4px;">
                                <i class="fas fa-exclamation-triangle me-1"></i> Registration Deadline
                            </div>
                            <div style="font-size:12px;color:#a16207;">Voter registration closes 30 days before election day.</div>
                        </div>
                        <div style="background:#f0f7ff;border-radius:12px;padding:16px;border:1px solid #93c5fd;">
                            <div style="font-size:13px;font-weight:700;color:#1e40af;margin-bottom:4px;">
                                <i class="fas fa-info-circle me-1"></i> Keep Your Details Updated
                            </div>
                            <div style="font-size:12px;color:#1d4ed8;">Ensure your phone number and email are current for election notifications.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
