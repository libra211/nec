@extends('layouts.app', ['title' => 'Legal Framework', 'active_page' => 'about', 'meta_description' => 'Legal framework governing elections in South Sudan - Constitution, Electoral Act, Political Parties Act, and regulations.'])

@section('extra_head')
<style>
.law-section{scroll-margin-top:92px;}
.legal-sidebar{position:sticky;top:92px;}
.onpage-link{font-size:0.82rem;padding:7px 12px;border-radius:8px;color:#475569;transition:all 0.15s;}
.onpage-link:hover{background:rgba(46,139,87,0.08);color:var(--nec-green);}
.onpage-link i{width:18px;color:var(--nec-green);}
.doc-item{transition:background 0.15s;}
.doc-item:hover{background:rgba(46,139,87,0.05);}
.doc-item .file-ico{width:40px;height:44px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:#fff;background:#dc3545;flex-shrink:0;}
.law-stat-tile{border-radius:12px;background:#f8fafc;border:1px solid rgba(0,0,0,0.05);padding:14px 16px;}
.law-stat-tile .num{font-size:1.35rem;font-weight:800;color:var(--nec-green);line-height:1;}
.law-stat-tile .lbl{font-size:0.7rem;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;font-weight:600;}
</style>
@endsection

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
                <div class="d-inline-block text-center px-4 py-2 rounded-3" style="background:rgba(255,255,255,0.15);">
                    <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;font-size:0.65rem;">Legal Instruments</div>
                    <div class="text-white fw-bold" style="font-size:1.15rem;">{{ $documents->count() }} Documents</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="legal-sidebar">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="color: var(--nec-black);">Download Legal Documents</h5>
                                <span class="badge bg-success total-downloads-badge" style="font-size:0.7rem;background:var(--nec-green)!important;">
                                    <i class="fas fa-download me-1"></i><span class="total-dl-count">{{ $documents->count() }}</span>
                                </span>
                            </div>
                            <div class="list-group list-group-flush">
                                @forelse($documents as $doc)
                                <a href="{{ route('downloads.serve', ['type' => 'file', 'id' => $doc->id]) }}" target="_blank" class="doc-item list-group-item list-group-item-action px-0 d-flex align-items-center gap-3 border-bottom" data-track-download="{{ $doc->id }}" data-label="{{ $doc->title }}">
                                    <span class="file-ico"><i class="fas {{ $doc->file_icon }}"></i></span>
                                    <div class="flex-grow-1">
                                        <small class="fw-semibold d-block">{{ $doc->title }}</small>
                                        <small class="text-muted d-block" style="font-size:0.7rem;">
                                            <span class="text-uppercase" style="font-size:0.62rem;">{{ $doc->category }}</span>
                                            @if(is_numeric($doc->file_size)) &middot; {{ number_format($doc->file_size / 1048576, 2) }} MB @endif
                                        </small>
                                    </div>
                                    <span class="dl-count-badge badge rounded-pill bg-light text-muted" style="font-size:0.65rem;font-weight:500;">{{ number_format($doc->downloads_count ?? 0) }}</span>
                                </a>
                                @empty
                                <p class="small text-muted mb-0">No legal documents are available yet.</p>
                                @endforelse
                            </div>
                            <a href="{{ route('downloads.index') }}" class="btn btn-sm btn-outline-success w-100 mt-3" style="border-radius:8px;">View All Downloads <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: var(--nec-black);">On This Page</h5>
                            <nav class="d-flex flex-column gap-1">
                                <a href="#constitution" class="onpage-link"><i class="fas fa-scroll"></i>Constitution, 2011</a>
                                <a href="#elections-act" class="onpage-link"><i class="fas fa-gavel"></i>National Elections Act</a>
                                <a href="#parties-act" class="onpage-link"><i class="fas fa-flag"></i>Political Parties Act</a>
                                <a href="#other-legislation" class="onpage-link"><i class="fas fa-file-contract"></i>Other Legislation</a>
                            </nav>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="law-stat-tile text-center">
                                <div class="num">{{ $documents->count() }}</div>
                                <div class="lbl">Instruments</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="law-stat-tile text-center">
                                <div class="num">{{ number_format($documents->sum('downloads_count')) }}</div>
                                <div class="lbl">Downloads</div>
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
            </div>

            {{-- Main content --}}
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4" style="color: var(--nec-black);">Legal Basis for Elections in South Sudan</h2>
                <p class="text-muted">The electoral process in South Sudan is governed by a comprehensive legal framework that ensures elections are conducted in accordance with constitutional principles, international standards, and the rule of law.</p>

                <h3 class="fw-bold mt-4 mb-3 law-section" style="color: var(--nec-black);" id="constitution">Transitional Constitution of the Republic of South Sudan, 2011 (as amended)</h3>
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

                <h3 class="fw-bold mt-5 mb-3 law-section" id="elections-act" style="color: var(--nec-black);">National Elections Act, 2023</h3>
                <p class="text-muted">The National Elections Act, 2023 is the primary legislation governing the conduct of elections in South Sudan. It repealed and replaced the National Elections Act, 2012, introducing significant reforms to strengthen the electoral process. The Act covers:</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-user-check me-2" style="color:var(--nec-green);"></i>Voter Registration</h6>
                            <p class="small text-muted mb-0">Procedures for compiling and maintaining the voters register</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-file-signature me-2" style="color:var(--nec-green);"></i>Candidate Nomination</h6>
                            <p class="small text-muted mb-0">Requirements and procedures for candidate nomination</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-bullhorn me-2" style="color:var(--nec-green);"></i>Election Campaign</h6>
                            <p class="small text-muted mb-0">Regulations governing campaign conduct and financing</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-box-open me-2" style="color:var(--nec-green);"></i>Polling & Counting</h6>
                            <p class="small text-muted mb-0">Procedures for voting, counting, and result declaration</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-scale-balanced me-2" style="color:var(--nec-green);"></i>Electoral Disputes</h6>
                            <p class="small text-muted mb-0">Mechanisms for resolving election-related disputes</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <h6 class="fw-bold mb-1"><i class="fas fa-glasses me-2" style="color:var(--nec-green);"></i>Observer Accreditation</h6>
                            <p class="small text-muted mb-0">Framework for domestic and international election observation</p>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold mt-5 mb-3 law-section" id="parties-act" style="color: var(--nec-black);">Political Parties Act, 2012</h3>
                <p class="text-muted">The Political Parties Act, 2012 regulates the formation, registration, and operation of political parties in South Sudan. It sets out the requirements for party registration, the rights and obligations of political parties, and the principles of political party funding and transparency.</p>

                <h3 class="fw-bold mt-5 mb-3 law-section" id="other-legislation" style="color: var(--nec-black);">Other Relevant Legislation</h3>
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

                <div class="row g-4 mt-5">
                    <div class="col-6">
                        <a href="{{ route('downloads.index') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm p-4 h-100 text-center">
                                <i class="fas fa-download fs-3 mb-2" style="color:var(--nec-green);"></i>
                                <strong class="small">Download Center</strong>
                                <small class="text-muted">Publications, reports &amp; electoral forms</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('about.mandate') }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm p-4 h-100 text-center">
                                <i class="fas fa-scale-balanced fs-3 mb-2" style="color:var(--nec-green);"></i>
                                <strong class="small">Our Mandate</strong>
                                <small class="text-muted">Powers and functions of the Commission</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection