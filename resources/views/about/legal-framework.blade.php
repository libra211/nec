@extends('layouts.app', ['title' => 'Legal Framework', 'active_page' => 'about', 'meta_description' => 'Legal framework governing elections in South Sudan - Constitution, Electoral Act, Political Parties Act, and regulations.'])

@section('hero')
<section class="page-header-section" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-8">
                <h1 class="text-white fw-bold mb-2">Legal Framework</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('about.index') }}" class="text-white-50">About NEC</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Legal Framework</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <i class="fas fa-book text-white-50" style="font-size: 3.5rem; opacity: 0.5;"></i>
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
                            <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Download Legal Documents</h5>
                            <span class="badge bg-success" style="font-size:0.7rem;background:var(--nec-green)!important;">
                                <i class="fas fa-download me-1"></i>{{ $documents->count() }}
                            </span>
                        </div>
                        <div class="list-group list-group-flush">
                            @forelse($documents as $doc)
                            <a href="{{ route('downloads.serve', ['type' => 'file', 'id' => $doc->id]) }}" target="_blank" rel="noopener" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom">
                                <i class="fas {{ $doc->file_icon }} fs-5" style="color: #dc3545;"></i>
                                <div class="flex-grow-1">
                                    <small class="fw-semibold d-block">{{ $doc->title }}</small>
                                    <small class="text-muted">@if(is_numeric($doc->file_size))PDF, {{ number_format($doc->file_size / 1048576, 2) }} MB @endif</small>
                                </div>
                            </a>
                            @empty
                            <p class="small text-muted mb-0">No legal documents are available yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm" style="background: var(--nec-black);">
                    <div class="card-body p-4 text-white">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-gold);">Guiding Principle</h5>
                        <p class="small text-white-50 mb-0 fst-italic">"The National Elections Commission shall perform its functions independently and impartially, without fear, favour, or prejudice, and in accordance with the Constitution and the law."</p>
                        <p class="small text-white-50 mt-2 mb-0">— National Elections Act, 2023</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3" style="color: var(--nec-black);">Related Links</h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('about.mandate') }}" class="list-group-item list-group-item-action px-0 border-bottom">Our Mandate</a>
                            <a href="{{ route('about.commissioners') }}" class="list-group-item list-group-item-action px-0 border-bottom">Commissioners</a>
                            <a href="{{ route('about.history') }}" class="list-group-item list-group-item-action px-0 border-bottom">History</a>
                            <a href="{{ route('about.state-committees') }}" class="list-group-item list-group-item-action px-0">State Committees</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4" style="color: var(--nec-black);">Legal Basis for Elections in South Sudan</h2>
                <p class="text-muted">The electoral process in South Sudan is governed by a comprehensive legal framework that ensures elections are conducted in accordance with constitutional principles, international standards, and the rule of law.</p>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Transitional Constitution of the Republic of South Sudan, 2011 (as amended)</h3>
                <p class="text-muted">The Transitional Constitution is the supreme law of South Sudan. It establishes the National Elections Commission as an independent constitutional institution (Articles 197-202) and sets out the fundamental principles governing elections, including universal suffrage, secret ballot, and regular periodic elections.</p>
                <p class="text-muted">Key constitutional provisions related to elections include:</p>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item px-3 border-bottom d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Article 57</strong> — Political rights: Every citizen has the right to participate in public affairs and to vote and be elected.</div>
                    </li>
                    <li class="list-group-item px-3 border-bottom d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Article 197</strong> — Establishment of the National Elections Commission.</div>
                    </li>
                    <li class="list-group-item px-3 border-bottom d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Article 198</strong> — Composition and appointment of Commissioners.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-check-circle mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Article 200</strong> — Functions and independence of the NEC.</div>
                    </li>
                </ul>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">National Elections Act, 2023</h3>
                <p class="text-muted">The National Elections Act, 2023 is the primary legislation governing the conduct of elections in South Sudan. It repealed and replaced the National Elections Act, 2012, introducing significant reforms to strengthen the electoral process. The Act covers:</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Voter Registration</h6>
                            <p class="small text-muted mb-0">Procedures for compiling and maintaining the voters register</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Candidate Nomination</h6>
                            <p class="small text-muted mb-0">Requirements and procedures for candidate nomination</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Election Campaign</h6>
                            <p class="small text-muted mb-0">Regulations governing campaign conduct and financing</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Polling & Counting</h6>
                            <p class="small text-muted mb-0">Procedures for voting, counting, and result declaration</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Electoral Disputes</h6>
                            <p class="small text-muted mb-0">Mechanisms for resolving election-related disputes</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1">Observer Accreditation</h6>
                            <p class="small text-muted mb-0">Framework for domestic and international election observation</p>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Political Parties Act, 2012</h3>
                <p class="text-muted">The Political Parties Act, 2012 regulates the formation, registration, and operation of political parties in South Sudan. It sets out the requirements for party registration, the rights and obligations of political parties, and the principles of political party funding and transparency.</p>

                <h3 class="fw-bold mt-5 mb-3" style="color: var(--nec-black);">Other Relevant Legislation</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3 border-bottom d-flex gap-3">
                        <i class="fas fa-file-alt mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>Code of Conduct for Political Parties</strong> — Ethical guidelines for political party behavior during elections.</div>
                    </li>
                    <li class="list-group-item px-3 border-bottom d-flex gap-3">
                        <i class="fas fa-file-alt mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>NEC Regulations & Directives</strong> — Subsidiary legislation issued by the NEC to implement the Electoral Act.</div>
                    </li>
                    <li class="list-group-item px-3 d-flex gap-3">
                        <i class="fas fa-file-alt mt-1" style="color: var(--nec-green);"></i>
                        <div><strong>International Treaties</strong> — Regional and international instruments on democratic elections to which South Sudan is a signatory.</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection