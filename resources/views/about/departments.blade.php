@extends('layouts.app', ['title' => 'Departments', 'active_page' => 'about', 'meta_description' => 'Departments of the National Elections Commission of South Sudan Secretariat.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Departments</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Departments</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-building text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color: var(--nec-black);">NEC Secretariat Departments</h2>
            <p class="text-muted">The professional secretariat of the NEC is organized into specialized departments that support the Commission in fulfilling its mandate.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, var(--nec-green), #1e6b3e); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-money-check-alt text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Finance & Administration</h5>
                        </div>
                        <p class="text-muted small mb-0">Responsible for budgeting, financial management, procurement, and administrative support services for the Commission.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #1a3c8f, #2E8B57); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-laptop-code text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Information Technology</h5>
                        </div>
                        <p class="text-muted small mb-0">Manages the Commission IT infrastructure, voter registration system, results management system, and data security.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #2E8B57, #1a3c8f); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-people-arrows text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Field Operations</h5>
                        </div>
                        <p class="text-muted small mb-0">Coordinates electoral operations across all states, including logistics, polling station setup, and field staff management.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #8B0000, #2E8B57); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-gavel text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Legal Affairs</h5>
                        </div>
                        <p class="text-muted small mb-0">Provides legal advice to the Commission, handles electoral disputes, and ensures compliance with electoral laws and regulations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #D4AF37, #8B6914); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bullhorn text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Communication & Public Relations</h5>
                        </div>
                        <p class="text-muted small mb-0">Manages media relations, public information campaigns, voter education, and stakeholder communication.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #2E8B57, #8B0000); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clipboard-list text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Planning & Research</h5>
                        </div>
                        <p class="text-muted small mb-0">Undertakes electoral research, strategic planning, delimitation studies, and performance monitoring and evaluation.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #1a3c8f, #8B0000); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hand-holding-heart text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Civic & Voter Education</h5>
                        </div>
                        <p class="text-muted small mb-0">Develops and implements civic and voter education programs to inform citizens about their electoral rights and responsibilities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="350">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #8B6914, #1a3c8f); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-check text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Voter Registration</h5>
                        </div>
                        <p class="text-muted small mb-0">Manages the continuous voter registration process, maintains the national voters register, and issues voter ID cards.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #2E8B57, #D4AF37); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-handshake text-white fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">External Relations</h5>
                        </div>
                        <p class="text-muted small mb-0">Liaises with international partners, donor organizations, observer missions, and other election management bodies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
