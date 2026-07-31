@extends('layouts.app', ['title' => 'NEC South Sudan - National Elections Commission', 'active_page' => 'home'])

@php
    $stats['total_voters'] = $stats['voters'] ?? 12000000;
    $showStat = fn($key) => isset($stats[$key]) && $stats[$key] !== null;
    $showStats = \App\Helpers\NecHelper::setting_get('public_show_stats', '1') === '1';
    $showSocials = \App\Helpers\NecHelper::setting_get('public_show_socials', '1') === '1';
@endphp

@push('styles')
<style>
    .dl-card {
        display: block;
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        padding: 1.4rem 1.1rem 1rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
    .dl-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ac-g);
        transition: height 0.3s;
    }
    .dl-card:hover {
        transform: translateY(-8px);
        border-color: transparent;
        box-shadow: 0 18px 40px var(--ac-soft);
    }
    .dl-card:hover::before { height: 6px; }
    .dl-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: var(--ac-g);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 0.9rem;
        box-shadow: 0 8px 20px var(--ac-soft);
        transition: all 0.4s ease;
    }
    .dl-card:hover .dl-icon {
        transform: rotate(-8deg) scale(1.1);
    }
    .dl-card:hover .dl-icon i { animation: dl-bounce 0.6s ease; }
    @keyframes dl-bounce {
        0%, 100% { transform: translateY(0); }
        40% { transform: translateY(-6px); }
        70% { transform: translateY(2px); }
    }
    .dl-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #2b2b2b;
        text-align: center;
        line-height: 1.3;
        min-height: 2.6rem;
        margin-bottom: 0.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dl-tags {
        display: flex;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }
    .dl-type {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--ac);
        background: var(--ac-soft);
        padding: 2px 8px;
        border-radius: 20px;
    }
    .dl-size {
        font-size: 0.68rem;
        font-weight: 700;
        color: #fff;
        background: #334155;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .dl-download {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--ac);
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        border-top: 1px dashed #e8e8e8;
        transition: all 0.3s;
    }
    .dl-count {
        display: inline-flex;
        align-items: center;
        font-size: 0.62rem;
        color: #64748b;
        letter-spacing: 0.3px;
    }
    .dl-card:hover .dl-download {
        color: #fff;
        background: var(--ac-g);
        border-top-color: transparent;
    }
    .dl-card:hover .dl-count { color: rgba(255,255,255,0.9); }
</style>
@endpush

@section('hero')
<!-- HERO CAROUSEL -->
<section class="hero-carousel-section">
    <div id="necHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#necHeroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#necHeroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#necHeroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide" style="background:linear-gradient(135deg,var(--nec-green-dark) 0%,var(--nec-green) 100%);">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-7">
                                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold fs-6 d-inline-block">
                                    <i class="fas fa-check-circle me-1"></i> Elections 2026
                                </div>
                                <h1 class="display-4 fw-bold text-white mb-3">Your Voice, Your Future</h1>
                                <p class="lead text-white-50 mb-4">The National Elections Commission is committed to delivering free, fair, and credible elections for the people of South Sudan.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('voter.register') }}" class="btn btn-lg btn-warning fw-bold px-4 py-3" style="background:var(--nec-gold);border-color:var(--nec-gold);color:var(--nec-black);">
                                        <i class="fas fa-vote-yea me-2"></i> Register to Vote
                                    </a>
                                    <a href="{{ route('about.mandate') }}" class="btn btn-lg btn-outline-light fw-bold px-4 py-3">
                                        <i class="fas fa-info-circle me-2"></i> Learn More
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-center">
                                <i class="fas fa-landmark" style="font-size:6rem;color:var(--nec-gold);opacity:0.8;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background:linear-gradient(135deg,var(--nec-gray-900) 0%,#1a3a2a 100%);">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-7">
                                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold fs-6 d-inline-block">
                                    <i class="fas fa-users me-1"></i> Voter Registration
                                </div>
                                <h1 class="display-4 fw-bold text-white mb-3">Register Today</h1>
                                <p class="lead text-white-50 mb-4">Ensure your voice is heard. Check your registration status or register to vote in the upcoming general elections.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('voter.inquiry') }}" class="btn btn-lg btn-warning fw-bold px-4 py-3" style="background:var(--nec-gold);border-color:var(--nec-gold);color:var(--nec-black);">
                                        <i class="fas fa-search me-2"></i> Verify Registration
                                    </a>
                                    <a href="{{ route('voter.polling-finder') }}" class="btn btn-lg btn-outline-light fw-bold px-4 py-3">
                                        <i class="fas fa-map-marker-alt me-2"></i> Find Polling Station
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-center">
                                <i class="fas fa-id-card" style="font-size:6rem;color:var(--nec-gold);opacity:0.8;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background:linear-gradient(135deg,var(--nec-red-dark) 0%,var(--nec-gray-900) 100%);">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-7">
                                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold fs-6 d-inline-block">
                                    <i class="fas fa-star me-1"></i> Democracy in Action
                                </div>
                                <h1 class="display-4 fw-bold text-white mb-3">Building Democracy Together</h1>
                                <p class="lead text-white-50 mb-4">From voter education to election day operations, NEC works tirelessly to ensure every vote counts.</p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('observers.accredit') }}" class="btn btn-lg btn-warning fw-bold px-4 py-3" style="background:var(--nec-gold);border-color:var(--nec-gold);color:var(--nec-black);">
                                        <i class="fas fa-binoculars me-2"></i> Observer Accreditation
                                    </a>
                                    <a href="{{ route('media.news') }}" class="btn btn-lg btn-outline-light fw-bold px-4 py-3">
                                        <i class="fas fa-newspaper me-2"></i> Latest News
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-center">
                                <i class="fas fa-handshake" style="font-size:6rem;color:var(--nec-gold);opacity:0.8;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#necHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#necHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>
@endsection

@section('content')
<!-- FLAG STRIPES -->
<section class="flag-animation-section overflow-hidden">
    <div class="flag-stripes-container d-flex">
        <div class="stripe-black" style="flex:1;height:5px;"></div>
        <div class="stripe-red" style="flex:1;height:5px;display:flex;align-items:center;justify-content:center;height:32px;color:#fff;font-weight:700;font-size:0.85rem;letter-spacing:3px;">ONE NATION · ONE PEOPLE · ONE DESTINY</div>
        <div class="stripe-green" style="flex:1;height:5px;"></div>
    </div>
</section>

<!-- STATS COUNTERS -->
@if($showStats)
<section class="py-5 section-shaded">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-chart-line', 'label' => 'Election Snapshot', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold">South Sudan at a Glance</h2>
            <p class="text-muted mb-0">Key statistics for the 2026 General Elections</p>
        </div>
        <div class="stat-grid mb-0 stat-grid-6">
            @if(($stats['voters'] ?? null) !== null)
            <div class="stat-slim info reveal reveal-delay-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['voters'] }}">0</div>
                        <div class="stat-label">Voters</div>
                    </div>
                </div>
            </div>
            @endif
            @if(($stats['constituencies'] ?? null) !== null)
            <div class="stat-slim purple reveal reveal-delay-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['constituencies'] }}">0</div>
                        <div class="stat-label">Constituencies</div>
                    </div>
                </div>
            </div>
            @endif
            @if(($stats['polling_stations'] ?? null) !== null)
            <div class="stat-slim teal reveal reveal-delay-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-school"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['polling_stations'] }}">0</div>
                        <div class="stat-label">Polling Stations</div>
                    </div>
                </div>
            </div>
            @endif
            @if(($stats['parties'] ?? null) !== null)
            <div class="stat-slim orange reveal reveal-delay-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-flag"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['parties'] }}">0</div>
                        <div class="stat-label">Parties</div>
                    </div>
                </div>
            </div>
            @endif
            @if(($stats['candidates'] ?? null) !== null)
            <div class="stat-slim danger reveal reveal-delay-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['candidates'] }}">0</div>
                        <div class="stat-label">Candidates</div>
                    </div>
                </div>
            </div>
            @endif
            @if(($stats['observers'] ?? null) !== null)
            <div class="stat-slim success reveal reveal-delay-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon"><i class="fas fa-binoculars"></i></div>
                    <div>
                        <div class="stat-value-sm" data-count="{{ $stats['observers'] }}">0</div>
                        <div class="stat-label">Observers</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- COUNTDOWN -->
<section class="py-5 section-gradient">
    <div class="container text-center">
        <h2 class="text-white fw-bold mb-2 reveal">Countdown to Election Day</h2>
        <p class="text-white-50 mb-4 reveal reveal-delay-1">{{ $electionType ?? 'General Elections' }} — <strong>{{ $electionDate ? date('d F Y', strtotime($electionDate)) : '22 December 2026' }}</strong></p>
        <div class="row justify-content-center g-3" id="countdownTimer">
            <div class="col-3 col-md-2 reveal reveal-delay-1">
                <div class="card-elevated bg-dark bg-opacity-25 text-center py-3 px-2 border-0">
                    <div class="fs-1 fw-bold text-white" id="countDays">00</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:0.8rem;">Days</div>
                </div>
            </div>
            <div class="col-3 col-md-2 reveal reveal-delay-2">
                <div class="card-elevated bg-dark bg-opacity-25 text-center py-3 px-2 border-0">
                    <div class="fs-1 fw-bold text-white" id="countHours">00</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:0.8rem;">Hours</div>
                </div>
            </div>
            <div class="col-3 col-md-2 reveal reveal-delay-3">
                <div class="card-elevated bg-dark bg-opacity-25 text-center py-3 px-2 border-0">
                    <div class="fs-1 fw-bold text-white" id="countMinutes">00</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:0.8rem;">Minutes</div>
                </div>
            </div>
            <div class="col-3 col-md-2 reveal reveal-delay-4">
                <div class="card-elevated bg-dark bg-opacity-25 text-center py-3 px-2 border-0">
                    <div class="fs-1 fw-bold text-white" id="countSeconds">00</div>
                    <div style="color:rgba(255,255,255,0.6);font-size:0.8rem;">Seconds</div>
                </div>
            </div>
        </div>
        <a href="{{ route('elections.calendar') }}" class="btn btn-outline-light fw-bold px-4 py-2 mt-4 reveal reveal-delay-5">
            <i class="fas fa-calendar-alt me-2"></i> View Full Election Calendar
        </a>
    </div>
</section>

<!-- ELECTION TIMELINE -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-road', 'label' => 'Road to 2026', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold reveal">Election Timeline</h2>
            <p class="text-muted mb-0">Key milestones on the path to the 2026 General Elections</p>
        </div>
        @php
        $timeline = [
            ['icon' => 'fa-user-plus', 'title' => 'Voter Registration', 'date' => 'Jan – Mar 2026', 'desc' => 'Citizens register to vote nationwide', 'status' => 'completed'],
            ['icon' => 'fa-clipboard-check', 'title' => 'Voter Verification', 'date' => 'Apr – Jun 2026', 'desc' => 'Confirm your registration details', 'status' => 'completed'],
            ['icon' => 'fa-file-signature', 'title' => 'Candidacy', 'date' => 'Jul – Sep 2026', 'desc' => 'Candidates submit nominations', 'status' => 'active'],
            ['icon' => 'fa-campground', 'title' => 'Campaigns', 'date' => 'Oct – Nov 2026', 'desc' => 'Political campaigns take place', 'status' => 'upcoming'],
            ['icon' => 'fa-vote-yea', 'title' => 'Election Day', 'date' => '22 Dec 2026', 'desc' => 'Polling stations open nationwide', 'status' => 'upcoming'],
            ['icon' => 'fa-chart-bar', 'title' => 'Results', 'date' => 'Dec 2026 – Jan 2027', 'desc' => 'Tallying and announcement', 'status' => 'upcoming'],
        ];
        $doneCount = collect($timeline)->filter(fn($s) => $s['status'] === 'completed')->count();
        $progress = round($doneCount / count($timeline) * 100);
        @endphp
        <div class="col-lg-11 mx-auto">
            <div class="d-flex align-items-center gap-3 mb-5 reveal">
                <div class="fw-bold text-uppercase flex-shrink-0" style="font-size:0.7rem;letter-spacing:2px;color:var(--nec-green);"><i class="fas fa-chart-line me-1"></i>Progress</div>
                <div style="flex:1;height:8px;border-radius:4px;background:#e9ecef;overflow:hidden;">
                    <div style="height:100%;width:{{ $progress }}%;background:linear-gradient(90deg,var(--nec-green) 0%,var(--nec-gold) 100%);border-radius:4px;transition:width 1.2s ease;"></div>
                </div>
                <div class="fw-bold flex-shrink-0" style="font-size:0.9rem;color:var(--nec-green);">{{ $progress }}%</div>
            </div>
            <div class="position-relative">
                <div class="d-none d-lg-block" style="position:absolute;top:30px;left:8.33%;right:8.33%;height:3px;background:linear-gradient(to right,var(--nec-green) {{ $progress }}%,#e9ecef {{ $progress }}%);"></div>
                <div class="row g-4">
                    @foreach($timeline as $ti => $step)
                    <div class="col-6 col-lg-2 reveal reveal-delay-{{ ($ti % 4) + 1 }}">
                        <div class="text-center">
                            <div class="mx-auto position-relative d-flex align-items-center justify-content-center mb-3 {{ $step['status'] === 'active' ? 'timeline-pulse' : '' }}" style="width:60px;height:60px;border-radius:50%;z-index:1;{{ $step['status'] === 'completed' ? 'background:var(--nec-green);' : ($step['status'] === 'active' ? 'background:var(--nec-gold);box-shadow:0 0 0 6px rgba(212,175,55,0.18);' : 'background:#fff;border:2px solid #d4d4d4;') }}">
                                <i class="fas {{ $step['icon'] }} {{ $step['status'] === 'completed' ? 'text-white' : ($step['status'] === 'active' ? 'text-dark' : 'text-muted') }}" style="font-size:1.2rem;"></i>
                                @if($step['status'] === 'completed')
                                <span style="position:absolute;top:-4px;right:-4px;width:20px;height:20px;border-radius:50%;background:var(--nec-gold);color:#000;display:flex;align-items:center;justify-content:center;font-size:0.6rem;border:2px solid #fff;"><i class="fas fa-check"></i></span>
                                @endif
                            </div>
                            <div class="card border-0 shadow-sm text-center h-100 px-2 py-3" style="border-radius:10px;border-top:3px solid {{ $step['status'] === 'completed' ? 'var(--nec-green)' : ($step['status'] === 'active' ? 'var(--nec-gold)' : '#e0e0e0') }};">
                                <span class="badge mx-auto mb-2" style="{{ $step['status'] === 'completed' ? 'background:rgba(0,145,76,0.12);color:var(--nec-green);' : ($step['status'] === 'active' ? 'background:rgba(212,175,55,0.22);color:#8a6d00;' : 'background:#e9ecef;color:#6c757d;') }}font-size:0.55rem;letter-spacing:1px;">
                                    {{ $step['status'] === 'completed' ? '✓ Completed' : ($step['status'] === 'active' ? '● In Progress' : '○ Upcoming') }}
                                </span>
                                <h6 class="fw-bold mb-1" style="font-size:0.8rem;">{{ $step['title'] }}</h6>
                                <div class="fw-semibold mb-1" style="font-size:0.68rem;color:var(--nec-gold);"><i class="far fa-calendar-alt me-1"></i>{{ $step['date'] }}</div>
                                <p class="text-muted mb-0" style="font-size:0.65rem;line-height:1.4;">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA BANNER -->
