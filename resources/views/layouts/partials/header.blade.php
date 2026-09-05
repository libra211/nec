@php
    $ticker_parties = 29; $ticker_constituencies = 102; $ticker_stations = 3284; $ticker_voters = 12000000;
    try {
        $autoVoters = \App\Models\Voter::count();
        $ticker_voters = \App\Helpers\NecHelper::setting_get('public_stat_total_voters_source', 'auto') === 'manual'
            ? (\App\Helpers\NecHelper::setting_get('public_stat_total_voters_value', '') ?: max($autoVoters, 12000000))
            : max($autoVoters, 12000000);
        $autoConst = \App\Models\Constituency::where('status', 'active')->count();
        $ticker_constituencies = \App\Helpers\NecHelper::setting_get('public_stat_constituencies_source', 'auto') === 'manual'
            ? (\App\Helpers\NecHelper::setting_get('public_stat_constituencies_value', '') ?: ($autoConst ?: $ticker_constituencies))
            : ($autoConst ?: $ticker_constituencies);
        $autoParties = \App\Models\PoliticalParty::where('status', 1)->count();
        $ticker_parties = \App\Helpers\NecHelper::setting_get('public_stat_parties_source', 'auto') === 'manual'
            ? (\App\Helpers\NecHelper::setting_get('public_stat_parties_value', '') ?: ($autoParties ?: $ticker_parties))
            : ($autoParties ?: $ticker_parties);
        $autoStations = \App\Models\PollingStation::where('status', 'active')->count();
        $ticker_stations = \App\Helpers\NecHelper::setting_get('public_stat_polling_stations_source', 'auto') === 'manual'
            ? (\App\Helpers\NecHelper::setting_get('public_stat_polling_stations_value', '') ?: ($autoStations ?: $ticker_stations))
            : ($autoStations ?: $ticker_stations);
    } catch (\Exception $e) {}
if (!function_exists('th')) { function th($n) { if ($n >= 1000000) return round($n/1000000,1).'M'; if ($n >= 1000) return round($n/1000,1).'K'; return number_format($n); } }
@endphp

<!-- PRELOADER -->
<div class="preloader" id="necPreloader">
    <div class="preloader-inner">
        <div class="preloader-flag">
            <div class="preloader-stripe preloader-stripe-black"></div>
            <div class="preloader-stripe preloader-stripe-red"></div>
            <div class="preloader-stripe preloader-stripe-green"></div>
        </div>
        <div class="preloader-text">SSNEC</div>
        <div class="preloader-sub">Loading...</div>
        <div class="preloader-bar">
            <div class="preloader-bar-fill"></div>
        </div>
    </div>
</div>

<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar" id="announcementBar">
    <div class="container">
        <div class="announcement-content">
            <span class="announcement-icon"><i class="fas fa-bullhorn"></i></span>
            <span class="announcement-text"><strong>Welcome to SSNEC</strong> — Ensuring free, fair, and credible elections for the people of South Sudan.</span>
            <button class="announcement-close" onclick="dismissAnnouncement()"><i class="fas fa-times"></i></button>
        </div>
    </div>
</div>
<script>if (localStorage.getItem('ssnec_announcement_dismissed')) document.getElementById('announcementBar').style.display='none'; function dismissAnnouncement(){ localStorage.setItem('ssnec_announcement_dismissed','1'); document.getElementById('announcementBar').style.display='none'; }</script>

<!-- TOP BAR -->
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
                        <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link" aria-label="Twitter / X"><i class="fab fa-x-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FLAG BAR -->
