@extends('layouts.app', ['title' => 'Types of Elections - NEC South Sudan', 'active_page' => 'elections'])

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
        <h1 class="fw-bold text-white mb-2">Types of Elections</h1>
        <p class="text-white opacity-90 lead mb-0">Understanding the electoral system of South Sudan</p>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold" style="color: var(--nec-green);">Election Types</h2>
            <div class="divider bg-danger mb-3" style="height: 4px; width: 60px; margin: 0 auto;"></div>
            <p class="text-muted">The Constitution of South Sudan provides for the following types of elections under the supervision of the National Elections Commission.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: var(--nec-green);">
                            <i class="fas fa-user-tie text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--nec-gold);">Presidential</h4>
                        <p class="small text-muted">Election of the President of the Republic of South Sudan. Held every five years through universal adult suffrage. The President is elected by an absolute majority of valid votes cast. If no candidate achieves an outright majority, a run-off election is held between the top two candidates within 14 days.</p>
                        <ul class="list-unstyled small text-start text-muted mt-3 mb-0">
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> 5-year term</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> Simple majority required</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> Run-off if no absolute majority</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: var(--nec-green);">
                            <i class="fas fa-landmark text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--nec-gold);">Parliamentary</h4>
                        <p class="small text-muted">Election of members of the National Assembly and the Council of States. The National Assembly consists of 332 members elected through a mixed electoral system combining first-past-the-post and proportional representation. Parliamentary elections determine the legislative body of the nation.</p>
                        <ul class="list-unstyled small text-start text-muted mt-3 mb-0">
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> 332 National Assembly seats</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> Mixed electoral system</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> 5-year term</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: var(--nec-green);">
                            <i class="fas fa-city text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--nec-gold);">State Legislative</h4>
                        <p class="small text-muted">Election of members of State Legislative Assemblies in each of the 13 states and administrative areas. Each state has its own legislative assembly responsible for state-level legislation and oversight of the state executive. Members are elected for a five-year term.</p>
                        <ul class="list-unstyled small text-start text-muted mt-3 mb-0">
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> 13 State Assemblies</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> State-level legislation</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> 5-year term</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; border-radius: 50%; background: var(--nec-green);">
                            <i class="fas fa-users text-white fa-2x"></i>
                        </div>
                        <h4 class="fw-bold" style="color: var(--nec-gold);">Local Government</h4>
                        <p class="small text-muted">Election of local government councils including county commissioners, payam administrators, and boma chiefs. These elections ensure grassroots representation and local governance. Local government elections are conducted under the supervision of the NEC in coordination with state authorities.</p>
                        <ul class="list-unstyled small text-start text-muted mt-3 mb-0">
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> County, Payam, Boma levels</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> Grassroots representation</li>
                            <li><i class="fas fa-check-circle me-1" style="color: var(--nec-green);"></i> Local governance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-balance-scale me-2" style="color: var(--nec-gold);"></i>Legal Basis</h5>
                        <p class="small text-muted">The electoral system of South Sudan is established by the Transitional Constitution of the Republic of South Sudan, 2011 (as amended), and the National Elections Act, 2012. These legal instruments define the powers and responsibilities of the NEC and the procedures for conducting elections.</p>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Transitional Constitution of South Sudan, 2011</li>
                            <li>National Elections Act, 2012</li>
                            <li>Political Parties Act, 2012</li>
                            <li>NEC Regulations and Guidelines</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 h-100" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
