@extends('layouts.app', ['title' => 'Sitemap', 'active_page' => 'sitemap'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Sitemap</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Sitemap</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-sitemap text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-home me-2"></i>Home</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none">Home Page</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-info-circle me-2"></i>About NEC</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('about') }}" class="text-decoration-none">About Overview</a></li>
                            <li class="mb-2"><a href="{{ url('about/mandate') }}" class="text-decoration-none">Our Mandate</a></li>
                            <li class="mb-2"><a href="{{ url('about/leadership') }}" class="text-decoration-none">Leadership</a></li>
                            <li class="mb-2"><a href="{{ url('about/commissioners') }}" class="text-decoration-none">Commissioners</a></li>
                            <li class="mb-2"><a href="{{ url('about/state-committees') }}" class="text-decoration-none">State Committees</a></li>
                            <li class="mb-2"><a href="{{ url('about/departments') }}" class="text-decoration-none">Departments</a></li>
                            <li class="mb-2"><a href="{{ url('about/history') }}" class="text-decoration-none">History</a></li>
                            <li class="mb-2"><a href="{{ url('about/legal-framework') }}" class="text-decoration-none">Legal Framework</a></li>
                            <li class="mb-2"><a href="{{ url('about/boundary-commission') }}" class="text-decoration-none">Boundary Commission</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-vote-yea me-2"></i>Elections</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('elections/calendar') }}" class="text-decoration-none">Election Calendar</a></li>
                            <li class="mb-2"><a href="{{ url('elections/types') }}" class="text-decoration-none">Types of Elections</a></li>
                            <li class="mb-2"><a href="{{ url('elections/results') }}" class="text-decoration-none">Election Results</a></li>
                            <li class="mb-2"><a href="{{ url('elections/electoral-system') }}" class="text-decoration-none">Electoral System</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-users me-2"></i>Voters</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('voter/register') }}" class="text-decoration-none">Register to Vote</a></li>
                            <li class="mb-2"><a href="{{ url('voter/verify') }}" class="text-decoration-none">Verify Registration</a></li>
                            <li class="mb-2"><a href="{{ url('voter/polling-finder') }}" class="text-decoration-none">Find Polling Station</a></li>
                            <li class="mb-2"><a href="{{ url('voter/inquiry') }}" class="text-decoration-none">Voter Inquiry</a></li>
                            <li class="mb-2"><a href="{{ url('faq') }}" class="text-decoration-none">FAQ</a></li>
                            <li class="mb-2"><a href="{{ url('voter/education') }}" class="text-decoration-none">Civic Education</a></li>
                            <li class="mb-2"><a href="{{ url('voter/education') }}" class="text-decoration-none">Voter Education</a></li>
                            <li class="mb-2"><a href="{{ url('voter/how-to-vote') }}" class="text-decoration-none">How to Vote</a></li>
                            <li class="mb-2"><a href="{{ url('voter/report-issue') }}" class="text-decoration-none">Report an Issue</a></li>
                            <li class="mb-2"><a href="{{ url('voter/status') }}" class="text-decoration-none">Check Registration Status</a></li>
                            <li class="mb-2"><a href="{{ url('voter/transfer') }}" class="text-decoration-none">Transfer Request</a></li>
                            <li class="mb-2"><a href="{{ url('voter/forgot-id') }}" class="text-decoration-none">Forgot Voter ID</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-flag me-2"></i>Parties & Candidates</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('parties') }}" class="text-decoration-none">Political Parties</a></li>
                            <li class="mb-2"><a href="{{ url('candidates') }}" class="text-decoration-none">Candidates</a></li>
                            <li class="mb-2"><a href="{{ url('constituencies') }}" class="text-decoration-none">Constituencies</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-newspaper me-2"></i>Media</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('media/news') }}" class="text-decoration-none">News</a></li>
                            <li class="mb-2"><a href="{{ url('media/gallery') }}" class="text-decoration-none">Gallery</a></li>
                            <li class="mb-2"><a href="{{ url('media/videos') }}" class="text-decoration-none">Videos</a></li>
                            <li class="mb-2"><a href="{{ url('media/publications') }}" class="text-decoration-none">Publications</a></li>
                            <li class="mb-2"><a href="{{ url('media/press-releases') }}" class="text-decoration-none">Press Releases</a></li>
                            <li class="mb-2"><a href="{{ url('media/speeches') }}" class="text-decoration-none">Speeches</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-download me-2"></i>Downloads & Reports</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('downloads') }}" class="text-decoration-none">Downloads</a></li>
                            <li class="mb-2"><a href="{{ url('downloads/forms') }}" class="text-decoration-none">Forms & Documents</a></li>
                            <li class="mb-2"><a href="{{ url('reports/annual') }}" class="text-decoration-none">Annual Reports</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-binoculars me-2"></i>Observers</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('observers/accredit') }}" class="text-decoration-none">Observer Accreditation</a></li>
                            <li class="mb-2"><a href="{{ url('observers/') }}" class="text-decoration-none">Observer Portal</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-map-marked-alt me-2"></i>GIS & Reports</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('gis/map') }}" class="text-decoration-none">GIS Electoral Map</a></li>
                            <li class="mb-2"><a href="{{ url('reports/annual') }}" class="text-decoration-none">Annual Reports</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-concierge-bell me-2"></i>Support</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('contact') }}" class="text-decoration-none">Contact Us</a></li>
                            <li class="mb-2"><a href="{{ url('help') }}" class="text-decoration-none">Help Center</a></li>
                            <li class="mb-2"><a href="{{ url('faq') }}" class="text-decoration-none">FAQ</a></li>
                            <li class="mb-2"><a href="{{ url('search') }}" class="text-decoration-none">Search</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-user-lock me-2"></i>Account</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('login') }}" class="text-decoration-none">Login</a></li>
                            <li class="mb-2"><a href="{{ url('admin/login') }}" class="text-decoration-none">Admin Login</a></li>
                            <li class="mb-2"><a href="{{ url('admin') }}" class="text-decoration-none">Admin Dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-green);"><i class="fas fa-file-alt me-2"></i>Policies & Legal</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ url('legal/privacy-policy') }}" class="text-decoration-none">Privacy Policy</a></li>
                            <li class="mb-2"><a href="{{ url('legal/terms-of-use') }}" class="text-decoration-none">Terms of Use</a></li>
                            <li class="mb-2"><a href="{{ url('legal/accessibility') }}" class="text-decoration-none">Accessibility</a></li>
                            <li class="mb-2"><a href="{{ url('careers') }}" class="text-decoration-none">Careers</a></li>
                            <li class="mb-2"><a href="{{ url('sitemap') }}" class="text-decoration-none">Sitemap</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