<div class="top-flag-bar">
    <div class="stripe stripe-black"></div>
    <div class="stripe stripe-red"></div>
    <div class="stripe stripe-green"></div>
    <div class="stripe stripe-blue"></div>
    <div class="stripe stripe-gold"></div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-xl nec-navbar sticky-top" id="necNavbar">
    <div class="container">
        <a class="navbar-brand py-2" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" style="width:100px;height:80px;border-radius:3px;">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#necMainNav" aria-controls="necMainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="necMainNav">
            <ul class="navbar-nav me-auto align-items-xl-center gap-xl-1 mb-2 mb-xl-0">
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'home' ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'about' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">About NEC</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('about.mandate') }}">Our Mandate</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.leadership') }}">Leadership</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.commissioners') }}">Commissioners</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.state-committees') }}">State Committees</a></li>
                        <li><a class="dropdown-item" href="{{ route('gis.map') }}">GIS Map</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.departments') }}">Departments</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.history') }}">History</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.legal-framework') }}">Legal Framework</a></li>
                        <li><a class="dropdown-item" href="{{ route('about.boundary-commission') }}">Boundary Commission</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'elections' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Elections</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('elections.calendar') }}">Election Calendar</a></li>
                        <li><a class="dropdown-item" href="{{ route('elections.types') }}">Types of Elections</a></li>
                        <li><a class="dropdown-item" href="{{ route('elections.results') }}">Election Results</a></li>
                        <li><a class="dropdown-item" href="{{ route('electoral-system') }}">Electoral System</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'voters' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Voters</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('voter.register') }}">Register to Vote</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.status') }}">Check Registration Status</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.verify') }}">Verify Registration</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.polling-finder') }}">Find Polling Station</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.transfer') }}">Transfer Request</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.inquiry') }}">Voter Inquiry</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.forgot-id') }}">Forgot Voter ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.report-issue') }}">Report Issue / Complaint</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('voter.education') }}"><i class="fas fa-book-open me-2"></i>Voter Education</a></li>
                        <li><a class="dropdown-item" href="{{ route('voter.how-to-vote') }}"><i class="fas fa-vote-yea me-2"></i>How to Vote</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'constituencies' ? 'active' : '' }}" href="{{ route('constituencies.index') }}">Constituencies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'parties' ? 'active' : '' }}" href="{{ route('parties.index') }}">Parties</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'candidates' ? 'active' : '' }}" href="{{ route('candidates.index') }}">Candidates</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'media' ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Media</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('media.news') }}">News</a></li>
                        <li><a class="dropdown-item" href="{{ route('media.gallery') }}">Gallery</a></li>
                        <li><a class="dropdown-item" href="{{ route('media.videos') }}">Videos</a></li>
                        <li><a class="dropdown-item" href="{{ route('media.speeches') }}">Speeches</a></li>
                        <li><a class="dropdown-item" href="{{ route('media.press-releases') }}">Press Releases</a></li>
                        <li><a class="dropdown-item" href="{{ route('media.publications') }}">Publications</a></li>
                        <li><a class="dropdown-item" href="{{ route('downloads.index') }}">Downloads</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'observers' ? 'active' : '' }}" href="{{ route('observers.index') }}">Observers</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold text-uppercase px-3 {{ ($active_page ?? '') === 'contact' ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
            </ul>

            <ul class="navbar-nav align-items-xl-center">
                <li class="nav-item">
                    <button class="nav-btn-search" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Search">
                        <i class="fas fa-search"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.login') }}" class="nav-btn-login">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- DATA TICKER -->
<div class="data-ticker">
    <div class="container">
        <div class="d-flex align-items-center">
            <span class="badge bg-danger me-2 flex-shrink-0">LIVE</span>
            <div class="overflow-hidden flex-grow-1" style="white-space:nowrap;">
                <div class="ticker-scroll">
                    <span class="mx-3"><i class="fa-solid fa-check-circle text-success"></i> <strong>{{ date('Y') }} Elections:</strong> Voter registration ongoing — <a href="{{ route('voter.register') }}" class="text-warning text-decoration-none">Register Now</a></span>
                    @if(\App\Helpers\NecHelper::setting_get('public_show_total_voters', '1') === '1')
                    <span class="mx-3"><i class="fa-solid fa-users text-info"></i> <strong>{{ th($ticker_voters) }}</strong> registered voters across <strong>{{ number_format($ticker_constituencies) }}</strong> constituencies</span>
                    @endif
                    @if(\App\Helpers\NecHelper::setting_get('public_show_parties', '1') === '1')
                    <span class="mx-3"><i class="fa-solid fa-flag text-primary"></i> <strong>{{ $ticker_parties }}</strong> political parties registered with SSNEC</span>
                    @endif
                    @if(\App\Helpers\NecHelper::setting_get('public_show_polling_stations', '1') === '1')
                    <span class="mx-3"><i class="fa-solid fa-building text-success"></i> <strong>{{ number_format($ticker_stations) }}</strong> polling stations nationwide</span>
                    @endif
                    @if(\App\Helpers\NecHelper::setting_get('public_show_election_date', '1') === '1')
                    @php $tickerDate = \App\Helpers\NecHelper::setting_get('public_stat_election_date_source', 'auto') === 'manual' ? \App\Helpers\NecHelper::setting_get('public_stat_election_date_value', '22 December 2026') : \App\Helpers\NecHelper::setting_get('election_date', '22 December 2026'); @endphp
                    <span class="mx-3"><i class="fa-solid fa-calendar text-warning"></i> Next election: <strong>{{ $tickerDate }}</strong> — <a href="{{ route('elections.calendar') }}" class="text-warning text-decoration-none">View Calendar</a></span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