<section class="py-5" style="background:linear-gradient(135deg,var(--nec-green-dark) 0%,var(--nec-green) 60%,var(--nec-gold) 160%);">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 reveal-left">
                <h2 class="fw-bold text-white mb-2">Your Voice. Your Vote. Your Future.</h2>
                <p class="text-white-50 mb-0" style="color:rgba(255,255,255,0.7) !important;">Register to vote, verify your registration, or get accredited as an observer for the 2026 General Elections.</p>
            </div>
            <div class="col-lg-4 reveal-right">
                <div class="d-flex flex-wrap gap-3 justify-content-lg-end">
                    <a href="{{ route('voter.register') }}" class="btn btn-lg fw-bold px-4" style="background:var(--nec-gold);border-color:var(--nec-gold);color:var(--nec-black);">
                        <i class="fas fa-vote-yea me-2"></i> Register to Vote
                    </a>
                    <a href="{{ route('observers.accredit') }}" class="btn btn-lg btn-outline-light fw-bold px-4">
                        <i class="fas fa-binoculars me-2"></i> Observer
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CHAIRPERSON -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 text-center reveal-left">
                <div class="section-card border-0 d-inline-flex align-items-center justify-content-center overflow-hidden" style="width:360px;height:360px;border-radius:50%;border:4px solid var(--nec-gold);box-shadow:0 0 30px rgba(212,175,55,0.3);">
                    <img src="{{ asset('assets/images/chairperson.webp') }}" alt="Prof. Abednego Akok Kacuol" style="width:100%;height:100%;object-fit:cover;transform:scale(1.3);">
                </div>
            </div>
            <div class="col-lg-7 reveal-right">
                @include('partials.section-heading', ['icon' => 'fa-user-tie', 'label' => 'Message from the Chairperson'])
                <h2 class="fw-bold mb-3">Hon. Prof. Abednego Akok Kacuol</h2>
                <div class="section-card border-left-4 ps-4" style="border-left:4px solid var(--nec-gold);">
                    <p class="mb-2 fst-italic" style="font-size:1.05rem;color:var(--nec-gray-600);line-height:1.8;">
                    Our commitment is to deliver elections that reflect the true will of the South Sudanese people.
                    The NEC is dedicated to transparency, inclusivity, and integrity in every step of the electoral process.
                    I call upon all citizens to actively participate in shaping the future of our great nation.
                    </p>
                    <footer class="mt-2">
                        <strong class="d-block">Hon. Prof. Abednego Akok Kacuol</strong>
                        <span style="font-size:0.85rem;color:var(--nec-gray-500);">Chairperson, National Elections Commission</span>
                    </footer>
                </div>
                <a href="{{ route('about.leadership') }}" class="btn btn-nec fw-bold px-4 mt-3">
                    Meet the Leadership <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- VISION & MISSION -->
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-eye', 'label' => 'Who We Are', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold reveal">Our Vision & Mission</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5 reveal">
                <div class="card h-100 border-0 overflow-hidden" style="border-radius:14px;box-shadow:0 8px 28px rgba(0,0,0,0.08);">
                    <div class="p-4 text-center" style="background:linear-gradient(135deg,rgba(0,145,76,0.10) 0%,rgba(0,145,76,0.03) 100%);">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:50%;background:var(--nec-green);box-shadow:0 6px 18px rgba(0,145,76,0.35);">
                            <i class="fas fa-eye text-white" style="font-size:1.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Our Vision</h4>
                    </div>
                    <div class="p-4 pt-4" style="border-top:3px solid var(--nec-green);">
                        <p class="text-muted mb-0" style="line-height:1.9;">A peaceful, prosperous, and democratic South Sudan where free, fair, and credible elections uphold the will of the people and strengthen national unity.</p>
                        <div class="d-flex align-items-center gap-2 mt-3" style="font-size:0.72rem;color:var(--nec-green);font-weight:600;">
                            <i class="fas fa-check-circle"></i> Free &amp; Fair Elections
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 reveal reveal-delay-2">
                <div class="card h-100 border-0 overflow-hidden" style="border-radius:14px;box-shadow:0 8px 28px rgba(0,0,0,0.08);">
                    <div class="p-4 text-center" style="background:linear-gradient(135deg,rgba(212,175,55,0.14) 0%,rgba(212,175,55,0.04) 100%);">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:50%;background:var(--nec-gold);box-shadow:0 6px 18px rgba(212,175,55,0.4);">
                            <i class="fas fa-bullseye" style="font-size:1.5rem;color:#000;"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Our Mission</h4>
                    </div>
                    <div class="p-4 pt-4" style="border-top:3px solid var(--nec-gold);">
                        <p class="text-muted mb-0" style="line-height:1.9;">To organize, conduct, and supervise elections with transparency, inclusivity, and integrity — ensuring every eligible citizen can freely exercise their democratic right to vote.</p>
                        <div class="d-flex align-items-center gap-2 mt-3" style="font-size:0.72rem;color:#8a6d00;font-weight:600;">
                            <i class="fas fa-check-circle"></i> Transparent &amp; Inclusive
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CORE VALUES -->
        <div class="mt-5 reveal">
            <div class="text-center mb-4">
                @include('partials.section-heading', ['icon' => 'fa-star', 'label' => \App\Helpers\NecHelper::setting_get('core_values_title', 'Core Values'), 'align' => 'center', 'line' => false])
            </div>
            @php
            $cvDefaults = [
                ['icon' => 'fa-balance-scale', 'name' => 'Independence', 'desc' => 'Free from external interference in decision-making'],
                ['icon' => 'fa-eye', 'name' => 'Transparency', 'desc' => 'Open processes accessible to all stakeholders'],
                ['icon' => 'fa-handshake', 'name' => 'Impartiality', 'desc' => 'Neutral and unbiased service to all citizens'],
                ['icon' => 'fa-users', 'name' => 'Inclusivity', 'desc' => 'Equal participation opportunities for all'],
                ['icon' => 'fa-shield-alt', 'name' => 'Integrity', 'desc' => 'Highest ethical standards in all operations'],
                ['icon' => 'fa-star', 'name' => 'Excellence', 'desc' => 'Continuous improvement in service delivery'],
            ];
            $coreValues = [];
            foreach ($cvDefaults as $i => $d) {
                $n = $i + 1;
                $coreValues[] = [
                    'icon' => $d['icon'],
                    'name' => \App\Helpers\NecHelper::setting_get("core_value_{$n}_name", $d['name']),
                    'desc' => \App\Helpers\NecHelper::setting_get("core_value_{$n}_desc", $d['desc']),
                ];
            }
            @endphp
            <div class="row g-3 justify-content-center">
                @foreach($coreValues as $i => $cv)
                @php $isGreen = ($i % 2 === 0); @endphp
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-{{ ($i % 4) + 1 }}">
                    <div class="card h-100 border-0 overflow-hidden text-center" style="border-radius:14px;box-shadow:0 8px 28px rgba(0,0,0,0.08);">
                        <div class="p-3" style="background:{{ $isGreen ? 'linear-gradient(135deg,rgba(0,145,76,0.10) 0%,rgba(0,145,76,0.03) 100%)' : 'linear-gradient(135deg,rgba(212,175,55,0.14) 0%,rgba(212,175,55,0.04) 100%)' }};">
                            <div class="mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:50%;background:{{ $isGreen ? 'var(--nec-green)' : 'var(--nec-gold)' }};box-shadow:{{ $isGreen ? '0 6px 18px rgba(0,145,76,0.35)' : '0 6px 18px rgba(212,175,55,0.4)' }};">
                                <i class="fas {{ $cv['icon'] }} {{ $isGreen ? 'text-white' : '' }}" style="font-size:1.2rem;{{ $isGreen ? '' : 'color:#000;' }}"></i>
                            </div>
                            <h6 class="fw-bold mb-0" style="font-size:0.85rem;">{{ $cv['name'] }}</h6>
                        </div>
                        <div class="p-3" style="border-top:3px solid {{ $isGreen ? 'var(--nec-green)' : 'var(--nec-gold)' }};">
                            <p class="text-muted mb-0" style="line-height:1.7;font-size:0.8rem;">{{ $cv['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- LATEST NEWS -->
@if($showNews)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @include('partials.section-heading', ['icon' => 'fa-newspaper', 'label' => 'Stay Informed'])
                <h2 class="fw-bold reveal">Latest News & Updates</h2>
            </div>
            <a href="{{ route('media.news') }}" class="btn btn-nec d-none d-md-inline-block fw-bold">
                View All News <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($latestNews as $n)
            <div class="col-md-4 reveal reveal-delay-{{ $loop->iteration }}">
                <div class="card card-elevated h-100">
                    @if($n->image)
                    <div class="overflow-hidden" style="height:170px;border-radius:12px 12px 0 0;"><img src="{{ asset($n->image) }}" alt="{{ $n->title }}" style="width:100%;height:100%;object-fit:cover;"></div>
                    @else
                    <div class="d-flex align-items-center justify-content-center" style="height:170px;background:var(--nec-gradient-primary);border-radius:12px 12px 0 0;"><i class="fas fa-newspaper text-white" style="font-size:3rem;opacity:0.4;"></i></div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-{{ ($n->category ?? '') === 'press-release' ? 'success' : (($n->category ?? '') === 'news' ? 'info' : 'warning') }} mb-2">{{ ucfirst(str_replace('-', ' ', $n->category ?? 'General')) }}</span>
                        <h5 class="fw-bold mb-2">{{ $n->title }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit(strip_tags($n->excerpt ?? $n->title), 120) }}...</p>
                        <div class="d-flex align-items-center gap-3 text-muted small mb-3 mt-auto">
                            <span><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($n->created_at)->format('d M Y') }}</span>
                        </div>
                        <a href="{{ route('news.article', $n->slug) }}" class="fw-semibold text-decoration-none text-green">Read More <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-4"><p>No news articles published yet.</p></div>
            @endforelse
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('media.news') }}" class="btn btn-nec fw-bold">View All News <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>
@endif

