@extends('layouts.app', ['title' => 'Types of Elections - NEC South Sudan', 'active_page' => 'elections'])

@section('extra_head')
<style>
:root{--type-green:#2E8B57;--type-gold:#D4AF37;--type-blue:#1a3c8f;--type-purple:#8b5cf6;}
.election-type-card{border-radius:16px;overflow:hidden;background:#fff;box-shadow:var(--nec-shadow-xs);border:1px solid rgba(0,0,0,0.03);transition:transform 0.22s,box-shadow 0.22s,border-color 0.22s;}
.election-type-card:hover{transform:translateY(-5px);box-shadow:0 16px 32px rgba(0,0,0,0.09);border-color:var(--nec-green);}
.type-icon{width:56px;height:56px;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;font-size:1.3rem;color:#fff;}
.type-icon.green{background:linear-gradient(135deg,#2E8B57,#1f6b41);}
.type-icon.gold{background:linear-gradient(135deg,#D4AF37,#b8912a);}
.type-icon.blue{background:linear-gradient(135deg,#1a3c8f,#142c66);}
.type-icon.purple{background:linear-gradient(135deg,#8b5cf6,#6d3fd6);}
.type-type-badge{font-size:0.62rem;font-weight:700;text-transform:uppercase;letter-spacing:0.6px;padding:4px 10px;border-radius:20px;}
.type-date-badge{font-size:0.7rem;font-weight:600;padding:5px 12px;border-radius:20px;background:rgba(46,139,87,0.12);color:var(--nec-green);}
.election-type-card .type-feature{font-size:0.82rem;color:#64748b;display:flex;gap:8px;align-items:flex-start;margin-bottom:8px;}
.election-type-card .type-feature i{color:var(--nec-green);margin-top:2px;font-size:0.85rem;}
.table-success th{font-size:0.75rem;text-transform:uppercase;letter-spacing:0.6px;font-weight:700!important;color:#14532d!important;border-bottom:2px solid rgba(46,139,87,0.25)!important;}
.cta-band{background:linear-gradient(135deg,#0a1628 0%,#1a3c8f 55%,#2E8B57 100%);position:relative;overflow:hidden;border-radius:18px;}
.cta-band::before{content:"";position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");pointer-events:none;}
</style>
@endsection

@section('hero')
<section class="page-header" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('elections.index') }}" class="text-white opacity-75">Elections</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Types of Elections</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold text-white mb-2">Types of Elections</h1>
                <p class="text-white opacity-90 lead mb-0">Understanding the electoral system of South Sudan</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-block text-center px-4 py-2 rounded-3" style="background:rgba(255,255,255,0.15);">
                    <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;font-size:0.65rem;">Next Election Day</div>
                    <div class="text-white fw-bold" style="font-size:1.15rem;">{{ $fmt($presidential) ?? '21 Dec 2026' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold" style="color: var(--nec-green);">Election Types</h2>
            <div class="divider bg-danger mb-3" style="height: 4px; width: 60px; margin: 0 auto;"></div>
            <p class="text-muted mb-0" style="max-width:720px;margin:0 auto;">The Constitution of South Sudan provides for the following types of elections under the supervision of the National Elections Commission.</p>
        </div>

        <div class="row g-4">
            {{-- Presidential --}}
            <div class="col-md-6 col-lg-3">
                <div class="card election-type-card h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="type-icon green"><i class="fas fa-user-tie"></i></span>
                            @if($fmt($presidential))
                                <span class="type-date-badge"><i class="far fa-calendar-alt me-1"></i>{{ $fmt($presidential) }}</span>
                            @endif
                        </div>
                        <span class="type-type-badge mb-2" style="background:rgba(46,139,87,0.12);color:#2E8B57;"><i class="fas fa-crown me-1"></i>Head of State</span>
                        <h4 class="fw-bold mb-2" style="color: var(--nec-gold);">Presidential</h4>
                        <p class="small text-muted">Election of the President of the Republic of South Sudan. Held every five years through universal adult suffrage. The President is elected by an absolute majority of valid votes cast; if no candidate achieves an outright majority, a run-off is held between the top two candidates within 14 days.</p>
                        <ul class="list-unstyled mb-4 mt-auto">
                            <li class="type-feature"><i class="fas fa-check-circle"></i> 5-year term</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> Absolute majority + run-off</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> Universal adult suffrage</li>
                        </ul>
                        <a href="{{ route('elections.calendar') }}" class="btn btn-sm btn-success w-100" style="border-radius:9px;">2026 Timeline <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Parliamentary --}}
            <div class="col-md-6 col-lg-3">
                <div class="card election-type-card h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="type-icon blue"><i class="fas fa-landmark"></i></span>
                            @if($fmt($parliamentary))
                                <span class="type-date-badge"><i class="far fa-calendar-alt me-1"></i>{{ $fmt($parliamentary) }}</span>
                            @endif
                        </div>
                        <span class="type-type-badge mb-2" style="background:rgba(26,60,143,0.12);color:#1a3c8f;"><i class="fas fa-gavel me-1"></i>Legislature</span>
                        <h4 class="fw-bold mb-2" style="color: var(--nec-gold);">Parliamentary</h4>
                        <p class="small text-muted">Election of members of the National Assembly and the Council of States. The National Assembly consists of 332 members elected through a mixed electoral system combining first-past-the-post and proportional representation. These elections determine the legislative body of the nation.</p>
                        <ul class="list-unstyled mb-4 mt-auto">
                            <li class="type-feature"><i class="fas fa-check-circle"></i> 332 National Assembly seats</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> Mixed electoral system</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> 5-year term</li>
                        </ul>
                        <a href="{{ route('elections.calendar') }}" class="btn btn-sm btn-success w-100" style="border-radius:9px;">2026 Timeline <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- State Legislative --}}
            <div class="col-md-6 col-lg-3">
                <div class="card election-type-card h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="type-icon gold"><i class="fas fa-city"></i></span>
                            <span class="type-date-badge"><i class="fas fa-location-dot me-1"></i>{{ $stateAssembly->count() ? $stateAssembly->count().' regions' : 'Nationwide' }}</span>
                        </div>
                        <span class="type-type-badge mb-2" style="background:rgba(212,175,55,0.14);color:#b8912a;"><i class="fas fa-map-location-dot me-1"></i>Sub-national</span>
                        <h4 class="fw-bold mb-2" style="color: var(--nec-gold);">State Legislative</h4>
                        <p class="small text-muted">Election of members of State Legislative Assemblies in each of the states and administrative areas. Each state has its own assembly responsible for state-level legislation and oversight of the state executive. Members are elected for a five-year term.</p>
                        <ul class="list-unstyled mb-4 mt-auto">
                            <li class="type-feature"><i class="fas fa-check-circle"></i> State Assemblies</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> State-level legislation</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> 5-year term</li>
                        </ul>
                        <a href="{{ route('elections.calendar') }}" class="btn btn-sm btn-success w-100" style="border-radius:9px;">2026 Timeline <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Local Government --}}
            <div class="col-md-6 col-lg-3">
                <div class="card election-type-card h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="type-icon purple"><i class="fas fa-users"></i></span>
                            <span class="type-date-badge"><i class="fas fa-calendar-day me-1"></i>Rolling</span>
                        </div>
                        <span class="type-type-badge mb-2" style="background:rgba(139,92,246,0.12);color:#6d3fd6;"><i class="fas fa-house-user me-1"></i>Grassroots</span>
                        <h4 class="fw-bold mb-2" style="color: var(--nec-gold);">Local Government</h4>
                        <p class="small text-muted">Election of local government councils including county commissioners, payam administrators, and boma chiefs. These elections ensure grassroots representation and local governance, conducted under the supervision of the NEC in coordination with state authorities.</p>
                        <ul class="list-unstyled mb-4 mt-auto">
                            <li class="type-feature"><i class="fas fa-check-circle"></i> County, Payam, Boma levels</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> Grassroots representation</li>
                            <li class="type-feature"><i class="fas fa-check-circle"></i> Local governance</li>
                        </ul>
                        <a href="{{ route('elections.calendar') }}" class="btn btn-sm btn-success w-100" style="border-radius:9px;">2026 Timeline <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Comparison Table --}}
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="section-title text-center mb-4">
            <h2 class="fw-bold" style="color: var(--nec-green);">At a Glance</h2>
            <div class="divider bg-danger mb-3" style="height: 4px; width: 60px; margin: 0 auto;"></div>
            <p class="text-muted mb-0" style="max-width:680px;margin:0 auto;">A quick comparison of the electoral processes conducted by the Commission.</p>
        </div>
        <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th scope="col" class="ps-4" style="width:6%;">#</th>
                            <th scope="col" style="width:18%;">Election Type</th>
                            <th scope="col" style="width:14%;">Level</th>
                            <th scope="col" style="width:22%;">Seats / Bodies</th>
                            <th scope="col" style="width:26%;">Voting System</th>
                            <th scope="col" class="text-center pe-4" style="width:12%;">Term</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 text-center fw-bold text-muted" style="font-size:0.82rem;">1</td>
                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-user-tie me-2" style="color:var(--nec-green);"></i>Presidential</td>
                            <td style="font-size:0.85rem;">National</td>
                            <td style="font-size:0.85rem;">President <span class="badge rounded-pill ms-1" style="background:rgba(46,139,87,0.12);color:#2E8B57;font-size:0.65rem;">1</span></td>
                            <td style="font-size:0.85rem;">Absolute majority, run-off within 14 days</td>
                            <td class="text-center pe-4"><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.7rem;font-weight:600;">5 years</span></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-center fw-bold text-muted" style="font-size:0.82rem;">2</td>
                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-landmark me-2" style="color:var(--nec-blue);"></i>Parliamentary</td>
                            <td style="font-size:0.85rem;">National</td>
                            <td style="font-size:0.85rem;">National Assembly &amp; Council of States <span class="badge rounded-pill ms-1" style="background:rgba(26,60,143,0.12);color:#1a3c8f;font-size:0.65rem;">332</span></td>
                            <td style="font-size:0.85rem;">Mixed (FPTP + proportional representation)</td>
                            <td class="text-center pe-4"><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.7rem;font-weight:600;">5 years</span></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-center fw-bold text-muted" style="font-size:0.82rem;">3</td>
                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-city me-2" style="color:var(--nec-gold);"></i>State Legislative</td>
                            <td style="font-size:0.85rem;">State / Admin Area</td>
                            <td style="font-size:0.85rem;">State Legislative Assemblies</td>
                            <td style="font-size:0.85rem;">Varies by state electoral law</td>
                            <td class="text-center pe-4"><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.7rem;font-weight:600;">5 years</span></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-center fw-bold text-muted" style="font-size:0.82rem;">4</td>
                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-users me-2" style="color:#6d3fd6;"></i>Local Government</td>
                            <td style="font-size:0.85rem;">County / Payam / Boma</td>
                            <td style="font-size:0.85rem;">Local Government Councils</td>
                            <td style="font-size:0.85rem;">Grassroots representation</td>
                            <td class="text-center pe-4"><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.7rem;font-weight:600;">5 years</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Legal Basis + FAQ --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 h-100 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-balance-scale me-2" style="color: var(--nec-gold);"></i>Legal Basis</h5>
                        <p class="small text-muted">The electoral system of South Sudan is established by the Transitional Constitution of the Republic of South Sudan, 2011 (as amended), and the National Elections Act, 2012. These legal instruments define the powers and responsibilities of the NEC and the procedures for conducting elections.</p>
                        <ul class="small text-muted mb-4 ps-3">
                            <li>Transitional Constitution of South Sudan, 2011</li>
                            <li>National Elections Act, 2012</li>
                            <li>Political Parties Act, 2012</li>
                            <li>NEC Regulations and Guidelines</li>
                        </ul>
                        <a href="{{ route('about.legal-framework') }}" class="btn btn-sm btn-outline-success" style="border-radius:8px;">Legal Framework <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 h-100 shadow-sm" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2" style="color: var(--nec-gold);"></i>Frequently Asked Questions</h5>
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">How often are elections held?</button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted">General elections are held every five years. By-elections may be conducted as needed to fill vacancies.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Can I vote in all types of elections?</button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted">Yes, eligible voters can vote in all elections: Presidential, Parliamentary, State Legislative, and Local Government.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">What is the voting system used?</button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted">South Sudan uses a mixed electoral system combining first-past-the-post and proportional representation for parliamentary elections.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">When is the next election?</button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body small text-muted">The next Presidential and National Legislative elections are scheduled for {{ $fmt($presidential) ?? 'December 2026' }}. See the <a href="{{ route('elections.calendar') }}" class="text-decoration-none">Election Calendar</a> for all key dates.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Band --}}
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="cta-band px-4 py-5">
            <div class="row align-items-center g-4 position-relative" style="z-index:1;">
                <div class="col-lg-7">
                    <h3 class="text-white fw-bold mb-2" style="font-size:1.5rem;">Prepare for the 2026 General Elections</h3>
                    <p class="text-white-50 mb-0" style="font-size:0.95rem;">Check key dates, register to vote, and follow results as they are announced by the Commission.</p>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('elections.calendar') }}" class="btn btn-warning btn-sm fw-semibold" style="border-radius:9px;"><i class="fas fa-calendar-alt me-1"></i>Election Calendar</a>
                        <a href="{{ route('voter.register') }}" class="btn btn-light btn-sm fw-semibold" style="border-radius:9px;"><i class="fas fa-user-plus me-1"></i>Register to Vote</a>
                        <a href="{{ route('elections.results') }}" class="btn btn-outline-light btn-sm fw-semibold" style="border-radius:9px;"><i class="fas fa-chart-bar me-1"></i>Election Results</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection