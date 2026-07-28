@extends('layouts.app', ['title' => 'Voter Services - NEC South Sudan', 'active_page' => 'voter'])

@section('hero')
<section class="page-header-section" style="background:linear-gradient(135deg,var(--nec-green) 0%,#0d3b1e 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Voter Services</h1>
                <p class="text-white-50 mb-3">Everything you need to register, verify, and participate in elections across South Sudan.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Voter Services</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-users text-white-50" style="font-size:3.5rem;opacity:0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <a href="{{ route('voter.register') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-user-plus"></i></div>
                    <h5>Voter Registration</h5>
                    <p class="text-muted small">Register to vote for upcoming elections in your constituency</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('voter.verify') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-check-double"></i></div>
                    <h5>Verify Registration</h5>
                    <p class="text-muted small">Confirm your voter registration status and details</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('voter.polling-finder') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h5>Find Polling Station</h5>
                    <p class="text-muted small">Locate your designated polling station for election day</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('voter.forgot-id') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-id-card"></i></div>
                    <h5>Forgot Voter ID</h5>
                    <p class="text-muted small">Recover your voter ID number if you have lost or forgotten it</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('voter.transfer') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-exchange-alt"></i></div>
                    <h5>Voter Transfer</h5>
                    <p class="text-muted small">Request a transfer of your voter registration to a new constituency</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <a href="{{ route('voter.status') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-clipboard-check"></i></div>
                    <h5>Check Registration Status</h5>
                    <p class="text-muted small">Look up your registration status using your Voter ID or phone number</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <a href="{{ route('voter.education') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h5>Voter Education</h5>
                    <p class="text-muted small">Learn about your rights, the electoral process, and civic duties</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <a href="{{ route('voter.how-to-vote') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-vote-yea"></i></div>
                    <h5>How to Vote</h5>
                    <p class="text-muted small">Step-by-step guide to casting your ballot on election day</p>
                </a>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="700">
                <a href="{{ route('voter.report-issue') }}" class="service-card text-decoration-none">
                    <div class="service-icon"><i class="fas fa-flag"></i></div>
                    <h5>Report Issue</h5>
                    <p class="text-muted small">Report an electoral issue, complaint, or irregularity to NEC</p>
                </a>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-2" style="color:var(--nec-green);">Need Help?</h4>
                        <p class="text-muted mb-3">Contact the NEC Voter Services Desk for assistance with registration, transfers, or any other enquiry.</p>
                        <p class="mb-1"><i class="fas fa-envelope me-2 text-success"></i> voters@nec.gov.ss</p>
                        <p class="mb-0"><i class="fas fa-phone me-2 text-success"></i> +211 (0) 912 345 678</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