<!-- QUICK SERVICES -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-bolt', 'label' => 'Quick Services', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold">How Can We Help You?</h2>
            <p class="text-muted mb-0">Access NEC services quickly and easily</p>
        </div>
        @php
        $services = [
            ['url' => 'voter.register', 'icon' => 'fa-vote-yea', 'label' => 'Register Voter'],
            ['url' => 'voter.inquiry', 'icon' => 'fa-id-card', 'label' => 'Voter Inquiry'],
            ['url' => 'voter.verify', 'icon' => 'fa-check-circle', 'label' => 'Verify Registration'],
            ['url' => 'voter.polling-finder', 'icon' => 'fa-map-marker-alt', 'label' => 'Find Polling Station'],
            ['url' => 'constituencies.index', 'icon' => 'fa-map-marked-alt', 'label' => 'Find Constituency'],
            ['url' => 'elections.calendar', 'icon' => 'fa-calendar-alt', 'label' => 'Election Calendar'],
            ['url' => 'candidates.index', 'icon' => 'fa-user-tie', 'label' => 'Candidate Search'],
            ['url' => 'parties.index', 'icon' => 'fa-flag', 'label' => 'Political Parties'],
            ['url' => 'observers.accredit', 'icon' => 'fa-binoculars', 'label' => 'Observer Accreditation'],
            ['url' => 'downloads.forms', 'icon' => 'fa-download', 'label' => 'Download Forms'],
            ['url' => 'elections.results', 'icon' => 'fa-chart-bar', 'label' => 'Election Results'],
            ['url' => 'voter.education', 'icon' => 'fa-book-open', 'label' => 'Civic Education'],
        ];
        $delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3','reveal-delay-4'];
        $svc_colors = ['info','green','orange','purple','cyan','teal','danger','success','warning','blue','pink','indigo'];
        @endphp
        <div class="row g-3">
            @foreach($services as $i => $svc)
            <div class="col-6 col-md-4 col-lg-3 reveal {{ $delays[$i % 4] }}">
                <a href="{{ route($svc['url']) }}" class="service-card {{ $svc_colors[$i] ?? 'info' }}">
                    <div class="service-icon" style="background:rgba(var(--nec-green-rgb),0.08);color:var(--nec-green);">
                        <i class="fas {{ $svc['icon'] }}"></i>
                    </div>
                    <div class="service-info">
                        <h6>{{ $svc['label'] }}</h6>
                        <p>Click to access</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ANNOUNCEMENTS & EVENTS -->
@if($showAnnouncements || $showEvents)
<section class="py-5 position-relative overflow-hidden" style="background:linear-gradient(160deg,#e9f6ef 0%,#f7fbf9 45%,#f0f7f4 100%);">
    <div style="position:absolute;top:-80px;right:-60px;width:260px;height:260px;border-radius:50%;background:rgba(212,175,55,0.10);"></div>
    <div style="position:absolute;bottom:-100px;left:-80px;width:300px;height:300px;border-radius:50%;background:rgba(0,145,76,0.07);"></div>
    <i class="fas fa-bullhorn" style="position:absolute;top:40px;left:30px;font-size:6rem;color:rgba(0,145,76,0.05);"></i>
    <i class="far fa-calendar-check" style="position:absolute;bottom:30px;right:40px;font-size:6rem;color:rgba(212,175,55,0.07);"></i>
    <div class="container position-relative">
        <div class="row g-4">
            @if($showAnnouncements)
            <div class="col-lg-8 reveal d-flex flex-column">
                @include('partials.section-heading', ['icon' => 'fa-bullhorn', 'label' => 'Announcements'])
                <h2 class="fw-bold mb-4">Public Notices</h2>
                <div class="d-flex flex-column gap-2 flex-grow-1">
                    @forelse($latestAnnouncements as $a)
                    <div class="d-flex gap-3 align-items-start bg-white px-3 py-3 shadow-sm" style="border-left:3px solid var(--nec-green);border-radius:8px;transition:transform 0.2s ease,box-shadow 0.2s ease;" onmouseover="this.style.transform='translateX(4px)';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.10)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
                        <div class="text-center flex-shrink-0" style="min-width:55px;">
                            <div class="fw-bold fs-5" style="color:var(--nec-green);">{{ \Carbon\Carbon::parse($a->created_at)->format('d') }}</div>
                            <div class="small text-muted" style="font-size:0.6rem;">{{ \Carbon\Carbon::parse($a->created_at)->format('M Y') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1" style="font-size:0.9rem;">{{ $a->title }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit(strip_tags($a->excerpt ?? $a->title), 120) }}</p>
                            <span class="small fw-semibold" style="color:var(--nec-green);cursor:pointer;">
                                Read More <i class="fas fa-arrow-right ms-1" style="font-size:0.6rem;"></i>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">No announcements published yet.</div>
                    @endforelse
                </div>
                <a href="{{ route('media.news') }}" class="btn fw-bold mt-3 px-4 align-self-start" style="background:var(--nec-green);color:#fff;border-radius:6px;">
                    View All Notices <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
            @if($showEvents)
            <div class="col-lg-4 reveal reveal-delay-2 d-flex flex-column">
                <div class="card border-0 shadow-sm flex-grow-1" style="border-radius:12px;overflow:hidden;">
                    <div class="card-header fw-bold text-white border-0 rounded-0 py-3" style="background:var(--nec-green);">
                        <i class="fas fa-calendar-check me-2"></i> Upcoming Events
                    </div>
                    <div class="card-body p-3">
                        @forelse($upcomingEvents as $ei => $ev)
                        <div class="{{ $loop->last ? '' : 'mb-3 pb-3 border-bottom' }}" style="border-color:#eee !important;">
                            <small class="fw-bold" style="color:var(--nec-gold);font-size:0.75rem;">
                                <i class="far fa-calendar-alt me-1"></i> {{ $ev->start_date->format('j M Y') }}{{ $ev->end_date ? ' - ' . $ev->end_date->format('j M Y') : '' }}
                            </small>
                            <p class="mb-0 mt-1 fw-semibold" style="font-size:0.85rem;">{{ $ev->title }}</p>
                        </div>
                        @empty
                        <div class="text-center text-muted small py-3">No upcoming events scheduled.</div>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('events.index') }}" class="btn fw-bold mt-3 px-4 align-self-start" style="background:var(--nec-green);color:#fff;border-radius:6px;">
                    View All Events <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- PHOTO GALLERY -->
