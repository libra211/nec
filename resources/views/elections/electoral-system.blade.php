@extends('layouts.app', ['title' => 'Electoral System - NEC South Sudan', 'active_page' => 'elections', 'meta_description' => 'How elections work in South Sudan: presidential, parliamentary, and state electoral systems under the Constitution and National Elections Act 2023.'])

@section('extra_head')
<style>
.es-stat-tile{border-radius:14px;background:#fff;border:1px solid rgba(0,0,0,0.05);box-shadow:var(--nec-shadow-xs);padding:20px 18px;transition:transform 0.2s,box-shadow 0.2s;}
.es-stat-tile:hover{transform:translateY(-4px);box-shadow:0 12px 24px rgba(0,0,0,0.08);}
.es-stat-tile .stat-ico{width:48px;height:48px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:1.15rem;flex-shrink:0;}
.es-stat-tile .num{font-size:1.7rem;font-weight:800;line-height:1;color:var(--nec-black);}
.es-stat-tile .lbl{font-size:0.68rem;text-transform:uppercase;letter-spacing:0.6px;color:#64748b;font-weight:600;}
.table-success th{font-size:0.75rem;text-transform:uppercase;letter-spacing:0.6px;font-weight:700!important;color:#14532d!important;border-bottom:2px solid rgba(46,139,87,0.25)!important;}
</style>
@endsection

@section('hero')
<section class="page-header" style="background: linear-gradient(135deg, var(--nec-green) 0%, var(--nec-green-dark) 100%);">
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white opacity-75">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('elections.calendar') }}" class="text-white opacity-75">Elections</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Electoral System</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold text-white mb-2">Electoral System of South Sudan</h1>
                <p class="text-white opacity-90 lead mb-0">Understanding how elections work under the legal and constitutional framework of the Republic of South Sudan.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-block text-center px-4 py-2 rounded-3" style="background:rgba(255,255,255,0.15);">
                    <div class="text-white-50 small text-uppercase" style="letter-spacing:1px;font-size:0.65rem;">National Assembly</div>
                    <div class="text-white fw-bold" style="font-size:1.15rem;">170 Seats &middot; 25% Women</div>
                </div>
            </div>
        </div>
    </div>
</section>
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

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3" style="color:var(--nec-black);">Electoral System Overview</h2>
                    <p class="text-muted">The electoral system of South Sudan is governed by the Transitional Constitution of the Republic of South Sudan, 2011 (as amended), the National Elections Act 2023, and regulations issued by the National Elections Commission. The system is designed to ensure free, fair, and credible elections that reflect the will of the people.</p>

                    <div class="row g-3 my-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="es-stat-tile text-center h-100">
                                <span class="stat-ico mb-2" style="background:linear-gradient(135deg,#2E8B57,#1f6b41);"><i class="fas fa-landmark"></i></span>
                                <div class="num">170</div>
                                <div class="lbl">Assembly Seats</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="es-stat-tile text-center h-100">
                                <span class="stat-ico mb-2" style="background:linear-gradient(135deg,#1a3c8f,#142c66);"><i class="fas fa-building-columns"></i></span>
                                <div class="num">30</div>
                                <div class="lbl">Council of States</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="es-stat-tile text-center h-100">
                                <span class="stat-ico mb-2" style="background:linear-gradient(135deg,#D4AF37,#b8912a);"><i class="fas fa-person-dress"></i></span>
                                <div class="num">25%</div>
                                <div class="lbl">Women Minimum</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="es-stat-tile text-center h-100">
                                <span class="stat-ico mb-2" style="background:linear-gradient(135deg,#b91c1c,#7f1414);"><i class="fas fa-percent"></i></span>
                                <div class="num">50%+1</div>
                                <div class="lbl">Presidential Threshold</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3" style="color:var(--nec-black);">Key Principles</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3 border" style="border-color:rgba(46,139,87,0.15)!important;">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Universal Suffrage</strong><br><small class="text-muted">Every citizen 18+ has the right to vote</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3 border" style="border-color:rgba(46,139,87,0.15)!important;">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>One Person, One Vote</strong><br><small class="text-muted">Each vote carries equal weight</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3 border" style="border-color:rgba(46,139,87,0.15)!important;">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Secret Ballot</strong><br><small class="text-muted">Voters cast ballots in private</small></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 p-3 bg-white rounded-3 border" style="border-color:rgba(46,139,87,0.15)!important;">
                                <div class="text-success fs-4"><i class="fas fa-check-circle"></i></div>
                                <div><strong>Independent Commission</strong><br><small class="text-muted">NEC operates independently</small></div>
                            </div>
                        </div>
                    </div>

                    <!-- Electoral System Types -->
                    <h5 class="fw-bold mt-4 mb-3" style="color:var(--nec-black);">Electoral System Types</h5>
                    <div class="card border-0 shadow-sm mt-3" style="border-radius:14px;overflow:hidden;">
                        <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 py-3 border-0">
                            <h6 class="mb-0 fw-bold" style="color:var(--nec-green);">
                                <i class="fas fa-gavel me-2"></i>How Each Office Is Elected
                            </h6>
                            <div class="d-flex gap-2">
                                <div class="input-group input-group-sm" style="max-width:220px;">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted" style="font-size:0.75rem;"></i></span>
                                    <input type="text" class="form-control border-start-0" id="eSystemSearch" placeholder="Search offices..." style="font-size:0.82rem;">
                                </div>
                                <button class="btn btn-sm btn-outline-success" id="eSystemExport" title="Export as CSV" style="font-size:0.78rem;">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="eSystemTable">
                                    <thead class="table-success">
                                        <tr>
                                            <th scope="col" class="ps-4" style="width:5%;">#</th>
                                            <th scope="col" style="width:20%;">Office</th>
                                            <th scope="col" style="width:24%;">Electoral System</th>
                                            <th scope="col" style="width:20%;">Seats</th>
                                            <th scope="col" class="pe-4" style="width:31%;">Method / Allocation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size:0.82rem;">1</td>
                                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-user-tie me-2" style="color:var(--nec-green);"></i>President</td>
                                            <td><span class="badge rounded-pill px-3 py-1 bg-success" style="font-size:0.7rem;font-weight:600;">Absolute Majority</span></td>
                                            <td><span class="badge rounded-pill px-3 py-1" style="background:rgba(46,139,87,0.12);color:#2E8B57;font-size:0.7rem;font-weight:600;">1</span></td>
                                            <td class="text-muted" style="font-size:0.83rem;">Two-round system if no candidate secures 50%+1 in the first round</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size:0.82rem;">2</td>
                                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-landmark me-2" style="color:var(--nec-green);"></i>National Assembly</td>
                                            <td><span class="badge rounded-pill px-3 py-1 bg-info text-dark" style="font-size:0.7rem;font-weight:600;">Parallel Voting</span></td>
                                            <td><span class="badge rounded-pill px-3 py-1" style="background:rgba(13,110,253,0.12);color:#0a58ca;font-size:0.7rem;font-weight:600;">170</span></td>
                                            <td class="text-muted" style="font-size:0.83rem;">85 FPTP constituencies + 85 PR (closed list) + 25% women reserved</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size:0.82rem;">3</td>
                                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-building-columns me-2" style="color:var(--nec-green);"></i>Council of States</td>
                                            <td><span class="badge rounded-pill px-3 py-1 bg-warning text-dark" style="font-size:0.7rem;font-weight:600;">Indirect</span></td>
                                            <td><span class="badge rounded-pill px-3 py-1" style="background:rgba(255,193,7,0.18);color:#997404;font-size:0.7rem;font-weight:600;">30</span></td>
                                            <td class="text-muted" style="font-size:0.83rem;">Elected by state legislative assemblies (3 per state + 2 from CAP)</td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4 text-center text-muted fw-semibold" style="font-size:0.82rem;">4</td>
                                            <td class="fw-semibold" style="font-size:0.88rem;"><i class="fas fa-user-crown me-2" style="color:var(--nec-green);"></i>State Governors</td>
                                            <td><span class="badge rounded-pill px-3 py-1 bg-danger" style="font-size:0.7rem;font-weight:600;">First-Past-the-Post</span></td>
                                            <td><span class="badge rounded-pill px-3 py-1" style="background:rgba(220,53,69,0.12);color:#b02a37;font-size:0.7rem;font-weight:600;">13</span></td>
                                            <td class="text-muted" style="font-size:0.83rem;">Simple plurality in each of the 10 states + 3 administrative areas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
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

            <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;">
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

            <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;background:#fff;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                    <h6 class="fw-bold">Need Help?</h6>
                    <p class="small text-muted mb-3">Contact the NEC electoral education team for more information.</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-success btn-sm w-100" style="border-radius:8px;">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
(function($) {
    function initSearch() {
        var $input = $('#eSystemSearch');
        var $table = $('#eSystemTable');
        if (!$input.length || !$table.length) return;
        $input.on('keyup', function() {
            var q = $(this).val().toLowerCase();
            $table.find('tbody tr').each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(q) > -1);
            });
        });
    }
    function initExportCsv() {
        var $btn = $('#eSystemExport');
        if (!$btn.length) return;
        $btn.on('click', function(e) {
            e.preventDefault();
            var rows = [];
            $('#eSystemTable thead tr').each(function() {
                var cols = [];
                $(this).find('th').each(function() { cols.push('"' + $(this).text().trim() + '"'); });
                rows.push(cols.join(','));
            });
            $('#eSystemTable tbody tr').each(function() {
                var cols = [];
                $(this).find('td').each(function() { cols.push('"' + $(this).text().trim() + '"'); });
                rows.push(cols.join(','));
            });
            var blob = new Blob([rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'NEC_Electoral_System.csv';
            link.click();
        });
    }
    $(document).ready(function() {
        initSearch();
        initExportCsv();
    });
})(jQuery);
</script>
@endsection