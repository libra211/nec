@extends('layouts.app', ['title' => 'History', 'active_page' => 'about', 'meta_description' => 'History of the National Elections Commission of South Sudan from independence to the present day.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">History</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">History</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-history text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Key Milestones</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2011</span>
                                <span class="text-muted small">Independence & CPA Referendum</span>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2012</span>
                                <span class="text-muted small">NEC Established</span>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2018</span>
                                <span class="text-muted small">R-ARCSS Signed</span>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2022</span>
                                <span class="text-muted small">NEC Reconstituted</span>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2023</span>
                                <span class="text-muted small">New Electoral Act</span>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-green); min-width: 45px;">2024</span>
                                <span class="text-muted small">Voter Registration</span>
                            </li>
                            <li class="d-flex gap-3">
                                <span class="fw-bold" style="color: var(--nec-gold); min-width: 45px;">2026</span>
                                <span class="text-muted small fw-semibold">General Elections</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 download-stats-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Historical Documents</h5>
                            <span class="badge bg-success total-downloads-badge" style="font-size:0.7rem;background:var(--nec-green)!important;">
                                <i class="fas fa-download me-1"></i><span class="total-dl-count">0</span>
                            </span>
                        </div>
                        <div class="list-group list-group-flush" id="historicalDocs">
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="constitution-2011" data-label="Transitional Constitution 2011">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">Transitional Constitution 2011</small><small class="text-muted">PDF, 2.4 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="cpa-2005" data-label="CPA Agreement 2005">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">CPA Agreement 2005</small><small class="text-muted">PDF, 3.1 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="r-arcss-2018" data-label="R-ARCSS 2018">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">R-ARCSS 2018</small><small class="text-muted">PDF, 1.8 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3" data-track-download="elections-act-2023" data-label="National Elections Act 2023">
                                <i class="fas fa-file-pdf fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1"><small class="fw-semibold d-block">National Elections Act 2023</small><small class="text-muted">PDF, 1.8 MB</small></div>
                                <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">0</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Related Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('about.mandate') }}" class="list-group-item list-group-item-action px-0 border-bottom">Our Mandate</a>
                            <a href="{{ route('about.leadership') }}" class="list-group-item list-group-item-action px-0 border-bottom">Leadership</a>
                            <a href="{{ route('about.commissioners') }}" class="list-group-item list-group-item-action px-0 border-bottom">Commissioners</a>
                            <a href="{{ route('about.legal-framework') }}" class="list-group-item list-group-item-action px-0">Legal Framework</a>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);"><i class="fas fa-phone-alt me-2" style="color:var(--nec-gold);"></i>Contact NEC</h5>
                        <div class="small text-muted">
                            <span class="d-block mb-2"><i class="fas fa-map-marker-alt me-2" style="color:var(--nec-green);width:16px;"></i>Juba, South Sudan</span>
                            <span class="d-block mb-2"><i class="fas fa-phone me-2" style="color:var(--nec-green);width:16px;"></i>+211 (0) 912 345 678</span>
                            <span class="d-block mb-2"><i class="fas fa-envelope me-2" style="color:var(--nec-green);width:16px;"></i>info@nec.gov.ss</span>
                            <a href="{{ route('contact.index') }}" class="btn btn-sm btn-outline-success mt-2 w-100">Send a Message</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3" style="color: var(--nec-black);">A Journey of Democracy: The History of NEC South Sudan</h2>
                <p class="text-muted">The National Elections Commission of South Sudan has a rich history intertwined with the nation's journey toward peace, independence, and democratic governance. From the Comprehensive Peace Agreement (CPA) of 2005 to the upcoming 2026 general elections, the NEC has played a pivotal role in shaping the electoral landscape of the world's youngest nation.</p>
                <div class="timeline mt-4" id="necHistory">
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2005</span>
                            <span class="fw-bold" style="color: var(--nec-green);">January 2005</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">Comprehensive Peace Agreement</h5>
                            <p class="text-muted small">The CPA was signed between the Government of Sudan and the Sudan People's Liberation Movement/Army (SPLM/A), ending Africa's longest-running civil war. The agreement provided for a referendum on self-determination for South Sudan, laying the groundwork for the establishment of an independent electoral authority.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2011</span>
                            <span class="fw-bold" style="color: var(--nec-green);">January 2011</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">CPA Referendum</h5>
                            <p class="text-muted small">The landmark referendum on self-determination was conducted, with 98.83% of voters choosing independence. This historic vote was organized by the Southern Sudan Referendum Commission and marked the birth of the Republic of South Sudan on 9 July 2011.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2012</span>
                            <span class="fw-bold" style="color: var(--nec-green);">July 2012</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">Establishment of NEC</h5>
                            <p class="text-muted small">The National Elections Commission was formally established under the Transitional Constitution, 2011. The first Commissioners were appointed, and the Commission began its work of building the institutional framework for democratic elections in the new nation. The National Elections Act, 2012 was also enacted to provide the legal foundation for electoral activities.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2015</span>
                            <span class="fw-bold" style="color: var(--nec-green);">2015</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">First Planned Elections Postponed</h5>
                            <p class="text-muted small">The first general elections after independence, initially scheduled for 2015, were postponed due to the outbreak of civil war in December 2013 and the subsequent security and political challenges. The Transitional National Legislative Assembly extended the term of the elected government, and the NEC continued with voter registration and civic education activities in preparation for future elections.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2018</span>
                            <span class="fw-bold" style="color: var(--nec-green);">September 2018</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">Revitalized Peace Agreement</h5>
                            <p class="text-muted small">The Revitalized Agreement on the Resolution of the Conflict in the Republic of South Sudan (R-ARCSS) was signed, establishing a transitional period of 36 months and setting the stage for the conduct of elections at the end of the transition. The agreement reaffirmed the role of the NEC as the independent body responsible for conducting elections.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2022</span>
                            <span class="fw-bold" style="color: var(--nec-green);">2022</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">NEC Reconstituted</h5>
                            <p class="text-muted small">The NEC was reconstituted with new leadership and Commissioners appointed by the President with the approval of the Transitional National Legislative Assembly. The reconstitution brought renewed focus to electoral preparations, with the Commission embarking on a comprehensive program to build its institutional capacity and operational readiness.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2023</span>
                            <span class="fw-bold" style="color: var(--nec-green);">2023</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">New Electoral Act</h5>
                            <p class="text-muted small">The National Elections Act, 2023 was enacted, repealing and replacing the 2012 Act. The new law introduced significant reforms, including provisions for diaspora voting, improved gender representation, enhanced dispute resolution mechanisms, and stronger guarantees for the independence and impartiality of the electoral process.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2024</span>
                            <span class="fw-bold" style="color: var(--nec-green);">2024</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">Voter Registration Drive</h5>
                            <p class="text-muted small">The NEC launched a nationwide voter registration exercise in preparation for the 2026 general elections. The Commission registered approximately 8.4 million voters, deployed modern biometric registration kits, and conducted extensive civic education campaigns to ensure maximum citizen participation.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-date">
                            <span class="badge bg-warning text-dark mb-2">2026</span>
                            <span class="fw-bold" style="color: var(--nec-gold);">December 2026</span>
                        </div>
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h5 class="fw-bold">General Elections</h5>
                            <p class="text-muted small">South Sudan prepares for its first post-independence general elections, marking a historic milestone in the country's democratic journey. The NEC is fully engaged in preparations to deliver free, fair, and credible elections.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
