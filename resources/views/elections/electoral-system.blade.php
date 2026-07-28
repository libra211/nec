@extends('layouts.app', ['title' => 'Electoral System - NEC South Sudan', 'active_page' => 'elections'])

@section('hero')
<div class="nec-hero-wrapper text-white" style="background:linear-gradient(135deg,#00914c 0%,#006b37 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('elections.calendar') }}" class="text-white opacity-75 text-decoration-none">Elections</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Electoral System</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-2">Electoral System of South Sudan</h1>
        <p class="lead mb-0 opacity-90">Understanding how elections work under the legal and constitutional framework of the Republic of South Sudan.</p>
    </div>
</div>
@endsection

@section('content')
@php
$sidebar_section = 'Elections';
$sidebar_links = [
    ['url' => route('elections.calendar'), 'label' => 'Election Calendar', 'icon' => 'fas fa-calendar-alt'],
    ['url' => route('electoral-system'), 'label' => 'Electoral System', 'icon' => 'fas fa-gavel', 'active' => true],
    ['url' => route('elections.types'), 'label' => 'Types of Elections', 'icon' => 'fas fa-vote-yea'],
    ['url' => route('elections.results'), 'label' => 'Election Results', 'icon' => 'fas fa-chart-bar'],
];
@endphp

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4">Electoral System Overview</h2>
                    <p>The electoral system of South Sudan is governed by the Transitional Constitution of the Republic of South Sudan, 2011 (as amended), the National Elections Act 2023, and regulations issued by the National Elections Commission. The system is designed to ensure free, fair, and credible elections that reflect the will of the people.</p>

                    <div class="row g-3 my-4">
                        <div class="col-md-6">
                            <div class="card card-bordered-green h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 fw-bold text-success">170</div>
                                    <small class="text-muted text-uppercase fw-semibold">National Assembly Seats</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered-red h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 fw-bold text-danger">30</div>
                                    <small class="text-muted text-uppercase fw-semibold">Council of States Seats</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered-blue h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 fw-bold" style="color:var(--nec-blue);">25%</div>
                                    <small class="text-muted text-uppercase fw-semibold">Women Representation Minimum</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-bordered-gold h-100">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 fw-bold" style="color:#b8860b;">50%+1</div>
                                    <small class="text-muted text-uppercase fw-semibold">Presidential Threshold</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Key Principles</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Universal Suffrage</strong><br><small class="text-muted">Every citizen 18+ has the right to vote</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>One Person, One Vote</strong><br><small class="text-muted">Each vote carries equal weight</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Secret Ballot</strong><br><small class="text-muted">Voters cast ballots in private</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Independent Commission</strong><br><small class="text-muted">NEC operates independently</small></div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Electoral System Types</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Position</th>
                                    <th>System</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>President</td>
                                    <td><span class="badge bg-success">Absolute Majority</span></td>
                                    <td>Two-round system if no candidate secures 50%+1 in first round</td>
                                </tr>
                                <tr>
                                    <td>National Assembly</td>
                                    <td><span class="badge bg-info">Parallel Voting</span></td>
                                    <td>170 seats: 85 FPTP constituencies + 85 PR (closed list) + 25% women reserved</td>
                                </tr>
                                <tr>
                                    <td>Council of States</td>
                                    <td><span class="badge bg-warning text-dark">Indirect</span></td>
                                    <td>Elected by state legislative assemblies (3 per state + 2 from CAP)</td>
                                </tr>
                                <tr>
                                    <td>State Governors</td>
                                    <td><span class="badge bg-danger">First-Past-the-Post</span></td>
                                    <td>Simple plurality in each of the 10 states + 3 admin areas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Sidebar -->
            <div class="card border-0 shadow-sm" style="border-radius:12px;position:sticky;top:100px;">
                <div class="card-body p-0">
                    <div class="px-3 pt-3 pb-2" style="background:var(--nec-green);color:#fff;border-radius:12px 12px 0 0;">
                        <small class="fw-bold text-uppercase" style="font-size:0.65rem;letter-spacing:1px;opacity:0.8;">In this section</small>
                        <h6 class="fw-bold mb-0" style="color:#fff;font-size:0.95rem;">{{ $sidebar_section }}</h6>
                    </div>
                    <ul class="sidebar-nav p-2">
                        @foreach($sidebar_links as $sl)
                        <li class="sidebar-nav-item">
                            <a href="{{ $sl['url'] }}" class="sidebar-nav-link {{ ($sl['active'] ?? false) ? 'active' : '' }}">
                                <i class="{{ $sl['icon'] ?? 'fas fa-link' }}"></i>
                                {{ $sl['label'] }}
                                @if(isset($sl['badge']))
                                <span class="badge bg-{{ $sl['badge_color'] ?? 'success' }} ms-auto">{{ $sl['badge'] }}</span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-file-pdf text-danger me-2"></i>Key Documents</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#" class="text-decoration-none"><i class="fas fa-download me-2 text-muted"></i>National Elections Act 2023</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none"><i class="fas fa-download me-2 text-muted"></i>Transitional Constitution (Elections Provisions)</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none"><i class="fas fa-download me-2 text-muted"></i>NEC Regulations & Guidelines</a></li>
                        <li><a href="#" class="text-decoration-none"><i class="fas fa-download me-2 text-muted"></i>Code of Conduct for Political Parties</a></li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4" style="background:#fff;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                    <h6 class="fw-bold">Need Help?</h6>
                    <p class="small text-muted mb-3">Contact the NEC electoral education team for more information.</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-success btn-sm w-100">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
