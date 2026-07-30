@extends('layouts.app', ['title' => 'NEC South Sudan - National Elections Commission', 'active_page' => 'home'])

@php
    $stats['total_voters'] = $stats['voters'] ?? 12000000;
    $showStat = fn($key) => isset($stats[$key]) && $stats[$key] !== null;
@endphp

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
<section class="py-5 section-shaded">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-bold mb-1 gradient-text-gold" style="letter-spacing:2px;font-size:0.85rem;">Election Snapshot</p>
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

<!-- CHAIRPERSON -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 text-center reveal-left">
                <div class="section-card border-0 d-inline-flex align-items-center justify-content-center overflow-hidden" style="width:360px;height:360px;border-radius:50%;border:4px solid var(--nec-gold);box-shadow:0 0 30px rgba(212,175,55,0.3);">
                    <img src="{{ asset('assets/images/chairperson.webp') }}" alt="Prof. Abednego A. A. Akok" style="width:100%;height:100%;object-fit:cover;transform:scale(1.3);">
                </div>
            </div>
            <div class="col-lg-7 reveal-right">
                <p class="text-uppercase fw-bold mb-1 gradient-text-gold" style="letter-spacing:2px;font-size:0.85rem;">Message from the Chairperson</p>
                <h2 class="fw-bold mb-3">Hon. Prof. Abednego A. A. Akok</h2>
                <div class="section-card border-left-4 ps-4" style="border-left:4px solid var(--nec-gold);">
                    <p class="mb-2 fst-italic" style="font-size:1.05rem;color:var(--nec-gray-600);line-height:1.8;">
                    Our commitment is to deliver elections that reflect the true will of the South Sudanese people.
                    The NEC is dedicated to transparency, inclusivity, and integrity in every step of the electoral process.
                    I call upon all citizens to actively participate in shaping the future of our great nation.
                    </p>
                    <footer class="mt-2">
                        <strong class="d-block">Hon. Prof. Abednego A. A. Akok</strong>
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

<!-- LATEST NEWS -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-uppercase fw-bold mb-0 gradient-text-gold" style="letter-spacing:2px;font-size:0.85rem;">Stay Informed</p>
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
                    <div class="overflow-hidden" style="height:170px;border-radius:12px 12px 0 0;"><img src="{{ asset($n->image) }}" alt="{{ e($n->title) }}" style="width:100%;height:100%;object-fit:cover;"></div>
                    @else
                    <div class="d-flex align-items-center justify-content-center" style="height:170px;background:var(--nec-gradient-primary);border-radius:12px 12px 0 0;"><i class="fas fa-newspaper text-white" style="font-size:3rem;opacity:0.4;"></i></div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-{{ ($n->category ?? '') === 'press-release' ? 'success' : (($n->category ?? '') === 'news' ? 'info' : 'warning') }} mb-2">{{ e(ucfirst(str_replace('-', ' ', $n->category ?? 'General'))) }}</span>
                        <h5 class="fw-bold mb-2">{{ e($n->title) }}</h5>
                        <p class="text-muted small mb-3">{{ e(Str::limit(strip_tags($n->excerpt ?? $n->title), 120)) }}...</p>
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

<!-- QUICK SERVICES -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-bold mb-1" style="color:var(--nec-gold);letter-spacing:2px;font-size:0.85rem;">Quick Services</p>
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