@if($showGallery && ($galleryAlbums ?? collect())->isNotEmpty())
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @include('partials.section-heading', ['icon' => 'fa-images', 'label' => 'Photo Gallery'])
                <h2 class="fw-bold reveal">Moments in Pictures</h2>
            </div>
            <a href="{{ route('media.gallery') }}" class="btn btn-nec d-none d-md-inline-block fw-bold">
                View Gallery <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($galleryAlbums->take(4) as $album)
            <div class="col-6 col-lg-3 reveal reveal-delay-{{ $loop->iteration }}">
                <a href="{{ route('media.gallery') }}" class="text-decoration-none d-block position-relative overflow-hidden" style="border-radius:12px;height:200px;box-shadow:0 4px 16px rgba(0,0,0,0.1);">
                    <img src="{{ asset($album->featured_image ?: ($album->images()->first()->image_path ?? 'assets/images/flag-gu.webp')) }}" alt="{{ $album->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;">
                    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.65) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:14px;">
                        <h6 class="text-white fw-bold mb-1" style="font-size:0.85rem;">{{ $album->title }}</h6>
                        <small style="color:rgba(255,255,255,0.8);font-size:0.7rem;"><i class="far fa-images me-1"></i>{{ $album->images_count }} photo{{ $album->images_count !== 1 ? 's' : '' }}</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('media.gallery') }}" class="btn btn-nec fw-bold">View Gallery <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>
@endif

<!-- CIVIC EDUCATION -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-6 d-flex reveal">
                <div class="d-flex flex-column justify-content-center">
                    @include('partials.section-heading', ['icon' => 'fa-gavel', 'label' => 'Know Your Rights'])
                    <h2 class="fw-bold mb-3">Civic & Voter Education</h2>
                    <p class="text-muted mb-3">Understanding the electoral process is the foundation of a strong democracy. Explore our civic education materials to learn about your rights and responsibilities as a voter.</p>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 section-card px-3 py-3" style="border-left:3px solid var(--nec-green);">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(0,145,76,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:var(--nec-green);font-size:0.85rem;"></i>
                                </div>
                                <span class="small fw-semibold">Voter Checklist</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 section-card px-3 py-3" style="border-left:3px solid var(--nec-gold);">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(212,175,55,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-gavel" style="color:var(--nec-gold);font-size:0.85rem;"></i>
                                </div>
                                <span class="small fw-semibold">Electoral Laws</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 section-card px-3 py-3" style="border-left:3px solid var(--nec-green);">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(0,145,76,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-eye" style="color:var(--nec-green);font-size:0.85rem;"></i>
                                </div>
                                <span class="small fw-semibold">Observer Guide</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 section-card px-3 py-3" style="border-left:3px solid var(--nec-gold);">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(212,175,55,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-question-circle" style="color:var(--nec-gold);font-size:0.85rem;"></i>
                                </div>
                                <span class="small fw-semibold">FAQ</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted small mb-3"><i class="fas fa-check-circle me-1" style="color:var(--nec-green);"></i> Every citizen aged 18+ has the right to vote in South Sudan's elections. Your vote is your voice — make it count.</p>
                    <a href="{{ route('voter.education') }}" class="btn fw-bold px-4 py-2 align-self-start" style="background:var(--nec-green);color:#fff;border-radius:6px;">
                        <i class="fas fa-graduation-cap me-2"></i> Explore Resources
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-flex reveal-right">
                <div class="d-flex align-items-center" style="position:relative;width:100%;">
                    <div style="width:100%;aspect-ratio:4/3;border-radius:5px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.1);">
                        <img src="{{ asset('assets/images/flag-gu.webp') }}" alt="Flag of South Sudan" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div style="position:absolute;bottom:10px;right:10px;background:var(--nec-gold);color:#fff;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DOWNLOADS -->
