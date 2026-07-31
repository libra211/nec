@extends('layouts.app', ['title' => 'Our Team', 'active_page' => 'about', 'meta_description' => 'Meet the leadership and commissioners of the National Elections Commission of South Sudan.'])

@php $showStats = \App\Helpers\NecHelper::setting_get('public_show_stats', '1') === '1'; @endphp

@push('styles')
<style>
    .team-hero {
        background: linear-gradient(135deg, #0a1628 0%, #1a3c8f 50%, #2E8B57 100%);
        position: relative;
        overflow: hidden;
    }
    .team-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: rgba(212,175,55,0.08);
    }
    .team-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(46,139,87,0.08);
    }
    .team-stats-bar {
        background: #fff;
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-top: -60px;
        position: relative;
        z-index: 10;
        box-shadow: 0 20px 60px rgba(0,0,0,0.08);
    }
    .stat-item {
        text-align: center;
        padding: 0.5rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--nec-green);
        line-height: 1;
    }
    .stat-label {
        font-size: 0.78rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
        font-weight: 600;
    }
    .member-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e5e5;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }
    .member-photo-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg,#ffffff 0%,#ffffff 30%,#eef7f0 55%,#bfe0cd 100%);
    }
    .member-photo-wrapper img {
        width: 100%;
        height: 280px;
        object-fit: contain;
    }
    .member-initials {
        width: 100%;
        height: 280px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #adb5bd;
    }
    .member-initials i {
        font-size: 4rem;
    }
    .member-initials span {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .member-social {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .member-social a {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f5;
        color: #495057 !important;
        font-size: 0.9rem;
        border: none;
        text-decoration: none !important;
        transition: all 0.25s ease;
    }
    .member-social a:hover {
        background: var(--nec-green);
        color: #fff !important;
        transform: translateY(-2px);
        text-decoration: none !important;
    }
    .member-divider {
        border: none;
        border-top: 1px solid #f0f0f0;
        margin: 0.9rem 0 0.8rem;
        opacity: 1;
    }
    .member-info {
        padding: 1.25rem;
    }
    .member-info a {
        text-decoration: none !important;
    }
    .member-position-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .badge-chairperson { background: #d4af37; color: #1a1a1a; }
    .badge-deputy { background: #1a3c8f; color: #fff; }
    .badge-commissioner { background: #2E8B57; color: #fff; }
    .member-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }
    .member-dept {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .member-years {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(8px);
        color: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Modal / Profile Detail Styles */
    .profile-modal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    .profile-modal .modal-body {
        padding: 0;
    }
    .profile-header {
        background: linear-gradient(135deg, #0a1628, #1a3c8f);
        padding: 2rem;
        color: #fff;
        position: relative;
    }
    .profile-photo-lg {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--nec-gold);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }
    .profile-initials-lg {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: rgba(255,255,255,0.9);
        border: 4px solid var(--nec-gold);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        background: linear-gradient(135deg, #1a3c8f, #2E8B57);
    }
    .profile-initials-lg i {
        font-size: 3rem;
    }
    .profile-initials-lg span {
        font-size: 1.1rem;
        font-weight: 700;
    }
    .profile-social-links a {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(46,139,87,0.85);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.9rem;
        transition: all 0.3s;
        text-decoration: none !important;
        border: 1px solid rgba(255,255,255,0.15);
    }
    .profile-social-links a:hover {
        background: var(--nec-gold);
        color: #1a1a1a;
        transform: translateY(-2px);
    }
    .profile-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.75rem 1.25rem;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .profile-tabs .nav-link.active {
        color: var(--nec-green);
        border-bottom-color: var(--nec-green);
        background: none;
    }
    .profile-tabs .nav-link:hover:not(.active) {
        color: var(--nec-black);
    }
    .profile-tab-content {
        padding: 1.5rem 2rem;
    }
    .experience-item {
        position: relative;
        padding-left: 24px;
        padding-bottom: 1.25rem;
        border-left: 2px solid #e9ecef;
    }
    .experience-item:last-child {
        border-left-color: transparent;
        padding-bottom: 0;
    }
    .experience-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--nec-green);
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px var(--nec-green);
    }
    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        background: #f0f4f8;
        font-size: 0.8rem;
        color: #495057;
        margin: 3px;
    }
    .info-chip i {
        color: var(--nec-green);
        font-size: 0.75rem;
    }

    /* Filter Bar */
    .filter-bar {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
    }
    .filter-btn {
        border: 2px solid #dee2e6;
        background: #fff;
        color: #495057;
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    .filter-btn:hover, .filter-btn.active {
        background: var(--nec-green);
        color: #fff;
        border-color: var(--nec-green);
    }
</style>
@endpush

@section('hero')
<section class="team-hero py-5">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center py-4">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2" style="font-size:2.8rem;">Our Team</h1>
                <p class="text-white-50 mb-3" style="font-size:1.1rem;max-width:600px;">Meet the dedicated leaders of the National Elections Commission of South Sudan — working to ensure free, fair, and transparent elections for all citizens.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50 text-decoration-none">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Our Team</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-users text-white" style="font-size:4rem;opacity:0.2;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">

        {{-- Stats Bar --}}
        <div class="team-stats-bar" data-aos="fade-up">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $commissioners->count() }}</div>
                        <div class="stat-label">Commissioners</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $commissioners->where('gender','female')->count() }}</div>
                        <div class="stat-label">Women Leaders</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $commissioners->sum('years_of_service') }}+</div>
                        <div class="stat-label">Years Combined</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">6</div>
                        <div class="stat-label">Departments</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chairperson Feature --}}
        @if($chairperson)
        <div class="row justify-content-center mt-5" data-aos="fade-up">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg" style="border-radius:20px;overflow:hidden;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="member-photo-wrapper h-100" style="min-height:350px;background:linear-gradient(180deg,#ffffff 0%,#ffffff 25%,#eef7f0 55%,#bfe0cd 100%);">
                                @if($chairperson->photo)
                                    <img src="{{ asset($chairperson->photo) }}" alt="{{ $chairperson->name }}" class="h-100" style="object-fit:contain;width:100%;">
                                @else
                                    <div class="member-initials h-100" style="height:100%;min-height:350px;"><i class="fas fa-user" style="font-size:5rem;"></i><span>{{ $chairperson->initials }}</span></div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8 p-4 p-md-5">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="member-position-badge badge-chairperson" style="font-size:0.75rem;">Chairperson</span>
                                @if($chairperson->years_of_service)
                                    <span class="info-chip"><i class="fas fa-calendar-alt"></i> {{ $chairperson->years_of_service }} years of service</span>
                                @endif
                            </div>
                            <h2 class="fw-bold mb-1" style="color:var(--nec-black);font-size:1.8rem;">{{ $chairperson->name }}</h2>
                            <p class="mb-3" style="color:var(--nec-green);font-weight:600;">{{ $chairperson->position }}, National Elections Commission</p>
                            <p class="text-muted mb-4" style="line-height:1.8;">{{ $chairperson->about ?? $chairperson->bio }}</p>

                            @if($showStats && $chairperson->email)
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-envelope" style="color:var(--nec-green);width:20px;"></i>
                                    <a href="mailto:{{ $chairperson->email }}" class="text-decoration-none" style="color:#495057;">{{ $chairperson->email }}</a>
                                </div>
                            @endif
                            @if($showStats && $chairperson->phone)
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fas fa-phone" style="color:var(--nec-green);width:20px;"></i>
                                    <span style="color:#495057;">{{ $chairperson->phone }}</span>
                                </div>
                            @endif

                            <div class="d-flex align-items-center gap-2 mt-4">
                                <button class="btn btn-sm px-3 py-2 fw-semibold" style="background:var(--nec-green);color:#fff;border-radius:8px;" onclick="openProfileModal({{ $chairperson->id }})">
                                    <i class="fas fa-user me-1"></i> View Full Profile
                                </button>
                                @if($showStats)
                                <div class="profile-social-links ms-2">
                                    @if($chairperson->facebook_url)
                                        <a href="{{ $chairperson->facebook_url }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if($chairperson->twitter_url)
                                        <a href="{{ $chairperson->twitter_url }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>
                                    @endif
                                    @if($chairperson->linkedin_url)
                                        <a href="{{ $chairperson->linkedin_url }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                                    @endif
                                    @if($chairperson->website_url)
                                        <a href="{{ $chairperson->website_url }}" target="_blank" rel="noopener"><i class="fas fa-globe"></i></a>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Filter Bar --}}
        <div class="filter-bar mt-5" data-aos="fade-up">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h5 class="fw-bold mb-0" style="color:var(--nec-black);">All Team Members</h5>
                <div class="d-flex flex-wrap gap-2">
                    <button class="filter-btn active" onclick="filterTeam('all', this)">All</button>
                    <button class="filter-btn" onclick="filterTeam('Chairperson', this)">Chairperson</button>
                    <button class="filter-btn" onclick="filterTeam('Deputy', this)">Deputy</button>
                    <button class="filter-btn" onclick="filterTeam('Commissioner', this)">Commissioners</button>
                    <button class="filter-btn" onclick="filterTeam('male', this)">Men</button>
                    <button class="filter-btn" onclick="filterTeam('female', this)">Women</button>
                </div>
            </div>
        </div>

        {{-- Team Grid --}}
        @php
            $member_bgs = [
                'linear-gradient(180deg,#ffffff 0%,#ffffff 28%,#eef7f0 55%,#bfe0cd 100%)',
                'linear-gradient(180deg,#ffffff 0%,#ffffff 28%,#eef4fb 55%,#b6cdec 100%)',
                'linear-gradient(180deg,#ffffff 0%,#ffffff 28%,#fdf6e6 55%,#ecd9a8 100%)',
                'linear-gradient(180deg,#ffffff 0%,#ffffff 28%,#ecf9f7 55%,#b2e0da 100%)',
            ];
        @endphp
        <div class="row g-4" id="teamGrid">
            @foreach($commissioners as $c)
                <div class="col-md-6 col-lg-4 team-col" data-position="{{ $c->position }}" data-gender="{{ $c->gender }}" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                    <div class="member-card h-100" onclick="openProfileModal({{ $c->id }})">
                        <div class="member-photo-wrapper" style="background:{{ $member_bgs[$loop->index % 4] }};">
                            @if($c->photo)
                                <img src="{{ asset($c->photo) }}" alt="{{ $c->name }}">
                            @else
                                <div class="member-initials"><i class="fas fa-user"></i><span>{{ $c->initials }}</span></div>
                            @endif
                            @if($c->years_of_service)
                                <span class="member-years"><i class="fas fa-clock me-1"></i>{{ $c->years_of_service }}y</span>
                            @endif
                        </div>
                        <div class="member-info">
                            @php
                                $badgeClass = 'badge-commissioner';
                                if (str_contains($c->position, 'Chairperson') && !str_contains($c->position, 'Deputy')) $badgeClass = 'badge-chairperson';
                                elseif (str_contains($c->position, 'Deputy')) $badgeClass = 'badge-deputy';
                            @endphp
                            <span class="member-position-badge {{ $badgeClass }}">{{ $c->position }}</span>
                            <h5 class="member-name">{{ $c->name }}</h5>
                            @if($c->department)
                                <p class="member-dept mb-0"><i class="fas fa-building me-1"></i>{{ $c->department }}</p>
                            @endif
                            @php $hasSocial = $c->facebook_url || $c->twitter_url || $c->linkedin_url || $c->website_url || $c->email || $c->phone; @endphp
                            @if($showStats && $hasSocial)
                                <hr class="member-divider">
                                <div class="member-social">
                                    @if($c->email)
                                        <a href="mailto:{{ $c->email }}" aria-label="Email" onclick="event.stopPropagation()"><i class="fas fa-envelope"></i></a>
                                    @endif
                                    @if($c->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c->phone) }}" aria-label="Phone" onclick="event.stopPropagation()"><i class="fas fa-phone"></i></a>
                                    @endif
                                    @if($c->facebook_url)
                                        <a href="{{ $c->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook" onclick="event.stopPropagation()"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if($c->twitter_url)
                                        <a href="{{ $c->twitter_url }}" target="_blank" rel="noopener" aria-label="X (Twitter)" onclick="event.stopPropagation()"><i class="fab fa-x-twitter"></i></a>
                                    @endif
                                    @if($c->linkedin_url)
                                        <a href="{{ $c->linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn" onclick="event.stopPropagation()"><i class="fab fa-linkedin-in"></i></a>
                                    @endif
                                    @if($c->website_url)
                                        <a href="{{ $c->website_url }}" target="_blank" rel="noopener" aria-label="Website" onclick="event.stopPropagation()"><i class="fas fa-globe"></i></a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Profile Modal --}}
