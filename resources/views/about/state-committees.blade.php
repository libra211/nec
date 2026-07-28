@extends('layouts.app', ['title' => 'State High Committees', 'active_page' => 'about', 'meta_description' => 'State High Committees of the National Elections Commission of South Sudan.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">State High Committees</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">State High Committees</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-map-marked-alt text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">States of South Sudan</h5>
                        <div class="list-group list-group-flush">
                            @php
                                if (!isset($states) || empty($states)) {
                                    $state_names = ['Central Equatoria', 'Eastern Equatoria', 'Jonglei', 'Lakes', 'Northern Bahr el Ghazal', 'Unity', 'Upper Nile', 'Warrap', 'Western Bahr el Ghazal', 'Western Equatoria'];
                                    $states = [];
                                    foreach ($state_names as $i => $name) {
                                        $states[] = (object)['id' => $i + 1, 'name' => $name, 'capital' => '', 'population' => ''];
                                    }
                                }
                            @endphp
                            @foreach($states as $s)
                            <div class="list-group-item px-0 d-flex align-items-center gap-3 border-bottom">
                                <i class="fas fa-map-pin" style="color: var(--nec-green); font-size: 0.8rem;"></i>
                                <span>{{ $s->name }}</span>
                            </div>
                            @endforeach
                        </div>
                        <p class="small text-muted mt-3 mb-0">Each state has a dedicated State High Committee appointed by NEC to oversee electoral operations.</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);"><i class="fas fa-link me-2" style="color:var(--nec-gold);"></i>Related Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('about.mandate') }}" class="list-group-item list-group-item-action px-0 border-bottom">Our Mandate</a>
                            <a href="{{ route('about.commissioners') }}" class="list-group-item list-group-item-action px-0 border-bottom">Commissioners</a>
                            <a href="{{ route('about.history') }}" class="list-group-item list-group-item-action px-0 border-bottom">History</a>
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
                <h2 class="fw-bold mb-3" style="color: var(--nec-black);">Role of State High Committees</h2>
                <p class="text-muted">State High Committees (SHCs) are decentralized bodies of the National Elections Commission established in each state of South Sudan. They are responsible for the coordination and implementation of electoral activities at the state level, ensuring that elections are conducted smoothly and in accordance with national standards.</p>
                <p class="text-muted">Each State High Committee is composed of a Chairperson and members appointed by the NEC in consultation with state authorities. The committees work closely with the NEC headquarters in Juba to ensure consistent application of electoral laws and regulations across all states.</p>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Key Responsibilities</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-clipboard-list fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Registration</h6>
                                <p class="small text-muted mb-0">Oversee voter registration drives within the state</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-school fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Polling Stations</h6>
                                <p class="small text-muted mb-0">Establish and manage polling stations across counties</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-boxes fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Logistics</h6>
                                <p class="small text-muted mb-0">Coordinate distribution of election materials</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-shield-alt fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Security</h6>
                                <p class="small text-muted mb-0">Coordinate with security agencies for safe elections</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-chart-bar fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Result Collation</h6>
                                <p class="small text-muted mb-0">Collate and transmit results to NEC headquarters</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 border rounded-3" style="background:#fff;box-shadow:var(--nec-shadow-xs);">
                            <i class="fas fa-handshake fs-4" style="color: var(--nec-green);"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Stakeholder Liaison</h6>
                                <p class="small text-muted mb-0">Engage with local stakeholders and political parties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
