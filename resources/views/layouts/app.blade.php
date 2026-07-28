<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'NEC South Sudan - National Elections Commission' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'National Elections Commission of South Sudan - Ensuring free, fair, and credible elections for the people of South Sudan.' }}">
    <meta name="keywords" content="NEC, South Sudan, elections, voting, voters registration, political parties">
    <meta name="author" content="National Elections Commission - South Sudan">
    <meta property="og:title" content="{{ $title ?? 'NEC South Sudan' }}">
    <meta property="og:description" content="National Elections Commission of South Sudan">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Leaflet CSS -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <!-- NEC Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animations.css') }}" rel="stylesheet">

    @yield('extra_head')
    @stack('styles')

    <!-- Anti-inspection: disable right-click, F12, Ctrl+U, Ctrl+Shift+I, etc. -->
    <script>
    document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && ['I','C','J'].includes(e.key.toUpperCase())) || (e.ctrlKey && ['U','S'].includes(e.key.toUpperCase()))) {
            e.preventDefault(); e.stopPropagation();
        }
    });
    </script>
</head>

<body>

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
    @php
        $ticker_parties = 29; $ticker_constituencies = 102; $ticker_stations = 3284; $ticker_voters = 12000000;
        try {
            $ticker_voters = max(\App\Models\Voter::count(), 12000000);
            $ticker_constituencies = \App\Models\Constituency::where('status', 'active')->count() ?: $ticker_constituencies;
            $ticker_parties = \App\Models\PoliticalParty::where('status', 'active')->count() ?: $ticker_parties;
            $ticker_stations = \App\Models\PollingStation::where('status', 'active')->count() ?: $ticker_stations;
        } catch (\Exception $e) {}
        function th($n) { if ($n >= 1000000) return round($n/1000000,1).'M'; if ($n >= 1000) return round($n/1000,1).'K'; return number_format($n); }
    @endphp
    <div class="data-ticker">
        <div class="container">
            <div class="d-flex align-items-center">
                <span class="badge bg-danger me-2 flex-shrink-0">LIVE</span>
                <div class="overflow-hidden flex-grow-1" style="white-space:nowrap;">
                    <div class="ticker-scroll">
                        <span class="mx-3"><i class="fa-solid fa-check-circle text-success"></i> <strong>{{ date('Y') }} Elections:</strong> Voter registration ongoing — <a href="{{ route('voter.register') }}" class="text-warning text-decoration-none">Register Now</a></span>
                        <span class="mx-3"><i class="fa-solid fa-users text-info"></i> <strong>{{ th($ticker_voters) }}</strong> registered voters across <strong>{{ number_format($ticker_constituencies) }}</strong> constituencies</span>
                        <span class="mx-3"><i class="fa-solid fa-flag text-primary"></i> <strong>{{ $ticker_parties }}</strong> political parties registered with SSNEC</span>
                        <span class="mx-3"><i class="fa-solid fa-building text-success"></i> <strong>{{ number_format($ticker_stations) }}</strong> polling stations nationwide</span>
                        <span class="mx-3"><i class="fa-solid fa-calendar text-warning"></i> Next election: <strong>22 December 2026</strong> — <a href="{{ route('elections.calendar') }}" class="text-warning text-decoration-none">View Calendar</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HERO SECTION -->
    @yield('hero')

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="nec-footer">
        <div class="footer-newsletter">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h4 class="newsletter-title">Subscribe to Our Newsletter</h4>
                        <p class="newsletter-text">Stay informed with the latest NEC updates, election schedules, and voter information.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form" id="newsletterForm" method="POST" action="{{ route('newsletter.subscribe') }}">
                            @csrf
                            <input type="hidden" name="newsletter_subscribe" value="1">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Your name" required style="border-radius:50px 0 0 50px;">
                                <input type="email" name="email" class="form-control" placeholder="Your email" required>
                                <button class="btn btn-nec-danger fw-bold" type="submit" style="border-radius:0 50px 50px 0;padding:0 20px;font-size:0.82rem;">SUBSCRIBE</button>
                            </div>
                            <div class="newsletter-msg mt-2" style="font-size:0.82rem;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-main">
            <div class="container">

                <div class="row g-4 align-items-stretch">
                    <div class="col-md-6 col-lg-4 d-flex">
                        <div class="footer-widget w-100">
                            <div class="footer-brand mb-3" style="display:flex;align-items:center;gap:14px;">
                                <img src="{{ asset('assets/images/logos/neclogo.jpeg') }}" alt="NEC" width="80" height="80" style="object-fit:contain;max-width:100%;border-radius:5px;">
                                <div>
                                    <span style="display:block;font-size:1.5rem;font-weight:900;color:var(--nec-gold);line-height:1;">NEC</span>
                                    <span style="display:block;width:24px;height:2px;background:var(--nec-gold);margin:5px 0;border-radius:1px;"></span>
                                    <span style="font-size:0.65rem;text-transform:uppercase;letter-spacing:3px;color:rgba(255,255,255,.45);">Republic of South Sudan</span>
                                </div>
                            </div>
                            <p class="text-white-50 mb-3" style="line-height:1.8;font-size:0.88rem;text-align:justify;">The National Elections Commission (NEC) is the independent constitutional body responsible for organizing and supervising elections in South Sudan.</p>
                            <h5 style="color:var(--nec-gold);font-size:0.85rem;font-weight:600;margin-bottom:10px;text-transform:uppercase;letter-spacing:1px;">Contact Us</h5>
                            <div class="footer-contact mb-3">
                                <p class="mb-1"><i class="fas fa-map-marker-alt me-2" style="color:var(--nec-gold);"></i>Juba, South Sudan</p>
                                <p class="mb-1"><i class="fas fa-phone me-2" style="color:var(--nec-gold);"></i>+211 (0) 912 345 678</p>
                                <p class="mb-1"><i class="fas fa-envelope me-2" style="color:var(--nec-gold);"></i>info@nec.gov.ss</p>
                            </div>
                            <div class="mb-3">
                                <p class="mb-1"><i class="fas fa-clock me-2" style="color:var(--nec-gold);"></i>Mon – Fri: 8:00 AM – 5:00 PM</p>
                                <p class="mb-1" style="padding-left:28px;color:rgba(255,255,255,.5);font-size:0.85rem;">Sat – Sun: Closed</p>
                            </div>
                            <div class="footer-social">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-x-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Quick Links</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('about.index') }}">About NEC</a></li>
                                <li><a href="{{ route('about.mandate') }}">Our Mandate</a></li>
                                <li><a href="{{ route('about.commissioners') }}">Commissioners</a></li>
                                <li><a href="{{ route('about.departments') }}">The Secretariat</a></li>
                                <li><a href="{{ route('about.mandate') }}">Vision &amp; Mission</a></li>
                                <li><a href="{{ route('elections.calendar') }}">Election Calendar</a></li>
                                <li><a href="{{ route('elections.results') }}">Election Results</a></li>
                                <li><a href="{{ route('parties.index') }}">Political Parties</a></li>
                                <li><a href="{{ route('candidates.index') }}">Candidates</a></li>
                                <li><a href="{{ route('downloads.index') }}">Downloads</a></li>
                                <li><a href="{{ route('observers.index') }}">Observer Accreditation</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">For Voters</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('voter.register') }}">Voter Registration</a></li>
                                <li><a href="{{ route('voter.verify') }}">Verify Registration</a></li>
                                <li><a href="{{ route('voter.polling-finder') }}">Find Polling Station</a></li>
                                <li><a href="{{ route('voter.transfer') }}">Transfer Request</a></li>
                                <li><a href="{{ route('voter.inquiry') }}">Voter Inquiry</a></li>
                                <li><a href="{{ route('constituencies.index') }}">Find Constituency</a></li>
                                <li><a href="{{ route('voter.education') }}">Voter Education</a></li>
                                <li><a href="{{ route('voter.how-to-vote') }}">How to Vote</a></li>
                                <li><a href="{{ route('downloads.forms') }}">Download Forms</a></li>
                                <li><a href="{{ route('voter.report-issue') }}">Report Issue / Complaint</a></li>
                                <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Resources</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('media.news') }}">News &amp; Updates</a></li>
                                <li><a href="{{ route('media.gallery') }}">Photo Gallery</a></li>
                                <li><a href="{{ route('media.videos') }}">Videos</a></li>
                                <li><a href="{{ route('media.publications') }}">Publications</a></li>
                                <li><a href="{{ route('media.press-releases') }}">Press Releases</a></li>
                                <li><a href="{{ route('media.speeches') }}">Speeches</a></li>
                                <li><a href="{{ route('reports.annual') }}">Annual Reports</a></li>
                                <li><a href="{{ route('elections.types') }}">Election Types</a></li>
                                <li><a href="{{ route('downloads.forms') }}">Forms &amp; Documents</a></li>
                                <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg d-flex">
                        <div class="footer-widget w-100">
                            <h5 class="fw-bold mb-3 splitter" style="color:#fff;font-size:0.92rem;">Support</h5>
                            <ul class="footer-links list-unstyled mb-0">
                                <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                                <li><a href="{{ route('contact.index') }}">Send a Message</a></li>
                                <li><a href="{{ route('contact.index') }}">Office Locations</a></li>
                                <li><a href="{{ route('faq.index') }}">FAQ</a></li>
                                <li><a href="{{ route('help.index') }}">Help Center</a></li>
                                <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                                <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('legal.terms-of-use') }}">Terms of Use</a></li>
                                <li><a href="{{ route('legal.accessibility') }}">Accessibility</a></li>
                                <li><a href="{{ route('careers.index') }}">Careers</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright mb-0">&copy; {{ date('Y') }} National Elections Commission – South Sudan. All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="footer-bottom-links mb-0">
                            <li><a href="{{ route('legal.privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('legal.terms-of-use') }}">Terms of Service</a></li>
                            <li><a href="{{ route('legal.accessibility') }}">Accessibility</a></li>
                            <li><a href="{{ route('sitemap.index') }}">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- SEARCH MODAL -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-search text-muted" style="font-size: 2.5rem;"></i>
                        <h4 class="mt-2 fw-bold">Search NEC South Sudan</h4>
                        <p class="text-muted">Enter your search term to find information across the website.</p>
                    </div>
                    <form action="{{ route('search.results') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control border-end-0" placeholder="Search for candidates, elections, forms..." required>
                            <button class="btn btn-success" type="submit" style="background: var(--nec-green); border-color: var(--nec-green);">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- WHATSAPP FLOAT -->
    <div class="whatsapp-float">
        <a href="https://wa.me/211912345678" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- SCROLL TO TOP -->
    <button class="scroll-to-top" id="scrollToTop" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    @yield('extra_scripts')
    @stack('scripts')
</body>
</html>