@if($showDownloads)
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-download', 'label' => 'Resources', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold">Downloads & Forms</h2>
            <p class="text-muted mb-0">Access important election documents and forms</p>
        </div>
        @php
        $dl_theme = [
            ['c' => '#2E8B57', 'g' => 'linear-gradient(135deg,#2E8B57,#4cc07e)', 'soft' => 'rgba(46,139,87,0.18)'],
            ['c' => '#d4af37', 'g' => 'linear-gradient(135deg,#d4af37,#f0cf68)', 'soft' => 'rgba(212,175,55,0.22)'],
            ['c' => '#1a3c8f', 'g' => 'linear-gradient(135deg,#1a3c8f,#4a78e0)', 'soft' => 'rgba(26,60,143,0.18)'],
            ['c' => '#0e7d7d', 'g' => 'linear-gradient(135deg,#0e7d7d,#40c4b6)', 'soft' => 'rgba(14,125,125,0.2)'],
        ];
        @endphp
        <div class="row g-4">
            @forelse($topDownloads as $j => $d)
                @php $t = $dl_theme[$j % 4]; @endphp
                <div class="col-6 col-md-4 col-lg-3 reveal reveal-delay-{{ ($j % 4) + 1 }}">
                    @php $dlType = $d instanceof \App\Models\Download ? 'file' : 'resource'; @endphp
                    <a href="{{ route('downloads.serve', ['type' => $dlType, 'id' => $d->id]) }}" class="dl-card text-decoration-none" style="--ac:{{ $t['c'] }};--ac-g:{{ $t['g'] }};--ac-soft:{{ $t['soft'] }};">
                        <div class="dl-icon"><i class="fas {{ $d->file_icon }}"></i></div>
                        <div class="dl-title">{{ $d->title }}</div>
                        <div class="dl-tags">
                            <span class="dl-type">{{ $d->file_type_label }}</span>
                            <span class="dl-size">{{ $d->formatted_size }}</span>
                        </div>
                        <div class="dl-download">
                            <span class="dl-count"><i class="fas fa-download me-1"></i>{{ $d->downloads_count_label }}</span>
                            <span class="dl-dl">Download<i class="fas fa-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
            @empty
            <div class="col-12 text-center text-muted py-3"><p>No downloads available yet.</p></div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('downloads.index') }}" class="btn fw-bold px-4" style="background:var(--nec-green);color:#fff;border-radius:0;">
                View All Documents <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- COMMISSIONERS -->