<!-- ANNOUNCEMENTS -->
<section class="py-5" style="background:#f0f7f4;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8 reveal">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-dark fw-bold px-3 py-2 rounded-0" style="letter-spacing:2px;font-size:0.7rem;"><i class="fas fa-bullhorn me-1"></i> ANNOUNCEMENTS</span>
                </div>
                <h2 class="fw-bold mb-4">Public Notices</h2>
                <div class="d-flex flex-column gap-2">
                    @forelse($latestAnnouncements as $a)
                    <div class="d-flex gap-3 align-items-start bg-white px-3 py-3 shadow-sm" style="border-left:3px solid var(--nec-green);">
                        <div class="text-center flex-shrink-0" style="min-width:55px;">
                            <div class="fw-bold fs-5" style="color:var(--nec-green);">{{ \Carbon\Carbon::parse($a->created_at)->format('d') }}</div>
                            <div class="small text-muted" style="font-size:0.6rem;">{{ \Carbon\Carbon::parse($a->created_at)->format('M Y') }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1" style="font-size:0.9rem;">{{ e($a->title) }}</h6>
                            <p class="text-muted small mb-2">{{ e(Str::limit(strip_tags($a->excerpt ?? $a->title), 120)) }}</p>
                            <span class="small fw-semibold" style="color:var(--nec-green);cursor:pointer;">
                                Read More <i class="fas fa-arrow-right ms-1" style="font-size:0.6rem;"></i>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">No announcements published yet.</div>
                    @endforelse
                </div>
                <a href="{{ route('media.news') }}" class="btn fw-bold mt-3 px-4" style="background:var(--nec-green);color:#fff;border-radius:0;">
                    View All Notices <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="col-lg-4 reveal reveal-delay-2">
                <div class="card border-0 shadow-sm" style="border-radius:0;">
                    <div class="card-header fw-bold text-white border-0 rounded-0 py-3" style="background:var(--nec-green);">
                        <i class="fas fa-calendar-check me-2"></i> Upcoming Events
                    </div>
                    <div class="card-body p-3">
                        @forelse($upcomingEvents as $ei => $ev)
                        <div class="{{ $loop->last ? '' : 'mb-3 pb-3 border-bottom' }}" style="border-color:#eee !important;">
                            <small class="fw-bold" style="color:var(--nec-gold);font-size:0.75rem;">
                                <i class="far fa-calendar-alt me-1"></i> {{ $ev->start_date->format('j M Y') }}{{ $ev->end_date ? ' - ' . $ev->end_date->format('j M Y') : '' }}
                            </small>
                            <p class="mb-0 mt-1 fw-semibold" style="font-size:0.85rem;">{{ e($ev->title) }}</p>
                        </div>
                        @empty
                        <div class="text-center text-muted small py-3">No upcoming events scheduled.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CIVIC EDUCATION -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-6 d-flex reveal">
                <div class="d-flex flex-column justify-content-center">
                    <span class="badge bg-dark fw-bold px-4 py-2 rounded-0 mb-3" style="letter-spacing:2px;font-size:0.7rem;"><i class="fas fa-gavel me-1"></i> KNOW YOUR RIGHTS</span>
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
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-dark fw-bold px-3 py-2 rounded-0 mb-3" style="letter-spacing:2px;font-size:0.7rem;">RESOURCES</span>
            <h2 class="fw-bold">Downloads & Forms</h2>
            <p class="text-muted mb-0">Access important election documents and forms</p>
        </div>
        @php
        $stat_colors = ['info','purple','success','warning','teal','primary'];
        $d_delays = ['reveal-delay-1','reveal-delay-2','reveal-delay-3','reveal-delay-4','reveal-delay-1','reveal-delay-2'];
        @endphp
        <div class="row g-3">
            @forelse($topDownloads as $j => $d)
            <div class="col-md-4 col-lg-2 reveal {{ $d_delays[$j % 4] }}">
                <a href="{{ asset($d->file_path) }}" class="stat-slim {{ $stat_colors[$j % 6] }} text-decoration-none d-block" target="_blank">
                    <div class="stat-icon"><i class="fas {{ $d->file_icon }}"></i></div>
                    <div class="stat-label">{{ e($d->title) }}</div>
                    <div style="font-size:0.6rem;color:rgba(0,0,0,0.45);margin-bottom:2px;">{{ $d->file_type_label }}</div>
                    <div class="stat-value-sm" style="font-size:0.85rem;font-weight:600;">{{ $d->formatted_size }}</div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-3"><p>No downloads available yet.</p></div>
            @endforelse
        </div>
    </div>
</section>

<!-- COMMISSIONERS -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-dark fw-bold px-3 py-2 rounded-0 mb-3" style="letter-spacing:2px;font-size:0.7rem;">LEADERSHIP</span>
            <h2 class="fw-bold reveal">NEC Commissioners</h2>
            <p class="text-muted mb-0">Meet the commissioners steering South Sudan's electoral process</p>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($commissioners as $c)
            <div class="col-6 col-md-4 col-lg-3 reveal reveal-delay-{{ $loop->iteration % 4 }}">
                <div class="card border-0 shadow-sm text-center h-100 p-4" style="border-radius:12px;">
                    <div class="mx-auto mb-3" style="width:90px;height:90px;border-radius:50%;overflow:hidden;border:3px solid var(--nec-green);">
                        @if($c->photo)
                        <img src="{{ asset($c->photo) }}" alt="{{ $c->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <div style="width:100%;height:100%;background:var(--nec-green);display:flex;align-items:center;justify-content:center;"><span class="fw-bold text-white" style="font-size:1.2rem;">NE</span></div>
                        @endif
                    </div>
                    <h6 class="fw-bold mb-1 small">{{ $c->name }}</h6>
                    <span class="badge bg-light text-dark border fw-semibold mb-2 px-3 py-1" style="font-size:0.7rem;">{{ $c->position }}</span>
                    @if($c->department)<p class="text-muted mb-2" style="font-size:0.7rem;">{{ $c->department }}</p>@endif
                    <div class="d-flex justify-content-center gap-2 mt-auto pt-2 border-top">
                        @if($c->facebook_url)<a href="{{ $c->facebook_url }}" class="text-decoration-none" style="color:#1877F2;font-size:0.9rem;" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>@endif
                        @if($c->twitter_url)<a href="{{ $c->twitter_url }}" class="text-decoration-none" style="color:#000;font-size:0.9rem;" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>@endif
                        @if($c->linkedin_url)<a href="{{ $c->linkedin_url }}" class="text-decoration-none" style="color:#0A66C2;font-size:0.85rem;" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>@endif
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

<!-- PARTNERS & SUPPORT -->
<section class="py-5" style="background:var(--nec-gray-50);">
    <div class="container">
        <div class="text-center mb-4">
            <span class="badge bg-dark fw-bold px-3 py-2 rounded-0 mb-3" style="letter-spacing:2px;font-size:0.7rem;">OUR PARTNERS</span>
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
