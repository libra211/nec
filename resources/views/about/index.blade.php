@extends('layouts.app', ['title' => 'About NEC', 'active_page' => 'about', 'meta_description' => 'Learn about the National Elections Commission of South Sudan - our mandate, leadership, history, and legal framework.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">About NEC</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">About NEC</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-landmark text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3" style="color: var(--nec-black);">Who We Are</h2>
                <p class="lead text-muted">The National Elections Commission (NEC) is the independent constitutional body responsible for organizing, supervising, and conducting all elections and referenda in the Republic of South Sudan.</p>
                <p class="text-muted">Established under the Transitional Constitution of the Republic of South Sudan, 2011 (as amended), the NEC operates with full independence and impartiality. The Commission is committed to ensuring that elections are free, fair, credible, and transparent, reflecting the true will of the South Sudanese people.</p>
                <p class="text-muted">The NEC is composed of a Chairperson, Deputy Chairperson, and Commissioners appointed by the President with the approval of the Transitional National Legislative Assembly. The Commission is supported by a professional secretariat comprising various departments responsible for the technical and administrative aspects of electoral operations.</p>

                <h3 class="fw-bold mt-4 mb-3" style="color: var(--nec-black);">Our Mission</h3>
                <p class="text-muted">To organize and conduct free, fair, and credible elections that reflect the will of the people of South Sudan, thereby contributing to the consolidation of democracy, peace, and stability in the country.</p>

                <h3 class="fw-bold mt-4 mb-3" style="color: var(--nec-black);">Our Vision</h3>
                <p class="text-muted">To be a model election management body in Africa, delivering excellence in electoral services and fostering a culture of democratic participation among all South Sudanese citizens.</p>

                <h3 class="fw-bold mt-4 mb-3" style="color: var(--nec-black);">Functions of the NEC</h3>
                <p class="text-muted">The National Elections Commission is mandated by the Transitional Constitution and the National Elections Act to perform the following functions:</p>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Organize and Conduct Elections</h6>
                                <p class="small text-muted mb-0">Organize, supervise, and conduct all elections and referenda in a free, fair, and credible manner.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Registration</h6>
                                <p class="small text-muted mb-0">Compile, maintain, and update a comprehensive National Voters Register through continuous voter registration.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Candidate and Party Regulation</h6>
                                <p class="small text-muted mb-0">Register and regulate political parties, receive and vet candidate nominations, and oversee campaign activities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Constituency Delimitation</h6>
                                <p class="small text-muted mb-0">Determine constituency boundaries and polling station locations in consultation with relevant authorities.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Education</h6>
                                <p class="small text-muted mb-0">Design and implement voter and civic education programmes to promote democratic participation.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Observer Accreditation</h6>
                                <p class="small text-muted mb-0">Accredit domestic and international election observers to ensure transparency and accountability.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Results Management</h6>
                                <p class="small text-muted mb-0">Collect, tally, verify, and declare election results in a transparent and timely manner.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-check-circle fs-5" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Electoral Dispute Resolution</h6>
                                <p class="small text-muted mb-0">Hear and determine election petitions and complaints in accordance with the law.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold mt-4 mb-3" style="color: var(--nec-black);">Core Values</h3>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-balance-scale fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Independence</h6>
                                <p class="small text-muted mb-0">Free from external interference in decision-making</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-eye fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Transparency</h6>
                                <p class="small text-muted mb-0">Open processes accessible to all stakeholders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-handshake fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Impartiality</h6>
                                <p class="small text-muted mb-0">Neutral and unbiased service to all citizens</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-users fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Inclusivity</h6>
                                <p class="small text-muted mb-0">Equal participation opportunities for all</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-shield-alt fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Integrity</h6>
                                <p class="small text-muted mb-0">Highest ethical standards in all operations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 bg-white rounded-3">
                            <i class="fas fa-star fs-3" style="color: var(--nec-gold);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Excellence</h6>
                                <p class="small text-muted mb-0">Continuous improvement in service delivery</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Quick Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('about.mandate') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-gavel" style="color: var(--nec-green);"></i>
                                <span>Our Mandate</span>
                            </a>
                            <a href="{{ route('about.leadership') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-user-tie" style="color: var(--nec-green);"></i>
                                <span>Leadership</span>
                            </a>
                            <a href="{{ route('about.commissioners') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-users" style="color: var(--nec-green);"></i>
                                <span>Commissioners</span>
                            </a>
                            <a href="{{ route('about.state-committees') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-map-marked-alt" style="color: var(--nec-green);"></i>
                                <span>State Committees</span>
                            </a>
                            <a id="departments"></a>
                            <a href="{{ route('about.departments') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-building" style="color: var(--nec-green);"></i>
                                <span>Departments</span>
                            </a>
                            <a href="{{ route('about.history') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0 border-bottom">
                                <i class="fas fa-history" style="color: var(--nec-green);"></i>
                                <span>History</span>
                            </a>
                            <a href="{{ route('about.legal-framework') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-0">
                                <i class="fas fa-book" style="color: var(--nec-green);"></i>
                                <span>Legal Framework</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h5 class="text-uppercase" style="color: var(--nec-gold); letter-spacing: 2px; font-weight: 600;">Explore</h5>
            <h2 class="fw-bold" style="color: var(--nec-black);">Learn More About NEC</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-gavel fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">Our Mandate</h5>
                        <p class="text-muted small">Discover the constitutional mandate and legal framework that empowers the NEC to conduct elections in South Sudan.</p>
                        <a href="{{ route('about.mandate') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-user-tie fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">Leadership</h5>
                        <p class="text-muted small">Meet the Chairperson, Deputy Chairperson, and Commissioners who guide the work of the Commission.</p>
                        <a href="{{ route('about.leadership') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">Meet the Team</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-map-marked-alt fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">State Committees</h5>
                        <p class="text-muted small">Learn about the State High Committees that coordinate electoral activities across all states.</p>
                        <a href="{{ route('about.state-committees') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">Explore</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-building fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">Departments</h5>
                        <p class="text-muted small">Explore the various departments that form the secretariat and support electoral operations.</p>
                        <a href="{{ route('about.departments') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">View Departments</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-history fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">History</h5>
                        <p class="text-muted small">Trace the journey of South Sudan's electoral process from independence to the present day.</p>
                        <a href="{{ route('about.history') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">Our History</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="mb-3"><i class="fas fa-book fs-1" style="color: var(--nec-green);"></i></div>
                        <h5 class="fw-bold">Legal Framework</h5>
                        <p class="text-muted small">Understand the laws, acts, and regulations that govern the electoral process in South Sudan.</p>
                        <a href="{{ route('about.legal-framework') }}" class="btn btn-outline-success btn-sm" style="border-color: var(--nec-green); color: var(--nec-green);">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