@foreach($commissioners as $c)
<div class="modal fade profile-modal" id="profileModal{{ $c->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            {{-- Header --}}
            <div class="profile-header">
                <button type="button" class="btn-close btn-close-white position-absolute" style="top:16px;right:16px;z-index:10;" data-bs-dismiss="modal"></button>
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    @if($c->photo)
                        <img src="{{ asset($c->photo) }}" alt="{{ $c->name }}" class="profile-photo-lg">
                    @else
                        <div class="profile-initials-lg"><i class="fas fa-user"></i><span>{{ $c->initials }}</span></div>
                    @endif
                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-1">{{ $c->name }}</h3>
                        <p class="mb-2 opacity-75">{{ $c->position }}</p>
                        <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                            @if($c->department)
                                <span class="info-chip" style="background:rgba(255,255,255,0.15);color:#fff;"><i class="fas fa-building"></i> {{ $c->department }}</span>
                            @endif
                            @if($c->years_of_service)
                                <span class="info-chip" style="background:rgba(255,255,255,0.15);color:#fff;"><i class="fas fa-clock"></i> {{ $c->years_of_service }} years</span>
                            @endif
                            @if($c->nationality)
                                <span class="info-chip" style="background:rgba(255,255,255,0.15);color:#fff;"><i class="fas fa-flag"></i> {{ $c->nationality }}</span>
                            @endif
                        </div>
                        <div class="profile-social-links">
                            @if($showStats)
                            @if($c->facebook_url)
                                <a href="{{ $c->facebook_url }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if($c->twitter_url)
                                <a href="{{ $c->twitter_url }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>
                            @endif
                            @if($c->linkedin_url)
                                <a href="{{ $c->linkedin_url }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                            @endif
                            @if($c->website_url)
                                <a href="{{ $c->website_url }}" target="_blank" rel="noopener"><i class="fas fa-globe"></i></a>
                            @endif
                            @if($c->email)
                                <a href="mailto:{{ $c->email }}"><i class="fas fa-envelope"></i></a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="profile-tabs border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#about-{{ $c->id }}" role="tab">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#experience-{{ $c->id }}" role="tab">Experience</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#qualifications-{{ $c->id }}" role="tab">Qualifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#achievements-{{ $c->id }}" role="tab">Achievements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#contact-{{ $c->id }}" role="tab">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content profile-tab-content">
                {{-- About --}}
                <div class="tab-pane fade show active" id="about-{{ $c->id }}" role="tabpanel">
                    <h6 class="fw-bold mb-3" style="color:var(--nec-green);">About</h6>
                    <p style="line-height:1.9;color:#495057;">{{ $c->about ?? $c->bio }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @if($c->title)
                            <span class="info-chip"><i class="fas fa-user-tie"></i> {{ $c->title }}</span>
                        @endif
                        @if($c->gender)
                            <span class="info-chip"><i class="fas fa-venus-mars"></i> {{ ucfirst($c->gender) }}</span>
                        @endif
                        @if($c->date_of_birth)
                            <span class="info-chip"><i class="fas fa-birthday-cake"></i> {{ $c->date_of_birth->format('d M Y') }}</span>
                        @endif
                        @if($c->nationality)
                            <span class="info-chip"><i class="fas fa-flag"></i> {{ $c->nationality }}</span>
                        @endif
                    </div>
                </div>

                {{-- Experience --}}
                <div class="tab-pane fade" id="experience-{{ $c->id }}" role="tabpanel">
                    <h6 class="fw-bold mb-3" style="color:var(--nec-green);">Professional Experience</h6>
                    @if($c->experience)
                        @foreach(explode("\n", $c->experience) as $exp)
                            @if(trim($exp))
                                <div class="experience-item">
                                    <p class="mb-0 fw-semibold" style="color:var(--nec-black);font-size:0.9rem;">{{ $exp }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">Experience details not yet available.</p>
                    @endif
                </div>

                {{-- Qualifications --}}
                <div class="tab-pane fade" id="qualifications-{{ $c->id }}" role="tabpanel">
                    <h6 class="fw-bold mb-3" style="color:var(--nec-green);">Qualifications & Education</h6>
                    @if($c->qualifications)
                        @foreach(explode("\n", $c->qualifications) as $q)
                            @if(trim($q))
                                <div class="d-flex align-items-start gap-3 mb-3 p-3 rounded-3" style="background:#f8f9fa;">
                                    <div style="width:36px;height:36px;border-radius:10px;background:var(--nec-green);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-graduation-cap text-white" style="font-size:0.85rem;"></i>
                                    </div>
                                    <p class="mb-0" style="color:#495057;font-size:0.9rem;line-height:1.6;">{{ $q }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">Qualifications not yet available.</p>
                    @endif
                </div>

                {{-- Achievements --}}
                <div class="tab-pane fade" id="achievements-{{ $c->id }}" role="tabpanel">
                    <h6 class="fw-bold mb-3" style="color:var(--nec-green);">Key Achievements</h6>
                    @if($c->achievements)
                        @foreach(explode("\n", $c->achievements) as $a)
                            @if(trim($a))
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:28px;height:28px;border-radius:50%;background:var(--nec-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                                        <i class="fas fa-check text-dark" style="font-size:0.7rem;"></i>
                                    </div>
                                    <p class="mb-0" style="color:#495057;font-size:0.9rem;line-height:1.6;">{{ $a }}</p>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-muted">Achievements not yet available.</p>
                    @endif
                </div>

                {{-- Contact --}}
                <div class="tab-pane fade" id="contact-{{ $c->id }}" role="tabpanel">
                    <h6 class="fw-bold mb-3" style="color:var(--nec-green);">Contact Information</h6>
                    <div class="row g-3">
                        @if($showStats && $c->email)
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:#f0f7f4;">
                                    <i class="fas fa-envelope" style="color:var(--nec-green);font-size:1.2rem;"></i>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <a href="mailto:{{ $c->email }}" class="text-decoration-none fw-semibold" style="color:var(--nec-black);">{{ $c->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($showStats && $c->phone)
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:#f0f7f4;">
                                    <i class="fas fa-phone" style="color:var(--nec-green);font-size:1.2rem;"></i>
                                    <div>
                                        <small class="text-muted d-block">Phone</small>
                                        <span class="fw-semibold" style="color:var(--nec-black);">{{ $c->phone }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:#f0f7f4;">
                                <i class="fas fa-map-marker-alt" style="color:var(--nec-green);font-size:1.2rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Office</small>
                                    <span class="fw-semibold" style="color:var(--nec-black);">National Elections Commission, NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Thongpiny, Juba</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-3 d-flex align-items-center gap-3" style="background:#f0f7f4;">
                                <i class="fas fa-clock" style="color:var(--nec-green);font-size:1.2rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Office Hours</small>
                                    <span class="fw-semibold" style="color:var(--nec-black);">Mon – Fri: 8:00 AM – 5:00 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
function filterTeam(filter, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.team-col').forEach(col => {
        const pos = col.getAttribute('data-position');
        const gender = col.getAttribute('data-gender');
        let show = false;

        if (filter === 'all') show = true;
        else if (filter === 'Chairperson') show = pos.includes('Chairperson') && !pos.includes('Deputy');
        else if (filter === 'Deputy') show = pos.includes('Deputy');
        else if (filter === 'Commissioner') show = pos.includes('Commissioner');
        else if (filter === 'male') show = gender === 'male';
        else if (filter === 'female') show = gender === 'female';

        col.style.display = show ? '' : 'none';
        if (show) {
            col.style.opacity = '0';
            col.style.transform = 'translateY(20px)';
            setTimeout(() => {
                col.style.transition = 'all 0.4s ease';
                col.style.opacity = '1';
                col.style.transform = 'translateY(0)';
            }, 50);
        }
    });
}

function openProfileModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('profileModal' + id));
    modal.show();
}
</script>
@endpush
