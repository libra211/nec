@extends('layouts.app', ['title' => 'Help Center', 'active_page' => 'help'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Help Center</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Help Center</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-headset text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4 d-flex flex-column align-items-center">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(46, 139, 87, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-check fs-1" style="color: var(--nec-green);"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Voter Help</h5>
                        <p class="text-muted small mb-3">Get assistance with voter registration, polling stations, and the voting process.</p>
                        <div class="mt-auto w-100">
                            <div class="list-group list-group-flush text-start">
                                <a href="{{ url('voter/register') }}" class="list-group-item list-group-item-action px-0 border-bottom small">How to Register</a>
                                <a href="{{ url('voter/verify') }}" class="list-group-item list-group-item-action px-0 border-bottom small">Verify Your Registration</a>
                                <a href="{{ url('voter/polling-finder') }}" class="list-group-item list-group-item-action px-0 border-bottom small">Find Your Polling Station</a>
                                <a href="{{ url('faq') }}" class="list-group-item list-group-item-action px-0 small">Voter FAQ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4 d-flex flex-column align-items-center">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-laptop-code fs-1" style="color: var(--nec-gold);"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Technical Support</h5>
                        <p class="text-muted small mb-3">Having issues with the website, online forms, or your account? We can help.</p>
                        <div class="mt-auto w-100">
                            <div class="list-group list-group-flush text-start">
                                <a href="{{ url('login') }}" class="list-group-item list-group-item-action px-0 border-bottom small">Login Issues</a>
                                <a href="#" class="list-group-item list-group-item-action px-0 border-bottom small">Browser Compatibility</a>
                                <a href="#" class="list-group-item list-group-item-action px-0 border-bottom small">Form Submission Errors</a>
                                <a href="#" class="list-group-item list-group-item-action px-0 small">Account Recovery</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4 d-flex flex-column align-items-center">
                        <div class="mb-3" style="width: 80px; height: 80px; background: rgba(0, 0, 0, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-comments fs-1" style="color: var(--nec-black);"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">General Inquiries</h5>
                        <p class="text-muted small mb-3">Questions about NEC, elections, media, or other general information.</p>
                        <div class="mt-auto w-100">
                            <div class="list-group list-group-flush text-start">
                                <a href="{{ url('about/mandate') }}" class="list-group-item list-group-item-action px-0 border-bottom small">About NEC</a>
                                <a href="{{ url('media/news') }}" class="list-group-item list-group-item-action px-0 border-bottom small">News & Updates</a>
                                <a href="{{ url('contact') }}" class="list-group-item list-group-item-action px-0 border-bottom small">Contact Us</a>
                                <a href="{{ url('downloads') }}" class="list-group-item list-group-item-action px-0 small">Downloads</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3" style="color: var(--nec-black);">Contact Support</h4>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <i class="fas fa-phone fs-3 mb-2" style="color: var(--nec-green);"></i>
                                    <h6 class="fw-bold small mb-1">Phone</h6>
                                    <small class="text-muted">+211 (0) 912 345 678</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <i class="fas fa-envelope fs-3 mb-2" style="color: var(--nec-green);"></i>
                                    <h6 class="fw-bold small mb-1">Email</h6>
                                    <small class="text-muted">support@nec.gov.ss</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <i class="fas fa-map-marker-alt fs-3 mb-2" style="color: var(--nec-green);"></i>
                                    <h6 class="fw-bold small mb-1">Visit Us</h6>
                                    <small class="text-muted">Juba, South Sudan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3">
                                    <i class="fas fa-clock fs-3 mb-2" style="color: var(--nec-green);"></i>
                                    <h6 class="fw-bold small mb-1">Hours</h6>
                                    <small class="text-muted">Mon–Fri, 8:00–17:00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
