@extends('layouts.app', ['title' => 'Our Mandate', 'active_page' => 'about', 'meta_description' => 'The constitutional mandate and legal framework of the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Our Mandate</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Our Mandate</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-gavel text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Legal Documents</h5>
                            <span class="badge bg-success total-downloads-badge" style="font-size:0.7rem;background:var(--nec-green)!important;">
                                <i class="fas fa-download me-1"></i><span class="total-dl-count">0</span>
                            </span>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="mandate-const-2011" data-label="Transitional Constitution 2011">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">Transitional Constitution 2011</small><small class="text-muted">PDF, 2.4 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="mandate-elections-act-2023" data-label="National Elections Act 2023">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">National Elections Act 2023</small><small class="text-muted">PDF, 1.8 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3" data-track-download="mandate-parties-act-2012" data-label="Political Parties Act 2012">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">Political Parties Act 2012</small><small class="text-muted">PDF, 1.1 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Related Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('about.legal-framework') }}" class="list-group-item list-group-item-action px-0 border-bottom">Legal Framework</a>
                            <a href="{{ route('about.commissioners') }}" class="list-group-item list-group-item-action px-0 border-bottom">Commissioners</a>
                            <a href="{{ route('about.history') }}" class="list-group-item list-group-item-action px-0 border-bottom">History</a>
                            <a href="{{ route('about.state-committees') }}" class="list-group-item list-group-item-action px-0">State Committees</a>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);"><i class="fas fa-phone-alt me-2" style="color:var(--nec-gold);"></i>Contact NEC</h5>
                        <div class="small text-muted">
                            <span class="d-block mb-2"><i class="fas fa-map-marker-alt me-2" style="color:var(--nec-green);width:16px;"></i>NEC Headquarters (formerly Aida Hotel), Plot no. 563, Bilpam Road, Thongpiny, Juba</span>
                            <span class="d-block mb-2"><i class="fas fa-phone me-2" style="color:var(--nec-green);width:16px;"></i>+211 (0) 912 345 678</span>
                            <span class="d-block mb-2"><i class="fas fa-envelope me-2" style="color:var(--nec-green);width:16px;"></i>info@nec.gov.ss</span>
                            <span class="d-block"><i class="fas fa-clock me-2" style="color:var(--nec-green);width:16px;"></i>Mon–Fri 8AM–5PM</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4" style="color: var(--nec-black);">Constitutional Basis</h2>
                <p class="text-muted">The National Elections Commission (NEC) derives its mandate from the Transitional Constitution of the Republic of South Sudan, 2011 (as amended). Chapter Three, Articles 197 to 202 establish the Commission as an independent constitutional institution with the authority to organize, supervise, and conduct elections and referenda.</p>
                <p class="text-muted">The NEC is further guided by the National Elections Act, 2023, which provides the detailed legal framework for the conduct of elections, including voter registration, candidate nomination, campaigning, polling, counting, and the declaration of results.</p>

                <div class="p-4 rounded-3 my-4" style="background:#fff;border:1px solid #dee2e6;">
                    <h5 class="fw-bold mb-2" style="color: var(--nec-green);">Article 197 — Establishment</h5>
                    <p class="text-muted mb-0 fst-italic">"There shall be established an independent National Elections Commission composed of a Chairperson, Deputy Chairperson, and other members who shall be appointed by the President with the approval of the Transitional National Legislative Assembly."</p>
                </div>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Core Functions</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Organize Elections</h6>
                                <p class="small text-muted mb-0">Plan and conduct all national and state elections</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Registration</h6>
                                <p class="small text-muted mb-0">Compile and maintain the national voters register</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Candidate Nomination</h6>
                                <p class="small text-muted mb-0">Receive and vet nominations for all elective positions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Education</h6>
                                <p class="small text-muted mb-0">Educate citizens on the electoral process</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Result Declaration</h6>
                                <p class="small text-muted mb-0">Count, tally, and officially declare election results</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Observer Accreditation</h6>
                                <p class="small text-muted mb-0">Accredit domestic and international observers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Delimitation</h6>
                                <p class="small text-muted mb-0">Review and delimit constituencies periodically</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;">
                            <i class="fas fa-check-circle fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Referenda</h6>
                                <p class="small text-muted mb-0">Conduct national referenda as required by law</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Values & Principles</h3>
                <p class="text-muted">In the discharge of its mandate, the NEC is guided by the following constitutional values and principles:</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Independence</strong> — The Commission operates free from direction or control by any person or authority.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Impartiality</strong> — All electoral processes are conducted without fear, favour, or prejudice.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Transparency</strong> — Electoral processes are open to scrutiny by political parties, observers, and the public.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Accountability</strong> — The Commission is accountable to the people of South Sudan through the legislature.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Inclusivity</strong> — All eligible citizens have equal opportunity to participate in the electoral process.</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