@if($showTeam)
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-5">
            @include('partials.section-heading', ['icon' => 'fa-users', 'label' => 'Commissioners', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold reveal">NEC Commissioners</h2>
            <p class="text-muted mb-0">Meet the commissioners steering South Sudan's electoral process</p>
        </div>
        @php
            $rowColsClass = match($teamColumns ?? 5) {
                2 => 'row-cols-md-2 row-cols-lg-2',
                3 => 'row-cols-md-3 row-cols-lg-3',
                4 => 'row-cols-md-2 row-cols-lg-4',
                5 => 'row-cols-md-3 row-cols-lg-5',
                6 => 'row-cols-md-3 row-cols-lg-6',
                default => 'row-cols-md-3 row-cols-lg-4',
            };
        @endphp
        <div class="row g-4 justify-content-center {{ $rowColsClass }}">
            @php
                $comm_bgs = [
                    'linear-gradient(180deg,#ffffff 0%,#ffffff 32%,#eef7f0 60%,#bfe0cd 100%)',
                    'linear-gradient(180deg,#ffffff 0%,#ffffff 32%,#eef4fb 60%,#b6cdec 100%)',
                    'linear-gradient(180deg,#ffffff 0%,#ffffff 32%,#fdf6e6 60%,#ecd9a8 100%)',
                    'linear-gradient(180deg,#ffffff 0%,#ffffff 32%,#ecf9f7 60%,#b2e0da 100%)',
                ];
            @endphp
            @forelse($commissioners as $c)
            <div class="reveal reveal-delay-{{ $loop->iteration % 4 }}">
                <div class="card team-card h-100" style="position:relative;border-radius:12px;overflow:hidden;border:1px solid #e0e0e0;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                    <div style="position:relative;height:300px;overflow:hidden;background:{{ $comm_bgs[$loop->iteration % 4] }};">
                        @if($c->photo)
                        <img src="{{ asset($c->photo) }}" alt="{{ $c->name }}" style="width:100%;height:100%;object-fit:cover;object-position:center 20%;">
                        @else
                        <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                            <i class="fas fa-user" style="font-size:2.6rem;color:#adb5bd;"></i>
                            <span style="font-size:1rem;font-weight:700;color:#6c757d;">{{ $c->initials }}</span>
                        </div>
                        @endif
                        <div class="team-hover-overlay">
                            <a href="{{ route('about.leadership') }}?member={{ $c->id }}" class="team-hover-btn">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="p-3 text-center">
                        <h6 class="fw-bold mb-1">{{ $c->name }}</h6>
                        <p style="color:var(--nec-green);font-size:0.8rem;font-weight:600;margin-bottom:0.5rem;">{{ $c->position }}</p>
                        @if($showSocials && ($c->email || $c->phone))
                        <div class="d-flex justify-content-center gap-2 mb-2">
                            @if($c->email)<a href="mailto:{{ $c->email }}" class="comm-social" style="--sc:#6c757d;" aria-label="Email"><i class="far fa-envelope"></i></a>@endif
                            @if($c->phone)<a href="tel:{{ $c->phone }}" class="comm-social" style="--sc:#6c757d;" aria-label="Phone"><i class="fas fa-phone"></i></a>@endif
                        </div>
                        @endif
                        @if($showSocials)
                        <div class="d-flex justify-content-center gap-2">
                            @if($c->facebook_url)<a href="{{ $c->facebook_url }}" class="comm-social" style="--sc:#1877F2;" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
                            @if($c->twitter_url)<a href="{{ $c->twitter_url }}" class="comm-social" style="--sc:#000;" target="_blank" rel="noopener" aria-label="X"><i class="fab fa-x-twitter"></i></a>@endif
                            @if($c->linkedin_url)<a href="{{ $c->linkedin_url }}" class="comm-social" style="--sc:#0A66C2;" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>@endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-4"><p>No commissioners listed yet.</p></div>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('about.commissioners') }}" class="btn fw-bold px-4" style="background:var(--nec-green);color:#fff;border-radius:0;">
                View All Commissioners <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- POLITICAL PARTIES -->
@if($showParties && ($politicalParties ?? collect())->isNotEmpty())
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4">
            @include('partials.section-heading', ['icon' => 'fa-flag', 'label' => 'Political Parties', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold reveal">Registered Political Parties</h2>
            <p class="text-muted mb-0">Contesting parties in the 2026 General Elections</p>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($politicalParties as $p)
            <div class="col-4 col-md-3 col-lg-2 reveal reveal-delay-{{ $loop->iteration % 4 }}">
                <a href="{{ route('parties.index') }}" class="text-decoration-none d-block p-3 text-center" style="background:#fff;border:1px solid #e0e0e0;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center overflow-hidden" style="width:56px;height:56px;border-radius:50%;background:{{ $p->color ?? '#f0f0f0' }};">
                        @if($p->logo)
                        <img src="{{ asset($p->logo) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <span class="fw-bold text-white" style="font-size:0.9rem;">{{ $p->acronym ?: Str::upper(Str::substr($p->name, 0, 3)) }}</span>
                        @endif
                    </div>
                    <div class="fw-bold" style="font-size:0.75rem;line-height:1.2;">{{ $p->acronym ?: $p->name }}</div>
                    @if($p->acronym)<div class="text-muted" style="font-size:0.6rem;">{{ $p->name }}</div>@endif
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('parties.index') }}" class="btn fw-bold px-4" style="background:var(--nec-green);color:#fff;border-radius:0;">
                View All Parties <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- PARTNERS & SUPPORT -->
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-4">
            @include('partials.section-heading', ['icon' => 'fa-handshake', 'label' => 'Our Partners', 'align' => 'center', 'line' => false])
            <h2 class="fw-bold reveal">Supported By</h2>
            <p class="text-muted mb-0">International partners supporting democracy in South Sudan</p>
        </div>
        @php
        $partners = [
            ['name' => 'United Nations', 'url' => 'https://www.un.org', 'color' => '#1a6bb3', 'initials' => 'UN'],
            ['name' => 'African Union', 'url' => 'https://au.int', 'color' => '#e7a832', 'initials' => 'AU'],
            ['name' => 'European Union', 'url' => 'https://europa.eu', 'color' => '#003399', 'initials' => 'EU'],
            ['name' => 'IGAD', 'url' => 'https://igad.int', 'color' => '#2d8c3c', 'initials' => 'IGAD'],
            ['name' => 'UNDP', 'url' => 'https://www.undp.org', 'color' => '#0073b7', 'initials' => 'UNDP'],
            ['name' => 'USAID', 'url' => 'https://www.usaid.gov', 'color' => '#ba0c2f', 'initials' => 'USAID'],
            ['name' => 'International IDEA', 'url' => 'https://www.idea.int', 'color' => '#5a2d82', 'initials' => 'IDEA'],
            ['name' => 'The Carter Center', 'url' => 'https://www.cartercenter.org', 'color' => '#1a5276', 'initials' => 'TCC'],
            ['name' => 'NDI', 'url' => 'https://www.ndi.org', 'color' => '#c0392b', 'initials' => 'NDI'],
            ['name' => 'IFES', 'url' => 'https://www.ifes.org', 'color' => '#1f618d', 'initials' => 'IFES'],
            ['name' => 'UNMISS', 'url' => 'https://unmiss.unmissions.org', 'color' => '#5b8c5a', 'initials' => 'UNMISS'],
            ['name' => 'ACME', 'url' => 'https://www.acme-elections.org', 'color' => '#b7950b', 'initials' => 'ACME'],
        ];
        @endphp
        <div style="overflow:hidden;position:relative;">
            <div class="partner-track">
                @foreach($partners as $pt)
                <a href="{{ $pt['url'] }}" target="_blank" rel="noopener" class="text-decoration-none flex-shrink-0" style="width:180px;">
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:8px;">
                        <div style="height:100px;background:{{ $pt['color'] }};display:flex;align-items:center;justify-content:center;">
                            <span class="fw-bold text-white" style="font-size:1.6rem;letter-spacing:1px;">{{ $pt['initials'] }}</span>
                        </div>
                        <div style="padding:6px 10px;background:#fff;">
                            <p class="mb-0 text-center text-dark" style="font-size:0.65rem;font-weight:600;line-height:1.2;">{{ $pt['name'] }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
                @foreach($partners as $pt)
                <a href="{{ $pt['url'] }}" target="_blank" rel="noopener" class="text-decoration-none flex-shrink-0" style="width:180px;">
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius:8px;">
                        <div style="height:100px;background:{{ $pt['color'] }};display:flex;align-items:center;justify-content:center;">
                            <span class="fw-bold text-white" style="font-size:1.6rem;letter-spacing:1px;">{{ $pt['initials'] }}</span>
                        </div>
                        <div style="padding:6px 10px;background:#fff;">
                            <p class="mb-0 text-center text-dark" style="font-size:0.65rem;font-weight:600;line-height:1.2;">{{ $pt['name'] }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra_scripts')
<style>
.stat-grid-6 { grid-template-columns: repeat(6, 1fr); }
@media (max-width: 992px) { .stat-grid-6 { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 576px) { .stat-grid-6 { grid-template-columns: repeat(2, 1fr); } }
.timeline-pulse { animation: timelinePulse 2s ease-in-out infinite; }
@keyframes timelinePulse {
    0%, 100% { box-shadow: 0 0 0 6px rgba(212,175,55,0.18); }
    50% { box-shadow: 0 0 0 12px rgba(212,175,55,0.08); }
}
.partner-track {
    display: flex;
    gap: 0.75rem;
    width: max-content;
    animation: partnerScroll 40s linear infinite;
}
.partner-track:hover { animation-play-state: paused; }
@keyframes partnerScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(calc(-180px * 12 - 0.75rem * 11)); }
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Countdown Timer
        var electionDate = new Date('2026-12-22T00:00:00').getTime();
        var countdown = setInterval(function () {
            var now = new Date().getTime();
            var distance = electionDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById('countDays').textContent = days.toString().padStart(2, '0');
            document.getElementById('countHours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countMinutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countSeconds').textContent = seconds.toString().padStart(2, '0');
            if (distance < 0) { clearInterval(countdown); }
        }, 1000);

        // Counter Animation
        var counters = document.querySelectorAll('[data-count]');
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var target = parseInt(entry.target.getAttribute('data-count'));
                    var count = 0;
                    var increment = Math.ceil(target / 100);
                    var timer = setInterval(function () {
                        count += increment;
                        if (count >= target) { entry.target.textContent = target.toLocaleString(); clearInterval(timer); }
                        else { entry.target.textContent = count.toLocaleString(); }
                    }, 20);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(function (counter) { observer.observe(counter); });
    });
</script>
@endsection
